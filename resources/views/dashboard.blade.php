<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VaultScribe — Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            --amber:      #d97706;
            --red:        #dc2626;
            --red-l:      #fef2f2;
            --shadow-sm:  0 1px 2px rgba(0,0,0,0.05);
            --shadow:     0 1px 3px rgba(0,0,0,0.07), 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md:  0 4px 6px rgba(0,0,0,0.05), 0 2px 4px rgba(0,0,0,0.04);
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
        .nav-item svg, .nav-item i { width: 16px; height: 16px; flex-shrink: 0; opacity: 0.75; text-align: center;}
        .nav-item.active svg, .nav-item.active i { opacity: 1; }
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
        .content { flex: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 22px; }

        /* ─── FORMS & INPUTS ─── */
        .form-card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-lg); box-shadow: var(--shadow); padding: 20px; animation: fadeUp 0.4s both; }
        .form-title { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 15px; color: var(--gray-900); margin-bottom: 15px; display: flex; align-items: center; gap: 8px;}
        .form-input { width: 100%; padding: 10px 14px; background: var(--gray-50); border: 1px solid var(--gray-200); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px; color: var(--gray-900); outline: none; transition: all 0.18s; margin-bottom: 12px; }
        .form-input:focus { background: #fff; border-color: var(--blue-l); box-shadow: 0 0 0 3px rgba(59,130,246,0.12); }
        .form-textarea { resize: none; overflow: hidden; min-height: 100px; line-height: 1.5; }
        .card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 8px; }
        
        .mbtn { padding: 8px 18px; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.16s; border: none; }
        .mbtn-sec { background: var(--gray-100); color: var(--gray-700); border: 1px solid var(--gray-200); }
        .mbtn-sec:hover { background: var(--gray-200); transform: translateY(-1px); }
        .mbtn-pri { background: var(--blue); color: #fff; box-shadow: 0 2px 8px rgba(37,99,235,0.25); }
        .mbtn-pri:hover { background: #1d4ed8; transform: translateY(-1px); }
        .mbtn-danger { background: var(--red); color: #fff; box-shadow: 0 2px 8px rgba(220,38,38,0.25); }
        .mbtn-danger:hover { background: #b91c1c; transform: translateY(-1px); }

        /* ─── STAT CARDS ─── */
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; }
        .stat-card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-lg); padding: 18px 20px; box-shadow: var(--shadow); display: flex; align-items: flex-start; gap: 14px; transition: all 0.2s; animation: fadeUp 0.4s both; }
        .stat-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-2px); }
        .stat-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .si-blue { background: var(--blue-xl); color: var(--blue); }
        .si-green { background: var(--green-l); color: var(--green); }
        .stat-label { font-size: 12px; color: var(--gray-500); margin-bottom: 4px; font-weight: 500; }
        .stat-num { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 26px; color: var(--gray-900); line-height: 1.1; }

        /* ─── NOTES GRID ─── */
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; animation: fadeUp 0.4s 0.1s both; }
        .notes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
        .note-item { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius); padding: 16px; display: flex; flex-direction: column; gap: 8px; transition: all 0.18s; position: relative; box-shadow: var(--shadow-sm); animation: fadeUp 0.4s both; }
        .note-item:hover { border-color: #bfdbfe; box-shadow: 0 4px 12px rgba(59,130,246,0.08); transform: translateY(-2px); }
        .note-title { font-weight: 600; font-size: 14px; color: var(--gray-900); line-height: 1.35; }
        .note-preview { font-size: 12.5px; color: var(--gray-500); line-height: 1.55; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; flex-grow: 1; margin-bottom: 8px; }
        .note-foot { display: flex; align-items: center; justify-content: space-between; margin-top: auto; padding-top: 10px; border-top: 1px solid var(--gray-100); }
        .note-date { font-size: 11px; color: var(--gray-400); font-weight: 500; }
        .note-btns { display: flex; gap: 6px; }
        .btn-edit, .btn-del { font-size: 11.5px; font-weight: 500; padding: 4px 10px; border-radius: 5px; cursor: pointer; transition: all 0.15s; text-decoration: none; border: 1px solid transparent; background: transparent; }
        .btn-edit { color: var(--blue); background: var(--blue-xl); border-color: var(--blue-m); }
        .btn-edit:hover { background: var(--blue-m); }
        .btn-del { color: var(--red); background: var(--red-l); border-color: #fecaca; }
        .btn-del:hover { background: #fee2e2; }

        /* ─── MODAL CSS ─── */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.45);
            display: flex; align-items: center; justify-content: center; z-index: 1000;
            opacity: 0; pointer-events: none; transition: opacity 0.22s;
            backdrop-filter: blur(3px);
        }
        .modal-overlay.open { opacity: 1; pointer-events: all; }
        .modal-box {
            background: #fff; border-radius: 16px; width: 440px; max-width: 95vw;
            box-shadow: 0 24px 60px rgba(0,0,0,0.18);
            transform: translateY(18px) scale(0.97); transition: transform 0.25s cubic-bezier(.34,1.56,.64,1);
        }
        .modal-overlay.open .modal-box { transform: translateY(0) scale(1); }
        .modal-head {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 20px 14px; border-bottom: 1px solid var(--gray-200);
        }
        .modal-title { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 15px; color: var(--gray-900); }
        .modal-close { background: none; border: none; font-size: 16px; color: var(--gray-400); cursor: pointer; padding: 2px 6px; border-radius: 5px; transition: all 0.15s; }
        .modal-close:hover { background: var(--gray-100); color: var(--gray-700); }
        .modal-body { padding: 18px 20px; }
        .modal-foot { display: flex; justify-content: flex-end; gap: 8px; padding: 14px 20px; border-top: 1px solid var(--gray-100); }
        .form-group { margin-bottom: 14px; }
        .form-label { display: block; font-size: 12px; font-weight: 600; color: var(--gray-700); margin-bottom: 6px; }

        /* ─── TOAST NOTIFICATION ─── */
        .toast { position: fixed; bottom: 24px; right: 24px; z-index: 2000; background: var(--gray-900); color: #fff; font-size: 13px; font-weight: 500; padding: 12px 20px; border-radius: 9px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); transform: translateY(20px); opacity: 0; transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55); pointer-events: none; display: flex; align-items: center; gap: 8px; }
        .toast.show { transform: translateY(0); opacity: 1; }

        /* ─── ANIMATIONS ─── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(15px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 4px; }

        .note-item {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius);
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    position: relative;
    box-shadow: var(--shadow-sm);
    animation: fadeUp 0.4s both;
    
    /* FIX 1: Smooth performance */
    will-change: transform;
    backface-visibility: hidden;
    transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s;
}
.note-item:hover {
    border-color: #bfdbfe;
    box-shadow: 0 8px 20px rgba(59,130,246,0.15);
    
    /* FIX 2: Stabilize the zoom */
    transform: translateY(-4px) scale(1.01); 
}

.note-view-modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.65);
    display:flex;
    justify-content:center;
    align-items:center;
    z-index:9999;

    opacity:0;
    pointer-events:none;
    transition:.3s ease;
}

