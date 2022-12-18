<?php


interface LoggerInterface {

    public function logMessage(string $error): void;
    public function lastMessages(int $num): array;
}

interface EventListenerInterface {

    public function attachEvent(string $className, callable $callback);
    public function detouchEvent (string $className);
}