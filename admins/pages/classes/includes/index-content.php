<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">
                <span class="glyphicon glyphicon-cloud"></span>
            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="../..">Esci</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="under-nav" ng-app="lss-db" ng-controller="linkController as linkCtrl">
    <div class="container" ng-app="lss-db" ng-controller="classesController as classCtrl">
        <h2>Elenco dell classi:</h2>
        <ul class="list-group top-sep">
            <div class="row" ng-repeat="classe in classi.content">
                <li class="list-group-item col-sm-12">
                    <form class="form-inline" ng-class="{'has-warning' : classe.modified}" >
                        <div class="form-group">
                            <label for="{{classe.anno + classe.sezione + 'a'}}">Anno:</label>
                            <input id="{{classe.anno + classe.sezione + 'a'}}" type="text" class="form-control" placeholder="anno" ng-keypress="classe.modified = true" ng-model="classe.anno"/>
                        </div>
                        <div class="form-group">
                            <label for="{{classe.anno + classe.sezione + 's'}}">Sezione:</label>
                            <input id="{{classe.anno + classe.sezione + 's'}}" type="text" class="form-control" placeholder="sezione" ng-keypress="classe.modified = true" ng-model="classe.sezione"/>
                        </div>
                        <!-- Delete -->
                        <a class="right btn btn-xs btn-default"
                           ng-click="onDeleteClass(classe)">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                        <!-- Edit -->
                        <a class="right btn btn-xs btn-default"
                           ng-click="onEditClass(classe)">
                            <span class="glyphicon glyphicon-save"></span>
                        </a>
                    </form>
                </li>
            </div>
        </ul>
        <form class="form-inline">
            <div>Nuova classe:</div>
            <input type="text" class="form-control" placeholder="anno" ng-keypress="classe.modified = true" ng-model="newClass.anno"/>
            <input type="text" class="form-control" placeholder="nome" ng-keypress="classe.modified = true" ng-model="newClass.sezione"/>
            <button class="btn btn-default" ng-click="onAddClass()">Aggiungi classe</button>
        </form>

    </div>

    <div class="row error message">
        <div class="alert alert-danger error-message col-sm-12" role="alert" hidden>
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Errore:</span>
            {{lastErrorMessage.msg}}
        </div>
    </div>
    <div class="row success message">
        <div class="alert alert-success success-message col-sm-12" role="alert" hidden>
            <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
            <span class="sr-only">Successo:</span>
            {{lastSuccessMessage.msg}}
        </div>
    </div>
</div>
