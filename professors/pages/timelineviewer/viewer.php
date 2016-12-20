<?php
require_once '../../../common/auth-header.php';
$id = $_POST['timelineid'];
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Visualizzazione</title>

        <script src="../../../common/scripts/jquery.js"></script>
        <script src="../../../common/swal/sweetalert.min.js"></script>
        <link href="../../../common/swal/sweetalert.css" rel="stylesheet" type="text/css"/>
        <script src="../../../common/timeline/timeline2.js"></script>
        <link rel="stylesheet" href="../../../common/styles/bootstrap.min.css">
        <link rel="stylesheet" href="../../../common/styles/style.css">
        <script type="text/javascript" src="../../../common/scripts/bootstrap.min.js"></script>
        <script type="text/javascript" src="../../../common/scripts/angular.min.js"></script>
        <script src="../../../common/scripts/links.js"></script>
        <script src="./scripts/script.js"></script>

        <script type="text/javascript">
                    var timeline_id = <?php echo $id; ?>;</script>
        <link href="../../../common/timeline/timeline.css" rel="stylesheet" type="text/css" />
        <link href="../../../common/styles/style.css" rel="stylesheet" type="text/css" />
    </head>
    <body ng-app="lss-db" ng-controller="linkController as linkCtrl">
        <div ng-app="lss-db" ng-controller="timelineController as tCtrl">
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#">
                            <span class="glyphicon glyphicon-list"></span>
                        </a>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a ng-if="!singleMode" ng-click="onSingleMode()">Singola programmazione</a></li>
                                    <li><a ng-if="singleMode" ng-click="onMultiMode()">Tutte le programmazioni</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a ng-if="!doneElements" ng-click="onDoneItems()">Argomenti svolti</a></li>
                                    <li><a ng-if="doneElements" ng-click="onUndoneItems()">Tutti gli argomenti</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="../../index.php">Indietro</a></li>
                                </ul>
                            </li>
                            <ul class="nav navbar-nav navbar-right">
                                <li>
                                    <a>Visualizzazione programmazione {{timelines[0].metadata.anno}}/{{timelines[0].metadata.anno2}} - Classe {{timelines[0].metadata.annoclasse}}{{timelines[0].metadata.sezione}}:
                                        <span class="small" ng-repeat="timeline in timelines| filter: {visible : true}">
                                            <span>{{timeline.metadata.nomemateria}}</span>
                                            <span ng-if="!$last">, </span>
                                        </span></a>
                                </li>
                            </ul>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>

            <div class="container under-nav">
                <div class="progress">
                    <div id="bar-timeline" class="progress-bar progress-bar-success progress-bar-striped active" style="width: 100%">
                    </div>
                </div>
                <div id="visualization" class="row top-sep clearfix"></div>
                <div class="row top-sep well" ng-if="selected.current">

                    <h4>{{selected.current.nome}}<small> da {{selected.current.topic.nome}} in {{selected.current.module.nome}}</small></h4>
                    <p>{{selected.current.descrizione}}</p>

                </div>
                <div class="row top-sep well" ng-if="selected.current.links && selected.current.links.length > 0">
                    <h3>Collegamenti</h3>
                    <span ng-repeat="link in selected.current.links">
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

                <div class="row error message always-bottom">
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
        </div>


    </body>
</html>