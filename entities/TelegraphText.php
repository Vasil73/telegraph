<?php




class TelegraphText {
    private $text, $title, $author, $published, $slug, $fileStorage;


    public function __construct(string $author, string $published, string $slug, string $fileStorage)
    {
        $this->fileStorage = $fileStorage;
        $this->author = $author;
        $this->published = new DateTime($published); //date('Y-m-d');
        $this->slug = $slug;
    }

    public function __set(string $name, string $value)
    {
        switch ($name) {
            case 'author' :
                if (strlen($value) > 120) {
                    echo 'Длина строки не может привышать 120 символов' . PHP_EOL;
                    return;
                }
                $this->author = $value;
                break;

            case 'slug' :
                $pattern = '/[^A-Za-z0-9-_\/]/';
                if (preg_match($pattern, $value)) {
                    echo 'Ошибка! Поле slug может содержать только буквы латинского
                     алфавита, цифры, и символы (тире, нижнее подчеркивание, слэш)' . PHP_EOL;
                    return;
                }
                $this->slug = $value;
                break;
            case 'published' :
                if (strtotime($value) < date('Y-m-d')) {
                    echo 'Ошибка! Дата должна быть больше или равна текущей';
                    return;
                }
                $this->published = $value;
                break;
            case 'text' :
                $this->text = $value;
                $this->storeText();
                break;
            default:
                return;
        }

    }

    public function __get($name){

        if ($name === 'text') {
            return $this->loadText();
        } else {
            return $this->$name;
        }
    }

    private function storeText()
    {
        $addTextArray = ['title' => $this, 'text' => $this->text,
            'author' => $this->author, 'published' => $this->published, 'fileStorage' => $this->fileStorage];
        file_put_contents($this->slug, serialize($addTextArray));
    }

    private function loadText(): string
    {
        if (file_exists($this->slug)) {
            $addTextArray = unserialize(file_get_contents($this->slug));
            $this->title = $addTextArray['title'];
            $this->text = $addTextArray['text'];
            $this->author = $addTextArray['author'];
            $this->published = $addTextArray['published'];
            $this->fileStorage = $addTextArray['fileStorage'];
        }
        return false;
    }
    public function editText($text, $title){
        $this->text = $text;
        $this->title = $title;
    }
}

