<?php
	session_start();

	$DB_HOST = "localhost";
	$DB_USER = "user_BookFace";
	$DB_PASSWORD = "";
	$DB_DBNAME = "BookFace_DB";

	# Database 연결
	@$DB = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_DBNAME);
	if (mysqli_connect_errno()) {
		# failDB == 1: Database 연결 실패
		$_SESSION["failDB"] = 1;
		header("Location: ./login.php");
		exit;
	}

	function writeArticle($DB, $email, $content) {
		# get User Id
		$strQuery = "SELECT id FROM userData WHERE email = '"
				. $email . "';";

		$queryResult = $DB->query($strQuery);
		$row = $queryResult->fetch_row();
		$user_id = $row[0];

		# INSERT id_writer AND content
		$strQuery = "INSERT INTO article (id_writer, content) "
				. "VALUES ('"
					. $user_id . "', '"
					. $content . "'"
				. ");";
		$DB->query($strQuery);

	}

	$arrayInput = array(email, content);
	foreach ($arrayInput as $var) {
		$$var = addslashes($_POST[$var]); 
	}

	if (!$content) {
		# failWrite == 1: Content가 입력되지 않음
		$_SESSION["failWrite"] = 1;
		header("Location: ./list.php");
		exit;
	}

	writeArticle($DB, $_SESSION["email"], $content);
	$DB->close();

	header("Location: ./list.php");
	exit;

?>
