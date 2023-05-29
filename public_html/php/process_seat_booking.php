<?php
// Проверка, что пользователь вошел в систему
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'reader') {
    // Подключение к базе данных
    require_once 'db.php';

    // Получение выбранных данных из формы
    $date = $_POST['date'];
    $socket = isset($_POST['seats']) && in_array('socket', $_POST['seats']);
    $computer = isset($_POST['seats']) && in_array('computer', $_POST['seats']);

    // Проверка, существует ли свободное место с заданными параметрами на выбранную дату
    $stmt = $db->prepare("SELECT * FROM seat WHERE socket = :socket AND computer = :computer AND seatNumber NOT IN (SELECT seatNumber FROM seatBooking WHERE bookingDate = :date)");
    $stmt->bindParam(':socket', $socket, PDO::PARAM_BOOL);
    $stmt->bindParam(':computer', $computer, PDO::PARAM_BOOL);
    $stmt->bindParam(':date', $date);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Если свободное место с заданными параметрами существует, добавляем запись в таблицу seatBooking
        $seatNumber = $stmt->fetch()['seatNumber'];
        $email = $_SESSION['login'];

        // Получение id_reader по email
        $readerStmt = $db->prepare("SELECT id_reader FROM reader WHERE email = :email");
        $readerStmt->bindParam(':email', $email);
        $readerStmt->execute();
        $readerId = $readerStmt->fetch()['id_reader'];

        $bookingStmt = $db->prepare("INSERT INTO seatBooking (seatNumber, bookingDate, id_reader) VALUES (:seatNumber, :bookingDate, :readerId)");
        $bookingStmt->bindParam(':seatNumber', $seatNumber);
        $bookingStmt->bindParam(':bookingDate', $date);
        $bookingStmt->bindParam(':readerId', $readerId);
        $bookingStmt->execute();

        echo "Место успешно забронировано.";
    } else {
        echo "На выбранную дату места с такими параметрами нет. Измените параметры или выберите другую дату.";
    }
} else {
    echo "Пожалуйста, войдите в систему как читатель.";
}
?>
