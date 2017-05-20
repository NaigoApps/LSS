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
        <div class="well container">
            <h4>Home</h4>
            <div class="row">
                <a class="col-sm-3 btn btn-sm btn-default" href="<?php echo WEB . "/index.php"; ?>">
                    <strong class="text-info">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        Nuova programmazione
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

                    <a class="col-sm-3 btn btn-sm btn-default" href="../admins/index.php">
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

        <div ng-app="lss-db" ng-controller="timelineController
             as
             timCtrl" class="container top-sep">
            <h4>Programmazioni attive</h4>
            <div class="row">
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
                                        <form class="dummy-form" action="./pages/timelinemanager/editor2.php" method="POST">
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
                                        <form class="dummy-form" action="./pages/timelinemanager/print.php" method="POST">
                                            <input type="hidden" name="timelineid" value="{{timeline.id}}"/>    
                                            <button class="btn btn-xs btn-info">
                                                <span class="hidden-xs">Stampa</span>
                                                <span class="glyphicon glyphicon-print"></span>
                                            </button>
                                        </form>
                                        <form class="dummy-form" action="./pages/timelinecreator/index.php" method="POST">
                                            <input type="hidden" name="timelineid" value="{{timeline.id}}"/> 
                                            <input type="hidden" name="copy" value="true"/>    
                                            <button type="submit" class="btn btn-xs btn-info">
                                                <span class="hidden-xs">Duplica</span>
                                                <span class="glyphicon glyphicon-duplicate"></span>
                                            </button>
                                        </form>
                                        <a class="btn btn-xs btn-warning" ng-click="onStoreTimeline(timeline)">
                                            <span class="hidden-xs">Archivia</span>
                                            <span class="glyphicon glyphicon-cloud-download"></span>
                                        </a>
                                        <a class="btn btn-xs btn-danger" ng-click="onDeleteTimeline(timeline)">
                                            <span class="hidden-xs">Rimuovi</span>
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                    </li>
                </ul>
            </div>
            <div class="row both-sep">
                <a id="btn-stored" class="btn btn-info col-sm-4 col-sm-offset-4" ng-click="onShowStored()">Mostra archiviate</a>
            </div>
            <div id="bar-stored" class="progress top-sep" hidden>
                <div class="progress-bar progress-bar-success progress-bar-striped active" style="width: 100%">
                </div>
            </div>
            <ul class="row" ng-hide="storedTimelines.hidden">
                <h3>Programmazioni archiviate</h3>
                <li class="list-group-item" ng-repeat="timeline in storedTimelines.content">
                    {{timeline.classe}}{{timeline.sezione}} - {{timeline.materia}} {{timeline.anno}}/{{timeline.anno2}}                    <div class="pull-right">
                        <form class="dummy-form" action="./pages/timelinecreator/index.php" method="POST">
                            <input type="hidden" name="timelineid" value="{{timeline.id}}"/> 
                            <input type="hidden" name="copy" value="true"/>    
                            <button type="submit" class="btn btn-xs btn-info">
                                <span class="hidden-xs">Duplica</span>
                                <span class="glyphicon glyphicon-duplicate"></span>
                            </button>
                        </form>
                        <a class="btn btn-xs btn-success" ng-click="onUnStoreTimeline(timeline)">
                            <span class="hidden-xs">Togli dall'archivio</span>
                            <span class="glyphicon glyphicon-cloud-upload"></span>
                        </a>
                        <a class="btn btn-xs btn-danger" ng-click="onDeleteTimeline(timeline)">
                            <span class="hidden-xs">Rimuovi</span>
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>

    </body>
</html>