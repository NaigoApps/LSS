<div class="wrapper" ng-app="lss-db" ng-controller="linkController as linkCtrl">
    <div ng-app="lss-db" ng-controller="dbController as dbCtrl">
        <div class="row">
            <?php include './includes/modules.html'; ?>
            <?php include './includes/topics.html'; ?>
            <?php include './includes/items.html'; ?>
        </div>

        <div class="row error message">
            <div class="alert alert-danger error-message col-sm-12" role="alert" hidden>
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only">Errore:</span>
                {{lastErrorMessage}}
            </div>
        </div>
        <div class="row success message">
            <div class="alert alert-success success-message col-sm-12" role="alert" hidden>
                <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
                <span class="sr-only">Successo:</span>
                {{lastSuccessMessage}}
            </div>
        </div>
    </div>
</div>