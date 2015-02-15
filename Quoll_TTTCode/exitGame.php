<?php
	include "playerinfo.php";
	
	session_start();
	$username = $_SESSION["username"];
	
	$player = new playerinfo();
	$player -> getPlayer($username);
	$player -> setinGame(0);
?>