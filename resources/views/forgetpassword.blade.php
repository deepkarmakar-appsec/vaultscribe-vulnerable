<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — VaultScribe</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:         #f8fafc;
            --white:      #ffffff;
            --gray-50:    #f9fafb;
            --gray-100:   #f3f4f6;
            --gray-200:   #e5e7eb;
            --gray-300:   #d1d5db;
            --gray-400:   #9ca3af;
            --gray-500:   #6b7280;
            --gray-700:   #374151;
            --gray-900:   #111827;
            --blue:       #2563eb;
            --blue-l:     #3b82f6;
            --blue-xl:    #eff6ff;
            --green:      #059669;
            --green-l:    #ecfdf5;
            --red:        #dc2626;
            --red-l:      #fef2f2;
            --shadow-md:  0 4px 6px rgba(0,0,0,0.05), 0 2px 4px rgba(0,0,0,0.04);
            --shadow-lg:  0 10px 25px rgba(0,0,0,0.05), 0 4px 10px rgba(0,0,0,0.03);
            --radius-lg:  16px;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--bg); 
            color: var(--gray-900); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
            animation: fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        /* ─── LOGO & BRANDING ─── */
        .brand-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 24px;
        }
        .logo-box { 
            width: 36px; height: 36px; border-radius: 10px; 
            background: linear-gradient(135deg, #2563eb, #0891b2); 
            display: flex; align-items: center; justify-content: center; 
            font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 16px; color: #fff; 
            box-shadow: 0 4px 12px rgba(37,99,235,0.25);
        }
        .logo-name { 
            font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; 
            font-size: 20px; color: var(--gray-900); letter-spacing: -0.3px; 
        }

        /* ─── CARD ─── */
        .card { 
            background: var(--white); 
            border: 1px solid var(--gray-200); 
            border-radius: var(--radius-lg); 
            box-shadow: var(--shadow-lg); 
            padding: 36px 32px; 
        }

        .icon-wrap {
            width: 56px; height: 56px;
            background: var(--blue-xl);
            color: var(--blue);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
        }

        .card-title { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            font-weight: 700; font-size: 20px; 
            color: var(--gray-900); margin-bottom: 6px; 
            text-align: center;
        }
        
        .auth-text { 
            font-size: 13.5px; color: var(--gray-500); 
            line-height: 1.5; margin-bottom: 24px; 
            text-align: center;
        }

        /* ─── FORM ELEMENTS ─── */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 6px;
        }

        .form-input { 
            width: 100%; padding: 12px 14px; 
            background: var(--gray-50); 
            border: 1px solid var(--gray-200); 
            border-radius: 8px; 
            font-family: 'Inter', sans-serif; 
            font-size: 14px; 
            color: var(--gray-900); 
            outline: none; transition: all 0.2s; 
        }
        .form-input::placeholder { color: var(--gray-400); }
        .form-input:focus { background: #fff; border-color: var(--blue-l); box-shadow: 0 0 0 3px rgba(59,130,246,0.12); }

        .mbtn-pri { 
            width: 100%; padding: 12px; 
            border-radius: 8px; font-family: 'Inter', sans-serif; 
            font-size: 14px; font-weight: 600; cursor: pointer; 
            transition: all 0.2s; border: none; 
            background: var(--blue); color: #fff; 
            box-shadow: 0 4px 12px rgba(37,99,235,0.25); 
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: 20px;
        }
        .mbtn-pri:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(37,99,235,0.3); }
        .mbtn-pri:active { transform: translateY(0); }

        /* ─── ALERTS ─── */
        .alert-box { 
            padding: 12px 14px; 
            border-radius: 8px; 
            margin-bottom: 20px; 
            font-size: 13px; 
            font-weight: 500; 
            display: flex; align-items: flex-start; gap: 8px;
        }
        .alert-error {
            background: var(--red-l); 
            border: 1px solid #fecaca; 
            color: var(--red); 
        }
        .alert-success {
            background: var(--green-l); 
            border: 1px solid #a7f3d0; 
            color: var(--green); 
        }

        /* ─── FOOTER LINKS ─── */
        .footer-links {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 24px;
            font-size: 13px;
            font-weight: 500;
        }
        .footer-links a {
            color: var(--blue);
            text-decoration: none;
            transition: color 0.2s;
            font-weight: 600;
        }
        .footer-links a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        /* ─── ANIMATIONS ─── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>
</head>
<body>

<div class="auth-container">
    
    <div class="brand-header">
        <div class="logo-box">V</div>
        <span class="logo-name">VaultScribe-vuln</span>
    </div>

    <div class="card">

        <div class="icon-wrap">
            <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
        </div>
        
        <h2 class="card-title">Forgot Password?</h2>
        <p class="auth-text">No worries! Enter your email address and we'll send you a secure link to reset it.</p>

        @if ($errors->any())
            <div class="alert-box alert-error">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink: 0;"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span>{!! $errors->first() !!}</span>
            </div>
        @endif

        @if(session('status'))
            <div class="alert-box alert-success">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink: 0;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.forget.post') }}">
            @csrf

            <div class="form-group" style="margin-bottom: 8px;">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-input" placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
            </div>

            <button type="submit" class="mbtn-pri">
                Send Reset Link
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
            </button>
        </form>

        <div class="footer-links">
            <a href="{{ route('login') }}">← Back to Login</a>
        </div>

    </div>
</div>

</body>
</html>