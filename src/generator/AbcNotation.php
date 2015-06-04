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
	private $noteList;

	function __construct($referenceNumber, $noteLength, $macro, $keySignature, $noteList) {
		$this->referenceNumber = $referenceNumber;
		$this->noteLength = $noteLength;
		$this->macro = $macro;
		$this->keySignature = $keySignature;
		$this->noteList = $noteList;
	}

	public static function newBuilder() {
		return new AbcNotationBuilder();
	}

	public function toString() {
		return "X:$this->referenceNumber<br/>L:$this->noteLength<br/>M:$this->macro<br/>K:$this->keySignature<br/>" . implode($this->noteList);
	}
}

class AbcNotationBuilder {
	private $referenceNumber;
	private $noteLength;
	private $macro;
	private $keySignature;
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

	public function withNoteList($noteList) {
		$this->noteList = $noteList;
		return $this;
	}

	public function build() {
		return new AbcNotation($this->referenceNumber,
			$this->noteLength,
			$this->macro,
			$this->keySignature,
			$this->noteList);
	}
}