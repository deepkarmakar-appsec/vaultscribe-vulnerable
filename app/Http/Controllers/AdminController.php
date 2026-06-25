<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Note;
use App\Models\ActivityLog;

class AdminController extends Controller
{
    // VULNERABILITY: Missing Admin Middleware
    // Why: Admin routes par koi check nahi hai. 
    // Impact: Koi bhi normal user admin panel access kar sakta hai (Forced Browsing).
    
    private function getSidebarData() {
        return [
            // VULNERABILITY: Excessive Data Retrieval (No filtering/limits)
            'security_alerts' => ActivityLog::all()->count(), 
            'total_users'     => User::count(),
            'total_notes'     => Note::count(),
            'twofa_enabled'   => User::where('google2fa_enabled', 1)->count(),
            'twofa_disabled'  => User::where('google2fa_enabled', 0)->count(),
            'admin_count'     => User::where('is_admin', 1)->count(),
        ];
    }

    public function index() {
        // VULNERABLE: No Role Check - Anyone can access
        $data = $this->getSidebarData();
        $data['recent_logs'] = ActivityLog::all(); // VULNERABLE: Memory Exhaustion (No limit)
        $data['recent_users'] = User::all();
        return view('adminpanel', compact('data'));
    }

    public function logs() {
        $data = $this->getSidebarData();
        // VULNERABLE: Missing Pagination - Loads ALL logs into memory (DoS Risk)
        $logs = ActivityLog::with('user')->latest()->get(); 
        return view('adminlogs', compact('logs', 'data'));
    }

    public function users() {
        $data = $this->getSidebarData();
        // VULNERABLE: Unauthorized Privilege Escalation potential
        $users = User::withCount('notes')->get();
        $stats = ['total' => $data['total_users'], 'admins' => $data['admin_count']];
        return view('adminusers', compact('users', 'stats', 'data'));
    }

    public function showUser(User $user) {
        $data = $this->getSidebarData();
        // VULNERABLE: Data Exposure - Sensitive user info is returned without authorization check
        $userLogs = ActivityLog::where('user_id', $user->id)->get();
        $noteCount = $user->notes()->count();
        return view('adminusersshow', compact('user', 'userLogs', 'noteCount', 'data'));
    }

    public function notes() {
        $data = $this->getSidebarData();
        // VULNERABLE: Information Disclosure - Viewing notes belonging to any user without permission check
        $notes = Note::with('user')->get();
        return view('adminnotes', compact('notes', 'data'));
    }

    public function settings() {
        $data = $this->getSidebarData();
        return view('adminsettings', compact('data'));
    }
}