<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">
                <span class="glyphicon glyphicon-blackboard"></span>

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
                        <a>Gestione delle classi</a>
                    </li>
                </ul>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="under-nav" ng-app="lss-db" ng-controller="linkController as linkCtrl">
    <div class="container" ng-app="lss-db" ng-controller="classesController as classCtrl">
        <h2 class="text-center">Elenco delle classi</h2>
        <div class="row">
            <ul class="list-group top-sep col-sm-8 col-sm-offset-2">
                <li class="list-group-item" ng-repeat="classe in classi.content| orderBy:['anno', 'sezione']">
                    <form class="form-inline dummy-form" ng-class="{'has-warning' : classe.modified}" >
                        <div class="form-group">
                            <label for="{{classe.anno + classe.sezione + 'a'}}">Anno:</label>
                            <input id="{{classe.anno + classe.sezione + 'a'}}" type="text" class="form-control" placeholder="anno" ng-keypress="classe.modified = true" ng-model="classe.anno"/>
                        </div>
                        <div class="form-group">
                            <label for="{{classe.anno + classe.sezione + 's'}}">Sezione:</label>
                            <input id="{{classe.anno + classe.sezione + 's'}}" type="text" class="form-control" placeholder="sezione" ng-keypress="classe.modified = true" ng-model="classe.sezione"/>
                        </div>
                        <!-- Delete -->
                        <div class="btn-group pull-right">
                            <!-- Edit -->
                            <a class="btn btn-xs btn-success tooltip-base"
                               ng-click="onEditClass(classe)">
                                Salva
                                <span class="glyphicon glyphicon-save"></span>
                                <span class="tooltip">Salva</span>
                            </a>
                            <a class="btn btn-xs btn-danger tooltip-base"
                               ng-click="onDeleteClass(classe)">
                                Elimina
                                <span class="glyphicon glyphicon-remove"></span>
                                <span class="tooltip">Elimina</span>
                            </a>
                        </div>
                    </form>
                </li>
            </ul>
            <form class="well form-inline col-sm-8 col-sm-offset-2">
                <div>Nuova classe:</div>
                <input type="text" class="form-control" placeholder="anno" ng-keypress="classe.modified = true" ng-model="newClass.anno"/>
                <input type="text" class="form-control" placeholder="nome" ng-keypress="classe.modified = true" ng-model="newClass.sezione"/>
                <button class="btn btn-default" ng-click="onAddClass()">Aggiungi classe</button>
            </form>
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
