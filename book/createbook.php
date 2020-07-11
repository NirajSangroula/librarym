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
		$subjects = $sql->selectAll('subject')->fetch_all();
		foreach($subjects as $subject)
			$optionText .= ("<option value=\"$subject[0]\">$subject[1]</option>\n");
		if(isPost()){
			$bookName = getPost('book_name');
			$author = getPost('author');
			$subId = getPost('sub_id');
			$publication = getPost('publication');
			$date = new DateTime();
			$createdDate = $date->format("yy-m-d h:i:s");
			$creatorId = 0; // Note this is to be edited later;
			$sql->insert(['book_name' => $bookName, 'sub_id' => $subId, 'author' => $author, 'publication' => $publication, 'created_date' => $createdDate, 'creator_id' => $creatorId], 'book');
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
	<title>Create Book</title>
</head>
<body>
	<?php require_once("/librarym/section/topSection.php");?>
	<?php require_once("/librarym/section/adminNav.php");?>
	<form method="post">
		<input class="input" type="text" name="book_name" placeholder="Book Name">
		<input class="input" type="text" name="author" placeholder="Author">
		<select name="sub_id" class="input">
			<?= $optionText?>
		</select>
		<input class="input" type="text" name="publication" placeholder="Publication">
		<input class="input" type="submit" name="submit" value="Create a Book">
	</form>
	<?php require_once("/librarym/section/bottomSection.php");?>
	<div class="error"><?=$error?></div>
</body>
</html>