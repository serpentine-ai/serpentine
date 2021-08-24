<?php

namespace Serpentine\Message;

/**
 * Message author
 */
class Author
{
    public int $id;
    public ?string $name = null;

    /**
     * @param int $id
     * [@param string $name = null]
     */
    public function __construct (int $id, string $name = null)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function new (...$args): self
    {
        return new self (...$args);
    }
}
