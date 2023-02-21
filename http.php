<?php

use GeekBrains\LevelTwo\Blog\Exceptions\AppException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Http\Actions\Users\CreateUser;
use GeekBrains\LevelTwo\Http\Actions\Users\FindByUsername;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;

require_once __DIR__ . '/vendor/autoload.php';

$request = new Request($_GET, $_SERVER);

// Получаем данные из объекта запроса
// $parameter = $request->query('some_parameter');
// $header = $request->header('Some-Header');

print_r($request->path());
die();


$response = new SuccessfulResponse([
    'message' => 'Hello from PHP',
]);

$response->send();

// die();

echo 'Hello from PHP';
