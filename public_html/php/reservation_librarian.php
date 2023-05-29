<?php
include 'db.php';

// Запрос для получения данных из базы данных
$query = "SELECT br.id_bookReservation, l.id_book, b.name AS book_name, b.number, r.studentNumber, r.surname, r.name
          FROM bookReservation AS br
          INNER JOIN link AS l ON br.id_link = l.id_link
          INNER JOIN reader AS r ON br.id_reader = r.id_reader
          INNER JOIN book AS b ON l.id_book = b.id_book";

$result = $db->query($query);

// Проверка наличия данных
if ($result && $result->rowCount() > 0) {
    // Вывод таблицы
    echo "<table>";
    echo "<tr><th>ID бронирования</th><th>ID книги</th><th>Название книги</th><th>Номер</th><th>Номер студ. билета</th><th>Фамилия</th><th>Имя</th><th>Действие</th></tr>";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['id_bookReservation'] . "</td>";
        echo "<td>" . $row['id_book'] . "</td>";
        echo "<td>" . $row['book_name'] . "</td>";
        echo "<td>" . $row['number'] . "</td>";
        echo "<td>" . $row['studentNumber'] . "</td>";
        echo "<td>" . $row['surname'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>";
        echo "<form action='php/give_book.php' method='post'>";
        echo "<input type='hidden' name='id_bookReservation' value='" . $row['id_bookReservation'] . "'>";
        echo "<input type='hidden' name='id_book' value='" . $row['id_book'] . "'>";
        echo "<button type='submit'>Выдать книгу</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Нет данных для отображения.";
}
?>
