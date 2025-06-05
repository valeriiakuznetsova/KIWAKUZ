<?php
require_once "db/db.php";
session_start();

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
  header("Location: /studentsky-rozcestnik/index.php");
  exit();
}

$post_id = $_GET["id"] ?? null;

if (!$post_id) {
  header("Location: /studentsky-rozcestnik/pages/komunita.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
       $topic = trim($_POST["topic"]);
  $content = trim($_POST["content"]);

  if (!empty($topic) && !empty($content)) {
    $stmt = $conn->prepare("UPDATE posts SET topic = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $topic, $content, $post_id);
 $stmt->execute();

  header("Location: /studentsky-rozcestnik/pages/komunita.php");
    exit();
  }
}
 $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
  echo "Příspěvek nebyl nalezen.";
  exit();
}
?>

<?php include("includes/header.php"); ?>

<section class="bg-white p-6 rounded-lg shadow max-w-2xl mx-auto mt-6">
<h2 class="text-2xl font-bold text-pink-800 mb-4 text-center">✏️ Úprava příspěvku</h2>

  <form method="post" class="space-y-4">
  <input type="text" name="topic" value="<?= htmlspecialchars($post['topic']) ?>" class="w-full p-2 border border-pink-300 rounded" required>
   <textarea name="content" rows="5" class="w-full p-3 border border-pink-300 rounded resize-none" required><?= htmlspecialchars($post['content']) ?></textarea>
  <div class="flex justify-between">
      <a href="/studentsky-rozcestnik/pages/komunita.php" class="text-pink-600 hover:underline">← Zpět</a>
      <button class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Uložit změny</button>
    </div>
  </form>
</section>

<?php include("includes/footer.php"); ?> 
