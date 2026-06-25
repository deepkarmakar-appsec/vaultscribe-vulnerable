@extends('adminpanel')

@section('content')

<div class="panel">

    <div class="panel-head">
        <h3>Users Management</h3>
    </div>

    <table class="log-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>2FA</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{!! $user->name !!}</td>
                <td>{!! $user->email !!}</td>
                <td>
                    {!! $user->google2fa_enabled ? 'Enabled' : 'Disabled' !!}
                </td>
                <td>
                    <a href="{{ route('admin.users.show', $user->id) }}"
                       class="act-btn">
                        View
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;">
                    No users found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {!! $users->links() !!}
    </div>

</div>

@endsection