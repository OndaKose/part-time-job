<?php
session_start();
$host = "localhost";
$dbname = "matsuri";
$user = "matsuri";
$password = "I6MstEzi";
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = trim($_POST['user_id']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if ($password !== $confirm_password) {
        $error = "パスワードが一致しません。";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (user_id, password) VALUES (?, ?)");
            $stmt->execute([$user_id, $hashed_password]);
            $_SESSION['user_id'] = $user_id;
            header("Location: login.php");
            exit();
        } catch(PDOException $e) {
            if (str_contains($e->getMessage(), 'unique')) {
                $error = "このユーザーIDはすでに使われています。";
            } else {
                $error = "登録失敗: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>新規登録</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>アカウント新規作成</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>ユーザーID:</label>
                <input type="text" name="user_id" required>
            </div>
            <div class="form-group">
                <label>パスワード:</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>パスワード（確認）:</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit">登録</button>
        </form>
        <p>アカウントをお持ちですか？ <a href="login.php">ログイン</a></p>
    </div>
</body>
</html>