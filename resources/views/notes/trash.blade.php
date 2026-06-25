<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VaultScribe - Trash</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('dashboardstyle.css') }}">
    <style>
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
        
        /* ─── BUTTONS ─── */
        .mbtn { padding: 8px 18px; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.16s; border: none; }
        .mbtn-sec { background: var(--gray-100); color: var(--gray-700); border: 1px solid var(--gray-200); }
        .mbtn-sec:hover { background: var(--gray-200); transform: translateY(-1px); }
        .mbtn-pri { background: var(--blue); color: #fff; box-shadow: 0 2px 8px rgba(37,99,235,0.25); }
        .mbtn-pri:hover { background: #1d4ed8; transform: translateY(-1px); }
        .mbtn-danger { background: var(--red); color: #fff; box-shadow: 0 2px 8px rgba(220,38,38,0.25); }
        .mbtn-danger:hover { background: #b91c1c; transform: translateY(-1px); }

        /* ─── TOAST NOTIFICATION ─── */
        .toast { position: fixed; bottom: 24px; right: 24px; z-index: 2000; background: var(--gray-900); color: #fff; font-size: 13px; font-weight: 500; padding: 12px 20px; border-radius: 9px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); transform: translateY(20px); opacity: 0; transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55); pointer-events: none; display: flex; align-items: center; gap: 8px; }
        .toast.show { transform: translateY(0); opacity: 1; }
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
            <i class="fa-solid fa-file-lines"></i>
            Notes
        </a>

        <a href="#" class="nav-item">
            <i class="fa-regular fa-star"></i>
            Important
        </a>

        <a href="{{ route('notes.trash') }}" class="nav-item active">
            <i class="fa-regular fa-trash-can"></i>
            Trash
        </a>
    </nav>

    <div class="sidebar-bottom">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </button>
        </form>
    </div>
</aside>

<main class="main">

    <header class="topbar">
        <span class="page-heading">Trash</span>

        <div class="topbar-right">
            <div class="user-btn">
                <div class="avatar-wrap">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ route('profile.photo', ['filename' => auth()->user()->profile_photo]) }}">
                    @else
                        {!! substr(auth()->user()->name,0,2) !!}
                    @endif
                </div>

                <span class="user-name">
                    {!! auth()->user()->name !!}
                </span>
            </div>
        </div>
    </header>

    <div class="content">

        <div class="section-header">
            <span class="page-heading" style="font-size:16px;">
                Deleted Notes
            </span>

            <span style="font-size:13px;color:#6b7280;">
                Trash will be kept until permanently deleted
            </span>
        </div>

        <div class="notes-grid">

            @forelse($notes as $note)

            <div class="note-item">
                <div class="note-title">
                    {!! $note->title !!}
                </div>
                <div class="note-preview">
                    {!! $note->description !!}
                </div>
                <div class="note-foot">
                    <span class="note-date">
                        Deleted {{ $note->deleted_at->diffForHumans() }}
                    </span>
                    <div class="note-btns">

                        <form action="{{ route('notes.restore',$note->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-edit">
                                Restore
                            </button>
                        </form>

                        <!-- UPDATED DELETE FORM -->
                        <form id="delete-form-{{ $note->id }}" action="{{ route('notes.forceDelete',$note->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-del" onclick="confirmDelete('delete-form-{{ $note->id }}', false)">
                                Delete
                            </button>
                        </form>

                    </div>
                </div>
            </div>

            @empty

            <div class="note-item" style="align-items: center; justify-content: center; min-height: 150px; border: 2px dashed var(--gray-200); background: transparent; box-shadow: none;">
                <i class="fa-solid fa-wind" style="font-size: 24px; color: var(--gray-400); margin-bottom: 8px;"></i>
                <span style="color: var(--gray-500); font-size: 13px;">Trash is Empty. No deleted notes found.</span>
            </div>

            @endforelse

        </div>

        @if($notes->count() > 0)

        <div style="margin-top:20px;display:flex;gap:10px;">

            <form method="POST" action="{{ route('notes.restoreAll') }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="mbtn mbtn-pri">
                    Restore All
                </button>
            </form>

            <!-- UPDATED DELETE ALL FORM -->
            <form id="delete-all-form" method="POST" action="{{ route('notes.deleteAll') }}">
                @csrf
                @method('DELETE')
                <button type="button" class="mbtn mbtn-danger" style="padding:8px 16px;" onclick="confirmDelete('delete-all-form', true)">
                    Delete All Permanently
                </button>
            </form>

        </div>

        @endif

    </div>

</main>

<!-- PERMANENT DELETE CONFIRMATION MODAL -->
<div id="modal-delete" class="modal-overlay" onclick="closeModal('modal-delete')">
  <div class="modal-box" onclick="event.stopPropagation()" style="width: 400px;">
    <div class="modal-head" style="border-bottom: none; padding-bottom: 0;">
      <div class="modal-title" style="display: flex; align-items: center; gap: 8px; color: var(--red);">
        <i class="fa-solid fa-triangle-exclamation"></i> Permanent Deletion
      </div>
      <button type="button" class="modal-close" onclick="closeModal('modal-delete')">✕</button>
    </div>
    <div class="modal-body">
      <p id="delete-warning-text" style="font-size: 13.5px; color: var(--gray-600); line-height: 1.5;">
        Are you sure you want to permanently delete this note? This action cannot be undone.
      </p>
    </div>
    <div class="modal-foot">
      <button type="button" class="mbtn mbtn-sec" onclick="closeModal('modal-delete')">Cancel</button>
      <button type="button" class="mbtn mbtn-danger" onclick="executeDelete()">Yes, Delete Forever</button>
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
    document.addEventListener('DOMContentLoaded', function() {
        showToast("{{ session('success') }}", '#059669'); 
    });
@endif

// ── Modal open/close Logic ──
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

// ── Delete Confirmation Logic ──
let deleteFormId = null;

function confirmDelete(id, isBulk = false) {
    deleteFormId = id;
    const warningText = document.getElementById('delete-warning-text');
    
    // Change text dynamically if user clicks "Delete All"
    if(isBulk) {
        warningText.innerText = "Are you sure you want to permanently delete ALL notes in the trash? This action cannot be undone.";
    } else {
        warningText.innerText = "Are you sure you want to permanently delete this note? This action cannot be undone.";
    }
    
    openModal('modal-delete');
}

function executeDelete() {
    if(deleteFormId) {
        document.getElementById(deleteFormId).submit();
    }
}

// Close modal on Escape key
document.addEventListener('keydown', e => { 
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'));
    }
});
</script>

</body>
</html>