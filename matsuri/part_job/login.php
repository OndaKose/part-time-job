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
    $input_user_id = $_POST['user_id'];
    $input_password = $_POST['password'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$input_user_id]);
        $user = $stmt->fetch();
        if ($user && password_verify($input_password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            header("Location: mainpage.php");
            exit();
        } else {
            $error = "ユーザーIDまたはパスワードが間違っています。";
        }
    } catch(PDOException $e) {
        $error = "ログイン失敗: " . $e->getMessage();
    }
}
?>
<form method="POST" action="">
    <div class="form-group">
        <label>ユーザーID:</label>
        <input type="text" name="user_id" required>
    </div>
    <div class="form-group">
        <label>パスワード:</label>
        <input type="password" name="password" required>
    </div>
    <button type="submit">ログイン</button>
    <p>*はじめての方は<a href="./register.php">こちら</a>から登録してください。</p>
</form>