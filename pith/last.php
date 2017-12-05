<?php
header('Content-Type: application/json');

try {
	$db = new PDO('sqlite:pith.sl3');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = "SELECT * FROM (SELECT * FROM pith ORDER BY datetime DESC LIMIT 3) sub ORDER BY datetime ASC;";
	$sth = $db->prepare($query);
	$sth->execute();
	$results = $sth->fetchAll(PDO::FETCH_ASSOC);

	/* foreach ($results as $row) {
	        $var_temp = $row['temperature'];
		$var_humi = $row['humidity'];
	}*/
	print json_encode($results);	
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>
