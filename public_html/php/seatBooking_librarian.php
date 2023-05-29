<?php
include 'db.php';

// Запрос для получения данных из таблицы seatBooking
$query = "SELECT sb.seatNumber, sb.bookingDate, r.studentNumber, r.surname, r.name, r.fathersName
          FROM seatBooking AS sb
          INNER JOIN reader AS r ON sb.id_reader = r.id_reader";

$result = $db->query($query);

// Проверка наличия данных
if ($result && $result->rowCount() > 0) {
    // Вывод таблицы
    echo "<table>";
    echo "<tr><th>Номер места</th><th>Дата брони</th><th>Номер студ. билета</th><th>Фамилия</th><th>Имя</th><th>Отчество</th></tr>";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['seatNumber'] . "</td>";
        echo "<td>" . $row['bookingDate'] . "</td>";
        echo "<td>" . $row['studentNumber'] . "</td>";
        echo "<td>" . $row['surname'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['fathersName'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
<meta charset="utf-8">
<form action="php/schedule.php" method="POST">
<div>
    <label for="date">Введи дату:</label>
    <input type="date" id="date" name="selectedDate">
    <button type="submit" name="action" value="getScheduleData">Расписание</button>
</div>
</form>
