<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// DB接続情報
$host = "localhost";
$dbname = "matsuri";
$user = "matsuri";
$password = "I6MstEzi";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 投稿を新しい順に取得
    $stmt = $pdo->query("SELECT * FROM posts ORDER BY id DESC");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "DB接続エラー: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メインページ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #0077cc;
            color: white;
            padding: 15px 30px;
            text-align: center;
            position: relative;
        }

        .logout-button {
            position: absolute;
            right: 20px;
            top: 15px;
            background: #fff;
            color: #0077cc;
            border: 1px solid #fff;
            padding: 5px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .logout-button:hover {
            background-color: #e0e0e0;
        }

        .nav {
            text-align: center;
            margin: 15px 0;
        }

        .nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #0077cc;
            font-weight: bold;
        }

        .post-container {
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
        }

        .post {
            background: white;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .genre {
            font-size: 0.9em;
            color: #888;
            margin-bottom: 5px;
        }

        .content {
            font-size: 1.1em;
            color: #333;
        }

        .post-link {
            text-decoration: none;
            display: block;
            color: inherit;
        }

        .post-link .post:hover {
            background-color: #eef;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>アルバイト投稿一覧</h1>
    <a href="login.php" class="logout-button">ログアウト</a>
</div>

<div class="nav">
    <a href="profile.php">プロフィール</a>
    <a href="post.php">投稿する</a>
</div>

<div class="post-container">
  <?php foreach ($posts as $post): ?>
    <a href="post_detail.php?id=<?= $post['id'] ?>" class="post-link">
      <div class="post">
        <div class="genre">[<?= htmlspecialchars($post['genre']) ?>]</div>
        <div class="content"><?= nl2br(htmlspecialchars($post['content'])) ?></div>
      </div>
    </a>
  <?php endforeach; ?>
</div>

</body>
</html>