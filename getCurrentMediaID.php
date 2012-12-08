<?php 
include 'dbConnection.php';

$con = mysql_connect($dbServerName,$dbUserName,$dbUserPassword);
if (!$con)
{
	$errorText = mysql_error();
	header('HTTP/1.1 500 '.$errorText);
	die('Could not connect: ' . $errorText);
}

mysql_select_db($dbName, $con);

// Figure out the current play time for the media
$sql="SELECT MediaID FROM Media ORDER BY ServerTime DESC";
$result = mysql_query($sql);
if (!$result)
{
	$errorText = mysql_error();
	header('HTTP/1.1 500 '.$errorText);
	die('Error: ' . $errorText);
}
else
{
	$row = mysql_fetch_array($result);
	echo $row['MediaID'];
}
mysql_close($con);
?>