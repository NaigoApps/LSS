
<?php require_once '../../../common/authentication-bar.php'; ?>
<div ng-app="lss-db" ng-controller="linkController as linkCtrl">
    <div class="wrapper" ng-app="lss-db" ng-controller="classesController as classCtrl">
        <h1>Creazione di una timeline</h1>
        <div class="row">
            <h4>Selezionare la classe:</h4>
            <ul class="list-group top-sep">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownItems" data-toggle="dropdown" aria-haspopup="true">
                        {{classi.selected.nome}}
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownModules">
                        <li ng-repeat="classe in classi.content"
                            ng-click="onSelectClass(classe)">
                            <a>{{classe.nome}}</a>
                        </li>
                    </ul>
                </div>
            </ul>
        </div>
        <div class="row">
            <h4>Selezionare la materia:</h4>

            <ul class="list-group top-sep">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownItems" data-toggle="dropdown" aria-haspopup="true">
                        {{materie.selected.nome}}
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownModules">
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
            <select name="singleSelect" ng-model="anno">
                <option value="{{y}}" ng-repeat="y in anni">{{y}}/{{y + 1}}</option>
            </select>
        </div>
        <div class="row top-sep">
            <a class="btn btn-success" ng-click="onConfirmTimeline()">Conferma</a>
            <a class="btn btn-warning" ng-click="onCancelTimeline()">Annulla</a>
        </div>
        <div class="row error-message top-sep">
            <div class="alert alert-danger error-message col-sm-12" role="alert" hidden>
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only">Errore:</span>
                {{lastErrorMessage}}
            </div>
        </div>
        <div class="row success-message top-sep">
            <div class="alert alert-success success-message col-sm-12" role="alert" hidden>
                <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
                <span class="sr-only">Successo:</span>
                {{lastSuccessMessage}}
            </div>
        </div>

    </div>
</div>
