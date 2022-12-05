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
    abstract public function create($telegraph);
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
    public function create($telegraphText)
    {
        $slug = 'test_text_file_' . date('Y-m-d') . '.txt';
        $i = 1;
        while (file_exists($slug)) {
            $slug = 'test_text_file_' . date('Y-m-d') . '_' . $i++ . '.txt';
        }

        $telegraphText->slug = $slug;
        file_put_contents($slug, $telegraphText);
        return $telegraphText->slug;

    }

    public function read(string $slug)
    {
        $fileName = 'test_text_file_' . '/' . $slug . '.txt';
        if (file_exists($fileName) && filesize($fileName) > 0) {
            $savedData = unserialize(file_get_contents($fileName));
            $post = new TelegraphText($savedData['author'], $savedData['slug'], $savedData['fileStorage']);
            return $post;
        }
    }


    public function update(string $slug, $data): void
    {
        file_put_contents('test_text_file' . '/' . $slug . '.txt', serialize($data));
    }

    public function delete(string $slug): void
    {
        $file = 'test_text_file' . '/' . $slug . '.txt';
        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function list(): array
    {
        $allFiles = array_diff(scandir('test_text_file'), array('..', '.'));
        $result = [];
        foreach ($allFiles as $file) {
            $result[$file] = unserialize(file_get_contents('test_text_file' . '/' . $file));
        }
        return $result;
    }
}


$telegraphText = new TelegraphText('Vasiliy', 'test_text_file.txt', '');
$telegraphText->storeText();
$telegraphText->loadText();
$telegraphText->editText( 'Научиться работать с классами и объектами на практике.', 'Практическая работа' );
echo $telegraphText->text . PHP_EOL;

$fileStorage = new FileStorage();
$textStorage = $fileStorage->create($telegraphText);
var_dump($textStorage);
print_r($fileStorage->read('test_text_file.txt'));
$fileStorage->delete('test_text_file.txt');
