
<div class="well container">
    <h4>Home</h4>
    <div class="row">
        <a class="col-sm-3 btn btn-sm btn-default" href="./pages/timelinecreator/index.php">
            <strong class="text-info">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Nuova programmazione
            </strong>
        </a>
<!--
        <a class="col-sm-3 btn btn-sm btn-default" href="./pages/timelinemanager/index.php">
            <strong class="text-info">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                Gestisci programmazioni
            </strong>
        </a>

        <a class="col-sm-3 btn btn-sm btn-default" href="./pages/timelineviewer/index.php">
            <strong class="text-info">
                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                Visualizza programmazioni
            </strong>
        </a>-->


        <?php
        if ($user_data->isAdmin()) {
            ?>

            <a class="col-sm-3 btn btn-sm btn-default" href="../admins/index.php">
                <strong class="text-warning">
                    <span class="glyphicon glyphicon-console" aria-hidden="true"></span>
                    Sezione amministratore
                </strong>
            </a>


            <?php
        }
        ?>
    </div>
</div>

<div ng-app="lss-db" ng-controller="timelineController
        as
        timCtrl" class="container top-sep">
    <h4>Programmazioni attive</h4>
    <div>
        <div id="bar-timeline" class="progress top-sep" hidden>
            <div class="progress-bar progress-bar-success progress-bar-striped active" style="width: 100%">
            </div>
        </div>
        <ul class="row">
            <li class="col-sm-3 col-xs-4" ng-repeat = "timeline in timelines.content">
                <div class="dropdown row">
                    <button class="btn btn-default dropdown-toggle col-sm-12" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        {{timeline.classe}}{{timeline.sezione}} - {{timeline.materia}} - {{timeline.anno}}/{{timeline.anno2}}
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu row">
                        <li>
                            <form action="./pages/timelinemanager/editor2.php" method="POST">
                                <input type="hidden" name="timelineid" value="{{timeline.id}}"/>    
                                <a class="btn text-success" onclick="this.parentNode.submit()">
                                    <span class="glyphicon glyphicon-edit"></span>
                                    Gestisci
                                </a>
                            </form>
                        </li>
                        <li>
                            <form action="./pages/timelineviewer/viewer.php" method="POST">
                                <input type="hidden" name="timelineid" value="{{timeline.id}}"/>    
                                <a class="btn text-success" onclick="this.parentNode.submit()">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                    Visualizza
                                </a>
                            </form>
                        </li>
                        <li>
                            <form action="./pages/timelinemanager/print.php" method="POST">
                                <input type="hidden" name="timelineid" value="{{timeline.id}}"/>    
                                <a class="btn" onclick="this.parentNode.submit()">
                                    <span class="glyphicon glyphicon-print"></span>
                                    Stampa
                                </a>
                            </form>
                        </li>
                        <li>
                            <form action="./pages/timelinecreator/index.php" method="POST">
                                <input type="hidden" name="timelineid" value="{{timeline.id}}"/> 
                                <input type="hidden" name="copy" value="true"/>    
                                <a class="btn" onclick="this.parentNode.submit()">
                                    <span class="glyphicon glyphicon-copy"></span>
                                    Duplica
                                </a>
                            </form>

                        </li>
                        <li>
                            <form>
                                <a class="btn text-warning" ng-click="onStoreTimeline(timeline)">
                                    <span class="glyphicon glyphicon-cloud-download"></span>
                                    Archivia
                                </a>
                            </form>


                        </li>
                        <li>
                            <form>
                                <a class="btn text-danger" ng-click="onDeleteTimeline(timeline)">
                                    <span class="glyphicon glyphicon-remove"></span>
                                    Rimuovi
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>

    <a id="btn-stored" class="btn btn-info" ng-click="onShowStored()">Mostra archiviate</a>
    <div id="bar-stored" class="progress top-sep" hidden>
        <div class="progress-bar progress-bar-success progress-bar-striped active" style="width: 100%">
        </div>
    </div>
    <ul class="row" ng-hide="storedTimelines.hidden">
        <h3>Programmazioni archiviate</h3>
        <li class="col-sm-3 col-xs-4" ng-repeat="timeline in storedTimelines.content">
            <div class="dropdown row">
                <button class="btn btn-default dropdown-toggle col-sm-12" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    {{timeline.classe}}{{timeline.sezione}} - {{timeline.materia}} - {{timeline.anno}}/{{timeline.anno2}}
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu row">
                    <li>
                        <form action="./pages/timelinecreator/index.php" method="POST">
                            <input type="hidden" name="timelineid" value="{{timeline.id}}"/> 
                            <input type="hidden" name="copy" value="true"/>    
                            <a class="btn" onclick="this.parentNode.submit()">
                                <span class="glyphicon glyphicon-copy"></span>
                                Duplica
                            </a>
                        </form>

                    </li>
                    <li>
                        <form>
                            <a class="btn" ng-click="onUnStoreTimeline(timeline)">
                                <span class="glyphicon glyphicon-cloud-download"></span>
                                Togli dall'archivio
                            </a>
                        </form>
                    </li>
                    <li>
                        <form>
                            <a class="btn" ng-click="onDeleteTimeline(timeline)">
                                <span class="glyphicon glyphicon-remove"></span>
                                Rimuovi
                            </a>
                        </form>
                    </li>
                </ul>
            </div>

        </li>
    </ul>
</div>

