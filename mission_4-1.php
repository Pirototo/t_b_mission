<?php

	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn,$user,$password);
	
	$sql = "CREATE TABLE tabl"
	." ("
	. "id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date DATETIME,"
	. "password TEXT"
	.");";
	$stmt = $pdo->query($sql);
		        
?>

<?php

if (isset($_POST["edit"]) && $_POST["edit"] != ""){
	$edit_num = $_POST["edit"];
	$sql = 'SELECT * FROM tabl';
	$results = $pdo -> query($sql);
	foreach ($results as $row){
		if ($row['id'] == $edit_num && $_POST["password"] == $row['password']) {
			$editedName = $row['name'];
			$editedcomment = $row['comment'];
        }
    }
}

if (isset($_POST["name"]) && isset($_POST["comment"]) && isset($_POST["submit_btn"])&& $_POST["name"] != "" && $_POST["comment"] != ""){
	if (($_POST["editnum"] > 0)){
		$id = $_POST["editnum"];
		$nm = $_POST["name"];
		$kome = $_POST["comment"]; 
		$sql = "update tabl set name='$nm' , comment='$kome' where id = $id";
		$result = $pdo->query($sql);
	}
	 else{
	 	$sql = $pdo -> prepare("INSERT INTO tabl (name, comment,date,password) VALUES (:name, :comment, :date, :password)");
	 	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	 	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	 	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
	 	$sql -> bindParam(':password', $password, PDO::PARAM_STR);
	 	$name = $_POST["name"];
	 	$comment = $_POST["comment"]; 
	 	$date = date('Y-m-d H:i:s');
	 	$password = $_POST["password"];
	 	$sql -> execute();
	 	}
}

$pattern="^(\s|　)+$";
if (isset($_POST["delete"]) && !mb_ereg_match($pattern, $n) && $_POST["delete"] != ""){
	$id = $_POST["delete"];
	$sql = 'SELECT * FROM tabl';
	$results = $pdo -> query($sql);
	foreach ($results as $row){
		if ($_POST["password"] == $row["password"]) {
			$sql = "delete from tabl where id=$id";
			$result = $pdo->query($sql);
		}
	}
}

?>

<!DOCTYPE html>
<html lang = "ja">
<head>
<meta charset = "utf-8">
</head>
<body>
    <form action = "mission_4-1.php" method = "POST">
        <input type = "text" name = "name" value = "<?php echo $editedName; ?>" placeholder = "名前"><br/>
        <input type = "hidden" name = "editnum" value = "<?php echo $edit_num; ?>">
        <input type = "text" name = "comment" value = "<?php echo $editedcomment; ?>" placeholder = "コメント"><br/>
        <input type = "password" name ="password" placeholder = "パスワード"><br/>
        <input type = "submit"  name = "submit_btn" value ="送信">
    </form>
    
    <br/>
    
    <form action = "mission_4-1.php" method = "POST">
        <input type = "text" name = "delete" placeholder = "削除対象番号"><br/>
        <input type = "password" name ="password" placeholder = "パスワード"><br/>
        <input type = "submit" value ="送信">
    </form>
    
    <br/>
    
    <form action = "mission_4-1.php" method = "POST">
        <input type = "text" name = "edit" placeholder = "編集対象番号"><br/>
        <input type = "password" name = "password" placeholder = "パスワード"><br/>
        <input type = "submit" name = "edit_btn" value ="送信">
    </form>
    
<?php  
    
    $sql = 'SELECT * FROM tabl ORDER BY id ASC';
	$results = $pdo -> query($sql);
	foreach ($results as $row){
		 //$rowの中にはテーブルのカラム名が入る
		 echo $row['id'].', ';
		 echo $row['name'].', ';
		 echo $row['comment'].', ';
		 echo $row['date'].'<br>';
	}
	
?>

</body>
</html>