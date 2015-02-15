<?php
	include "playerinfo.php";
	
	session_start();
	$username = $_SESSION["username"];
	
	$player = new playerinfo();
	$player -> getPlayer($username);
	$ingame = $player -> getinGame();
	
	if ($ingame == 0)
		echo json_encode(false);
	elseif ($ingame == 1)
		echo json_encode(true);
?>