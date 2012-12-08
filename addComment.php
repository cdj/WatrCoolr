<?php
include 'dbConnection.php';

if ((isset($_POST['MediaID'])) && is_numeric($_POST['MediaID'])
	&& (isset($_POST['UserID'])) && is_numeric($_POST['UserID'])
	&& (isset($_POST['CommentText'])) && (strlen(trim($_POST['CommentText'])) > 0)) {
	
	$inputField = mysql_real_escape_string(htmlspecialchars($_POST['CommentText']));
	$mediaID = $_POST['MediaID'];
	$userID = $_POST['UserID'];
	
	$con = mysql_connect($dbServerName,$dbUserName,$dbUserPassword);
	if (!$con)
	{
		$errorText = mysql_error();
		header('HTTP/1.1 500 '.$errorText);
		die('Could not connect: ' . $errorText);
	}
	
	mysql_select_db($dbName, $con);
	
	// Figure out the current play time for the media
	$sql="SELECT CurrentTime FROM Media WHERE MediaID=".$mediaID;
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	
	// add the comment
	$sql="INSERT INTO Comments (`UserID`, `MediaID`, `Comment`, `CommentTime`, `PlayTime`, `PhotoURL`, `PhotoPosX`, `PhotoPosY`)".
		 " VALUES ('".$userID."', '".$mediaID."', '".$inputField."', UTC_TIMESTAMP, '".$row['CurrentTime']."', NULL, NULL, NULL)";
	
	if (!mysql_query($sql,$con))
	{
		$errorText = mysql_error();
		header('HTTP/1.1 500 '.$errorText);
		die('Error: ' . $errorText);
	}
	
	mysql_close($con);
}
?>