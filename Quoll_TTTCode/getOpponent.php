<?php
	include "tttgame.php";
	
	session_start();
	$username = $_SESSION["username"];
	
	$game = new tttgame();
	$game -> getGame($username);
	if (strcasecmp($username, $game -> getPlayerX()) == 0)
		echo json_encode($game -> getPlayerO());
	else
		echo json_encode($game -> getPlayerX());
?>