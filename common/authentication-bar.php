
<ul class="nav navbar-nav navbar-right">
    <?php
    if (isset($authUrl)) {
        echo '<li><a href= "' . $authUrl . '">Login</a></li>';
    } else if (isset($_SESSION['user_data'])) {
        echo '<li><p class="navbar-text">' . $_SESSION['user_data']->getName() . '</p></li>';
        echo '<li><p class="navbar-text">' . $_SESSION['user_data']->getSurname() . '</p></li>';
        if ($_SESSION['user_data']->isStudent() || $_SESSION['user_data']->isUnregistered()) {
            if ($_SESSION['user_data']->getClassroom()) {
                echo '<li><p class="navbar-text">' . $_SESSION['user_data']->getClassroom()->display() . '</p></li>';
            }else{
                echo '<li><b><p class="navbar-text">Nessuna classe</p></b></li>';
            }
        }
        echo '<li><p class="navbar-text">' . $_SESSION['user_data']->getEmail() . '</p></li>';
        echo '<li><a href= "?logout">Logout</a></li>';
    }
    ?>
</ul>
