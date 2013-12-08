<div class="navbar">
    <div class="nav-content">
        <div class="breakout">
            <div class="color1"></div>
            <div class="color2"></div>
            <div class="color3"></div>
            <div class="color4"></div>
            <div class="color5"></div>
        </div>

        <a href="home.php" class="logo">Aegis</a>
        <?php if (!$_SESSION["userID"]): ?>
            <a href="login.php">Log In</a>
        <?php else: ?>
            <a href="#">
                <img src="../assets/settings_icon.png" style="width: 50px; height: 50px; vertical-align: middle;" onclick="dropDownSettings()">


            </a>

        <?php endif; ?>
        <?php
        foreach ($navbarlinks as $link) {
            if (strtoupper($currentPageID) == strtoupper($link->getName())) {
                print("<a href='" . $link->getLocation() . "' class='navcurrent'>" . $link->getName() . "</a>");
            } else {
                print("<a href='" . $link->getLocation() . "'>" . $link->getName() . "</a>");
            }
        }
        ?>
        <div id="settingsMenu" class="settingsMenu">
            <a href="settings.php">Settings</a>
            <a href="login.php?action=logout">Log Out</a>
        </div>
    </div>
</div>