<?php
session_start();

// Очищаем данные авторизации
unset($_SESSION['login']);
unset($_SESSION['role']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Выход</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .message {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="message">
        <h1>Выход успешно совершен!</h1>
        <h2>До новых встреч!</h2>
    </div>
</body>
</html>

