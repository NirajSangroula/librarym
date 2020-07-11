<?php
	include_once("/librarym/Sql/sql.php");
	include_once("/librarym/Helpers/pageFunctions.php");
	include_once("/librarym/Helpers/authentication.php");
	session_start();
	$authentication = Authentication::getInstance();
	$sql = new Sql();
	$authentication->sql = $sql;
	if(!$authentication->isLoggedIn() || $authentication->isAdmin()){
		sendToLoginPage();
	}
	$user = $authentication->getIdentity();

	$error = "";
	$date = new DateTime($user[7]);
	$createdDate = $date->format("g:ia\n l jS F Y");
	if(hasGet('action') && (getGet('action') == 'logout')){
		$authentication->logOut();
		header('location: login.php');
	}
	?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="/maincss.css">
	<title>User side</title>
</head>
<body>
	<?php require_once("/librarym/section/topSection.php");?>
	<?php require_once("/librarym/section/userNav.php");?>
	<div class="inlinedc"><span class="definition">Name:</span><span class="content"><?=$user[3]?></span></div>
	<div class="inlinedc"><span class="definition">Email: </span><span class="content"><?=$user[6]?></span></div>
	<div class="inlinedc"><span class="definition">Address: </span><span class="content"><?=$user[4]?></span></div>
	<div class="inlinedc"><span class="definition">Phone No: </span><span class="content"><?=$user[5]?></span></div>
	<div class="inlinedc"><span class="definition">Username: </span><span class="content"><?=$user[1]?></span></div>
	<div class="inlinedc"><span class="definition">Created on: </span><span class="content"><?=$createdDate?></span></div>
	<div class="error"><?=$error?></div>
	<?php require_once("/librarym/section/bottomSection.php");?>
</body>
</html>
<?php
	include_once("/librarym/Sql/sql.php");
	include_once("/librarym/Helpers/pageFunctions.php");
	include_once("/librarym/Helpers/authentication.php");
	session_start();
	$authentication = Authentication::getInstance();
	$sql = new Sql();
	$authentication->sql = $sql;
	if(!$authentication->isLoggedIn() || $authentication->isAdmin()){
		sendToLoginPage();
	}
	$user = $authentication->getIdentity();

	$error = "";
	$date = new DateTime($user[7]);
	$createdDate = $date->format("g:ia\n l jS F Y");
	if(hasGet('action') && (getGet('action') == 'logout')){
		$authentication->logOut();
		header('location: login.php');
	}
	?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="/maincss.css">
	<title>User side</title>
</head>
<body>
	<?php require_once("/librarym/section/topSection.php");?>
	<?php require_once("/librarym/section/userNav.php");?>
	<div class="inlinedc"><span class="definition">Name:</span><span class="content"><?=$user[3]?></span></div>
	<div class="inlinedc"><span class="definition">Email: </span><span class="content"><?=$user[6]?></span></div>
	<div class="inlinedc"><span class="definition">Address: </span><span class="content"><?=$user[4]?></span></div>
	<div class="inlinedc"><span class="definition">Phone No: </span><span class="content"><?=$user[5]?></span></div>
	<div class="inlinedc"><span class="definition">Username: </span><span class="content"><?=$user[1]?></span></div>
	<div class="inlinedc"><span class="definition">Created on: </span><span class="content"><?=$createdDate?></span></div>
	<div class="error"><?=$error?></div>
	<?php require_once("/librarym/section/bottomSection.php");?>
</body>
</html>
