<?php

namespace Spatie\EventSourcing\EventHandlers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Spatie\EventSourcing\StoredEvents\StoredEvent;

class EventHandlerCollection extends Collection
{
    public function __construct($eventHandlers = [])
    {
        parent::__construct([]);

        foreach ($eventHandlers as $eventHandler) {
            $this->addEventHandler($eventHandler);
        }
    }

    public function addEventHandler(EventHandler $eventHandler): void
    {
        $this->items[get_class($eventHandler)] = $eventHandler;
    }

    public function forEvent(StoredEvent $storedEvent): EventHandlerCollection
    {
        $eventHandlers = $this
            ->filter(
                function (EventHandler $eventHandler) {return in_array($storedEvent->event_class, $eventHandler->handles(), true);}
            )->toArray();

        return new static($eventHandlers);
    }

    public function call(string $method): void
    {
        $this
            ->filter(function (EventHandler $eventHandler) use ($method) {return method_exists($eventHandler, $method);})
            ->each(function (EventHandler $eventHandler) use ($method) {return app()->call([$eventHandler, $method]);});
    }

    public function remove(array $eventHandlerClassNames): void
    {
        $this->items = $this
            ->reject(
                function (EventHandler $eventHandler) use ($eventHandlerClassNames) {return in_array(get_class($eventHandler), $eventHandlerClassNames);}
            )
            ->toArray();
    }

    public function syncEventHandlers(): self
    {
        return $this ->reject(
            function (EventHandler $eventHandler) {return $eventHandler instanceof ShouldQueue;}
        );
    }

    public function asyncEventHandlers(): self
    {
        return $this->filter(
            function (EventHandler $eventHandler) {return $eventHandler instanceof ShouldQueue;}
        );
    }
}
