<?php
	include "tttgame.php";
	
	session_start();
	$username = $_SESSION['username'];
	$game = new tttgame();
	$game -> getGame($username);
	$activeplayer = $game -> getActivePlayer();
	if (strcasecmp($username, $activeplayer) == 0)
		echo json_encode(true);
	elseif ($activeplayer == false)
		echo json_encode("no game");
	else
		echo json_encode(false);
?>