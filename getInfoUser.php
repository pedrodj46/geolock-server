<?php
	include("lib/config.php");
	include("lib/funzioni.php");

	$tabella="tab_users";

	$json=json_decode($_POST['jsonInfoUser']);

	$idU=$json->idU;

	$conn=dbConnect();
	$sql="SELECT * FROM ".$tabella." WHERE id=".$idU;
	$risposta=mysql_query($sql) or die("Erorre ".mysql_error());
	$riga=mysql_fetch_array($risposta);

	$str=$riga['nome'].",".$riga['img'];

	mysql_close($conn);

	echo $str;
?>