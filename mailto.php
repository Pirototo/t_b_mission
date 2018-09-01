<?php
//セッション開始
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
	</head>
	<body>
		<?php
			mb_language("Japanese");
			mb_internal_encoding("UTF-8");
 
 			//メールの宛先
			$mailto = 'メールアドレス';
			
			$name = $_SESSION['name'];
			$mail = $_SESSION['mail'];
			$subject = "お問い合わせ";
			$inquiry = $_SESSION['inquiry'];
						
			//ヘッダーを作成
			$header = 'From: '.$mail."\r\n";
			$header .=  'Reply-To:'.$mail."\r\n";

			if(mb_send_mail($mailto, $subject, $inquiry, $header)){
				echo "メールを送信しました".'<br>';
				echo "<a href='login_admin.php'>メインページ</a>";
			} else {
				echo "メールの送信に失敗しました";
			}
		?>
	</body>
</html>
