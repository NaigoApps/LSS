<div class="row">
    <a class="col-sm-2 col-sm-offset-2 btn btn-md btn-default" href="./pages/timelinecreator/index.php">
        <h1 class="text-info">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
        </h1>
        Nuova programmazione
    </a>

    <a class="col-sm-2 col-sm-offset-1 btn btn-md btn-default" href="./pages/timelinemanager/index.php">
        <h1 class="text-info">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        </h1>
        Gestisci programmazione
    </a>

    <a class="col-sm-2 col-sm-offset-1 btn btn-md btn-default" href="./pages/timelineviewer/index.php">
        <h1 class="text-info">
            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
        </h1>
        Visualizza programmazione
    </a>

</div>
    
<?php
if ($user_data->isAdmin()) {
    ?>

    <div class="row top-sep">
        <a class="col-sm-2 col-sm-offset-5 btn btn-md btn-default" href="../admins/index.php">
            <h1 class="text-info">
                <span class="glyphicon glyphicon-console" aria-hidden="true"></span>
            </h1>
            Sezione amministratore
        </a>

    </div>

    <?php
}
?>