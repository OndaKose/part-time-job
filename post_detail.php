<?php
session_start();
require 'db_connect.php';

if (!isset($_GET['id'])) {
    echo "投稿IDが指定されていません。";
    exit();
}

$post_id = $_GET['id'];

// 投稿情報の取得
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo "投稿が見つかりません。";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>投稿詳細</title>
</head>
<body>
    <h2>[<?= htmlspecialchars($post['genre']) ?>]</h2>
    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>

    <a href="mainpage.php">← 戻る</a>

    <!-- 今後ここにコメント一覧や投稿フォームも追加できます -->
</body>
</html>