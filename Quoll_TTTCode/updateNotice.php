<?php
	include "tttgame.php";
	
	$notice = $_GET["notice"];
	
	session_start();
	$username = $_SESSION["username"];
	
	$game = new tttgame();
	$game -> getGame($username);
	$game -> updateNotice($notice);
?>