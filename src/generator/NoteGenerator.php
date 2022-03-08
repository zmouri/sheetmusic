<?php
class NoteGenerator {

	public static $NOTE_VALUES_NO_REST = array(
		'G,',
		'A,',
		'B,',
		'C',
		'D',
		'E',
		'F',
		'G',
		'A',
		'B',
		'c',
		'd',
		'e',
		'f',
		'g',
		'a',
		'b',
		'c\''
	);
	public static $NOTE_VALUES = array(
		'G,',
		'A,',
		'B,',
		'C',
		'D',
		'E',
		'F',
		'G',
		'A',
		'B',
		'c',
		'd',
		'e',
		'f',
		'g',
		'a',
		'b',
		'c\'',
		'z'
	);
	public static $REST_VALUE = 18;

	public static $WHOLE = 0;
	public static $HALF = 1;
	public static $QUARTER = 2;
	public static $EIGHTH = 3;

	public static $NOTE_LENGTHS = array(
		'Whole',
		'Half',
		'Quarter',
		'Eighth'
	);

	function generate($validNoteLengths, $validNoteValues, $rests, $sharpsFlats) {
		require_once dirname(__FILE__) . '/../domain/Note.php';

		if($rests) {
			array_push($validNoteValues, self::$REST_VALUE);
		}
		$noteValue = array_rand(self::$NOTE_VALUES);
		while(array_search($noteValue, $validNoteValues) === FALSE) {	// use === since this can return 0 as a valid value
			$noteValue = array_rand(self::$NOTE_VALUES);
		}

		$noteLength = array_rand(self::$NOTE_LENGTHS);
		while(array_search($noteLength, $validNoteLengths) === FALSE) {	// use === since this can return 0 as a valid value
			$noteLength = array_rand(self::$NOTE_LENGTHS);
		}

		$noteModifier = "";
		// make sure not to generate sharps/flats for a rest
		if($sharpsFlats && $noteValue !== self::$REST_VALUE) {
			// 25% chance for a sharp or flat
			$rand = random_int(1,8);
			if($rand === 7) {
				$noteModifier = "^";
			} elseif($rand === 8) {
				$noteModifier = "_";
			} else {
				$noteModifier = "";
			}
		}
		return new Note($noteValue, $noteLength, $noteModifier);
	}
}