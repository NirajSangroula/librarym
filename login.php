<?php
	session_start();
	include_once("/librarym/Sql/sql.php");
	include_once("/librarym/Sql/bookFunctions.php");
	include_once("/librarym/Helpers/pageFunctions.php");
	include_once("/librarym/Helpers/authentication.php");
	$error = '';
	try{
		$sql = new Sql();
		$authentication = Authentication::getInstance();
		if($authentication->isLoggedIn()){
				if($authentication->isAdmin()){
					header("location: admin.php");
				}
				else
					header("location: index.php");
			}
		if(isPost()){
			$username = getPost('username');
			$password = getPost('password');
			if($authentication->authenticate(['username' => $username, 'password' => crypt($password, 'sastraastra')])){
				if($authentication->isAdmin()){
					header("location: admin.php");
				}
				else
					header("location: index.php");
			}
			else{
				throw new Exception("Invalid credentials entered!");
			}
		}
	}
	catch(Exception $e){
		$error = $e->getMessage();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="/maincss.css">
	<title></title>
</head>
<body>
	<?php require_once("/librarym/section/topSection.php");?>
		<div class="centeredForm">
			<form method="post">
				<input type="text" name="username" class="input">
				<input type="password" name="password" class="input">
				<input type="submit" name="submit" value="Log in">
			</form>
		</div>
		<div class="error"><?=$error?></div>
	<?php require_once("/librarym/section/bottomSection.php");?>
</body>
</html>
