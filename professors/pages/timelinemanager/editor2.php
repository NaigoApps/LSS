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
        <script src="scripts/script2.js"></script>
        <link rel="stylesheet"  href="styles/style.css"/>

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

                <div class="top-sep">
                    <button class="btn btn-lg btn-success add-item"
                            ng-disabled="mesi.selected == undefined"
                            ng-click="onAddToTimeline()">
                        Aggiungi
                    </button>
                </div>

                <div class="alert alert-success" ng-if="!monthToAdd">
                    <h1 class="glyphicon glyphicon-arrow-up"></h1>
                    <h1>Selezionare un mese...</h1>
                </div>
                
                <div class="well timeline-month" ng-if="monthToAdd">
                    <h3>{{monthToAdd.nome}}</h3>
                    <div class="timeline-item" ng-repeat="element in timeline track by $index " ng-if="element.start.getMonth() === monthToAdd.numero">
                        {{element.content}}
                        <a class="btn btn-danger right" ng-click="removeFromTimeline($index)">
                            <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                            Elimina
                        </a>
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
                <div class="save-buttons row always-bottom">
                    <a class="btn btn-success col-sm-3 col-sm-offset-1" type="button" title="SALVA" ng-click="saveData()">Salva</a>
                    <a class="btn btn-success col-sm-2 col-sm-offset-1" type="button" title="SALVA" ng-click="saveDataExit()">Salva ed esci</a>
                    <a class="btn btn-warning col-sm-3 col-sm-offset-1" type="button" title="SALVA" ng-click="exit()">Esci senza salvare</a>
                </div>
            </div>



        </div>


    </body>
</html>