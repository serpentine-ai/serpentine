<?php

namespace Serpentine;

/**
 * Message
 */
class Message
{
    public MessageText $text;
    public ?int $author  = null;

    /**
     * @param MessageText $text
     * [@param int $author = null]
     */
    public function __construct (MessageText $text, int $author = null)
    {
        $this->text   = $text;
        $this->author = $author;
    }

    /**
     * Quikly create new message
     * 
     * @param MessageText|string $text
     * I really wanted to use union data type here, but
     * PHP 7.4 won and yeah, maybe when PHP 8 will become more popular
     * 
     * [@param int $author = null]
     * 
     * @return self
     * 
     * @throws \Exception when text is invalid
     */
    public static function new (MessageText|string $text, int $author = null): self
    {
        if (is_string ($text))
            return new self (new Serpentine\Message\RawText ($text), $author);

        elseif (is_object ($text) && $text instanceof MessageText)
            return new self ($text, $author);

        else throw new \Exception ('Invalid text value');
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
