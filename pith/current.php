<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="refresh" content="110">
        <title>pith current</title>
        <link rel="shortcut icon" type="image/x-icon" href="pith.ico">

	<link href="style.css" rel="stylesheet" type="text/css">

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
		echo "<span id=\"temp-value\">".$var_temp."</span>";
		echo "<span id=\"humi-value\">".$var_humi."</span>";
	} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
	}
?>
</div>
</a>
</body>
</html>
