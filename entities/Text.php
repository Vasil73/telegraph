<?php


$textStorage = [];

function add(string $title, string $text, &$textStorage): void
{
    $textStorage[] = [
        'title' => $title,
        'text' => $text,
    ];
}

add('Заголовок 1', 'Текст 1', $textStorage);
add('Заголовок 2', 'Текст 2', $textStorage);
print_r($textStorage);

function remove(int $textNumber, array &$textStorage): bool
{
    if (isset($textStorage[$textNumber])) {
        unset($textStorage[$textNumber]);
        return false;
    }
    return true;
}

var_dump(remove(0, $textStorage));
var_dump(remove(5, $textStorage));

var_dump($textStorage);

function edit(int $textNumber, string $title, string $text, &$textStorage): bool
{
    if (isset($textStorage[$textNumber])) {
        $textStorage[$textNumber]['title'] = $title;
        $textStorage[$textNumber]['text'] = $text;
        return true;
    }
    return false;
}

edit(1, 'Еще один заголовок', 'Еще один текст', $textStorage);
print_r($textStorage);
var_dump(edit(7, 'Еще один заголовок 5', 'Еще один текст 5', $textStorage));
