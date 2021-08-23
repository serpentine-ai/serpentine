<?php

namespace Serpentine\Events;

/**
 * Handles every message and verifies its processing
 */
interface InputHandler
{
    /**
     * @return bool
     * true  - nothing happens
     * false - message processing will be stopped
     */
    public function handle (Message $message, ?Action $action, IO $io): bool;
}
