<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Memory
 *
 * @property integer $id
 * @property integer $record_id
 * @property float $swap_percent
 * @property integer $swap_used
 * @property integer $swap_in
 * @property integer $swap_out
 * @property integer $swap_free
 * @property integer $swap_total
 * @property float $virt_percent
 * @property integer $virt_buffers
 * @property integer $virt_inactive
 * @property integer $virt_used
 * @property integer $virt_free
 * @property integer $virt_active
 * @property integer $virt_cached
 * @property integer $virt_available
 * @property integer $virt_total
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereRecordId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereSwapPercent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereSwapUsed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereSwapIn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereSwapOut($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereSwapFree($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereSwapTotal($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereVirtPercent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereVirtBuffers($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereVirtInactive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereVirtUsed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereVirtFree($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereVirtActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereVirtCached($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereVirtAvailable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereVirtTotal($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Memory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Memory extends Model
{
    //
}
