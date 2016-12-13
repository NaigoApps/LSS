<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">
                <span class="glyphicon glyphicon-calendar"></span>
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
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a>Creazione programmazione</a>
                    </li>
                </ul>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="under-nav" ng-app="lss-db" ng-controller="linkController
            as linkCtrl">
    <div class="container" ng-app="lss-db" ng-controller="classesController
                as classCtrl">
        <div class="row">

            <div class="col-sm-6 col-sm-offset-3">
                <div class="row">
                    <h1 class="text-center">Dati della programmazione</h1>
                    <h4>Selezionare la classe:</h4>
                    <ul class="list-group top-sep">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true">
                                {{classi.selected.anno + classi.selected.sezione}}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li ng-repeat="classe in classi.content"
                                    ng-click="onSelectClass(classe)">
                                    <a>{{classe.anno + classe.sezione}}</a>
                                </li>
                            </ul>
                        </div>
                    </ul>
                </div>
                <div class="row">
                    <h4>Selezionare la materia:</h4>

                    <ul class="list-group top-sep">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true">
                                {{materie.selected.nome}}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li ng-repeat="materia in materie.content"
                                    ng-click="onSelectSubject(materia)">
                                    <a>{{materia.nome}}</a>
                                </li>
                            </ul>
                        </div>
                    </ul>
                </div>
                <div class="row">

                    <h4>Selezionare l'anno scolastico</h4>
                    <ul class="list-group top-sep">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" ng-if="!anni.selected">
                                <span class="caret"></span>
                            </button>
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" ng-if="anni.selected">
                                {{anni.selected}}/{{anni.selected + 1}}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li ng-repeat="y in anni.content"
                                    ng-click="onSelectYear(y)">
                                    <a>{{y}}/{{y + 1}}</a>
                                </li>
                            </ul>
                        </div>
                    </ul>
                </div>
                <div class="row top-sep">
                    <?php
                    if (isset($_POST['copy'])) {
                        ?>
                        <a class="btn btn-success" ng-click="onCopyTimeline(<?php echo $_POST['timelineid']; ?>)">Copia</a>
                        <?php
                    } else {
                        ?>
                        <a class="btn btn-success" ng-click="onConfirmTimeline()">Conferma</a>
                        <?php
                    }
                    ?>
                </div>

                <div class="row top-sep">
                    <h1>Lista delle programmazioni</h1>
                    <ul>
                        <li ng-repeat = "timeline in timelines.content">
                            <div class = "well well-sm clearfix">
                                {{timeline.classe}}{{timeline.sezione}} - {{timeline.materia}} - {{timeline.anno}}/{{timeline.anno2}}
                                <form class = "pull-right dummy-form" action = "../timelinemanager/editor2.php" method = "POST">
                                    <input type = "hidden" name = "timelineid" value = "{{timeline.id}}"/>
                                    <button type = "submit" class = "btn btn-xs btn-success tooltip-base">
                                        <span class = "tooltip-text">Gestisci</span>
                                        <span class = "glyphicon glyphicon-edit"></span>
                                    </button>
                                    <a class = "btn btn-xs btn-danger tooltip-base" ng-click = "onDeleteTimeline(timeline)">
                                        <span class = "tooltip-text">Rimuovi</span>
                                        <span class = "glyphicon glyphicon-remove"></span>
                                    </a>
                                </form>
                            </div>
                        </li>
                    </ul>

                    <div class = "row error-message top-sep">
                        <div class = "alert alert-danger error-message col-sm-12" role = "alert" hidden>
                            <span class = "glyphicon glyphicon-exclamation-sign" aria-hidden = "true"></span>
                            <span class = "sr-only">Errore:</span>
                            {{lastErrorMessage}}
                        </div>
                    </div>
                    <div class = "row success-message top-sep">
                        <div class = "alert alert-success success-message col-sm-12" role = "alert" hidden>
                            <span class = "glyphicon glyphicon-ok-sign" aria-hidden = "true"></span>
                            <span class = "sr-only">Successo:</span>
                            {{lastSuccessMessage}}
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>