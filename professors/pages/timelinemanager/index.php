<?php
require_once '../../../common/auth-header.php';
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../../../common/styles/jquery.svg.css"></link>
        <link rel="stylesheet" href="../../../common/styles/bootstrap.min.css"></link>
        <link rel="stylesheet" href="../../../common/styles/style.css"></link>
        <script type="text/javascript" src="../../../common/scripts/jquery.js"></script>
        <script type="text/javascript" src="../../../common/scripts/bootstrap.min.js"></script>
        <script type="text/javascript" src="../../../common/scripts/angular.min.js"></script>
        <script src="../../../common/swal/sweetalert.min.js"></script>
        <link href="../../../common/swal/sweetalert.css" rel="stylesheet" type="text/css"/>
        <script src="scripts/timelines-list.js"></script>
    </head>
    <body>

        <?php
        if (isset($user_data) && $user_data) {
            if ($user_data->isProfessor()) {
                include './includes/timeline-manager-content.php';
            } else {
                print_msg('Accesso non autorizzato');
            }
        } else {
            print_msg('Utente non autorizzato: procedere alla registrazione');
        }
        ?>

    </body>
</html>