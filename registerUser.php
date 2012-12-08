<?php
include 'dbConnection.php';

if (isset($_POST['UserName']) && (strlen(trim($_POST['UserName'])) > 0))
{
	$userName = mysql_real_escape_string(htmlspecialchars(strip_tags($_POST['UserName'])));
	
	$con = mysql_connect($dbServerName,$dbUserName,$dbUserPassword);
	if (!$con)
	{
		$errorText = mysql_error();
		header('HTTP/1.1 500 '.$errorText);
		die('Could not connect: ' . $errorText);
	}
	
	mysql_select_db($dbName, $con);
	}
	
	$userID = -1;
	$num_rows = -1;
		
	do
	{
		$sql = "SELECT * FROM `Users` WHERE `UserName`='".$userName."' ORDER BY `UserID` DESC";
		$result = mysql_query($sql);
		if(!$result)
		{
			$errorText = mysql_error();
			header('HTTP/1.1 500 '.$errorText);
			die('Error: ' . $errorText);
		}
		$num_rows = mysql_num_rows($result);
		if ($num_rows > 0)
		{
			$row = mysql_fetch_array($result);
			$userID = $row['UserID'];
		}
		else
		{
			$sql = "INSERT INTO `Users` (`UserName`) VALUES ('".$userName."')";
			if (!mysql_query($sql,$con))
			{
				$errorText = mysql_error();
				header('HTTP/1.1 500 '.$errorText);
				die('Error: ' . $errorText);
			}
		}
	} while($num_rows <= 0);
	
	mysql_close($con);
	
	echo $userID;	
}
?>