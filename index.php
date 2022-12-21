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


abstract class User implements EventListenerInterface
{
    protected $id, $name, $role;

    abstract public function getTextsToEdit($id, $name, $role): string;
    abstract public function attachEvent(string $className, callable $callback);
    abstract public function detouchEvent (string $className);
}



class FileStorage extends Storage
{


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
        if (is_callable($callback)) {
            call_user_func('attachEvent', $callback);
        }
    }


    public function detouchEvent(string $className)
    {
        // TODO: Implement detouchEvent() method.
    }


    public function create(TelegraphText $object): string
    {
        $fileName = $object->slug . '_' . date('d-m-Y');
        if (file_exists('file_storage' . '/' . $fileName . '.txt')) {
            $i = 0;
            do {
                $i++;
                $fileNameConflict = $fileName . '_' . $i;
            } while (file_exists('file_storage' . '/' . $fileNameConflict . '.txt'));
            $fileName = $fileNameConflict;
        }
        $object->slug = $fileName;

        $data = [
            'title' => $object->title,
            'text' => $object->text,
            'author' => $object->author,
            'published' => $object->published,
            'slug' => $object->slug,
        ];

        file_put_contents('file_storage' . '/' . $fileName . '.txt', serialize($data));
        return $fileName;
    }

    public function read(string $slug)
    {
        if (file_exists($slug) && filesize($slug) > 0) {
            $savedData = unserialize(file_get_contents($slug));
            $post = new TelegraphText($savedData['author'], $savedData['published'],
                $savedData['slug'], $savedData['fileStorage']);
            return $post;
        }
        return false;
    }


    public function update(string $slug, TelegraphText $data): void
    {
        file_put_contents('test_text_file' . '.txt', serialize($data));
    }

    public function delete(string $slug): void
    {
        $file = 'file_storage' . '/' . $slug . '.txt';
        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function list(): array
    {
        $allFiles = array_diff(scandir('file_storage'), array('.', '..'));
        $result = [];
        foreach ($allFiles as $file) {
            $result[$file] = unserialize(file_get_contents('file_storage' . '/' . $file));
        }
        return $result;
    }
}


$telegraphText = new TelegraphText( 'Vasiliy', '19.12.2022',
    'test_text_file', 'C:xampp\htdocs\Telegraph_Project\telegraph\file_storage');
$fileStorage = new FileStorage();
//$telegraphText->storeText();
//$telegraphText->loadText();
$telegraphText->editText( 'Научиться работать с классами и объектами на практике.', 'Практическая работа' );
echo $telegraphText->text . PHP_EOL;

$textStorage = $fileStorage->create($telegraphText);
var_dump($textStorage);
print_r($fileStorage->read('test_text_file_2022-12-08.txt'));
$fileStorage->delete('test_text_file_2022-12-08.txt');
$fileStorage->update('test_text_file_2022-12-08.txt', $telegraphText);
print_r($fileStorage->list()) ;
