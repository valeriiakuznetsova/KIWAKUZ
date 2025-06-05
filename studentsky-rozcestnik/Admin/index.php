<?php
require_once __DIR__ . '/../db/db.php';
session_start();

// Проверка доступа админки !!
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
  header("Location: /studentsky-rozcestnik/index.php");
  exit();
}

$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <title>Správa uživatelů</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-pink-100 text-red-800 min-h-screen p-8">
  <h1 class="text-3xl font-bold mb-6 text-center">📋 Správa uživatelů</h1>

  <div class="overflow-x-auto">
    <table class="w-full bg-white shadow rounded-lg">
      <thead class="bg-pink-200 text-left text-sm font-semibold text-pink-900">
        <tr>
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Jméno</th>
          <th class="px-4 py-2">E-mail</th>
          <th class="px-4 py-2">Role</th>
          <th class="px-4 py-2">Vytvořeno</th>
          <th class="px-4 py-2">Akce</th>
        </tr>
      </thead>
      <tbody class="text-sm">
        <?php while ($user = $users->fetch_assoc()): ?>
          <tr class="border-b hover:bg-pink-50 transition">
            <td class="px-4 py-2"><?= $user['id'] ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($user['name']) ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($user['email']) ?></td>
            <td class="px-4 py-2">
              <?= $user['role'] === 'admin' ? '<span class="text-red-600 font-semibold">admin</span>' : 'user' ?>
            </td>
            <td class="px-4 py-2">
              <?= isset($user['created_at']) ? date("d.m.Y H:i", strtotime($user['created_at'])) : '<span class="text-gray-400 italic">není dostupné</span>' ?>
            </td>
            <td class="px-4 py-2 space-x-2">
              <?php if ($user['id'] != $_SESSION["user"]["id"]): ?>
                <a href="delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Opravdu chceš smazat tohoto uživatele?');" class="bg-red-500 text-white px-2 py-1 rounded text-sm hover:bg-red-600">Smazat</a>
              <?php else: ?>
                <span class="text-xs text-gray-400">Nelze smazat sebe</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div class="text-center mt-6">
    <a href="/studentsky-rozcestnik/index.php" class="text-pink-600 underline hover:text-pink-800">← Zpět na hlavní stránku</a>
  </div>
</body>
</html>
