<?php
require_once '../../../common/auth-header.php';
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Gestione del materiale</title>
        <meta charset="UTF-8">
        <script src=<?php echo WEB . "/common/scripts/jquery.js"; ?>></script>
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/bootstrap.min.css"; ?>>
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/style.css"; ?>>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/bootstrap.min.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/angular.min.js"; ?>></script>
        <link rel="stylesheet" href=<?php echo WEB . "/common/swal/sweetalert.css"; ?>>
        <script type="text/javascript" src=<?php echo WEB . "/common/swal/sweetalert.min.js"; ?>></script>
        <script src="materialmanager.js"></script>

    </head>
    <body>

        <div ng-app="lss-db" ng-controller="timelineController as timeCtrl">
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>
                    </div>

                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a ng-click="exit()">Torna al menu</a></li>
                                </ul>
                            </li>
                            <li>
                                <a>Gestione del materiale</a>
                            </li>
                        </ul>
                        <?php require_once __DIR__ . '/../../../common/authentication-bar.php'; ?>
                    </div><!-- /.navbar-collapse -->

                </div><!-- /.container-fluid -->
            </nav>
            <div class="container under-nav">
                <div class="row">

                    <div class="col-sm-4 bg-info">
                        <h4 class="text-center">Moduli:</h4>
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownModules" data-toggle="dropdown" aria-haspopup="true">
                                {{modules.selected != undefined ? modules.selected.name:"Selezionare un modulo"}}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownModules">
                                <li ng-repeat="module in modules.content"
                                    ng-click="onSelectModule(module)">
                                    <a>{{module.name}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-4 bg-info"
                         ng-if="modules.selected !== undefined">
                        <h4 class="text-center">Argomenti:</h4>
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownTopics" data-toggle="dropdown" aria-haspopup="true">
                                {{topics.selected != undefined ? topics.selected.name:"Selezionare un argomento"}}  
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownTopics">
                                <li ng-repeat="topic in topics.content"
                                    ng-click="onSelectTopic(topic)">
                                    <a>{{topic.name}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-4 bg-info"
                         ng-if="topics.selected !== undefined">
                        <h4 class="text-center">Voci:</h4>
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownItems" data-toggle="dropdown" aria-haspopup="true">
                                {{items.selected != undefined ? items.selected.name:"Selezionare una voce"}}  
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownTopics">
                                <li ng-repeat="item in items.content"
                                    ng-click="onSelectItem(item)">
                                    <a>{{item.name}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="row top-sep" ng-if="!addMode">
                    <button class="btn btn-lg btn-default add-item col-sm-2 col-sm-offset-5"
                            ng-disabled="modules.selected == undefined && topics.selected == undefined && items.selected == undefined"
                            ng-click="onAddMaterial()">
                        Aggiungi
                    </button>
                </div>

                <div class="row top-sep" ng-if="addMode">
                    <form class="col-sm-8 col-sm-offset-2">
                        <h3>Nuovo materiale per {{newMaterial.element.name}}</h3>
                        <div class="form-group">
                            <label>Nome visualizzato:</label>
                            <input class="form-control" type="text" placeholder="Nome" ng-model="newMaterial.name">
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" ng-model="newMaterial.internal">Collega file
                            </label>
                        </div>
                        <div class="form-group" ng-if="!newMaterial.internal">
                            <label>Link:</label>
                            <input class="form-control" type="text" placeholder="Nome" ng-model="newMaterial.url">
                        </div>
                        <div class="form-group" ng-if="newMaterial.internal">
                            <h4 class="text-center">Files:</h4>
                            <div class="list-group">
                                <div ng-class="{'active' : file.id === newMaterial.file.id}"
                                     class="list-group-item clearfix"
                                     ng-if="file.element.id === getRightElement().id"
                                     ng-repeat="file in files.content"
                                     ng-click="onSelectFile(file)">
                                    {{file.name}}
                                    <button class="btn btn-xs btn-danger pull-right"
                                            ng-click="onDeleteFile(file)">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </button>
                                </div>
                            </div>
                            <label>Carica:</label>
                            <input name="document" id="file-loader" type="file" class="form-control">
                            <div class="text-center">
                                <button class="btn btn-lg btn-default" ng-click="onUploadFile()">
                                    <span class="glyphicon glyphicon-upload"></span>
                                </button>
                            </div>
                            <div id="file-upload-progress-container" class="progress" hidden>
                                <div id="file-upload-progress" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" ng-model="newMaterial.private">Privato
                            </label>
                        </div>

                        <div class="pull-right btn-group" role="group">
                            <a class="btn btn-xs btn-success" ng-click="onConfirmNewMaterial()">
                                <span class="glyphicon glyphicon-ok"></span>
                            </a>
                            <a class="btn btn-xs btn-warning" ng-click="onDiscardNewMaterial()">
                                <span class="glyphicon glyphicon-remove"></span>
                            </a>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <h3 class="text-center">{{getRightElement().name}}</h3>

                    <div class="col-sm-10 col-sm-offset-1 panel panel-default">

                        <div id="material-load-progress-container" class="progress" hidden>
                            <div id="material-load-progress" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item clearfix" ng-repeat="(i,material) in materials.content">
                                <a ng-if="material.url" target="_blank" href="{{material.url}}">{{material.name}}</a>
                                <a ng-if="!material.url" target="_blank" href="{{material.file.url}}">{{material.name}}</a>
                                <span ng-if="material.private">(Privato)</span>
                                <div class="btn-group pull-right">
                                    <button class="btn btn-danger btn-md tooltip-base pull-right" ng-click="onDeleteMaterial(material)">
                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>