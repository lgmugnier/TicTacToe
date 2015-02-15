<?php
	include "tttgame.php";
	include "playerinfo.php";
	
	session_start();
	$username = $_SESSION["username"];
	
	$game = new tttgame();
	$game -> getGame($username);
	$playerX  = $game -> getPlayerX();
	$playerO = $game -> getPlayerO();
	$game -> closeGame();
	
	$player1 = new playerinfo();
	$player1 -> getPlayer($playerX);
	$player1 -> setinGame(0);
	$player2 = new playerinfo();
	$player2 -> getPlayer($playerO);
	$player2 -> setinGame(0);
?>