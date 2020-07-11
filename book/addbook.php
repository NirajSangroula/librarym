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
			$rackNo = getPost('rack_no');
			$description = getPost('description');
			$fineRate = getPost('fine_rate');
			$bookId = getPost('book_id');
			$sql->insert(['book_id' => $bookId, 'rack_no' => $rackNo, 'description' => $description, 'fine_rate' => $fineRate, 'availability' => 'yes'], 'book_location');
			header("Location: /book/book.php");
		}
	} catch (Exception $e) {
		$error = $e->getMessage();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="/maincss.css">
	<title>Add book</title>
</head>
<body>
	<?php require_once("/librarym/section/topSection.php");?>
	<?php require_once("/librarym/section/adminNav.php");?>
	<form method="post">
		<input type="hidden" name="book_id" value="<?=getGet('id')?>">
		<input class="input" type="text" name="rack_no" placeholder="Rack No"><br><br>
		<textarea class="input" name="description" rows="14" placeholder="Description"></textarea><br><br>
		<input class="input" type="text" name="fine_rate" placeholder="Fine rate">
		<input type="submit" name="submit" value="Add new book">
	</form>
	<?php require_once("/librarym/section/bottomSection.php");?>
	<div class="error"><?=$error?></div>
</body>
</html>