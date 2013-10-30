<!DOCTYPE html>
<html>
	<head>
		<title>LandingsPage</title>
	</head>
	
	<body>
		<?php include('navbar.partial.view.php') ?>
		
		<div class="content" style="text-align: center;">
			<div class="popupWrapper" id="popupWrapper"></div>
            <div class="popup" id="popAddItem">
                <h1 id="closeAddItem" class="closeButton">[x]CLOSE</h1>
                <form action="items.php" method="post">
                    <input name="addItem" id="addItem" type="text" class="inputText" placeholder="Item Name" >
                    <input type="submit" class="mySubmitButton" value="Add Item">
                </form>
            </div>
            <div class="popup" id="popSellItem">
                <h1 id="closeSellItem" class="closeButton">[x]CLOSE</h1>
                <form action="items.php" method="post" style="text-align: left;">
                    <input name="itemAmount" type="text" class="inputText" id="itemAmount" placeholder="#" value="1" style="width: 20px;">
                    <select data-placeholder="Pick an item..." class="chosen-select" id="itemName" name="itemName">
                        <option value=""></option>
                        <?php foreach($itemlist as $item){ ?>
                                <option value="<?php print($item->getId());?>"><?php print($item->getName());?></option>
                        <?php } ?>
                    </select>
                    <input name="itemValue" type="text" class="inputText" id="itemValue" placeholder="Item Value">
                    <input type="submit" class="mySubmitButton" value="Sell Item">
                </form>
            </div>
            <?php if(isset($errorMessage)){ ?>
            <div>
                <h1><?php print($errorMessage); ?></h1>
            </div>
            <?php } ?>
            <div class="buttonContainer">
                <input id="addItemButton" type="submit" class="myButton" value="Add Item">
                <input id="sellItemButton" type="submit" class="myButton" value="Sell Item">
            </div>
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
        <link rel="stylesheet" type="text/css" href="../assets/site_style.css">
        <link rel="stylesheet" type="text/css" href="../assets/chosen.css">
        <script type="text/javascript" src="../assets/site_layout.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
        <script type="text/javascript" src="../assets/chosen.jquery.js"></script>
        <script type="text/javascript">
            window.onload = function(){
                initializePage();
                document.getElementById("addItemButton").onclick = function(event) {
                    popup('popAddItem','block');
                    setFocus('addItem');
                }
                document.getElementById("sellItemButton").onclick = function(event) {
                    popup('popSellItem','block');
                    $("#itemName_chosen").children('.chosen-drop').children('.chosen-search').children('input[type="text"]').focus();
                }
                document.getElementById("closeAddItem").onclick = function(event) {
                    popup('popAddItem','none');
                }
                document.getElementById("closeSellItem").onclick = function(event) {
                    popup('popSellItem','none');
                }
                document.getElementById("popupWrapper").onclick = function(event) {
                    popup('popAddItem','none');
                    popup('popSellItem','none');
                }
            }
            window.onresize = initializePage;

            //close popup boxes using ESC key.
            document.onkeydown = function(event) {
                event = event || window.event;
                if (event.keyCode == 27) {
                    popup('popSellItem','none');
                    popup('popSellItem','none');
                }
            }

            var config = {
                '.chosen-select'           : {width:"200px"},
                '.chosen-select-deselect'  : {allow_single_deselect:true},
                '.chosen-select-no-single' : {disable_search_threshold:10},
                '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
                '.chosen-select-width'     : {width:"95%"}
            }
            for (var selector in config) {
                $(selector).chosen(config[selector]);
            }

        </script>
	</body>
</html>