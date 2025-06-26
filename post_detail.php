<?php
session_start();
require 'db_connect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 投稿ID取得
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'] ?? null;

if (!$post_id) {
    echo "投稿IDが無効です。";
    exit();
}

// 投稿取得
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$post) {
    echo "投稿が見つかりません。";
    exit();
}

// コメント取得
$stmt = $pdo->prepare("SELECT content FROM comments WHERE post_id = ? ORDER BY created_at ASC");
$stmt->execute([$post_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// いいね数
$stmt = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ?");
$stmt->execute([$post_id]);
$like_count = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>投稿詳細</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

  <!-- ✅ ヘッダー -->
  <header class="bg-blue-500 text-white py-4 px-6 flex justify-between items-center shadow">
    <h1 class="text-xl font-semibold">投稿詳細</h1>
    <nav class="space-x-4">
      <a href="mainpage.php" class="hover:underline">トップ</a>
      <a href="profile.php" class="hover:underline">プロフィール</a>
      <a href="logout.php" class="hover:underline text-red-200">ログアウト</a>
    </nav>
  </header>

  <main class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-blue-600 mb-4">[<?= htmlspecialchars($post['genre']) ?>]</h2>
    <p class="text-gray-800 whitespace-pre-wrap mb-6"><?= nl2br(htmlspecialchars($post['content'])) ?></p>

    <!-- いいね -->
    <form action="like.php" method="post" class="mb-6">
      <input type="hidden" name="post_id" value="<?= $post_id ?>">
      <button type="submit" class="text-red-500 text-xl hover:scale-125 transition-transform">
        ❤️ <?= $like_count ?>
      </button>
    </form>

    <!-- コメント一覧 -->
    <section class="mb-6">
      <h3 class="text-lg font-semibold mb-3">💬 コメント</h3>
      <?php if (count($comments) > 0): ?>
        <div class="space-y-3">
          <?php foreach ($comments as $c): ?>
            <div class="bg-gray-100 p-3 rounded shadow-inner">
              <?= htmlspecialchars($c['content']) ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-gray-500">まだコメントがありません。</p>
      <?php endif; ?>
    </section>

    <!-- コメント投稿 -->
    <form action="comment.php" method="post" class="flex gap-2">
      <input type="hidden" name="post_id" value="<?= $post_id ?>">
      <input type="text" name="content" required placeholder="コメントを入力..." class="flex-1 border rounded px-3 py-2">
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">送信</button>
    </form>

    <!-- 戻るリンク -->
    <div class="mt-6 text-center">
      <a href="mainpage.php" class="text-sm text-blue-500 hover:underline">← 投稿一覧に戻る</a>
    </div>
  </main>
</body>
</html>