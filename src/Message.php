<?php

namespace Serpentine;

use Serpentine\Message\{
    Text,
    Author
};

use Serpentine\Message\Text\Raw;

/**
 * Message
 */
class Message
{
    public Text $text;
    public ?Author $author = null;

    /**
     * @param Text $text
     * [@param Author $author = null]
     */
    public function __construct (Text $text, Author $author = null)
    {
        $this->text   = $text;
        $this->author = $author;
    }

    /**
     * Quikly create new message
     * 
     * @param Text|string $text
     * [@param Author|int $author = null]
     * 
     * @return self
     */
    public static function new (Text|string $text, Author|int $author = null): self
    {
        if (is_string ($text))
            $text = new Raw ($text);

        if (is_int ($author))
            $author = new Author ($author);

        return new self ($text, $author);
    }

    /**
     * Get message text
     * 
     * @return string
     */
    public function getText (): string
    {
        return $this->text->getText ();
    }

    /**
     * Get message text tokens
     * 
     * @return array
     */
    public function getTokens (string $language = null): array
    {
        return Serpentine::getTokens ($this->text->getText (), $language);
    }
}
