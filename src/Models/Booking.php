<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Shirokovnv\Innkeeper\Constants;
use Shirokovnv\Innkeeper\Database\Factories\BookingFactory;

/**
 * @property int         $id
 * @property string      $hash
 * @property int         $bookable_id
 * @property string      $bookable_type
 * @property Carbon|null $started_at
 * @property Carbon|null $ended_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'hash',
        'bookable_id',
        'bookable_type',
        'started_at',
        'ended_at',
    ];

    /**
     * @var string
     */
    protected $dateFormat = Constants::MYSQL_DATE_FORMAT;

    /**
     * The attributes that should be cast.
     *
     * @var array<string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * @return MorphTo
     */
    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BookingFactory
     */
    public static function newFactory(): BookingFactory
    {
        return BookingFactory::new();
    }
}
