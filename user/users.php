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
		$optionText = "";
		$error = "";
		$sql = new Sql();
		$rows = $sql->selectAll('user')->fetch_all();
		if(hasGet('search') && getGet('search')){
			$search = getGet('search');
			$query = "select * from user where username like '$search%' or name like '%$search' or phone_no like '%$search' or email like '%$search'";
			$rows = $sql->getMySqli()->query($query)->fetch_all();
		}

	} catch (Exception $e) {
		$error = $e->getMessage();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="/maincss.css">
	<title>Users</title>
</head>
<body>
	<?php require_once("/librarym/section/topSection.php");?>
	<?php require_once("/librarym/section/adminNav.php");?>
	<a class="btn btn-secondary" href="/user/createuser.php">Create new user</a>
	<form>
		<input class="searchInput" type="text" name="search" placeholder="Search username">
		<input type="submit" name="submit">
	</form>
	<table class="table table-info">
		<tr class="thead-dark">
			<th>Name</th>
			<th>Username</th>
			<th>Address</th>
			<th>Phone No</th>
			<th>Email</th>
			<th>Action</th>
		</tr>
		<?php foreach($rows as $key=>$value):?>
		<tr>
			<td><?=$value[3]?></td>
			<td><?=$value[1]?></td>
			<td><?=$value[4]?></td>
			<td><?=$value[5]?></td>
			<td><?=$value[6]?></td>
			<td><a class="btn btn-secondary" href="/user/givebook.php?uid=<?=$value[0]?>">Borrow book</a> <a href="/user/userDetails.php?uid=<?=$value[0]?>" class="btn btn-secondary">View user details</a></td>
		</tr>
		<?php endforeach;?>
	</table>
	<?php require_once("/librarym/section/bottomSection.php");?>
	<div class="error"><?=$error?></div>
</body>
</html>