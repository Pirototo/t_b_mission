<?php
	header("Content-Type: text/html; charset=UTF-8");

	//データベース接続
	require_once("db.php");
	$pdo = db_connect();

    $sql = 'SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo "<br/>";
	}
	echo "<hr>";	

    $sql = 'SELECT * FROM pre_member';
	$results = $pdo -> query($sql);
	echo "pre_member".'<br>';
	foreach ($results as $row){
		 //$rowの中にはテーブルのカラム名が入る
		 echo $row['id'].',';
		 echo $row['mail'].',';
		 echo $row['date'].'<br>';
	}

    $sql = 'SELECT * FROM member';
	$results = $pdo -> query($sql);
	echo "member".'<br>';
	foreach ($results as $row){
		 //$rowの中にはテーブルのカラム名が入る
		 echo $row['id'].',';
		 echo $row['account'].',';
		 echo $row['mail'].',';
		 echo $row['score'].',';
		 echo $row['score2'].',';
		 echo $row['score3'].',';
		 echo $row['password'].'<br>';
	}
	
	if($_POST["name"] == "%"){
		$sql = 'DROP TABLE pre_member';
		$stmt = $pdo -> query($sql); 
	}
	
	if($_POST["name"] == "%"){
		$sql = 'DROP TABLE member';
		$stmt = $pdo -> query($sql); 
	}
	
	$pdo = null;	

?>

<!DOCTYPE html>
<html lang = "ja">
<head>
<meta charset = "utf-8">
</head>
<body>
    <form action = "confirm.php" method = "POST">
   	 	<input type = "text" name = "name"><br/>
		<input type = "submit"  name = "submit_btn" value ="削除">
    </form>
</body>
</html>
