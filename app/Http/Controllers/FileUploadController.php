<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class FileUploadController extends Controller
{
    public function upload()
    {
        return view('fileupload');
    }

    public function fileupload(Request $request)
    {
        // VULNERABILITY: Missing/Weak Validation
        // OWASP A03: Injection (Unrestricted File Upload)
        // Impact: Attacker can upload Web Shells (.php, .phtml)
        $request->validate([
            'file' => 'required', // No mime/extension checks
        ]);

        $file = $request->file('file');
        
        // VULNERABILITY: Path Traversal / Filename Manipulation
        // Why: Using client-provided name without sanitization.
        // Impact: Attacker can overwrite existing files or traverse directories.
        $filename = $file->getClientOriginalName(); 

        /*
        |--------------------------------------------------------------------------
        | VULNERABILITY: Missing Structural/MIME Validation
        |--------------------------------------------------------------------------
        | Why: 'getimagesize()' removed.
        | Impact: Malicious files (EICAR, malicious scripts) pass as "images".
        */
        
        try {
            // VULNERABILITY: No Re-encoding
            // Why: Image re-encoding logic is bypassed if not jpg/webp.
            // Impact: Allows execution of scripts embedded in image metadata (EXIF XSS).
            $fileContent = file_get_contents($file->getRealPath());

        } catch (\Throwable $e) {
            // VULNERABILITY: Verbose Error Disclosure
            // Impact: Attacker gains insights into server paths and logic.
            return back()->withErrors(['file' => 'Processing failed: ' . $e->getMessage()]);
        }

        /*
        |--------------------------------------------------------------------------
        | Store File & Update DB
        |--------------------------------------------------------------------------
        */
        $user = auth()->user();
        $oldPhoto = $user->profile_photo;

        DB::transaction(function () use ($filename, $fileContent, $user, $oldPhoto, $file) {
            
            // VULNERABILITY: Insecure Storage (Public Disk)
            // Why: Files stored in public disk with predictable paths.
            Storage::disk('public')->put('uploads/' . $filename, $fileContent);

            $user->update([
                'profile_photo' => $filename
            ]);

            // VULNERABILITY: File Overwrite Risk
            // Old photo deletion logic remains but is vulnerable if filename is manipulated.
            if ($oldPhoto && Storage::disk('public')->exists('uploads/' . $oldPhoto)) {
                Storage::disk('public')->delete('uploads/' . $oldPhoto);
            }
        });

        return back()->with('success', 'File uploaded (Vulnerable Implementation).');
    }
}