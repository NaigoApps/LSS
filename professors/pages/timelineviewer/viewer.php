<?php
require_once '../../../common/auth-header.php';
$id = $_POST['timelineid'];
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
            var timeline_id = <?php echo $id; ?>;
        </script>
        <script src="viewer.js"></script>

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
                        <li>
                            <a data-toggle="modal" data-target="#info">
                                <span class="glyphicon glyphicon-info-sign"/>
                            </a>
                        </li>
                    </ul>
                    <?php require_once __DIR__ . '/../../../common/authentication-bar.php'; ?>
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
                        <h2 class="text-center">{{currentElement.name}} <small>{{currentTimelineElement && currentTimelineElement.message ? "(" + currentTimelineElement.message + ")" : ""}}</small></h4>
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
        <div class="modal fade" tabindex="-1" role="dialog" id="info">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title text-center">FLUX</h4>
                        <p class="text-center">Visualizzazione dello sviluppo temporale dei saperi scientifici</p>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <img src="../../../logo.png" width="200"/>
                        </div>
                        <p><b>Cos’è?</b></p>
                        <p>Flux è un software per la visualizzazione in tempo reale degli argomenti svolti
                            nel biennio nelle Scienze Integrate (Fisica, Chimica, Scienze della Terra e
                            Biologia)
                            Tramite l’applicazione è possibile, sia per gli studenti che per i docenti,
                            visualizzare lo sviluppo temporale dei vari argomenti svolti nelle materie che
                            fanno riferimento alle Scienze integrate.
                            La visualizzazione dei vari argomenti/concetti svolti appare in un grafico che
                            cresce nel tempo (“timeline”), il quale individua i collegamenti tra gli
                            argomenti delle varie discipline e dà anche la possibilità di poter accedere a
                            file esplicativi o a contenuti multimediali.
                        </p>
                        <p><b>Quali scopi ha?</b></p>
                        <ul class="decorated">
                            <li>favorire una didattica innovativa tendente all’integrazione degli insegnamenti
                                di Fisica, Scienze della Terra, Chimica e Biologia, proponendo un linguaggio
                                scientifico omogeneo, modelli comparabili e temi che abbiano una valenza
                                unificante nell’ insegnamento delle scienze integrate;</li>
                            <li>
                                sfruttare la sinergia tra i diversi ambiti disciplinari per raggiungere meglio gli
                                obiettivi che ciascun insegnamento persegue in termini di competenze, abilità
                                e conoscenze, facilitando gli apprendimenti trasversali alle diverse materie;
                            </li>
                            <li>
                                promuovere una metodologia comune di insegnamento delle scienze e
                                sviluppare nuove forme di comunicazione e di cooperazione fra i docenti per
                                favorire il lavoro in team dei docenti del consiglio di classe nella
                                programmazione dell’attività didattica;
                            </li>
                            <li>
                                avere un riscontro immediato dello stato di avanzamento del programma
                                nelle varie materie delle scienze integrate per ottimizzare i tempi di
                                svolgimento del programma scolastico e per affinare la progettazione del
                                piano di lavoro annuale;
                            </li>
                            <li>
                                poter sperimentare più agevolmente nuove metodologie didattiche (es.
                                “flipped classroom”);
                            </li>
                            <li>
                                fornire agli studenti una serie di sussidi didattici digitali (testo, immagini e
                                video) che possono integrare (o sostituire) il libro di testo;
                            </li>
                            <li>
                                aiutare nello studio gli studenti che non possono frequentare la scuola per
                                motivi di salute;
                            </li>
                            <li>
                                favorire la cultura scientifica negli studenti mediante strategie didattiche
                                adattate in base fabbisogni e al tempo disponibile nelle lezioni, attraverso la
                                selezione e l’adattamento dei contenuti e del materiale didattico da parte
                                dell’insegnante;
                            </li>
                            <li>
                                semplificare e velocizzare il lavoro in classe del docente per l’assegnazione
                                del materiale di studio agli studenti.
                            </li>
                        </ul>
                        <p><b>A chi è rivolto?</b></p>
                        <p>
                            A tutti gli studenti del biennio dell’Istituto e a tutti gli insegnanti di Scienze
                            integrate del Biennio (Fisica, Chimica, Scienze della Terra e Biologia)*.
                            *in futuro l’uso del software potrebbe essere esteso ai docenti di tutte le discipline
                        </p>
                        <p><b>Come fare ad utilizzarlo?</b></p>
                        <p>
                            Basta entrare nel sito … dalla homepage dell’ITTS Fedi Fermi (cliccare sul
                            logo in basso a sinistra) con le credenziali della posta elettronica dell’istituto
                            (@ittfedifermi.gov.it o @studenti-ittfedifermi.gov.it) ed aspettare
                            l’approvazione da parte dell’amministratore
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </body>
</html>