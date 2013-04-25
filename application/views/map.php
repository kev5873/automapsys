<?php header("Content-Type: text/plain"); ?>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <style type="text/css">
        body{
        	background-color:black;
        	color: white;
        	font-family: Tahoma, Arial, Helvetica, sans-serif;
        }
        .mini{
        	width: 32px;
        	height: 32px;
        }
        .miniC{
        	width: 24px;
        	height: 24px;
        }
        #map-canvas { height: 75% }
        </style>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqCjOtUb9nrWCFPKpQ6AFkwOSH766zvc8&sensor=true"></script>
        <script type="text/javascript" src="/a/i/jquery-1.9.1.min.js"></script>
        <script type="text/javascript">
        var map;
        var markersArray = [];
        var lineArray    = [];
        var windowsArray = [];

        function initialize() {
            var mapOptions = {
                center: new google.maps.LatLng(40.712472, -73.940105),
                zoom: 11,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map-canvas"),mapOptions);

            $.ajax({
              url: '/map/grab',
              data:{id: '<?=$line?>', direction:'<?=$direction?>'},
              dataType: 'json',
              success:function(data){
                var color = data[0].color;
                for(i=0;i<data.length;i++) {
                    var myLatLng = new google.maps.LatLng(data[i].coordinatex, data[i].coorrdinatey);
                    addMarker(myLatLng, data[i].station_name);
                    //alert(myLatLng);
                }

                  var lineSymbol = {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 3,
                    strokeColor: '#FFFFFF'
                  };

                  var flightPath2 = new google.maps.Polyline({
                    path: lineArray,
                    strokeColor: '#000000',
                    strokeOpacity: 1.0,
                    strokeWeight: 7
                  });

                  var flightPath = new google.maps.Polyline({
                    path: lineArray,
                    strokeColor: color,
                    strokeOpacity: 1.0,
                    strokeWeight: 5,
                    icons: [{
                      icon: lineSymbol,
                      offset: '100%'
                    }],
                  });

                  flightPath2.setMap(map);
                  flightPath.setMap(map);

                var count = 0;
                window.setInterval(function() {
                  count = (count + 1) % 200;

                  var icons = flightPath.get('icons');
                  icons[0].offset = (count / 2) + '%';
                  flightPath.set('icons', icons);
                }, 50);



             }
             });

          }

        function addMarker(location, station_name) {
    /*    var image = new google.maps.MarkerImage('/a/i/stationstop12px.png',
        // This marker is 20 pixels wide by 32 pixels tall.
        null,
        // The origin for this image is 0,0.
        null,//new google.maps.Point(0,0),
        // The anchor for this image is the base of the flagpole at 0,32.
        new google.maps.Point(4,-4), 
        // Resize the image 8x8 pixel
        new google.maps.Size(8, 8)
    );

var marker = new google.maps.Marker({
        position: location,
        map: map,
        icon: image,
    });*/

        //==============================================
            var image = '/a/i/stationstop12px.png';

            //var image = new google.maps.MarkerImage('/a/i/stationstop12px.png', null, new google.maps.Point(0,0);
            var infowindow = new google.maps.InfoWindow({
                content: station_name,
                color:'#FFFFFF'
            });
            
            var marker = new google.maps.Marker({
                position: location,
                map: map,
                icon: image,
            });

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map,marker);
            });

            markersArray.push(marker);
            windowsArray.push(infowindow);
            lineArray.push(location);

        }

          google.maps.event.addDomListener(window, 'load', initialize);
        </script>
    </head>
    <body>
    	<div style="text-align:center; width: 920px; margin-left: auto; margin-right: auto; border: solid 1px black;">
    		<div style="padding: 10px; font-size: 16pt; text-decoration: underline;">New York City Subway Service Status</div>
    		<table border="0" cellspacing="0">
    			<tr>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    				<td style="text-align:center;"><img src="<?=URL::base()?>a/i/caution.png" class="miniC"/></td>
    			</tr>
    			<tr>
    				<td><a href="?id=1"?><img src="<?=URL::base()?>a/bullet/1.png" class="mini"/></a></td>
    				<td><a href="?id=2"?><img src="<?=URL::base()?>a/bullet/2.png" class="mini"/></a></td>
    				<td><a href="?id=3"?><img src="<?=URL::base()?>a/bullet/3.png" class="mini"/></a></td>
    				<td><a href="?id=4"?><img src="<?=URL::base()?>a/bullet/4.png" class="mini"/></a></td>
    				<td><a href="?id=5"?><img src="<?=URL::base()?>a/bullet/5.png" class="mini"/></a></td>
    				<td><a href="?id=6"?><img src="<?=URL::base()?>a/bullet/6.png" class="mini"/></a></td>
    				<td><a href="?id=8"?><img src="<?=URL::base()?>a/bullet/6D.png" class="mini"/></a></td>
    				<td><a href="?id=7"?><img src="<?=URL::base()?>a/bullet/7.png" class="mini"/></a></td>
    				<td><a href="?id=9"?><img src="<?=URL::base()?>a/bullet/7D.png" class="mini"/></a></td>
    				<td><a href="?id=24"?><img src="<?=URL::base()?>a/bullet/S.png" class="mini"/></a></td>
    				<td><a href="?id=10"?><img src="<?=URL::base()?>a/bullet/A.png" class="mini"/></a></td>
    				<td><a href="?id=11"?><img src="<?=URL::base()?>a/bullet/C.png" class="mini"/></a></td>
    				<td><a href="?id=12"?><img src="<?=URL::base()?>a/bullet/E.png" class="mini"/></a></td>
    				<td><a href="?id=13"?><img src="<?=URL::base()?>a/bullet/B.png" class="mini"/></a></td>
    				<td><a href="?id=14"?><img src="<?=URL::base()?>a/bullet/D.png" class="mini"/></a></td>
    				<td><a href="?id=15"?><img src="<?=URL::base()?>a/bullet/F.png" class="mini"/></a></td>
    				<td><a href="?id=16"?><img src="<?=URL::base()?>a/bullet/M.png" class="mini"/></a></td>
    				<td><a href="?id=20"?><img src="<?=URL::base()?>a/bullet/G.png" class="mini"/></a></td>
    				<td><a href="?id=22"?><img src="<?=URL::base()?>a/bullet/J.png" class="mini"/></a></td>
    				<td><a href="?id=23"?><img src="<?=URL::base()?>a/bullet/Z.png" class="mini"/></a></td>
    				<td><a href="?id=21"?><img src="<?=URL::base()?>a/bullet/L.png" class="mini"/></a></td>
    				<td><a href="?id=18"?><img src="<?=URL::base()?>a/bullet/N.png" class="mini"/></a></td>
    				<td><a href="?id=17"?><img src="<?=URL::base()?>a/bullet/Q.png" class="mini"/></a></td>
    				<td><a href="?id=19"?><img src="<?=URL::base()?>a/bullet/R.png" class="mini"/></a></td>
    				<td><a href="?id=25"?><img src="<?=URL::base()?>a/bullet/S.png" class="mini"/></a></td>
    			</tr>
    			<tr>
    				<td colspan="25" style="text-align:center;">
    					<img src="<?= URL::base(); ?>a/bullet/<?= $routeDesignation; ?>.png" style="padding-bottom: 20px; vertical-align: middle;"/><span style="font-size: 18pt;"><?= $routeDetail; ?></span>
    				</td>
    			</tr>
    		</table>
    	</div>
    	<div style="text-align:center; width: 920px; margin-left: auto; margin-right: auto; border: solid 1px black;">
            <div id="map-canvas" style="color: #000000;"/>
    	</div>
    </body>
</html>