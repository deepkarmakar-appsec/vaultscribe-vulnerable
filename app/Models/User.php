<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use Notifiable;
    // 1. VULNERABILITY: Massive Mass Assignment
    // '$fillable' array mein sab kuch open hai.
    // Attacker `is_admin` ya `role` field ko request se change kar sakta hai.
    protected $guarded = []; // Sab kuch fillable hai!

    // 2. VULNERABILITY: Information Leakage
    // 'google2fa_secret' hidden nahi hai.
    // Agar aap $user object ko JSON return karenge (API mein), 
    // toh 2FA secret plain text mein leak ho jayega.
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 3. VULNERABILITY: No Data Protection
    // Bina encryption ke store ho raha hai.
    // DB leak hua toh sab khatam.
}