.note-view-modal.show{
    opacity:1;
    pointer-events:auto;
}

.note-view-box{
    width:800px;
    max-width:95vw;
    max-height:90vh;

    overflow-y:auto;
    overflow-x:hidden;

    background:#fff;
    border-radius:20px;
    padding:30px;

    scrollbar-width:thin;
    -webkit-overflow-scrolling:touch;
}
.note-view-content{
    line-height:1.9;
    white-space:pre-wrap;
    word-break:break-word;
    overflow-wrap:break-word;
    color:#374151;
}
.note-view-modal.show .note-view-box{
    transform:scale(1);
}

.note-view-title{
    font-size:24px;
    font-weight:700;
    margin-bottom:15px;
}

.note-view-content{
    line-height:1.9;
    white-space:pre-wrap;
    color:#374151;
}
    </style>
</head>
<body>

<div id="noteViewer" class="note-view-modal">

    <div class="note-view-box">

        <div class="note-view-title"
             id="noteViewerTitle">
        </div>

        <hr style="margin-bottom:20px;">

        <div class="note-view-content"
             id="noteViewerContent">
        </div>

    </div>

</div>

<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-box">V</div>
        <span class="logo-name">VaultScribe-vuln</span>
    </div>

    <nav class="nav">
        <div class="nav-section">Menu</div>
        <a href="{{ route('dashboard') }}" class="nav-item active">
            <i class="fa-solid fa-file-lines"></i> Notes
        </a>
        <a href="#" class="nav-item">
            <i class="fa-regular fa-star"></i> Important
        </a>
        <a href="{{ route('notes.trash') }}" class="nav-item">
            <i class="fa-regular fa-trash-can"></i> Trash
        </a>

        <div class="nav-section">Security</div>
        {{-- 2FA Section --}}
        @if(auth()->user()->google2fa_enabled)
            <div class="nav-item" style="color: #10b981;">
                <i class="fa-solid fa-shield-check"></i> 2FA Enabled
            </div>
            <form method="POST" action="{{ route('2fa.disable') }}" style="margin:0;">
                @csrf
                <button type="submit" class="nav-item" style="color: #ef4444;">
                    <i class="fa-solid fa-shield-xmark"></i> Disable 2FA
                </button>
            </form>
        @else
            <a href="{{ route('2fa.setup') }}" class="nav-item">
                <i class="fa-solid fa-shield-halved"></i> Enable 2FA
            </a>
        @endif
    </nav>

    <div class="sidebar-bottom">
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="nav-item logout">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </div>
</aside>

