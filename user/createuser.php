<?php
	include_once("/librarym/Sql/sql.php");
	include_once("/librarym/Helpers/pageFunctions.php");
	require_once("/librarym/Helpers/authentication.php");
	session_start();
	$authentication = Authentication::getInstance();
	$sql = new Sql();
	$authentication->sql = $sql;
	if(!($authentication->isLoggedIn() && $authentication->isAdmin())){
		sendToLoginPage();
	}
	try {
		$error = "";
		$sql = new Sql();
		if(isPost()){
			$name = getPost('name');
			$username = getPost('username');
			$password = getPost('password');
			$address = getPost('address');
			$phoneNo = getPost('phone_no');
			$email = getPost('email');
			$date = new DateTime();
			$createdDate = $date->format("yy-m-d h:i:s");
			$creatorId = 0; // Note this is to be edited later;
			$sql->insert(['username' => $username, 'name' => $name, 'password' => crypt($password, 'sastraastra'), 'address' => $address, 'created_date' => $createdDate, 'phone_no' => $phoneNo, 'address' => $address, 'email' => $email, 'type' => 'user', 'authID' => crypt(random_int(0, 1000), 'sastraastra')], 'user');
			header("Location: /user/users.php");
		}
	} catch (Exception $e) {
		$error = $e->getMessage();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="/maincss.css">
	<title>Create new user account</title>
</head>
<body>
	<?php require_once("/librarym/section/topSection.php");?>
	<?php require_once("/librarym/section/adminNav.php");?>
	<form method="post">
		<input class="input" type="text" name="name" placeholder="Name">
		<input class="input" type="text" name="username" placeholder="Username">
		<input class="input" type="password" name="password" placeholder="Password">
		<input class="input" type="text" name="address" placeholder="Address">
		<input class="input" type="text" name="phone_no" placeholder="Phone no">
		<input class="input" type="email" name="email" placeholder="Email Address">
		<input type="submit" name="submit" value="Create User Account">
	</form>
	<?php require_once("/librarym/section/bottomSection.php");?>
	<div class="error"><?=$error?></div>
</body>
</html>