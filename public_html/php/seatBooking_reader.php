<!-- seatBooking_reader.php -->
<h2>Бронирование места в читальном зале</h2>

<form action="php/process_seat_booking.php" method="POST">
  <label for="date">Дата:</label>
  <input type="date" id="date" name="date" required><br><br>
  
  <label for="socket">Наличие розетки:</label>
  <input type="checkbox" id="socket" name="seats[]" value="socket"><br><br>
  
  <label for="computer">Наличие компьютера:</label>
  <input type="checkbox" id="computer" name="seats[]" value="computer"><br><br>
  
  <button type="submit">Забронировать</button>
</form>
