<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\CommentsRepositories;

use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;

use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories\SqlitePostsRepository;

use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;


use \PDO;
use \PDOStatement;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{
    private PDO $connection;
    public function __construct(PDO $connection) 
        {
            $this->connection = $connection;
        }
        
    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, post_uuid, author_uuid, txt)
            VALUES (:uuid, :post_uuid, :author_uuid, :txt)'
            );
            // Выполняем запрос с конкретными значениями
            $statement->execute([
            ':uuid' => $comment->id(),
            ':post_uuid' => $comment->getPostId(),
            ':author_uuid' => $comment->getAuthorId(),
            ':txt' => $comment->getText()
            ]);
            
    }
    public function get(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE uuid = ?'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

// исключение, если не найден
        if (false === $result) {
            throw new CommentNotFoundException(
                "Cannot get comment: $uuid"
            );
        }
        $userRepo = new SqliteUsersRepository($this->connection); // чтоб юзера получить потом
        $postRepo = new SqlitePostsRepository($this->connection); // чтоб пост получить потом

        return new Comment(
            new UUID($result['uuid']),
            $userRepo->get($result['author_uuid']),
            $postRepo->get($result['post_uuid']),
            $result['txt']        
        );
    }
}