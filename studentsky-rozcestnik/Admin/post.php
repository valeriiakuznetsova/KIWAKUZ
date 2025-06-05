<?php
require_once __DIR__ . '/../db/db.php';
session_start();

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
  header("Location: /studentsky-rozcestnik/index.php");
  exit();
}

$user_id = $_GET["user_id"] ?? null;
if (!$user_id) {
  header("Location: index.php");
  exit();
}

$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
$posts = $conn->query("SELECT * FROM posts WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <title>Příspěvky uživatele</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-pink-100 text-red-800 min-h-screen p-8">
  <h1 class="text-2xl font-bold mb-4">📝 Příspěvky uživatele: <?= htmlspecialchars($user["name"]) ?></h1>

  <?php if ($posts->num_rows === 0): ?>
    <p class="text-sm">Žádné příspěvky zatím nebyly přidány.</p>
  <?php endif; ?>

  <div class="space-y-4">
    <?php while ($post = $posts->fetch_assoc()): ?>
      <div class="bg-white p-4 shadow rounded border border-pink-200">
        <p class="text-sm italic text-pink-700">Téma: <?= htmlspecialchars($post["topic"]) ?></p>
        <p class="text-red-900 mb-2"><?= nl2br(htmlspecialchars($post["content"])) ?></p>
        <div class="text-sm flex justify-between items-center">
          <span class="text-pink-600"><?= date("d.m.Y H:i", strtotime($post["created_at"])) ?></span>
          <a href="delete_post.php?id=<?= $post["id"] ?>" onclick="return confirm('Smazat tento příspěvek?');"
             class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">Smazat</a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <div class="mt-6">
    <a href="index.php" class="text-pink-600 underline hover:text-pink-800">← Zpět na seznam uživatelů</a>
  </div>
</body>
</html>
