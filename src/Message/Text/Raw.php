<?php

namespace Serpentine\Message\Text;

use Serpentine\Message\Text;

/**
 * Raw message text
 */
class Raw implements Text
{
    protected string $text;

    public function __construct (string $text)
    {
        $this->text = $text;
    }

    public function getText (): string
    {
        return $this->text;
    }
}