<main class="main">
    <header class="topbar">
        <span class="page-heading">Dashboard</span>
        <div class="topbar-right">
            <div class="user-btn" onclick="window.location.href='{{ route('upload') }}'">
                <div class="avatar-wrap">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ route('profile.photo', ['filename' => auth()->user()->profile_photo]) }}" alt="Avatar">
                    @else
                        {{ substr(auth()->user()->name, 0, 2) }}
                    @endif
                </div>
                <span class="user-name">{{ auth()->user()->name }}</span>
            </div>
        </div>
    </header>

    <div class="content">

        <div class="stats-row">
            <div class="stat-card" style="animation-delay: 0s;">
                <div class="stat-icon si-blue"><i class="fa-solid fa-file-lines"></i></div>
                <div>
                    <div class="stat-label">Total Notes</div>
                    <div class="stat-num" data-to="{{ $notes->count() }}">0</div>
                </div>
            </div>
            <div class="stat-card" style="animation-delay: 0.1s;">
                <div class="stat-icon si-green"><i class="fa-solid fa-shield-halved"></i></div>
                <div>
                    <div class="stat-label">Security Score</div>
                    <div class="stat-num" data-to="98" style="color:var(--green)">0</div>
                </div>
            </div>
        </div>

        <section class="form-card" style="animation-delay: 0.2s;">
            <div class="form-title">
                <i class="fa-solid fa-pen-nib" style="color: var(--blue);"></i> Create a New Note
            </div>
            <form id="noteForm" method="POST" action="{{ route('notes.store') }}">
    @csrf

    <input
        name="title"
        type="text"
        placeholder="Write the title..."
        class="form-input">

    <textarea
        name="description"
        placeholder="Write something meaningful..."
        class="form-input form-textarea"></textarea>

    <!-- Required because notes.user_id is NOT NULL -->
    <input
        type="hidden"
        name="user_id"
        value="{{ auth()->id() }}">

    <div class="card-footer">
        <span style="font-size:11px;color:var(--gray-400);font-weight:500;">
            [Ctrl + S to save]
        </span>

        <div style="display:flex;gap:10px;">
            <button
                type="button"
                class="mbtn mbtn-sec"
                onclick="openModal('modal-url')">
                <i class="fa-solid fa-link"></i> Import URL
            </button>

            <button
                type="button"
                id="aiGenerateBtn"
                class="mbtn mbtn-sec">
                ✦ AI Summary
            </button>

            <button
                type="submit"
                class="mbtn mbtn-pri">
                Save Note
            </button>
        </div>
    </div>
