<?php

use GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepositories\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Person\Name;


require_once __DIR__ . '/vendor/autoload.php';

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$postRepository = new SqlitePostsRepository($connection);
$userRepository = new SqliteUsersRepository($connection);
$commentRepository = new SqliteCommentsRepository($connection);


$name1 = new Name('Denis', 'Denisov');
$name2 = new Name('Maxim', 'Maximov');

$user1 = new User(
    UUID::random(),
    'Den',
    $name1
);

$user2 = new User(
    UUID::random(),
    'Max',
    $name2
); 

// $userFromDB = $userRepository->get(new UUID(''));
// $postFromDB = $postRepository->get(new UUID(''));

// $userFromDB_2 = $userRepository->get(new UUID('ee1dc807-7cb9-4e0c-8e65-89ec06c1fde2'));

// $postId1 = UUID::random();
// $postId2 = UUID::random();
// $postId3 = UUID::random();

// $commentId = UUID::random();

// $comment = new Comment(
//     $commentId,
//     $userFromDB,
//     $postFromDB,
//     'comment_1'
// );


// $post1 = new Post(
//     $postId1,
//     $user1,
//     'POST_FROM_DEN',
//     'HELLO'
// );
// $post2 = new Post(
//     $postId2,
//     $user2,
//     'POST_FROM_MAX',
//     'HELLOOO'
// );

// $commentRepository->save($comment);
// $postRepository->save($post1);
// $postRepository->save($post2);


// $userRepository->save($user1);
// $userRepository->save($user2);


// $newPost = $postsRepository->get(new UUID(''));
// $newUser = $userRepository->get(new UUID(''));


$postRepository->delete(new UUID('912b016c-15d3-4459-ae7f-88c8eb9ffc84'));