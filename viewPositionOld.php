<?php
	include("lib/config.php");
	include("lib/funzioni.php");

	$tabella="tab_users_position";

	$conn=dbConnect();
	$sql="SELECT * FROM ".$tabella." ORDER BY data DESC";
	$risposta=mysql_query($sql);
	while($riga=mysql_fetch_array($risposta)){
		echo "lat: ".$riga['latitudine'].", lon: ".$riga['longitudine']."<br />";
	}

	mysql_close($conn);
?>