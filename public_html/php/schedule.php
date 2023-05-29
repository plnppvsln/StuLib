<?php
require('tfpdf/tfpdf.php');
include 'db.php';

// Проверяем, был ли выполнен POST-запрос с параметром 'selectedDate' и 'action' равным 'getScheduleData'
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selectedDate']) && isset($_POST['action']) && $_POST['action'] === 'getScheduleData') {
    $selectedDate = $_POST['selectedDate'];

    // Запрос для получения расписания на выбранную дату
    $query = "SELECT sb.seatNumber, sb.bookingDate, r.studentNumber, r.surname, r.name, r.fathersName
              FROM seatBooking AS sb
              INNER JOIN reader AS r ON sb.id_reader = r.id_reader
              WHERE sb.bookingDate = :selectedDate";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':selectedDate', $selectedDate);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Создаем PDF файл
    $pdf = new tFPDF();
    $pdf->AddPage();

    // Устанавливаем шрифт и размер шрифта
    $pdf->AddFont('DejaVuSerif', '', 'DejaVuSerif.ttf', true);
    $pdf->SetFont('DejaVuSerif', '', 12);

    // Выводим расписание в PDF
    $pdf->Cell(0, 10, 'Расписание на дату: ' . $selectedDate, 0, 1);
    if (count($data) > 0) {
        $pdf->SetFont('DejaVuSerif', '', 12);
        $pdf->Cell(30, 10, 'Номер места', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Студ. билета', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Фамилия', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Имя', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Отчество', 1, 1, 'C');

        $pdf->SetFont('DejaVuSerif', '', 12);
        foreach ($data as $row) {
            $pdf->Cell(30, 10, $row['seatNumber'], 1, 0, 'C');
            $pdf->Cell(40, 10, $row['studentNumber'], 1, 0, 'C');
            $pdf->Cell(40, 10, $row['surname'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['name'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['fathersName'], 1, 1, 'C');
        }
    } else {
        $pdf->Cell(0, 10, 'На выбранную дату нет расписания.', 0, 1);
    }

    // Генерируем имя PDF файла
    $filename = 'schedule_' . $selectedDate . '.pdf';

    // Сохраняем и отправляем PDF файл для скачивания
    $pdf->Output($filename, 'D');
}
?>