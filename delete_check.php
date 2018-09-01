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

//前後にある半角全角スペースを削除する関数
function spaceTrim ($str) {
	// 行頭
	$str = preg_replace('/^[ 　]+/u', '', $str);
	// 末尾
	$str = preg_replace('/[ 　]+$/u', '', $str);
	return $str;
}

//エラーメッセージの初期化
$errors = array();
    
if(empty($_POST)) {
	header("Location: delete.php");
	exit();
}else{
    
    //POSTされたデータを各変数に入れる
	$account = isset($_POST['account']) ? $_POST['account'] : NULL;
	$mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;
	
	//前後にある半角全角スペースを削除
	$account = spaceTrim($account);
	$mail = spaceTrim($mail);
	
	//メールアドレス入力判定
	if ($mail == ''):
		$errors['mail'] = "メールアドレスが入力されていません。";
	endif;

	//アカウント入力判定
	if ($account == ''):
		$errors['account'] = "アカウントが入力されていません。";
	elseif(mb_strlen($account)>10):
		$errors['account_length'] = "アカウントは10文字以内で入力して下さい。";
	endif;
	
}

//エラーが無ければ実行する
if(count($errors) === 0){
	try{
	    //例外処理を投げる（スロー）ようにする
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		//アカウントで検索
		$statement = $dbh->prepare("SELECT * FROM member WHERE mail=(:mail) AND account=(:account)");
		$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
		$statement->bindValue(':account', $account, PDO::PARAM_STR);
		$statement->execute();
		
		//アカウントとメールアドレスが一致
		if($row = $statement->fetch()){
		    
		    //セッションハイジャック対策
				session_regenerate_id(true);
		    
		    // データの削除
		    $sql = "DELETE FROM member WHERE mail='$mail' AND account='$account'";
		    $result = $dbh->query($sql);
		    
		    echo "退会完了いたしました";
		    exit();
		    
		}else{
		    $errors['mail_account'] = "登録されていませんでした。";
		}
		
		//データベース接続切断
		$dbh = null;
		
	}catch (PDOException $e){
		print('Error:'.$e->getMessage());
		die();
	}
}

?>

<!DOCTYPE html>
<html>
<head>
<title>会員登録解除(退会)確認画面</title>
<meta charset="utf-8">
</head>
<body>
<h1>会員登録解除(退会)確認画面</h1>
 
<?php if(count($errors) > 0): ?>
 
<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>
 
<input type="button" value="戻る" onClick="history.back()">
 
<?php endif; ?>
 
</body>
</html>