<?php
	include "tttgame.php";
	
	session_start();
	if (empty($_GET["username"]))
		$username = $_SESSION["username"];
	else
		$username = $_GET["username"];
	
	$game = new tttgame();
	$game -> getGame($username);
	$playerX = $game -> getPlayerX();
	$playerO = $game -> getPlayerO();
	
	if (strcasecmp($username, $playerX) == 0)
		echo json_encode("x");
	elseif (strcasecmp($username, $playerO) == 0)
		echo json_encode("o");
?>