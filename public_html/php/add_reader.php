<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из формы
    $studentNumber = $_POST['studentNumber'];
    $surname = $_POST['surname'];
    $name = $_POST['name'];
    $fathersName = $_POST['fathersName'];
    $passNumber = $_POST['passNumber'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Вставка данных в базу данных
    $query = "INSERT INTO reader (studentNumber, surname, name, fathersName, passNumber, phone, email, password)
              VALUES (:studentNumber, :surname, :name, :fathersName, :passNumber, :phone, :email, :password)";

    $statement = $db->prepare($query);
    $statement->bindParam(':studentNumber', $studentNumber);
    $statement->bindParam(':surname', $surname);
    $statement->bindParam(':name', $name);
    $statement->bindParam(':fathersName', $fathersName);
    $statement->bindParam(':passNumber', $passNumber);
    $statement->bindParam(':phone', $phone);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':password', $password);

    if ($statement->execute()) {
        echo 'Читатель успешно добавлен.';
    } else {
        die('Ошибка выполнения запроса: ' . $statement->errorInfo()[2]);
    }
}
?>
