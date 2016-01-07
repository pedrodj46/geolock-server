<?php
	include("lib/config.php");
	include("lib/funzioni.php");

	$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');

	$tabella="tab_users";

	$count=0;
	$i=0;

	$conn=dbConnect();
	$sql="SELECT * FROM ".$tabella;
	$risposta1=mysql_query($sql);
	while($riga1=mysql_fetch_array($risposta1)){
		$sql="SELECT * FROM ".$tabella."_position WHERE id_utente=".$riga1['id']." ORDER BY data DESC";
		$risposta=mysql_query($sql);
		while($riga=mysql_fetch_array($risposta)){
			if($riga['accuracy']>0 && $riga['accuracy']<60){
				echo "lat: ".$riga['latitudine'].", lon: ".$riga['longitudine']."<br />";
				$str.="{lat: ".$riga['latitudine'].", lng: ".$riga['longitudine']."},";
				$mediaLat+=$riga['latitudine'];
				$mediaLon+=$riga['longitudine'];
				$count++;
			}
		}
		$arr[$i]=$str;
		$str="";
		$i++;
	}

	$mediaLat/=$count;
	$mediaLon/=$count;

	mysql_close($conn);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Simple Polylines</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>

// This example creates a 2-pixel-wide red polyline showing the path of William
// Kingsford Smith's first trans-Pacific flight between Oakland, CA, and
// Brisbane, Australia.

function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 15,
    center: {lat: <?php echo $mediaLat; ?>, lng: <?php echo $mediaLon; ?>},
    mapTypeId: google.maps.MapTypeId.TERRAIN
  });

  <?php
  	for($j=0;$j<$i;$j++){
  ?>

  var flightPlanCoordinates<?php echo $j; ?> = [
    <?php
    	$str=$arr[$j];
    	echo substr($str,0,-1);
    ?>
  ];
  var flightPath<?php echo $j; ?> = new google.maps.Polyline({
    path: flightPlanCoordinates<?php echo $j; ?>,
    geodesic: true,
    strokeColor: '#<?php echo $rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)]; ?>',
    strokeOpacity: 1.0,
    strokeWeight: 2
  });

  flightPath<?php echo $j; ?>.setMap(map);

  <?php
  	}
  ?>
}

    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?signed_in=true&callback=initMap"></script>
  </body>
</html>