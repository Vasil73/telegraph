<?php


require_once 'autoload.php';
const STORAGE = 'file-storage';
if (!file_exists('file_storage')) {
    mkdir('file_storage');
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
