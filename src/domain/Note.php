<?php
class Note {
	public $value;
	public $length;
	public $modifier;	// e.g. sharp or flat

	public function __construct($val, $len, $mod) {
		$this->value = $val;
		$this->length = $len;
		$this->modifier = $mod;
	}
}