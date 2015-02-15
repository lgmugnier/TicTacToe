<?php
	include "friendslist.php";
	
	session_start();
	$username = $_SESSION["username"];
	
	$friend = $_GET["friend"];
	$flist = new friendslist($username);
	$flist -> removeFriend($friend);
?>