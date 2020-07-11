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
	// $_SESSION['cart'] = [];

	try {
		$optionText = "";
		$error = "";
		$sql = new Sql();
		$rows = $sql->selectAll('book')->fetch_all();
		$optionText = "";
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
		if(hasGet('uid') && getGet('uid')){
			$_SESSION['target_user_id'] = getGet('uid');
		}

		if(isPost()){
			foreach($_POST['book'] as $key=>$value){
				addToCart($value);
			}

		}
		$cartData = [];
		if(isset($_SESSION['cart']))
		foreach($_SESSION['cart'] as $value){
			$cartData[] = $sql->selectFollowing(['id', 'book_name', 'author', 'publication'], ['id' => $value], 'book')->fetch_all()[0];
		}
	} catch (Exception $e) {
		$error = $e->getMessage();
	}	
?>
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
	.switch {
	  position: relative;
	  display: inline-block;
	  width: 60px;
	  height: 34px;
	}

	/* Hide default HTML checkbox */
	.switch input {
	  opacity: 0;
	  width: 0;
	  height: 0;
	}

	/* The slider */
	.slider {
	  position: absolute;
	  cursor: pointer;
	  top: 0;
	  left: 0;
	  right: 0;
	  bottom: 0;
	  background-color: #ccc;
	  -webkit-transition: .4s;
	  transition: .4s;
	}

	.slider:before {
	  position: absolute;
	  content: "";
	  height: 26px;
	  width: 26px;
	  left: 4px;
	  bottom: 4px;
	  background-color: white;
	  -webkit-transition: .4s;
	  transition: .4s;
	}

	input:checked + .slider {
	  background-color: #2196F3;
	}

	input:focus + .slider {
	  box-shadow: 0 0 1px #2196F3;
	}

	input:checked + .slider:before {
	  -webkit-transform: translateX(26px);
	  -ms-transform: translateX(26px);
	  transform: translateX(26px);
	}

	/* Rounded sliders */
	.slider.round {
	  border-radius: 34px;
	}

	.slider.round:before {
	  border-radius: 50%;
	}
	</style>
	<link rel="stylesheet" type="text/css" href="/maincss.css">
	<title>Give book</title>
</head>
<body>
	<?php require_once("/librarym/section/topSection.php");?>
	<?php require_once("/librarym/section/adminNav.php");?>
	<form method="get">
			<input class="searchInput" type="text" name="search">
			<select class="searchInput" name="subject">
				<option value="">Subject</option>
				<?= $optionText?>
			</select>
			<input type="submit" name="submit" value="search">
	</form>
	<form method="post">
		<?php foreach($rows as $key=>$value): 
			if(!isInSession($value[0])){

		?>
			<div class="inlinedc">
				<span class="definition"><?=$key+1?>)</span> <span class="definition"><?=$value[1]?></span> <a class="btn btn-secondary" href="/book/bookdetails.php?id=<?=$value[0]?>">Book Details</a>
				<?php if(isAvailable($value[0])):?>
					<label class="switch">
					  <input type="checkbox" name="book[]" value="<?=$value[0]?>">
					  <span class="slider"></span>
					</label>
				<?php endif;?>
			</div>
		<?php }endforeach;?>
		<input type="submit" name="send" value="Add selection to cart">
	</form>
	<br><br>
	<div style="font-size: 2rem; text-align: center;" class="definition">Books in the cart</div>
	<table class="table-info table">
		<tr class="thead-dark">
			<th>SN</th>
			<th>Book Name</th>
			<th>Publication</th>
			<th>Author</th>
		</tr>
		<?php foreach ($cartData as $key => $value) {
			printf(
				"<tr>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
				",
				$value[0], $value[1], $value[2], $value[3]
			);
		}?>
	</table>
	<form action="/user/final.php">
		<input type="submit" name="final" value="Borrow these books">
	</form>
	<?php require_once("/librarym/section/bottomSection.php");?>
	<div class="error"><?=$error?></div>
</body>
</html>