<?php
	include_once("/librarym/Sql/sql.php");
	include_once("/librarym/Sql/bookFunctions.php");
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

	try {
		$error = "";
		$sql = new Sql();
		$rows = $sql->selectFollowing(['username', 'name', 'address', 'phone_no', 'email', 'created_date'], ['id' => getGet('uid')], 'user')->fetch_all();
		if(count($rows) < 1){
			throw new Exception("User doesn't exist");
		}
		$query = "select e.book_name, a.rack_no, a.description, b.borrowed_date, b.till_date, b.status, d.finebilled, d.return_date from borrow b left join book_location a on a.id = b.book_id left join returns d on d.borrow_id = b.id left join book e on a.book_id = e.id";
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
	<?php if(!$authentication->isAdmin())
			require_once("/librarym/section/userNav.php");
		else
			require_once("/librarym/section/adminNav.php");
		?>
	<table class="table table-success">
		<tr class="thead-dark">
			<th>Username</th>
			<th>Name</th>
			<th>Address</th>
			<th>Phone No</th>
			<th>Email Address</th>
			<th>Registered On</th>
		</tr>
		<tr>
			<?php 
				foreach ($rows[0] as $key => $value) {
					echo "<td>$value</td>\n";
				}
			?>
		</tr>
	</table>
	<table class="table table-info">
		<tr class="thead-dark">
			<th>Book Name</th>
			<th>Rack No</th>
			<th>Location Description</th>
			<th>Borrowed Date</th>
			<th>To be returned on</th>
			<th>Status</th>
			<th>Fine paid</th>
			<th>Returned on</th>
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
	<div class="error"><?=$error?></div>
	<?php require_once("/librarym/section/bottomSection.php");?>
</body>
</html>