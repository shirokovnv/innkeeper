<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Tests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Shirokovnv\Innkeeper\Contracts\Bookable;
use Shirokovnv\Innkeeper\Tests\Database\Factories\RoomFactory;
use Shirokovnv\Innkeeper\Traits\HasBooking;

/**
 * @property int         $id
 * @property string      $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Room extends Model implements Bookable
{
    use HasBooking, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @var string
     */
    protected $table = 'rooms';

    /**
     * @return RoomFactory
     */
    public static function newFactory(): RoomFactory
    {
        return RoomFactory::new();
    }
}
