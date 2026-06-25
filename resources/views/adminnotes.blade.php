@extends('adminpanel')

@section('content')

<div class="panel">
    <div class="panel-head">
        <h3>All Encrypted Notes</h3>
    </div>

    <table class="log-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Owner</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>
            @forelse($notes as $note)
                <tr>
                    <td>{!! $note->title !!}</td>
                    <td>{!! $note->user->name ?? 'Unknown' !!}</td>
                    <td>{!! $note->created_at->format('d M Y') !!}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align:center;">
                        No notes found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {!! $notes->links() !!}
    </div>
</div>

@endsection