<?php
	require_once dirname(__FILE__) . '/src/generator/MusicGenerator.php';

	$measureNumber = $_REQUEST["txtMeasureNumber"];
	$noteLengths = $_REQUEST["chkNoteLength"];
	$noteValues = $_REQUEST["chkNoteValue"];
	$tempo = 70;
	$title = 'Sheet Music';
	//$rests = $_REQUEST["chkRests"];
	$musicGenerator = new MusicGenerator($measureNumber, $noteLengths, $noteValues, $title);
	$music = $musicGenerator->generateABC();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once dirname(__FILE__) . '/script_header.php'; ?>
<script type="text/javascript">
	abc_plugin["show_midi"] = true;
	abc_plugin["hide_abc"] = true;
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

			start_abc();
		});
	});
</script>
</head>
<body>
	<input id="btnRefresh" name="btnRefresh" type="button" value="Re-generate!" />
	<div><label id="lblTempo" for="txtTempo">Tempo: </label><input id="txtTempo" name="txtTempo" type="text" value="<?php echo $tempo; ?>" /></div>
	<div class="print"><input id="btnPrint" name="btnPrint" type="button" value="Print!" /></div>
	<div id="notation">
		<?php echo $music;?>
	</div>
	<input id="btnBack" name="btnBack" type="button" value="<<< Back to start" />
</body>
</html>