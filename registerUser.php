<?php
if (isset($_POST['UserName']) && (strlen(trim($_POST['UserName'])) > 0))
{
	$userName = mysql_real_escape_string(htmlspecialchars(strip_tags($_POST['UserName'])));
	
	$con = mysql_connect("localhost","root","");
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	
	mysql_select_db("WaterCooler", $con);
	
	$sql = "INSERT INTO `Users` (`UserName`) VALUES ('".$userName."')";
	
	if (!mysql_query($sql,$con))
	{
		die('Error: ' . mysql_error());
	}
	else
	{
		// Get the user ID for the user we just registered
		$userID = mysql_insert_id();
		$sql = "SELECT * FROM `Users` WHERE `UserID`=".$userID;
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		if($row['UserName'] != $userName)
		{
			// semi-workaround for potential race condition
			$sql = "SELECT * FROM `Users` WHERE `UserID`<".$userID." AND `UserName`='".$userName."' ORDER BY `UserID` DESC";
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result);
			$userID = $row['UserID'];
		}
		echo $userID;
	}
	
	mysql_close($con);
}
?>