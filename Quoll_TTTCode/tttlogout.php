<?php
	include "playerinfo.php";
	include "tttgame.php";
	
	session_start();
	$username = $_SESSION["username"];

	$player = new playerinfo();
	$player -> getPlayer($username);
	$player -> putOffline();

	$game = new tttgame();
	$success = $game -> getGame($username);
	if ($success != false)
		$game -> updateNotice("quit");

		
	session_destroy();
	
?>