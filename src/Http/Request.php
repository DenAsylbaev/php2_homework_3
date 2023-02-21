<?php

namespace GeekBrains\LevelTwo\Http;

use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\JsonException;


class Request
{        
    private array $get;
    private array $server;
    // private string $body;

    public function __construct(
        array $get,
        array $server
        // string $body,
    ) {
        $this->get = $get;
        $this->server = $server;
        // $this->body = $body;
    }

    public function path(): string
    {   
        // print_r($this->server['REQUEST_URI']);
        // die();

        // В суперглобальном массиве $_SERVER
        // значение URI хранится под ключом REQUEST_URI
        if (!array_key_exists('REQUEST_URI', $this->server)) {

        // Если мы не можем получить URI - бросаем исключение
            throw new HttpException('Cannot get path from the request');
        }

        // Используем встроенную в PHP функцию parse_url
        $components = parse_url($this->server['REQUEST_URI']);
        if (!is_array($components) || !array_key_exists('path', $components)) {
            
        // Если мы не можем получить путь - бросаем исключение
            throw new HttpException('Cannot get path from the request');
        }
        return $components['path'];
    }

    // Метод для получения значения
    // определённого параметра строки запроса
    // Напрмер, для http://example.com/some/page?x=1&y=acb
    // значением параметра x будет строка '1'
    public function query(string $param): string
    {
        if (!array_key_exists($param, $this->get)) {
            // Если нет такого параметра в запросе - бросаем исключение
            throw new HttpException(
                "No such query param in the request: $param"
            );
        }
        $value = trim($this->get[$param]);
        if (empty($value)) {
            // Если значение параметра пусто - бросаем исключение
            throw new HttpException(
                "Empty query param in the request: $param"
            );
        }
        return $value;
    }
    // Метод для получения значения
    // определённого заголовка
    public function header(string $header): string
    {
        // В суперглобальном массиве $_SERVER
        // имена заголовков имеют префикс 'HTTP_',
        // а знаки подчёркивания заменены на минусы
        $headerName = mb_strtoupper("http_". str_replace('-', '_', $header));
        if (!array_key_exists($headerName, $this->server)) {
            // Если нет такого заголовка - бросаем исключение
            throw new HttpException("No such header in the request: $header");
        }
        $value = trim($this->server[$headerName]);
        if (empty($value)) {
            // Если значение заголовка пусто - бросаем исключение
            throw new HttpException("Empty header in the request: $header");
        }
        return $value;
    }
}