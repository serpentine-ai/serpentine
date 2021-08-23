<?php

namespace Serpentine\Events;

/**
 * Executes every this->getTick() seconds
 */
interface TickEvent
{
    /**
     * @return int - number of seconds between event executions
     */
    public function getTick (): int;

    /**
     * @return bool
     * true  - nothing happens
     * false - event will be removed
     */
    public function execute (): bool;
}
