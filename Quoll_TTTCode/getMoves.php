<?php
	include "tttgame.php";
	
	session_start();
	$username = $_SESSION['username'];

	$game = new tttgame();
	$game -> getGame($username);
	$moves = $game -> getMoves();
	if ($moves == false)
		return json_encode(false);
	else
		echo json_encode(str_split ($moves, 1));
?>