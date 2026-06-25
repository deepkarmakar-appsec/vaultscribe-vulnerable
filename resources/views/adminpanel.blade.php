<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VaultScribe Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body{
            margin:0;
            font-family:'DM Sans',sans-serif;
            background:#f5f7fb;
        }

        .sidebar{
            width:250px;
            height:100vh;
            position:fixed;
            left:0;
            top:0;
            background:#0f172a;
            color:white;
        }

        .sidebar a{
            display:block;
            color:white;
            text-decoration:none;
            padding:15px 20px;
        }

        .sidebar a:hover{
            background:#1e293b;
        }

        .main{
            margin-left:250px;
            min-height:100vh;
        }

        .topbar{
            background:white;
            padding:20px;
            border-bottom:1px solid #ddd;
        }

        .content{
            padding:20px;
        }

        .panel {
    background: var(--white);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-lg);
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
    padding: 24px;
    transition: transform 0.2s, box-shadow 0.2s;
    animation: fadeUp 0.4s both;
}

.panel-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
    font-size: 16px;
    color: var(--gray-900);
}
.log-table { 
    width: 100%; 
    border-collapse: collapse; 
}

.log-table th {
    font-size: 11px;
    text-transform: uppercase;
    color: var(--gray-500);
    padding: 12px 16px;
    border-bottom: 1px solid var(--gray-100);
}

.log-table td {
    padding: 16px;
    font-size: 13.5px;
    border-bottom: 1px solid var(--gray-100);
}

/* Row hover effect */
.log-table tbody tr {
    transition: background 0.2s;
}

.log-table tbody tr:hover {
    background: var(--gray-50);
}

        .act-btn{
            background:#2563eb;
            color:white;
            padding:6px 12px;
            border-radius:6px;
            text-decoration:none;
        }

        .panel:hover {
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.08);
}
    </style>
</head>
<body>

<div class="sidebar">
    <h2 style="padding:20px;">VaultScribe</h2>

    <a href="{{ route('admin.dashboard') }}">
        <i class="fa fa-gauge"></i> Dashboard
    </a>

    <a href="{{ route('admin.users') }}">
        <i class="fa fa-users"></i> Users
    </a>

    <a href="{{ route('admin.notes') }}">
        <i class="fa fa-note-sticky"></i> Notes
    </a>

    <a href="{{ route('admin.logs') }}">
        <i class="fa fa-shield"></i> Logs
    </a>

    <a href="{{ route('admin.settings') }}">
        <i class="fa fa-gear"></i> Settings
    </a>
</div>

<div class="main">

    <div class="topbar">
        <h2>Admin Panel</h2>
    </div>

    <div class="content">
        @yield('content')
    </div>

</div>

</body>
</html>



