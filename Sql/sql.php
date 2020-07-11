<?php
include "/librarym/Helpers/sqlStringCreator.php";

class Sql{
	private $mysqli;
	private $stringCreator;
	public function __construct(){
		$this->mysqli = new \mysqli("testhost.com", "niraj", "root", "library");
		if($this->mysqli->connect_error){
			throw new Exception($this->mysqli->connect_error);
		}
		$this->stringCreator = new SqlStringCreator();
	}
	public function getMySqli(){
		return $this->mysqli;
	}
	public function getError(){
		return $this->mysqli->error;
	}
	public function insert(array $data, $tableName){
		$string = $this->stringCreator->createDataString($data);
		$sql = "insert into $tableName( {$string[0]} ) values( {$string[1]} )";
		$result = $this->mysqli->query($sql);
		if($this->mysqli->error){
			throw new Exception($this->mysqli->error);
		}
		return $result;
	}
	public function update(array $data, array $identifier, $tableName){
		$string = $this->stringCreator->createUpdateString($data);
		$identifierString = $this->stringCreator->createIdentifierString($identifier);
		$sql = "update $tableName set $string where $identifierString";
		$result = $this->mysqli->query($sql);
		if($this->mysqli->error){
			throw new Exception($this->mysqli->error);
		}
		return $result;
	}
	public function delete(array $identifier, $tableName){
		$string = $this->stringCreator->createIdentifierString($identifier);
		$sql = "delete from $tableName where $string";
		$result = $this->mysqli->query($sql);
		if($this->mysqli->error){
			throw new Exception($this->mysqli->error);
		}
		return $result;
	}
	public function selectAll($tableName){
		$sql = "select * from $tableName";
		$result = $this->mysqli->query($sql);
		if($this->mysqli->error){
			throw new Exception($this->mysqli->error);
		}
		return $result;
	}
	public function select(array $identifier, $tableName){
		$string = $this->stringCreator->createIdentifierString($identifier);
		$sql = "select * from $tableName where $string";
		$result = $this->mysqli->query($sql);
		if($this->mysqli->error){
			throw new Exception($this->mysqli->error);
		}
		return $result;
	}
	public function selectFollowing(array $selection, array $identifier, $tableName){
		$string = "";
		$i = 1;
		foreach ($selection as $value) {
			if($i++ < count($selection))
				$string .= ($value.', ');
			else
				$string .= $value;
		}
		$iString = $this->stringCreator->createIdentifierString($identifier);
		$sql = "select $string from $tableName where $iString";
		$result = $this->mysqli->query($sql);
		if($this->mysqli->error){
			throw new Exception($this->mysqli->error);
		}
		return $result;
	}

	public function selectFollowingInOrder(array $selection, array $identifier, $tableName){
		$string = "";
		$i = 1;
		foreach ($selection as $value) {
			if($i++ < count($selection))
				$string .= ($value.', ');
			else
				$string .= $value;
		}
		$iString = $this->stringCreator->createIdentifierString($identifier);
		$sql = "select $string from $tableName where $iString $orderString";
		$result = $this->mysqli->query($sql);
		if($this->mysqli->error){
			throw new Exception($this->mysqli->error);
		}
		return $result;
	}

	public function searchFollowing(array $selection, array $identifier, $tableName){
		$string = "";
		$i = 1;
		foreach ($selection as $value) {
			if($i++ < count($selection))
				$string .= ($value.', ');
			else
				$string .= $value;
		}
		$iString = $this->stringCreator->createLikeString($identifier);
		$sql = "select $string from $tableName where $iString";
		$result = $this->mysqli->query($sql);
		if($this->mysqli->error){
			throw new Exception($this->mysqli->error);
		}
		return $result;
	}

	public function selectWithLimit(array $selection, array $identifier, $tableName, $pageNo = 1, $no = 20){
		$string = "";
		$i = 1;
		foreach ($selection as $value) {
			if($i++ < count($selection))
				$string .= ($value.', ');
			else
				$string .= $value;
		}
		$iString = $this->stringCreator->createIdentifierString($identifier);
		$sql = "select $string from $tableName where $iString limit " . ($pageNo - 1) * $no . ", $no";
		$result = $this->mysqli->query($sql);
		if($this->mysqli->error){
			throw new Exception($this->mysqli->error);
		}
		return $result;

	}
}
