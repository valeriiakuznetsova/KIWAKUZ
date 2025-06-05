<?php
require_once __DIR__ . '/../db/db.php';
session_start();

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
  header("Location: /studentsky-rozcestnik/index.php");
  exit();
}

$id = $_GET["id"] ?? null;
if ($id && $id != $_SESSION["user"]["id"]) {
  $conn->query("DELETE FROM posts WHERE user_id = $id");
  $conn->query("DELETE FROM users WHERE id = $id");
}

header("Location: index.php");
exit();
