<?php


class TelegraphText {
    public $text;
    public $title;
    public $author;
    public $published;
    public $slug;
    public $fileStorage;

    public function __construct(string $author, string $slug, string $fileStorage)
    {
        $this->fileStorage = $fileStorage;
        $this->author = $author;
        $this->published = date('Y-m-d');
        $this->slug = $slug;
    }

    public function storeText(): void
    {
        $addTextArray = ['title' => $this, 'text' => $this->text,
            'author' => $this->author, 'published' => $this->published, 'fileStorage' => $this->fileStorage];
        file_put_contents($this->slug, serialize($addTextArray));
    }

    public function loadText()
    {
        if (file_exists($this->slug)) {
            $addTextArray = unserialize(file_get_contents($this->slug));
            $this->title = $addTextArray['title'];
            $this->text = $addTextArray['text'];
            $this->author = $addTextArray['author'];
            $this->published = $addTextArray['published'];
            $this->fileStorage = $addTextArray['fileStorage'];
        }

    }
    public function editText($text, $title){
        $this->text = $text;
        $this->title = $title;
    }
}


abstract class Storage
{
    abstract public function create($object): string;
    abstract public function read(string $slug);
    abstract public function update(string $slug, $data): void;
    abstract public function delete(string $slug): void;
    abstract public function list();
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

abstract class User
{
    public $id, $name, $role;

    abstract public function getTextsToEdit($id, $name, $role): string;
}


class FileStorage extends Storage
{
    public function create($object): string
    {
        $fileName = $object->slug . '_' . date('Y-m-d');
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
            $post = new TelegraphText($savedData['author'], $savedData['slug'], $savedData['fileStorage']);
            return $post;
        }
        return false;
    }


    public function update(string $slug, $data): void
    {
        file_put_contents( 'test_text_file' . '.txt', serialize($data));
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
            $result[$file] = unserialize(file_get_contents('file_storage' . '/' .  $file));
        }
        return $result;
    }
}


$telegraphText = new TelegraphText( 'Vasiliy', 'test_text_file', 'C:xampp\htdocs\Telegraph_Project\telegraph\storage');
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
