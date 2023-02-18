<?php

//Создаём объект подключения к SQLite
$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');


$connection->exec('CREATE TABLE users (
    uuid TEXT NOT NULL 
      CONSTRAINT uuid_primary_key PRIMARY KEY,
    username TEXT NOT NULL 
      CONSTRAINT username_unique_key UNIQUE,
    first_name TEXT,
    last_name TEXT
  )');

$connection->exec('CREATE TABLE posts (
  post uuid TEXT NOT NULL 
    CONSTRAINT uuid_primary_key PRIMARY KEY,
  author uuid TEXT NOT NULL 
    CONSTRAINT username_unique_key UNIQUE,
  title TEXT,
  txt TEXT
)');

$connection->exec('CREATE TABLE comments (
  comment uuid TEXT NOT NULL 
    CONSTRAINT uuid_primary_key PRIMARY KEY,
  post uuid TEXT NOT NULL 
    CONSTRAINT username_unique_key UNIQUE,
  author uuid TEXT NOT NULL 
    CONSTRAINT username_unique_key UNIQUE,
  txt TEXT
)');


//Вставляем строку в таблицу пользователей
// $connection->exec(
//     "INSERT INTO users (first_name, last_name) VALUES ('Ivan', 'Nikitin')"
// );