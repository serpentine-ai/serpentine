<?php

namespace Serpentine;

use Serpentine\Events\{
    InputHandler,
    IntervalEvent,
    TickEvent
};

use CATI\RandomForest;
use Wamania\Snowball\StemmerFactory;

/**
 * Serpentine core
 */
class Serpentine
{
    # CATI Tree model's path
    public static string $model_path = __DIR__ .'/../model.json';

    # CATI Tree model
    protected ?RandomForest $forest = null;

    protected array $pipeline = [];
    protected array $contextIdentifiers = [];
    protected array $actions = [];

    protected array $intervalEvents = [];
    protected array $tickEvents     = [];
    protected array $inputHandlers  = [];

    /**
     * [@param array $params = []] - preset of Serpentine params
     */
    public function __construct (array $params = [])
    {
        foreach ($params as $name => $param)
            $this->$name = $param;

        if (file_exists (self::$model_path))
            $this->forest = RandomForest::load (json_decode (file_get_contents (self::$model_path), true));
    }

    public function addIO (IO $io): self
    {
        $this->pipeline[] = $io;

        return $this;
    }

    public function addContextIdentifier (ContextIdentifier $contextIdentifier): self
    {
        $this->contextIdentifiers[$contextIdentifier->getName ()] = $contextIdentifier;

        return $this;
    }

    public function addAction (Action $action): self
    {
        $this->actions[$action->getName ()] = $action;

        return $this;
    }

    public function addInputHandler (InputHandler $handler): self
    {
        $this->inputHandlers[] = $handler;

        return $this;
    }

    public function addIntervalEvent (IntervalEvent $event): self
    {
        $this->intervalEvents[] = $event;

        return $this;
    }

    public function addTickEvent (TickEvent $event): self
    {
        $this->tickEvents[] = $event;

        return $this;
    }

    public function getAction (string $name): ?Action
    {
        return $this->actions[$name] ?? null;
    }

    public function getContextIdentifier (string $name): ?ContextIdentifier
    {
        return $this->contextIdentifiers[$name] ?? null;
    }

    /**
     * Get all tokenized training samples
     * 
     * [@param string $language = null] (by default uses config language value)
     * 
     * @return array
     */
    public function getSamples (string $language = null): array
    {
        $samples = [];

        foreach ($this->actions as $action)
            $samples = array_merge ($samples, array_map (
                fn ($sample) => self::getTokens ($sample, $language),
                $action->getSamples ()));

        return $samples;
    }

    /**
     * Get tokens from text
     * 
     * @param string $text
     * [@param string $language = null] (by default uses config language value)
     * 
     * @return array
     */
    public static function getTokens (string $text, string $language = null): array
    {
        $stemmer = StemmerFactory::create ($language ?: Config::get ('language'));

        return array_values (array_filter (array_map (function (string $word) use ($stemmer)
        {
            $word = trim ($word, '!?.,()[]{}');

            return strlen ($word) > 2 ?
                $stemmer->stem ($word) : null;
        }, preg_split ('/\s/', mb_strtolower ($text)))));
    }

    /**
     * Update Serpentine state
     * Executes tick and interval events,
     * runs pipeline, executes actions...
     * 
     * [@param callable $callback = null] - custom Message processor
     * 
     * @return self
     */
    public function update (callable $callback = null): self
    {
        if ($this->forest === null)
            throw new \Exception ('Model is not initialized');

        /**
         * Tick events
         */
        foreach ($this->tickEvents as $id => $event)
            if (!$event->execute ())
                unset ($this->tickEvents[$id]);

        /**
         * Interval events
         */
        $time = time ();

        foreach ($this->intervalEvents as $id => $event)
            if ($event['time'] < $time)
            {
                if (!$event['event']->execute ())
                    unset ($this->intervalEvents[$id]);

                else $event['time'] = $time + $event['event']->getTick ();
            }

        /**
         * Pipeline
         */
        foreach ($this->pipeline as $io)
            foreach ($io->getUpdates () as $message)
            {
                if ($callback !== null)
                    $callback ($message, $io);

                else
                {
                    $action = $this->predict ($message);
                    $continue = true;

                    foreach ($this->inputHandlers as $handler)
                        $continue &= $handler->handle ($message, $action, $io);

                    if ($continue)
                    {
                        $context = [];
                        
                        foreach ($this->contextIdentifiers as $identifier)
                            $context[$identifier->getName ()] = $identifier->identify ($message, $action, $io);

                        if ($action !== null)
                            $action->execute ($message, $io, $context);
                    }
                }
            }

        return $this;
    }

    /**
     * Predict Action
     * 
     * @param Message $message
     * 
     * @return Action|null
     */
    public function predict (Message $message): ?Action
    {
        $probabilities = $this->forest->probability ($message->getTokens ());

        if ($probabilities['null'] >= 0.5)
            return null;

        else
        {
            arsort ($probabilities['categories']);

            return $this->actions[array_keys ($probabilities['categories'])[0]] ?? null;
        }
    }

    /**
     * Train CATI Tree model
     * 
     * @return self
     */
    public function trainModel (): self
    {
        $samples = $this->getSamples ();
        $hash = md5 (join ($samples));

        if (file_exists (self::$model_path))
        {
            if (json_decode (file_get_contents (self::$model_path))->hash == $hash)
                return $this;
        }

        $this->forest = RandomForest::create (
            $samples,
            Config::get ('randomForest.minThreshold'),
            Config::get ('randomForest.maxThreshold'),
            Config::get ('randomForest.forestSize')
        );

        file_put_contents (self::$model_path, json_encode ([
            'model' => $this->forest->export (),
            'hash'  => $hash
        ]));

        return $this;
    }
}
