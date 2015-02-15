<?php
	include "playerstats.php";
	
	$stats = new playerstats();
	$stats -> updateStandings();
	$topTen = $stats -> getTopTen();
	
	echo json_encode($topTen);
?>