<?php
	include_once("/librarym/Sql/sql.php");
	include_once("/librarym/Helpers/pageFunctions.php");
	require_once("/librarym/Helpers/authentication.php");
	
	try {
		session_start();
		$authentication = Authentication::getInstance();
		$sql = new Sql();
		$authentication->sql = $sql;
		if(!$authentication->isLoggedIn() || $authentication->isAdmin()){
			sendToLoginPage();
		}
		$user = $authentication->getIdentity();
		$optionText = "";
		$error = "";
		$rows = $sql->selectAll('book')->fetch_all();
		$subjects = $sql->selectAll('subject')->fetch_all();
		foreach($subjects as $subject)
				$optionText .= ("<option value=\"$subject[0]\">$subject[1]</option>\n");
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
	<?php require_once("/librarym/section/userNav.php");?>
	<div class="searchWrapper">
		<form method="get">
			<input type="text" name="search" class="searchInput">
			<select name="subject" class="searchInput">
				<option value="">Subject</option>
				<?= $optionText?>
			</select>
			<input type="submit" name="submit" value="search">
		</form>
	</div>
	<?php foreach($rows as $key=>$value): ?>
		<div class="inlinedc"><span class="definition"><?=$key+1?>)</span> <span class="definition"><?=$value[1]?></span>
			<a class="btn btn-secondary btn-sm" href="/book/bookdetails.php?id=<?=$value[0]?>">Book Details</a></div>
	<?php endforeach;?>
	<div class="error"><?=$error?></div>
	<?php require_once("/librarym/section/bottomSection.php");?>
</body>
</html>