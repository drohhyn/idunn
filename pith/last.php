<?php
require_once 'config.php';
header('Content-Type: application/json');
print "{\"data\": ";
try {
    $db = new PDO('sqlite:'.$CONFIG['db.path']);
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
}