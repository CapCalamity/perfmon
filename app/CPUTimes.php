<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CPUTimes
 *
 * @property integer $id
 * @property integer $record_id
 * @property float $user
 * @property float $user_percent
 * @property float $steal
 * @property float $steal_percent
 * @property float $system
 * @property float $system_percent
 * @property float $irq
 * @property float $irq_percent
 * @property float $softirq
 * @property float $softirq_percent
 * @property float $nice
 * @property float $nice_percent
 * @property float $guest_nice
 * @property float $guest_nice_percent
 * @property float $guest
 * @property float $guest_percent
 * @property float $idle
 * @property float $idle_percent
 * @property float $iowait
 * @property float $iowait_percent
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereRecordId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereUser($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereUserPercent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereSteal($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereStealPercent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereSystem($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereSystemPercent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereIrq($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereIrqPercent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereSoftirq($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereSoftirqPercent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereNice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereNicePercent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereGuestNice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereGuestNicePercent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereGuest($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereGuestPercent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereIdle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereIdlePercent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereIowait($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereIowaitPercent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CPUTimes whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CPUTimes extends Model
{
    protected $table = 'cpu_times';
}
