<?php
	include("lib/config.php");
	include("lib/funzioni.php");

	$tabella="tab_regid";

	if(isset($_POST['regid'])){

		$regid=$_POST['regid'];
		$androidid=$_POST['androidid'];

		$array1=['data', 'regid', 'androidid'];
		$array2=[date("Y-m-d H:i:s"), $regid, $androidid];

		$where="";

		$controllo=queryGo($tabella, $array1, $array2, $where);

		if($controllo==1){
			echo "1";
		}
		else{
			echo "000";
		}
	}
 
?>