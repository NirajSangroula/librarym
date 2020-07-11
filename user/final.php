 <?php
 	session_start();
	include_once("/librarym/Sql/sql.php");
	include_once("/librarym/Sql/bookFunctions.php");
	include_once("/librarym/Helpers/pageFunctions.php");
	require_once("/librarym/Helpers/authentication.php");
	$authentication = Authentication::getInstance();
	$sql = new Sql();
	$authentication->sql = $sql;
	if(!($authentication->isLoggedIn() && $authentication->isAdmin())){
		sendToLoginPage();
	}

	try {
		$error = "";
		$sql = new Sql();
		if(isPost() && hasPost('book')){
			foreach($_POST['book'] as $key=>$value){
				addToCart($value);
			}

		}
		$cartData = [];
		if(isset($_SESSION['cart']))
		foreach($_SESSION['cart'] as $value){
			$query = "select a.book_name, a.author, a.publication, b.rack_no, b.description, b.fine_rate, b.availability, b.id from book a inner join book_location b on a.id = b.book_id where a.id = $value";
			$results = $sql->getMySqli()->query($query);
			if($sql->getError()){
				$error .= $sql->getError();
			}else{
				foreach($results->fetch_all() as $v){
					$cartData[$value][] = $v;
				}
			}
		}
		if(isPost() && hasPost('book')){
			// $sql->getMySqli()->begin_transaction();
			$date = new DateTime();
			$createdDate = $date->format("yy-m-d h:i:s");
			foreach($_POST['book'] as $key=>$value){
				$tillDate = date('yy-m-d h:i:s', strtotime("now + ".$_POST['day'][$key]." day"));
				$sql->insert(['book_id' => $value, 'user_id' => $_SESSION['target_user_id'], 'status' => 'borrowed', 'till_date' => $tillDate, 'borrowed_date' => $createdDate], 'borrow');
				$sql->update(['availability' => 'no'], ['id' => $value], 'book_location');
				$_SESSION = [];
				header("location: /user/borrows.php");
			}
			// $sql->getMySqli()->commit();
		}
	} catch (Exception $e) {
		$error = $e->getMessage();
	}	
 ?>
 <!DOCTYPE html>
 <html>
 <head><style type="text/css">
	.switch {
	  position: relative;
	  display: inline-block;
	  width: 60px;
	  height: 34px;
    bottom: 9px;
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
 	<title>Final</title>
 </head>
 <body>
	<?php require_once("/librarym/section/topSection.php");?>
	<?php require_once("/librarym/section/adminNav.php");?>
 	<form method="post">
 	<?php 
 		foreach($cartData as $id => $value): 
 	?>
 	<div style="border-top: 8px solid white"> 
 		<div class="inlinedc">
 			<span class="definition">Book: </span> <span class="content"><?=$value[0][0]?></span>
 		</div>
 		<div class="inlinedc">
 			<span class="definition">Author: </span> <span class="content"><?=$value[0][1]?></span>
 		</div>
 		<div class="inlinedc">
 			<span class="definition">Publication: </span> <span class="content"><?=$value[0][2]?></span>
 		</div>
		 	<table class="table table-info">
		 		<tr class="thead-dark">
		 			<th>Book Name</th>
		 			<th>Rack No</th>
		 			<th>Location</th>
		 			<th>Fine Rate</th>
		 			<th>Action</th>
		 		</tr>

			 	<?php foreach($cartData[$id] as $row){?>
				 	<tr>
				 		<td><?=$row[0]?></td>
				 		<td><?=$row[3]?></td>
				 		<td><?=$row[4]?></td>
				 		<td><?=$row[5]?></td>
				 		<td><?php if($row[6] == 'yes'){?>
				 		<label class="switch">
					  <input type="checkbox" name="book[]" value="<?=$row[7]?>">
					  <span class="slider round"></span>
					</label>
					<input style="margin-left: 3rem;" class="searchInput" type="number" name="day[]" placeholder="Return after days"><?php }
					else{?>
						<span style="font-size: 1.4rem" class="content">Not available to borrow</span>
				<?php 
					}
					?></td>
				 	</tr>
			 	<?php } ?>
		 	</table>
 	</div>
 	<?php endforeach; ?>
		<input type="submit" name="borrow" value="Borrow these">
	</form>
	<?php require_once("/librarym/section/bottomSection.php");?>
	<div class="error"><?=$error?></div>
 </body>
 </html>