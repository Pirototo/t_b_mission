<?php
session_start();
 
header("Content-type: text/html; charset=utf-8");
 
// ログイン状態のチェック
if (!isset($_SESSION["account"])) {
	header("Location: login_form.php");
	exit();
}
 
$account = $_SESSION['account'];

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="hue.css">
</head>
<body>
<h1>ようこそ English test for intermediate へ</h1>
<p> <?php echo htmlspecialchars($account,ENT_QUOTES)."さん、こんにちは！"; ?> </p>
<div id="header">
    <a href="delete.php" class="link delete">退会する</a>
    <a href="logout.php" class="link logout">ログアウト</a>
    <a href="regist.html" class="link inquiry">お問い合わせ</a>
</div>
<div class="main">
    <h2>英単語テスト一覧</h2>
    <a href='eigo.php'>英単語テスト</a><br><br>
    <a href='eigo2.php'>英単語テスト2</a><br><br>
    <a href='eigo3.php'>英単語テスト3</a><br>
</main>
</body>
</html>