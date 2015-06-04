<?php
class Note {
	public $value;
	public $length;
	
	public function __construct($val, $len) {
		$this->value = $val;
		$this->length = $len;
	}
}