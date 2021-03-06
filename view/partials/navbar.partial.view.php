<div id="wrap">
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php">Aegis</a>
            </div>
            <div class="navbar-collapse collapse navbar-right">
                <ul class="nav navbar-nav">
                    <?php
                    $manageLinks = array();
                    foreach ($navBarLinks as $link) :
                        /** @var Links $link */
                        if ($link->getVisibility() != 10) :
                            if (strtoupper($currentPageID) == strtoupper($link->getName())) {
                                print('<li class="active"><a href="' . $link->getLocation() . '">' . $link->getName() . '</a></li>');
                            } else {
                                print('<li><a href="' . $link->getLocation() . '">' . $link->getName() . '</a></li>');
                            }
                        else :
                            array_push($manageLinks, $link);
                        endif;
                    endforeach;
                    if (sizeof($manageLinks) > 0) : ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                Manage<b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <?php foreach ($manageLinks as $link) :
                                    print('<li><a href="' . $link->getLocation() . '">' . $link->getName() . '</a></li>');
                                endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (!isset($_SESSION["userID"])): ?>
                        <form class="navbar-form navbar-left" action="home.php?action=login" method="post"
                              role="Sign In">
                            <div class="form-group">
                                <input name="name" id="name" type="text" class="form-control"
                                       placeholder="Username">
                                <input name="password" id="password" type="password" class="form-control"
                                       placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn-success">Sign In</button>
                        </form>

                        <form class="navbar-form navbar-left" action="register.php" method="post" role="Sign Up">
                            <button type="submit" class="btn btn-primary">Sign Up</button>
                        </form>
                    <?php else: ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="glyphicon glyphicon-cog"></span><b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="settings.php"><span class="glyphicon glyphicon-cog"></span> Settings</a>
                                </li>
                                <li><a href="home.php?action=logout"><span class="glyphicon glyphicon-off"></span> Sign
                                        Out</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
    <div style="height: 50px;"></div>