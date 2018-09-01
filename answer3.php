<?php
//データベース接続
require_once("db.php");
$dbh = db_connect();

session_start();
if($_POST['question'] ==  $_POST['answer'] && $_POST['question'] != "" && $_POST['answer'] != ""){
    $_SESSION['point'] += 1;
}

$score = $_SESSION['point'];
$account = $_SESSION['account'];
$sql = "update member set score3='$score' where account='$account'";
$result = $dbh->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>簡易クイズプログラム - 結果</title>
</head>
<body>

<h2>クイズの結果</h2>
<?php echo $_SESSION['point'] . "/" . $_SESSION['count'] . "です！" . '<br>'; ?>

</body>
</html>
<?php

	$number = 1;
	$count = 0;
	$sql = 'SELECT * FROM member ORDER BY score3 DESC';
	$results = $dbh -> query($sql);
	echo '<br>'."ランキング！".'<br>';
	foreach ($results as $row){
		 //$rowの中にはテーブルのカラム名が入る
		 echo $number.',';
		 echo $row['account'].',';
		 echo $row['score3']."点".'<br>';
		 $number += 1;
		 $count += 1;
		 if ($count == 10){
		 	break;
		 }
    }
    echo '<br>';
    echo "<a href='login_admin.php'>メインページ</a>";

?>