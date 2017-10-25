<?php
require_once '../../../common/auth-header.php';

$id = filter($_POST['timelineid']);
?>
<script type="text/javascript">
    var schedule_id = <?php echo $id ?>;</script>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Gestione programmazione</title>

        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/bootstrap.min.css"; ?>>
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/style.css"; ?>>
        <link rel="stylesheet" href=<?php echo WEB . "/common/swal/sweetalert.css"; ?>>
        <script type="text/javascript" src=<?php echo WEB . "/common/swal/sweetalert.min.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/jquery.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/bootstrap.min.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/angular.min.js"; ?>></script>

        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/jquery-ui.min.css"; ?>>
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/jquery-ui.structure.min.css"; ?>>
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/jquery-ui.theme.min.css"; ?>>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/jquery-ui.min.js"; ?>></script>

        <script src="script.js"></script>

    </head>
    <body>
        <div ng-app="lss-db" ng-controller="timelineController as timeCtrl">
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
                                    <li>
                                        <a ng-click="saveData()">Salva</a>
                                    </li>
                                    <li><a ng-click="saveDataExit()">Salva e torna al menu</a></li>
                                    <li><a ng-click="exit()">Torna al menu</a></li>
                                </ul>
                            </li>
                            <li>
                                <a>Programmazione {{schedule.class.year + schedule.class.section + " " + schedule.year}}/{{schedule.year2}} ({{schedule.subject.name}})</a>
                            </li>
                        </ul>
                        <?php require_once __DIR__ . '/../../../common/authentication-bar.php'; ?>
                    </div><!-- /.navbar-collapse -->

                </div><!-- /.container-fluid -->
            </nav>
            <div class="container under-nav">
                <div class="row">
                    <div class="col-sm-3 bg-primary">
                        <h4 class="text-center">Mese:</h4>
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownItems" data-toggle="dropdown" aria-haspopup="true">
                                {{months.selected != undefined ? months.selected.name:"Selezionare un mese"}}  
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownTopics">
                                <li ng-repeat="month in months.content"
                                    ng-click="onSelectMonth(month)">
                                    <a>{{month.name}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3 bg-info">
                        <h4 class="text-center">Moduli:</h4>
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownModules" data-toggle="dropdown" aria-haspopup="true">
                                {{modules.selected != undefined ? modules.selected.name:"Selezionare un modulo"}}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownModules">
                                <li ng-repeat="module in modules.content"
                                    ng-click="onSelectModule(module)">
                                    <a>{{module.name}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3 bg-info"
                         ng-if="modules.selected !== undefined">
                        <h4 class="text-center">Argomenti:</h4>
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownTopics" data-toggle="dropdown" aria-haspopup="true">
                                {{topics.selected != undefined ? topics.selected.name:"Selezionare un argomento"}}  
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownTopics">
                                <li ng-repeat="topic in topics.content"
                                    ng-click="onSelectTopic(topic)">
                                    <a>{{topic.name}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3 bg-info"
                         ng-if="topics.selected !== undefined">
                        <h4 class="text-center">Voci:</h4>
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownItems" data-toggle="dropdown" aria-haspopup="true">
                                {{items.selected != undefined ? items.selected.name:"Selezionare una voce"}}  
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownTopics">
                                <li ng-repeat="item in items.content"
                                    ng-click="onSelectItem(item)">
                                    <a>{{item.name}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row top-sep">
                    <button class="btn btn-lg btn-success add-item col-sm-2 col-sm-offset-5"
                            ng-disabled="months.selected == undefined"
                            ng-click="onAddToTimeline()">
                        Aggiungi
                    </button>
                </div>
                <div class="row top-sep" ng-repeat="month in months.content">
                    <div class="col-sm-12 panel panel-default">
                        <div class="row">
                            <h3 class="panel-heading">{{month.name}} <small>non svolti</small></h3>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item status-{{element.status}}" ng-repeat="(i, element) in schedule.elements" 
                                ng-if="element.date.getMonth() + 1 === month.number">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="label-group">
                                        <div class="label-group-item col-sm-1 module-name">
                                            {{element.element.parent.parent.name}}
                                        </div>
                                        <div class="label-group-item col-sm-2 topic-name">
                                            {{element.element.parent.name}}
                                        </div>
                                        <div class="label-group-item col-sm-2 item-name">
                                            {{element.element.name}}
                                        </div>
                                            </div>
                                        <div class="col-sm-7">
                                            <div class="pull-right">
                                                <div class="subjects">
                                                    <span ng-repeat="subject in subjects.content">
                                                        <div class="subject tooltip-base" ng-style="{'background-color':subject.color}" ng-if="subjectStatus(element, subject) !== 'todo'">
                                                            <span class="tooltip">{{subject.name}} : {{subjectDate(element, subject) | date:'dd/MM/yyyy'}}</span>
                                                        </div>
                                                    </span>
                                                </div>
                                                <div class="btn-group">
                                                    <button class="btn btn-info btn-md tooltip-base" ng-click="onSetDate(i)">
                                                        <span class="tooltip" ng-if="!element.settingDate">Imposta data</span>
                                                        <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                                                        {{element.date| date:'dd/MM/yyyy'}}
                                                    </button>
                                                    <div class="picker-container" id="picker{{i}}"></div>
                                                    <button class="btn btn-danger btn-md tooltip-base" ng-click="setTodo(element, i)"
                                                            ng-disabled="element.status === 'todo'">
                                                        <span ng-if="element.status !== 'todo'">Annulla svolgimento</span>
                                                        <span ng-if="element.status === 'todo'">Non svolto</span>
                                                    </button>
                                                    <button class="btn btn-warning btn-md tooltip-base" ng-click="setAssigned(element, i)"
                                                            ng-disabled="element.status === 'assigned'">
                                                        <span ng-if="element.status !== 'assigned'">Assegna</span>
                                                        <span ng-if="element.status === 'assigned'">Assegnato</span>
                                                    </button>
                                                    <button class="btn btn-success btn-md tooltip-base" ng-click="setDone(element, i)"
                                                            ng-disabled="element.status === 'done'">
                                                        <span ng-if="element.status !== 'done'">Svolgi</span>
                                                        <span ng-if="element.status === 'done'">Svolto</span>
                                                    </button>
                                                    <button class="btn btn-danger btn-md tooltip-base" ng-click="onRemoveFromTimeline(element, i)">
                                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>&nbsp;
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
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
</body>
</html>