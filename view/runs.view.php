<!DOCTYPE html>
<html>
	<head>
		<title>LandingsPage</title>
        <?php include('headers.partial.view.php') ?>
		<script>
			window.onload = function(){
				initializePage();
				document.getElementById("addRunButton").onclick = function(event) {
                    popup('popAddRun','block');
                    setFocus('runName');
				}
				document.getElementById("closePopUp").onclick = function(event) {
                    popup('popAddRun', 'none');
				}
                document.getElementById("popupWrapper").onclick = function(event) {
                    popup('popAddRun','none');
                }
			}
			window.onresize = initializePage;

            //close popup boxes using ESC key.
            document.onkeydown = function(evt) {
                evt = evt || window.event;
                if (evt.keyCode == 27) {
                    popup('popAddRun', 'none');
                }
            }
			
			<?php if($editing == 1){ ?>
			function DragOver(event)
			{
				var id = event.dataTransfer.getData("Text");
				var stripped = id.split("_");
				event.preventDefault();
				if(event.target.nodeName == "LI"){
					event.target.parentNode.parentNode.style.background ="#6AA121";
				}
				else if(event.target.nodeName == "UL"){
					event.target.parentNode.style.background ="#6AA121";
				}
                else if(event.target.nodeName == "IMG"){
                    event.target.parentNode.style.background ="#8C2B2B";
                }
                else{
                    event.target.style.background ="#8C2B2B";
                }
			}
			
			function DragLeave(event)
			{
				event.preventDefault();
				if(event.target.nodeName == "UL"){
					event.target.parentNode.style.background ="";
				}
				else if(event.target.nodeName == "LI"){
					event.target.parentNode.parentNode.style.background ="";
				}
				else if(event.target.nodeName == "IMG"){
					event.target.parentNode.style.background ="";
				}
				else{
					event.target.style.background ="";
				}
			}
			
			function doDrop(event)
			{
				var draggedElementID = event.dataTransfer.getData("Text");
				var stripped = draggedElementID.split("_");
				if(event.target.nodeName == "LI"){
					var unorderedList = event.target.parentNode;
				}
				else if(event.target.nodeName == "UL"){
					var unorderedList = event.target;
				}
                else if(event.target.nodeName == "IMG"){
                    var unorderedList = event.target;
                }
                else{
                    var unorderedList = event.target.firstElementChild;
                }

				if(stripped[0] == unorderedList.id){ //check if dragging in the right section
					var allowedToDrop = false;
					if(stripped[0] == "users"){
						var found = userIdInElementChildren("db_users_", stripped[1], unorderedList);
                        allowedToDrop = !found;
					}
					else{
                        allowedToDrop = true;
					}
					if(allowedToDrop){
						var li = document.createElement('li');
						li.id = "db_"+ stripped[0] +"_id_temp";
						var text = document.getElementById(draggedElementID).innerHTML;
						var item = document.createTextNode(text);
						li.appendChild(item);
						unorderedList.appendChild(li);
						unorderedList.parentNode.style.background ="";
						makeRequest("runs.php?editrun=<?php print($run->getID()); ?>&add="+ stripped[0] +"&id="+ stripped[1]);
					}
					else{
						unorderedList.parentNode.style.background ="";
					}
				}
                else if(stripped[0] == 'db' && unorderedList.id == 'bin'){
                    //delete on success.
                    unorderedList.parentNode.style.background ="";
                    makeRequest("runs.php?editrun=<?php print($run->getID()); ?>&delete="+ stripped[1] +"&id="+ stripped[2]);
                }
				else if (stripped[0] != unorderedList.id) {
					unorderedList.parentNode.style.background ="";
				}
				event.preventDefault();
			}
			
			function userIdInElementChildren(pre, id, domElement)
			{
				for(i=0; i<domElement.childNodes.length; i+=1) {
					if(domElement.childNodes[i].id == pre + id){
						return true;
					}
				}
				return false;
				
			}
			
			function drag(event)
			{
				event.dataTransfer.effectAllowed='move';
				event.dataTransfer.setData("Text",event.target.id);
				return true;
			}

			function makeRequest(url)
			{
				var httpRequest;
				if (window.XMLHttpRequest) { // Mozilla, Safari, ...
					httpRequest = new XMLHttpRequest();
				}
				else if (window.ActiveXObject) { // IE
					try {
						httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
					} 
					catch (e) {
						try {
						httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
						} 
						catch (e) {}
					}
				}

				if (!httpRequest) {
					alert('Giving up :( Cannot create an XMLHTTP instance');
					return false;
				}
				httpRequest.onreadystatechange = function(){
					serverResponse(httpRequest);
				};
				httpRequest.open('POST', url, true);
				httpRequest.send ();
			}
			
			function serverResponse(httpRequest)
			{
				if (httpRequest.readyState === 4) {
					if (httpRequest.status === 200) {
						var result = JSON.parse(httpRequest.responseText);
                        if(result['action'] == 'added'){
                            element = document.getElementById("db_"+ result["database"] +"_id_temp");
                            element.id = "db_"+ result["database"] +"_"+ result["id"];
                            element.draggable = true;
                            element.addEventListener("dragstart", function(event){return drag(event)});
                        }
                        else if(result['action'] == 'deleted'){
                            element = document.getElementById("db_"+ result["database"] +"_"+ result["id"]);
                            console.log(result["id"]);
                            element.parentNode.removeChild(element);
                        }
					}
					else {
						alert('There was a problem with the request.');
					}
				}
			}
			<?php } ?>
		</script>
	</head>
	
	<body>
		<?php include('navbar.partial.view.php') ?>
		
		<div class="content" style="text-align: center;">
			
			<?php if($editing == 1):?>
				<br />
				<div style="text-align: left;" class="featured-node-copy">
					<table style="width: 100%;" border=0>
						<thead>
							<tr>
								<td colspan="3" style="font-size: 14px; font-weight: bold;">
								<?php 
								print($run->getName());
								print(" -- ");
								print($run->getTime());
								?>
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="dropSpaceBin" ondragover="return DragOver(event)" ondragleave="return DragLeave(event)" ondrop="return doDrop(event)" rowspan="3">
									<img id="bin" src="../assets/bin.png" style="height: 50px;" />
								</td>
								<td class="dropSpace">
									<span style="font-size: 12px; font-weight: bold;">Participants</span>
									<ul id="users" class="dropable_lists" ondragover="return DragOver(event)" ondragleave="return DragLeave(event)" ondrop="return doDrop(event)">
										<?php
										foreach($run->getParticipants() as $participant){
											?>
											<li id="db_users_<?php print($participant->getUserID());?>" draggable='true' ondragstart='return drag(event)'>
												<?php print($participant->getName());?>
											</li>
										<?php
										}
										?>
									</ul>
									<br />
									<br />
								</td>
								<td>

									<ul>
                                    <?php for ($i = 0; $i < count($userList[0]); $i++) { ?>
                                        <?php if(isset($userList[0][$i])): ?>
                                            <li id='users_<?php print($userList[0][$i]->getId()); ?>' draggable='true' ondragstart='return drag(event)' class="floatListItem">
                                                <?php print($userList[0][$i]->getName()); ?>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(isset($userList[1][$i])): ?>
                                            <li id='users_<?php print($userList[1][$i]->getId()); ?>' draggable='true' ondragstart='return drag(event)' class="floatListItem">
                                                <?php print($userList[1][$i]->getName()); ?>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(isset($userList[2][$i])): ?>
                                            <li id='users_<?php print($userList[2][$i]->getId()); ?>' draggable='true' ondragstart='return drag(event)' class="floatListItem">
                                                <?php print($userList[2][$i]->getName()); ?>
                                            </li>
                                        <?php endif; ?>
                                        <br />
                                    <?php } ?>
									</ul>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<hr />
								</td>
							</tr>
							<tr>
								<td class="dropSpace">
									<span style="font-size: 12px; font-weight: bold;">drops</span>
									<ul id="items" class="dropable_lists" ondragover="return DragOver(event)" ondragleave="return DragLeave(event)" ondrop="return doDrop(event)">
										<?php
										foreach($run->getDrops() as $drop){
											?>
											<li id="db_items_<?php print($drop->getDropId());?>" draggable='true' ondragstart='return drag(event)'>
												<?php print($drop->getName());?>
											</li>
										<?php
										}
										?>
									</ul>
									<br />
									<br />
								</td>
								<td>
									<ul>
                                    <?php for ($i = 0; $i < count($itemList[0]); $i++) { ?>
                                        <?php if(isset($itemList[0][$i])): ?>
                                        <li id='users_<?php print($itemList[0][$i]->getId()); ?>' draggable='true' ondragstart='return drag(event)' class="floatListItem">
                                            <?php print($itemList[0][$i]->getName()); ?>
                                        </li>
                                        <?php endif; ?>
                                        <?php if(isset($itemList[1][$i])): ?>
                                        <li id='users_<?php print($itemList[1][$i]->getId()); ?>' draggable='true' ondragstart='return drag(event)' class="floatListItem">
                                            <?php print($itemList[1][$i]->getName()); ?>
                                        </li>
                                        <?php endif; ?>
                                        <?php if(isset($itemList[2][$i])): ?>
                                        <li id='users_<?php print($itemList[2][$i]->getId()); ?>' draggable='true' ondragstart='return drag(event)' class="floatListItem">
                                            <?php print($itemList[2][$i]->getName()); ?>
                                        </li>
                                        <?php endif; ?>
                                        <br />
                                    <?php } ?>
									</ul>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<br />
				<br />
            <?php endif; ?>
			
			<div class="popupWrapper" id="popupWrapper"></div>
            <div class="popup" id="popAddRun">
                <h1 id="closePopUp" class="closeButton">[x]CLOSE</h1>
                <form action="runs.php?addRun=1" method="post">
                    <input name= "runName" id= "runName" class="inputText" type="text" placeholder="Run Name">
                    <input name="runDate" class="inputText" type="date" placeholder="mm/dd/yyyy">
                    <input type="submit" class="mySubmitButton" value="Add Run">
                </form>
            </div>
            <div class="buttonContainer">
			    <input id="addRunButton" type="submit" class="myButton" value="Add Run">
            </div>
		
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