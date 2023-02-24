<?php

use GeekBrains\LevelTwo\Blog\Exceptions\AppException;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepositories\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Actions\Users\FindByUsername;
use GeekBrains\LevelTwo\Http\Actions\Posts\FindPostByUuid;
use GeekBrains\LevelTwo\Http\Actions\Posts\CreatePost;
use GeekBrains\LevelTwo\Http\Actions\Posts\DeletePost;
use GeekBrains\LevelTwo\Http\Actions\Comments\CreateComment;


require_once __DIR__ . '/vendor/autoload.php';

// Создаём объект запроса из суперглобальных переменных
$request = new Request(
    $_GET, 
    $_SERVER,
    file_get_contents('php://input'),
);

// $parameter = $request->query('some_parameter');
// $header = $request->header('Some-Header');
// $path = $request->path();

try {
    // Пытаемся получить путь из запроса
    $path = $request->path();

} catch (HttpException $e) {

    // Отправляем неудачный ответ,
    // если по какой-то причине
    // не можем получить путь
    (new ErrorResponse($e->getMessage()))->send();

    // Выходим из программы
    return;
}

// try {
//     // Пытаемся получить HTTP-метод запроса

//     $method = $request->method();

//     } catch (HttpException) {
//         // Возвращаем неудачный ответ,
//         // если по какой-то причине
//         // не можем получить метод
//         (new ErrorResponse($e->getMessage()))->send();
//         return;
//     }

$routes = [
    // Добавили ещё один уровень вложенности
    // для отделения маршрутов,
    // применяемых к запросам с разными методами
    'GET' => [
        '/users/show' => new FindByUsername(
            new SqliteUsersRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),
            '/posts/show' => new FindPostByUuid(
                new SqlitePostsRepository(
                    new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),
    ],
    'POST' => [
        // Добавили новый маршрут
        '/posts/create' => new CreatePost(
            new SqlitePostsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            ),
            new SqliteUsersRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),
        '/posts/comment' => new CreateComment(
            new SqliteCommentsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            ),
            new SqlitePostsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            ),
            new SqliteUsersRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),
    ],
    'DELETE' => [
        '/posts' => new DeletePost(
            new SqlitePostsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),
    ],
];

$method = $request->method();


// Если у нас нет маршрута для пути из запроса -
// отправляем неуспешный ответ
if (!array_key_exists($method, $routes)) {

    (new ErrorResponse('Not found'))->send();
    return;
}
// Ищем маршрут среди маршрутов для этого метода
if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Not found'))->send();

    return;
}

// Выбираем действие по методу и пути
$action = $routes[$method][$path];

// // Выбираем найденное действие
// $action = $routes[$path];
try {
    // Пытаемся выполнить действие,
    // при этом результатом может быть
    // как успешный, так и неуспешный ответ
    $response = $action->handle($request);
} catch (AppException $e) {
    // Отправляем неудачный ответ,
    // если что-то пошло не так

    (new ErrorResponse($e->getMessage()))->send();
}
// Отправляем ответ
$response->send();
