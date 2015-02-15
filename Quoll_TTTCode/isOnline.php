<?php
	include "playerinfo.php";

	$username = $_GET["friend"];
	
	$player = new playerinfo();
	$player -> getPlayer($username);
	$status = $player -> getStatus();
	if ($status == "offline")
		echo json_encode(false);
	else
		echo json_encode(true);
	
?>