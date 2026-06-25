@extends('adminpanel')

@section('content')

<div class="panel">

    <div class="panel-head">
        <h3>System Settings</h3>
    </div>

    <div style="padding:20px;">

        <form method="POST" action="#">
            @csrf

            <div style="margin-bottom:20px;">
                <label>
                    <strong>Security Alert Threshold</strong>
                </label>
                <br><br>
                <input type="number"
                       name="security_threshold"
                       value="5"
                       style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;">
            </div>

            <div style="margin-bottom:20px;">
                <label>
                    <strong>System Mode</strong>
                </label>
                <br><br>
                <select name="system_mode"
                        style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;">
                    <option value="production">Production</option>
                    <option value="development">Development</option>
                </select>
            </div>

            <div style="margin-bottom:20px;">
                <label>
                    <strong>Enable Security Logs</strong>
                </label>
                <br><br>
                <input type="checkbox" checked>
            </div>

            <button type="submit" class="act-btn">
                Update Settings
            </button>

        </form>

    </div>

</div>

@endsection