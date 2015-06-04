<?php
class KeyType {
	const NONE = 0;
	const SHARP = 1;
	const FLAT = 2;
}

class KeyNotes {
	public $type;
	public $noteList;

	function __construct($type, $noteList) {
		$this->type = $type;
		$this->noteList = $noteList;
	}
}

class KeySignature {

	private $keySignatures;

	function __construct() {
		$this->keySignatures['C'] = new KeyNotes(KeyType::NONE, array());
		$this->keySignatures['G'] = new KeyNotes(KeyType::SHARP, array('F'));
		$this->keySignatures['D'] = new KeyNotes(KeyType::SHARP, array('F', 'C'));
		$this->keySignatures['A'] = new KeyNotes(KeyType::SHARP, array('F', 'C', 'G'));
		$this->keySignatures['E'] = new KeyNotes(KeyType::SHARP, array('F', 'C', 'G', 'D'));
		$this->keySignatures['B'] = new KeyNotes(KeyType::SHARP, array('F', 'C', 'G', 'D', 'A'));
		$this->keySignatures['F#'] = new KeyNotes(KeyType::SHARP, array('F', 'C', 'G', 'D', 'A', 'E'));
		$this->keySignatures['C#'] = new KeyNotes(KeyType::SHARP, array('F', 'C', 'G', 'D', 'A', 'E', 'B'));
		$this->keySignatures['F'] = new KeyNotes(KeyType::FLAT, array('B'));
		$this->keySignatures['Bb'] = new KeyNotes(KeyType::FLAT, array('B', 'E'));
		$this->keySignatures['Eb'] = new KeyNotes(KeyType::FLAT, array('B', 'E', 'A'));
		$this->keySignatures['Ab'] = new KeyNotes(KeyType::FLAT, array('B', 'E', 'A', 'D'));
		$this->keySignatures['Db'] = new KeyNotes(KeyType::FLAT, array('B', 'E', 'A', 'D', 'G'));
		$this->keySignatures['Gb'] = new KeyNotes(KeyType::FLAT, array('B', 'E', 'A', 'D', 'G', 'C'));
		$this->keySignatures['Cb'] = new KeyNotes(KeyType::FLAT, array('B', 'E', 'A', 'D', 'G', 'C', 'F'));
	}

	public function getKeySignatures() {
		return $this->keySignatures;
	}

	public static function generateScale($keySignature, $validNoteValues) {
		$noteArray = array();
		foreach($validNoteValues as $note) {
			$noteToUse = $note;
			if(in_array($note, $keySignature->noteList)) {
				$noteToUse = $noteToUse . ($keySignature->type === KeyType::SHARP) ? '#' : 'b';
			}

			$noteArray[] = $noteToUse;
		}

		return $noteArray;
	}
}