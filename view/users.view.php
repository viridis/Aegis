<!DOCTYPE html>
<html>
	<head>
		<title>LandingsPage</title>
        <?php include('headers.partial.view.php') ?>
		<script>
			window.onload = function(){
				initializePage();
                document.getElementById("addUserButton").onclick = function(event) {
                    popup('popAddUser','block');
                    setFocus('addUser');
                }
                document.getElementById("closePopUp").onclick = function(event) {
                    popup('popAddUser','none');
                }
                document.getElementById("popupWrapper").onclick = function(event) {
                    popup('popAddUser','none');
                }

			}
			window.onresize = initializePage;

            //close popup boxes using ESC key.
            document.onkeydown = function(event) {
                event = event || window.event;
                if (event.keyCode == 27) {
                    popup('popAddUser','none');
                }
            }
		</script>
	</head>
	
	<body>
		<?php include('navbar.partial.view.php') ?>
		
		<div class="content" style="text-align: center;">
			<div class="popupWrapper" id="popupWrapper"></div>
            <div class="popup" id="popAddUser">
                <h1 id="closePopUp" class="closeButton">[x]CLOSE</h1>
                <form action="users.php" method="post">
                    <input name="addUser" id="addUser" class="inputText" type="text" placeholder="Username">
                    <input type="submit" class="mySubmitButton" value="Add User">
                </form>
            </div>
            <?php if(isset($notification)): ?>
                <br />
                <div class="<?php print($notification['type']); ?>">
                    <h1><?php print($notification['message']); ?></h1>
                </div>
                <br />
            <?php endif; ?>
            <div class="buttonContainer">
			    <input id="addUserButton" type="submit" class="myButton" value="Add User">
            </div>
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