<?php
include 'dbConnection.php';

if (isset($_POST['MediaName']) && (strlen(trim($_POST['MediaName'])) > 0))
{
	$mediaName = mysql_real_escape_string(htmlspecialchars(urldecode($_POST['MediaName'])));
	
	$con = mysql_connect($dbServerName,$dbUserName,$dbUserPassword);
	if (!$con)
	{
		$errorText = mysql_error();
		header('HTTP/1.1 500 '.$errorText);
		die('Could not connect: ' . $errorText);
	}
	
	mysql_select_db($dbName, $con);
	
	$mediaExists = false;
	$row = NULL;
	$result = NULL;
	
	do
	{
		$sql="SELECT MediaID FROM Media WHERE MediaName='".$mediaName."'";
		$result = mysql_query($sql);		
		$mediaExists = $result && (mysql_num_rows($result) > 0);
		if(!$mediaExists)
		{
			$sql = "INSERT INTO `Media` (`State`, `ServerTime`, `CurrentTime`, `MediaName`)".
				   " VALUES ('new', CURRENT_TIMESTAMP, '0', '".$mediaName."')";			
			if (!mysql_query($sql,$con))
			{
				$errorText = mysql_error();
				header('HTTP/1.1 500 '.$errorText);
				die('Error: ' . $errorText);
			}
		}
	} while(!$mediaExists);
	$row = mysql_fetch_array($result);
	
	echo $row['MediaID'];
	
	mysql_close($con);
}
?>