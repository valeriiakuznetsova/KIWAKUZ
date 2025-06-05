<?php
require_once "db/db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user && password_verify($password, $user["password"])) {
    $_SESSION["user"] = [
      "id" => $user["id"],
      "name" => $user["name"],
      "email" => $user["email"],
      "role" => $user["role"]
    ];
    
echo '<script>window.location.href = "index.php";</script>';
    exit();
  } else {
    $error = "Nesprávný email nebo heslo.";
  }
} 
?>

<?php include('includes/header.php'); ?>
<form method="post" class="p-6 max-w-md mx-auto mt-10 bg-white shadow rounded">
  <h2 class="text-xl font-bold mb-4 text-center">Přihlášení</h2>

  <?php if (isset($_GET["registered"])): ?>
    <div class="text-green-700 bg-green-100 border border-green-300 p-2 mb-4 rounded text-sm">
      ✅ Registrace proběhla úspěšně! Teď se přihlaš.
    </div>
  <?php endif; ?>

  <?php if (isset($error)): ?>
    <div class="text-red-600 mb-2"><?= $error ?></div>
  <?php endif; ?>

  <input type="email" name="email" placeholder="Email" class="w-full p-2 border mb-3 rounded" required>
  <input type="password" name="password" placeholder="Heslo" class="w-full p-2 border mb-4 rounded" required>
  <button style="background-color: #FFD8DE; color: #831843;" class="w-full py-2 rounded hover:brightness-95 transition font-semibold">
  Přihlásit se
</button>


  <p class="text-sm text-center mt-4 text-red-800">
    Nemáš účet? <a href="register.php" class="underline hover:text-pink-700">Zaregistruj se tady</a>.
  </p>
</form>
<?php include('includes/footer.php'); ?>
