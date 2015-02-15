<?php
	include "tttgame.php";
	
	session_start();
	$username = $_SESSION["username"];
	
	$game = new tttgame();
	$game -> getGame($username);
	$score = $game -> getScore();
	$split = strrpos($score, ',');
	
	$userscore = substr($score, 0, $split);
	$userscore = intval($userscore);
	$userscore ++;
	
	$oppscore = substr($score, $split + 1);
	$oppscore = intval($oppscore);
	$oppscore ++;
	
	$newScore = strval($userscore) . ',' . strval($oppscore);
	echo $newScore;
	$game -> updateScore($newScore);
?>