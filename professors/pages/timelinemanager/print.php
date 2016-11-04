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

        <?php require_once '../../../common/authentication-bar.php'; ?>


        <div class="wrapper" ng-app="lss-db" ng-controller="timelineController as timeCtrl">
            <h1 class="text-center">Programmazione {{timeline.annoclasse + timeline.sezione + " " + timeline.anno}}</h1>

            <div>
                <div ng-repeat="month in mesi">
                    <h3>{{month.nome}}</h3>
                    <div class="row" ng-repeat="element in timeline track by $index" ng-if="element.date.month === month.numero">
                        <div class="col-sm-6 timeline-item">
                            {{element.content}}
                        </div>
                        <div class="col-sm-6 timeline-item" ng-if="element.performed">
                            Svolto in data {{element.date.day + '/' + element.date.month + '/' + element.date.year}}
                        </div>
                        <div class="col-sm-6 timeline-item" ng-if="!element.performed">
                            Da svolgere
                        </div>
                    </div>
                </div>
            </div>
            <div class="save-buttons row always-bottom">
                <a class="btn btn-success col-xs-3 col-xs-offset-2 hidden-print" type="button" title="SALVA" ng-click="print()">Stampa</a>
                <a class="btn btn-warning col-xs-3 col-xs-offset-2 hidden-print" type="button" title="SALVA" ng-click="exit()">Esci</a>
            </div>
        </div>



    </body>
</html>