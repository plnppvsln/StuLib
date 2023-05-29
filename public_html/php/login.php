<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Подключение к базе данных
    require_once('db.php');

    try {
        // Запрос для проверки наличия пользователя в таблице librarian
        $stmt = $db->prepare("SELECT * FROM librarian WHERE login = :username AND passwort = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Проверяем, найден ли пользователь в таблице librarian
        if ($stmt->rowCount() > 0) {
            // Пользователь найден в таблице librarian, сохраняем роль и логин в сессию
            $_SESSION['role'] = 'librarian';
            $_SESSION['login'] = $username;

            // Перенаправляем на страницу для библиотекаря
            header('Location: librarian.php');
            exit;
        } else {
            // Пользователь не найден в таблице librarian, проверяем таблицу reader
            $stmt = $db->prepare("SELECT * FROM reader WHERE email = :username AND passwort = :password");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            // Проверяем, найден ли пользователь в таблице reader
            if ($stmt->rowCount() > 0) {
                // Пользователь найден в таблице reader, сохраняем роль и логин в сессию
                $_SESSION['role'] = 'reader';
                $_SESSION['login'] = $username;

                // Перенаправляем на страницу для читателя
                header('Location: reader.php');
                exit;
            } else {
                // Пользователь не найден, выводим сообщение об ошибке
                $error = 'Неверные учетные данные';
            }
        }
    } catch (PDOException $e) {
        // Обработка ошибок подключения к базе данных
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Вход</title>
</head>
<body>
    <h1>Вход</h1>
    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <form action="login.php" method="post">
        <input type="text" name="username" placeholder="Логин" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Войти</button>
    </form>
</body>
</html>

