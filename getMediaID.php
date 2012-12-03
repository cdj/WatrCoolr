<?php
include 'dbConnection.php';

if (isset($_POST['MediaName']) && (strlen(trim($_POST['MediaName'])) > 0))
{
	$mediaName = mysql_real_escape_string(htmlspecialchars($_POST['MediaName']));
	
	$con = mysql_connect($dbServerName,$dbUserName,$dbUserPassword);
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	
	mysql_select_db($dbName, $con);
	
	$mediaExists = false;
	$row = NULL;
	
	do
	{
		$sql="SELECT MediaID FROM Media WHERE MediaName=".$mediaName;
		$result = mysql_query($sql);		
		$mediaExists = (mysql_num_rows($result) > 0);
		if(!$mediaExists)
		{
			$sql = "INSERT INTO `Media` (`State`, `ServerTime`, `CurrentTime`, `MediaName`)".
				   " VALUES ('new', CURRENT_TIMESTAMP, '0', '".$mediaName."')";			
			if (!mysql_query($sql,$con))
			{
				die('Error: ' . mysql_error());
			}
		}
		$row = mysql_fetch_array($result);
	} while(!$mediaExists);
	
	echo $row['MediaID'];
	
	mysql_close($con);
}
?>