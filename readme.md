# Innkeeper

![ci.yml][link-ci]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

The library for your next [Laravel](https://laravel.com/) booking app.

## Installation

1. Install package via composer

``` bash
$ composer require shirokovnv/innkeeper
```

2. Run migrations

```bash
$ php artisan migrate
```

3. Done!

## Usage

### Add bookable functionality to your eloquent model

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Shirokovnv\Innkeeper\Contracts\Bookable;
use Shirokovnv\Innkeeper\Traits\HasBooking;

class Room extends Model implements Bookable {
    use HasBooking;
}
```

That's basically it. Now your rooms can be booked.

### Checking opportunity for the booking:

The `Innkeeper` service provides functionality to check wether its possible to book something in a date range:

```php
use \Shirokovnv\Innkeeper\Contracts\Innkeepable;

$room = \App\Models\Room::find(1);

$innkeeper = app()->make(Innkeepable::class);

$canBeBooked = $innkeeper->canBook(
    $room,
    new DateTime('2022-08-01 15:00'), 
    new DateTime('2022-08-07 12:00')
);
```

### Create a new booking

Creating a new booking is straight forward and could be done the following way:

```php
use \Shirokovnv\Innkeeper\Contracts\Innkeepable;

$room = \App\Models\Room::find(1);

$innkeeper = app()->make(Innkeepable::class);
$booking_hash = generateBookingHash();

$innkeeper->book(
    $room, 
    $booking_hash, 
    new DateTime('2022-08-01 15:00'), 
    new DateTime('2022-08-07 12:00')
);
```

Or if you like facades, you can do it like this:

```php
use Shirokovnv\Innkeeper\Facades\Innkeeper;

Innkeeper::book(
    $room, 
    $booking_hash, 
    new DateTime('2022-08-01 15:00'), 
    new DateTime('2022-08-07 12:00')
);
```

> **Notes:** 
> 
> Why we need `booking_hash` and what is it ?
> - The hash is a field in a database with a unique constraint
> - It serves purpose to prevent some duplicate bookings without explicitly locking tables

Let me show you and example:

Suppose, you have a few visitors on your booking site. 
And all the visitors asks for booking the same room at a time.

In this particular case, you probably need to assign the room to the 
first customer and notify others the room already booked.

You can do it by these simple steps:

1. Define hash function 

```php
function generateBookingHash(int $room_id, string $started_at, string $ended_at) {
    return $room_id . $started_at . $ended_at;
}
```

2. In your business logic code

```php
use Illuminate\Database\QueryException;

$room = \App\Models\Room::find(1);
$booking_hash = generateBookingHash($room->id, $started_at, $ended_at);

try {
    $innkeeper->book($room, $started_at, $ended_at);
} catch (QueryException $exception) {
    // show user popup with apologies or
    // redirect to another free room or ...
}
```

This example covers only the case, when you have deterministic booking schedule without intersections.
Like this:

- 09:00 - 10:00
- 10:00 - 11:00
- 11:00 - 12:00
- ...

If you have some intersections, like:

- 09:00 - 10:00
- 09:30 - 10:30

you may still have a problem with duplicates.

Of course, you can check the availability of the room: 

```php
$canBeBooked = $innkeeper->canBook($room, $started_at, $ended_at);
if ($canBeBooked) {
    $innkeeper->book($room, $booking_hash, $started_at, $ended_at);
} else {
    // show user an error message
}
```

But it doesn't guarantee resolving concurrent requests for your schedule.

So, please, let me know what can be a solution if it is your case.

### Query bookings

```php
use \Shirokovnv\Innkeeper\Contracts\Innkeepable;

$room = \App\Models\Room::find(1);

$innkeeper = app()->make(Innkeepable::class);

// All the bookings for the room
$booking_collection = $innkeeper->all($room);

// The first started booking
$first_booking = $innkeeper->first($room);

// The last ended booking
$last_booking = $innkeeper->last($room);
```

### Delete bookings

```php
$room = \App\Models\Room::find(1);

$booking_hash = 'some hash';

// Delete by predefined hash
$innkeeper->deleteByHash($room, $booking_hash);

$started_at = new DateTime('2022-08-01 09:00');
$ended_at = new DateTime('2022-08-02 09:00');

// Delete by specific date range.
$innkeeper->deleteByRange($room, $started_at, $ended_at);
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email shirokovnv@gmail.com instead of using the issue tracker.

## Credits

- [Shirokov Nickolai][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/shirokovnv/innkeeper.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/shirokovnv/innkeeper.svg?style=flat-square

[link-ci]: https://github.com/shirokovnv/innkeeper/actions/workflows/ci.yml/badge.svg
[link-packagist]: https://packagist.org/packages/shirokovnv/innkeeper
[link-downloads]: https://packagist.org/packages/shirokovnv/innkeeper
[link-author]: https://github.com/shirokovnv
[link-contributors]: ../../contributors
