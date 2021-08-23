<?php

namespace Serpentine\Events;

/**
 * Executes every Serpentine->update() method call
 */
interface TickEvent
{
    /**
     * @return bool
     * true  - nothing happens
     * false - event will be removed
     */
    public function execute (): bool;
}
