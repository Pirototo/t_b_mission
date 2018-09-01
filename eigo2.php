<?php
session_start();
//日本語化
header("Content-type: text/html; charset=utf-8");

//データベース接続
require_once("db.php");
$dbh = db_connect();

//質問番号配置
$question_num = range(1, 150); 
shuffle($question_num); //質問番号をシャッフル
$number = array(); //この変数は配列ですよという宣言
for($i = 0; $i < 4; $i++){
    $number[$i] = $question_num[$i];
}

// 英語問題選択
$engilsh = array();
for($i = 0; $i < 4; $i++){
    $id = $number[$i];
    $sql = $dbh -> prepare("SELECT * FROM eigo2 where id='$id'");
    $sql->execute();
    $row = $sql -> fetch(PDO::FETCH_ASSOC);
    $english[$i] = $row['english'];
}
    
// 正解の日本語取り出し
$id_ja = $number[2];
$sql = $dbh -> prepare("SELECT * FROM eigo2 where id='$id_ja'");
$sql->execute();
$row_ja = $sql -> fetch(PDO::FETCH_ASSOC);
$answer_ja = $row_ja['japanese'];

// 質問配置
$question = array($english[0], $english[1], $english[2], $english[3]); //4択の選択肢を設定
 
$answer = $question[2]; //正解の問題を設定
 
shuffle($question); //配列の中身をシャッフル

if ($_POST['question'] == "" && $_POST['answer'] == ""){
    $_SESSION['point'] = 0;
    $_SESSION['count'] = 0;
}

if($_POST['question'] ==  $_POST['answer'] && $_POST['question'] != "" && $_POST['answer'] != ""){
    $_SESSION['point'] += 1;
}
$_SESSION['count'] += 1;
 
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>簡易クイズプログラム</title>
</head>
<body>

<h2>問題</h2>
<?php echo $answer_ja ?><br/>
<br/>

<?php if($_SESSION['count'] < 10){ ?>
<form method="POST" action="eigo2.php">
	<?php foreach($question as $value){ ?>
   	<input type="radio" name="question" value="<?php echo $value; ?>" > <?php echo $value; ?><br>
   	<?php } ?>
   	<input type="hidden" name="answer" value="<?php echo $answer ?>">
   	<input type="submit" value="回答する">
</form>
<?php } ?>

<?php if($_SESSION['count'] == 10){ ?>
<form method="POST" action="answer2.php">
    <?php foreach($question as $value){ ?>
   	<input type="radio" name="question" value="<?php echo $value; ?>" > <?php echo $value; ?><br>
   	<?php } ?>
   	<input type="hidden" name="answer" value="<?php echo $answer ?>">
   	<input type="submit" value="回答する">
</form>
<?php } ?>
    

<?php echo $_SESSION['count'] . "問/" . "10" ?>
</body>
</html>