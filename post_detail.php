<?php
session_start();
require 'db_connect.php'; // ← PDO接続（$pdo）が定義されている前提

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

// いいね取得
$stmt = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ?");
$stmt->execute([$post_id]);
$like_count = $stmt->fetchColumn();

// ログイン中ユーザーがいいね済みか
$liked = false;
if ($user_id) {
    $stmt = $pdo->prepare("SELECT 1 FROM likes WHERE post_id = ? AND user_id = ?");
    $stmt->execute([$post_id, $user_id]);
    $liked = $stmt->fetchColumn();
}
?>

<!-- 以下 HTML はあなたが貼ってくれたまま + コメント表示部分あり -->
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>投稿詳細</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f7fa;
      margin: 0;
      padding: 30px;
      color: #333;
    }

    .post-card {
      background: white;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
      max-width: 720px;
      margin: 0 auto 40px;
      transition: box-shadow 0.3s ease;
    }

    .post-card:hover {
      box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }

    h2 {
      color: #007acc;
      margin-bottom: 16px;
    }

    .post-content {
      font-size: 1.1rem;
      line-height: 1.6;
      margin-bottom: 24px;
    }

    .like-button {
      font-size: 1.3rem;
      background: none;
      border: none;
      cursor: pointer;
      color: #e74c3c;
      transition: transform 0.2s ease;
    }

    .like-button:disabled {
      opacity: 0.5;
      cursor: default;
    }

    .like-button:hover:not(:disabled) {
      transform: scale(1.3);
    }

    .comment-section {
      margin-top: 40px;
    }

    .comment-section h3 {
      font-size: 1.2rem;
      margin-bottom: 15px;
    }

    .comment {
      background: #f0f4f9;
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 12px;
      box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }

    .comment-form {
      margin-top: 20px;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .comment-form input[type="text"] {
      flex: 1;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    .comment-form button {
      padding: 10px 16px;
      background-color: #0077cc;
      border: none;
      color: white;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .comment-form button:hover {
      background-color: #005fa3;
    }

    .back-link {
      display: block;
      margin-top: 30px;
      text-align: center;
      color: #555;
      text-decoration: none;
      font-size: 14px;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="post-card">
    <h2>[<?= htmlspecialchars($post['genre']) ?>]</h2>
    <p class="post-content"><?= nl2br(htmlspecialchars($post['content'])) ?></p>

    <form action="like.php" method="post">
        <input type="hidden" name="post_id" value="<?= $post_id ?>">
        <button class="like-button" type="submit">
            ❤️ <?= $like_count ?>
        </button>
    </form>

    <!-- コメント表示 -->
    <div class="comment-section">
      <h3>コメント</h3>
      <?php if (count($comments) > 0): ?>
        <?php foreach ($comments as $c): ?>
          <div class="comment"><?= htmlspecialchars($c['content']) ?></div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="color: #999;">まだコメントがありません。</p>
      <?php endif; ?>

      <!-- コメント投稿 -->
      <form class="comment-form" action="comment.php" method="post">
        <input type="hidden" name="post_id" value="<?= $post_id ?>">
        <input type="text" name="content" placeholder="コメントを入力..." required>
        <button type="submit">送信</button>
      </form>
    </div>

    <a class="back-link" href="mainpage.php">← 投稿一覧に戻る</a>
  </div>

</body>
</html>