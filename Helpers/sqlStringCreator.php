<?php
class SqlStringCreator{
	public function createDataString(array $data){
		$suitableColumnText = "";
		$suitableColumnValues = "";
		$i = 1;
		foreach ($data as $key => $value) {
			if($i++ < count($data)){
				$suitableColumnText .= ($key.',');
				$suitableColumnValues .= ($this->quoteIfString($value) . ', ');
			}
			else{
				$suitableColumnText .= ($key);
				$suitableColumnValues .= ($this->quoteIfString($value));
			}
		}
		return [$suitableColumnText, $suitableColumnValues];
	}
	public function quoteIfString($data){
		if(is_double($data) || is_integer($data)){
			return $data;
		}
		else
			return '\'' . $data . '\'';
	}
	public function createIdentifierString(array $identifier){
		$string = "";
		$i = 1;
		foreach($identifier as $key => $value){
			if($i++ < count($identifier)){
			$string .= "$key = " . $this->quoteIfString($value) . " and ";
			}
			else{
				$string .= ("$key = " . $this->quoteIfString($value));
			}
		}
		return $string;
	}
	public function createLikeString(array $identifier){
		$string = "";
		$i = 1;
		foreach($identifier as $key => $value){
			if($i++ < count($identifier)){
			$string .= "$key like " . $this->quoteIfString($value) . " and ";
			}
			else{
				$string .= ("$key like " . $this->quoteIfString($value));
			}
		}
		return $string;
	}
	public function createUpdateString(array $data){
		$text = "";
		$i = 1;
		foreach ($data as $key => $value) {
			if($i++ < count($data))
				$text .= ($key . ' = ' . $this->quoteIfString($value) . ', ');
			else
				$text .= ($key . ' = ' . $this->quoteIfString($value));
		}
		return $text;
	}
}