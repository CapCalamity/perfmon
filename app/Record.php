<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Record
 *
 * @property integer $id
 * @property integer $system_id
 * @property integer $cpu_count
 * @property integer $cpu_count_physical
 * @property integer $boot_time
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Memory $memory
 * @property-read \App\User $users
 * @property-read \App\NetIO $netio
 * @property-read \App\CPUTimes $cputimes
 * @property-read \App\Disk $disks
 * @method static \Illuminate\Database\Query\Builder|\App\Record whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Record whereSystemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Record whereCpuCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Record whereCpuCountPhysical($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Record whereBootTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Record whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Record whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Record extends Model
{
    public function memory()
    {
        return $this->hasOne(\App\Memory::class);
    }

    public function users()
    {
        return $this->hasMany(\App\SystemUser::class);
    }

    public function netio()
    {
        return $this->hasMany(\App\NetIO::class);
    }

    public function cputimes()
    {
        return $this->hasOne(\App\CPUTimes::class);
    }

    public function disks()
    {
        return $this->hasMany(\App\Disk::class);
    }
}
