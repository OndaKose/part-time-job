<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = (int)$_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>投稿フォーム</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

  <!-- ✅ ヘッダー -->
  <header class="bg-blue-500 text-white py-4 px-6 flex justify-between items-center shadow">
    <h1 class="text-xl font-semibold">投稿フォーム</h1>
    <nav class="space-x-4">
      <a href="mainpage.php" class="hover:underline">トップ</a>
      <a href="profile.php" class="hover:underline">プロフィール</a>
      <a href="logout.php" class="hover:underline text-red-200">ログアウト</a>
    </nav>
  </header>

  <!-- ✅ 投稿フォーム -->
  <main class="flex justify-center mt-10 px-4">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-xl">
      <h2 class="text-2xl font-bold mb-6 text-gray-800">新規投稿</h2>
      <form action="submit_post.php" method="post" class="space-y-6">
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">

        <div>
          <label for="genre" class="block text-sm font-medium text-gray-700 mb-1">ジャンル</label>
          <select id="genre" name="genre" required class="w-full border border-gray-300 rounded-md px-4 py-2">
            <option value="">選択してください</option>
            <option value="飲食">飲食</option>
            <option value="販売">販売</option>
            <option value="教育">教育</option>
            <option value="運搬">運搬</option>
            <option value="事務">事務</option>
            <option value="その他">その他</option>
          </select>
        </div>

        <div>
          <label for="content" class="block text-sm font-medium text-gray-700 mb-1">内容</label>
          <textarea id="content" name="content" rows="5" required class="w-full border border-gray-300 rounded-md px-4 py-2 resize-none"></textarea>
        </div>

        <div class="text-right">
          <input type="submit" value="投稿する" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md font-semibold">
        </div>
      </form>
    </div>
  </main>

</body>
</html>