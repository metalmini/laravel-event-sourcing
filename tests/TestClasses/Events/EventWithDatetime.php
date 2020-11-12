<?php

namespace Spatie\EventSourcing\Tests\TestClasses\Events;

use DateTimeImmutable;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class EventWithDatetime extends ShouldBeStored
{
    public $value;

    public function __construct(DateTimeImmutable $value)
    {
        $this->value = $value;
    }
}
