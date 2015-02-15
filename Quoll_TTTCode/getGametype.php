<?php
	include "tttgame.php";
	
	session_start();
	$username = $_SESSION["username"];
	
	$game = new tttgame();
	$game -> getGame($username);
	$gametype = $game -> getGametype();
	echo json_encode($gametype);
?>