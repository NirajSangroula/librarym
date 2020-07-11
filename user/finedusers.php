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
		$error = "";
		$sql = new Sql();
		$query = "select f.name, e.book_name, d.finebilled, b.borrowed_date, d.return_date, b.till_date, a.rack_no, a.description from returns d left join borrow b on d.borrow_id = b.id left join book_location a on a.id = b.book_id left join book e on a.book_id = e.id left join user f on f.id = b.user_id where d.finebilled > 0 order by d.finebilled desc";
		$borrowedReturnedResult = $sql->getMySqli()->query($query);
		if($sql->getError()){
			throw new Exception($sql->getError(), 1);
			
		}
		$borrowedReturns = $borrowedReturnedResult->fetch_all();
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
	<table class="table table-info table-hover">
		<tr class="thead-dark">
			<th>Name</th>
			<th>Book Name</th>
			<th>Fined Rs</th>
			<th>Borrowed Date</th>
			<th>Returned Date</th>
			<th>To be returned date</th>
			<th>Rack no</th>
			<th>Description</th>
		</tr>
		<?php foreach($borrowedReturns as $i){
			echo "<tr>";
			foreach($i as $item){
				echo "<td>$item</td>\n";
			}
			echo "</tr>";
		}
		?>
	</table>
	<?php require_once("/librarym/section/bottomSection.php");?>
	<div class="error"><?=$error?></div>
</body>
</html>