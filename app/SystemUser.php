<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\SystemUser
 *
 * @property integer $id
 * @property integer $record_id
 * @property string $name
 * @property string $host
 * @property integer $start_time
 * @property string $terminal
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\SystemUser whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SystemUser whereRecordId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SystemUser whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SystemUser whereHost($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SystemUser whereStartTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SystemUser whereTerminal($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SystemUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SystemUser whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SystemUser extends Model
{
    //
}
