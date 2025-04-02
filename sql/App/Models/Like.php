<?php

namespace App\Models;

class Like extends Model {
    protected $fillable = [
        'user_id', 
        'likeable_type', 
        'likeable_id'
    ];

    protected $hidden = [
        'created_at'
    ];
    
    /**
     * Метод для получения автора лайка.
     *
     * @return User Автор лайка.
     */
    public function author(): User
    {
        return new User($this->getAttribute('user_id'));
    }
    
    /**
     * Метод для получения связанной модели.
     *
     * @return Model Связанная модель.
     */
    public function getRelated(): Model|\Exception
    {
        $type = $this -> getAttribute('likeable_type');
        $id = $this -> getAttribute('likeable_id');

        return match ($type) {
            'post' => new Post($id),
            'comment' => new Comment($id),
            default => throw new \Exception("Unknown likeable type: {$type}"),
        };
    }

    public function toggleLike(int $userId, string $likeableType, int $likeableId): bool
    {
        $existingLike = $this->database->read($this->table, 
            ['id'],
            [
                ['user_id', '=', $userId],
                ['likeable_type', '=', $likeableType],
                ['likeable_id', '=', $likeableId]
            ],
            1
        );

        if (!empty($existingLike)) {
            // Лайк уже есть - удаляем его
            return $this->database->delete($this->table, [
                ['user_id', '=', $userId],
                ['likeable_type', '=', $likeableType],
                ['likeable_id', '=', $likeableId]
            ]);
        } else {
            // Лайка нет - добавляем
            return $this->database->create($this->table, [
                'user_id' => $userId,
                'likeable_type' => $likeableType,
                'likeable_id' => $likeableId
            ]);
        }
    }

    public function getLikesCount(string $likeableType, int $likeableId): int
    {
        $result = $this->database->read($this->table, 
            ['COUNT(*) as count'],
            [
                ['likeable_type', '=', $likeableType],
                ['likeable_id', '=', $likeableId]
            ]
        );
        
        return $result[0]['count'] ?? 0;
    }
}
