<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Note — VaultScribe</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --sidebar: #111827; --bg: #f8fafc; --white: #ffffff; --gray-100: #f3f4f6; --gray-200: #e5e7eb; --gray-700: #374151; --gray-900: #111827; --blue: #2563eb; --blue-l: #3b82f6; --red: #dc2626; --red-l: #fef2f2; --radius-lg: 14px; --header-h: 60px; --shadow: 0 1px 3px rgba(0,0,0,0.07); }
        
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--gray-900); height: 100vh; overflow: hidden; display: flex; }

        /* SIDEBAR & MAIN */
        .sidebar { width: 240px; background: var(--sidebar); flex-shrink: 0; height: 100vh; display: flex; flex-direction: column; }
        .main { flex: 1; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }
        .topbar { height: var(--header-h); background: var(--white); border-bottom: 1px solid var(--gray-200); display: flex; align-items: center; padding: 0 24px; }
        
        /* CONTENT LAYOUT */
        .content { flex: 1; padding: 24px; display: flex; justify-content: center; overflow: hidden; }

        /* FIXED CARD */
        .card { 
            width: 100%; max-width: 700px; background: var(--white); 
            border: 1px solid var(--gray-200); border-radius: var(--radius-lg); 
            box-shadow: var(--shadow); display: flex; flex-direction: column; 
            max-height: 100%; overflow: hidden; 
        }
        
        .card-head { padding: 18px 24px; border-bottom: 1px solid var(--gray-100); font-weight: 700; display: flex; align-items: center; gap: 8px; }
        
        /* SCROLLABLE AREA */
        .card-body { padding: 24px; overflow-y: auto; flex: 1; }
        
        /* FORM STYLES */
        .form-label { font-size: 12px; font-weight: 600; color: var(--gray-700); margin-bottom: 8px; display: block; margin-top: 16px; }
        .form-input { width: 100%; padding: 12px 14px; background: var(--gray-100); border: 1px solid var(--gray-200); border-radius: 8px; font-size: 13.5px; outline: none; }
        .form-textarea { min-height: 200px; resize: none; }
        
        /* FOOTER BUTTONS */
        .card-footer { padding: 16px 24px; border-top: 1px solid var(--gray-100); display: flex; justify-content: space-between; align-items: center; background: #fff; }
        .mbtn { padding: 10px 18px; border-radius: 8px; font-size: 13.5px; font-weight: 500; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
        .mbtn-sec { background: var(--gray-100); color: var(--gray-700); }
        .mbtn-pri { background: var(--blue); color: #fff; }
        
        .error-box { background: var(--red-l); border: 1px solid #fecaca; color: var(--red); padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 13px; }
    </style>
</head>
<body>

<aside class="sidebar">
    </aside>

<main class="main">
    <header class="topbar">
        <span class="page-heading">Edit Note</span>
    </header>

    <div class="content">
        <div class="card">
            <div class="card-head">Edit Your Note</div>
            
            <form method="POST" action="{{ route('notes.update', $note->id) }}" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                @csrf
                @method('PUT')

                <div class="card-body">
                    @if($errors->any())
                        <div class="error-box">
                            <strong>Errors:</strong>
                            <ul>@foreach($errors->all() as $error) <li>{!! $error !!}</li> @endforeach</ul>
                        </div>
                    @endif

                    <label class="form-label" style="margin-top: 0;">Note Title</label>
                    <input type="text" name="title" value="{{ old('title', $note->title) }}" required class="form-input">

                    <label class="form-label">Note Description</label>
                    <textarea name="description" class="form-input form-textarea">{{ old('description', $note->description) }}</textarea>
                </div>

                <div class="card-footer">
                    <a href="{{ route('dashboard') }}" class="mbtn mbtn-sec">Back</a>
                    <button type="submit" class="mbtn mbtn-pri">Update Note</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    
    function autoResize(textarea) {
        textarea.style.height = 'auto'; 
        textarea.style.height = textarea.scrollHeight + 'px'; 
    }

    const descriptionField = document.querySelector('[name="description"]');
    
 
    if(descriptionField) {
        autoResize(descriptionField);
        descriptionField.addEventListener('input', function() {
            autoResize(this);
        });
    } 
</script>
</body>
</html>





