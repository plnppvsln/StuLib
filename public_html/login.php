<?php
session_start();

// Побключаем файл, в котором идет подключение к бд
require_once('php/db.php');

// Проверяем, были ли отправлены данные формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем введенный логин и пароль из формы
    $login = $_POST['username'];
    $password = $_POST['password'];

    // Проверяем, является ли пользователь библиотекарем
    $query = $db->prepare("SELECT * FROM librarian WHERE login = :login AND password = :password");
    $query->bindParam(':login', $login);
    $query->bindParam(':password', $password);
    $query->execute();
    $librarian = $query->fetch(PDO::FETCH_ASSOC);

    // Проверяем, является ли пользователь читателем
    $query = $db->prepare("SELECT * FROM reader WHERE email = :email AND password = :password");
    $query->bindParam(':email', $login);
    $query->bindParam(':password', $password);
    $query->execute();
    $reader = $query->fetch(PDO::FETCH_ASSOC);

    // Проверяем результаты запросов
    if ($librarian) {
        // Если ользователь является библиотекарем, переводим его на нужный интерфейс
        $_SESSION['login'] = $librarian['login'];
        $_SESSION['role'] = 'librarian';
        header('Location: librarian.php');
        exit();
    } elseif ($reader) {
        // Если ользователь является читателем, переводим его на нужный интерфейс
        $_SESSION['login'] = $reader['email'];
        $_SESSION['role'] = 'reader';
        header('Location: reader.php');
        exit();
    } else {
        // Неверный логин или пароль
        echo 'Неверный логин или пароль';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" type="text/css" href="style/style.css">
</head>
<body>
  <div class="container">
    <h1>Добро пожаловать <br>в студенческую библиотеку!</h1>
    <p>Для дальнейшей работы необходимо авторизоваться.</p>
    <form method="post" action="login.php"> 
      <input type="text" name="username" placeholder="Логин или Email">
      <input type="password" name="password" placeholder="Пароль">
      <input type="submit" value="Войти">
    </form>
  </div>
</body>
</html>
