<?php
require_once 'db.php';

if (isset($_GET['action']) && $_GET['action'] == 'издание') {
    // Выполнение запроса к базе данных
    $stmt = $db->query('SELECT b.id_book, b.name AS book_name, a.name AS author_name, b.yearOfPublication, b.number, sd.name AS scientific_direction_name, et.name AS edition_type_name
                        FROM book AS b
                        JOIN author AS a ON b.id_author = a.id_author
                        JOIN scientificDirection AS sd ON b.id_scientificDirection = sd.id_scientificDirection
                        JOIN editionType AS et ON b.id_editionType = et.id_editionType');
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Вывод результатов в виде таблицы
    echo '<table>';
    echo '<tr><th>ID</th><th>Название</th><th>Имя автора</th><th>Год публикации</th><th>Количество</th><th>Научное направление</th><th>Тип издания</th></tr>';

    foreach ($results as $row) {
        echo '<tr>';
        echo '<td>' . $row['id_book'] . '</td>';
        echo '<td>' . $row['book_name'] . '</td>';
        echo '<td>' . $row['author_name'] . '</td>';
        echo '<td>' . $row['yearOfPublication'] . '</td>';
        echo '<td>' . $row['number'] . '</td>';
        echo '<td>' . $row['scientific_direction_name'] . '</td>';
        echo '<td>' . $row['edition_type_name'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';
}
?>
