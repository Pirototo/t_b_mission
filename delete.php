<?php
session_start();
 
header("Content-type: text/html; charset=utf-8");
 
//クロスサイトリクエストフォージェリ（CSRF）対策
$_SESSION['token'] = hash_hmac('sha256' , uniqid(mt_rand(), true), false);
$token = $_SESSION['token'];
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
 
?>
 
<!DOCTYPE html>
<html lang = "ja">
<head>
<title>会員登録解除(退会)</title>
<meta charset="utf-8">
</head>
<body>
<h1>会員登録解除(退会)</h1>

<form action="delete_check.php" method="post">

        <p>登録メールアドレス：<input type = "text" name = "mail" size="50"></p>
        <p>アカウント：<input type = "text" name = "account" size="20"></p>
        
<input type="hidden" name="token" value="<?=$token?>">
<input type = "submit" value ="退会する">
</form>

</body>
</html>