<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_bookReservation = $_POST['id_bookReservation'];
    $id_book = $_POST['id_book'];

    // Получение соответствующих id_reader и id_link
    $query = "SELECT id_link, id_reader FROM bookReservation WHERE id_bookReservation = :id_bookReservation";
    $statement = $db->prepare($query);
    $statement->bindParam(':id_bookReservation', $id_bookReservation);
    $statement->execute();
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    $id_link = $row['id_link'];
    $id_reader = $row['id_reader'];

    // Уменьшение значения "number" в таблице "book"
    $query = "UPDATE book SET number = number - 1 WHERE id_book = :id_book";
    $statement = $db->prepare($query);
    $statement->bindParam(':id_book', $id_book);
    $statement->execute();

    // Добавление записи в таблицу "readersBook"
    $currentDate = date("Y-m-d");
    $handoverDate = date("Y-m-d", strtotime("+4 months"));
    $query = "INSERT INTO readersBook (id_reader, id_link, takingDate, handoverDate) VALUES (:id_reader, :id_link, :takingDate, :handoverDate)";
    $statement = $db->prepare($query);
    $statement->bindParam(':id_reader', $id_reader);
    $statement->bindParam(':id_link', $id_link);
    $statement->bindParam(':takingDate', $currentDate);
    $statement->bindParam(':handoverDate', $handoverDate);
    $statement->execute();

    // Удаление записи из таблицы "bookReservation"
    $query = "DELETE FROM bookReservation WHERE id_bookReservation = :id_bookReservation";
    $statement = $db->prepare($query);
    $statement->bindParam(':id_bookReservation', $id_bookReservation);
    $statement->execute();

    echo "Книга выдана успешно.";
} else {
    echo "Ошибка: Некорректный метод запроса.";
}
?>
