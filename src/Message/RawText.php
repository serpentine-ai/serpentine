<?php

namespace Serpentine\Message;

use Serpentine\MessageText;

/**
 * Raw message text
 */
class RawText implements MessageText
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
