<?php

namespace App\Models;

class Comment extends Model {
    protected string $table = 'comments';

    protected array $fillable = [
        'body',
        'post_id',
        'user_id',
        'parent_id',
    ];
    protected array $hidden = [
        'created_at',
        'updated_at'
    ];
    
    /**
     * Переопределенный метод конструктора модели Comment. Данное переопределение позволяет создавать объекты модели Comment по идентификатору если он передан.
     *
     * @param ?int $id идентификатор комментария.
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
     * Метод возвращает автора комментария.
     *
     * @return ?User автор комментария.
     */
    public function author(): ?User
    {
        return new User($this -> getAttribute('user_id'));
    }
    
    /**
     * Метод возвращает пост к которому относится комментарий.
     *
     * @return ?Post пост к которому относится комментарий.
     */
    public function post(): ?Post
    {
        return new Post($this -> getAttribute('post_id'));
    }
    
    /**
     * Метод возвращает родительский комментарий.
     *
     * @return ?Comment родительский комментарий.
     */
    public function parentComment(): ?Comment
    {
        return new Comment($this -> getAttribute('parent_id'));
    }
    
    /**
     * Функция, которая проверяет, есть и у комментария ответы.
     *
     * @return bool возвращается true если присутствуют связанные комментарии и false если нет.
     */
    public function haveChildren(): bool
    {
        if ($this -> current_record !== null) {
            return !empty($this -> database -> read($this -> table, ['id'], [['parent_id', '=', $this -> getAttribute('id')]], 1));
        } else return false;
    }
}