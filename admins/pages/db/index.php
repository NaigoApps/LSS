<?php require_once '../../../common/auth-header.php'; ?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="../../../common/styles/bootstrap.min.css"></link>
        <link rel="stylesheet" href="../../../common/styles/style.css"></link>
        <script type="text/javascript" src="../../../common/scripts/jquery.js"></script>
        <script type="text/javascript" src="../../../common/scripts/bootstrap.min.js"></script>
        <script type="text/javascript" src="../../../common/scripts/angular.min.js"></script>

        <script type="text/javascript" src="../../../common/scripts/links.js"></script>
        <script type="text/javascript" src="./scripts/dbManager.js"></script>
    </head>
    <body>

        <?php
        if (isset($user_data) && $user_data) {
            if ($user_data->isAdmin()) {
                $ok = true;
            } else {
                $ok = false;
            }
        } else {
            $ok = false;
        }
        if (isset($ok) && $ok) {
            require_once './includes/index-content.php';
        } else {
            header('Location: ' . filter_var('../index.php', FILTER_SANITIZE_URL));
        }
        ?>
    </body>
</html>