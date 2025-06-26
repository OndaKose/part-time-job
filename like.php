<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = (int)$_POST['post_id'];

// 既にいいねしているか確認
$stmt = $pdo->prepare("SELECT 1 FROM likes WHERE user_id = ? AND post_id = ?");
$stmt->execute([$user_id, $post_id]);
$liked = $stmt->fetchColumn();

if ($liked) {
    // すでにいいねしていた → いいね解除
    $stmt = $pdo->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
    $stmt->execute([$user_id, $post_id]);
} else {
    // いいねしていない → いいね登録
    $stmt = $pdo->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $post_id]);
}

// 元のページへリダイレクト
header("Location: post_detail.php?id=$post_id");
exit();
?>