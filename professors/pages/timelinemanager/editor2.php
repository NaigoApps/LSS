<?php
require_once '../../../common/auth-header.php';

$id = $_POST['timelineid'];
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Timeline | Basic demo</title>

        <style type="text/css">
            body, html {
                font-family: sans-serif;
            }
        </style>
        <meta charset="utf-8">
        <script src="../../../common/scripts/jquery.js"></script>
        <script src="../../../common/swal/sweetalert.min.js"></script>
        <link href="../../../common/swal/sweetalert.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="../../../common/styles/bootstrap.min.css">
        <link rel="stylesheet" href="../../../common/styles/style.css">
        <script type="text/javascript" src="../../../common/scripts/bootstrap.min.js"></script>
        <script type="text/javascript" src="../../../common/scripts/angular.min.js"></script>
        <link rel="stylesheet" href="../../../common/styles/jquery-ui.min.css">
        <link rel="stylesheet" href="../../../common/styles/jquery-ui.structure.min.css">
        <link rel="stylesheet" href="../../../common/styles/jquery-ui.theme.min.css">
        <script src="../../../common/scripts/jquery-ui.min.js"></script>

        <script type="text/javascript">
                    var timeline_id = <?php echo $id ?>;</script>

        <script src="../../../common/scripts/links.js"></script>
        <script src="scripts/script2.js"></script>
        <link rel="stylesheet"  href="../../../common/styles/style.css"/>
    </head>
    <body>
        <div ng-app="lss-db" ng-controller="linkController as linkCtrl">
            <div ng-app="lss-db" ng-controller="timelineController as timeCtrl">
                <nav class="navbar navbar-default navbar-fixed-top">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a class="navbar-brand" href="#">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </a>
                        </div>

                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu<span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a ng-click="saveData()">Salva</a>
                                        </li>
                                        <li><a ng-click="saveDataExit()">Salva e torna al menu</a></li>
                                        <li><a ng-click="exit()">Torna al menu</a></li>
                                    </ul>
                                </li>
                                <ul class="nav navbar-nav navbar-right">
                                    <li>
                                        <a>Programmazione {{timeline.annoclasse + timeline.sezione + " " + timeline.anno}}/{{timeline.anno2}} ({{timeline.nomemateria}})</a>
                                    </li>
                                </ul>
                            </ul>
                        </div><!-- /.navbar-collapse -->
                    </div><!-- /.container-fluid -->
                </nav>
                <div class="container under-nav">
                    <div class="row">
                        <div class="col-sm-3 bg-primary">
                            <h4 class="text-center">Mese:</h4>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownItems" data-toggle="dropdown" aria-haspopup="true">
                                    {{mesi.selected != undefined ? mesi.selected.nome:"Selezionare un mese"}}  
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownTopics">
                                    <li ng-repeat="mese in mesi"
                                        ng-click="onSelectMonth(mese)">
                                        <a>{{mese.nome}}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-3 bg-info">
                            <h4 class="text-center">Moduli:</h4>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownModules" data-toggle="dropdown" aria-haspopup="true">
                                    {{moduli.selected != undefined ? moduli.selected.nome:"Selezionare un modulo"}}
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownModules">
                                    <li ng-repeat="modulo in moduli.content"
                                        ng-click="onSelectModule(modulo)">
                                        <a>{{modulo.nome}}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-3 bg-info"
                             ng-if="moduli.selected !== undefined">
                            <h4 class="text-center">Argomenti:</h4>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownTopics" data-toggle="dropdown" aria-haspopup="true">
                                    {{argomenti.selected != undefined ? argomenti.selected.nome:"Selezionare un argomento"}}  
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownTopics">
                                    <li ng-repeat="argomento in argomenti.content"
                                        ng-click="onSelectTopic(argomento)">
                                        <a>{{argomento.nome}}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-3 bg-info"
                             ng-if="argomenti.selected !== undefined">
                            <h4 class="text-center">Voci:</h4>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownItems" data-toggle="dropdown" aria-haspopup="true">
                                    {{voci.selected != undefined ? voci.selected.nome:"Selezionare una voce"}}  
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownTopics">
                                    <li ng-repeat="voce in voci.content"
                                        ng-click="onSelectItem(voce)">
                                        <a>{{voce.nome}}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row top-sep">
                        <button class="btn btn-lg btn-success add-item col-sm-2 col-sm-offset-5"
                                ng-disabled="mesi.selected == undefined"
                                ng-click="onAddToTimeline()">
                            Aggiungi
                        </button>
                    </div>
                    <div class="row top-sep" ng-repeat="mese in mesi">
                        <!--Argomenti non svolti-->
                        <div class="col col-sm-6">
                            <div class="col-sm-12 panel panel-default">
                                <div class="row">
                                    <h3 class="panel-heading">{{mese.nome}} <small>non svolti</small></h3>
                                </div>
                                <ul class="list-group">
                                    <li class="list-group-item clearfix" ng-repeat="(i,element) in elements" ng-if="element.data.getMonth() + 1 === mese.numero && findById(element.performance, timeline.idmateria) === - 1">
                                        {{element.nome}}
                                        <div class="btn-group pull-right">

                                            <a class="btn btn-info btn-sm tooltip-base" ng-click="onSetDate(i)">
                                                <span class="tooltip" ng-if="!element.settingDate">Imposta data</span>
                                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                                                {{element.data| date:'dd/MM/yyyy'}}
                                            </a>
                                            <div class="picker-container" id="picker{{i}}"></div>
                                            <a class="btn btn-success btn-sm tooltip-base" ng-click="setDone(i)">
                                                <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
                                                <span class="tooltip">Svolgi</span>
                                            </a>
                                            <a class="btn btn-danger btn-sm tooltip-base" ng-click="onRemoveFromTimeline(i)">
                                                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                                                <span class="tooltip">Elimina</span>
                                            </a>
                                        </div>

                                        <div class="subjects pull-right">
                                            <span ng-repeat="(j,materia) in materie.content">
                                                <div class="subject tooltip-base" ng-style="{'background-color':materie.colors[j]}" ng-if="findById(element.performance, materia.id) !== - 1">
                                                    <span class="tooltip">{{materia.nome}} : {{findObjectById(element.performance, materia.id).data | date:'dd/MM/yyyy'}}</span>
                                                </div>
                                            </span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--Argomenti svolti-->
                        <div class="col col-sm-6">
                            <div class="col-sm-12 panel panel-default">
                                <div class="row">
                                    <h3 class="panel-heading">{{mese.nome}} <small>svolti</small></h3>
                                </div>
                                <ul class="list-group">
                                    <li class="list-group-item clearfix" ng-repeat="(i,element) in elements" ng-if="element.data.getMonth() + 1 === mese.numero && findById(element.performance, timeline.idmateria) !== - 1">
                                        {{element.nome}}
                                        <div class="btn-group pull-right">
                                            <a class="btn btn-info btn-sm tooltip-base" ng-click="onSetDate(i)">
                                                <span class="tooltip" ng-if="element.settingDate">Conferma data</span>
                                                <span class="tooltip" ng-if="!element.settingDate">Imposta data</span>
                                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                                                {{element.data| date:'dd/MM/yyyy'}}
                                            </a>
                                            <div class="picker-container" id="picker{{i}}"></div>
                                            <a class="btn btn-warning btn-sm tooltip-base" ng-click="setUndone(i)">
                                                <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                                                <span class="tooltip">Annulla svolgimento</span>
                                            </a>
                                            <a class="btn btn-danger btn-sm tooltip-base" ng-click="onRemoveFromTimeline(i)">
                                                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                                                <span class="tooltip">Elimina</span>
                                            </a>
                                        </div>
                                        <div class="subjects pull-right">
                                            <span ng-repeat="(j,materia) in materie.content">
                                                <div class="subject tooltip-base" ng-style="{'background-color':materie.colors[j]}" ng-if="findById(element.performance, materia.id) !== - 1">
                                                    <span class="tooltip">{{materia.nome}} : {{findObjectById(element.performance, materia.id).data | date:'dd/MM/yyyy'}}</span>
                                                </div>
                                            </span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
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
        </div>
    </div>
</body>
</html>