<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories;

use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;

use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;


use \PDO;
use \PDOStatement;

class SqlitePostsRepository implements PostsRepositoryInterface
{
    private PDO $connection;
    public function __construct(PDO $connection) 
        {
            $this->connection = $connection;
        }
        
    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, txt)
            VALUES (:uuid, :author_uuid, :title, :txt)'
            );
            // Выполняем запрос с конкретными значениями
            $statement->execute([
                ':uuid' => $post->id(),
                ':author_uuid' => $post->getAuthorId(),
                ':title' => (string)$post->getTitle(),
                ':txt' => $post->getText()
            ]);
            
    }
    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE uuid = ?'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

// исключение, если пост не найден
        if (false === $result) {
            throw new PostNotFoundException(
                "Cannot get post: $uuid"
            );
        }
        $userRepo = new SqliteUsersRepository($this->connection); // чтоб юзера получить потом
        return new Post(
            new UUID($result['uuid']),
            $userRepo->get($result['author_uuid']),
            $result['title'],
            $result['txt']        
        );
    }
}