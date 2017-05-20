<div class="row" ng-app="lss-db" ng-controller="newUserController as usrCtrl">
    <h1 class="text-center">
        Laboratorio del Sapere Scientifico
    </h1>
    <div class="col-sm-2 col-sm-offset-5">
        <a class="btn btn-lg btn-default" href="../index.php" ng-click="requestUser('<?php echo $USER_EMAIL; ?>')">

            <h1 class="text-info">
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
            </h1>
            Richiedi un account
        </a>

    </div>
</div>