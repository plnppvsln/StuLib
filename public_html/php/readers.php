<?php
require_once 'db.php';

// Вывод данных о читателях
$query = "SELECT * FROM reader";
$result = $db->query($query);

if (!$result) {
    die('Ошибка выполнения запроса: ' . $db->errorInfo()[2]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Список читателей</title>
   <head>
    <title>Список читателей</title>
    <style>
        form div {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
        }

        label {
            font-weight: bold;
        }

        input[type="submit"] {
            margin-top: 10px;
            margin-bottom: 10px;
            width: 100%;
            background-color: black;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
</head>
<body>
    <h2>Список читателей</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Номер студента</th>
            <th>Фамилия</th>
            <th>Имя</th>
            <th>Отчество</th>
            <th>Номер паспорта</th>
            <th>Телефон</th>
            <th>Email</th>
            <th>Пароль</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $row['id_reader']; ?></td>
            <td><?php echo $row['studentNumber']; ?></td>
            <td><?php echo $row['surname']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['fathersName']; ?></td>
            <td><?php echo $row['passNumber']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['password']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Добавить читателя</h2>
<form method="post" action="php/add_reader.php">
    <div>
        <label for="studentNumber">Номер студента:</label>
        <input type="text" name="studentNumber" id="studentNumber" required>
    </div>

    <div>
        <label for="surname">Фамилия:</label>
        <input type="text" name="surname" id="surname" required>
    </div>

    <div>
        <label for="name">Имя:</label>
        <input type="text" name="name" id="name" required>
    </div>

    <div>
        <label for="fathersName">Отчество:</label>
        <input type="text" name="fathersName" id="fathersName" required>
    </div>

    <div>
        <label for="passNumber">Номер паспорта:</label>
        <input type="text" name="passNumber" id="passNumber" required>
    </div>

    <div>
        <label for="phone">Телефон:</label>
        <input type="text" name="phone" id="phone" required>
    </div>

    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
    </div>

    <div>
        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required>
    </div>

    <div>
        <input type="submit" value="Добавить">
    </div>
</form>
</body>
</html>
