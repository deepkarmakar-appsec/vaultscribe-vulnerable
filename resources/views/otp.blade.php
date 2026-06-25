<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification — VaultScribe</title>
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
            --gray-900:   #111827;
            --blue:       #2563eb;
            --blue-l:     #3b82f6;
            --blue-xl:    #eff6ff;
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

        /* ─── FORM ELEMENTS ─── */
        .form-input { 
            width: 100%; padding: 14px 16px; 
            background: var(--gray-50); 
            border: 1px solid var(--gray-200); 
            border-radius: 10px; 
            font-family: 'Inter', sans-serif; 
            font-size: 22px; 
            font-weight: 600;
            letter-spacing: 6px;
            text-align: center;
            color: var(--gray-900); 
            outline: none; transition: all 0.2s; 
            margin-bottom: 20px; 
        }
        .form-input::placeholder { color: var(--gray-300); letter-spacing: 6px; font-weight: 500;}
        .form-input:focus { background: #fff; border-color: var(--blue-l); box-shadow: 0 0 0 4px rgba(59,130,246,0.12); }

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
                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
            </svg>
        </div>

        <h2 class="card-title">Email Verification</h2>
        <p class="auth-text">
            We've sent a 6-digit verification code to your email. Please enter it below.
        </p>

        @if ($errors->has('otp'))
            <div class="alert-error">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink: 0;"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span>{!! $errors->first('otp') !!}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('otp.verify.post') }}">
            @csrf

            <input
                type="text"
                name="otp"
                class="form-input"
                maxlength="4"
                pattern="[0-9]{4}"
                placeholder="0000"
                autocomplete="one-time-code"
                inputmode="numeric"
                required
                autofocus>

            <button type="submit" class="mbtn-pri">
                Verify OTP
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </button>
        </form>

    </div>
</div>

<script>
    // Automatically submit the form when 6 digits are entered
    document.querySelector('input[name="otp"]').addEventListener('input', function (e) {
        // Allow only numbers
        this.value = this.value.replace(/[^0-9]/g, '');
        
        if (this.value.length === 6) {
            // Give a slight delay (300ms) for better UX before auto-submitting
            setTimeout(() => {
                this.closest('form').submit();
            }, 300);
        }
    });
</script>

</body>
</html>