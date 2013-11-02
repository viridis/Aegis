            <div class="navbar">
				<div class="nav-content">
					<div class="breakout">
						<div class="color1"></div>
						<div class="color2"></div>
						<div class="color3"></div>
						<div class="color4"></div>
						<div class="color5"></div>
					</div>
					<div class="logo">
						<a href="home.php">Aegis</a>
					</div>
					<?php	
						foreach($navbarlinks as $link){
							if( strtoupper($currentPageID) == strtoupper($link->getName())){
								print("<a href='". $link->getLocation() ."' class='navcurrent'>". $link->getName() ."</a>");
							}
							else{
								print("<a href='". $link->getLocation() ."'>". $link->getName() ."</a>");
							}
						}
					?>
                </div>
            </div>