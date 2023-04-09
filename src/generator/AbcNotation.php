<?php
/**
 * Builds ABC information fields. See the ABC notation standard for descriptions of each
 *
 * @link http://abcnotation.com/wiki/abc:standard:v2.1#description_of_information_fields
 */
class AbcNotation {

	private $referenceNumber;
	private $noteLength;
	private $macro;
	private $keySignature;
	private $voice;
	private $noteList;

	function __construct($referenceNumber, $noteLength, $macro, $keySignature, $voice, $noteList) {
		$this->referenceNumber = $referenceNumber;
		$this->noteLength = $noteLength;
		$this->macro = $macro;
		$this->keySignature = $keySignature;
		$this->voice = $voice;
		$this->noteList = $noteList;
	}

	public static function newBuilder() {
		return new AbcNotationBuilder();
	}

	public function toString() {
		return "X:$this->referenceNumber\\nL:$this->noteLength\\nM:$this->macro\\nK:$this->keySignature\\nV:$this->voice\\n" . implode($this->noteList);
	}
}

class AbcNotationBuilder {
	private $referenceNumber;
	private $noteLength;
	private $macro;
	private $keySignature;
	private $voice;
	private $noteList;

	public function withReferenceNumber($referenceNumber) {
		$this->referenceNumber = $referenceNumber;
		return $this;
	}

	public function withNoteLength($noteLength) {
		$this->noteLength = $noteLength;
		return $this;
	}

	public function withMacro($macro) {
		$this->macro = $macro;
		return $this;
	}

	public function withKey($keySignature) {
		$this->keySignature = $keySignature;
		return $this;
	}

	public function withVoice($voice) {
		$this->voice = $voice;
		return $this;
	}

	public function withNoteList($noteList) {
		$this->noteList = $noteList;
		return $this;
	}

	public function build() {
		return new AbcNotation($this->referenceNumber,
			$this->noteLength,
			$this->macro,
			$this->keySignature,
			$this->voice,
			$this->noteList);
	}
}