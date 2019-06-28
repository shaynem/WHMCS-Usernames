<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

//If user input is an email address forward everything as-is to dologin.php
if (filter_var($_POST['username'], FILTER_VALIDATE_EMAIL)){
} else {
	
	//load DB configuration file and connect
	require_once('configuration.php');
	$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);

	//handle connection failure
	if (mysqli_connect_errno()) {
	    error_log("Connect failed: %s\n", mysqli_connect_error());
	} else {

		if ($rule = $mysqli->query("SELECT regexpr FROM tblcustomfields WHERE fieldname = 'Username' OR fieldname = 'username'")) { //select the regex from database
			
			if(preg_match($rule->fetch_object()->regexpr, $_POST['username'])) {

				//prepared statements ensure no SQL injection into the username field. Important since we bypass WHMCS to read from tables
				$stmt = $mysqli->prepare("SELECT id FROM tblcustomfieldsvalues WHERE value = ?"); //Select the user id from custom fileds table.
				$stmt->bind_param("s", $_POST['username']);
				$stmt->execute();

				if ($stmt) {
					$result = $stmt->get_result()->fetch_object()->id;
					$stmt->close();

					//match selected user id from usernames custom field table with email from clients table
					$stmt = $mysqli->prepare("SELECT email FROM tblclients WHERE id = ?");
					$stmt->bind_param("s", $result);
					$stmt->execute();

					if ($stmt) {

						if ($result = $stmt->get_result()) {
						    //get row from DB
						    $mail = $result->fetch_object()->email;
							
							//replace the variables that whmcs' dologin.php will read
						    $_POST['username'] = $mail;
						    $_REQUEST['username'] = $mail;
						}

						//Else, we do nothing and WHMCS fails to log the user in.

					} else {
						error_log("Error: Failed to select email from tblclients.");
					}

				} else {
					error_log("Error: Failed to select POSTED username from tblcustomfieldsvalues.");
				}
			} else {
				error_log("Error: invalid input in username field.");
			}
			
		} else {
			error_log("Error: Failed to select username regex.");
		}
		
	}

	//close the connection
	$mysqli->close();
}

include 'dologin.php'; //do the rest of the login process as per WHMCS

