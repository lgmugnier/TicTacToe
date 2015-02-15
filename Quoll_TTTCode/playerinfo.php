<?php
//playerinfo communcates with the table playerinfo in the tttdatabase
//playerinfo(username varchar(20), password varchar(30), email varchar(30), onlinestatus)
//username is the primary key.
//onlinestatus defaults to "offline" but can and should be changed to online when the user logs in.

include "playerstats.php";
include "friendslist.php";

class playerinfo
{
	private $user = "user";
	private $userpassword = "userpassword";
	private $database = "tttdatabase";
	private $server = "localhost";
	
	public $username;
	public $password;
	public $email;
	public $status;
	public $ingame;
	
	public function getUsername()
	{
		return $this -> username;
	}
	
	public function getPassword()
	{
		return $this -> password;
	}
	
	public function getEmail()
	{
		return $this -> email;
	}
	
	public function getStatus()
	{
		return $this -> status;
	}
	
	public function getinGame()
	{
		return $this -> ingame;
	}
	
	public function generateRandomOpps()
	{
		$query = "SELECT username FROM playerinfo WHERE onlinestatus = 'online' AND ingame = 0;";
		$result = $this -> queryDB($query);
		
		$array = [];
		
		for ($row = mysql_fetch_row($result) ; $row != false ; $row = mysql_fetch_row($result))
		{
			array_push($array, $row[0]);
		}
		
		return $array;
	}
	
	public function queryDB($query)
	{
		$conn = mysql_connect($this -> server,$this -> user,$this -> userpassword) or die("Unable to connect to MySQL server");
		mysql_select_db($this -> database) or die( "Unable to select database");
		
		$result = mysql_query ($query) or die("Query failed: ".mysql_error());
		
		mysql_close($conn);
		
		return $result;
	}
	
	public function putOnline()
	{
		$username = $this -> username;
		$this -> status = "online";
		$query = "UPDATE playerinfo
				  SET onlinestatus = 'online'
				  WHERE username = '$username';";
		$this -> queryDB($query);
	}
	
	public function putOffline()
	{
		$username = $this -> username;
		$this -> status = "offline";
		$query = "UPDATE playerinfo
				  SET onlinestatus = 'offline'
				  WHERE username = '$username';";
		$this -> queryDB($query);
	}
	
	public function setinGame($ingame)
	{
		$username = $this -> username;
		$this -> ingame = $ingame;
			
		$query = "UPDATE playerinfo 
			     SET ingame = '$ingame' 
				 WHERE username = '$username';";
		$this -> queryDB($query);
	}
	
	public function validLogin($playername, $pword)
	{
		$query = "SELECT *
				  FROM playerinfo
				  WHERE username = '$playername'
				  AND password = '$pword';";
		$result = $this -> queryDB($query);
		$row = mysql_fetch_row($result);
		if ($row == False)
			return false;
		else
			return true;
	}
	
	public function getPlayer($playername)
	{		
		$this -> username = $playername;
		
		$query = "SELECT password, email, onlinestatus, ingame 
			      FROM playerinfo 
				  WHERE username = '$playername';";
		$result = $this -> queryDB($query);

		$player = mysql_fetch_row ($result);
		
		if ($player != False)
		{
			$this -> password = $player [0];
			$this -> email = $player [1];
			$this -> status = $player [2];
			$this -> ingame = $player [3];
			return true;
		}
		else
			return false;
	}
	
	public function exists ($username)
	{
		$query = "SELECT *
				  FROM playerinfo
				  WHERE username = '$username';";
		$result = $this -> queryDB($query);
		if (mysql_fetch_row($result) == false)
			return false;
		else
			return true;
	}
	
	public function createPlayer($playername, $pword, $email)
	{
		$this -> username = $playername;
		
		$query = "INSERT INTO playerinfo (username, password, email) 
				  VALUES ('$playername', '$pword', '$email');";		
		$result = $this -> queryDB($query);
		
		$stats = new playerStats();
		$stats -> createPlayerStats($playername);
		
		$this -> username = $playername;
		$this -> password = $pword;
		$this -> email = $email;
		$this -> status = "offline";
	}
	
	public function deletePlayer()
	//doesn't delete from tttgame
	{
		$username = $this -> username;
		$query_frlist = "DELETE FROM friendslist 
						 WHERE username = '$username';";
		$query_stats = "DELETE FROM playerstats 
						WHERE username = '$username';";
		$query_info = "DELETE FROM playerinfo
					   WHERE username = '$username';";
		$this -> queryDB($query_frlist);
		$this -> queryDB($query_stats);
		$this -> queryDB($query_info);
	}
}
?>