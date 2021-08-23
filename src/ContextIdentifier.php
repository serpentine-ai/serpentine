<?php

namespace Serpentine;

/**
 * Getting some information from message
 * which will be used in Action->process() method
 */
interface ContextIdentifier
{
    /**
     * Get identifier name (standard is provider/name, for example: essentials/datetime)
     */
    public function getName (): string;

    /**
     * Parse information from the message
     * 
     * @param Message $message
     * @param Action|null $action
     * @param IO $io
     */
    public function identify (Message $message, ?Action $action, IO $io);
}
