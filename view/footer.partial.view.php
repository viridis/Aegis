<div class="footer">
    <div class="footer-content">
        <div class="footer-main">&copy; Ain't Nobody Got Time For That</div>
        <div class="footer-breakout footer-featured">
            <ul>
                <li>
                    <a href="https://github.com/viridis/Aegis/issues">
                        <img src="../assets/icon_github.png"/>GitHub Issue Tracker
                    </a>
                </li>
                <li>
                    <a href="https://www.facebook.com/viridis101">
                        <img src="../assets/icon_facebook.png"/>Facebook
                    </a>
                </li>
                <li>
                    <a href="https://twitter.com/Xinthiae">
                        <img src="../assets/icon_twitter.png"/>Twitter
                    </a>
                </li>
                <li>
                    <a href="http://www.twitch.tv/xinthiae">
                        <img src="../assets/icon_twitchtv.png"/>Twitch TV
                    </a>
                </li>
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
                for ($i = 0; $i < count($usefulllinks); $i++) {
                    print("<li><a href='" . $usefulllinks[$i]->getLocation() . "'>" . $usefulllinks[$i]->getName() . "</a></li>");
                }
                ?>
            </ul>
        </div>
        <div class="footer-featured">
            <h6>Featured Links</h6>
            <ul>
                <?php
                for ($i = 0; $i < count($featuredlinks); $i++) {
                    print("<li><a href='" . $featuredlinks[$i]->getLocation() . "'>" . $featuredlinks[$i]->getName() . "</a></li>");
                }
                ?>
            </ul>
        </div>
    </div>
</div>