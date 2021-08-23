<?php

namespace Serpentine;

/**
 * Message's text storage
 */
interface MessageText
{
    /**
     * Get message text
     * 
     * @return string
     */
    public function getText (): string;
}
