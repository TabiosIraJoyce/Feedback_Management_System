<?php
$db = new SQLite3(__DIR__ . '/../database/database.sqlite');
$result = $db->querySingle('PRAGMA integrity_check');
echo $result . PHP_EOL;
