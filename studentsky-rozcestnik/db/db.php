<?php
$conn = new mysqli("localhost", "root", "", "studentsky_portal");
if ($conn->connect_error) {
  die("Chyba připojení: " . $conn->connect_error);
}
?>
