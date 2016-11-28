<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">
                <span class="glyphicon glyphicon-list"></span>
                Elenco delle programmazioni
            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="../..">Home</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="container under-nav" ng-app="lss-db" ng-controller="timelineController as tCtrl">

    <div class="row">

        <div class="col-sm-6 col-sm-offset-3">
            <div id="bar-timeline" class="progress top-sep" hidden>
                <div class="progress-bar progress-bar-success progress-bar-striped active" style="width: 100%">
                </div>
            </div>
            <ul>
                <li ng-repeat="timeline in timelines.content">
                    <div class="well well-sm clearfix">
                        Programmazione di {{timeline.materia}}, <b>{{timeline.classe}}{{timeline.sezione}}</b> - {{timeline.anno}}/{{timeline.anno2}}
                        <div class="pull-right">
                            <form class="dummy-form" action="../timelinemanager/editor2.php" method="POST">
                                <input type="hidden" name="timelineid" value="{{timeline.id}}"/>    
                                <button type="submit" class="btn btn-xs btn-success tooltip-base">
                                    <span class="tooltip">Gestisci</span>
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                            </form>
                            <form class="dummy-form" action="../timelinemanager/print.php" method="POST">
                                <input type="hidden" name="timelineid" value="{{timeline.id}}"/>    
                                <button class="btn btn-xs btn-info tooltip-base">
                                    <span class="tooltip">Stampa</span>
                                    <span class="glyphicon glyphicon-print"></span>
                                </button>
                            </form>
                            <form class="dummy-form" action="../timelinecreator/index.php" method="POST">
                                <input type="hidden" name="timelineid" value="{{timeline.id}}"/> 
                                <input type="hidden" name="copy" value="true"/>    
                                <button type="submit" class="btn btn-xs btn-info tooltip-base">
                                    <span class="tooltip">Duplica</span>
                                    <span class="glyphicon glyphicon-duplicate"></span>
                                </button>
                            </form>
                            <a class="btn btn-xs btn-warning tooltip-base" ng-click="onStoreTimeline(timeline)">
                                <span class="tooltip">Archivia</span>
                                <span class="glyphicon glyphicon-cloud-download"></span>
                            </a>
                            <a class="btn btn-xs btn-danger tooltip-base" ng-click="onDeleteTimeline(timeline)">
                                <span class="tooltip">Rimuovi</span>
                                <span class="glyphicon glyphicon-remove"></span>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
            <a id="btn-stored" class="btn btn-info" ng-click="onShowStored()">Mostra archiviate</a>
            <div id="bar-stored" class="progress top-sep" hidden>
                <div class="progress-bar progress-bar-success progress-bar-striped active" style="width: 100%">
                </div>
            </div>
            <ul class="list-group" ng-hide="storedTimelines.hidden">
                <h3>Programmazioni archiviate</h3>
                <li class="list-group-item" ng-repeat="timeline in storedTimelines.content">
                    Programmazione di {{timeline.materia}}, <b>{{timeline.classe}}{{timeline.sezione}}</b> - {{timeline.anno}}/{{timeline.anno2}}                    <div class="pull-right">
                        <form class="dummy-form" action="../timelinecreator/index.php" method="POST">
                            <input type="hidden" name="timelineid" value="{{timeline.id}}"/> 
                            <input type="hidden" name="copy" value="true"/>    
                            <button type="submit" class="btn btn-xs btn-info tooltip-base">
                                <span class="tooltip">Duplica</span>
                                <span class="glyphicon glyphicon-duplicate"></span>
                            </button>
                        </form>
                        <a class="btn btn-xs btn-success tooltip-base" ng-click="onUnStoreTimeline(timeline)">
                            <span class="tooltip">Togli dall'archivio</span>
                            <span class="glyphicon glyphicon-cloud-upload"></span>
                        </a>
                        <a class="btn btn-xs btn-danger tooltip-base" ng-click="onDeleteTimeline(timeline)">
                            <span class="tooltip">Rimuovi</span>
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>

    </div>

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