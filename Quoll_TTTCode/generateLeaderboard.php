<?php
	include "playerstats.php";
	
	session_start();
	$username = $_SESSION['username'];
	
	$stats = new playerstats();
	$stats -> updateStandings();
	echo json_encode($stats -> getTopTen());
?>