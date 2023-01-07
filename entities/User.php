<?php

    require_once 'interfaces/EventListenerInterface.php';
    require_once 'entities/FileStorage.php';


abstract class User implements EventListenerInterface
{
    protected $id, $name, $role;

    abstract public function getTextsToEdit($id, $name, $role): string;
    abstract public function attachEvent(string $className, callable $callback);
    abstract public function detouchEvent (string $className);
}