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

	$arrayInput = array(email_user_from, email_user_to, beFriend);
	foreach ($arrayInput as $var) {
		$$var = addslashes($_POST[$var]); 
	}

	$strQuery = "SELECT id FROM userData WHERE email = '"
			. $email_user_from . "';";
	$queryResult = $DB->query($strQuery);
	$row = $queryResult->fetch_row();
	#echo($row[0]);
	$user_from = $row[0];


	$strQuery = "SELECT id FROM userData WHERE email = '"
			. $email_user_to . "';";
	$queryResult = $DB->query($strQuery);
	$row = $queryResult->fetch_row();
	#echo($row[0]);
	$user_to = $row[0];

	if ($beFriend == 0) {
		$strQuery = "DELETE FROM friendRelation "
				. "WHERE "
						. "( "
							. "user_from = '" . $user_from . "' "
							. "AND "
							. "user_to = '" . $user_to . "' "
						. ") "
					. "OR "
						. "( "
							. "user_from = '" . $user_to . "' "
							. "AND "
							. "user_to = '" . $user_from . "' "
						. ") "
				. ";";
		#echo($strQuery);
		$DB->query($strQuery);
	}
	else if ($beFriend == 1) {
		$strQuery = "INSERT INTO friendRelation "
				. "( user_from, user_to, isFriend ) "
				. "VALUES "
					. "( "
						. "'" . $user_from . "', "
						. "'" . $user_to . "', "
						. "'" . $beFriend . "' "
					. ") "
				. ";";
		$queryResult = $DB->query($strQuery);
		$strQuery = "INSERT INTO friendRelation "
				. "( user_from, user_to, isFriend ) "
				. "VALUES "
					. "( "
						. "'" . $user_to . "', "
						. "'" . $user_from . "', "
						. "'" . $beFriend . "' "
					. ") "
				. ";";
		$queryResult = $DB->query($strQuery);
	}

	$DB->close();
	header("Location: ./list.php");
	exit;
?>
