<?php
require_once 'db.php';

$user_id = $_POST['user_id'] ?? '';
$genre = $_POST['genre'] ?? '';
$content = $_POST['content'] ?? '';
$time = date('Y-m-d H:i:s'); // 現在時刻を取得

$message = '';
$is_success = false;

if ($user_id && $genre && $content) {
    $sql = "INSERT INTO posts (user_id, time, genre, content)
            VALUES (:user_id, :time, :genre, :content)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':genre', $genre);
    $stmt->bindParam(':content', $content);

    if ($stmt->execute()) {
        $message = "投稿が完了しました！";
        $is_success = true;
    } else {
        $message = "投稿に失敗しました。";
    }
} else {
    $message = "必要なデータが不足しています。";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<title>投稿結果</title>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #ffffff;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        flex-direction: column;
    }
    .message-box {
        background: <?= $is_success ? '#2563eb' : '#ef4444' ?>;
        color: white;
        padding: 30px 40px;
        border-radius: 24px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.2);
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s ease, transform 0.8s ease;
        max-width: 90vw;
        text-align: center;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 20px;
    }
    .message-box.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .back-button {
        padding: 12px 24px;
        background-color: #2563eb;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }
    .back-button:hover {
        background-color: #1e40af;
    }
</style>
</head>
<body>
    <div class="message-box" id="messageBox"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>

    <?php if ($is_success): ?>
        <a href="mainpage.php" class="back-button">トップへ戻る</a>
    <?php endif; ?>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const box = document.getElementById('messageBox');
            box.classList.add('visible');
        });
    </script>
</body>
</html>