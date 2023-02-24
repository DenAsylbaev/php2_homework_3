<?php
namespace GeekBrains\LevelTwo\Http\Actions\Posts;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;

class DeletePost implements ActionInterface
{
    private PostsRepositoryInterface $postsRepository;

    // Внедряем репозитории статей и пользователей
    public function __construct(
        PostsRepositoryInterface $postsRepository
    ) {
        $this->postsRepository = $postsRepository;
    }

    public function handle(Request $request): Response
    {
        // Пытаемся создать UUID поста из данных запроса
        try {
            // $postUuid = new UUID($request->jsonBodyField('uuid'));
            $postId = $request->query('uuid');
            // print_r($postId);

            $postUuid = new UUID($postId);
            // print_r($postUuid);

        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        // Пытаемся найти пост в репозитории и удалить
        try {
            $this->postsRepository->delete($postUuid);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        // Генерируем UUID для новой статьи
        // $newPostUuid = UUID::random();

        // try {
        //     // Пытаемся создать объект статьи
        //     // из данных запроса
        //     $post = new Post(
        //     $newPostUuid,
        //     $this->usersRepository->get($authorUuid),
        //     $request->jsonBodyField('title'),
        //     $request->jsonBodyField('text'),
        //     );
        // } catch (HttpException $e) {
        //     return new ErrorResponse($e->getMessage());
        // }

        // // Сохраняем новую статью в репозитории
        // $this->postsRepository->save($post);
        
        // Возвращаем успешный ответ,
        // содержащий UUID новой статьи
        return new SuccessfulResponse([
            'deleted post uuid' => (string)$postUuid,
        ]);
    }
}