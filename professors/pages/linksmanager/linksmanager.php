<?php
require_once '../../../common/auth-header.php';
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Gestione dei collegamenti</title>
        <meta charset="UTF-8">
        <script src=<?php echo WEB . "/common/scripts/jquery.js"; ?>></script>
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/bootstrap.min.css"; ?>>
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/style.css"; ?>>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/bootstrap.min.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/angular.min.js"; ?>></script>
        <link rel="stylesheet" href=<?php echo WEB . "/common/swal/sweetalert.css"; ?>>
        <script type="text/javascript" src=<?php echo WEB . "/common/swal/sweetalert.min.js"; ?>></script>
        <script src="linksmanager.js"></script>

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
                                <a>Gestione dei collegamenti</a>
                            </li>
                        </ul>
                        <?php require_once __DIR__ . '/../../../common/authentication-bar.php'; ?>
                    </div><!-- /.navbar-collapse -->

                </div><!-- /.container-fluid -->
            </nav>
            <div class="container under-nav">
                <div class="row">
                    <!--First element-->
                    <div class="col-sm-4">
                        <div class="row">
                            <div class="col-sm-12 bg-info">
                                <h4 class="text-center">Moduli:</h4>
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownModules" data-toggle="dropdown" aria-haspopup="true">
                                        {{modules1.selected != undefined ? modules1.selected.name:"Selezionare un modulo"}}
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownModules">
                                        <li ng-repeat="module in modules1.content"
                                            ng-click="onSelectModule1(module)">
                                            <a>{{module.name}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 bg-info"
                                 ng-if="modules1.selected !== undefined">
                                <h4 class="text-center">Argomenti:</h4>
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownTopics" data-toggle="dropdown" aria-haspopup="true">
                                        {{topics1.selected != undefined ? topics1.selected.name:"Selezionare un argomento"}}  
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownTopics">
                                        <li ng-repeat="topic in topics1.content"
                                            ng-click="onSelectTopic1(topic)">
                                            <a>{{topic.name}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 bg-info"
                                 ng-if="topics1.selected !== undefined">
                                <h4 class="text-center">Voci:</h4>
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownItems" data-toggle="dropdown" aria-haspopup="true">
                                        {{items1.selected != undefined ? items1.selected.name:"Selezionare una voce"}}  
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownTopics">
                                        <li ng-repeat="item in items1.content"
                                            ng-click="onSelectItem1(item)">
                                            <a>{{item.name}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Add link-->
                    <button class="btn btn-lg btn-success col-sm-2 col-sm-offset-1"
                            ng-if="currentLink === undefined"
                            ng-click="onLinkElements()">
                        Aggiungi
                    </button>
                    <button class="btn btn-lg btn-warning col-sm-2 col-sm-offset-1"
                            ng-if="currentLink !== undefined"
                            ng-click="onUnlinkElements()">
                        Rimuovi
                    </button>
                    <!--Second element-->
                    <div class="col-sm-4 col-sm-offset-1">
                        <div class="row">
                            <div class="col-sm-12 bg-info">
                                <h4 class="text-center">Moduli:</h4>
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownModules" data-toggle="dropdown" aria-haspopup="true">
                                        {{modules2.selected != undefined ? modules2.selected.name:"Selezionare un modulo"}}
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownModules">
                                        <li ng-repeat="module in modules2.content"
                                            ng-click="onSelectModule2(module)">
                                            <a>{{module.name}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 bg-info"
                                 ng-if="modules2.selected !== undefined">
                                <h4 class="text-center">Argomenti:</h4>
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownTopics" data-toggle="dropdown" aria-haspopup="true">
                                        {{topics2.selected != undefined ? topics2.selected.name:"Selezionare un argomento"}}  
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownTopics">
                                        <li ng-repeat="topic in topics2.content"
                                            ng-click="onSelectTopic2(topic)">
                                            <a>{{topic.name}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 bg-info"
                                 ng-if="topics2.selected !== undefined">
                                <h4 class="text-center">Voci:</h4>
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle full" type="button" id="dropdownItems" data-toggle="dropdown" aria-haspopup="true">
                                        {{items2.selected != undefined ? items2.selected.name:"Selezionare una voce"}}  
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownTopics">
                                        <li ng-repeat="item in items2.content"
                                            ng-click="onSelectItem2(item)">
                                            <a>{{item.name}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>