<?php
include 'dbConnection.php';
include 'constants.php';
if (isset($_GET['MediaID'])) {
	$con = mysql_connect($dbServerName,$dbUserName,$dbUserPassword);
	if (!$con)
	{
		$errorText = mysql_error();
		header('HTTP/1.1 500 '.$errorText);
		die('Could not connect: ' . $errorText);
	}
	$mediaID = $_GET['MediaID'];

	mysql_select_db($dbName, $con);

	$sql="SELECT c.CommentID as CommentID, c.UserID as UserID, c.Comment as Comment, c.PlayTime as PlayTime, u.UserName as UserName, c.CommentTime + 0 as CommentTime, c.Mood as Mood".
		" FROM Comments c".
		" INNER JOIN Users u".
		" ON c.UserID = u.UserID".
		" INNER JOIN Media m".
		" ON c.MediaID = m.MediaID".
		" WHERE m.MediaID = ".$mediaID.
		" AND (m.CurrentTime - ".Constants::commentPostSpan.") <= c.PlayTime".
		" AND (m.CurrentTime + ".Constants::commentPreSpan.") >= c.PlayTime".
		" ORDER BY c.PlayTime, c.CommentTime";
	$result = mysql_query($sql);
	if (!$result)
	{
		$errorText = mysql_error();
		header('HTTP/1.1 500 '.$errorText);
		die('Error: ' . $errorText);
	}
	else
	{
		$ret = array();
		while ($row = mysql_fetch_assoc($result)) {
			$ret[] = $row;
		}
		echo json_encode($ret);
	}
	mysql_close($con);
}
?>