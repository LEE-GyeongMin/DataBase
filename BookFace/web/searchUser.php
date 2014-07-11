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
	<link rel="stylesheet" type="text/css" href="./style/searchUser.css" />
</head>

<body>
<div id="headerWrapper">
	<div id="header">
		<a href="./list.php"><h1>BookFace</h1></a>
		<form id="search" action="searchUser.php" method="post">
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
	<div id="userListWrapper">
	<div id="userList">
		<h1>모든 결과</h1>
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
					. ") "
				. "AND "
					. "( "
						. "email LIKE '%" . $_POST["searchInput"] . "%' "
						. "OR name LIKE '%" . $_POST["searchInput"] . "%' "
					. ") "
				. ";";
		#echo($strQuery);
		$queryResult = $DB->query($strQuery);
		while ($row = $queryResult->fetch_assoc()) {
			$rows_friend[] = $row;
		}

		$strQuery = "SELECT name, email FROM userData "
				. "WHERE id NOT IN "
					. "( "
						. "SELECT user_to FROM friendRelation "
						. "WHERE user_from = '" . $user_id . "' "
								. "AND isFriend = 1 "
					. ") "
				. "AND id != '" . $user_id . "' "
				. "AND "
					. "( "
						. "email LIKE '%" . $_POST["searchInput"] . "%' "
						. "OR name LIKE '%" . $_POST["searchInput"] . "%' "
					. ") "
				. ";";
		#echo($strQuery);
		$queryResult = $DB->query($strQuery);
		while ($row = $queryResult->fetch_assoc()) {
			$rows_notFriend[] = $row;
		}

		if (!$rows_friend && !$rows_notFriend) {
			$strHTML =
				"<div class=\"user\">"
					. "<p class=\"email\">해당되는 회원이 없네요 ㅠㅠ</p>"
				. "</div>";
			echo($strHTML);
			$DB->close();
			exit;
		}

		if ($rows_friend) {
			foreach($rows_friend as $row) {
			$strHTML =
				"<div class=\"user\">"
					. "<a class=\"name\">" . $row["name"] . "</a>"
					. "<p class=\"email\">(" . $row["email"] . ")</p>"
					. "<p>야는 이미 친구여</p>"
					. "<form action=\"./relationController.php\" method=\"post\">"
						. "<input type=\"hidden\" name=\"email_user_from\" value=\"" . $_SESSION["email"] . "\">"
						. "<input type=\"hidden\" name=\"email_user_to\" value=\"" . $row["email"] . "\">"
						. "<input type=\"hidden\" name=\"beFriend\" value=0>"
						. "<input type=\"submit\" value=\"친구 끊기\">"
					. "</form>"
				. "</div>";
			echo($strHTML);
			}
		}

		if ($rows_notFriend) {
			foreach($rows_notFriend as $row) {
			$strHTML =
				"<div class=\"user\">"
					. "<a class=\"name\">" . $row["name"] . "</a>"
					. "<p class=\"email\">(" . $row["email"] . ")</p>"
					. "<form action=\"./relationController.php\" method=\"post\">"
						. "<input type=\"hidden\" name=\"email_user_from\" value=\"" . $_SESSION["email"] . "\">"
						. "<input type=\"hidden\" name=\"email_user_to\" value=\"" . $row["email"] . "\">"
						. "<input type=\"hidden\" name=\"beFriend\" value=1>"
						. "<input type=\"submit\" value=\"친구 요청\">"
					. "</form>"
				. "</div>";
			echo($strHTML);
			}
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
