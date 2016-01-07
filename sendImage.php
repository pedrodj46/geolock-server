<?php
	include("lib/config.php");
	include("lib/funzioni.php");
	
	$immagine=$_POST['immagine'];
	$nomeImmagine=$_POST['nomeImmagine'];

	$decodeImage=base64_decode($immagine);

	if(file_put_contents("img/".$nomeImmagine.".jpg", $decodeImage))
		echo "1";
	else
		echo "0";

	/*

	$immagine=$_POST['immagine'];
	$nomeImmagine=$_POST['nomeImmagine'];

	$tabella="tab_prova";

	$conn=dbConnect();

	$decodeImage=base64_decode($immagine);

	$array1=['data', 'elemento'];
	$array2=[date("Y-m-d H:i:s"), addslashes($nomeImmagine." - ".$decodeImage)];

	$where="";

	$controllo=queryGo($tabella, $array1, $array2, $where);

	mysql_close($conn);

	if(file_put_contents("img/".$nomeImmagine.".jpg", $decodeImage))
		echo "1";
	else
		echo "0";

	*/
?>