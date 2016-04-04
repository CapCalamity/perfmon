<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\NetIO
 *
 * @property integer $id
 * @property integer $record_id
 * @property string $interface
 * @property integer $errin
 * @property integer $errout
 * @property integer $bytes_recv
 * @property integer $bytes_sent
 * @property integer $dropout
 * @property integer $dropin
 * @property integer $packets_recv
 * @property integer $packets_sent
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO whereRecordId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO whereInterface($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO whereErrin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO whereErrout($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO whereBytesRecv($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO whereBytesSent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO whereDropout($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO whereDropin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO wherePacketsRecv($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO wherePacketsSent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $bytes_recv_sec
 * @property integer $bytes_sent_sec
 * @property integer $packets_recv_sec
 * @property integer $packets_sent_sec
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO whereBytesRecvSec($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO whereBytesSentSec($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO wherePacketsRecvSec($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NetIO wherePacketsSentSec($value)
 */
class NetIO extends Model
{
    protected $table = 'net_ios';
}
