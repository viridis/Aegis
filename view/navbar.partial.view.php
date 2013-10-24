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
						for($i=0; $i<count($navbarlinks); $i++){
							if( strtoupper($currentPageID) == strtoupper($navbarlinks[$i]->getName())){
								print("<a href='". $navbarlinks[$i]->getLocation() ."' class='navcurrent'>". $navbarlinks[$i]->getName() ."</a>");
							}
							else{
								print("<a href='". $navbarlinks[$i]->getLocation() ."'>". $navbarlinks[$i]->getName() ."</a>");
							}
						}
					?>
					</div>
				</div>