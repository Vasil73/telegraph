<?php


require_once 'autoload.php';


$telegraphText = new TelegraphText( 'Vasiliy', '19.12.2022',
    'test_text_file', 'C:xampp\htdocs\Telegraph_Project\telegraph\file_storage');
$fileStorage = new FileStorage();
//$telegraphText->storeText();
//$telegraphText->loadText();
$telegraphText->editText( 'сдрасте', 'заголовок' );
echo $telegraphText->text . PHP_EOL;

$textStorage = $fileStorage->create($telegraphText);
var_dump($textStorage);
print_r($fileStorage->read('test_text_file_2022-12-08.txt'));
$fileStorage->delete('test_text_file_2022-12-08.txt');
$fileStorage->update('test_text_file_2022-12-08.txt', $telegraphText);
print_r($fileStorage->list()) ;
