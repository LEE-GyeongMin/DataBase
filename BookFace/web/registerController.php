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

	#$arrayInput = array(email, name, birth);
	$arrayInput = array(email, name, password);
	foreach ($arrayInput as $var) {
		$$var = addslashes($_POST[$var]); 
	}

	if (!$email or !$password or !$name) {
		# failRegister == 1: 빠진 값이 있습니다
		$_SESSION["failRegister"] = 1;
		header("Location: ./login.php");
		exit;
	}

	$encryptedPassword = sha1($password);

	$strQuery = "SELECT COUNT(*) FROM userData WHERE email = '"
			. $email . "';";

	$queryResult = $DB->query($strQuery);
	$row = $queryResult->fetch_row();
	$matchEmailNum = $row[0];

	if ($matchEmailNum > 0) {
		# failRegister == 2: 사용중인 이메일 입니다
		$_SESSION["failRegister"] = 2;
		header("Location: ./login.php");
		exit;
	}

	$strQuery = "INSERT INTO userData (email, password, name) "
			. "VALUES ('"
				. $email . "', '"
				. $encryptedPassword . "', '"
				. $name . "'"
			. ");";
	$queryResult = $DB->query($strQuery);

	$DB->close();

	# isRegister == 1: 회원가입 성공
	$_SESSION["isRegister"] = 1;
	header("Location: ./login.php");
	exit;
?>
