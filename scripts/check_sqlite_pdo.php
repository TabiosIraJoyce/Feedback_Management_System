<?php
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
    $stmt = $pdo->query('PRAGMA integrity_check');
    $result = $stmt->fetchColumn();
    echo $result . PHP_EOL;
} catch (PDOException $e) {
    echo 'PDO error: ' . $e->getMessage() . PHP_EOL;
}
