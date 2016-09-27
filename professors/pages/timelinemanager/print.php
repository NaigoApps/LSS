<?php
require_once '../../../common/auth-header.php';

$year = $_SESSION['timeline-year'];
$subject = $_SESSION['timeline-subject'];
$subject_id = $_SESSION['timeline-subject-id'];
$class_a = $_SESSION['timeline-class-year'];
$class_s = $_SESSION['timeline-class-section'];
$class_id = $_SESSION['timeline-class-id'];
$folder = $_SESSION['timeline-folder'];
$filename = "../../timelines/" . $folder . "/$year-$class_a-$class_s-$class_id-$subject-$subject_id.json";
$file = fopen($filename, "r") or die("Unable to open file!");
$content = str_replace(["\r\n", "\n", "\r"], ' ', fread($file, filesize($filename)));
$content = json_encode($content);
fclose($file);
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
        <script src="scripts/print-script.js"></script>


        <script type="text/javascript">
                    var data = JSON.parse(<?php echo $content; ?>);
                    var from = '<?php echo $year; ?>';
                    var to = '<?php echo ($year + 1); ?>';</script>
    </head>
    <body>

        <?php require_once '../../../common/authentication-bar.php'; ?>


        <div class="wrapper" ng-app="lss-db" ng-controller="timelineController as timeCtrl">
            <h1 class="text-center">Programmazione <?php echo $year . "/" . ($year + 1) . " - " . $class_a . $class_s . " " . $subject; ?></h1>

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