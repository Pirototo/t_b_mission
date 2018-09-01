<?php

	//データベース接続
	require_once("db.php");
	$pdo = db_connect();
	
	$sql = "CREATE TABLE eigo2"
	." ("
	. "id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,"
	. "japanese TEXT,"
	. "english TEXT,"
	. "date DATETIME"
	.");";
	$stmt = $pdo->query($sql);
		        
?>

<?php

//編集番号指定
if (isset($_POST["edit"]) && $_POST["edit"] != ""){
	$edit_num = $_POST["edit"];
	$sql = 'SELECT * FROM eigo2';
	$results = $pdo -> query($sql);
	foreach ($results as $row){
		if ($row['id'] == $edit_num) {
			$editedja = $row['japanese'];
			$editeden = $row['english'];
        }
    }
}

//編集機能
if (isset($_POST["japanese"]) && isset($_POST["english"]) && isset($_POST["submit_btn"])&& $_POST["japanese"] != "" && $_POST["english"] != "" && isset($_POST["editnum"]) && $_POST["editnum"] != ""){
	if (($_POST["editnum"] > 0)){
		$id = $_POST["editnum"];
		$ja = $_POST["japanese"];
		$en = $_POST["english"]; 
		$sql = "update eigo2 set japanese='$ja' , english='$en' where id = $id";
		$result = $pdo->query($sql);
	}
	else{
		echo "番号は正の番号で指定してください" . $_POST["editnum"] . '<br>';
	}
} // 投稿機能
elseif(isset($_POST["japanese"]) && isset($_POST["english"]) && isset($_POST["submit_btn"])&& $_POST["japanese"] != "" && $_POST["english"] != ""){
	 $sql = $pdo -> prepare("INSERT INTO eigo2 (japanese, english, date) VALUES (:japanese, :english, :date)");
	 $sql -> bindParam(':japanese', $japanese, PDO::PARAM_STR);
	 $sql -> bindParam(':english', $english, PDO::PARAM_STR);
	 $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	 $japanese = $_POST["japanese"];
	 $english = $_POST["english"]; 
	 $date = date('Y-m-d H:i:s');
	 $sql -> execute();
}

?>

<!DOCTYPE html>
<html lang = "ja">
<head>
<meta charset = "utf-8">
</head>
<body>
    <form action = "eigo_data2.php" method = "POST">
        <input type = "text" name = "japanese" value = "<?php echo $editedja; ?>" placeholder = "日本語"><br/>
        <input type = "hidden" name = "editnum" value = "<?php echo $edit_num; ?>">
        <input type = "text" name = "english" value = "<?php echo $editeden; ?>" placeholder = "英単語"><br/>
        <input type = "submit"  name = "submit_btn" value ="送信">
    </form>
    
    <br/>
    
    <form action = "eigo_data2.php" method = "POST">
        <input type = "text" name = "edit" placeholder = "編集対象番号"><br/>
       	<input type = "submit" name = "edit_btn" value ="送信">
    </form>
    
<?php  
    
    //表の中身表示
    $sql = 'SELECT * FROM eigo2 ORDER BY id ASC';
	$results = $pdo -> query($sql);
	foreach ($results as $row){
		 //$rowの中にはテーブルのカラム名が入る
		 echo $row['id'].', ';
		 echo $row['japanese'].', ';
		 echo $row['english'].', ';
		 echo $row['date'].'<br>';
	}
	
?>

</body>
</html>