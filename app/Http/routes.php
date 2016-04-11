<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

use App\CPUTimes,
    App\Disk,
    App\Memory,
    App\NetIO,
    App\Record,
    App\System;

use Illuminate\Http\Request;

Route::group([ 'middleware' => [ 'web' ] ], function ()
{
    Route::get('/', function ()
    {
        return view('app.overview', [
            'systems' => System::all()
        ]);
    });

    Route::get('/view/system/{system}', function (System $system)
    {
        return view('app.system', [
            'system'       => $system,
            'latestRecord' => $system->records()->orderBy('id', 'desc')->first(),
            'firstRecord'  => $system->records()->orderBy('id', 'asc')->first(),
        ]);
    });

    Route::delete('/system/{system}', function (System $system)
    {
        $system->delete();

        return redirect('/');
    });

    Route::get('/system/{system}/{recordCount?}', function (System $system, $recordCount = 60)
    {
        $date = new DateTime();
        $date->modify(env('APP_RECORD_TRIM_THRESHOLD', '-1 day'));
        $formatted_date = $date->format('Y-m-d H:i:s');

        $system->records()->where('created_at', '<=', $formatted_date)->delete();

        $validator = Validator::make([
            'recordCount' => $recordCount
        ], [
            'recordCount' => 'required|integer|between:1,3600'
        ]);

        if ($validator->fails())
        {
            return view('common.ajax', [
                'return', false,
                'data' => $validator->failed(),
            ]);
        }
        else
        {
            $system->load([ 'records' => function ($query) use ($recordCount)
            {
                $query->with('cputimes', 'disks', 'memory', 'netio', 'users')
                    ->orderBy('id', 'desc')
                    ->take($recordCount);
            } ]);

            return view('common.ajax', [
                'data' => [ 'return' => true,
                            'data'   => [
                                'system'      => $system,
                                'firstRecord' => $system->records()->orderBy('id', 'asc')->first(),
                                'recordCount' => $system->records()->count(),
                            ]
                ]
            ]);
        }
    });
});

