<?php

namespace Serpentine\Message;

/**
 * Message's text storage
 */
interface Text
{
    /**
     * Get message text
     * 
     * @return string
     */
    public function getText (): string;
}
