<?php require_once '../../../common/auth-header.php'; ?>

<!doctype html>
<html>
    <head>
        <title>Gestione database</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/bootstrap.min.css"; ?>>
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/style.css"; ?>>
        <link rel="stylesheet" href=<?php echo WEB . "/common/swal/sweetalert.css"; ?>>
        <script type="text/javascript" src=<?php echo WEB . "/common/swal/sweetalert.min.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/jquery.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/bootstrap.min.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/angular.min.js"; ?>></script>
        <script type="text/javascript" src="elements.manager.js"></script>
    </head>
    <body>

        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">
                        <span class="glyphicon glyphicon-plus"></span>
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
                            <a>Gestione database</a>
                        </li>
                    </ul>
                    <?php require_once __DIR__ . '/../../../common/authentication-bar.php'; ?>
                </div>
            </div>
        </nav>
        <div class="container under-nav" ng-app="lss-db" ng-controller="dbController as dbCtrl">
            <div class="row">
                <div class="col-sm-4">

                    <h2>Moduli</h2>
                    <input type="text" class="form-control" placeholder="ricerca"
                           ng-model="modules.searchString"
                           ng-keyup="onSearchModule()"/>
                    <ul class="list-group top-sep">


                        <div class="row top-sep" ng-if="!modules.addMode">
                            <button class="btn btn-default col-sm-2 col-sm-offset-5" ng-click="onAddModule()">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </div>

                        <div class="row" ng-if="modules.addMode">
                            <h3 class="text-center">
                                Nuovo modulo
                                <a class="btn btn-xs btn-success" ng-click="onConfirmAddModule()">
                                    <span class="glyphicon glyphicon-ok"></span>
                                </a>
                                <a class="btn btn-xs btn-warning" ng-click="onDiscardAddModule()">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </a>
                            </h3>
                            <li class="list-group-item col-sm-12">
                                <form>
                                    <div class="form-group">
                                        <label>Nome:</label>
                                        <input class="form-control" type="text" placeholder="Nome" ng-model="newModule.name">
                                    </div>
                                    <div class="form-group">
                                        <label>Descrizione:</label>
                                        <textarea maxlength="500" rows="5" cols="50" class="form-control" placeholder="Descrizione" ng-model="newModule.description"></textarea>
                                    </div>
                                </form>
                            </li>
                        </div>

                        <div class="row" ng-repeat="module in modules.content">
                            <!-- Module -->
                            <li id="mod{{module.id}}" class="list-group-item module-name col-sm-12" ng-if="!module.editMode"
                                ng-class="{'current': module === modules.selected}"
                                ng-click="onSelectModule(module)">
                                {{module.name}}

                                <div class="pull-right btn-group" role="group">

                                    <a class="btn btn-xs btn-default" ng-click="onEditModule(module)">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </a>
                                    <a class="btn btn-xs btn-default" ng-click="onDeleteElement(module)">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </a>
                                </div>
                            </li>

                            <li class="list-group-item module-name col-sm-12" ng-if="module.editMode">

                                <form>
                                    <div class="form-group">
                                        <label>Nome:</label>
                                        <input class="form-control" type="text" placeholder="Nome" ng-model="module.newName">
                                    </div>
                                    <div class="form-group">
                                        <label>Descrizione:</label>
                                        <textarea maxlength="500" rows="5" cols="50" class="form-control" placeholder="Descrizione" ng-model="module.newDescription"></textarea>
                                    </div>

                                </form>
                                <div class="pull-right btn-group" role="group">
                                    <a class="btn btn-xs btn-success" ng-click="onConfirmEditElement(module)">
                                        <span class="glyphicon glyphicon-ok"></span>
                                    </a>
                                    <a class="btn btn-xs btn-warning" ng-click="onDiscardEditModule(module)">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </a>
                                </div>
                            </li>
                        </div>
                    </ul>
                </div>
                <div class="col-sm-4">

                    <h2>Argomenti</h2>
                    <input type="text" class="form-control" placeholder="ricerca"
                           ng-model="topics.searchString"
                           ng-keyup="onSearchTopic()"/>
                    <ul class="list-group top-sep">


                        <div class="row top-sep" ng-if="!topics.addMode">
                            <button class="btn btn-default col-sm-2 col-sm-offset-5" ng-click="onAddTopic()"
                                    ng-disabled="modules.selected === undefined">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </div>

                        <div class="row" ng-if="topics.addMode">
                            <h3 class="text-center">
                                Nuovo argomento
                                <a class="btn btn-xs btn-success" ng-click="onConfirmAddTopic()">
                                    <span class="glyphicon glyphicon-ok"></span>
                                </a>
                                <a class="btn btn-xs btn-warning" ng-click="onDiscardAddTopic()">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </a>
                            </h3>
                            <li class="list-group-item col-sm-12">
                                <form>
                                    <div class="form-group">
                                        <label>Nome:</label>
                                        <input class="form-control" type="text" placeholder="Nome" ng-model="newTopic.name">
                                    </div>
                                    <div class="form-group">
                                        <label>Descrizione:</label>
                                        <textarea maxlength="500" rows="5" cols="50" class="form-control" placeholder="Descrizione" ng-model="newTopic.description"></textarea>
                                    </div>
                                </form>
                            </li>
                        </div>

                        <div class="row" ng-repeat="topic in topics.content">
                            <li class="list-group-item col-sm-12" ng-if="!topic.editMode"
                                ng-class="{'current': topic === topics.selected}"
                                ng-click="onSelectTopic(topic)">
                                {{topic.name}}

                                <div class="pull-right btn-group" role="group">

                                    <a class="btn btn-xs btn-default" ng-click="onEditTopic(topic)">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </a>
                                    <a class="btn btn-xs btn-default" ng-click="onDeleteElement(topic)">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </a>
                                </div>
                            </li>

                            <li class="list-group-item col-sm-12" ng-if="topic.editMode">

                                <form>
                                    <div class="form-group">
                                        <label>Nome:</label>
                                        <input class="form-control" type="text" placeholder="Nome" ng-model="topic.newName">
                                    </div>
                                    <div class="form-group">
                                        <label>Descrizione:</label>
                                        <textarea maxlength="500" rows="5" cols="50" class="form-control" placeholder="Descrizione" ng-model="topic.newDescription"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Modulo:</label>
                                        <select class="form-control" ng-model="topic.newParent"
                                                ng-options="module as module.name for module in modules.content track by module.id">
                                        </select>
                                    </div>
                                </form>
                                <div class="pull-right btn-group" role="group">
                                    <a class="btn btn-xs btn-success" ng-click="onConfirmEditElement(topic)">
                                        <span class="glyphicon glyphicon-ok"></span>
                                    </a>
                                    <a class="btn btn-xs btn-warning" ng-click="onDiscardEditTopic(topic)">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </a>
                                </div>
                            </li>
                        </div>
                    </ul>
                </div>
                <div class="col-sm-4">

                    <h2>Voci</h2>
                    <input type="text" class="form-control" placeholder="ricerca"
                           ng-model="items.searchString"
                           ng-keyup="onSearchItem()"/>
                    <ul class="list-group top-sep">


                        <div class="row top-sep" ng-if="!items.addMode">
                            <button class="btn btn-default col-sm-2 col-sm-offset-5" ng-click="onAddItem()"
                                    ng-disabled="topics.selected === undefined">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </div>

                        <div class="row" ng-if="items.addMode">
                            <h3 class="text-center">
                                Nuova voce
                                <a class="btn btn-xs btn-success" ng-click="onConfirmAddItem()">
                                    <span class="glyphicon glyphicon-ok"></span>
                                </a>
                                <a class="btn btn-xs btn-warning" ng-click="onDiscardAddItem()">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </a>
                            </h3>
                            <li class="list-group-item col-sm-12">
                                <form>
                                    <div class="form-group">
                                        <label>Nome:</label>
                                        <input class="form-control" type="text" placeholder="Nome" ng-model="newItem.name">
                                    </div>
                                    <div class="form-group">
                                        <label>Descrizione:</label>
                                        <textarea maxlength="500" rows="5" cols="50" class="form-control" placeholder="Descrizione" ng-model="newItem.description"></textarea>
                                    </div>
                                </form>
                            </li>
                        </div>

                        <div class="row" ng-repeat="item in items.content">
                            <li class="list-group-item col-sm-12" ng-if="!item.editMode">
                                {{item.name}}

                                <div class="pull-right btn-group" role="group">

                                    <a class="btn btn-xs btn-default" ng-click="onEditItem(item)">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </a>
                                    <a class="btn btn-xs btn-default" ng-click="onDeleteElement(item)">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </a>
                                </div>
                            </li>

                            <li class="list-group-item col-sm-12" ng-if="item.editMode">

                                <form>
                                    <div class="form-group">
                                        <label>Nome:</label>
                                        <input class="form-control" type="text" placeholder="Nome" ng-model="item.newName">
                                    </div>
                                    <div class="form-group">
                                        <label>Descrizione:</label>
                                        <textarea maxlength="500" rows="5" cols="50" class="form-control" placeholder="Descrizione" ng-model="item.newDescription"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Modulo:</label>
                                        <select class="form-control" ng-model="item.baseModule"
                                                ng-options="module as module.name for module in modules.content track by module.id"
                                                ng-change="onSelectBaseModule(item)">
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Argomento:</label>
                                        <select class="form-control" ng-model="item.newParent"
                                                ng-options="topic as topic.name for topic in item.editTopics.content track by topic.id">
                                        </select>
                                    </div>

                                </form>
                                <div class="pull-right btn-group" role="group">
                                    <a class="btn btn-xs btn-success" ng-click="onConfirmEditElement(item)">
                                        <span class="glyphicon glyphicon-ok"></span>
                                    </a>
                                    <a class="btn btn-xs btn-warning" ng-click="onDiscardEditItem(item)">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </a>
                                </div>
                            </li>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>