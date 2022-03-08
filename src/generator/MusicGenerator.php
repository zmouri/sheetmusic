<?php
class MusicGenerator {
	// see wikipedia http://en.wikipedia.org/wiki/Abc_notation for description of ABC notation
	private static $ABC_CONSTANTS = array(
		'X' => '1',				// number of tunes
		'M' => '4/4',				// time signature
		'L' => '1/1',				// default note length
		'Q' => '1/4=70'				// tempo
	);

	private $measureNumber;
	private $noteLengthArr;
	private $noteValueArr;
	private $keySignature;
	private $beatCounter;
	private $measureCounter;
	private $title;
	private $rests;
	private $sharpsFlats;
	private $twoHand;
	private $twoHandNoteValueArr;

	public function __construct($numMeasures, $lengths, $values, $keySignature, $title, $rests, $sharpsFlats, $twoHand, $twoHandValues) {
		$this->measureNumber = $numMeasures;
		$this->noteLengthArr = $lengths;
		$this->noteValueArr = $values;
		$this->keySignature = $keySignature;
		$this->title = $title;
		$this->rests = $rests;
		$this->sharpsFlats = $sharpsFlats;
		$this->twoHand = $twoHand;
		$this->twoHandNoteValueArr = $twoHandValues;
	}

	public function generateABC() {
		$output = "";
		foreach(self::$ABC_CONSTANTS as $notation => $val) {
			$output .= "$notation:$val<br/>";
		}

		// add title
		$output .= "T:$this->title<br/>";

		// add key, must be the last element in the tune header
		$output .= "K:$this->keySignature<br/>";

		$output .= "V:V1 clef=treble<br/>";
		$this->beatCounter = 0;
		$this->measureCounter = 0;
		$output .= $this->generateMeasures($this->noteValueArr);

		// add 2 hand clef if set
		if($this->twoHand) {
			$output .= "]<br/>";
			$output .= "V:V2 clef=bass middle=d<br/>";
			$this->beatCounter = 0;
			$this->measureCounter = 0;
			$output .= $this->generateMeasures($this->twoHandNoteValueArr);
		}

		$output .= ']';
		return $output;
	}

	private function generateMeasures($noteValueArr) {
		$output = "";
		$lastEighthNote = false;
		$lastNote = NULL;
		while($this->measureCounter < $this->measureNumber) {
			// generate the note
			$note = $this->generateNote($noteValueArr);

			// if the last note was an eighth, make sure the note that was generated is not more than eight notes away
			while($lastNote && $lastNote->length == NoteGenerator::$EIGHTH && abs($lastNote->value - $note->value) >= 8 ) {
				$note = $this->generateNote($noteValueArr);
			}

			// get the note time
			// if the last note was an eighth, always make this note an eighth to even things up
			if($lastEighthNote) {
				$note->length = NoteGenerator::$EIGHTH;
			}
			$calculatedLength = pow(2, $note->length);

			// print the note
			$output .= $note->modifier;
			$output .= NoteGenerator::$NOTE_VALUES[$note->value];

			// determine bar and print if necessary
			// assumes 4/4 time - TODO extend to support any time
			$this->beatCounter += 4 / $calculatedLength;
			if($this->beatCounter == 4) {
				// exactly 4 beats, print the note and a bar
				if($calculatedLength != 1) {
					$output .= "/$calculatedLength";
				}
				$output .= $this->printBar();
			}
			elseif($this->beatCounter > 4) {
				// greater than 4 beats
				// modify the time on the note to make an even bar
				// TODO add support for ties
				$currentBar = 4 / $calculatedLength - ($this->beatCounter - 4);
				$output .= $this->convertFraction($currentBar / 4);
				$output .= $this->printBar();
			}
			else {
				// less than 4 beats, don't print the bar
				if($calculatedLength != 1) {
					$output .= "/$calculatedLength";
				}
			}

			// if the last note printed was an eighth note, save it, and print the next note as an eighth
			$lastEighthNote = (!$lastEighthNote && $calculatedLength == 8);
			$lastNote = $note;
		}

		return $output;
	}

	private function generateNote($noteValueArr) {
		require_once dirname(__FILE__) . '/NoteGenerator.php';

		$noteGenerator = new NoteGenerator();
		$note = $noteGenerator->generate($this->noteLengthArr, $noteValueArr, $this->rests, $this->sharpsFlats);

		return $note;
	}

	private function printBar() {
		$out = ' |';
		// every 4 measures, new line
		$this->measureCounter++;
		if($this->measureCounter % 4 == 0 && $this->measureCounter < $this->measureNumber) {
			$out .= "<br/>";
		}

		// space after the bar if we're not at the end
		if($this->measureCounter < $this->measureNumber) {
			$out .= " ";
		}

		$this->beatCounter = 0;
		return $out;
	}

	private function convertFraction($decimal) {
		// convert decimal into fraction with denominator of 8 (eighth notes)
		if(is_int($decimal)) {
			return $decimal;
		}

		$numerator = 8 * $decimal;
		return "$numerator/8";
	}
}