<?php
if ((isset($_POST['MediaID'])) && is_numeric($_POST['MediaID'])
	&& (isset($_POST['UserID'])) && is_numeric($_POST['UserID'])
	&& (isset($_POST['CommentText'])) && (strlen(trim($_POST['CommentText'])) > 0)) {
	
	$inputField = mysql_real_escape_string(htmlspecialchars($_POST['CommentText']));
	$mediaID = $_POST['MediaID'];
	$userID = $_POST['UserID'];
	
	$con = mysql_connect("localhost","root","");
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	
	mysql_select_db("WaterCooler", $con);
	
	// Figure out the current play time for the media
	$sql="SELECT PlayTime, State, (UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - UNIX_TIMESTAMP(ServerTime) + PlayTime) AS ProjectedPlayTime FROM Media WHERE MediaID=".$mediaID;
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	if($row['State'] == "playing")
	{
		$playTime = $row['ProjectedPlayTime'];
	}
	else
	{
		$playTime = $row['PlayTime'];
	}
	
	// add the comment
	$sql="INSERT INTO Comments (`UserID`, `MediaID`, `Comment`, `CommentTime`, `PlayTime`, `PhotoURL`, `PhotoPosX`, `PhotoPosY`)".
		 " VALUES ('".$userID."', '".$mediaID."', '".$inputField."', CURRENT_TIMESTAMP, '".$playTime."', NULL, NULL, NULL)";
	
	if (!mysql_query($sql,$con))
	{
		die('Error: ' . mysql_error());
	}
	
	mysql_close($con);
}
?>