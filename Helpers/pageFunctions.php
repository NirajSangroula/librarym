<?php
function isPost(){
	return $_SERVER['REQUEST_METHOD'] == 'POST';
}
function isGet(){
	return $_SERVER['REQUEST_METHOD'] == 'GET';
}
function getPost($name){
	return $_POST[$name];
}
function getGet($name){
	return $_GET[$name];
}
function hasGet($name){
	return isset($_GET[$name]);
}
function hasPost($name){
	return isset($_POST[$name]);
}
function sendToHomepage(){
	header("location: index.php");
}
function sendToLoginPage(){
	header("location: login.php");
}
function setSession($key, $value){
	$_SESSION[$key] = $value;
}
function getSession($key){
	return $_SESSION[$key];
}
function addToCart($data){
	if(!isInSession($data))
		$_SESSION['cart'][] = $data;
}
function removeFromCart($id){
	foreach($_SESSION['cart'] as $value){
		if($value == $id){
			unset($_SESSION[$key]);
		}
	}
}
function isInSession($v){
		if(!isset($_SESSION['cart']))
			return false;
		foreach($_SESSION['cart'] as $value){
			if($value == $v)
				return true;
		}
		return false;
}