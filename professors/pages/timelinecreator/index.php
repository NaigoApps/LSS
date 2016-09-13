<?php
require_once '../../../common/auth-header.php';
?>

<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="../../../common/styles/bootstrap.min.css"></link>
        <link rel="stylesheet" href="../../../common/styles/style.css"></link>
        <script type="text/javascript" src="../../../common/scripts/jquery.js"></script>
        <script type="text/javascript" src="../../../common/scripts/bootstrap.min.js"></script>
        <script type="text/javascript" src="../../../common/scripts/angular.min.js"></script>
        <script type="text/javascript" src="../../../common/scripts/links.js"></script>
        <script type="text/javascript" src="./scripts/creator.js"></script>
    </head>
    <body>

        <?php
        if (isset($user_data) && $user_data) {
            if ($user_data->isProfessor()) {
                include './includes/timeline-creator-content.php';
            } else {
                print_msg('Accesso non autorizzato');
            }
        } else {
            print_msg('Utente non autorizzato: procedere alla registrazione');
        }
        ?>

    </body>
</html>