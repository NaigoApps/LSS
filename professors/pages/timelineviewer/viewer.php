<?php
require_once '../../../common/auth-header.php';
$id = $_POST['timelineid'];
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Visualizzazione</title>

        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/bootstrap.min.css"; ?>>
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/style.css"; ?>>
        <link rel="stylesheet" href=<?php echo WEB . "/common/swal/sweetalert.css"; ?>>
        <script type="text/javascript" src=<?php echo WEB . "/common/swal/sweetalert.min.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/jquery.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/bootstrap.min.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/angular.min.js"; ?>></script>

        <script type="text/javascript">
            var timeline_id = <?php echo $id; ?>;
        </script>
        <script src="viewer.js"></script>

        <link href='<?php echo WEB . "/common/timeline/timeline.css"; ?>' rel="stylesheet" type="text/css" />
        <script src='<?php echo WEB . "/common/timeline/timeline.js"; ?>' type="text/javascript" ></script>
    </head>
    <body ng-app="lss-db" ng-controller="timelineController as tCtrl">
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#">
                            <span class="glyphicon glyphicon-list"></span>
                        </a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li ng-repeat="schedule in schedules">
                                        <a ng-if="schedule.visible" ng-click="onToggleSchedule(schedule)">Nascondi {{schedule.metadata.nomemateria}}</a>
                                        <a ng-if="!schedule.visible" ng-click="onToggleSchedule(schedule)">Mostra {{schedule.metadata.nomemateria}}</a>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li><a ng-if="!doneElements" ng-click="onDoneItems()">Argomenti svolti</a></li>
                                    <li><a ng-if="doneElements" ng-click="onUndoneItems()">Tutti gli argomenti</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="../../index.php">Indietro</a></li>
                                </ul>
                            </li>
                            <li>
                                <a>Visualizzazione programmazione {{schedules[0].metadata.anno}}/{{schedules[0].metadata.anno2}} - Classe {{schedules[0].metadata.annoclasse}}{{schedules[0].metadata.sezione}}:
                                    <span class="small" ng-repeat="schedule in schedules| filter: {visible : true}">
                                        <span>{{schedule.metadata.nomemateria}}</span>
                                        <span ng-if="!$last">, </span>
                                    </span></a>
                            </li>
                        </ul>
                        <?php require_once __DIR__ . '/../../../common/authentication-bar.php'; ?>
                    </div><!-- /.navbar-collapse -->

                </div><!-- /.container-fluid -->
            </nav>

            <div class="container under-nav">
                <div class="progress">
                    <div id="bar-timeline" class="progress-bar progress-bar-success progress-bar-striped active" style="width: 100%">
                    </div>
                </div>
                <div id="visualization" class="row top-sep clearfix"></div>
                <div class="row top-sep well" ng-if="voci.current">

                    <h4>{{voci.current.nome}}<small> da {{voci.current.topic.nome}} in {{voci.current.module.nome}}</small></h4>
                    <p>{{voci.current.descrizione}}</p>

                </div>
                <div class="row top-sep well" ng-if="voci.current.links && voci.current.links.length > 0">
                    <h3>Collegamenti</h3>
                    <span ng-repeat="link in moduli.current.links">
                        <a class="text-success" href="{{link.link}}" target="blank">
                            <span class="glyphicon glyphicon-link"></span>
                            {{link.nome}}
                        </a>
                    </span>
                    <span ng-repeat="link in argomenti.current.links">
                        <a class="text-success" href="{{link.link}}" target="blank">
                            <span class="glyphicon glyphicon-link"></span>
                            {{link.nome}}
                        </a>
                    </span>
                    <span ng-repeat="link in voci.current.links">
                        <a class="text-success" href="{{link.link}}" target="blank">
                            <span class="glyphicon glyphicon-link"></span>
                            {{link.nome}}
                        </a>
                    </span>
                </div>
                <!--                <div class="row top-sep well" ng-if="selected.current.docs && selected.current.docs.length > 0">
                                    <h3>Documenti</h3>
                                    <div ng-repeat="doc in selected.current.docs">
                                        <a class="text-success" target="blank">
                                            <span class="glyphicon glyphicon-download"></span>
                                            {{doc}}
                                        </a>
                                    </div>
                                </div>-->
                <!--                <div class="row top-sep more">
                                    <button class="btn btn-sm btn-default col-sm-1" id="moveLeft" value="Muovi indietro">
                                        <span class="glyphicon glyphicon-arrow-left"></span>
                                    </button>
                                    <button class="btn btn-sm btn-default col-sm-1" id="moveRight" value="Muovi avanti">
                                        <span class="glyphicon glyphicon-arrow-right"></span>
                                    </button>
                                </div>
                                <div id="loading">loading...</div>
                                <p></p>
                                <div id="log"></div>-->

                <!--                <div class="row top-sep">
                                    TODO: INSERIRE CALENDAR
                                    <div class="col-sm-1 col-sm-offset-3">
                                        <button class="btn btn-sm btn-default" id="zoomOut" value="Zoom -">
                                            <span class="glyphicon glyphicon-zoom-out"></span>
                                        </button>
                                    </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-sm btn-default" id="zoomIn" value="Zoom +">
                                            <span class="glyphicon glyphicon-zoom-in"></span>
                                        </button>
                                    </div>
                                </div>-->
                iv class="row error message always-bottom">
                <div class="alert alert-danger error-message col-sm-12" role="alert" hidden>
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Errore:</span>
                    {{lastErrorMessage}}
                </div>
            </div>
            <div class="row success message always-bottom">
                <div class="alert alert-success success-message col-sm-12" role="alert" hidden>
                    <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
                    <span class="sr-only">Successo:</span>
                    {{lastSuccessMessage}}
                </div>
            </div>
    </div>


</body>
</html>