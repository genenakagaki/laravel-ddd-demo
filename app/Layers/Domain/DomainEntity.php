<?php

namespace App\Layers\Domain;

class DomainEntity
{
    public array $events = [];

    public function addEvent(DomainEvent $event): void {
        array_push($this->events, $event);
    }
}
