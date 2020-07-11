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
	$error = "";
	try {
		if(hasGet('id') && getGet('id')){
			$id = getGet('id');
			$sql = new Sql();
			$query = "select d.book_name, b.name, b.email, a.borrowed_date, a.till_date, c.fine_rate, c.id from borrow a left join user b on a.user_id = b.id inner join book_location c on c.id = a.book_id left join book d on c.book_id = d.id where a.id = $id";
			$results = $sql->getMySqli()->query($query);
			if($sql->getError()){
				throw new Exception($sql->getError());
			}
			$rows = $results->fetch_all();
			if(count($rows) != 1){
				throw new Exception("Invalid identifier.", 1);
			}else{
				$row = $rows[0];
				$currentDate = new DateTime();
				$tillDate = new DateTime($row[4]);
				$secondsLate = $currentDate->getTimeStamp() - $tillDate->getTimeStamp();
				if($secondsLate > 0){
					$status = 'late';
					$fine = ($secondsLate/86400) * $row[5];
				}
				else{
					$status = 'early';
				}
			}
		}
		if(isPost()){
			if(getPost('submit') === 'Confirm return'){
				$sql->insert(['borrow_id' => getPost('borrow_id'), 'finebilled' => getPost('fine')],'returns');
				$sql->update(['availability' => 'yes'], ['id' => getPost('book_location_id')],'book_location');
				$sql->update(['status' => 'returned'], ['id' => getPost('borrow_id')], 'borrow');
				header("location: borrows.php");
			}
		}
	} catch (Exception $e) {
		$error = $e->getMessage();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="/maincss.css">
	<title>Return Book</title>
</head>
<body>
	<?php require_once("/librarym/section/topSection.php");?>
	<?php require_once("/librarym/section/adminNav.php");?>
	<div style="font-size: 3rem">
		<div class="content">Note that, you are going to return</div>
		<div class="definition"><?=$row[0]?></div>
		<div class="content">that was borrowed on <?=$row[3]?> by <?=$row[1]?></div>
		<div class="definition">The book was returned <?=($status == 'early')?'In time, so no fine is required':"Late, and deadline was $row[4] so fine is Rs". $fine.'.'?></div>	
		</div>	
	<form method="post">
		<input type="hidden" name="fine" value="<?=$fine?>">
		<input type="hidden" name="book_location_id" value="<?=$row[6]?>">
		<input type="hidden" name="borrow_id" value="<?=$id?>">
		<input type="submit" name="submit" value="Confirm return">
	</form>
	<?php require_once("/librarym/section/bottomSection.php");?>
	<div class="error"><?=$error?></div>
</body>
</html>