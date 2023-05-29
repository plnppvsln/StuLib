<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dbhost = 'localhost';
$dbname = 'b916631i_library';
$username = 'b916631i_library';
$password = 'Pvasilievnaa02';

try {
    // Устанавливаем соединение с базой данных и настройки PDO
    $db = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // Дополнительные настройки, если необходимо

} catch (PDOException $e) {
    // В случае ошибки подключения к базе данных
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
