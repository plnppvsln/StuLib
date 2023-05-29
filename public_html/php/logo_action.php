<?php
// Выыод всего 
echo '<!DOCTYPE html>
<html>
<meta charset="utf-8">
<head>
  <link rel="stylesheet" type="text/css" href="style/librarian-style.css">
  <!-- Подключение Яндекс.Карт -->
  <script src="https://api-maps.yandex.ru/2.1/?apikey=898a19f3-3f54-4637-8090-ed0194d9ec3b&lang=ru_RU" type="text/javascript"></script>
</head>
<body>
  <div class="content">
    <div class="info">
      <h1>Студенческая библиотека</h1>
      <h2>Контактная информация</h2>
      <p>Часы работы:</p>
      <ul>
        <li>Будни: 8:30 - 18:00</li>
        <li>Сб, Вс: выходные</li>
      </ul>
      <p>Телефон: +7 (490) 763-98-34</p>
    </div>
    <div id="map" style="width: 600px; height: 400px;"></div>
  </div>

  <script type="text/javascript">
    ymaps.ready(init);

    function init() {
      var myMap = new ymaps.Map("map", {
        center: [55.843026, 37.506529], // Координаты
        zoom: 10 // Масштаб
      });

      var myPlacemark = new ymaps.Placemark([55.843026, 37.506529], {
        hintContent: "Студенческая библиотека",
        balloonContent: "Адрес вашей библиотеки"
      });

      myMap.geoObjects.add(myPlacemark);
    }
  </script>
</body>
</html>';
?>
