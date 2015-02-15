<?php
	include "playerstats.php";
		
	session_start();
	if ($_GET["username"] == "this")
		$username = $_SESSION['username'];
	else
		$username = $_GET["username"];
	
	$playerstats = new playerstats();
	$playerstats -> getPlayerStats($username);
	$wins = $playerstats -> getWins();
	$losses = $playerstats -> getLosses();
	$ties = $playerstats -> getTies();
	$total = $playerstats -> getTotal();
	$rank = $playerstats -> getRank();
	$standing = $playerstats -> getStanding();
	$stats = [];
	array_push($stats, $username, $wins, $losses, $ties, $total, $rank, $standing);
	echo json_encode($stats);

?>