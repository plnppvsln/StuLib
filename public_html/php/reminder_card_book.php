<?php
require_once('tfpdf/tfpdf.php'); // Путь к файлу tFPDF

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
$stmt = $db->prepare("SELECT b.name AS book_name, rb.handoverDate
                      FROM readersBook rb
                      JOIN link l ON rb.id_link = l.id_link
                      JOIN book b ON l.id_book = b.id_book
                      WHERE rb.id_reader = :readerId
                      AND rb.handoverDate BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)");
$stmt->bindParam(':readerId', $readerId);
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($books) > 0) {
    // Создаем новый PDF-документ
    $pdf = new tFPDF();
    $pdf->AddPage();

    // Устанавливаем шрифт и размер текста
    $pdf->AddFont('DejaVuSerif', '', 'DejaVuSerif.ttf', true);
    $pdf->SetFont('DejaVuSerif', '', 12);
    $pdf->Cell(0, 10, 'Книги для сдачи в ближайшие 3 дня:', 0, 1, 'C');

    $pdf->SetFont('DejaVuSerif', '', 12);
    foreach ($books as $book) {
        $pdf->Cell(0, 10, $book['book_name'] . ' - ' . $book['handoverDate'], 0, 1);
    }

    // Генерируем имя файла и сохраняем PDF
    $filename = 'reminder_card_book.pdf';
    $pdf->Output('D', $filename); // Отправляем файл пользователю для скачивания

    // Очищаем выходной буфер и завершаем выполнение скрипта
    ob_end_clean();
    exit;
} else {
    // Если у читателя нет книг для сдачи в ближайшие 3 дня, выводим сообщение
    echo 'У вас нет таких книг';
}
?>
