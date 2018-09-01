<?php
session_start();
 
header("Content-type: text/html; charset=utf-8");
 
//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
if ($_POST['token'] != $_SESSION['token']){
	echo "不正アクセスの可能性あり";
	exit();
}
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
 
//データベース接続
require_once("db.php");
$dbh = db_connect();
 
//エラーメッセージの初期化
$errors = array();
 
if(empty($_POST)) {
	header("Location: registration_mail_form.php");
	exit();
}
 
$mail = $_SESSION['mail'];
$account = $_SESSION['account'];
 
//パスワードのハッシュ化
$cost = 12;
$salt = '適当な値';
$password_hash =  crypt($_SESSION['password'], '$2a$' . $cost . '$' . $salt . '$');
 
//ここでデータベースに登録する
try{
	//例外処理を投げる（スロー）ようにする
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//トランザクション開始
	$dbh->beginTransaction();
	
	//memberテーブルに本登録する
	$statement = $dbh->prepare("INSERT INTO member (account,mail,password,score,score2,score3) VALUES (:account,:mail,:password_hash,:score,:score2,:score3)");
	//プレースホルダへ実際の値を設定する
	$statement->bindValue(':account', $account, PDO::PARAM_STR);
	$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
	$statement->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
	$statement->bindValue(':score',0, PDO::PARAM_INT);
	$statement->bindValue(':score2',0, PDO::PARAM_INT);
	$statement->bindValue(':score3',0, PDO::PARAM_INT);
	$statement->execute();
		
	//pre_memberのflagを1にする
	$statement = $dbh->prepare("UPDATE pre_member SET flag=1 WHERE mail=(:mail)");
	//プレースホルダへ実際の値を設定する
	$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
	$statement->execute();
	
	// トランザクション完了（コミット）
	$dbh->commit();
		
	//データベース接続切断
	$dbh = null;
	
	//セッション変数を全て解除
	$_SESSION = array();
	
	//セッションクッキーの削除・sessionidとの関係を探れ。つまりはじめのsesssionidを名前でやる
	if (isset($_COOKIE["PHPSESSID"])) {
    		setcookie("PHPSESSID", '', time() - 1800, '/');
	}
	
 	//セッションを破棄する
 	session_destroy();
 	
 	//登録完了のメールを送信
 	mb_language("Japanese");
 	mb_internal_encoding("UTF-8");
 	$to = $mail;
 	$subject = 'English test for intermediate 登録完了';
 	$body = "English test for intermediateに登録してくれてありがとうございます！\r\n";
 	$body .= "登録完了いたしましたのでその確認メールをお送りしました。\r\nこれからもよろしくお願いいたします。";
 	$headers = "From : メールアドレス\r\n";
 	$headers = "X-Mailer : PHP 5.2.4\r\n";
 	if(mb_send_mail($to, $subject, $body, $headers)){ // メールを送信
        echo "メールを送信しました";
      } else {
        echo "メールの送信に失敗しました";
      }
	
}catch (PDOException $e){
	//トランザクション取り消し（ロールバック）
	$dbh->rollBack();
	$errors['error'] = "もう一度やりなおして下さい。";
	print('Error:'.$e->getMessage());
}
 
?>
 
<!DOCTYPE html>
<html>
<head>
<title>会員登録完了画面</title>
<meta charset="utf-8">
</head>
<body>
 
<?php if (count($errors) === 0): ?>
<h1>会員登録完了画面</h1>
 
<p>登録完了いたしました。ログイン画面からどうぞ。</p>
<p><a href="login_form.php">ログイン画面</a></p>

 
<?php elseif(count($errors) > 0): ?>
 
<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>
 
<?php endif; ?>
 
</body>
</html>