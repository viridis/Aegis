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
		
		<div class="content" style="text-align: center;">
		<div class="featured-node" style="margin-top: 20px;">
			<table>
				<tr>
					<td style="width: 20px; padding: 2px 5px;"></td>
					<td style="padding: 2px 5px; font-weight: bold;">Name</td>
					<td style="padding: 2px 5px; font-weight: bold">To Be Paid Out</td>
					<td></td>
				</tr>
				<?php
					$i = 0;
					foreach($payoutList as $payOut){
						if($payOut[1] != 0){
							$i++;
							?>
							<tr>
								<td style="width: 20px; padding: 2px 5px;"><?php print($i);?></td>
								<td style="padding: 2px 5px;"><?php print($payOut[0]->getName());?></td>
								<td style="padding: 2px 5px;text-align: right;font-size: 14px; font-weight: bold; color: #8C2B2B;">
								<?php print(number_format($payOut[1]));?>
								</td>
								<td style="width: 50px; padding: 2px 5px;">
									<a href="./payout.php?payout=<?php print($payOut[0]->getUserID()); ?>" style="text-decoration: none;">
										Pay Out
									</a>
								</td>
							</tr>									
							<?php
						}
					}
					?>
			</table>
		</div>
		
		</div>
		
		
		<?php include('footer.partial.view.php') ?>
	</body>
</html>