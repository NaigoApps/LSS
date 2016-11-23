<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">
                <span class="glyphicon glyphicon-book"></span>
                
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
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a>Gestione del database</a>
                    </li>
                </ul>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="container under-nav" ng-app="lss-db" ng-controller="linkController as linkCtrl">
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