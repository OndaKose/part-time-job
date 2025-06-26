<?php
require_once 'db.php';
session_start();

// ログイン確認
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// セッションから取得（POSTのuser_idは改ざんされる可能性があるため、信頼しない）
$user_id = (int)$_SESSION['user_id'];

// 入力取得 & バリデーション
$genre = trim($_POST['genre'] ?? '');
$content = trim($_POST['content'] ?? '');

if ($genre === '' || $content === '') {
    exit('ジャンルまたは内容が未入力です。');
}

$time = date('Y-m-d H:i:s');

// SQL準備
$sql = "INSERT INTO posts (user_id, time, genre, content)
        VALUES (:user_id, :time, :genre, :content)";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':time', $time);
$stmt->bindParam(':genre', $genre);
$stmt->bindParam(':content', $content);

if ($stmt->execute()) {
    header("Location: profile.php"); // 成功後はリダイレクト
    exit();
} else {
    echo "投稿に失敗しました。";
}
?>