<?php
	function isAvailable($id){
		$sql = new Sql();
		$results = $sql->select(['book_id' => $id, 'availability' => 'yes'], 'book_location')->fetch_all();
		return count($results) > 0;
	}
?>