<?php
$dsn = 'pgsql:host=localhost;port=5432;dbname=ayae';
$user = 'ayae';
$password = '7VPF3knJ';

try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    exit('DB接続失敗: ' . $e->getMessage());
}
?>
