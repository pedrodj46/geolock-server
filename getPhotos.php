<?php
	header('Content-Type: application/json');

	include("lib/config.php");
	include("lib/funzioni.php");

	$tabella="tab_images";

	if(isset($_GET['idU']) && (int)$_GET['idU']!=0){

		$idU=(int)$_GET['idU'];

		$i=0;
		$json="";

		$conn=dbConnect();
		$sql="SELECT * FROM ".$tabella." WHERE id_utente=".$idU." ORDER BY data DESC";
		$risposta=mysql_query($sql) or die("Erorre ".mysql_error());
		while($riga=mysql_fetch_array($risposta)){

			$data=date("d/m/Y", strtotime($riga['data']))." alle ".date("H:m", strtotime($riga['data']));

			if($i!=0){
				$json.=",";
			}

			$json.='{"nome":"'.$riga['nome'].'", "data":"'.$riga['data'].'", "idF":"'.$riga['id'].'"}';
			$i++;
		}
		$json='['.$json.']';

		mysql_close($conn);

		echo $json;

	}
	else if(isset($_GET['idF']) && (int)$_GET['idF']!=0){
		$idF=(int)$_GET['idF'];

		$json="";

		$conn=dbConnect();
		$sql="SELECT * FROM ".$tabella." WHERE id=".$idF;
		$risposta=mysql_query($sql) or die("Errore ".mysql_error());
		$riga=mysql_fetch_array($risposta);

		$json.='[{"nome":"'.$riga['nome'].'", "lat":"'.$riga['lat'].'", "lon":"'.$riga['lon'].'", "indirizzo":"'.$riga['indirizzo'].'", "dirUtente":"'.$riga['dirUtente'].'", "angUtente":"'.$riga['angUtente'].'", "dirSogg":"'.$riga['dirSogg'].'", "angSogg":"'.$riga['angSogg'].'", "distanza":"'.$riga['distanza'].'", "aperta":"'.$riga['aperta'].'", "messaggio":"'.$riga['messaggio'].'"}]';

		mysql_close($conn);

		echo $json;

	}
?>