<!doctype html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="refresh" content="110">
<title>pith current</title>
<link rel="shortcut icon" type="image/x-icon" href="pith.ico">

<link href="style.css" rel="stylesheet" type="text/css">

</head>
<body>

	<div class="container">
		<a href="./index.php">
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
    echo "<span id=\"temp-value\">" . $var_temp . "</span>";
    echo "<span id=\"humi-value\">" . $var_humi . "</span>";
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>
		</a>
	</div>

</body>
</html>
