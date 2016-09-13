<?php
require_once '../../../common/auth-header.php';

$year = $_SESSION['timeline-year'];
$subject = $_SESSION['timeline-subject'];
$class = $_SESSION['timeline-class'];
$folder = $_SESSION['timeline-folder'];
$filename = "../../" . $folder . "/$year-$class-$subject.json";
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
        <script src="scripts/script.js"></script>
        <script src="styles/style.css"></script>
        
        <script type="text/javascript">
                    var data = JSON.parse(<?php echo $content; ?>);
                    var from = '<?php echo $year; ?>';
                    var to = '<?php echo ($year + 1); ?>';</script>
        <link href="includes/timeline/dist/timeline.css" rel="stylesheet" type="text/css" />
    </head>
    <body>

        <?php require_once '../../../common/authentication-bar.php'; ?>

        <h1 class="text-center">Creazione programmazione</h1>

        <div class="wrapper" ng-app="lss-db" ng-controller="linkController as linkCtrl">
            <div ng-app="lss-db" ng-controller="timelineController as timeCtrl">
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
                         ng-if="moduli.selected != undefined">
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
                         ng-if="argomenti.selected != undefined">
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
                    <button class="btn btn-lg btn-success"
                            ng-disabled="mesi.selected == undefined"
                            ng-click="onAddToTimeline()">
                        Aggiungi
                    </button>
                </div>

                
                
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
            </div>
        </div>



        <div class="buttons row always-bottom">
            <input class="btn btn-success col-sm-2 col-sm-offset-2" type="button" id="save" value="Salva" title="SALVA" onclick="saveData()">
            <input class="btn btn-success col-sm-2 col-sm-offset-1" type="button" id="save" value="Salva ed esci" title="SALVA" onclick="saveDataExit()">
            <input class="btn btn-warning col-sm-2 col-sm-offset-1" type="button" id="save" value="Esci senza salvare" title="SALVA" onclick="exit()">
        </div>
    </body>
</html>