<!DOCTYPE html>
<html>
	<head>
		<title>LandingsPage</title>
		<link rel="stylesheet" type="text/css" href="../assets/site_style.css">
		<script type="text/javascript" src="../assets/site_layout.js"></script>
		<script>
			window.onload = function(){
				initializePage();
			}
			
			window.onresize = initializePage;
		</script>
	</head>
	
	<body>
		<?php include('navbar.partial.view.php') ?>
		
		<div class="content">
			<div class="featured">
				<div class="featured-node" style="width: 200px;">
					Short description of SQI and Seals and stuff. Summing up Prontera, Manuk, Niff and Yuno Seal, just to fill up the space.<br>
					<br>
                    <div class="buttonContainer">
					    <a href="" class="myButton">Read More ...</a>
                    </div>
				</div>
				<div class="featured-node" style="width: 200px;">
					Generic information about Game Master Challenges for when this gets added.<br>
					<br>
					<br>
                    <div class="buttonContainer">
                        <a href="" class="myButton">Read More ...</a>
                    </div>
				</div>
				<div class="featured-node" style="width: 200px;">
					Absolutely no clue what to put here, besides GMC and Seals I dont know what the site will provide.<br>
					<br>
                    <div class="buttonContainer">
                        <a href="" class="myButton">Read More ...</a>
                    </div>
				</div>
			</div>
		</div>
		
		
		<?php include('footer.partial.view.php') ?>
	</body>
</html>