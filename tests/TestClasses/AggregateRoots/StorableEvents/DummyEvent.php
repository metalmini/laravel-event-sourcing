<?php

namespace Spatie\EventSourcing\Tests\TestClasses\AggregateRoots\StorableEvents;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class DummyEvent extends ShouldBeStored
{
    public $integer;

    public function __construct(int $integer)
    {
        $this->integer = $integer;
    }
}
