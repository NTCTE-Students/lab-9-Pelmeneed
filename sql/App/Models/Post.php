<?php

namespace App\Models;

class Post extends Model {
    protected string $table = 'posts';

    protected array $fillable = [
        'heading',
        'body',
        'user_id',
    ];
    
    /**
     * Переопределение конструктора. При создании объекта класса Post сразу загружаем данные из БД по id если он передан.
     *
     * @param ?int $id id поста.
     * @return void
     */
    public function __construct(?int $id = null)
    {
        parent::__construct();

        if ($id) {
            $this -> getById($id);
        }
    }
    
    /**
     * Метод для получения автора поста.
     *
     * @return User Автор поста.
     */
    public function author(): User
    {
        return new User($this -> getAttribute('user_id'));
    }
}