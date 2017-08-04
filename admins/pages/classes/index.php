<?php
require_once '../../../common/auth-header.php';
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Gestione delle classi</title>
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

        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">
                        <span class="glyphicon glyphicon-blackboard"></span>

                    </a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="../../main.php">Home</a></li>
                            </ul>
                        </li>

                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a>Gestione delle classi</a>
                            </li>
                        </ul>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <div class="container" ng-app="lss-db" ng-controller="classesController as classCtrl">
            <h2 class="text-center">Elenco delle classi</h2>
            <div class="row">
                <ul class="list-group top-sep">
                    <li class="list-group-item" ng-repeat="classroom in classes.content| orderBy:['year', 'section']">
                        <form class="form-inline dummy-form clearfix" ng-class="{'has-warning' : classroom.modified}" >
                            <div class="form-group">
                                <label for="{{classroom.year + classroom.section + 'a'}}">Classe:</label>
                                <input id="{{classroom.year + classroom.section + 'a'}}" type="text" class="form-control" placeholder="anno" ng-keypress="classroom.modified = true" ng-model="classroom.year"/>
                            </div>
                            <div class="form-group">
                                <label for="{{classroom.year + classroom.section + 's'}}">Sezione:</label>
                                <input id="{{classroom.year + classroom.section + 's'}}" type="text" class="form-control" placeholder="sezione" ng-keypress="classroom.modified = true" ng-model="classroom.section"/>
                            </div>
                            <!-- Delete -->
                            <div class="btn-group pull-right">
                                <!-- Edit -->
                                <a class="btn btn-xs btn-success"
                                   ng-click="onEditClass(classroom)">
                                    <span class="glyphicon glyphicon-save"></span>
                                    <span class="hidden-xs">Salva</span>
                                </a>
                                <a class="btn btn-xs btn-danger"
                                   ng-click="onDeleteClass(classroom)">
                                    <span class="glyphicon glyphicon-remove"></span>
                                    <span class="hidden-xs">Elimina</span>
                                </a>
                            </div>
                        </form>
                    </li>
                </ul>
                <form class="well form-inline col-sm-8 col-sm-offset-2">
                    <div>Nuova classe:</div>
                    <input type="text" class="form-control" placeholder="anno" ng-model="year"/>
                    <input type="text" class="form-control" placeholder="nome" ng-model="section"/>
                    <button class="btn btn-default" ng-click="onAddClass()">Aggiungi classe</button>
                </form>
            </div>
        </div>
    </body>
</html>