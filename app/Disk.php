<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Disk
 *
 * @property integer $id
 * @property integer $record_id
 * @property string $opts
 * @property string $fstype
 * @property string $device
 * @property string $mountpoint
 * @property integer $free
 * @property integer $total
 * @property integer $used
 * @property float $used_percent
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Disk whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Disk whereRecordId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Disk whereOpts($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Disk whereFstype($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Disk whereDevice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Disk whereMountpoint($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Disk whereFree($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Disk whereTotal($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Disk whereUsed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Disk whereUsedPercent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Disk whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Disk whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Disk extends Model
{
    //
}