</form>
        </section>

        <section>
            <div class="section-header">
                <span class="page-heading" style="font-size: 16px;">My Notes</span>
            </div>
            
            <div class="notes-grid">
                @forelse($notes as $index => $note)
                <div class="note-item"
     onclick="openNoteModal(
        `{{ addslashes($note->title) }}`,
        `{{ addslashes($note->description) }}`
     )"
     style="animation-delay: {{ 0.2 + ($index * 0.05) }}s;">
                    <div class="note-title">{!! $note->title !!}</div>
                    <div class="note-preview">{!! $note->description !!}</div>
                    <div class="note-foot">
                        <span class="note-date">{!! $note->created_at->diffForHumans() !!}</span>
                        <div class="note-btns">
                        <a href="{{ route('notes.edit', $note->id) }}"
   class="btn-edit"
   onclick="event.stopPropagation();">
   Edit
</a>
                            
                            <form id="delete-form-{{ $note->id }}" action="{{ route('notes.delete', $note) }}" method="POST" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="button"
        class="btn-del"
        onclick="event.stopPropagation(); confirmDelete('delete-form-{{ $note->id }}')">
    Delete
</button>
                            </form>

                        </div>
                    </div>
                </div>
                @empty
                <div class="note-item" style="align-items: center; justify-content: center; min-height: 150px; border: 2px dashed var(--gray-200); background: transparent; box-shadow: none;">
                    <i class="fa-regular fa-folder-open" style="font-size: 24px; color: var(--gray-400); margin-bottom: 8px;"></i>
                    <span style="color: var(--gray-500); font-size: 13px;">No notes found. Create your first one above!</span>
                </div>
                @endforelse
            </div>
        </section>

    </div>
</main>

<div id="modal-url" class="modal-overlay" onclick="closeModal('modal-url')">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="modal-head">
      <div class="modal-title">Import Note From URL</div>
      <button type="button" class="modal-close" onclick="closeModal('modal-url')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">URL</label>
        <input type="url" class="form-input" id="urlInput" placeholder="https://example.com/article...">
      </div>
    </div>
    <div class="modal-foot">
      <button type="button" class="mbtn mbtn-sec" onclick="closeModal('modal-url')">Cancel</button>
      <button type="button" class="mbtn mbtn-pri" onclick="importURLDummy()">Import Note</button>
    </div>
  </div>
</div>

<div id="modal-delete" class="modal-overlay" onclick="closeModal('modal-delete')">
  <div class="modal-box" onclick="event.stopPropagation()" style="width: 400px;">
    <div class="modal-head" style="border-bottom: none; padding-bottom: 0;">
      <div class="modal-title" style="display: flex; align-items: center; gap: 8px; color: var(--red);">
        <i class="fa-solid fa-triangle-exclamation"></i> Confirm Deletion
      </div>
      <button type="button" class="modal-close" onclick="closeModal('modal-delete')">✕</button>
    </div>
    <div class="modal-body">
      <p style="font-size: 13.5px; color: var(--gray-600); line-height: 1.5;">
        Are you sure you want to delete this note? It will be moved to the Trash where you can restore it later.
      </p>
    </div>
    <div class="modal-foot">
      <button type="button" class="mbtn mbtn-sec" onclick="closeModal('modal-delete')">Cancel</button>
      <button type="button" class="mbtn mbtn-danger" onclick="executeDelete()">Yes, Delete</button>
    </div>
  </div>
</div>

<div id="toast" class="toast">
    <i class="fa-solid fa-circle-check"></i> <span id="toast-msg"></span>
</div>

<script>
// ── Toast Notification Logic ──
function showToast(msg, color = '#111827') {
    const t = document.getElementById('toast');
    document.getElementById('toast-msg').innerText = msg;
    t.style.background = color;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}

@if(session('success'))
    document.addEventListener('DOMContentLoaded', () => showToast("{{ session('success') }}", '#059669'));
@endif

// ── Modal Generic Logic ──
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

// ── Note Viewer (Modal) Logic ──
const noteViewer = document.getElementById('noteViewer');

