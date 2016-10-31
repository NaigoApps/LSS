
<?php
require_once '../../../common/authentication-bar.php';
?>
<h1 class="text-center">Elenco delle programmazioni</h1>
<div class="wrapper" ng-app="lss-db" ng-controller="timelineController as tCtrl">

    <div class="row">

        <div class="col-sm-6">
            <ul>
                <li ng-repeat="timeline in timelines.content">
                    <div class="well well-sm clearfix">
                        {{timeline.classe}}{{timeline.sezione}} - {{timeline.materia}} - {{timeline.anno}}
                        <div class="pull-right">
                            <a class="btn btn-xs btn-info tooltip-base" ng-click="onPrintTimeline(timeline.id)">
                                <span class="tooltip-text">Stampa</span>
                                <span class="glyphicon glyphicon-print"></span>
                            </a>
                            <a class="btn btn-xs btn-success tooltip-base" ng-click="onManageTimeline(timeline.id)">
                                <span class="tooltip-text">Gestisci</span>
                                <span class="glyphicon glyphicon-edit"></span>
                            </a>
                            <a class="btn btn-xs btn-danger tooltip-base" ng-click="onDeleteTimeline(timeline.id)">
                                <span class="tooltip-text">Rimuovi</span>
                                <span class="glyphicon glyphicon-remove"></span>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

    </div>
    <button class="btn btn-warning col-sm-2 col-sm-offset-5" ng-click="onExit()">
        Esci
    </button>

    <!-- Delete Modal -->
    <div id="deleteTimeline" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center">Elminazione timeline</h3>
                </div>
                <div class="modal-body">
                    <form name="timelineDeleteForm" novalidate>
                        <p>Confermare l'eliminazione?</p>
                        <button type="submit" class="btn btn-success col-sm-2 col-sm-offset-3"
                                ng-click="onDeleteCurrentTimeline()"
                                data-dismiss="modal">
                            <span class="glyphicon glyphicon-ok"></span>
                        </button>
                        <button type="submit" class="btn btn-danger col-sm-2 col-sm-offset-1"
                                data-dismiss="modal">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>