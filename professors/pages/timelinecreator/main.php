<?php
require_once '../../../common/auth-header.php';
?>

<!doctype html>
<html>
    <head>
        <title>Creazione programmazione</title>
        <meta charset="utf-8"/>
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

        <div ng-app="lss-db" ng-controller="timelineController as timeCtrl">
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#">
                            <span class="glyphicon glyphicon-plus"></span>
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
                                <a>Creazione programmazione</a>
                            </li>
                        </ul>
                        <?php require_once __DIR__ . '/../../../common/authentication-bar.php'; ?>
                    </div>
                </div>
            </nav>

            <div class="container under-nav">
                <div class="row">

                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="row">
                            <h1 class="text-center">Dati della programmazione</h1>
                            <ul class="list-group top-sep">
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true">
                                        {{classes.selected != undefined ? classes.selected.year + classes.selected.section:"Selezionare una classe"}}  
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li ng-repeat="class in classes.content"
                                            ng-click="onSelectClass(class)">
                                            <a>{{class.year + class.section}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </ul>
                        </div>
                        <div class="row">
                            <ul class="list-group top-sep">
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true">
                                        {{subjects.selected != undefined ? subjects.selected.name:"Selezionare una materia"}}  
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li ng-repeat="subject in subjects.content"
                                            ng-click="onSelectSubject(subject)">
                                            <a>{{subject.name}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </ul>
                        </div>
                        <div class="row">
                            <ul class="list-group top-sep">
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true">
                                        {{years.selected != undefined ? years.selected + "/" + (years.selected + 1):"Selezionare un anno"}}  
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li ng-repeat="y in years.content"
                                            ng-click="onSelectYear(y)">
                                            <a>{{y}}/{{y + 1}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </ul>
                        </div>
                        <div class="row top-sep">
                            <?php
                            if (isset($_POST['copy'])) {
                                ?>
                                <a class="btn btn-success" ng-click="onCopyTimeline(<?php echo $_POST['timelineid']; ?>)">Copia</a>
                                <?php
                            } else {
                                ?>
                                <a class="btn btn-success" ng-click="onConfirmTimeline()">Conferma</a>
                                <?php
                            }
                            ?>
                        </div>

                        <div class="row top-sep">
                            <h1 class="text-center">Programmazioni</h1>
                            <ul>
                                <li ng-repeat = "schedule in schedules.content">
                                    <div class = "well well-sm clearfix">
                                        {{schedule.class.year}}{{schedule.class.section}} - {{schedule.subject.name}} - {{schedule.year}}/{{schedule.year2}}
                                        <form class="pull-right dummy-form" action="<?php echo WEB . "/professors/pages/timelinemanager/main.php"; ?>" method="POST">
                                            <input type = "hidden" name = "timelineid" value = "{{schedule.id}}"/>
                                            <button type = "submit" class = "btn btn-xs btn-success tooltip-base">
                                                <span class = "tooltip-text">Gestisci</span>
                                                <span class = "glyphicon glyphicon-edit"></span>
                                            </button>
                                            <a class = "btn btn-xs btn-danger tooltip-base" ng-click = "onDeleteSchedule(schedule)">
                                                <span class = "tooltip-text">Rimuovi</span>
                                                <span class = "glyphicon glyphicon-remove"></span>
                                            </a>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>