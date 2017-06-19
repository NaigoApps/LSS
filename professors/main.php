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
        <div class="container">
            <div class="jumbotron text-center"><h1>Home</h1></div>
            <div class="well">
                <div class="row">
                    <a class="col-sm-3 btn btn-sm btn-default" href="<?php echo WEB . "/professors/pages/timelinecreator/main.php"; ?>">
                        <strong class="text-info">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                            Nuova programmazione
                        </strong>
                    </a>
                    <a class="col-sm-3 btn btn-sm btn-default" href="<?php echo WEB . "/professors/pages/materialmanager/materialmanager.php"; ?>">
                        <strong class="text-info">
                            <span class="glyphicon glyphicon-link" aria-hidden="true"></span>
                            Gestione del materiale
                        </strong>
                    </a>
                    <!--
                            <a class="col-sm-3 btn btn-sm btn-default" href="./pages/timelinemanager/index.php">
                                <strong class="text-info">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    Gestisci programmazioni
                                </strong>
                            </a>
                    
                            <a class="col-sm-3 btn btn-sm btn-default" href="./pages/timelineviewer/index.php">
                                <strong class="text-info">
                                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                    Visualizza programmazioni
                                </strong>
                            </a>-->


                    <?php
                    if ($_SESSION['user_data']->isAdmin()) {
                        ?>

                        <a class="col-sm-3 btn btn-sm btn-default" href="<?php echo WEB . "/admins/main.php"; ?>">
                            <strong class="text-warning">
                                <span class="glyphicon glyphicon-console" aria-hidden="true"></span>
                                Sezione amministratore
                            </strong>
                        </a>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <div ng-app="lss-db" ng-controller="timelineController as timCtrl" class="top-sep">
                <h2>Programmazioni attive</h2>
                <div id="bar-unstored" class="progress" hidden>
                    <div class="progress-bar progress-bar-success progress-bar-striped active" style="width: 100%">
                    </div>
                </div>
                <ul class="row">
                    <li class="col-sm-12 col-xs-12" ng-repeat = "timeline in timelines.content">
                        <div class="row">
                            <div class="well well-sm clearfix col-sm-12">
                                {{timeline.class.year}}{{timeline.class.section}} - {{timeline.subject.name}} {{timeline.year}}/{{timeline.year2}}                    <div class="pull-right">
                                    <div class="pull-right">
                                        <form class="dummy-form" action="<?php echo WEB . "/professors/pages/timelinemanager/main.php"; ?>" method="POST">
                                            <input type="hidden" name="timelineid" value="{{timeline.id}}"/>    
                                            <button type="submit" class="btn btn-xs btn-success">
                                                <span class="hidden-xs">Gestisci</span>
                                                <span class="glyphicon glyphicon-edit"></span>
                                            </button>
                                        </form>
                                        <form class="dummy-form" action="./pages/timelineviewer/viewer.php" method="POST">
                                            <input type="hidden" name="timelineid" value="{{timeline.id}}"/> 
                                            <button type="submit" class="btn btn-xs btn-success">
                                                <span class="hidden-xs">Visualizza</span>
                                                <span class="glyphicon glyphicon-eye-open"></span>
                                            </button>
                                        </form>
                                        <form class="dummy-form" action="<?php echo WEB . "/professors/pages/scheduleprinter/print.php"; ?>" method="POST">
                                            <input type="hidden" name="timelineid" value="{{timeline.id}}"/>    
                                            <button class="btn btn-xs btn-info">
                                                <span class="hidden-xs">Stampa</span>
                                                <span class="glyphicon glyphicon-print"></span>
                                            </button>
                                        </form>
                                        <form class="dummy-form" action="<?php echo WEB . "/professors/pages/timelinecreator/main.php"; ?>" method="POST">
                                            <input type="hidden" name="timelineid" value="{{timeline.id}}"/> 
                                            <input type="hidden" name="copy" value="true"/>    
                                            <button type="submit" class="btn btn-xs btn-info">
                                                <span class="hidden-xs">Duplica</span>
                                                <span class="glyphicon glyphicon-duplicate"></span>
                                            </button>
                                        </form>
                                        <a class="btn btn-xs btn-warning" ng-click="onStoreSchedule(timeline)">
                                            <span class="hidden-xs">Archivia</span>
                                            <span class="glyphicon glyphicon-cloud-download"></span>
                                        </a>
                                        <a class="btn btn-xs btn-danger" ng-click="onDeleteSchedule(timeline)">
                                            <span class="hidden-xs">Rimuovi</span>
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                    </li>
                </ul>
                <div class="row both-sep">
                    <a id="btn-stored" class="btn btn-info col-sm-4 col-sm-offset-4" ng-click="onShowStored()">Mostra archiviate</a>
                </div>
                <div id="bar-stored" class="progress top-sep" hidden>
                    <div class="progress-bar progress-bar-success progress-bar-striped active" style="width: 100%">
                    </div>
                </div>
                <ul class="row" ng-hide="storedSchedules.hidden">
                    <h3>Programmazioni archiviate</h3>
                    <li class="list-group-item" ng-repeat="schedule in storedSchedules.content">
                        {{schedule.class.year}}{{schedule.class.section}} - {{schedule.subject.name}} {{schedule.year}}/{{schedule.year2}}                    <div class="pull-right">

                            <form class="dummy-form" action="<?php echo WEB . "/professors/pages/timelinecreator/main.php"; ?>" method="POST">
                                <input type="hidden" name="timelineid" value="{{schedule.id}}"/> 
                                <input type="hidden" name="copy" value="true"/>    
                                <button type="submit" class="btn btn-xs btn-info">
                                    <span class="hidden-xs">Duplica</span>
                                    <span class="glyphicon glyphicon-duplicate"></span>
                                </button>
                            </form>
                            <a class="btn btn-xs btn-success" ng-click="onUnstoreSchedule(schedule)">
                                <span class="hidden-xs">Recupera</span>
                                <span class="glyphicon glyphicon-cloud-upload"></span>
                            </a>
                            <a class="btn btn-xs btn-danger" ng-click="onDeleteSchedule(schedule)">
                                <span class="hidden-xs">Rimuovi</span>
                                <span class="glyphicon glyphicon-remove"></span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </body>
</html>