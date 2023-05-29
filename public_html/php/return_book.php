<?php
session_start();

// Проверяем, авторизован ли читатель
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'reader') {
    // Если читатель не авторизован, перенаправляем на страницу входа
    header('Location: login.php');
    exit;
}

// Подключение к базе данных
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $bookName = $_POST['book_name'];

    // Получение информации о читателе
    $readerEmail = $_SESSION['login']; // Используем email читателя в качестве идентификатора
    $stmt = $db->prepare("SELECT * FROM reader WHERE email = :readerEmail");
    $stmt->bindParam(':readerEmail', $readerEmail);
    $stmt->execute();
    $reader = $stmt->fetch(PDO::FETCH_ASSOC);
    $readerId = $reader['id_reader']; // Получаем идентификатор читателя

    // Получаем информацию о книге
    $stmt = $db->prepare("SELECT * FROM book WHERE name = :bookName");
    $stmt->bindParam(':bookName', $bookName);
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($book) {
        // Увеличиваем значение поля 'number' в таблице 'book' на 1
        $newNumber = $book['number'] + 1;
        $stmt = $db->prepare("UPDATE book SET number = :newNumber WHERE id_book = :bookId");
        $stmt->bindParam(':newNumber', $newNumber);
        $stmt->bindParam(':bookId', $book['id_book']);
        $stmt->execute();

        // Удаляем запись из таблицы 'readersBook'
        $stmt = $db->prepare("DELETE FROM readersBook WHERE id_reader = :readerId AND id_link IN (SELECT id_link FROM link WHERE id_book = :bookId)");
        $stmt->bindParam(':readerId', $readerId);
        $stmt->bindParam(':bookId', $book['id_book']);
        $stmt->execute();

        // Выводим сообщение о успешной сдаче книги
        echo "Книга успешно сдана.";
    } else {
        // Выводим сообщение о невозможности сдать книгу
        echo "Ошибка: книга не найдена.";
    }
} else {
    // Если метод запроса не является POST, перенаправляем на страницу профиля читателя
    header('Location: profile_reader.php');
    exit;
}
?>
