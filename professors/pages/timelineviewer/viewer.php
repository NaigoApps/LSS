
<?php
require_once '../../../common/auth-header.php';

$year = $_SESSION['timeline-year'];
$subject = $_SESSION['timeline-subject'];
$class_a = $_SESSION['timeline-class-year'];
$class_s = $_SESSION['timeline-class-section'];
$subject_id = $_SESSION['timeline-subject-id'];
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
        <title>Timeline | Basic demo</title>

        <style type="text/css">
            body, html {
                font-family: sans-serif;
            }
        </style>
        <script src="../../../common/scripts/jquery.js"></script>
        <script src="includes/timeline/alert/dist/sweetalert.min.js"></script>
        <link href="includes/timeline/alert/dist/sweetalert.css" rel="stylesheet" type="text/css"/>
        <script src="includes/timeline/dist/timeline2.js"></script>
        <script type="text/javascript" src="includes/timeline/dist/jsDatePick.min.1.3.js"></script>
        <link rel="stylesheet" type="text/css" media="all" href="includes/timeline/dist/jsDatePick_ltr.min.css" />    
        <link rel="stylesheet" href="../../../common/styles/bootstrap.min.css">
        <link rel="stylesheet" href="../../../common/styles/style.css">
        <script type="text/javascript" src="../../../common/scripts/bootstrap.min.js"></script>
        <script type="text/javascript" src="../../../common/scripts/angular.min.js"></script>
        <script src="../../../common/scripts/links.js"></script>
        <script src="scripts/script2.js"></script>

        <script type="text/javascript">
            var data = JSON.parse(<?php echo $content; ?>);
            var from = '<?php echo $year; ?>';
            var to = '<?php echo ($year + 1); ?>';</script>
        <link href="includes/timeline/dist/timeline.css" rel="stylesheet" type="text/css" />
    </head>
    <body>

        <?php require_once '../../../common/authentication-bar.php'; ?>

        <h1 class="text-center">Visualizzazione programmazione</h1>

        <div ng-app="lss-db" ng-controller="linkController as linkCtrl">
            <div class="wrapper" ng-app="lss-db" ng-controller="timelineController as tCtrl">

                <div class="row top-sep more">
                    <button class="btn btn-sm btn-default col-sm-1" id="moveLeft" value="Muovi indietro">
                        <span class="glyphicon glyphicon-arrow-left"></span>
                    </button>
                    <div id="visualization" class="col-sm-10 top-sep"></div>
                    <button class="btn btn-sm btn-default col-sm-1" id="moveRight" value="Muovi avanti">
                        <span class="glyphicon glyphicon-arrow-right"></span>
                    </button>
                </div>
                <div id="loading">loading...</div>
                <p></p>
                <div id="log"></div>

                <div class="row top-sep">
                    <div class="col-sm-3">Data iniziale: <input type="text" size="12" id="inputField" /></div>
                    <div class="col-sm-3">Data finale: <input type="text" size="12" id="inputField2" /></div>
                    <div class="col-sm-1">
                        <button class="btn btn-sm btn-default" type="button" id="focus" value="&uarr; Focus" title="FOCUS">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </button> 
                    </div>
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

                <a class="btn btn-warning col-sm-2 col-sm-offset-5 top-sep" ng-click="exit()">
                    Esci
                </a>
            </div>
        </div>



    </body>
</html>