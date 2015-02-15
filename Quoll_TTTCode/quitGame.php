<?php
	include "tttgame.php";
	
	session_start();
	$username = $_SESSION["username"];
	
	$game = new tttgame();
	$success = $game -> getGame($username)
	if ($success != false)
		$game -> updateNotice("quit");
?>