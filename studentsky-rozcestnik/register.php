<?php
require_once "db/db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

  $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $name, $email, $password);
  $stmt->execute();

  header("Location: login.php?registered=1");
  exit();
}
?>

<?php include('includes/header.php'); ?>
<form method="post" class="p-6 max-w-md mx-auto mt-10 bg-white shadow rounded">
  <h2 class="text-xl font-bold mb-4">Registrace</h2>
  <input type="text" name="name" placeholder="Jméno" class="w-full p-2 border mb-3 rounded" required>
 <input type="email" name="email" placeholder="Email" class="w-full p-2 border mb-3 rounded" required>
  <input type="password" name="password" placeholder="Heslo" class="w-full p-2 border mb-4 rounded" required>
 <button style="background-color: #FFD8DE; color: #831843;" class="w-full py-2 rounded hover:brightness-95 transition font-semibold">
  Registrovat se
</button>

</form>
<?php include('includes/footer.php'); ?>
