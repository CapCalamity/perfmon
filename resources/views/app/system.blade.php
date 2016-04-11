<!-- resources/views/systems.blade.php -->

@extends('layouts.app')

@section('content')
    <div id="system-settings"
         data-endpoint="{{ URL::to('/') }}"
         data-system="{{ $system->id }}"></div>
    <div class="col-md-12">
        <div class="panel panel-primary root">
            <div class="panel-heading">
                {{ $system->uid }}
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th width="{{ 100/6 }}%">Hostname</th>
                    <th width="{{ 100/6 }}%">CPUs</th>
                    <th width="{{ 100/6 }}%">Startup Time</th>
                    <th width="{{ 100/6 }}%">First Entry</th>
                    <th width="{{ 100/6 }}%">Latest Entry</th>
                    <th width="{{ 100/6 }}%">Entries</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <div class="system-hostname"></div>
                    </td>
                    <td>
                        <span class="system-cpu-count"></span>
                        /
                        <span class="system-cpu-count-physical"></span>
                    </td>
                    <td>
                        <div class="system-records-latest-boot"></div>
                    </td>
                    <td>
                        <div class="system-records-first-time"></div>
                    </td>
                    <td>
                        <div class="system-records-latest-time"></div>
                    </td>
                    <td>
                        <div class="system-records-total"></div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-6 root">
        <div class="perfmon-info"></div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                CPU Usage
            </div>
            <div class="panel-body">
                <div class="system-graph graph-cpu">
                </div>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th width="{{ 100/4 }}%">User</th>
                    <th width="{{ 100/4 }}%">System</th>
                    <th width="{{ 100/4 }}%">Idle</th>
                    <th width="{{ 100/4 }}%">IO</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <span class="cpu-user-percent"></span> %
                    </td>
                    <td>
                        <span class="cpu-system-percent"></span> %
                    </td>
                    <td>
                        <span class="cpu-idle-percent"></span> %
                    </td>
                    <td>
                        <span class="cpu-io-percent"></span> %
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    @foreach($latestRecord->disks as $disk)
        <div class="col-md-6 root">
            <div class="perfmon-info"
                 data-device="{{ $disk->device }}"></div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Disk - {{ $disk->device }}
                </div>
                <div class="panel-body">
                    <div class="system-graph graph-disk"></div>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th width="{{ 100/7 }}%">Device</th>
                        <th width="{{ 100/7 }}%">Mount</th>
                        <th width="{{ 100/7 }}%">FS Type</th>
                        <th width="{{ 100/7 }}%">Options</th>
                        <th width="{{ 100/7 }}%">Total</th>
                        <th width="{{ 100/7 }}%">Free</th>
                        <th width="{{ 100/7 }}%">Used</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <div class="disk-device"></div>
                        </td>
                        <td>
                            <div class="disk-mountpoint"></div>
                        </td>
                        <td>
                            <div class="disk-fstype"></div>
                        </td>
                        <td>
                            <div class="disk-opts"></div>
                        </td>
                        <td>
                            <div class="disk-total size-b"></div>
                        </td>
                        <td>
                            <div class="disk-free size-b"></div>
                        </td>
                        <td>
                            <span class="disk-used size-b"></span> (<span class="disk-used-percent"></span>%)
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    <div class="col-md-6 root">
        <div class="perfmon-info"></div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                Memory Usage
            </div>
            <div class="panel-body">
                <div class="system-graph graph-memory">
                </div>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th width="{{ 100/6 }}%">Total</th>
                    <th width="{{ 100/6 }}%">Used</th>
                    <th width="{{ 100/6 }}%">Free</th>
                    <th width="{{ 100/6 }}%">Inactive</th>
                    <th width="{{ 100/6 }}%">Cached</th>
                    <th width="{{ 100/6 }}%">Buffers</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <div class="memory-total size-b"></div>
                    </td>
                    <td>
                        <div class="memory-used size-b"></div>
                    </td>
                    <td>
                        <div class="memory-free size-b"></div>
                    </td>
                    <td>
                        <div class="memory-inactive size-b"></div>
                    </td>
                    <td>
                        <div class="memory-cached size-b"></div>
                    </td>
                    <td>
                        <div class="memory-buffers size-b"></div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-6 root">
        <div class="perfmon-info"></div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                Swap Usage
            </div>
            <div class="panel-body">
                <div class="system-graph graph-swap">
                </div>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th width="{{ 100/5 }}%">Total</th>
                    <th width="{{ 100/5 }}%">Used</th>
                    <th width="{{ 100/5 }}%">Free</th>
                    <th width="{{ 100/5 }}%">In</th>
                    <th width="{{ 100/5 }}%">Out</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <div class="swap-total size-b"></div>
                    </td>
                    <td>
                        <div class="swap-used size-b"></div>
                    </td>
                    <td>
                        <div class="swap-free size-b"></div>
                    </td>
                    <td>
                        <div class="swap-in size-b"></div>
                    </td>
                    <td>
                        <div class="swap-out size-b"></div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    @foreach($latestRecord->netio as $netio)
        <div class="col-md-6 root">
            <div class="perfmon-info"
                 data-interface="{{ $netio->interface }}"></div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Interface - {{ $netio->interface }}
                </div>
                <div class="panel-body">
                    <div class="system-graph graph-netio">
                    </div>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th  width="{{ 100/12 }}%">B Rt/s</th>
                        <th  width="{{ 100/12 }}%">B Rx/s</th>
                        <th  width="{{ 100/12 }}%">B Rt</th>
                        <th  width="{{ 100/12 }}%">B Rx</th>
                        <th  width="{{ 100/12 }}%">P Rt/s</th>
                        <th  width="{{ 100/12 }}%">P Rx/s</th>
                        <th  width="{{ 100/12 }}%">P Rt</th>
                        <th  width="{{ 100/12 }}%">P Rx</th>
                        <th  width="{{ 100/12 }}%">Dropped Rt</th>
                        <th  width="{{ 100/12 }}%">Dropped Rx</th>
                        <th  width="{{ 100/12 }}%">Error Rt</th>
                        <th  width="{{ 100/12 }}%">Error Rx</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <div class="netio-bytes-received-second size-b"></div>
                        </td>
                        <td>
                            <div class="netio-bytes-sent-second size-b"></div>
                        </td>
                        <td>
                            <div class="netio-bytes-received size-b"></div>
                        </td>
                        <td>
                            <div class="netio-bytes-sent size-b"></div>
                        </td>
                        <td>
                            <div class="netio-packets-received-second size"></div>
                        </td>
                        <td>
                            <div class="netio-packets-sent-second size"></div>
                        </td>
                        <td>
                            <div class="netio-packets-received size"></div>
                        </td>
                        <td>
                            <div class="netio-packets-sent size"></div>
                        </td>
                        <td>
                            <div class="netio-dropin"></div>
                        </td>
                        <td>
                            <div class="netio-dropout"></div>
                        </td>
                        <td>
                            <div class="netio-errin"></div>
                        </td>
                        <td>
                            <div class="netio-errout"></div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    <div class="col-md-6 root">
        <div class="perfmon-info"></div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                Logged-In Users: <span class="users-count-total"></span>
            </div>
            <table class="table users-table">
                <thead>
                <tr>
                    <th width="{{ 100/5 }}%" class="users-username">Username</th>
                    <th width="{{ 100/5 }}%" class="users-hostname">Host</th>
                    <th width="{{ 100/5 }}%" class="users-terminal"></th>
                    <th width="{{ 100/5 }}%" class="users-uptime">Uptime</th>
                    <th width="{{ 100/5 }}%" class="users-start-time">Connected At</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection