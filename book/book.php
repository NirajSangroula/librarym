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
		$rows = $sql->selectAll('book')->fetch_all();
		$optionText = "";
		$subjects = $sql->selectAll('subject')->fetch_all();
		foreach($subjects as $subject)
			$optionText .= ("<option value=\"$subject[0]\">$subject[1]</option>\n");
		if(hasGet('id') && getGet('id')){
			$bookLocations = $sql->selectFollowing(['id'], ['book_id' => getGet('id')], 'book_location')->fetch_all();
			foreach($bookLocations as $bookLocation){
				$sql->delete(['id' => $bookLocation[0]], 'book_location');
			}
			$sql->delete(['id' => getGet('id')], 'book');
			header("Location: book.php");
		}
		if(hasGet('search') && getGet('search')){
			$search = getGet('search');
			$subId = getGet('subject');
			$query = "select * from book where (book_name like '%$search%' or author like '%$search%' or publication like '%$search%')";
			$rows = $sql->getMySqli()->query($query)->fetch_all();
			$error.=$sql->getError();
		}
		else if(hasGet('subject') && getGet('subject')){
			$subId = getGet('subject');
			$query = "select * from book where sub_id like '$subId'";
			$rows = $sql->getMySqli()->query($query)->fetch_all();
			$error.=$sql->getError();	
		}
	} catch (Exception $e) {
		$error = $e->getMessage();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="/maincss.css">
	<title>Library Books</title>
</head>
<body>
	<?php require_once("/librarym/section/topSection.php");?>
	<?php require_once("/librarym/section/adminNav.php");?>
	<div>
		<a class="btn btn-secondary" href="/book/createbook.php">Create a book</a>
		<form method="get">
			<input class="searchInput" type="text" name="search">
			<select name="subject" class="searchInput">
				<option value="">Subject</option>
				<?= $optionText?>
			</select>
			<input type="submit" name="submit" value="search">
		</form>
	</div>
	<?php foreach($rows as $key=>$value): ?>
		<div class="inlinedc"><span class="definition"><?=$key+1?>)</span><span class="definition"><?=$value[1]?></span><a class="btn btn-secondary" href="/book/book.php?id=<?=$value[0]?>">Delete Book</a>
			<a class="btn btn-secondary" href="/book/addbook.php?id=<?=$value[0]?>">Add Book</a>
			<a class="btn btn-secondary" href="/book/bookdetails.php?id=<?=$value[0]?>">Book Details</a></div>
			
	<?php endforeach;?>
	<div class="error"><?=$error?></div>
	<?php require_once("/librarym/section/bottomSection.php");?>
</body>
</html>