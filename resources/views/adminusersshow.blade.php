@extends('adminpanel')

@section('content')

<div class="panel">

    <div class="panel-head">
        <h3>User Details</h3>
    </div>

    <div style="padding:20px;">

        <p>
            <strong>Name:</strong>
            {!! $user->name !!}
        </p>

        <br>

        <p>
            <strong>Email:</strong>
            {!! $user->email !!}
        </p>

        <br>

        <p>
            <strong>Notes Count:</strong>
            {!! $noteCount !!}
        </p>

        <br><br>

        <h4>Recent Activity</h4>

        <table class="log-table" style="margin-top:15px;">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                @forelse($userLogs as $log)
                <tr>
                    <td>{!! $log->action !!}</td>
                    <td>{!! $log->created_at !!}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="text-align:center;">
                        No activity found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:20px;">
            <a href="{{ route('admin.users') }}" class="act-btn">
                Back to Users
            </a>
        </div>

    </div>

</div>

@endsection