<div class="row">
    <a class="col-sm-2 col-sm-offset-2 btn btn-md btn-default" href="./pages/db/index.php">
        <h1 class="text-info">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
        </h1>
        Gestione database
    </a>

    <a class="col-sm-2 col-sm-offset-1 btn btn-md btn-default" href="./pages/classes/index.php">
        <h1 class="text-info">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        </h1>
        Gestione classi
    </a>

    <a class="col-sm-2 col-sm-offset-1 btn btn-md btn-default" href="./pages/users/index.php">
        <h1 class="text-info">
            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
        </h1>
        Gestione utenti
    </a>
</div>

<?php
if ($user_data->isProfessor()) {
    ?>

    <div class="row top-sep">
        <a class="col-sm-2 col-sm-offset-5 btn btn-md btn-default" href="../professors/index.php">
            <h1 class="text-info">
                <span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span>
            </h1>
            Home docenti
        </a>

    </div>

    <?php
}
?>