<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\System
 *
 * @property integer $id
 * @property string $hostname
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Record[] $records
 * @method static \Illuminate\Database\Query\Builder|\App\System whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\System whereHostname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\System whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\System whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $uid
 * @method static \Illuminate\Database\Query\Builder|\App\System whereUid($value)
 */
class System extends Model
{
    public function records()
    {
        return $this->hasMany(\App\Record::class);
    }
}
