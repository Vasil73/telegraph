<?php

require_once 'interfaces/LoggerInterface.php';
require_once 'interfaces/EventListenerInterface.php';

abstract class Storage implements LoggerInterface, EventListenerInterface
{
    abstract public function create(TelegraphText $object): string;
    abstract public function read(string $slug);
    abstract public function update(string $slug, TelegraphText $data): void;
    abstract public function delete(string $slug): void;
    abstract public function list();
    public function logMessage(string $error): void
    {
        // TODO: Implement logMessage() method.
    }
    public function lastMessages(int $num): array
    {
        $numArr = [];
        return $numArr;
    }
    public function attachEvent(string $className, callable $callback)
    {
        // TODO: Implement attachEvent() method.
    }
    public function detouchEvent (string $className)
    {
        // TODO: Implement detouchEvent() method.
    }
}

abstract class View
{
    public $storage;

    public function __construct(Storage $object)
    {
        $this->storage = $object;
    }
    abstract public function displayTextById(string $id): string;
    abstract public function displayTextByUrl(string $url): string;
}