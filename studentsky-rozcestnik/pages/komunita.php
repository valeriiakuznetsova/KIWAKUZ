<?php
require_once "../db/db.php";
session_start();

// Zpracování LIKE
if (isset($_GET["like"]) && isset($_SESSION["user"])) {
  $user_id = $_SESSION["user"]["id"];
  $post_id = (int) $_GET["like"];

  $check = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id = ?");
  $check->bind_param("ii", $user_id, $post_id);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows === 0) {
    $stmt = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $post_id);
    $stmt->execute();
  }

  header("Location: komunita.php");
  exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['topic'], $_POST['content'], $_SESSION["user"])) {
  $user_id = $_SESSION["user"]["id"];
  $topic = trim($_POST["topic"]);
  $content = trim($_POST["content"]);

  if (!empty($topic) && !empty($content)) {
    $stmt = $conn->prepare("INSERT INTO posts (user_id, topic, content) VALUES (?, ?, ?)");
   $stmt->bind_param("iss", $user_id, $topic, $content);
    $stmt->execute();
  }
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['comment'], $_POST['post_id'], $_SESSION['user'])) {
  $comment = trim($_POST["comment"]);
  $postId = (int) $_POST["post_id"];
  $userId = $_SESSION["user"]["id"];

  if (!empty($comment)) {
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $postId, $userId, $comment);
    $stmt->execute();
  }

  header("Location: komunita.php");
  exit();
}

$posts = $conn->query("SELECT posts.*, users.name FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");
?>

<?php include("../includes/header.php"); ?>
<link href="https://fonts.googleapis.com/css2?family=Caveat&family=Nunito&display=swap" rel="stylesheet">
<style>
  body {
    font-family: 'Nunito', sans-serif;
  }
  h1, h2, h3, h4, h5, h6 {
    font-family: 'Caveat', cursive;
    color: #BE185D;
  }
</style>

<main class="max-w-7xl mx-auto px-8 py-6">

<section class="bg-white p-6 rounded-lg shadow">
  <h2 class="text-3xl font-bold mb-6 text-center">💬 Komunita studentů 💬</h2>

  <?php if (isset($_SESSION["user"])): ?>
    <form method="post" class="mb-6 space-y-3">
      <input type="text" name="topic" placeholder="Téma příspěvku (např. Jídlo, Tipy, Zkoušky...)" class="w-full p-2 border border-pink-300 rounded" required>
      <textarea name="content" rows="3" placeholder="Napiš svou zkušenost, tip nebo recept..." class="w-full p-3 border border-pink-300 rounded resize-none" required></textarea>
      <button style="background-color: #FFD8DE;" class="text-pink-800 px-4 py-2 rounded hover:brightness-95 transition">Přidat příspěvek</button>
    </form>
  <?php else: ?>
    <p class="text-sm text-pink-800 mb-6">Pro přidání příspěvku se musíš <a href="/studentsky-rozcestnik/login.php" class="underline text-pink-700">přihlásit</a>.</p>
  <?php endif; ?>

  <div class="space-y-4">
    <?php while ($row = $posts->fetch_assoc()): ?>
      <?php
        $postId = $row["id"];
        $likes = $conn->query("SELECT COUNT(*) AS total FROM likes WHERE post_id = $postId")->fetch_assoc()["total"];

        $stmt = $conn->prepare("SELECT comments.*, users.name FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = ? ORDER BY comments.created_at ASC");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $commentsResult = $stmt->get_result();
      ?>
      <div style="background-color: #FFD8DE;" class="p-4 rounded shadow relative">
        <p class="text-sm text-pink-800 mb-1"><strong><?= htmlspecialchars($row["name"]) ?></strong> napsal/a:</p>
        <p class="text-sm italic text-pink-700 mb-1"> Téma: <?= htmlspecialchars($row["topic"]) ?></p>
        <p class="text-red-900 whitespace-pre-wrap"><?= nl2br(htmlspecialchars($row["content"])) ?></p>

        <div class="mt-2 flex justify-between items-center text-sm text-pink-700">
          <span><?= date("d.m.Y H:i", strtotime($row["created_at"])) ?></span>
          <div class="flex items-center gap-3">
            <?php if (isset($_SESSION["user"])): ?>
              <a href="?like=<?= $postId ?>" class="hover:text-red-500 transition">❤️ <?= $likes ?></a>
        <?php if ($_SESSION["user"]["role"] === "admin"): ?>
                <a href="/studentsky-rozcestnik/editpost.php?id=<?= $postId ?>" class="text-blue-600 hover:underline">✏️</a>
      <a href="/studentsky-rozcestnik/delete_post.php?id=<?= $postId ?>" class="text-red-600 hover:underline" onclick="return confirm('Opravdu chceš smazat tento příspěvek?')">🗑️</a>
              <?php endif; ?>
     <?php else: ?>
              <span>❤️ <?= $likes ?></span>
            <?php endif; ?>
          </div>
        </div>

  
        <div class="mt-4 pl-4 border-l-2 border-pink-300 space-y-2">
          <?php while ($c = $commentsResult->fetch_assoc()): ?>
            <p class="text-sm text-pink-800">
        <strong><?= htmlspecialchars($c["name"]) ?>:</strong>
              <?= htmlspecialchars($c["content"]) ?>
            </p>
          <?php endwhile; ?>

          <?php if (isset($_SESSION["user"])): ?>
            <form method="post" action="komunita.php" class="space-y-1">
              <input type="hidden" name="post_id" value="<?= $postId ?>">
              <textarea name="comment" rows="1" placeholder="Napiš komentář..." class="w-full p-2 border border-pink-300 rounded text-sm" required></textarea>
              <button class="bg-[#FFD8DE] text-pink-800 px-4 py-1 rounded text-sm hover:brightness-95">Přidat komentář</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</section>

</main>

<?php include("../includes/footer.php"); ?>
