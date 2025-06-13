<?php
$dsn = 'pgsql:host=localhost;port=5432;dbname=matsuri';
$user = 'matsuri';
$password = 'I6MstEzi';

try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    exit('DB接続失敗: ' . $e->getMessage());
}
?>
