<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$current = $_SERVER['REQUEST_URI'];
?>

<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Studentský rozcestník TUL</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Caveat&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Nunito', sans-serif;
    }

    h1, h2, h3, h4 {
      font-family: 'Caveat', cursive;
      color: #BE185D;
    }

    button, .btn, input[type="submit"], a.btn {
      font-family: 'Nunito', sans-serif;
      font-weight: 600;
      color: #BE185D;
    }
  </style>
</head>
<body class="text-red-900 min-h-screen" style="
  background: url('/studentsky-rozcestnik/assets/bb1.png') repeat;
  background-size: cover;
  background-position: center;
  background-color: #ffeef8;
">

<header class="text-red-800 p-4 shadow" style="background-color: #FFF1F1;">
  <div class="max-w-7xl mx-auto flex justify-between items-center flex-wrap gap-4">

    <a href="/studentsky-rozcestnik/index.php" class="flex items-center space-x-4">
      <img src="/studentsky-rozcestnik/assets/logo.png" alt="Logo" class="h-10 w-10 rounded-full shadow" />
      <span class="text-4xl font-bold" style="font-family: 'Caveat', cursive; color: #BE185D;">
        Studentský rozcestník
      </span>
    </a>

    <nav class="flex flex-wrap items-center gap-2 text-sm md:text-base">
      <?php
        $pages = [
          'index.php' => 'Domů',
          'pages/predmety.php' => 'Předměty',
          'pages/zdroje.php' => 'Zdroje',
          'pages/komunita.php' => 'Komunita'
        ];
        foreach ($pages as $link => $name):
          $isActive = str_contains($current, $link);
      ?>
        <a href="/studentsky-rozcestnik/<?= $link ?>"
           class="px-3 py-1 rounded-full transition font-semibold <?= $isActive ? 'bg-[#FFF1F1] text-red-800 shadow-inner' : 'bg-white text-pink-800 hover:bg-[#FFF1F1]' ?>">
           <?= $name ?>
        </a>
      <?php endforeach; ?>

      <?php if (isset($_SESSION["user"])): ?>
        <span class="ml-2 text-sm text-pink-800">
          Ahoj, <?= htmlspecialchars($_SESSION["user"]["name"]) ?> 💖
          <?php if ($_SESSION["user"]["role"] === "admin"): ?>
            <a href="/studentsky-rozcestnik/admin/index.php"
               class="text-xs bg-red-200 text-red-800 px-2 py-1 rounded ml-1 align-middle hover:bg-red-300 transition">
               admin
            </a>
          <?php endif; ?>
        </span>
        <a href="/studentsky-rozcestnik/logout.php"
           class="bg-white text-[#BE185D] font-semibold px-3 py-1 rounded-full shadow-sm hover:bg-pink-200 transition ml-2">
          Odhlásit se
        </a>
      <?php else: ?>
        <a href="/studentsky-rozcestnik/login.php"
           class="px-3 py-1 rounded-full bg-white text-pink-800 hover:bg-[#FFF1F1] transition">
          Přihlásit se
        </a>
      <?php endif; ?>
    </nav>

  </div>
</header>

<main class="max-w-7xl mx-auto px-8 py-6">
