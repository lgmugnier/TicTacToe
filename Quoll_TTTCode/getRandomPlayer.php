<?php
	include "playerinfo.php";
	
	session_start();
	$username = $_SESSION["username"];
	
	$player = new playerinfo();
	$players = $player -> generateRandomOpps();
	
	if ($players == false)
		echo json_encode(false);
	elseif (count($players) != 0)
	{
		$rNum = rand(0, count($players) - 1);
		$rando = $players[$rNum];
		
		while (strcasecmp($rando, $username) == 0)
		{
			$rNum = rand(0, count($players) - 1);
			$rando = $players[$rNum];
		}
		
		echo json_encode($rando);
	}
?>