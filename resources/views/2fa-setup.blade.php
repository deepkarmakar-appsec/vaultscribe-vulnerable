<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup 2FA — VaultScribe</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            --gray-800:   #1f2937;
            --gray-900:   #111827;
            --blue:       #2563eb;
            --blue-l:     #3b82f6;
            --blue-xl:    #eff6ff;
            --red:        #dc2626;
            --red-l:      #fef2f2;
            --green:      #059669;
            --green-l:    #ecfdf5;
            --shadow-sm:  0 1px 2px rgba(0,0,0,0.05);
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
            max-width: 440px;
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
            text-align: center;
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
            color: var(--gray-900); margin-bottom: 8px; 
        }
        
        .auth-text { 
            font-size: 13.5px; color: var(--gray-500); 
            line-height: 1.5; margin-bottom: 24px; 
        }

        /* ─── QR & SECRET SECTION ─── */
        .qr-section {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .qr-wrapper {
            background: #fff;
            padding: 12px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: var(--shadow-sm);
            margin-bottom: 16px;
            border: 1px solid var(--gray-200);
        }
        
        /* Ensures the SVG QR code scales properly */
        .qr-wrapper svg {
            width: 160px;
            height: 160px;
            display: block;
        }

        .secret-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .secret-box {
            background: var(--white);
            border: 1px dashed var(--gray-300);
            padding: 10px 14px;
            border-radius: 8px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 15px;
            font-weight: 700;
            color: var(--gray-800);
            letter-spacing: 1px;
            word-break: break-all;
        }

        /* ─── FORM ELEMENTS ─── */
        .form-input { 
            width: 100%; padding: 14px 16px; 
            background: var(--white); 
            border: 1px solid var(--gray-200); 
            border-radius: 10px; 
            font-family: 'Inter', sans-serif; 
            font-size: 18px; 
            font-weight: 600;
            letter-spacing: 3px;
            text-align: center;
            color: var(--gray-900); 
            outline: none; transition: all 0.2s; 
            margin-bottom: 20px; 
        }
        .form-input::placeholder { color: var(--gray-300); letter-spacing: normal; font-weight: 500; font-size: 14px;}
        .form-input:focus { border-color: var(--blue-l); box-shadow: 0 0 0 4px rgba(59,130,246,0.12); }

        .mbtn-pri { 
            width: 100%; padding: 14px; 
            border-radius: 10px; font-family: 'Inter', sans-serif; 
            font-size: 14.5px; font-weight: 600; cursor: pointer; 
            transition: all 0.2s; border: none; 
            background: var(--blue); color: #fff; 
            box-shadow: 0 4px 12px rgba(37,99,235,0.25); 
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .mbtn-pri:hover { background: #1d4ed8; transform: translateY(-2px); box-shadow: 0 6px 16px rgba(37,99,235,0.3); }
        .mbtn-pri:active { transform: translateY(0); }

        .back-link {
            display: inline-block;
            margin-top: 16px;
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-500);
            text-decoration: none;
            transition: color 0.15s;
        }
        .back-link:hover {
            color: var(--gray-900);
        }

        /* ─── ERRORS ─── */
        .alert-error { 
            background: var(--red-l); 
            border: 1px solid #fecaca; 
            color: var(--red); 
            padding: 12px 14px; 
            border-radius: 8px; 
            margin-bottom: 20px; 
            font-size: 13px; 
            font-weight: 500; 
            display: flex; align-items: flex-start; gap: 8px;
            text-align: left;
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
            <svg width="28" height="28" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.65 2 6.32 2 7c0 5.225 3.34 9.67 8 11.317 4.66-1.647 8-6.092 8-11.317 0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/>
            </svg>
        </div>

        <h2 class="card-title">Setup Two-Factor Auth</h2>
        <p class="auth-text">
            Scan the QR code below using Google Authenticator or Authy to secure your account.
        </p>

        @if ($errors->any())
            <div class="alert-error">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink: 0;"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span>{!! $errors->first() !!}</span>
            </div>
        @endif

        <div class="qr-section">
            <div class="qr-wrapper">
                {!! $QR_Image !!}
            </div>
            
            <div class="secret-label">Manual Setup Key</div>
            <div class="secret-box">
                {!! $secret !!}
            </div>
        </div>

        <form method="POST" action="{{ route('2fa.enable') }}">
            @csrf

            <input
                type="text"
                name="one_time_password"
                class="form-input"
                maxlength="6"
                pattern="\d{6}"
                placeholder="Enter 6-digit OTP"
                autocomplete="off"
                inputmode="numeric"
                required>

            <button type="submit" class="mbtn-pri">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Enable 2FA Protection
            </button>
        </form>

        <a href="{{ route('dashboard') }}" class="back-link">← Go back to Dashboard</a>

    </div>
</div>

<script>
    // Numbers only & auto-submit script
    document.querySelector('input[name="one_time_password"]').addEventListener('input', function (e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        
        if (this.value.length === 6) {
            setTimeout(() => {
                this.closest('form').submit();
            }, 300);
        }
    });
</script>

</body>
</html>