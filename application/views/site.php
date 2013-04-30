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
        <script type="text/javascript" src="/a/i/jquery-1.9.1.min.js"></script>
        <script type="text/javascript">

        function sendEmail()
        {
            console.log( $('#email').val() );
            $.ajax({
                url: '/ams/email',
                data:{email: $('#email').val() },
                success:function(data){
                    console.log(data);
                    if(data == '1')
                    {
                        alert("Thank you for entering your email into our database to recieve alerts");
                    }
                    else
                    {
                        alert("There was an error inserting your email into our system, we might already have you, otherwise please try again.");
                    }
                }
            });
        }

        </script>
    </head>
    <body>
    	<div style="text-align:center; width: 920px; margin-left: auto; margin-right: auto; border: solid 1px black;">
    		<div style="padding: 10px; font-size: 16pt; text-decoration: underline;">New York City Subway Service Status</div>
    		<table border="0" cellspacing="0">
    			<tr>
    				<td style="text-align:center;"><?=$status1; ?></td>
    				<td style="text-align:center;"><?=$status2; ?></td>
    				<td style="text-align:center;"><?=$status3; ?></td>
    				<td style="text-align:center;"><?=$status4; ?></td>
    				<td style="text-align:center;"><?=$status5; ?></td>
    				<td style="text-align:center;"><?=$status6; ?></td>
    				<td style="text-align:center;"><?=$status8; ?></td>
    				<td style="text-align:center;"><?=$status7; ?></td>
    				<td style="text-align:center;"><?=$status9; ?></td>
    				<td style="text-align:center;"><?=$status24; ?></td>
    				<td style="text-align:center;"><?=$status10; ?></td>
    				<td style="text-align:center;"><?=$status11; ?></td>
    				<td style="text-align:center;"><?=$status12; ?></td>
    				<td style="text-align:center;"><?=$status13; ?></td>
    				<td style="text-align:center;"><?=$status14; ?></td>
    				<td style="text-align:center;"><?=$status15; ?></td>
    				<td style="text-align:center;"><?=$status16; ?></td>
    				<td style="text-align:center;"><?=$status20; ?></td>
    				<td style="text-align:center;"><?=$status22; ?></td>
    				<td style="text-align:center;"><?=$status23; ?></td>
    				<td style="text-align:center;"><?=$status21; ?></td>
    				<td style="text-align:center;"><?=$status18; ?></td>
    				<td style="text-align:center;"><?=$status17; ?></td>
    				<td style="text-align:center;"><?=$status19; ?></td>
    				<td style="text-align:center;"><?=$status25; ?></td>
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
		        </table><br/><br/><br/><br/><br/>

                
                
                <p style="text-align: right;">
                Sign up for email alerts : <input type="text" id="email"/><span onclick="sendEmail()"> 
                <br/><br/>
                <img style = 'position:relative; right:19%;'src="<?=URL::base()?>a/bullet/submit.png"/></span>
                </p>
                
                <p style= 'position:relative; left:25%;'>
                <font size="2"><br/><br/>


                                    All trademarks and copyrights held by respective owners. 
                                Automated Mapping System ® is a Registered Trademarks of AMS TEAM. 
                                        © Automated Mapping System all rights reserved

                </font></p>
    
			</div>
			<div style="float:left; position: relative; width: 320px">
				<table>
					<tr>
						<td style="font-size: 14pt; overflow: hidden;" colspan="2">
							Currently Displaying : <?= $direction; ?>

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
							Advisory goes here: <?=$advisory?>
						</td>
					</tr>

                    <tr>
                        <td style="font-size: 14pt; overflow: hidden;" colspan="2">

                        </td>
                    </tr>

				</table>
			</div>
    	</div>
    </body>
