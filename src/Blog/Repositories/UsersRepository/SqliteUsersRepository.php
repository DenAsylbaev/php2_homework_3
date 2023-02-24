<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UsersRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;

use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;


use \PDO;
use \PDOStatement;

class SqliteUsersRepository implements UsersRepositoryInterface
{
    private PDO $connection;
    public function __construct(PDO $connection) 
        {
            $this->connection = $connection;
        }
        
    public function save(User $user): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO users (first_name, last_name, uuid, username)
            VALUES (:first_name, :last_name, :uuid, :username)'
            );
            // Выполняем запрос с конкретными значениями
            $statement->execute([
            ':first_name' => $user->getName()->getFirstName(),
            ':last_name' => $user->getName()->getLastName(),
            ':uuid' => (string)$user->id(),
            ':username' => $user->getUsername()
            ]);
            
    }
    public function get(UUID $uuid): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE uuid = ?'
        );
        // $statement->execute([ -- КАК БЫЛО
        //     ':uuid' => (string)$uuid,
        // ]);

        $statement->execute([(string)$uuid]);
        // $result = $statement->fetch(PDO::FETCH_ASSOC);

        // print_r($result); //НЕ РАБОТАЕТ ПОИСК ПО id!!!!!!!!!!!!AAAAAABLYAAAAAAA!!!!
        // print_r('tttt');
        // die();

// Бросаем исключение, если пользователь не найден
        // if (false === $result) {
        //     throw new UserNotFoundException(
        //         "Cannot get user: $uuid"
        //     );
        // }
        return $this->getUser($statement, $uuid);

    }

    public function getByUsername(string $username): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE username = :username'
        );
        $statement->execute([
            ':username' => $username,
        ]);
        return $this->getUser($statement, $username);
    }

    private function getUser(PDOStatement $statement, string $errorname): User
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        // print_r($result); //НЕ РАБОТАЕТ ПОИСК ПО id!!!!!!!!!!!!AAAAAABLYAAAAAAA!!!!
        // print_r('tttt2');
        // die();

        if (false === $result) {
            throw new UserNotFoundException(
                "Cannot find user: $errorname"
            );
        }   
        return new User(
            new UUID($result['uuid']),
            $result['username'],
            new Name($result['first_name'], $result['last_name'])
        );
    }
}