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

// Получение информации о забронированных местах читателя
$bookingDate = $_POST['booking_date'];

$stmt = $db->prepare("SELECT sb.seatNumber, sb.bookingDate, s.socket, s.computer
                      FROM seatBooking sb
                      JOIN seat s ON sb.seatNumber = s.seatNumber
                      WHERE sb.id_reader = :readerId
                      AND sb.bookingDate = :bookingDate");
$stmt->bindParam(':readerId', $readerId);
$stmt->bindParam(':bookingDate', $bookingDate);
$stmt->execute();
$seats = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($seats) > 0) {
    // Создаем новый PDF-документ
    $pdf = new tFPDF();
    $pdf->AddPage();

    // Устанавливаем шрифт и размер текста
    $pdf->AddFont('DejaVuSerif', '', 'DejaVuSerif.ttf', true);
    $pdf->SetFont('DejaVuSerif', '', 12);
    $pdf->Cell(0, 10, 'Информация о брони места в читальном зале', 0, 1, 'C');

    $pdf->SetFont('DejaVuSerif', '', 12);
    foreach ($seats as $seat) {
        $pdf->Cell(0, 10, 'Место: ' . $seat['seatNumber'], 0, 1);
        $pdf->Cell(0, 10, 'Дата бронирования: ' . $seat['bookingDate'], 0, 1);
        $pdf->Cell(0, 10, 'Разетка: ' . ($seat['socket'] ? 'Есть' : 'Нет'), 0, 1);
        $pdf->Cell(0, 10, 'Компьютер: ' . ($seat['computer'] ? 'Есть' : 'Нет'), 0, 1);
        $pdf->Ln(10); // Добавляем пустую строку
    }

    // Генерируем имя файла и сохраняем PDF
    $filename = 'reminder_card_seat.pdf';
    $pdf->Output('D', $filename); // Отправляем файл пользователю для скачивания

    // Очищаем выходной буфер и завершаем выполнение скрипта
    ob_clean();
    exit;
} else {
    // Если место не было забронировано на выбранную дату
    echo 'Вы не бронировали место на указанную дату.';
}
?>
