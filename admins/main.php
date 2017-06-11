<?php require_once __DIR__ . '/../common/php/consts.php'; ?>
<?php require_once __DIR__ . '/../common/auth-header.php'; ?>
<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/bootstrap.min.css"; ?>>
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/style.css"; ?>>
        <link rel="stylesheet" href=<?php echo WEB . "/common/swal/sweetalert.css"; ?>>
        <script type="text/javascript" src=<?php echo WEB . "/common/swal/sweetalert.min.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/jquery.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/bootstrap.min.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/angular.min.js"; ?>></script>

        <script type="text/javascript" src="script.js"></script>
    </head>
    <body>

        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="collapse navbar-collapse">
                    <?php require_once __DIR__ . '/../common/authentication-bar.php'; ?>
                </div><!-- /.navbar-collapse -->

            </div><!-- /.container-fluid -->
        </nav>
        <div class="row under-nav">
            <a class="col-sm-2 col-sm-offset-2 btn btn-md btn-default" href="<?php echo WEB . "/admins/pages/elements-manager/elements-manager.php"; ?>">
                <h1 class="text-info">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </h1>
                Gestione database
            </a>

            <a class="col-sm-2 col-sm-offset-1 btn btn-md btn-default" href="./pages/classes/index.php">
                <h1 class="text-info">
                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                </h1>
                Gestione classi
            </a>

            <a class="col-sm-2 col-sm-offset-1 btn btn-md btn-default" href="./pages/users/index.php">
                <h1 class="text-info">
                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                </h1>
                Gestione utenti
            </a>
        </div>

        <?php
        if ($_SESSION['user_data']->isProfessor()) {
            ?>

            <div class="row top-sep">
                <a class="col-sm-2 col-sm-offset-5 btn btn-md btn-default" href="../professors/index.php">
                    <h1 class="text-info">
                        <span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span>
                    </h1>
                    Home docenti
                </a>

            </div>

            <?php
        }
        ?>

    </body>
</html>


