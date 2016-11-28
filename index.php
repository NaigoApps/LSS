<?php require_once './common/auth-header.php'; ?>
<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="./common/styles/jquery.svg.css"></link>
        <link rel="stylesheet" href="./common/styles/bootstrap.min.css"></link>
        <link rel="stylesheet" href="./common/styles/bootstrap-switch.min.css"></link>
        <link rel="stylesheet" href="./common/styles/style.css"></link>
        <script type="text/javascript" src="./common/scripts/jquery.js"></script>
        <script type="text/javascript" src="./common/scripts/jquery.svg.min.js"></script>
        <script type="text/javascript" src="./common/scripts/bootstrap.min.js"></script>
        <script type="text/javascript" src="./common/scripts/bootstrap-switch.min.js"></script>
        <script type="text/javascript" src="./common/scripts/angular.min.js"></script>
        <script type="text/javascript" src="./common/scripts/script.js"></script>
    </head>
    <body>
        <?php require_once './common/authentication-bar.php'; ?>
        <?php
        if (isset($user_data)) {
            if ($user_data->isAdmin()) {
                $redirect = "./professors/index.php";
            } elseif ($user_data->isProfessor()) {
                $redirect = "./professors/index.php";
            } elseif ($user_data->isStudent()) {
                $redirect = "./students/index.php";
            } elseif ($user_data->isUnknown()) {
                $redirect = "./unknown/index.php";
            } elseif ($user_data->isUnregistered()) {
                $redirect = "./register/index.php";
            }else{
                // ???
                $redirect = "./register/index.php";
            }
            if(isset($redirect)){
                header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
            }
        }
        ?>
    </body>
</html>