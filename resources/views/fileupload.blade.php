<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload — VaultScribe</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar:    #111827;
            --sidebar2:   #1a2235;
            --s-border:   rgba(255,255,255,0.07);
            --s-text:     #9ca3af;
            --s-text2:    #d1d5db;
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
            --blue-m:     #dbeafe;
            --green:      #059669;
            --green-l:    #ecfdf5;
            --red:        #dc2626;
            --red-l:      #fef2f2;
            --shadow-sm:  0 1px 2px rgba(0,0,0,0.05);
            --shadow:     0 1px 3px rgba(0,0,0,0.07), 0 1px 2px rgba(0,0,0,0.05);
            --shadow-lg:  0 10px 15px rgba(0,0,0,0.06), 0 4px 6px rgba(0,0,0,0.04);
            --radius:     10px;
            --radius-lg:  14px;
            --sidebar-w:  240px;
            --header-h:   60px;
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--gray-900); height: 100vh; overflow: hidden; display: flex; }

        /* ─── SIDEBAR ─── */
        .sidebar { width: var(--sidebar-w); background: var(--sidebar); display: flex; flex-direction: column; flex-shrink: 0; height: 100vh; position: relative; z-index: 10; }
        .sidebar-logo { height: var(--header-h); display: flex; align-items: center; gap: 10px; padding: 0 18px; border-bottom: 1px solid var(--s-border); }
        .logo-box { width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0; background: linear-gradient(135deg, #2563eb, #0891b2); display: flex; align-items: center; justify-content: center; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 13px; color: #fff; }
        .logo-name { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 16px; color: #fff; letter-spacing: -0.2px; }
        
        .nav { flex: 1; padding: 14px 10px; overflow-y: auto; display: flex; flex-direction: column; gap: 4px; }
        .nav-section { font-size: 10px; font-weight: 600; letter-spacing: 0.8px; color: #4b5563; text-transform: uppercase; padding: 10px 10px 6px; margin-top: 4px; }
        .nav-item { display: flex; align-items: center; gap: 9px; padding: 10px 12px; border-radius: 7px; color: var(--s-text); font-size: 13.5px; font-weight: 500; cursor: pointer; transition: all 0.15s; text-decoration: none; border: none; background: transparent; width: 100%; text-align: left; }
        .nav-item:hover { background: rgba(255,255,255,0.06); color: var(--s-text2); }
        .nav-item.active { background: rgba(37,99,235,0.2); color: #93c5fd; }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; opacity: 0.75; }
        .nav-item.active svg { opacity: 1; }
        
        .nav-item.logout { color: #f87171; margin-top: 4px; }
        .nav-item.logout:hover { background: rgba(239,68,68,0.1); }
        .sidebar-bottom { padding: 10px; border-top: 1px solid var(--s-border); }

        /* ─── MAIN ─── */
        .main { flex: 1; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }

        /* ─── TOPBAR ─── */
        .topbar { height: var(--header-h); background: var(--white); border-bottom: 1px solid var(--gray-200); display: flex; align-items: center; gap: 14px; padding: 0 24px; flex-shrink: 0; }
        .page-heading { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 17px; color: var(--gray-900); }
        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }
        
        .user-btn { display: flex; align-items: center; gap: 8px; padding: 4px 10px 4px 5px; background: var(--gray-100); border: 1px solid var(--gray-200); border-radius: 8px; cursor: pointer; transition: all 0.15s; }
        .user-btn:hover { background: var(--gray-200); }
        .avatar-wrap { width: 26px; height: 26px; border-radius: 7px; overflow: hidden; background: linear-gradient(135deg, #2563eb, #7c3aed); display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; color: #fff; }
        .avatar-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .user-name { font-size: 13px; font-weight: 500; color: var(--gray-700); }

        /* ─── CONTENT ─── */
        .content { flex: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; align-items: center; }

        /* ─── UPLOAD CARD ─── */
        .card { width: 100%; max-width: 650px; background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-lg); box-shadow: var(--shadow); overflow: hidden; animation: fadeUp 0.4s both; margin-top: 20px; }
        .card-head { padding: 18px 20px 16px; border-bottom: 1px solid var(--gray-100); display: flex; align-items: center; gap: 8px; }
        .card-title { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 16px; color: var(--gray-900); }
        
        /* ─── DROP ZONE ─── */
        .drop-zone { border: 2px dashed var(--gray-300); border-radius: 12px; padding: 36px 20px; display: flex; flex-direction: column; align-items: center; gap: 10px; text-align: center; transition: all 0.2s; cursor: pointer; background: var(--gray-50); margin-bottom: 20px; }
        .drop-zone.dragover { border-color: var(--blue-l); background: var(--blue-xl); }
        .drop-icon { width: 56px; height: 56px; background: var(--blue-xl); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--blue); margin-bottom: 4px; transition: transform 0.2s; }
        .drop-zone.dragover .drop-icon { transform: scale(1.1); }
        .drop-text { font-weight: 600; font-size: 15px; color: var(--gray-900); }
        .drop-sub { font-size: 12.5px; color: var(--gray-500); }
        
        .btn-upload-pick { margin-top: 8px; padding: 8px 20px; background: var(--white); color: var(--gray-700); border: 1px solid var(--gray-200); border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.15s; box-shadow: var(--shadow-sm); }
        .btn-upload-pick:hover { background: var(--gray-100); border-color: var(--gray-300); }

        /* ─── FILE LIST & STATS ─── */
        .file-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
        .file-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; background: var(--white); border: 1px solid var(--gray-200); border-radius: 8px; box-shadow: var(--shadow-sm); }
        .file-icon { font-size: 20px; }
        .file-details { flex: 1; display: flex; flex-direction: column; }
        .file-name { font-size: 13px; font-weight: 600; color: var(--gray-800); }
        .file-size { font-size: 11.5px; color: var(--gray-500); margin-top: 2px; }
        
        .upload-stats { display: flex; gap: 12px; margin-bottom: 20px; border-top: 1px solid var(--gray-100); padding-top: 20px; }
        .stat-box { flex: 1; background: var(--gray-50); border: 1px solid var(--gray-200); border-radius: 8px; padding: 12px 16px; display: flex; flex-direction: column; }
        .stat-val { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 18px; font-weight: 700; color: var(--gray-900); }
        .stat-label { font-size: 11px; font-weight: 600; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 4px; }

        /* ─── BUTTONS & ALERTS ─── */
        .mbtn { padding: 10px 20px; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13.5px; font-weight: 500; cursor: pointer; transition: all 0.16s; border: none; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; }
        .mbtn-sec { background: var(--gray-100); color: var(--gray-700); border: 1px solid var(--gray-200); }
        .mbtn-sec:hover { background: var(--gray-200); }
        .mbtn-pri { background: var(--blue); color: #fff; box-shadow: 0 2px 8px rgba(37,99,235,0.25); width: 100%; }
        .mbtn-pri:hover:not(:disabled) { background: #1d4ed8; transform: translateY(-1px); }
        .mbtn-pri:disabled { background: var(--gray-300); box-shadow: none; cursor: not-allowed; opacity: 0.7; }

        .alert-box { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 13px; font-weight: 500; display: flex; align-items: flex-start; gap: 10px; }
        .alert-success { background: var(--green-l); border: 1px solid #a7f3d0; color: var(--green); }
        .alert-error { background: var(--red-l); border: 1px solid #fecaca; color: var(--red); }
        .alert-error ul { margin-left: 20px; margin-top: 5px; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(15px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-box">V</div>
        <span class="logo-name">VaultScribe-vuln</span>
    </div>

    <nav class="nav">
        <div class="nav-section">Menu</div>
        
        <a href="{{ route('dashboard') }}" class="nav-item">
            <svg viewBox="0 0 20 20" fill="currentColor"><path d="M2 4a2 2 0 012-2h3a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V4zm9-2h3a2 2 0 012 2v12a2 2 0 01-2 2h-3a2 2 0 01-2-2V4a2 2 0 012-2z"/></svg>
            Dashboard
        </a>
        
        <a href="{{ route('notes.trash') }}" class="nav-item">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            Trash
        </a>

        <div class="nav-section">Security</div>
        @if(auth()->user()->google2fa_enabled)
            <div class="nav-item" style="color: #10b981;">
                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                2FA Enabled
            </div>
            <form method="POST" action="{{ route('2fa.disable') }}" style="margin:0;">
                @csrf
                <button type="submit" class="nav-item logout" style="color: #ef4444;">
                    <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    Disable 2FA
                </button>
            </form>
        @else
            <a href="{{ route('2fa.setup') }}" class="nav-item">
                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.65 2 6.32 2 7c0 5.225 3.34 9.67 8 11.317 4.66-1.647 8-6.092 8-11.317 0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/></svg>
                Enable 2FA
            </a>
        @endif
    </nav>

    <div class="sidebar-bottom">
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="nav-item logout">
                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/></svg>
                Logout
            </button>
        </form>
    </div>
</aside>

<main class="main">
    <header class="topbar">
        <span class="page-heading">Secure File Upload</span>
        <div class="topbar-right">
            <div class="user-btn" onclick="window.location.href='{{ route('dashboard') }}'">
                <div class="avatar-wrap">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ route('profile.photo', ['filename' => auth()->user()->profile_photo]) }}" alt="Avatar">
                    @else
                        {!! substr(auth()->user()->name, 0, 2) !!}
                    @endif
                </div>
                <span class="user-name">{!! auth()->user()->name !!}</span>
            </div>
        </div>
    </header>

    <div class="content">
        <div class="card">
            <div class="card-head">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" style="color: var(--blue);"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <span class="card-title">Upload Profile Photo</span>
            </div>
            
            <div style="padding: 24px;">
                
                @if(session('success'))
                    <div class="alert-box alert-success">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {!! session('success') !!}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-box alert-error">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <div>
                            <strong>Validation Error:</strong>
                            <ul style="margin-top: 4px; padding-left: 15px;">
                                @foreach($errors->all() as $error)
                                    <li>{!! $error !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('fileupload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf

                    <div class="drop-zone" id="dropZone">
                        <div class="drop-icon">
                            <svg width="28" height="28" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                        </div>
                        <div class="drop-text">Drag & Drop your image here</div>
                        <div class="drop-sub">Supported formats: JPG, JPEG, WEBP (Max: 5MB)</div>
                        
                        <label class="btn-upload-pick">
                            Browse Files
                            <input type="file" id="fileInput" name="file" accept=".jpg,.jpeg,.webp" style="display:none">
                        </label>
                    </div>

                    <div id="fileList" class="file-list" style="display: none;">
                        <div class="file-item">
                            <div class="file-icon">🖼️</div>
                            <div class="file-details">
                                <span class="file-name" id="fileNameDisplay">filename.jpg</span>
                                <span class="file-size" id="fileSizeDisplay">0 KB</span>
                            </div>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" style="color: var(--green);"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>

                    <div class="upload-stats">
                        <div class="stat-box">
                            <div class="stat-val" id="statCount">0</div>
                            <div class="stat-label">Files Selected</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-val" id="statSize">0 KB</div>
                            <div class="stat-label">Total Size</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-val" id="statReady" style="color: var(--gray-400);">Waiting</div>
                            <div class="stat-label">Status</div>
                        </div>
                    </div>

                    <button type="submit" id="uploadBtn" class="mbtn mbtn-pri" disabled>
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                        Upload to Vault
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const uploadBtn = document.getElementById('uploadBtn');
    
    const fileList = document.getElementById('fileList');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const fileSizeDisplay = document.getElementById('fileSizeDisplay');
    
    const statCount = document.getElementById('statCount');
    const statSize = document.getElementById('statSize');
    const statReady = document.getElementById('statReady');

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults (e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Highlight drop zone
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
    });

    // Handle dropped files
    dropZone.addEventListener('drop', function(e) {
        let dt = e.dataTransfer;
        let files = dt.files;
        fileInput.files = files; // Assign files to input
        handleFiles(files);
    });

    // Handle selected files
    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function formatBytes(bytes, decimals = 2) {
        if (!+bytes) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
    }

    function handleFiles(files) {
        if (files.length > 0) {
            const file = files[0];
            
            // Basic validation check (UI level)
            const validTypes = ['image/jpeg', 'image/jpg', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert("Please select only JPG, JPEG, or WEBP images.");
                fileInput.value = ""; 
                resetUI();
                return;
            }

            // Update UI with file info
            fileList.style.display = 'block';
            fileNameDisplay.textContent = file.name;
            
            const sizeStr = formatBytes(file.size);
            fileSizeDisplay.textContent = sizeStr;

            // Update Stats
            statCount.textContent = '1';
            statSize.textContent = sizeStr;
            
            statReady.textContent = 'Ready';
            statReady.style.color = 'var(--green)';

            // Enable button
            uploadBtn.disabled = false;
        } else {
            resetUI();
        }
    }

    function resetUI() {
        fileList.style.display = 'none';
        statCount.textContent = '0';
        statSize.textContent = '0 KB';
        statReady.textContent = 'Waiting';
        statReady.style.color = 'var(--gray-400)';
        uploadBtn.disabled = true;
    }
</script>
</body>
</html>