<?php 
include 'dbConnection.php';

$con = mysql_connect($dbServerName,$dbUserName,$dbUserPassword);
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

mysql_select_db($dbName, $con);

// Figure out the current play time for the media
$sql="SELECT MediaID FROM Media ORDER BY ServerTime DESC";
$result = mysql_query($sql);
if (!$result)
{
	die('Error: ' . mysql_error());
}
else
{
	$row = mysql_fetch_array($result);
	echo $row['MediaID'];
}
mysql_close($con);
?>