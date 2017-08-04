<?php require_once __DIR__ . '/../common/php/consts.php'; ?>
<?php require_once __DIR__ . '/../common/auth-header.php'; ?>
<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/bootstrap.min.css"; ?>>
        <link rel="stylesheet" href=<?php echo WEB . "/common/styles/style.css"; ?>>
        <link rel="stylesheet" href=<?php echo WEB . "/common/swal/sweetalert.css"; ?>>
        <script type="text/javascript" src=<?php echo WEB . "/common/swal/sweetalert.min.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/jquery.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/bootstrap.min.js"; ?>></script>
        <script type="text/javascript" src=<?php echo WEB . "/common/scripts/angular.min.js"; ?>></script>

        <script type="text/javascript" src="script.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="collapse navbar-collapse">
                    <?php require_once __DIR__ . '/../common/authentication-bar.php'; ?>
                </div><!-- /.navbar-collapse -->

            </div><!-- /.container-fluid -->
        </nav>
        <div class="container">
            <div class="row">
                <div class="jumbotron text-center"><h1>Registrazione completa</h1></div>
                <div class="col-sm-6 col-sm-offset-3" ng-app="lss-db" ng-controller="newUserController as userCtrl">

                    <h3>Puoi ancora modificare i tuoi dati</h3>
                    
                    <form class="dummy-form row">
                        <div class="form-group">
                            <label>Nome:</label>
                            <input class="form-control" type="text" placeholder="Nome" ng-model="name">
                        </div>
                        <div class="form-group">
                            <label>Cognome:</label>
                            <input class="form-control" type="text" placeholder="Cognome" ng-model="surname">
                        </div>
                        <ul class="list-group top-sep">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true">
                                    {{classes.selected != undefined ? classes.selected.year + classes.selected.section:"Selezionare una classe"}}  
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li ng-repeat="class in classes.content"
                                        ng-click="onSelectClass(class)">
                                        <a>{{class.year + class.section}}</a>
                                    </li>
                                </ul>
                            </div>
                        </ul>
                        <a class="btn btn-sm btn-success bot-sep" href="#" ng-click="updateUser()">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            Aggiorna i dati
                        </a>
                    </form>


                </div>
            </div>
        </div>
    </body>
</html>