<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="refresh" content="110">
        <title>pith current</title>
	<link rel="shortcut icon" type="image/x-icon" href="pith.ico">
	<link rel="manifest" href="manifest.json">

	<link href="style.css" rel="stylesheet" type="text/css">
	<script language="javascript" type="text/javascript" src="flot/jquery.js"></script>

</head>
<body>
<a href="./index.php">
<div class="container">
<?php
	try {
		$db = new PDO('sqlite:pith.sl3');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$query = "SELECT * FROM (SELECT * FROM pith ORDER BY datetime DESC LIMIT 1) sub ORDER BY datetime ASC;";

		$results = $db->query($query);

		$var_temp = 0;
		$var_humi = 0;
		foreach ($results as $row) {
		        $var_temp = $row['temperature'];
			$var_humi = $row['humidity'];
		}
		echo "<span class=\"temp-value\">".$var_temp."</span>";
		echo "<span class=\"humi-value\">".$var_humi."</span>";
	} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
	}
?>
</div>
</a>

<div class="ext-weather" style="font-size:small;">

</div>
<script>
    $.getJSON('http://api.openweathermap.org/data/2.5/weather?id=2817324&appid=1589effbcf2ea0ffab93b54fd8a19ee7&units=metric',
        function(data) {
		$(".ext-weather").html("<div class=\"temp-value\">"+data.main.temp+"</div><div class=\"humi-value\" style=\"float: none;\">"+data.main.humidity+"</div>");	
	});
</script>

</body>
</html>
