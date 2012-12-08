<?php
include 'dbConnection.php';

if ((isset($_POST['MediaID'])) && is_numeric($_POST['MediaID'])
	&& (isset($_POST['CurrentTime'])) && is_numeric($_POST['CurrentTime'])
	&& (isset($_POST['State'])) && (strlen(trim($_POST['State'])) > 0)) {
	
	$mediaState = mysql_real_escape_string(htmlspecialchars(strip_tags($_POST['State'])));
	$mediaID = $_POST['MediaID'];
	$currentTime = $_POST['CurrentTime'];
	
	$con = mysql_connect($dbServerName,$dbUserName,$dbUserPassword);
	if (!$con)
	{
		$errorText = mysql_error();
		header('HTTP/1.1 500 '.$errorText);
		die('Could not connect: ' . $errorText);
	}
	
	mysql_select_db($dbName, $con);
	$sql="UPDATE `Media` SET `State`='".$mediaState."', `CurrentTime`=".$currentTime." WHERE `MediaID`=".$mediaID;
	$result = mysql_query($sql);
	if (!mysql_query($sql,$con))
	{
		$errorText = mysql_error();
		header('HTTP/1.1 500 '.$errorText);
		die('Error: ' . $errorText);
	}
	
	mysql_close($con);
}
?>