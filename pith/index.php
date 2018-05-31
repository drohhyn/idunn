<?php require_once 'config.php';?>
<!doctype html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="refresh" content="110">
<title>pith</title>
<link rel="shortcut icon" type="image/x-icon" href="pith.ico">
<link rel="manifest" href="manifest.json">

<link href="style.css" rel="stylesheet" type="text/css">
<link href="flot/ui/jquery-ui.css" rel="stylesheet" type="text/css">
<link href="flot/jquery.timepicker.css" rel="stylesheet" type="text/css">
<!--[if lte IE 8]><script type="text/javascript" src="flot/excanvas.min.js"></script><![endif]-->
<script type="text/javascript"
	src="flot/jquery.js"></script>
<script type="text/javascript"
	src="flot/ui/jquery-ui.js"></script>

<script type="text/javascript"
	src="flot/jquery.timepicker.js"></script>
<script type="text/javascript"
	src="flot/jquery.flot.js"></script>
<script type="text/javascript"
	src="flot/jquery.flot.time.js"></script>
<script type="text/javascript">
	$(function() {
		$("#datepicker").datepicker({dateFormat: "@", maxDate: 0, changeMonth: true});
		$("#datepicker").attr("value",new Date().setHours(0,0,0,0));
		$("#timepicker").timepicker({timeFormat: "H:i"});
		var options = {
			lines: 	{	show: true},
			points:	{	show: true},
			grid:	{	hoverable: true},
			xaxis: {	mode: "time",	
			    timeformat: "%Y%m%d %H:%M:%S",
			    timezone: "browser"},
			yaxes: [{min: 0},{min: 0, max: 100, position: "right", alignTicksWithAxis: 1}],
			legend: {container: "#legend" }
	       };
		$("<div id='tooltip'></div>").css({
			position: "absolute",
			display: "none",
			border: "1px solid #D4C4E8",
			padding: "2px",
			"background-color": "#F0E4FF",
			opacity: 0.80,
			width: "200px",
		}).appendTo("body");

		<?php
// TODO: magic number 3600: timezone problem
$startDate = ($_POST["datepicker"] / 1000) + strtotime("1970-01-01 " . $_POST["timepicker"] . "") + 3600;

try {
    $db = new PDO('sqlite:'.$CONFIG['db.path']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $query = "SELECT * FROM (SELECT * FROM pith ORDER BY datetime DESC LIMIT 60) sub ORDER BY datetime ASC;";
    if (isset($_POST["datepicker"])) {
        $query = "SELECT * FROM (SELECT * FROM pith WHERE datetime > '" . $startDate . "' LIMIT 60) sub ORDER BY datetime ASC;";
    }
    $results = $db->query($query);
    foreach ($results as $row) {
        $points_temp .= "[" . ($row['datetime'] * 1000) . ", " . $row['temperature'] . "] , ";
        $points_humi .= "[" . ($row['datetime'] * 1000) . ", " . $row['humidity'] . "] , ";
    }
    $var_temp = "var data_temp = [" . $points_temp . "];\n";
    echo $var_temp;
    $var_humi = "var data_humi = [" . $points_humi . "];\n";
    echo $var_humi;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>

		$("#placeholder").bind("plothover", function (event, pos, item) {
 
		if (item) {
			var x = item.datapoint[0].toFixed(2),
			    yt = item.datapoint[1].toFixed(2);
			var date = new Date(x * 1);
			var unit = (item.seriesIndex == 0) ? "°C" : "%";
 
			$("#tooltip").html(date.getFullYear() + "-" + ('0' + (date.getMonth() + 1)).slice(-2) + "-" + date.getDate() + " - " + ('0' + date.getHours()).slice(-2) + ":" + ('0'+date.getMinutes()).slice(-2) + ":" + ('0' + date.getSeconds()).slice(-2) + " => " + yt + unit)
				.css({top: item.pageY+5, left: item.pageX+5})
				.fadeIn(200);
			} else {
				$("#tooltip").hide();
			}
		});
		$.plot("#placeholder", [{data: data_temp , label: "Temperatur [°C]" , color: 2},{data: data_humi, label: "Luftfeuchtigkeit [%]", color: 1, yaxis: 2}], options);
	});

	</script>
</head>
<body>
	<div id="header">

		<h1 class="headline-link">
			<a href="./current.php">Temperatur &amp; Luftfeuchtigkeit</a>
		</h1>
	</div>

	<div id="content">
		<div class="demo-container">
			<div id="placeholder" class="demo-placeholder"></div>
		</div>
		<span id="legend"></span>
		<form method="post" name="setterdatetime">
			<span style="float: right;"> <input autocomplete="off"
				readonly="readonly" type="text" name="datepicker" id="datepicker" /><input
				type="text" value="12:00" name="timepicker" id="timepicker" /><input
				type="submit" value="&gt;" />
			</span>
		</form>
	</div>
</body>
</html>
