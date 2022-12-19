<?php

require_once 'index.php';
require_once 'interfaces.php';


class FileStorage extends Storage
{

    public function logMessage(string $error): void
    {
    }


    public function lastMessages(int $num): array
    {
        $example = [];
        return $example;
    }


    public function attachEvent(string $className, callable $callback): void
    {
        if (is_callable($callback)) {
            call_user_func('attachEvent', $callback);
        }
    }

    public function detouchEvent(string $className): void
    {
    }


    public function create($object): string
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
            $post = new TelegraphText($savedData['author'], $savedData['published'], $savedData['slug'], $savedData['fileStorage']);
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