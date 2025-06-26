<?php
session_start();
require 'db_connect.php';

$post_id = (int)($_POST['post_id'] ?? 0);
$user_id = $_SESSION['user_id'] ?? 0;
$content = trim($_POST['content'] ?? '');

if ($post_id && $user_id && $content !== '') {
    $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
    $stmt->execute([$post_id, $user_id, $content]);
}

header("Location: post_detail.php?id=" . $post_id);
exit();