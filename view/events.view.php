<!DOCTYPE html>
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
			<br>
			<?php
			foreach($eventlist as $event){
				?>
			<table class="featured-node" style="width: 300px; float: left; margin: 5px;">
				<thead>
					<td style="font-size: 14px; font-weight: bold;">
						<?php 
						print($event->getName());
						print("<br>");
						print($event->getTime());
						?>
					</td>
					<td style="text-align: right; font-size: 14px; font-weight: bold; color: #8C2B2B;">
						<?php print(number_format($event->getTotalValue())); ?>
					</td>
				</thead>
				<tr>
					<td style="vertical-align: top">
					<?php 
					foreach($event->getParticipants() as $participant){
						print($participant->getName());
						print("<br >");
					} 
						?>
					</td>
					
					<td style="vertical-align: top">
						<table style="width: 100%">
						<?php 
							foreach($event->getDrops() as $drop){
								?>
								<tr>
									<td><?php print($drop->getName()); ?></td>
									<td style="text-align: right; font-weight: bold;padding-left: 5px;">
										<?php print(number_format($drop->getDropValue())); ?>
									</td>
								</tr>
								<?php
							}
						?>
						</table>
					
					</td>
				</tr>
			</table>
			<?php
			}
			?>
			
			
		</div>
		
		
		<?php include('footer.partial.view.php') ?>
	</body>
</html>