Route::group([ 'middleware' => [ 'api' ] ], function ()
{
    Route::post('/pingback', function (Request $request)
    {
        return view('common.ajax', [
            'data' => $request->all()
        ]);
    });

    Route::post('/record', function (Request $request)
    {
        $initialValidator = Validator::make($request->all(), [
            'uuid' => 'required',
            'info' => 'required'
        ]);

        if ($initialValidator->fails())
        {
            return view('common.ajax', [ 'data' => $initialValidator->failed() ]);
        }

        $uuid = $request->uuid;
        $info = json_decode($request->info, true);

        $validator = Validator::make($info, [
            'boot_time'          => 'required',
            'disk_partitions'    => 'required',
            'cpu_percent'        => 'required',
            'users'              => 'required',
            'memory_virtual'     => 'required',
            'net_io'             => 'required',
            'cpu_count'          => 'required',
            'cpu_count_physical' => 'required',
            'disk_usage'         => 'required',
            'cpu_times_percent'  => 'required',
            'cpu_times'          => 'required',
            'memory_swap'        => 'required',
            'hostname'           => 'required'
        ]);

        if ($validator->fails())
        {
            return view('common.ajax', [ 'data' => $validator->failed() ]);
        }

        try
        {
            try
            {
                $system           = System::where('uid', $uuid)->firstOrFail();
                $system->hostname = $info['hostname'];
                $system->save();
            }
            catch (Exception $e)
            {
                $system           = new System();
                $system->uid      = $uuid;
                $system->hostname = $info['hostname'];
                $system->save();
            }

            $record = new Record();

            $record->boot_time          = $info['boot_time'];
            $record->cpu_count          = $info['cpu_count'];
            $record->cpu_count_physical = $info['cpu_count_physical'];

            $memory               = new Memory();
            $memory->swap_percent = $info['memory_swap']['percent'];
            $memory->swap_used    = $info['memory_swap']['used'];
            $memory->swap_in      = $info['memory_swap']['sin'];
            $memory->swap_out     = $info['memory_swap']['sout'];
            $memory->swap_free    = $info['memory_swap']['free'];
            $memory->swap_total   = $info['memory_swap']['total'];

            $memory->virt_percent   = $info['memory_virtual']['percent'];
            $memory->virt_buffers   = $info['memory_virtual']['buffers'];
            $memory->virt_inactive  = $info['memory_virtual']['inactive'];
            $memory->virt_used      = $info['memory_virtual']['used'];
            $memory->virt_free      = $info['memory_virtual']['free'];
            $memory->virt_active    = $info['memory_virtual']['active'];
            $memory->virt_cached    = $info['memory_virtual']['cached'];
            $memory->virt_available = $info['memory_virtual']['available'];
            $memory->virt_total     = $info['memory_virtual']['total'];

            $users = [ ];
            foreach ($info['users'] as $u)
            {
                $user             = new \App\SystemUser();
                $user->name       = $u['name'];
                $user->host       = $u['host'];
                $user->start_time = $u['started'];
                $user->terminal   = $u['terminal'];

                $users[] = $user;
            }

            $interfaces = [ ];
            foreach ($info['net_io'] as $interface => $n)
            {
                $netio                   = new NetIO();
                $netio->interface        = $interface;
                $netio->errin            = $n['errin'];
                $netio->errout           = $n['errout'];
                $netio->bytes_recv       = $n['bytes_recv'];
                $netio->bytes_recv_sec   = $n['bytes_recv_sec'];
                $netio->bytes_sent       = $n['bytes_sent'];
                $netio->bytes_sent_sec   = $n['bytes_sent_sec'];
                $netio->dropout          = $n['dropout'];
                $netio->dropin           = $n['dropin'];
                $netio->packets_recv     = $n['packets_recv'];
                $netio->packets_recv_sec = $n['packets_recv_sec'];
                $netio->packets_sent     = $n['packets_sent'];
                $netio->packets_sent_sec = $n['packets_sent_sec'];

                $interfaces[] = $netio;
            }

            $cputimes                     = new CPUTimes();
            $cputimes->user               = $info['cpu_times']['user'];
            $cputimes->steal              = $info['cpu_times']['steal'];
            $cputimes->system             = $info['cpu_times']['system'];
            $cputimes->irq                = $info['cpu_times']['irq'];
            $cputimes->softirq            = $info['cpu_times']['softirq'];
            $cputimes->nice               = $info['cpu_times']['nice'];
            $cputimes->guest_nice         = $info['cpu_times']['guest_nice'];
            $cputimes->guest              = $info['cpu_times']['guest'];
            $cputimes->idle               = $info['cpu_times']['idle'];
            $cputimes->iowait             = $info['cpu_times']['iowait'];
            $cputimes->user_percent       = $info['cpu_times_percent']['user'];
            $cputimes->steal_percent      = $info['cpu_times_percent']['steal'];
            $cputimes->system_percent     = $info['cpu_times_percent']['system'];
            $cputimes->irq_percent        = $info['cpu_times_percent']['irq'];
            $cputimes->softirq_percent    = $info['cpu_times_percent']['softirq'];
            $cputimes->nice_percent       = $info['cpu_times_percent']['nice'];
            $cputimes->guest_nice_percent = $info['cpu_times_percent']['guest_nice'];
            $cputimes->guest_percent      = $info['cpu_times_percent']['guest'];
            $cputimes->idle_percent       = $info['cpu_times_percent']['idle'];
            $cputimes->iowait_percent     = $info['cpu_times_percent']['iowait'];

            $disks = [ ];
            foreach ($info['disk_partitions'] as $disk_partition)
            {
                $disk = new Disk();

                $disk->opts       = $disk_partition['opts'];
                $disk->fstype     = $disk_partition['fstype'];
                $disk->device     = $disk_partition['device'];
                $disk->mountpoint = $disk_partition['mountpoint'];

                $disk->free         = $info['disk_usage'][$disk_partition['device']]['free'];
                $disk->total        = $info['disk_usage'][$disk_partition['device']]['total'];
                $disk->used         = $info['disk_usage'][$disk_partition['device']]['used'];
                $disk->used_percent = $info['disk_usage'][$disk_partition['device']]['percent'];

                $disks[] = $disk;
            }

            $system->records()->save($record);
            $record->save();
            $record->memory()->save($memory);
            $record->users()->saveMany($users);
            $record->netio()->saveMany($interfaces);
            $record->cputimes()->save($cputimes);
            $record->disks()->saveMany($disks);
        }
        catch (Exception $e)
        {
            return view('common.ajax', [ 'data' => [
                'return'  => false,
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
                'trace'   => $e->getTraceAsString(),
            ] ]);
        }

        return view('common.ajax', [ 'data' => json_encode([ 'return' => true ]) ]);
    });
});