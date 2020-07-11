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
	$error = "";
	$sql = new Sql();
	$rows = $sql->selectAll('subject')->fetch_all();
	if(isPost()){
		try {
			$subject = getPost('description');
			$creatorId = 0; // Note this is to be edited later;
			$sql->insert(['description' => $subject, 'creator_id' => $creatorId], 'subject');
			header("Location: subjects.php");
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
	if(hasGet('id') && getGet('id')){
		$sql->delete(['id' => getGet('id')], 'subject');
		header("Location: subjects.php");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="/maincss.css">
	<title>Subjects</title>
</head>
<body>
	<?php require_once("/librarym/section/topSection.php");?>
	<?php require_once("/librarym/section/adminNav.php");?>
	<form method="post">
		<input class="input" type="text" name="description">
		<input type="submit" name="submit" value="Create a subject">
	</form>		
	<?php foreach($rows as $key=>$value): ?>
		<div style="padding: 8px; font-size: 1.2rem;"><span class="definition"><?=$key+1?>) </span><span class="content"><?=$value[1]?></span> <a class="btn btn-secondary" href="/book/subjects.php?id=<?=$value[0]?>">Delete Subject</a></div>
	<?php endforeach;?>
	<?php require_once("/librarym/section/bottomSection.php");?>
	<div class="error"><?=$error?></div>
</body>
</html>