<?php
//The friendslist class refers to the friendslist table in the tttdatabase
//friendslist (username varchar(20), friend varchar(20))
//both username and friend are foreign keys that refer to playerinfo(username)
//A user may have a friend who is not friends with them, the player in the 
//	username field will be friends with the player in the friend field but 
//	that friend player may not be friends with the original user

class friendslist
{
	private $user="user";
	private $userpassword="userpassword";
	private $database="tttdatabase";
	private $server="localhost";
	
	public $username;
	public $friends;
	
	
	public function getUsername()
	{
		return $this -> username;
	}
	
	public function getFriends()
	{
		return $this -> friends;
	}
	
	public function __construct($username)
	{
		$this -> username = $username;
		$this -> friends = [];
		
		$query = "SELECT friend 
				  FROM friendslist 
				  WHERE username = '$username';";
		$result = $this -> queryDB($query);
		for ($row = mysql_fetch_row($result); $row != False; $row = mysql_fetch_row($result))
			array_push ($this -> friends, $row [0]);
	}
	
	public function updateFriendsList()
	{		
		$username = $this -> username;
		$query = "SELECT DISTINCT friend 
			      FROM friendslist 
				  WHERE username = '$username';";
		$result = $this -> queryDB($query);
		
		$friends = mysql_fetch_row($result);
	}
	
	public function addFriend($friendName)
	{
		$username = $this -> username;
		$query = "INSERT INTO friendslist 
				  VALUES ('$username', '$friendName');";
		$this -> queryDB($query);
		$this -> updateFriendsList();
	}
	 
	public function removeFriend($friendName)
	{		
		$username = $this -> username;
		$query = "DELETE FROM friendslist 
				  WHERE username = '$username' 
				  AND friend = '$friendName';";
		$result = $this -> queryDB($query);
		$this -> updateFriendsList();
	}
	
	public function isFriend($friend)
	{
		$username = $this -> username;
		$query = "SELECT username
				  FROM friendslist
				  WHERE username = '$username'
				  AND friend = '$friend';";
		$result = $this -> queryDB($query);
		$row = mysql_fetch_row($result);
		if ($row[0] == false)
			return false;
		else
			return true;
	}
	
	public function queryDB($query)
	{
		$conn = mysql_connect($this -> server,$this -> user,$this -> userpassword) or die("Unable to connect to MySQL server");
		mysql_select_db($this -> database) or die( "Unable to select database");
		
		$result = mysql_query ($query) or die("Query failed: ".mysql_error());
		
		mysql_close($conn);
		
		return $result;
	}
}
?>