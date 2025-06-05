<?php
require_once __DIR__ . '/db/db.php';
session_start();

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
  header("Location: /studentsky-rozcestnik/index.php");
  exit();
}

$id = $_GET["id"] ?? null;
if ($id) {
  $conn->query("DELETE FROM comments WHERE post_id = $id");
 $conn->query("DELETE FROM likes WHERE post_id = $id");
 $conn->query("DELETE FROM posts WHERE id = $id");
}

header("Location: " . $_SERVER["HTTP_REFERER"]);
exit();
