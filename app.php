<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="./styles/jquery.svg.css"></link>
    <link rel="stylesheet" href="./styles/base.css"></link>
    <link rel="stylesheet" href="./styles/login.css"></link>
    <script type="text/javascript" src="./scripts/jquery.js"></script>
    <script type="text/javascript" src="./scripts/jquery.svg.min.js"></script>
    <script type="text/javascript" src="./scripts/angular.min.js"></script>
    <script type="text/javascript" src="./scripts/app.js"></script>
</head>
<body>
<div class="wrapper">
    <div class="app-content" ng-app="lss" ng-controller="DatabaseController as db">
        <div class="module-list">
            <ul>
                <li ng-repeat="module in db.modules">
                {{module.nome}}
                </li>
            </ul>
        </div>

        <form name="module-form" 
        ng-controller="ModuleController as mCtrl"
        ng-submit="mCtrl.addModule()">
            <label for="name">Nome</label>
            <input name="name" type="text" ng-model="mCtrl.module.name"></input>
            <label>{{mCtrl.module.name}}</label>
            <label for="description">Descrizione</label>
            <textarea name="description" ng-model="mCtrl.module.description"></textarea>
            
            <input class="button" name="module-submit" type="submit" value="Aggiungi"></input>
        </form>

    </div>
</div>
</body>
</html>