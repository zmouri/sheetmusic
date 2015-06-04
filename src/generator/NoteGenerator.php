<?php
class NoteGenerator {
	
	public static $NOTE_VALUE_MAP = array(
		0 => 'C',
		1 => 'D',
		2 => 'E',
		3 => 'F',
		4 => 'G',
		5 => 'A',
		6 => 'B',
		7 => 'c',
		8 => 'd',
		9 => 'e',
		10 => 'f',
		11 => 'g'
	);
	
	public static $WHOLE = 0;
	public static $HALF = 1;
	public static $QUARTER = 2;
	public static $EIGHTH = 3;
	
	public static $NOTE_LENGTH_MAP = array(
		0 => 'Whole',
		1 => 'Half',
		2 => 'Quarter',
		3 => 'Eighth'
	);
	
	function generate($validNoteLengths, $validNoteValues) {
		require_once dirname(__FILE__) . '/../domain/Note.php';

		$noteValue = array_rand(self::$NOTE_VALUE_MAP);
		while(array_search($noteValue, $validNoteValues) === FALSE) {	// use === since this can return 0 as a valid value
			$noteValue = array_rand(self::$NOTE_VALUE_MAP);
		}
		
		$noteLength = array_rand(self::$NOTE_LENGTH_MAP);
		while(array_search($noteLength, $validNoteLengths) === FALSE) {	// use === since this can return 0 as a valid value
			$noteLength = array_rand(self::$NOTE_LENGTH_MAP);
		}
		
		return new Note($noteValue, $noteLength);
	}
}