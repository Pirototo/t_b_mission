<?php
//セッション開始
session_start();

$_SESSION['name'] = $_POST['name'];
$_SESSION['mail'] = $_POST['mail'];
$_SESSION['inquiry'] = $_POST['inquiry'];

//エラーメッセージの初期化
$errors = array();

if(empty($_POST)) {
	header("Location: regist.html");
	exit();
}else{
	//POSTされたデータを変数に入れる
	$mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;
	$name = isset($_POST['name']) ? $_POST['name'] : NULL;
	$inquiry = isset($_POST['inquiry']) ? $_POST['inquiry'] : NULL;
	
	//名前入力判定
	if ($name == ''){
		$errors['name'] = "名前が入力されていません。";
	}

	//メール入力判定
	if ($mail == ''){
		$errors['mail'] = "メールが入力されていません。";
	}else{
		if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail) && !filter_var($mail, FILTER_VALIDATE_EMAIL)){
			$errors['mail_check'] = "メールアドレスの形式が正しくありません。";
		}
	}
	
	//お問い合わせ入力判定
	if ($inquiry == ''){
		$errors['inquiry'] = "お問い合わせ内容が入力されていません。";
	}
	
}
	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sample</title>
</head>
 
<body>
 
 <?php if (count($errors) === 0): ?>

<h2>問合せ内容</h2>    
 
<form action="mailto.php" method="post">
 
<table border="1">
<tr>
<td>名前</td>
<td><?php echo $_POST["name"]; ?></td>
</tr>
<tr>
<td>メールアドレス</td>
<td><?php echo $_POST["mail"]; ?></td>
</tr>
<tr>
<td>問い合わせ内容</td>
<td><?php echo $_POST["inquiry"]; ?></td>
</tr>
</table>

 
<input type="submit" value="送信" />
</form>

<?php elseif(count($errors) > 0): ?>

<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>

<input type="button" value="戻る" onClick="history.back()">

<?php endif; ?>

 
</body>
</html>