function openNoteModal(title, content) {
    document.getElementById('noteViewerTitle').innerText = title;
    document.getElementById('noteViewerContent').innerText = content;
    
    document.body.style.overflow = 'hidden'; // Scroll lock
    noteViewer.classList.add('show');
}

function closeNoteModal() {
    noteViewer.classList.remove('show');
    document.body.style.overflow = ''; // Scroll unlock
}

// Click outside to close
noteViewer.addEventListener('click', (e) => {
    if (e.target === noteViewer) closeNoteModal();
});

// Prevent bubbling in modal box
document.querySelector('.note-view-box').addEventListener('wheel', (e) => e.stopPropagation());

// ── Delete Confirmation Logic ──
let deleteFormId = null;
function confirmDelete(id) { deleteFormId = id; openModal('modal-delete'); }
function executeDelete() { if(deleteFormId) document.getElementById(deleteFormId).submit(); }

// ── Import URL Action ──
async function importURLDummy() {
    const urlInput = document.getElementById('urlInput');
    const url = urlInput.value.trim();
    const btn = document.querySelector('.mbtn-pri[onclick="importURLDummy()"]');
    
    if (!url) return showToast('⚠️ Please enter a valid URL', '#d97706');
    
    btn.innerHTML = '⏳ Importing...';
    btn.disabled = true;

    try {
        const response = await fetch('/notes/import-url', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ url })
        });

        const data = await response.json();
        if (!response.ok) throw new Error(data.error);

        document.querySelector('input[name="title"]').value = data.title;
        const desc = document.querySelector('textarea[name="description"]');
        desc.value = data.content;
        autoResize(desc);

        closeModal('modal-url');
        showToast('✅ Imported successfully!', '#059669');
        urlInput.value = '';
    } catch (error) {
        showToast('❌ ' + error.message, '#dc2626');
    } finally {
        btn.innerHTML = '<i class="fa-solid fa-link"></i> Import Note';
        btn.disabled = false;
    }
}

// ── Utilities (Auto-resize, Shortcuts) ──
function autoResize(textarea) {
    textarea.style.height = 'auto'; 
    textarea.style.height = textarea.scrollHeight + 'px'; 
}

const descriptionField = document.querySelector('[name="description"]');
descriptionField.addEventListener('input', function() { autoResize(this); });

document.addEventListener('keydown', (e) => {
    // Save shortcut
    if (e.ctrlKey && e.key === 's') { e.preventDefault(); document.getElementById('noteForm').submit(); }
    // Close modals on Esc
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'));
        closeNoteModal();
    }
});

// ── AI Summary Logic ──
document.getElementById('aiGenerateBtn').addEventListener('click', async function () {
    const btn = this;
    const titleField = document.querySelector('[name="title"]');
    let promptText = titleField.value.trim() + " " + descriptionField.value.trim();

    if (!promptText.trim()) return showToast('Add some content first!', '#d97706');

    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Generating...';
    btn.disabled = true;

    try {
        const response = await fetch('/ai-summary', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ description: promptText })
        });

        const data = await response.json();
        if (!response.ok) throw new Error(data.message);

        let cleanText = data.result.replace(/\*\*/g, ''); 
        if (cleanText.includes('Summary:')) {
            let parts = cleanText.split('Summary:');
            titleField.value = parts[0].replace(/Title:/i, '').trim();
            descriptionField.value = parts[1].trim();
        } else {
            descriptionField.value = cleanText.trim();
        }
        autoResize(descriptionField);
        showToast('✦ AI Summary Generated!', '#7c3aed');
    } catch (error) {
        showToast('AI Error: ' + error.message, '#dc2626');
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});

// ── Counter Animation ──
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.stat-num[data-to]').forEach(el => {
        const target = +el.dataset.to;
        const dur = 1200;
        const t0 = performance.now();
        function tick(now) {
            const p = Math.min((now - t0) / dur, 1);
            el.textContent = Math.floor((1 - Math.pow(1 - p, 3)) * target);
            if (p < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    });
});
</script>

</body>
</html>