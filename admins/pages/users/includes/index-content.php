
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">
                <span class="glyphicon glyphicon-user"></span>

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
                        <a>Gestione degli utenti</a>
                    </li>
                </ul>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="container under-nav" ng-app="lss-db" ng-controller="userController as userCtrl">

    <table class="table table-striped table-bordered">
        <tr>
            <th class="text-center">Utente</th>
            <th class="text-center">Admin</th>
            <th class="text-center">Docente</th>
            <th class="text-center">Studente</th>
            <th class="text-center"></th>
        </tr>
        <tr ng-repeat="user in userCtrl.visibleUsers">
            <td>{{user.email}}</td>

            <td>
                <a class="col-sm-3 btn btn-xs btn-default" ng-if="!userCtrl.isStudent(user)" ng-click="userCtrl.toggleAdmin(user)">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true" ng-if='userCtrl.isAdmin(user)'></span>
                    <span class="glyphicon glyphicon-remove" aria-hidden="true" ng-if='!userCtrl.isAdmin(user)'></span>
                </a>
            </td>
            <td>
                <a class="col-sm-3 btn btn-xs btn-default" ng-if="!userCtrl.isStudent(user)" ng-click="userCtrl.toggleProfessor(user)">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true" ng-if='userCtrl.isProfessor(user)'></span>
                    <span class="glyphicon glyphicon-remove" aria-hidden="true" ng-if='!userCtrl.isProfessor(user)'></span>
                </a>
            </td>
            <td>
                <a class="col-sm-3 btn btn-xs btn-default" ng-if="!userCtrl.isProfessor(user) && !userCtrl.isAdmin(user)" ng-click="userCtrl.toggleStudent(user)">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true" ng-if='userCtrl.isStudent(user)'></span>
                    <span class="glyphicon glyphicon-remove" aria-hidden="true" ng-if='!userCtrl.isStudent(user)'></span>
                </a>
            </td>

            <!-- Edit -->
            <td>
                <!-- Delete -->
                <div class="btn-group">
                    <a class="btn btn-xs btn-danger tooltip-base"
                       ng-click="userCtrl.delUser(user.id)">                                
                        <span class="tooltip-text">Elimina</span>
                        <span class="glyphicon glyphicon-trash"></span>
                    </a>
                </div>
            </td>

        </tr>
    </table>

</div>
