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
        </style>
    </head>
    <body>
    	<div style="text-align:center; width: 920px; margin-left: auto; margin-right: auto; border: solid 1px black;">
    		<div style="padding: 10px; font-size: 16pt; text-decoration: underline;">New York City Subway Service Status</div>
    		<table border="0" cellspacing="0">
    			<tr>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
    				<td style="text-align:center;"></td>
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
    		<div style="width: 600px; float:left; margin-left: 0; margin-top: -5px;">
		        <table border="0" cellspacing="0">
		        	<?= $lineData; ?>
		        </table>
			</div>
			<div style="float:left; position: relative; width: 320px">
				<table>
					<tr>
						<td style="font-size: 14pt; overflow: hidden;" colspan="2">
							Currently Displaying : Downtown
					</tr>
					<tr>
						<td><a href="<?=URL::base()?>?id=<?= $line; ?>&direction=uptown"><img src="<?=URL::base()?>a/i/up-arrow.png" class="mini"/></a></td>
						<td style="font-size: 14pt;">
							<img src="<?=URL::base()?>a/bullet/<?= $routeDesignation; ?>.png" style="padding-bottom: 5px; vertical-align: middle;" class="mini"/>
							Uptown</td>
					</tr>
					<tr>
						<td><a href="<?=URL::base()?>?id=<?= $line; ?>&direction=downtown"><img src="<?=URL::base()?>a/i/down-arrow.png" class="mini"/></a></td>
						<td style="font-size: 14pt;">
							<img src="<?=URL::base()?>a/bullet/<?= $routeDesignation; ?>.png" style="padding-bottom: 5px; vertical-align: middle;" class="mini"/>
							Downtown</td>
					</tr>
					<tr>
						<td style="font-size: 14pt; overflow: hidden;" colspan="2">
							Advisory goes here
						</td>
					</tr>
				</table>
			</div>
    	</div>
    </body>
</html>