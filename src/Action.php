<?php

namespace Serpentine;

interface Action
{
    /**
     * Get action name (standard is provider/action, for example: essentials/hello)
     * 
     * @return string
     */
    public function getName (): string;

    /**
     * Get training samples for CATI Tree model
     * Each element should be single string. It will be automatically tokenized
     * 
     * [
     *     'hello world',
     *     'hello',
     *     'amogus'
     * ]
     * 
     * @return array
     */
    public function getSamples (): array;

    /**
     * Execute action
     * 
     * @param Message $message - the message this action was predicted by
     * @param IO $io           - the IO that message was gotten by
     * @param array $context   - array of found context
     */
    public function execute (Message $message, IO $io, array $context);
}
