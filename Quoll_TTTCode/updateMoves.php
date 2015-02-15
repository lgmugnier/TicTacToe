<?php
	include "tttgame.php";
	
	session_start();
	$username = $_SESSION['username'];
	
	$movesarray = $_GET['moves'];
	//$moves = implode("",json_decode($movesarray));
	$game = new tttgame();
	$game -> getGame($username);
	$game -> updateMoves($movesarray);
?>