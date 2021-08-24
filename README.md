<h1 align="center">🚀 Serpentine</h1>

**Serpentine** is a platform to create an intellectual bot on in PHP 8

## Installation

```
composer require serpentine/serpentine
```

## Brief info

This package provides only foundation for your bot system. It realizes a lot of interfaces, basic `Serpentine` class and the machine learning model - [CATI Tree](https://github.com/krypt0nn/cati-tree)

That means that you can't just *use* this project. You should *extend* it, install or create other modules and build ***your own*** bot with native language commands understanding

More information you can see inside the code or in the Serpentine packages

<p align="center"><img src="https://i.ibb.co/wd8PT9Q/Untitled-Diagram.png"></p>

This is how Serpentine looks inside

The first thing you meet is `IO` objects. This objects realizes functionality to work with user input and output. For example, `Telegram IO` will realize methods to get new messages from telegram user and send answers back

After `IO` receives a message - this will be applied to every `InputHandler`. These callbacks will do they stuff and if it needs - disallow new message being processed

If all the input handlers will allow the message to be processed - then this message will be applied to the `ContextIdentifier`s. These ones will try to parse some useful info from the message text like dates and times

After that, action predictor (CATI Tree model) will try to find the `Action` for this message and if it found - then it will be executed

## Example

```php
<?php

require 'vendor/autoload.php';

use Serpentine\{
    Serpentine,
    Action,
    IO
};

class HelloWorldAction implements Action
{
    public function getName (): string
    {
        return 'Example/HelloWorld';
    }

    public function getSamples (): array
    {
        return [
            'Hello',
            'Hi'
        ];
    }

    public function execute (Message $message, IO $io, array $context)
    {
        $io->sendMessage ($message->author, Message::new ('Hi, man!'));
    }
}

class ConsoleIO implements IO
{
    public function getUpdates (): array
    {
        return [readline ('> ')];
    }
    
    public function sendMessage (int $receiver, Message $message): self
    {
        echo $message->getText () . PHP_EOL;
    }

    public function query (string $method, array $params = []): ?array
    {
        return null;
    }
}

$serpentine = new Serpentine ([
    'actions' => [
        new HelloWorldAction
    ],
    'pipeline' => [
        new ConsoleIO
    ]
]);

while (true)
    $serpentine->update();

```

<br>

Author: [Nikita Podvirnyy](https://vk.com/technomindlp)
