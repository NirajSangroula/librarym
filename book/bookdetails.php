<?php
	include_once("/librarym/Sql/sql.php");
	include_once("/librarym/Helpers/pageFunctions.php");
	require_once("/librarym/Helpers/authentication.php");
	session_start();
	$authentication = Authentication::getInstance();
	$sql = new Sql();
	$authentication->sql = $sql;
	if(!$authentication->isLoggedIn()){
		sendToLoginPage();
	}
	$user = $authentication->getIdentity();
	if(!$authentication->isAdmin())
	if($user[0] != getGet('id'))
		throw new Exception("Invalid request, Please reload the page and try again");
	try {
		$optionText = "";
		$error = "";
		$sql = new Sql();
		$query = "select a.id, a.book_name, a.author, a.publication, a.created_date, b.name, c.description from book a
				 left join user b on a.creator_id = b.id left join
				 subject c on a.sub_id = c.id
				  where a.id = ".getGet('id');
		$results = $sql->getMySqli()->query($query)->fetch_all();
		$locationQuery = "select a.rack_no, a.description, a.availability, a.fine_rate from book_location
			where book_id = " . getGet('id');
		$locations = $sql->select(['book_id' => getGet('id')], 'book_location')->fetch_all();
		if(count($results) < 1)
			header("location: /book/book.php");

	} catch (Exception $e) {
		$error = $e->getMessage();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="/maincss.css">
	<title>Book Details</title>
</head>
<body>
	<?php require_once("/librarym/section/topSection.php");?>
	<?php if(!$authentication->isAdmin())
			require_once("/librarym/section/userNav.php");
		else
			require_once("/librarym/section/adminNav.php");
		?>
<table class="table table-success">
	<tr class="thead-dark">
		<th>Id</th>
		<th>Name</th>
		<th>Author</th>
		<th>Publication</th>
		<th>Created Date</th>
		<th>Created By</th>
		<th>Subject</th>
	</tr>
	<tr>
		<?php foreach($results[0] as $value) :?>
			<td><?=$value?></td>
		<?php endforeach;?>
	</tr>
</table>
<h2>Available books in library</h2>
<table class="table table-success">
	<tr class="thead-dark">
		<th>Rack No</th>
		<th>Description</th>
		<th>Fine Rate</th>
		<th>Availability Now</th>
	</tr>
	<?php foreach($locations as $location): ?>
		<tr>
			<td><?=$location[1]?></td>
			<td><?=$location[2]?></td>
			<td><?=$location[5]?></td>
			<td><?=$location[4]?></td>
		</tr>
	<?php endforeach;?>
</table>
<div><?=$error?></div>
</body>
</html>