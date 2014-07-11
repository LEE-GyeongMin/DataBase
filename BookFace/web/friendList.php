<?php
	session_start();

	if (!$_SESSION["isLogin"]) {
		header("Location: ./login.php");
	}

	$DB_HOST = "localhost";
	$DB_USER = "user_BookFace";
	$DB_PASSWORD = "";
	$DB_DBNAME = "BookFace_DB";

	@$DB = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_DBNAME);
	if (mysqli_connect_error()) {
		# failDB == 1: Database 연결 실패
		$_SESSION["failDB"] = 1;
		header("Location: ./login.php");
		exit;
	}
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>BookFace</title>
	<link rel="stylesheet" type="text/css" href="./style/reset.css" />
	<link rel="stylesheet" type="text/css" href="./style/default.css" />
	<link rel="stylesheet" type="text/css" href="./style/friendList.css" />
</head>

<body>
<div id="headerWrapper">
	<div id="header">
		<a href="./list.php"><h1>BookFace</h1></a>
		<form id="search" action="searchUser.php" method="post">
			<input type="text" name="email" placeholder="친구를 검색해보세요">
			<input class="button" type="submit" value="검색">
		</form>
		<div id="login">
			<p><? echo( $_SESSION["name"] ); ?></p>
			<a class="button" href="./logout.php">로그아웃</a>
		</div>
	</div>
</div>

<div id="wrapper">
	<div id="userListWrapper">
	<div id="userList">
	<?
		$strQuery = "SELECT id FROM userData "
				. "WHERE email = '" . $_SESSION["email"] . "';";
		$queryResult = $DB->query($strQuery);
		$row = $queryResult->fetch_row();
		$user_id = $row[0];

		$strQuery = "SELECT name, email FROM userData "
				. "WHERE id IN "
					. "( "
						. "SELECT user_to FROM friendRelation "
						. "WHERE user_from = '" . $user_id . "' "
								. "AND isFriend = 1 "
					. "); ";

		#echo($strQuery);

		$queryResult = $DB->query($strQuery);

		while ($row = $queryResult->fetch_assoc()) {
			$rows[] = $row;
		}

		if (!$rows) {
			echo("<div class=\"user\">");
				echo("<p class=\"email\">친구가.... 없으시군요.... ㅠㅠ<br />검색으로 새로운 친구를 찾아보세요!</p>");
			echo("</div>");
			$DB->close();
			exit;
		}

		foreach($rows as $row) {
			echo("<div class=\"user\">");
				echo("<a class=\"name\">" . $row["name"] . "</a>");
				echo nl2br("<p class=\"email\">" . $row["email"] . "</p>");
			echo("</div>");
		}

	?>	
	</div>
	</div>
</div>
</body>
</html>

<?
	$DB->close();
	exit;
?>
