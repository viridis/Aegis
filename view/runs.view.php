<html>
	<head>
		<title>LandingsPage</title>
		<link rel="stylesheet" type="text/css" href="../assets/site_style.css">
		<script type="text/javascript" src="../assets/site_layout.js"></script>
		<script>
			window.onload = function(){
				initializePage();
				var butAddRun = document.getElementById("addRunButton");
				butAddRun.onclick = function(event) {
					popupAddItem("block");
				}
				var butAddRun = document.getElementById("closePopUp");
				butAddRun.onclick = function(event) {
					popupAddItem("none");
				}
			}
			window.onresize = initializePage;
			
			function popupAddItem(state){
				var popup = document.getElementById("popAddRun");
				var popupWrapper = document.getElementById("popupWrapper");
				popup.style.display = state;
				popupWrapper.style.display = state;
			}
		</script>
	</head>
	
	<body>
		<?php include('navbar.partial.view.php') ?>
		
		<div class="content" style="text-align: center;">
			
			<?php 
			if($editing == 1){
				?>
				<br />
				<div style="text-align: left;" class="featured-node-copy">
					<?php 
					print($run->getName());
					print("<br>");
					print($run->getTime());
					?>
				</div>
				<br />
				<br />
				<?php
			}
			?>
			
			<div class="popupWrapper" id="popupWrapper">
				<div class="popup" id="popAddRun">
				<h1 id="closePopUp" style="position: relative; float: right; margin-top: -40px; color: #FFF; background: #000;">[x]CLOSE</h1>
					<form action="runs.php?addRun=1" method="post">
						<input name= "runName" type="text">
						<input name="runDate" type="date">
						<input type="submit" class="myButton" value="Add Run">
					</form>
				</div>
			</div>
			<input id="addRunButton" type="submit" class="myButton" value="Add Run">
		
			<div class="featured-node" style="margin-top: 20px;">
			<table>
				<tr>
					<td style="width: 20px; padding: 2px 5px;"></td>
					<td style="padding: 2px 5px; font-weight: bold;">Run</td>
					<td style="padding: 2px 5px; font-weight: bold">Date</td>
					<td></td>
				</tr>
				<?php
					$i = 0;
					foreach($eventlist as $event){
						$i++;
						?>
						<tr>
							<td style="width: 20px; padding: 2px 5px;"><?php print($i);?></td>
							<td style="padding: 2px 5px;">
								<a href="./runs.php?editrun=<?php print($event->getID()) ?>">
									<?php print($event->getName());?>
								</a>
							</td>
							<td style="padding: 2px 5px;"><?php print($event->getTime());?></td>
						</tr>
						<?php
					}
					?>
			</table>
		</div>
		
		</div>
		
		
		<?php include('footer.partial.view.php') ?>
	</body>
</html>