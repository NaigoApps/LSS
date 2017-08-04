<?php
require_once '../../../common/auth-header.php';
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Gestione degli utenti</title>
        <meta charset="UTF-8">
        <script src=<?php echo WEB . "/common/scripts/jquery.js"; ?>></script>
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/bootstrap.min.css"; ?>>
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/style.css"; ?>>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/bootstrap.min.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/angular.min.js"; ?>></script>
        <link rel="stylesheet" href=<?php echo WEB . "/common/swal/sweetalert.css"; ?>>
        <script type="text/javascript" src=<?php echo WEB . "/common/swal/sweetalert.min.js"; ?>></script>
        <script src="script.js"></script>

    </head>
    <body>

        <div ng-app="lss-db" ng-controller="userController as usrCtrl">
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>
                    </div>

                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a ng-click="exit()">Torna al menu</a></li>
                                </ul>
                            </li>
                            <li>
                                <a>Gestione degli utenti</a>
                            </li>
                        </ul>
                        <?php require_once __DIR__ . '/../../../common/authentication-bar.php'; ?>
                    </div><!-- /.navbar-collapse -->

                </div><!-- /.container-fluid -->
            </nav>
            <div class="container under-nav">
                <div class="row">

                    <table class="table table-striped table-bordered">
                        <tr>
                            <th class="text-center">Nome</th>
                            <th class="text-center">Cognome</th>
                            <th class="text-center">e-mail</th>
                            <th class="text-center">Admin</th>
                            <th class="text-center">Docente</th>
                            <th class="text-center">Studente</th>
                            <th class="text-center"></th>
                        </tr>
                        <tr ng-repeat="user in visibleUsers.content">
                            <td>{{user.name}}</td>
                            <td>{{user.surname}}</td>
                            <td>{{user.email}}</td>

                            <td>
                                <a class="col-sm-3 btn btn-xs btn-default" ng-if="!isStudent(user)" ng-click="toggleAdmin(user)">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true" ng-if='isAdmin(user)'></span>
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true" ng-if='!isAdmin(user)'></span>
                                </a>
                            </td>
                            <td>
                                <a class="col-sm-3 btn btn-xs btn-default" ng-if="!isStudent(user)" ng-click="toggleProfessor(user)">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true" ng-if='isProfessor(user)'></span>
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true" ng-if='!isProfessor(user)'></span>
                                </a>
                            </td>
                            <td>
                                <a class="col-sm-3 btn btn-xs btn-default" ng-if="!isProfessor(user) && !isAdmin(user)" ng-click="toggleStudent(user)">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true" ng-if='isStudent(user)'></span>
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true" ng-if='!isStudent(user)'></span>
                                </a>
                            </td>

                            <!-- Edit -->
                            <td>
                                <!-- Delete -->
                                <div class="btn-group">
                                    <a class="btn btn-xs btn-danger tooltip-base"
                                       ng-click="removeUser(user)">                                
                                        <span class="tooltip-text">Elimina</span>
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                </div>
                            </td>

                        </tr>
                    </table>

                </div>
            </div>
        </div>
    </body>
</html>