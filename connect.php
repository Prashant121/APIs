<?php
$servername = "htdb.ciqltwapt3ps.us-east-1.rds.amazonaws.com";

$username = "htdev";

$password = "dev12345";

$dbname = "htdbmain";

$conn = mysqli_connect($servername, $username, $password);
if (!$conn){
	die('Could not connect: ');
}
$db_selected = mysqli_select_db($conn, $dbname);
if (!$db_selected)
{
	die ('ERROR::'.mysqli_connect_error());
}
