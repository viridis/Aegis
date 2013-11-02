		<div class="footer">
			<div class="footer-content">
				<div class="footer-main">&copy; Ain't Nobody Got Time For That</div>
				<div class="footer-breakout footer-featured">
					<ul>
						<li><img src="../assets/icon_github.png" />
                            <a href="https://github.com/viridis/Aegis">GitHub Issue Tracker</a>
                        </li>
						<li><img src="../assets/icon_facebook.png" />Facebook</li>
						<li><img src="../assets/icon_twitter.png" />Twitter</li>
						<li><img src="../assets/icon_twitchtv.png" />Twitch TV</li>
					</ul>
				</div>
				
				<div class="footer-featured">
					<h6>Information</h6>
					<ul>
						<li><a href="">Contact Me</a></li>
						<li><a href="">Sitemap</a></li>
						<li><a href="">Links to info about</a></li>
						<li><a href="">The website or</a></li>
						<li><a href="">Contributors</a></li>
					</ul>
				</div>
				
				<div class="footer-featured">
					<h6>Useful Links</h6>
					<ul>
					<?php
						for($i=0; $i<count($usefulllinks); $i++){
							print("<li><a href='". $usefulllinks[$i]->getLocation() ."'>". $usefulllinks[$i]->getName() ."</a></li>");
						}
					?>
					</ul>
				</div>
				<div class="footer-featured">
					<h6>Featured Links</h6>
					<ul>
					<?php
						for($i=0; $i<count($featuredlinks); $i++){
							print("<li><a href='". $featuredlinks[$i]->getLocation() ."'>". $featuredlinks[$i]->getName() ."</a></li>");
						}
					?>
					</ul>
				</div>
			</div>
        </div>