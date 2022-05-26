<?php

namespace App\Layers\Domain;

use Illuminate\Support\Collection;

class DomainEntity
{
    public Collection $events;

    public function __construct()
    {
        $this->events = Collection::make([]);
    }

    public function addEvent(DomainEvent $event): void {
        $this->events->push($event);
    }
}
