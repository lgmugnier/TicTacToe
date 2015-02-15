<?php
	include "playerstats.php";
	include "tttgame.php";
	
	session_start();
	$username = $_SESSION['username'];
	
	$game = new tttgame();
	$game -> getGame($username);
	$type = $game -> getGametype();
	
	if ($type == "random")
	{
		$player = new playerstats();
		$player -> getPlayerStats($username);
		$player -> addWin();
	}
?>