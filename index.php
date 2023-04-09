<?php
	require_once dirname(__FILE__) . '/src/generator/AbcNotation.php';
	require_once dirname(__FILE__) . '/src/generator/NoteGenerator.php';
	require_once dirname(__FILE__) . '/src/domain/KeySignature.php';

	$measureNumber = array_key_exists("txtMeasureNumber", $_REQUEST) ? $_REQUEST["txtMeasureNumber"] : "30";
	$noteLengths = array_key_exists("chkNoteLength", $_REQUEST) ? $_REQUEST["chkNoteLength"] : range(0, count(NoteGenerator::$NOTE_LENGTHS));	// default is all of them
	$noteValues = array_key_exists("chkNoteValue", $_REQUEST) ? $_REQUEST["chkNoteValue"] : range(0, count(NoteGenerator::$NOTE_VALUES_NO_REST));	// default is all of them
	$selectedClef = array_key_exists("selClef", $_REQUEST) ? $_REQUEST["selClef"] : "treble";
	$selectedKey = array_key_exists("selKey", $_REQUEST) ? $_REQUEST["selKey"] : "C";
	$rests = array_key_exists("chkRests", $_REQUEST) ? $_REQUEST["chkRests"] === "true" : false;
	$sharpsFlats = array_key_exists("chkSharpsFlats", $_REQUEST) ? $_REQUEST["chkSharpsFlats"] === "true" : false;
	$noRepeat = array_key_exists("chkNoRepeat", $_REQUEST) ? $_REQUEST["chkNoRepeat"] === "true" : false;
	$twoHand = array_key_exists("chkTwoHand", $_REQUEST) ? $_REQUEST["chkTwoHand"] === "true" : false;
	$twoHandNoteValues = array_key_exists("chkTwoHandNoteValue", $_REQUEST) ? $_REQUEST["chkTwoHandNoteValue"] : range(0, count(NoteGenerator::$NOTE_VALUES_NO_REST));	// default is all of them

	$keySignature = new KeySignature();
	$signatureList = $keySignature->getKeySignatures();
	foreach($signatureList as $signature => $notes) {
		foreach(NoteGenerator::$CLEF_VALUES as $clef => $octave) {
			$keyScales[$clef][$signature] = AbcNotation::newBuilder()
							->withReferenceNumber("1")
							->withNoteLength("1/1")
							->withMacro("")	// hides the time signature
							->withKey("$signature")
							->withVoice("V:V1 clef=$clef octave=$octave")
							->withNoteList(NoteGenerator::$NOTE_VALUES_NO_REST)
							->build()
							->toString();
		}
		$twoHandKeyScales[$signature] = AbcNotation::newBuilder()
						->withReferenceNumber("1")
						->withNoteLength("1/1")
						->withMacro("")	// hides the time signature
						->withKey($signature)
						->withVoice("V:V2 clef=bass middle=d")
						->withNoteList(NoteGenerator::$NOTE_VALUES_NO_REST)
						->build()
						->toString();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once dirname(__FILE__) . '/script_header.php'; ?>
<script type="text/javascript">
  	var keySignature = [];
  	<?php foreach(NoteGenerator::$CLEF_VALUES as $clef => $octave) {
  		echo "keySignature[\"$clef\"] = [];\n";
  		foreach($keyScales[$clef] as $signature => $abcString) {
  			echo "keySignature[\"$clef\"][\"$signature\"] = \"$abcString\";\n";
  		}
  	}
  	?>
  	var twoHandKeySignature = [];
  	<?php foreach($twoHandKeyScales as $signature => $abcString) {
  		echo "twoHandKeySignature[\"$signature\"] = \"$abcString\";\n";
  	}
  	?>

	var margins = [];
  	margins["C"] = { "margin" : 9, "padding" : 12 };
	margins["G"] = { "margin" : 21, "padding" : 12 };
	margins["D"] = { "margin" : 31, "padding" : 11 };
	margins["A"] = { "margin" : 42, "padding" : 11 };
	margins["E"] = { "margin" : 53, "padding" : 10 };
	margins["B"] = { "margin" : 64, "padding" : 10 };
	margins["F#"] = { "margin" : 75, "padding" : 9 };
	margins["C#"] = { "margin" : 86, "padding" : 9 };
	margins["F"] = { "margin" : 19, "padding" : 12 };
	margins["Bb"] = { "margin" : 29, "padding" : 11 };
	margins["Eb"] = { "margin" : 39, "padding" : 11 };
	margins["Ab"] = { "margin" : 49, "padding" : 10 };
	margins["Db"] = { "margin" : 59, "padding" : 10 };
	margins["Gb"] = { "margin" : 69, "padding" : 9 };
	margins["Cb"] = { "margin" : 79, "padding" : 9 };

	$(document).ready(function() {
		$('#btnSelectLengthAll').click(function() {
			$('.lengthList li input').prop('checked', true);
		});
		$('#btnSelectLengthNone').click(function() {
			$('.lengthList li input').prop('checked', false);
		});

		$('#btnSelectNoteAll').click(function() {
			$('.notelist li input').prop('checked', true);
		});
		$('#btnSelectNoteNone').click(function() {
			$('.notelist li input').prop('checked', false);
		});
		$('#btnFiveFinger').click(function() {
			$('.notelist li input').prop('checked', false);
			$('.notelist li input[note=C]').prop('checked', true);
			$('.notelist li input[note=D]').prop('checked', true);
			$('.notelist li input[note=E]').prop('checked', true);
			$('.notelist li input[note=F]').prop('checked', true);
			$('.notelist li input[note=G]').prop('checked', true);
		});

		$('select[name="selClef"]').change(function() {
			adjustMargins(margins[$('select[name="selKey"]').val()]);

			ABCJS.renderAbc("sampleMusic", keySignature[$(this).val()][$('select[name="selKey"]').val()]);
			ABCJS.renderAbc("sampleTwoHandMusic", twoHandKeySignature[$('select[name="selKey"]').val()]);
		});

		$('select[name="selKey"]').change(function() {
			adjustMargins(margins[$(this).val()]);

			ABCJS.renderAbc("sampleMusic", keySignature[$('select[name="selClef"]').val()][$(this).val()]);
			ABCJS.renderAbc("sampleTwoHandMusic", twoHandKeySignature[$(this).val()]);
		});

		$('#chkTwoHand').click(function() {
			$('.twoHand').toggle();
		});
		$('#btnSelectTwoHandNoteAll').click(function() {
			$('.noteTwoHandList li input').prop('checked', true);
		});
		$('#btnSelectTwoHandNoteNone').click(function() {
			$('.noteTwoHandList li input').prop('checked', false);
		});

		$('#frmGenerate').submit(function(event) {
			$('#spnErrorLength').css('display' , 'none');
			$('#spnErrorNote').css('display' , 'none');

			// if no boxes checked, can't continue
			if(!($('.lengthList li input').is(':checked'))) {
				$('#spnErrorLength').css('display' , 'inline');
				$('#spnErrorLength').html('At least one length must be included!');
				return false;
			}

			if(!($('.notelist li input').is(':checked'))) {
				$('#spnErrorNote').css('display', 'inline');
				$('#spnErrorNote').html('At least one note must be included!');
				return false;
			}

			if ($('#chkNoRepeat').is(':checked') && $('.notelist li input').filter(':checked').length === 1) {
				$('#spnErrorNote').css('display', 'inline');
				$('#spnErrorNote').html('At least two notes must be included in order to avoid repeats!');
				return false;
			}

			return true;
		});

		adjustMargins(margins["<?php echo $selectedKey; ?>"]);

		ABCJS.renderAbc("sampleMusic", keySignature[$('select[name="selClef"]').val()][$('select[name="selKey"]').val()]);
		ABCJS.renderAbc("sampleTwoHandMusic", twoHandKeySignature[$('select[name="selKey"]').val()]);
	});

	function adjustMargins(adjustment) {
		$('.notediv').css('margin-left', adjustment.margin);
		$('.notelist li').css('padding-right', adjustment.padding);

		$('.noteTwoHandDiv').css('margin-left', adjustment.margin);
		$('.noteTwoHandList li').css('padding-right', adjustment.padding);
	}
</script>
</head>
<body>
	<div class="wrapper">
	<form id="frmGenerate" action="generate.php" method="get">
		<h2>Sheet Music Generator</h2>
		<div><label id="lblMeasureNumber" for="txtMeasureNumber">Number of measures: </label><input id="txtMeasureNumber" name="txtMeasureNumber" type="text" value="<?php echo $measureNumber; ?>" /></div>
		<div>
			<label id="lblNoteLength" for="chkNoteLength">Length of notes to generate: </label>
			<input type="button" id="btnSelectLengthAll" value="All" />
			<input type="button" id="btnSelectLengthNone" value="None" />
			<span id="spnErrorLength" class="error" style="display: none"></span>
			<ul class="lengthList">
				<?php foreach(NoteGenerator::$NOTE_LENGTHS as $key => $name) {
					echo "<li><input type=\"checkbox\" name=\"chkNoteLength[]\"" . (array_search($key, $noteLengths) !== FALSE ? " checked=\"checked\" " : "") . " value=\"$key\" />$name</li>";
				}
				?>
			</ul>
		</div>
		<div>
			<label id="lblClef" for="selClef">Clef: </label>
			<select name="selClef">
				<?php foreach(NoteGenerator::$CLEF_VALUES as $clef => $octave) {
					echo "<option value=\"$clef\"" . ($clef == $selectedClef ? " selected " : "") . ">$clef</option>";
				}
				?>
			</select>
		</div>
		<div>
			<label id="lblKey" for="selKey">Key to generate: </label>
			<select name="selKey">
				<?php foreach($signatureList as $signature => $notes) {
					echo "<option value=\"$signature\"" . ($signature == $selectedKey ? " selected " : "") . ">$signature</option>";
				}
				?>
			</select>
		</div>
		<label class="notelabel">Notes to generate:</label>
		<input type="button" id="btnSelectNoteAll" value="All" />
		<input type="button" id="btnSelectNoteNone" value="None" />
		<input type="button" id="btnFiveFinger" value="5-Finger" />
		<span id="spnErrorNote" class="error" style="display: none"></span>
		<div><label id="lblRests" for="chkRests">Generate rests?: </label><input id="chkRests" name="chkRests" type="checkbox" value="true" <?= $rests ? "checked" : "" ?> /></div>
		<div>
			<label id="lblSharpsFlats" for="chkSharpsFlats">Generate random sharps/flats?: </label><input id="chkSharpsFlats" name="chkSharpsFlats" type="checkbox" value="true" <?= $sharpsFlats ? "checked" : "" ?> />
			<span>(These currently do not take the key into account)</span>
		</div>
		<div>
			<label id="lblNoRepeat" for="chkNoRepeat">Avoid repeated notes?: </label><input id="chkNoRepeat" name="chkNoRepeat" type="checkbox" value="true" <?= $noRepeat ? "checked" : "" ?> />
			<span>(This will not generate two of the same note in a row)</span>
		</div>
		<div id="sampleMusic"></div>
		<div class="notediv">
			<ul class="notelist">
				<?php foreach(NoteGenerator::$NOTE_VALUES_NO_REST as $key => $name) {
					echo "<li><input type=\"checkbox\" name=\"chkNoteValue[]\"" . (array_search($key, $noteValues) !== FALSE ? " checked=\"checked\" " : "") . " value=\"$key\" note=\"$name\" /></li>";
				}
				?>
			</ul>
		</div>

		<div>
			<label id="lblTwoHand" for="chkTwoHand">Grand Staff?: </label><input id="chkTwoHand" name="chkTwoHand" type="checkbox" value="true" <?= $twoHand ? "checked" : "" ?> />
			<span>(Generate an additional bass clef for the piano)</span>
		</div>
		<div class="twoHand" <?= !$twoHand ? "style=\"display: none\"" : "" ?> >
			<label class="notelabel">Notes to generate:</label>
			<input type="button" id="btnSelectTwoHandNoteAll" value="All" />
			<input type="button" id="btnSelectTwoHandNoteNone" value="None" />
			<div id="sampleTwoHandMusic"><?php echo $twoHandKeyScales[$selectedKey]; ?></div>
			<div class="noteTwoHandDiv">
				<ul class="noteTwoHandList">
					<?php foreach(NoteGenerator::$NOTE_VALUES_NO_REST as $key => $name) {
						 echo "<li><input type=\"checkbox\" name=\"chkTwoHandNoteValue[]\"" . (array_search($key, $twoHandNoteValues) !== FALSE ? " checked=\"checked\" " : "") . " value=\"$key\" note=\"$name\" /></li>";
					}
					?>
				</ul>
			</div>
		</div>
		<input id="btnSubmit" name="btnSubmit" type="submit" value="Generate!" />
	</form>
	<div class="push"></div>
	</div>
	<div class="footer">
		<?php /*require_once dirname(__FILE__) . '/footer.php';*/ ?>
	</div>
</body>
</html>