
<ul class="nav navbar-nav navbar-right">
    <?php
    if (isset($authUrl)) {
        echo '<li><a href= "' . $authUrl . '">Login</a></li>';
    } else if (isset($_SESSION['user_data'])) {
        echo '<li><p class="navbar-text">' . $_SESSION['user_data']->getEmail() . '</p></li>';
        echo '<li><a href= "?logout">Logout</a></li>';
    }
    ?>
</ul>
