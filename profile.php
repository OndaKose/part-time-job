<?php
session_start();
// データベース接続
$dsn = 'pgsql:host=localhost;port=5432;dbname=matsuri';
$user = 'matsuri';
$password = 'I6MstEzi';

try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    exit('DB接続失敗: ' . $e->getMessage());
}

// ユーザー情報を取得
$user_id = $_SESSION['user_id'] ?? null;    // セッションからuser_idを取得
$sql_user = "SELECT * FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql_user);   // セキュリティ対策
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC); // 実行

// 投稿数を取得
$sql_posts_number = "SELECT COUNT(*) AS content FROM posts WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql_posts_number);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$posts_number = $stmt->fetch(PDO::FETCH_ASSOC);

// 投稿内容を取得
$sql_post = "SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql_post);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdo = null;    // データベース接続を閉じる
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        profile
    </title>
    <style>
        /* アスタリスク：全てのHTML要素に適用 */
        * {
            margin: 0;  /* 外側の余白0 */
            padding: 0; /* 内側の余白0 */
            box-sizing: border-box; /* marginとpaddingを含めて計算 */
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;  /* whitesmoke */
            color: #333;
        }

        .header {
            position: fixed; /* 位置固定 */
            top: 0; /* 上端 */
            left: 0; /* 左端 */
            width: 100%; /* 横幅100% */
            height: 50px; /* ヘッダーの高さ */
            background-color: #f0f8ff; /* aliceblue */
            padding: 20px;
            text-align: left; /* テキスト：左 */
            font-size: 20px;
            border-bottom: 2px solid #ccc; /* 下線 */
            z-index: 1000; /* 他の要素の上に表示 */
        }

        .main {
            display: flex;
            flex-direction: row; /* 横に並べる */
            flex-grow: 1; /* 残りのスペースを占有 */
            margin-top: 60px; /* ヘッダーの高さ分の余白 */
            padding: 10px; /* 内側の余白 */
        }

        .side {
            width: 40%;
            height: 100%;
            background-color: #fffafa;  /* snow */
            margin: 10px;
            padding: 20px; /* 内側の余白 */
            border-radius: 5px; /* 角丸 */
        }

        /* プロフィールヘッダー部分(要素を包括) */
        .profile-header {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        /* ユーザー情報部分 */
        .user-info {
            flex-grow: 1;
            flex-direction: column; /* 縦 */
            justify-content: center; /* 水平方向：中央 */
            align-items: flex-start; /* 垂直方向：上 */
            margin-top: 10px; /* 上の余白 */
            line-height: 2.5; /* 行間 */
        }

        .name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* 投稿数 */
        .items {
            display: flex;
            flex-direction: column; /* 縦に並べる */
            flex: 1; /* 要素を均等に配置 */
            align-self: flex-start; /* 垂直方向：上に揃える */
            line-height: 1.5; /* 行間 */
            gap: 10px;
            margin-top: 15px;
        }

        .favorite {
            display: flex;
            flex-direction: column; /* 縦に並べる */
            justify-content: flex-start; /* 水平方向：左 */
            gap: 10px;
            margin-top: 50px;
        }

        .favorcontent {
            border: 1px solid #ccc; /* 枠線 */
            background-color: #fff; /* 白背景 */
            border-radius: 5px; /* 角丸 */
            padding: 10px; /* 内側の余白 */
            overflow: hidden; /* はみ出し防止 */
        }

        .posted {
            width: 60%;
            height: 100%;
            flex-direction: column; /* 縦に並べる */
            margin: 10px;
        }

        .card {
            border: 1px solid #ccc; /* 枠線 */
            background-color: #fff;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            overflow: hidden; /* はみ出し防止 */
        }        

    </style>
</head>
<body>
    <div class="header">profile</div>
    <div class="main">
        <div class="side">
            <div class="profile-header">
                <div class="user-info">
                    <h1 class="name"><?php echo htmlspecialchars($user['user_id'] ?? 'ユーザー名'); ?></h1>
                    <div class="items">
                        <p>投稿数</p>
                        <p><?php echo htmlspecialchars($posts_number['content'] ?? '0'); ?></p>
                    </div>
                </div>
            </div>
            <div class="favorite">
                <p>お気に入り投稿</p>
                <div class="favorcontent">
                    <p><?php echo htmlspecialchars($posts['content'] ?? 'xxx ...'); ?></p>
                </div>
            </div>
        </div>

    <!-- 画面右側(メイン) -->
        <div class="posted"> 
            <h3>投稿一覧</h3>
            <?php if ($posts): ?>
                <!-- カードの内容：ループ処理 -->
                <?php foreach($posts as $post): ?>
                    <div class="card">
                        <p>投稿日: <?php echo htmlspecialchars($post['time']); ?></p>
                        <p>職種: <?php echo htmlspecialchars($post['genre']); ?></p>
                        <p>内容: <?php echo htmlspecialchars($post['content']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>投稿がありません</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>