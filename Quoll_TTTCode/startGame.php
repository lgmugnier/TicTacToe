<?php
	include "playerinfo.php";
	include "tttgame.php";
	
	$opponent = $_GET["opponent"];
	$gametype = $_GET["type"];
	session_start();
	$username = $_SESSION["username"];
	
	$game = new tttgame();
	$rand = rand(0,1);
	
	if (strcasecmp($opponent, $username) != 0)
	{
		if ($rand == 0)
			$game -> beginNewGame($username, $opponent, $gametype);
		elseif ($rand == 1)
			$game -> beginNewGame($opponent, $username, $gametype);
	}

	$player1 = new playerinfo();
	$player1 -> getPlayer($username);
	$player1 -> setinGame(1);
	
	$player2 = new playerinfo();
	$player2 -> getPlayer($opponent);
	$player2 -> setinGame(1);
?>