<?php

$searchTerm = mysql_real_escape_string($_GET['term']);

$user="user";
$password="userpassword";
$database="tttdatabase";
$server="localhost";

$conn = mysql_connect($server,$user,$password);
mysql_select_db($database) or die("Unable to select database");

$query = "SELECT username FROM playerinfo WHERE username LIKE '" . $searchTerm . "%';";
$result = mysql_query ($query) or die('SELECT query failed: ' . mysql_error());

$userArray = [];

while($row = mysql_fetch_array($result)){
	$userArray = array('label' => $row['username']);
}

echo json_encode($userArray);

flush();

mysql_close($conn);



?>