<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

// DB接続
$dsn = 'pgsql:host=localhost;port=5432;dbname=matsuri';
$user = 'matsuri';
$password = 'I6MstEzi';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('DB接続失敗: ' . $e->getMessage());
}

// ユーザー情報
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// 投稿数
$stmt = $pdo->prepare("SELECT COUNT(*) AS content FROM posts WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$posts_number = $stmt->fetch(PDO::FETCH_ASSOC);

// 投稿内容
$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = :user_id ORDER BY time DESC");
$stmt->execute(['user_id' => $user_id]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// お気に入り投稿
$stmt = $pdo->prepare("
    SELECT posts.* FROM posts
    INNER JOIN likes ON posts.id = likes.post_id
    WHERE likes.user_id = :user_id
    ORDER BY likes.created_at DESC
");
$stmt->execute(['user_id' => $user_id]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>プロフィール</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

  <!-- ✅ ヘッダー -->
  <header class="bg-blue-500 text-white px-6 py-4 flex justify-between items-center shadow">
    <h1 class="text-xl font-bold">プロフィール</h1>
    <nav class="space-x-4 text-sm md:text-base">
      <a href="mainpage.php" class="hover:underline">トップ</a>
      <a href="logout.php" class="hover:underline text-red-200">ログアウト</a>
    </nav>
  </header>

  <main class="flex flex-col lg:flex-row p-4 gap-6">
    <!-- 左側プロフィール -->
    <section class="bg-white rounded-lg shadow p-6 w-full lg:w-1/3">
      <h2 class="text-2xl font-semibold mb-4"><?= htmlspecialchars($user['user_name'] ?? 'ユーザー名') ?></h2>
      <p class="mb-2"><span class="font-semibold">投稿数:</span> <?= htmlspecialchars($posts_number['content'] ?? '0') ?></p>

      <h3 class="text-lg font-semibold mt-6 mb-2">❤️ お気に入り投稿</h3>
      <?php if (!empty($favorites)): ?>
        <div class="space-y-4">
          <?php foreach ($favorites as $fav): ?>
            <div class="border border-gray-300 p-3 rounded-lg bg-gray-50">
              <p class="text-sm text-gray-500"><?= htmlspecialchars($fav['time']) ?></p>
              <p class="text-blue-600 font-semibold">[<?= htmlspecialchars($fav['genre']) ?>]</p>
              <p><?= nl2br(htmlspecialchars($fav['content'])) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-gray-500">お気に入り投稿がありません</p>
      <?php endif; ?>
    </section>

    <!-- 右側：自分の投稿一覧 -->
    <section class="bg-white rounded-lg shadow p-6 w-full lg:w-2/3">
      <h3 class="text-2xl font-semibold mb-4">📌 投稿一覧</h3>
      <?php if (!empty($posts)): ?>
        <div class="space-y-4">
          <?php foreach ($posts as $post): ?>
            <div class="border border-gray-300 p-4 rounded-md bg-white">
              <p class="text-sm text-gray-500">投稿日: <?= htmlspecialchars($post['time']) ?></p>
              <p class="text-indigo-600 font-medium">職種: <?= htmlspecialchars($post['genre']) ?></p>
              <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-gray-500">まだ投稿がありません。</p>
      <?php endif; ?>
    </section>
  </main>

</body>
</html>