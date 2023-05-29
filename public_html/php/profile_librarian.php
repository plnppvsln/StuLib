<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'librarian') {
    header("Location: index.php"); // Перенаправляем на страницу входа, если пользователь не авторизован как библиотекарь
    exit();
}

// Подключение к базе данных
require_once 'db.php';

try {
    // Запрос для получения информации о библиотекаре
    $query = "SELECT * FROM librarian WHERE login = :login";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':login', $_SESSION['login']);
    $stmt->execute();

    // Получение данных о библиотекаре
    $librarian = $stmt->fetch(PDO::FETCH_ASSOC);

    // Проверка, найдены ли данные о библиотекаре
    if (!$librarian) {
        die("Ошибка: библиотекарь не найден.");
    }

    // Извлечение данных о библиотекаре
    $name = $librarian['name'];
    $phone = $librarian['phone'];
    $email = $librarian['email'];

} catch (PDOException $e) {
    // В случае ошибки выполнения запроса к базе данных
    die("Ошибка выполнения запроса: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Личный кабинет</title>
</head>
<body>
    <h1>Личный кабинет библиотекаря</h1>
    <!-- Вывод информации о библиотекаре -->
    <p>Имя: <?php echo $name; ?></p>
    <p>Телефон: <?php echo $phone; ?></p>
    <p>Email: <?php echo $email; ?></p>

    
    <form action="php/logout.php" method="post">
        <input type="submit" value="Выход">
    </form>
</body>
</html>
