<?php
// Подключение файла db.php для установки соединения с базой данных
require_once 'db.php';

try {
    // Получение доступных книг с использованием связанных таблиц
    $sql = "SELECT book.id_book, book.name AS book_name, author.name AS author_name, book.yearOfPublication, book.number, scientificDirection.name AS scientific_direction, editionType.name AS edition_type
            FROM book
            INNER JOIN author ON book.id_author = author.id_author
            INNER JOIN scientificDirection ON book.id_scientificDirection = scientificDirection.id_scientificDirection
            INNER JOIN editionType ON book.id_editionType = editionType.id_editionType
            WHERE book.number > 0";
    
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($books) > 0) {
        // Создание таблицы для отображения доступных книг
        echo "<table>
                <tr>
                    <th>Название</th>
                    <th>Автор</th>
                    <th>Год выпуска</th>
                    <th>Научное направление</th>
                    <th>Тип издания</th>
                    <th>Действие</th>
                </tr>";

        // Вывод каждой доступной книги в отдельной строке таблицы
        foreach ($books as $book) {
            echo "<tr>
                    <td>".$book['book_name']."</td>
                    <td>".$book['author_name']."</td>
                    <td>".$book['yearOfPublication']."</td>
                    <td>".$book['scientific_direction']."</td>
                    <td>".$book['edition_type']."</td>
                    <td>
                        <form action='php/reserve_book.php' method='post'>
                            <input type='hidden' name='book_id' value='".$book['id_book']."'>
                            <input type='submit' value='Забронировать'>
                        </form>
                    </td>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "Нет доступных книг.";
    }
} catch (PDOException $e) {
    // В случае ошибки при выполнении запроса
    echo "Ошибка выполнения запроса: " . $e->getMessage();
}
?>
