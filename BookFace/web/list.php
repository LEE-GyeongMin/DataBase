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
	<link rel="stylesheet" type="text/css" href="./style/list.css" />
</head>

<body>
<div id="headerWrapper">
	<div id="header">
		<a href="./list.php"><h1>BookFace</h1></a>
		<form id="search" action="./searchUser.php" method="post">
			<input type="text" name="searchInput" placeholder="친구를 검색해보세요">
			<input class="button" type="submit" value="검색">
		</form>
		<div id="login">
			<p><? echo( $_SESSION["name"] ); ?></p>
			<a class="button" href="./logout.php">로그아웃</a>
		</div>
	</div>
</div>

<div id="wrapper">
	<div id="writeWrapper">
		<form id="writeContent" action="writeController.php" method="post">
			<textarea name="content" placeholder="지금 어때요?"></textarea>
			<input class="button" type="submit" value="글쓰기" />
		</form>
	</div>

	<div id="articleWrapper">
	<div id="articlePositioner">
	<?
		$strQuery = "SELECT id FROM userData "
				. "WHERE email = '" . $_SESSION["email"] . "';";
		$queryResult = $DB->query($strQuery);
		$row = $queryResult->fetch_row();
		$user_id = $row[0];

		$strQuery = "SELECT u.name, a.content, a.time "
				. "FROM article a "
				. "INNER JOIN userData u "
						. "ON u.id = a.id_writer "
				. "WHERE u.id IN "
					. "( "
						. "SELECT user_to FROM friendRelation "
						. "WHERE user_from = '" . $user_id . "' "
								. "AND isFriend = 1 "
					. ") "
					. "OR u.id = '" . $user_id . "' "
				. "ORDER BY a.id DESC;";

		#echo($strQuery);

		$queryResult = $DB->query($strQuery);

		while ($row = $queryResult->fetch_assoc()) {
			$rows[] = $row;
		}

		if (!$rows) {
			echo("<div class=\"article\">");
				echo("<p class=\"content\">친구들을 만나 당신의 이야기를 들려주세요!</p>");
			echo("</div>");
			$DB->close();
			exit;
		}

		foreach($rows as $row) {
			echo("<div class=\"article\">");
				echo("<a class=\"writer\">" . $row["name"] . "</a>");
				echo nl2br("<p class=\"content\">" . $row["content"] . "</p>");
				echo("<p class=\"time\">" . $row["time"] . "</p>");
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
