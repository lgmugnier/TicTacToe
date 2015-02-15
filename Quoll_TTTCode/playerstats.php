<?php
//playerstats communicates with the playerstats table in the tttdatabase
//playerstats(username varchar(20), rank int, standing int, wins int, losses int, ties int, total int)
//username is a foreign key that references playerinfo(username) and all ints default to 0

class playerstats
{
	private $user="user";
	private $password="userpassword";
	private $database="tttdatabase";
	private $server="localhost";
	
	public $username;
	public $rank;
	public $standing;
	public $wins;
	public $losses;
	public $ties;
	public $total;
	
	
	public function getUsername()
	{
		return $this -> username;
	}
	
	public function getStanding()
	{
		return $this -> standing;
	}
	
	public function getRank()
	{
		return $this -> rank;
	}
	
	public function getWins()
	{
		return $this -> wins;
	}
	
	public function getLosses()
	{
		return $this -> losses;
	}
	
	public function getTies()
	{
		return $this -> ties;
	}
	
	public function getTotal()
	{
		return $this -> total;
	}
	
	public function addWin()
	{
		$username = $this -> username;
		$getQuery = "SELECT wins, rank, total 
				     FROM playerstats 
					 WHERE username = '$username';";
		$result = $this -> queryDB($getQuery);
		$row = mysql_fetch_row($result);
		$w = $row [0];
		$r = $row [1];
		$t = $row [2];
		$this -> wins = $w + 1;
		$this -> rank = $r + 3;
		$this -> total = $t + 1;
		$this -> commit();
	}
	
	public function addLoss()
	{
		$username = $this -> username;
		$getQuery = "SELECT losses, rank, total 
				     FROM playerstats 
					 WHERE username = '$username';";
		$result = $this -> queryDB($getQuery);
		$row = mysql_fetch_row($result);
		$l = $row [0];
		$r = $row [1];
		$t = $row [2];
		$this -> losses = $l + 1;
		$this -> rank = $r - 1;
		$this -> total = $t + 1;
		$this -> commit();
	}
	
	public function addTie()
	{
		$username = $this -> username;
		$getQuery = "SELECT wins, rank, total 
				     FROM playerstats 
					 WHERE username = '$username';";
		$result = $this -> queryDB($getQuery);
		$result = mysql_fetch_row($result);
		$ti = $result [0];
		$r = $result [1];
		$t = $result [2];
		$this -> ties = $ti + 1;
		$this -> rank = $r + 1;
		$this -> total = $t + 1;
		$this -> commit();
	}
	
	public function commit()
	{	
		$username = $this -> username;
		$rank = $this -> rank;
		$standing = $this -> standing;
		$wins = $this -> wins;
		$losses = $this -> losses;
		$ties = $this -> ties;
		$total = $this -> total;
		$setQuery = "UPDATE playerstats 
			         SET standing = '$standing', total = '$total', rank = '$rank', 
					 wins = '$wins', losses = '$losses', ties = '$ties'
					 WHERE username = '$username';";
		$this -> queryDB($setQuery);
	}

	public function createPlayerStats($username)
	{
		$query = "INSERT INTO playerstats (username) 
			      VALUES ('$username');";
		$result = $this -> queryDB($query);
	}
	
	public function getPlayerStats($username)
	{
		$this -> username = $username;
		
		$query = "SELECT rank, standing, wins, losses, ties, total 
				  FROM playerstats 
				  WHERE username = '$username';";
		$result = $this -> queryDB($query);
		$playerstats = mysql_fetch_row($result);
		
		$this -> rank = $playerstats[0];
		$this -> standing = $playerstats[1];
		$this -> wins = $playerstats[2];
		$this -> losses = $playerstats[3];
		$this -> ties = $playerstats[4];
		$this -> total = $playerstats[5];
	}
	
	public function getTopTen()
	{
		$query = "SELECT username, rank 
				  FROM playerstats 
				  WHERE standing >= 1 
				  AND standing <=10
				  ORDER BY standing;";
		$result = $this -> queryDB($query);
		$topten = [];
		for ($row = mysql_fetch_row($result); $row != false; $row = mysql_fetch_row($result))
			array_push($topten, $row [0]); 
		return $topten;
	}
	
	public function queryDB($query)
	{
		$conn = mysql_connect($this -> server,$this -> user,$this -> password) or die("Unable to connect to MySQL server");
		mysql_select_db($this -> database) or die( "Unable to select database");
		
		$result = mysql_query ($query) or die("Query failed: ".mysql_error());
		return $result;
		
		mysql_close($conn);
	}
	
	public function setStanding($username, $standing)
	{
		$query = "UPDATE playerstats 
			      SET standing = '$standing'
				  WHERE username = '$username';";
		$this -> queryDB($query);
	}
	
	public function updateStandings()
	{
		$usernames = [];
		$query = "SELECT username
				  FROM playerstats
				  ORDER BY rank DESC, wins DESC, losses DESC, ties DESC, total DESC, username;";
		$result = $this -> queryDB($query);
		for ($row = mysql_fetch_row($result); $row != false; $row = mysql_fetch_row($result))
			array_push($usernames, $row [0]);
		
		for ($c = 0; $c < count($usernames); $c ++)
			$this -> setStanding($usernames[$c], $c + 1);
		
	}
	

}
?>