<?php
	session_start();
	
	if ($_SESSION["isLogin"]) {
		header("Location: ./list.php");
	}
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>BookFace 시작하기</title>
	<link rel="stylesheet" type="text/css" href="./style/reset.css" />
	<link rel="stylesheet" type="text/css" href="./style/default.css" />
	<link rel="stylesheet" type="text/css" href="./style/login.css" />
</head>

<body>
<div id="headerWrapper">
	<div id="header">
		<a href="./list.php"><h1>BookFace</h1></a>
		<form id="login" action="loginController.php" method="post">
			<div id="loginInput">
				<input type="text" name="email" placeholder="Email">
				<input type="password" name="password" placeholder="Password">
			</div>
			<input class="button" type="submit" value="로그인">
		</form>
	</div>
</div>

<div id="status">
	<?
		if ($_SESSION["failDB"]) {
			echo("<p class='warning'>");
			echo("Database 연결 실패. 잠시 후 시도해주세요");
			echo("</p>");
		}

		if ($_SESSION["failLogin"]) {
			echo("<p class='warning'>");
			if ($_SESSION["failLogin"] == 1) {
				echo("해당되는 회원정보가 없습니다");
			}
			else if ($_SESSION["failLogin"] == 2) {
				echo("로그인 입력은 해주셔야죠.... ㅠㅠ");
			}
			echo("</p>");
		}

		if ($_SESSION["failRegister"]) {
			echo("<p class='warning'>");
			if ($_SESSION["failRegister"] == 1) {
				echo("회원가입에 빠진 값이 있네요");
			}
			else if ($_SESSION["failRegister"] == 2) {
				echo("이미 사용중인 이메일 입니다");
			}
			echo("</p>");
		}

		if ($_SESSION["isRegister"]) {
			echo("<p class='success'>");
			echo("회원가입을 축하드립니다!");
			echo("</p>");
		}

		session_unset("failDB");
		session_unset("failLogin");
		session_unset("failRegister");
		session_unset("isRegister");
	?>
</div>

<div id="registerWrapper">
	<div id="img"></div>
	<div id="registerInput">
		<h1>지금 가입하세요!</h1>
		<form action="registerController.php" method="post">
			<input type="text" name="name" placeholder="Name"><br>
			<input type="text" name="email" placeholder="Email"><br>
			<input type="password" name="password" placeholder="Password"><br>
			<input class="button" type="submit" value="회원가입">
		</form>
	</div>
</div>
</body>
</html>
