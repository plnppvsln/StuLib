<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'reader') {
    header("Location: index.php"); // Перенаправляем на страницу входа, если пользователь не авторизован как библиотекарь
    exit();
}
?>

<!DOCTYPE html>
<html>
<meta charset="utf-8">
<head>
  <link rel="stylesheet" type="text/css" href="style/librarian-style.css">
</head>
<body>
  <div class="header">
    <div class="logo">
      <a href="?action=логотип"><img src="logo.png" alt="/"></a>
      <span>Студенческая<br>библиотека</span>
    </div>
    <a href="?action=издание"><button>Издание</button></a>
    <a href="?action=бронирование_книги"><button>Бронирование книги</button></a>
    <a href="?action=бронирование_места"><button>Бронирование места</button></a>
    <a href="?action=личный_кабинет"><button>Личный кабинет</button></a>
  </div>
  
  <div class="content">
    <?php
    // Проверка, какая кнопка была нажата
    if (isset($_GET['action'])) {
      if ($_GET['action'] == 'издание') {
        // Включите соответствующий код для кнопки "Издание"
        include 'php/query_books_reader.php';
      } elseif ($_GET['action'] == 'бронирование_книги') {
        // Включите соответствующий код для кнопки "Бронирование книги"
        include 'php/reservation_reader.php';
      } elseif ($_GET['action'] == 'бронирование_места') {
        // Включите соответствующий код для кнопки "Бронирование места"
        include 'php/seatBooking_reader.php';
      } elseif ($_GET['action'] == 'личный_кабинет') {
        // Включите соответствующий код для кнопки "Личный кабинет"
        include 'php/profile_reader.php';
      }elseif ($_GET['action'] == 'логотип') {
        // Включите соответствующий код для кнопки "Логотип"
        include 'php/logo_action.php';
      } else {
        // Код для других кнопок
      }
    } else {
      // Если не выбрана не однаих предложенных кнопок будет выполнено
    }
    ?>
  </div>
</body>
</html>