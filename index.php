<?php require_once __DIR__.'/common/php/consts.php'; ?>
<?php require_once __DIR__.'/common/auth-header.php'; ?>
<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href=<?php echo WEB."/common/styles/bootstrap.min.css";?>>
        <link rel="stylesheet" href=<?php echo WEB."/common/styles/style.css";?>>
        <link rel="stylesheet" href=<?php echo WEB."/common/swal/sweetalert.css";?>>
        <script type="text/javascript" src=<?php echo WEB."/common/swal/sweetalert.min.js";?>></script>
        <script type="text/javascript" src=<?php echo WEB."/common/scripts/jquery.js";?>></script>
        <script type="text/javascript" src=<?php echo WEB."/common/scripts/bootstrap.min.js";?>></script>
        <script type="text/javascript" src=<?php echo WEB."/common/scripts/angular.min.js";?>></script>
        <script type="text/javascript" src=<?php echo WEB."/common/scripts/script.js";?>></script>
    </head>
    <body>
        <?php require_once './common/authentication-bar.php'; ?>
        <?php
        if (isset($_SESSION['user_data'])) {
            if ($_SESSION['user_data']->isAdmin()) {
                header("Location: ".WEB."/professors/main.php");
            } elseif ($_SESSION['user_data']->isProfessor()) {
                require_once __DIR__."/professors/main.php";
            } elseif ($_SESSION['user_data']->isStudent()) {
                require_once __DIR__."/students/main.php";
            } elseif ($_SESSION['user_data']->isUnknown()) {                
                require_once __DIR__."/unknown/main.php";
            } elseif ($_SESSION['user_data']->isUnregistered()) {                
                require_once __DIR__."/register/main.php";
            }else{
                // ???                
                require_once __DIR__."/register/main.php";
            }
        }
        ?>
    </body>
</html>