<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Find a league near you." />
<title>UK Pinball League - Map</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<!-- Leafet.js includes -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<style>
	#map {
		position: relative;
		height: 800px;
		width: 100%;
	}
</style>

<!-- Header and menu -->
<?php 
	include("includes/header.inc"); 
?>

<div class="panel">

	<div id="map"></div>

	<script>
		var map = L.map('map').setView([53.5, -3], 7);
		
		map.options.maxZoom = 11;
		map.options.minZoom = 6;
		
		L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/light-v11/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoibmhpbGwiLCJhIjoiY2tmZHdkN2t4MW41eTJ4b2ZzbGRrbTZ5cSJ9.6NgSdrjSbrtfFk5ozMdFdw', {
		attribution: 'Imagery &copy; <a href="http://mapbox.com">Mapbox</a>'
		}).addTo(map);
		
		// Fetch league meet data from api style endpoint
		fetch('api/league_meets.php')
			.then(response => response.json())
			.then(data => {
				data.forEach(function (leagueMeet) {

					if (leagueMeet.latitude === null) return;

					switch (leagueMeet.region_id) {
						case 1: // Northern
							circleColour = 'purple';
							break;
						case 2: // South West
							circleColour = 'green';
							break;
						case 3: // Midlands
							circleColour = 'red';
							break;
						case 4: // London SE
							circleColour = 'blue';
							break;
						case 5: // Irish
							circleColour = '#B87333';
							break;
						case 6: // Scottish
							circleColour = 'magenta';
							break;
						case 7: // East Anglia
							circleColour = 'orange';
							break;
						case 8: // South Wales
							circleColour = '#87CEEB';
							break;
						default:
							circleColour = "gray";
					}

					L.circle([leagueMeet.latitude, leagueMeet.longitude], {
						color: circleColour,
						fillColor: circleColour,
						fillOpacity: 0.10,
						radius: 3000 + (leagueMeet.attendance / 5) * 1000
					}).addTo(map);
				});
			})
			.catch(error => console.error('Error fetching data:', error));
		
	</script>
</div>

<!-- Footer and menu -->
<?php include("includes/footer.inc"); ?>

</body>
</html>