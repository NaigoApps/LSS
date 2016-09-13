
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <?php
                if (isset($authUrl)) {
                    echo '<li><a href= "' . $authUrl . '">Login</a></li>';
                } else if(isset($user_data)){
                    echo '<li><p class="navbar-text">' . $user_data->getEmail() . '</p></li>';
                    echo '<li><a href= "?logout">Logout</a></li>';
                }
                ?>

            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>