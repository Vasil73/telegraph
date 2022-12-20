<?php


require_once 'interfaces.php';
require_once 'TelegraphText.php';



abstract class Storage implements LoggerInterface, EventListenerInterface
{
    abstract public function create(TelegraphText $object): string;
    abstract public function read(string $slug);
    abstract public function update(string $slug, TelegraphText $data): void;
    abstract public function delete(string $slug): void;
    abstract public function list();
    abstract public function logMessage(string $error): void;
    abstract public function lastMessages(int $num): array;
    abstract public function attachEvent(string $className, callable $callback);
    abstract public function detouchEvent (string $className);
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


abstract class User implements EventListenerInterface
{
    public $id, $name, $role;

    abstract public function getTextsToEdit($id, $name, $role): string;
    abstract public function attachEvent(string $className, callable $callback);
    abstract public function detouchEvent (string $className);
}


require_once 'FileStorage.php';


$telegraphText = new TelegraphText( 'Vasiliy', '19.12.2022',
    'test_text_file', 'C:xampp\htdocs\Telegraph_Project\telegraph\file_storage');
$fileStorage = new FileStorage();
$telegraphText->storeText();
$telegraphText->loadText();
$telegraphText->editText( 'Научиться работать с классами и объектами на практике.', 'Практическая работа' );
echo $telegraphText->text . PHP_EOL;

$textStorage = $fileStorage->create($telegraphText);
var_dump($textStorage);
print_r($fileStorage->read('test_text_file_2022-12-08.txt'));
$fileStorage->delete('test_text_file_2022-12-08.txt');
$fileStorage->update('test_text_file_2022-12-08.txt', $telegraphText);
print_r($fileStorage->list()) ;
