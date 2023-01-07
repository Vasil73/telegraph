<?php

interface EventListenerInterface {

    public function attachEvent(string $className, callable $callback);
    public function detouchEvent (string $className);
}