<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Exceptions;

class WrongDateInterval extends \Exception
{
    protected $message = 'Wrong booking date interval: start date should be less than end date.';
}
