<?php
require_once '../common/auth-header.php';
$id = $_SESSION["user_data"]->getId();
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
            var student_id = <?php echo $id; ?>;
        </script>
        <script src="main.js"></script>

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
                                <li ng-repeat="schedule in schedules.content">
                                    <a ng-if="schedule.visible" ng-click="onToggleSchedule(schedule)">Nascondi {{schedule.subject.name}}</a>
                                    <a ng-if="!schedule.visible" ng-click="onToggleSchedule(schedule)">Mostra {{schedule.subject.name}}</a>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li><a ng-if="!doneElements" ng-click="onShowDone()">Visualizza svolti</a></li>
                                <li><a ng-if="doneElements" ng-click="onHideDone()">Nascondi svolti</a></li>
                                <li><a ng-if="!assignedElements" ng-click="onShowAssigned()">Visualizza assegnati</a></li>
                                <li><a ng-if="assignedElements" ng-click="onHideAssigned()">Nascondi assegnati</a></li>
                                <li><a ng-if="!todoElements" ng-click="onShowTodo()">Visualizza da svolgere</a></li>
                                <li><a ng-if="todoElements" ng-click="onHideTodo()">Nascondi da svolgere</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a ng-click="exit()">Indietro</a></li>
                            </ul>
                        </li>
                        <li>
                            <a>Visualizzazione programmazioni {{schedules.content[0].year}}/{{schedules.content[0].year2}} - Classe {{schedules.content[0].class.year}}{{schedules.content[0].class.section}}:
                                <span class="small" ng-repeat="schedule in schedules.content| filter: {visible : true}">
                                    <span>{{schedule.subject.name}}</span>
                                    <span ng-if="!$last">, </span>
                                </span></a>
                        </li>
                    </ul>
                    <?php require_once __DIR__ . '/../common/authentication-bar.php'; ?>
                </div><!-- /.navbar-collapse -->

            </div><!-- /.container-fluid -->
        </nav>

        <div class="container under-nav">
            <div class="progress">
                <div id="bar-timeline" class="progress-bar progress-bar-success progress-bar-striped active" style="width: 100%">
                </div>
            </div>
            <div id="visualization" class="row top-sep clearfix"></div>
            <div class="row" ng-if="currentElement !== undefined">
                <div class="col-sm-12">
                    <div class="row top-sep well">
                        <h2 class="text-center">{{currentElement.name}}</h4>
                            <p>{{currentElement.description}}</p>
                            <h3>Materiale</h3>
                            <div class="well well-sm" ng-repeat="material in materials.content">
                                <a ng-if="material.url" target="_blank" href="{{material.url}}">
                                    <span class="glyphicon glyphicon-link"></span>
                                    {{material.name}}
                                </a>
                                <a ng-if="!material.url" target="_blank" href="{{material.file.url}}">
                                    <span class="glyphicon glyphicon-file"></span>
                                    {{material.name}}
                                </a>
                            </div>

                            <h3>Collegamenti</h3>
                            <div class="row top-sep">
                                <h4 ng-if="currentElement.parent">{{currentElement.name}} è contenuto in: </h4>
                                <span class="well well-sm" ng-if="currentElement.parent">
                                    <a ng-click="replaceCurrentElement(currentElement.parent)">
                                        {{currentElement.parent.name}}
                                    </a>
                                </span>
                            </div>
                            <div class="row top-sep">
                                <h4 ng-if="currentElement.children.length > 0">{{currentElement.name}} contiene:</h4>
                                <span class="well well-sm" ng-repeat="child in currentElement.children">
                                    <a ng-click="replaceCurrentElement(child)">
                                        {{child.name}}
                                    </a>
                                </span>
                            </div>
                            <div class="row top-sep">
                                <h4 ng-if="elementLinks(currentElement).length > 0">{{currentElement.name}} è collegato a:</h4>
                                <span class="well well-sm" ng-repeat="link in elementLinks(currentElement)">
                                    <a ng-click="replaceCurrentElement(link)">
                                        {{link.name}}
                                    </a>
                                </span>
                            </div>
                    </div>
                </div>
            </div>

        </div>


    </body>
</html>