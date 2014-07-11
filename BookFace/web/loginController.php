<?php
	session_start();

	$DB_HOST = "localhost";
	$DB_USER = "user_BookFace";
	$DB_PASSWORD = "";
	$DB_DBNAME = "BookFace_DB";

	@$DB = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_DBNAME);
	if (mysqli_connect_errno()) {
		# failDB == 1: Database 연결 실패
		$_SESSION["failDB"] = 1;
		header("Location: ./login.php");
		exit;
	}


	function newLogin($DB, $inputEmail, $encryptedPassword) {
		$strQuery = "SELECT COUNT(*), name FROM userData "
				. "WHERE email = '" . $inputEmail . "' "
				. "AND password = '" . $encryptedPassword . "'";

		$queryResult = $DB->query($strQuery);
		$row = $queryResult->fetch_row();
		$matchUserNum = $row[0];

		if ($matchUserNum == 0) {
			# failLogin == 1: 해당되는 회원정보가 없습니다.
			$_SESSION["failLogin"] = 1;
			header("Location: ./login.php");
			exit;
		}
		else if ($matchUserNum > 1) {
			# ID가 중복된 경우
			exit;
		}

		$matchUserName = $row[1];

		$_SESSION["email"] = stripslashes($inputEmail);
		$_SESSION["name"] = stripslashes($matchUserName);
		$_SESSION["isLogin"] = 1;

		header("Location: ./list.php");
	}

	if (!$_SESSION["isLogin"]) {
		if ($_POST["email"] == NULL or $_POST["password"] == NULL) {
			# failLogin == 2: 아무것도 입력하지 않은 경우거나 URL 직접 접근.
			$_SESSION["failLogin"] = 2;
			header("Location: ./login.php");
			exit;
		}

		newLogin( $DB, $_POST["email"], sha1($_POST["password"]) );
	}
	else {
		header("Location: ./list.php");
	}

	$DB->close();
	exit;

?>
