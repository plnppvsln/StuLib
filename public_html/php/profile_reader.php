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

// Получение информации о читателе
$readerEmail = $_SESSION['login']; // Используем email читателя в качестве идентификатора
$stmt = $db->prepare("SELECT * FROM reader WHERE email = :readerEmail");
$stmt->bindParam(':readerEmail', $readerEmail);
$stmt->execute();
$reader = $stmt->fetch(PDO::FETCH_ASSOC);
$readerId = $reader['id_reader']; // Получаем идентификатор читателя

// Получение информации о взятых книгах читателя
$stmt = $db->prepare("SELECT b.name AS book_name, rb.takingDate, rb.handoverDate
                      FROM readersBook rb
                      JOIN link l ON rb.id_link = l.id_link
                      JOIN book b ON l.id_book = b.id_book
                      WHERE rb.id_reader = :readerId");
$stmt->bindParam(':readerId', $readerId);
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получение информации о забронированных местах читателя
$stmt = $db->prepare("SELECT seatNumber, bookingDate
                      FROM seatBooking
                      WHERE id_reader = :readerId");
$stmt->bindParam(':readerId', $readerId);
$stmt->execute();
$seats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Профиль Читателя</title>
</head>
<body>
    <h1>Профиль Читателя</h1>
    <h2>Информация о читателе</h2>
    <p>Фамилия: <?php echo $reader['surname']; ?></p>
    <p>Имя: <?php echo $reader['name']; ?></p>
    <p>Отчество: <?php echo $reader['fathersName']; ?></p>
    <p>Номер студенческого билета: <?php echo $reader['studentNumber']; ?></p>
    <form action="php/logout.php" method="POST">
        <button type="submit">Выход</button>
    </form>


    <h2>Взятые книги</h2>
    <table>
        <tr>
            <th>Название книги</th>
            <th>Дата взятия</th>
            <th>Дата сдачи</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($books as $book) { ?>
            <tr>
                <td><?php echo $book['book_name']; ?></td>
                <td><?php echo $book['takingDate']; ?></td>
                <td><?php echo $book['handoverDate']; ?></td>
                <td>
                    <form action="php/return_book.php" method="POST">
                        <input type="hidden" name="book_name" value="<?php echo $book['book_name']; ?>">
                        <button type="submit">Сдать</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <form action="php/reminder_card_book.php" method="POST">
        <button type="submit">Карточка-напоминание о сдаче книг</button>
    </form>

    <h2>Забронированные места</h2>
    <table>
        <tr>
            <th>Номер места</th>
            <th>Дата бронирования</th>
        </tr>
        <?php foreach ($seats as $seat) { ?>
            <tr>
                <td><?php echo $seat['seatNumber']; ?></td>
                <td><?php echo $seat['bookingDate']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <form action="php/reminder_card_seat.php" method="POST">
        <label for="booking_date">Дата бронирования:</label>
        <input type="date" name="booking_date" id="booking_date">
        <button type="submit">Карточка-напоминание о записи в читальный зал</button>
    </form>
     <h1>                </h1>
</body>
</html>
