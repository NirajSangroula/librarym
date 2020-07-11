<?php
	include_once("/librarym/Sql/sql.php");
	include_once("/librarym/Sql/bookFunctions.php");
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
		if(hasGet('change')){
			$query = "select d.book_name, b.name, b.email, a.borrowed_date, a.till_date, a.id from borrow a left join user b on a.user_id = b.id inner join book_location c on c.id = a.book_id left join book d on c.book_id = d.id where a.status = 'borrowed' order by borrowed_date desc";	
		}
		else
		$query = "select d.book_name, b.name, b.email, a.borrowed_date, a.till_date, a.id from borrow a left join user b on a.user_id = b.id inner join book_location c on c.id = a.book_id left join book d on c.book_id = d.id where a.status = 'borrowed' order by borrowed_date asc";
		$results = $sql->getMySqli()->query($query);
		if($sql->getError()){
			throw new Exception($sql->getError());
		}
		$rows = $results->fetch_all();
	} catch (Exception $e) {
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
	<?php require_once("/librarym/section/adminNav.php");?>
	<a class="btn btn-secondary" href="borrows.php?change">Show old first</a>
	<a class="btn btn-secondary" href="borrows.php">Show new first</a>
	<br>
	<table class="table table-info table-hover">
		<tr class="thead-dark">
			<th>Book Name</th>
			<th>User Name</th>
			<th>Email</th>
			<th>Borrowed Date</th>
			<th>Returning Date</th>
			<th>Action</th>
		</tr>
		<?php foreach($rows as $row):?>
			<tr>
				<td><?=$row[0]?></td>
				<td><?=$row[1]?></td>
				<td><?=$row[2]?></td>
				<td><?=$row[3]?></td>
				<td><?=$row[4]?></td>
				<td><a class="btn btn-secondary" href="return.php?id=<?=$row[5]?>">Return</a></td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php require_once("/librarym/section/bottomSection.php");?>
	<div class="error"><?=$error?></div>
</body>
</html>