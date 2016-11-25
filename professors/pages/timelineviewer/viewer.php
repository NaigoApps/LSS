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
        <script src="../../../common/scripts/jquery.js"></script>
        <script src="../../../common/swal/sweetalert.min.js"></script>
        <link href="../../../common/swal/sweetalert.css" rel="stylesheet" type="text/css"/>
        <script src="../../../common/timeline/timeline2.js"></script>
        <link rel="stylesheet" href="../../../common/styles/bootstrap.min.css">
        <link rel="stylesheet" href="../../../common/styles/style.css">
        <script type="text/javascript" src="../../../common/scripts/bootstrap.min.js"></script>
        <script type="text/javascript" src="../../../common/scripts/angular.min.js"></script>
        <script src="../../../common/scripts/links.js"></script>
        <script src="scripts/script.js"></script>

        <script type="text/javascript">
                    var timeline_id = <?php echo $id; ?>;</script>
        <link href="../../../common/timeline/timeline.css" rel="stylesheet" type="text/css" />
    </head>
    <body>

        <h1 class="text-center">Visualizzazione programmazione</h1>

        <div ng-app="lss-db" ng-controller="linkController as linkCtrl">
            <div class="container" ng-app="lss-db" ng-controller="timelineController as tCtrl">

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
                    <!--TODO: INSERIRE CALENDAR-->
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
            </div>
        </div>



    </body>
</html>