<?php
	require_once dirname(__FILE__) . '/src/generator/MusicGenerator.php';

	$measureNumber = $_REQUEST["txtMeasureNumber"];
	$noteLengths = $_REQUEST["chkNoteLength"];
	$noteValues = $_REQUEST["chkNoteValue"];
	$selKey = $_REQUEST["selKey"];
	$rests = array_key_exists("chkRests", $_REQUEST) ? $_REQUEST["chkRests"] === "true" : false;
	$sharpsFlats = array_key_exists("chkSharpsFlats", $_REQUEST) ? $_REQUEST["chkSharpsFlats"] === "true" : false;
	$twoHand = (array_key_exists("chkTwoHand", $_REQUEST) && array_key_exists("chkTwoHandNoteValue", $_REQUEST)) ? $_REQUEST["chkTwoHand"] === "true" : false;
	$twoHandNoteValues = $_REQUEST["chkTwoHandNoteValue"];
	$errorMessage = "";

	$tempo = 70;
	$title = 'Sheet Music';

	if(empty($noteLengths) || empty($noteValues) || !is_numeric($measureNumber)) {
		$errorMessage = "Something went wrong generating music! Please hit back and try again.";
		$music = "";
	} else {
		if((int)($measureNumber) >= 5000) {
			$errorMessage = "Too many measures! Was only able to generate 5000.";
			$measureNumber = 5000;
		}
		$musicGenerator = new MusicGenerator((int)$measureNumber, $noteLengths, $noteValues, $selKey, $title, $rests, $sharpsFlats, $twoHand, $twoHandNoteValues);
		$music = $musicGenerator->generateABC();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once dirname(__FILE__) . '/script_header.php'; ?>
<script type="text/javascript">
  	ABCJS.plugin.show_midi = true;
  	ABCJS.plugin.hide_abc = true;
	$(document).ready(function() {
		$('#btnRefresh').click(function() {
			location.reload();
		});
		$('#btnPrint').click(function() {
			window.print();
		});
		$('#btnBack').click(function() {
			window.location.replace('index.php' + window.location.search);
		});
		$('#txtTempo').change(function() {
			music = "<?php echo $music;?>";
			$('#notation').html(music.replace(/Q:1\/4=[0-9]*/, "Q:1/4=" + $(this).val()));

			ABCJS.plugin.start(jQuery);
		});
	});
</script>
</head>
<body>
	<input id="btnRefresh" name="btnRefresh" type="button" value="Re-generate!" />
	<div><label id="lblTempo" for="txtTempo">Tempo: </label><input id="txtTempo" name="txtTempo" type="text" value="<?php echo $tempo; ?>" /></div>
	<div class="print"><input id="btnPrint" name="btnPrint" type="button" value="Print!" /></div>
	<div id="error"><?= $errorMessage ?></div>
	<div id="notation">
		<?php echo $music;?>
	</div>
	<input id="btnBack" name="btnBack" type="button" value="<<< Back to start" />
</body>
</html>