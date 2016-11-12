<?php
require_once '../../../common/auth-header.php';
$id = $_POST['timelineid'];
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Stampa programmazione</title>

        <script src="../../../common/scripts/jquery.js"></script>
        <script src="includes/timeline/alert/dist/sweetalert.min.js"></script>
        <link href="includes/timeline/alert/dist/sweetalert.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="../../../common/styles/bootstrap.min.css">
        <link rel="stylesheet" href="../../../common/styles/style.css">
        <script type="text/javascript" src="../../../common/scripts/bootstrap.min.js"></script>
        <script type="text/javascript" src="../../../common/scripts/angular.min.js"></script>
        <link rel="stylesheet" href="styles/print.css" media="all"/>      

        <script type="text/javascript">
                    var timeline_id = <?php echo $id ?>;</script>
        <script src="scripts/print-script.js"></script>

    </head>
    <body>

        <div ng-app="lss-db" ng-controller="timelineController as timeCtrl">
            <nav class="navbar navbar-default navbar-fixed-top hidden-print">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#">
                            <span class="glyphicon glyphicon-cloud"></span>
                        </a>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a ng-click="print()">Stampa</a></li>
                                    <li><a href="../timelinemanager/">Esci</a></li>
                                </ul>
                            </li>
                            <ul class="nav navbar-nav navbar-right">
                                <li>
                                    <a>Pagina di stampa</a>
                                </li>
                            </ul>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
            <div class="container under-nav">
                <h1 class="text-center">Programmazione {{timeline.annoclasse + timeline.sezione + " " + timeline.anno}} ({{timeline.nomemateria}})</h1>

                <div>
                    <div ng-repeat="month in mesi">
                        <h3>{{month.nome}}</h3>
                        <div class="row" ng-repeat="element in elements track by $index" ng-if="element.data.getMonth() + 1 === month.numero">
                            <div class="col-xs-6 timeline-item">
                                {{element.nome}}
                            </div>
                            <div class="col-xs-6 timeline-item" ng-if="element.performed">
                                Svolto in data {{element.data| date:'dd/MM/yyyy'}}
                            </div>
                            <div class="col-xs-6 timeline-item" ng-if="!element.performed">
                                Da svolgere
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </body>
</html>