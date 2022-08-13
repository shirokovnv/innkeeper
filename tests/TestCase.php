<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Tests;

use Shirokovnv\Innkeeper\InnkeeperServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @param $app
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            InnkeeperServiceProvider::class,
        ];
    }

    /**
     * @param $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        include_once __DIR__ . '/../database/migrations/2022_08_13_140950_create_bookings_table.php';
        include_once __DIR__ . '/database/migrations/2022_08_13_170039_create_rooms_table.php';

        // run the up() method (perform the migration)
        (new \CreateBookingsTable)->up();
        (new \CreateRoomsTable)->up();
    }
}
