<?php
	require_once dirname(__FILE__) . '/src/generator/NoteGenerator.php';

	$measureNumber = array_key_exists("txtMeasureNumber", $_REQUEST) ? $_REQUEST["txtMeasureNumber"] : "";
	$noteLengths = array_key_exists("chkNoteLength", $_REQUEST) ? $_REQUEST["chkNoteLength"] : range(0, 4);	// default is all of them
	$noteValues = array_key_exists("chkNoteValue", $_REQUEST) ? $_REQUEST["chkNoteValue"] : range(0, 11);	// default is all of them
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once dirname(__FILE__) . '/script_header.php'; ?>
<script type="text/javascript">
	abc_plugin["show_midi"] = false;
	abc_plugin["hide_abc"] = true;
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
			$('.notelist li input[value=0]').prop('checked', true);
			$('.notelist li input[value=1]').prop('checked', true);
			$('.notelist li input[value=2]').prop('checked', true);
			$('.notelist li input[value=3]').prop('checked', true);
			$('.notelist li input[value=4]').prop('checked', true);
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

			return true;
		});
	});
</script>
</head>
<body>
	<form id="frmGenerate" action="generate.php" method="get">
		<h2>Sheet Music Generator</h2>
		<div><label id="lblMeasureNumber" for="txtMeasureNumber">Number of measures: </label><input id="txtMeasureNumber" name="txtMeasureNumber" type="text" value="<?php echo $measureNumber; ?>" /></div>
		<div>
			<label id="lblNoteLength" for="chkNoteLength">Length of notes to generate: </label>
			<input type="button" id="btnSelectLengthAll" value="All" />
			<input type="button" id="btnSelectLengthNone" value="None" />
			<span id="spnErrorLength" class="error" style="display: none"></span>
			<ul class="lengthList">
				<?php foreach(NoteGenerator::$NOTE_LENGTH_MAP as $key => $name) {
					echo "<li><input type=\"checkbox\" name=\"chkNoteLength[]\"" . (array_search($key, $noteLengths) !== FALSE ? " checked=\"checked\" " : "") . " value=\"$key\" />$name</li>";
				}
				?>
			</ul>
		</div>
		<label class="notelabel">Notes to generate:</label>
		<input type="button" id="btnSelectNoteAll" value="All" />
		<input type="button" id="btnSelectNoteNone" value="None" />
		<input type="button" id="btnFiveFinger" value="5-Finger" />
		<span id="spnErrorNote" class="error" style="display: none"></span>
		<div>
			X:1
			L:1/1
			M:
			K:C
			CDEFGABcdefg
		</div>
		<div class="notediv">
			<ul class="notelist">
				<?php foreach(NoteGenerator::$NOTE_VALUE_MAP as $key => $name) {
					echo "<li><input type=\"checkbox\" name=\"chkNoteValue[]\"" . (array_search($key, $noteValues) !== FALSE ? " checked=\"checked\" " : "") . " value=\"$key\" /></li>";
				}
				?>
			</ul>
		</div>
		<!-- <div><label id="lblRests" for="chkRests">Generate rests?: </label><input id="chkRests" name="chkRests" type="checkbox" /></div> -->
		<input id="btnSubmit" name="btnSubmit" type="submit" value="Generate!" />
	</form>
</body>
</html>