<?php
	include "playerinfo.php";
	
	session_start();
	$username = $_SESSION['username'];
	
	$friendlist = new friendslist($username);
	$friendsarray = $friendlist -> getFriends();
	
	echo json_encode($friendsarray);

?>