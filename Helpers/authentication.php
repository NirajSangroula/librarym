<?php
class Authentication{
	public $status = 0;
	private $identity;
	private static $instance;
	public $sql;
	public function __construct(){
		$this->sql = new Sql();
	}
	public static function getInstance(){
		if(self::$instance)
			return self::$instance;
		else{
			self::$instance = new Authentication();
			return self::$instance;
		}

	}
	public function authenticate(array $identity){
		$results = $this->sql->selectFollowing(['id', 'username', 'password', 'authID', 'type'], $identity, 'user');
		if($results){
			$results = $results->fetch_all();
			if(count($results) > 0){
				$status = 1;
				$authID = crypt(random_int(0, 1000), 'sastraastra');
				$this->sql->update(['authID' => $authID], ['id' => $results[0][0]], 'user');
				$results[0][3] = $authID;
				$this->storageOperations($results[0]);
				return true;
			}
		}
		else{
			return false;
		}
	}
	public function selfAuthenticate(){
		$results = $this->sql->selectFollowing(['id', 'username', 'password', 'authID', 'type'], ['id' => $this->getID(), 'authID' => $this->getAuthID()], 'user');
		if($results){
			$results = $results->fetch_all();
			if(count($results) > 0){
				$status = 1;
				$this->storageOperations($results[0]);
				return true;
			}
		}
		else{
			$this->emptyStorage();
			return false;
		}
	}
	public function logOut(){
		$this->emptyStorage();
	}
	public function isLoggedIn(){
		if(isset($_SESSION['id'])){
			$results = $this->sql->selectFollowing(['id', 'authID'], ['id' => $_SESSION['id'], 'authID' => $this->getAuthID()], 'user')->fetch_all();
			if(count($results) > 0){
				if($_SESSION['authID'] == $results[0][1]){
					$status = 1;
					return true;
				}
			}
		}
		else{
			if(isset($_COOKIE['id']) && isset($_COOKIE['authID'])){
				$results = $this->sql->selectFollowing(['id', 'authID'], ['id' => $_COOKIE['id'], 'authID' => $this->getAuthID()], 'user')->fetch_all();
				if(count($results) > 0){
					if($_COOKIE['authID'] == $results[0][1]){
						$status = 1;
						return true;
					}
				}
			}
			else
				$this->emptyStorage();
		}
		return false;
	}
	public function storageOperations(array $results){
		$_SESSION['id'] =  $results[0];
		$_SESSION['authID'] =  $results[3];
		setcookie('id', $results[0], time() + 86400 * 60);
		setcookie('authID', $results[3], time() + 86400 * 60);
		$this->identity = $this->sql->selectFollowing(['*'], ['id' => $results[0]], 'user')->fetch_all()[0];
	}
	public function emptyStorage(){
		if(isset($_SESSION['id']))
			unset($_SESSION['id']);
		if(isset($_SESSION['authID']))
			unset($_SESSION['authID']);
		setcookie('id', '', time() - 1234);
	}
	private function getID(){
		return isset($_COOKIE['id']) ? $_COOKIE['id'] : (isset($_SESSION['id']) ? $_SESSION['id'] : false);
	}
	private function getAuthID(){
		return isset($_COOKIE['authID']) ? $_COOKIE['authID'] : (isset($_SESSION['authID']) ? $_SESSION['authID'] : false);
	}

	public function getIdentity(){
		if($this->identity){
			return $this->identity;
		}
		else{
			if($this->isLoggedIn()){
				$identity = $this->sql->selectFollowing(['*'], ['id' => $this->getID()], 'user')->fetch_all();
				if(count($identity) > 0){
					$this->identity = $identity[0];
					return $this->identity;
				}
			}
		}
	}
	public function isAdmin(){
		return $this->getIdentity()[8] == 'admin';
	}
}
?>
