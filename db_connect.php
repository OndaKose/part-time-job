<?php
$host = "localhost";
$dbname = "matsuri";
$user = "matsuri";
$password = "I6MstEzi";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "DB接続エラー: " . $e->getMessage();
    exit();
}
?>