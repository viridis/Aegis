<html>
	<head>
		<title>LandingsPage</title>
		<link rel="stylesheet" type="text/css" href="../assets/site_style.css">
		<script type="text/javascript" src="../assets/site_layout.js"></script>
		<script>
			window.onload = function(){
				initializePage();
				var butAddItem = document.getElementById("addItemButton");
				butAddItem.onclick = function(event) {
					popupAddItem("block");
				}
				var butAddItem = document.getElementById("closePopUp");
				butAddItem.onclick = function(event) {
					popupAddItem("none");
				}
			}
			window.onresize = initializePage;
			
			function popupAddItem(state){
				var popup = document.getElementById("popAddItem");
				var popupWrapper = document.getElementById("popupWrapper");
				popup.style.display = state;
				popupWrapper.style.display = state;
			}
			
		</script>
	</head>
	
	<body>
		<?php include('navbar.partial.view.php') ?>
		
		<div class="content" style="text-align: center;">
			<div class="popupWrapper" id="popupWrapper">
				
				<div class="popup" id="popAddItem">
				<h1 id="closePopUp" style="position: relative; float: right; margin-top: -40px; color: #FFF; background: #000;">[x]CLOSE</h1>
					<form action="items.php" method="post">
						<input name= "addItem" type="text">
						<input type="submit" class="myButton" value="Add Item">
					</form>
				</div>
			</div>
						
			<input id="addItemButton" type="submit" class="myButton" value="Add Item">
			<div class="featured-node" style="margin-top: 20px;">
			<table>
				<tr>
					<td style="width: 20px; padding: 2px 5px;"></td>
					<td style="padding: 2px 5px;">Registered Items</td>
				</tr>
				<?php
					$i = 0;
					foreach($itemlist as $item){
						$i++;
						?>
						<tr>
							<td style="width: 20px; padding: 2px 5px;"><?php print($i);?></td>
							<td style="padding: 2px 5px;"><?php print($item->getName());?></td>
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