<?php
// Подключение файла db.php для установки соединения с базой данных
require_once 'db.php';

session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'reader') {
    // Если пользователь не авторизован, перенаправляем его на страницу входа или показываем сообщение об ошибке
    header("Location: index.php"); // Замените "login.php" на вашу страницу входа
    exit();
}

// Получение идентификатора книги из POST-запроса
$bookId = isset($_POST['book_id']) ? $_POST['book_id'] : null;

if ($bookId) {
    try {
        // Получение идентификатора читателя по email из сессии
        $email = $_SESSION['login'];

        $stmtReader = $db->prepare("SELECT id_reader FROM reader WHERE email = :email");
        $stmtReader->bindParam(':email', $email);
        $stmtReader->execute();
        $rowReader = $stmtReader->fetch(PDO::FETCH_ASSOC);
        $readerId = $rowReader['id_reader'];

        // Получение идентификатора ссылки (id_link) из таблицы link по указанному id_book
        $stmtLink = $db->prepare("SELECT id_link FROM link WHERE id_book = :bookId");
        $stmtLink->bindParam(':bookId', $bookId);
        $stmtLink->execute();
        $rowLink = $stmtLink->fetch(PDO::FETCH_ASSOC);
        $linkId = $rowLink['id_link'];

        // Вставка новой записи в таблицу bookReservation
        $sql = "INSERT INTO bookReservation (id_link, id_reader) VALUES (:linkId, :readerId)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':linkId', $linkId);
        $stmt->bindParam(':readerId', $readerId);
        $stmt->execute();

        // Вывод сообщения о успешном бронировании
        echo "Книга успешно забронирована!";

    } catch (PDOException $e) {
        // В случае ошибки при выполнении запроса
        echo "Ошибка выполнения запроса: " . $e->getMessage();
    }
} else {
    // Если не указан идентификатор книги
    echo "Не удалось забронировать книгу.";
}
?>
