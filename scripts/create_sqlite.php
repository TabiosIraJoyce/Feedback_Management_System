<?php
$path = __DIR__ . '/../database/database.sqlite';
if (file_exists($path)) {
    unlink($path);
}
$pdo = new PDO('sqlite:' . $path);
$pdo->exec('CREATE TABLE IF NOT EXISTS __sqlite_init__(id INTEGER PRIMARY KEY)');
echo 'Created sqlite file at ' . $path . PHP_EOL;
