<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>投稿フォーム</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 40px;
            display: flex;
            justify-content: center;
        }

        .form-container {
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            margin-bottom: 25px;
            color: #333;
            font-size: 24px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            margin-bottom: 20px;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }

        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            border-color: #7aa7ff;
            outline: none;
        }

        input[type="submit"] {
            background-color: #4f83ff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #3a6ee8;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>投稿フォーム</h2>
        <form action="submit_post.php" method="post">
            <input type="hidden" name="user_id" value="1">

            <label for="genre">ジャンル:</label>
            <select id="genre" name="genre" required>
                <option value="">選択してください</option>
                <option value="飲食">飲食</option>
                <option value="販売">販売</option>
                <option value="教育">教育</option>
                <option value="運搬">運搬</option>
                <option value="事務">事務</option>
                <option value="その他">その他</option>
            </select>

            <label for="content">内容:</label>
            <textarea id="content" name="content" rows="5" required></textarea>

            <input type="submit" value="投稿する">
        </form>
    </div>
</body>
</html>
