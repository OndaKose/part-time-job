<?php
require_once 'db.php';

$user_id = $_POST['user_id'];
$genre = $_POST['genre'];
$content = $_POST['content'];
$time = date('Y-m-d H:i:s'); // 現在時刻を取得

$sql = "INSERT INTO posts (user_id, time, genre, content)
        VALUES (:user_id, :time, :genre, :content)";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':time', $time);
$stmt->bindParam(':genre', $genre);
$stmt->bindParam(':content', $content);

if ($stmt->execute()) {
    echo "投稿が完了しました！";
} else {
    echo "投稿に失敗しました。";
}
?>
