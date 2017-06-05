<?php
require_once '../../../common/auth-header.php';
$id = $_POST['timelineid'];
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Stampa programmazione</title>

        <script src="../../../common/scripts/jquery.js"></script>
        <link rel="stylesheet" href="../../../common/styles/bootstrap.min.css">
        <link rel="stylesheet" href="../../../common/styles/style.css">
        <script type="text/javascript" src="../../../common/scripts/bootstrap.min.js"></script>
        <script type="text/javascript" src="../../../common/scripts/angular.min.js"></script>
        <link rel="stylesheet" href="styles/print.css" media="all"/>      
        <script src="../../../common/scripts/links.js"></script>
        <script type="text/javascript">
            var schedule_id = <?php echo $id ?>;</script>
        <script src="print-script.js"></script>

    </head>
    <body>

        <div ng-app="lss-db" ng-controller="timelineController as timeCtrl">
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#">
                            <span class="glyphicon glyphicon-print"></span>
                        </a>
                    </div>

                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a ng-click="print()">Stampa</a></li>
                                    <li><a ng-click="exit()">Torna al menu</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Filtra<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a ng-if="!showTodo" ng-click="onShowTodo()">Mostra non svolti</a></li>
                                    <li><a ng-if="showTodo" ng-click="onHideTodo()">Nascondi non svolti</a></li>
                                    <li><a ng-if="!showAssigned" ng-click="onShowAssigned()">Mostra assegnati</a></li>
                                    <li><a ng-if="showAssigned" ng-click="onHideAssigned()">Nascondi assegnati</a></li>
                                    <li><a ng-if="!showDone" ng-click="onShowDone()">Mostra svolti</a></li>
                                    <li><a ng-if="showDone" ng-click="onHideDone()">Nascondi svolti</a></li>
                                </ul>
                            </li>
                            <li>
                                <a>Stampa programmazione</a>
                            </li>
                        </ul>
                        <?php require_once __DIR__ . '/../../../common/authentication-bar.php'; ?>
                    </div>
                </div>
            </nav>
            <div class="container under-nav">
                <h1 class="text-center">Programmazione di {{schedule.subject.name}}, {{schedule.class.year + schedule.class.section + " " + schedule.year}}/{{schedule.year2}}</h1>

                <div>
                    <table class="table table-bordered table-condensed table-striped" ng-repeat="month in months.content" ng-if='month.elements > 0'>
                        <tr><th class="h4" colspan="4">{{month.name}}</th></tr>
                        <tr><th>Modulo</th><th>Argomento</th><th>Voce</th><th>Data di svolgimento</th></tr>
                        <tr ng-repeat="element in schedule.elements track by $index" ng-if="element.date.getMonth() + 1 === month.number && isElementVisible(element)">
                            <td class="col-xs-3">
                                <span ng-if="element.moduleVisible">
                                    {{element.element.parent.parent.name}}
                                </span>
                            </td>
                            <td class="col-xs-3">
                                <span ng-if="element.topicVisible">
                                    {{element.element.parent.name}}
                                </span>
                            </td>
                            <td class="col-xs-3">
                                {{element.element.name}}
                            </td>
                            <td class="text-success col-xs-3" ng-if="element.status === 'done'">
                                {{element.date| date:'dd/MM/yyyy'}}
                            </td>
                            <td class="text-warning col-xs-3" ng-if="element.status === 'assigned'">
                                {{element.date| date:'dd/MM/yyyy'}} (assegnato)
                            </td>
                            <td class="text-danger col-xs-3" ng-if="element.status === 'todo'">
                                {{element.date| date:'dd/MM/yyyy'}} (previsto)
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </body>
</html>