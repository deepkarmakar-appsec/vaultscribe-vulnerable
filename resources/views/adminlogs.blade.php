@extends('adminpanel')

@section('content')

<div class="panel">

    <div class="panel-head">
        <h3>Security Activity Logs</h3>
    </div>

    <table class="log-table">
        <thead>
            <tr>
                <th>Action</th>
                <th>IP Address</th>
                <th>User</th>
                <th>Time</th>
            </tr>
        </thead>

        <tbody>
            @forelse($logs as $log)
            <tr>
                <td>{!! $log->action !!}</td>
                <td>{!! $log->ip_address !!}</td>
                <td>{!! $log->user->name ?? 'Guest' !!}</td>
                <td>{!! $log->created_at->diffForHumans() !!}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;">
                    No logs found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {!! $logs->links() !!}
    </div>

</div>

@endsection