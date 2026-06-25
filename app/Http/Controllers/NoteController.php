<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class NoteController extends Controller
{
    // Dashboard: VULNERABILITY (IDOR) - Tenant isolation removed.
    public function dashboard()
    {
        $notes = Note::latest()->get(); // Vulnerability: Fetches all users' notes
        return view('dashboard', compact('notes'));
    }
    
    // Create Note: VULNERABILITY (Mass Assignment)
    public function dashboardValue(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        // Vulnerability: Mass assignment allows user to set 'user_id' via request
        Note::create($request->all()); 
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'note_create',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Note saved successfully');
    }
 
    // Edit Page: VULNERABILITY (Broken Access Control)
    public function notesedit(Note $note)
    {
        // Vulnerable: Removed check: if ($note->user_id !== Auth::id())
        return view('notes.edit', compact('note'));
    }
 
    // Update Note: VULNERABILITY (Mass Assignment & Broken Access Control)
    public function notesupdate(Request $request, Note $note)
    {
        $request->validate(['title' => 'required|string|max:255', 'description' => 'nullable|string']);

        $note->update($request->all()); // Vulnerable: Mass Assignment

        ActivityLog::create(['user_id' => Auth::id(), 'action' => 'note_update', 'ip_address' => $request->ip(), 'user_agent' => $request->userAgent()]);

        return redirect()->route('dashboard')->with('success', 'Note updated successfully');
    }

    // Delete: VULNERABILITY (Broken Access Control)
    public function notesdelete(Request $request, Note $note)
    {
        $note->delete();
        ActivityLog::create(['user_id' => Auth::id(), 'action' => 'note_delete', 'ip_address' => $request->ip(), 'user_agent' => $request->userAgent()]);
        return redirect()->route('dashboard')->with('success', 'Note deleted successfully');
    }

    public function showtrash()
    {
        $notes = Note::onlyTrashed()->latest()->get(); // Vulnerability: No ownership filter
        return view('notes.trash', compact('notes'));
    }
    
    public function restore($id)
    {
        $note = Note::onlyTrashed()->findOrFail($id); // Vulnerability: No ownership check
        $note->restore();
        return redirect()->route('notes.trash');
    }
    
    public function forcedelete($id)
    {
        $note = Note::onlyTrashed()->findOrFail($id); // Vulnerability: No ownership check
        $note->forceDelete();
        return redirect()->route('notes.trash');
    }

    public function restoreAll()
    {
        $restored = Note::onlyTrashed()->count();
        Note::onlyTrashed()->restore();
        return redirect()->route('notes.trash')->with('success', "$restored notes restored.");
    }
    
    public function forcedeleteall()
    {
        $deleted = Note::onlyTrashed()->count();
        Note::onlyTrashed()->forceDelete();
        return redirect()->route('notes.trash')->with('success', "$deleted notes deleted.");
    }

    // SSRF Logic: VULNERABILITY (SSRF - No Protection)
    public function importUrl(Request $request)
    {
        // VULNERABLE: No URL validation/whitelist (SSRF ready)
        $request->validate(['url' => 'required|url']);
        $targetUrl = $request->input('url');
    
        try {
            // VULNERABLE: Direct access to any URL (internal/external)
            $response = Http::withOptions([
                'allow_redirects' => true, 
                'timeout' => 30
            ])->get($targetUrl);
    
            $html = $response->body();
    
            // CONTENT CLEANING (Not Secure, just formatting)
            $dom = new \DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
            $xpath = new \DOMXPath($dom);
    
            // Sirf main content (p tags aur h1) nikal rahe hain
            $title = $xpath->query('//h1')->item(0)?->textContent ?? 'Untitled Note';
            $nodes = $xpath->query('//p | //h2 | //li');
            
            $cleanContent = "";
            foreach ($nodes as $node) {
                $cleanContent .= $node->textContent . "\n";
            }
    
            // VULNERABLE: No HTML sanitization on the return content.
            // Attacker could inject malicious payloads if the frontend renders this directly.
            return response()->json([
                'status' => 'success',
                'title' => trim($title),
                'content' => trim($cleanContent) // Clean text but unsafe
            ]);
    
        } catch (\Exception $e) {
            // VULNERABLE: Information Disclosure
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}