<html>
	<head>
		<title>LandingsPage</title>
		<link rel="stylesheet" type="text/css" href="../assets/site_style.css">
		<script type="text/javascript" src="../assets/site_layout.js"></script>
		<script>
			window.onload = function(){
				initializePage();
				var butAddUser = document.getElementById("addUserButton");
				butAddUser.onclick = function(event) {
					popupAddUser("block");
				}
				var butAddUser = document.getElementById("closePopUp");
				butAddUser.onclick = function(event) {
					popupAddUser("none");
				}
			}
			window.onresize = initializePage;
			
			function popupAddUser(state){
				var popup = document.getElementById("popAddUser");
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
				<div class="popup" id="popAddUser">
				<h1 id="closePopUp" style="position: relative; float: right; margin-top: -40px; color: #FFF; background: #000;">[x]CLOSE</h1>
					<form action="users.php" method="post">
						<input name= "addUser" type="text">
						<input type="submit" class="myButton" value="Add User">
					</form>
				</div>
			</div>
			
			<input id="addUserButton" type="submit" class="myButton" value="Add User">
			<div class="featured-node" style="margin-top: 20px;">
			<table>
				<tr>
					<td style="width: 20px; padding: 2px 5px;"></td>
					<td style="padding: 2px 5px;">Nickname</td>
					<td style="padding: 2px 5px;">Mailname</td>
				</tr>
				<?php
					$i = 0;
					foreach($userlist as $user){
						$i++;
						?>
						<tr>
							<td style="width: 20px; padding: 2px 5px;"><?php print($i);?></td>
							<td style="padding: 2px 5px;"><?php print($user->getName());?></td>
							<td style="padding: 2px 5px;"><?php print($user->getMailName());?></td>
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