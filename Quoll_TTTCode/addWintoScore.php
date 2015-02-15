<?php
	include "tttgame.php";
	
	session_start();
	$username = $_SESSION["username"];
	
	$game = new tttgame();
	$game -> getGame($username);
	$score = $game -> getScore();
	$playerX = $game -> getPlayerX();
	
	$split = strrpos($score, ',');
	
	if (strcasecmp($username, $playerX) == 0)
	{
		$userscore = substr($score, 0, $split);
		$userscore = intval($userscore);
		$userscore ++;
		$newScore = strval($userscore) . substr($score, $split);
		
		echo $newScore;
		$game -> updateScore($newScore);
	}
	else
	{
		$userscore = substr($score, $split + 1);
		$userscore = intval($userscore);
		$userscore ++;
		$newScore = substr($score, 0, $split + 1) . strval($userscore);
		
		echo $newScore;
		$game -> updateScore($newScore);
	}
	
?>