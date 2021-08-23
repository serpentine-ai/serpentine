<?php

namespace Serpentine;

/**
 * Input-Output provider
 * Realizes communication between user and Serpentine
 */
interface IO
{
    /**
     * Get new messages
     * 
     * @return array of Message
     */
    public function getUpdates (): array;

    /**
     * Send Message to user
     * 
     * @param int $receiver
     * @param Message $message
     * 
     * @return self
     */
    public function sendMessage (int $receiver, Message $message): self;

    /**
     * Directly execute some API method
     * 
     * @param string $method
     * [@param array $params = []]
     * 
     * @return array|null
     */
    public function query (string $method, array $params = []): ?array;
}
