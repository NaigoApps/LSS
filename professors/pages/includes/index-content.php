
<div class="well container">
    <h4>Home</h4>
    <div class="row">
        <a class="col-sm-3 btn btn-sm btn-default" href="./pages/timelinecreator/index.php">
            <strong class="text-info">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Nuova programmazione
            </strong>
        </a>

        <a class="col-sm-3 btn btn-sm btn-default" href="./pages/timelinemanager/index.php">
            <strong class="text-info">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                Gestisci programmazioni
            </strong>
        </a>

        <a class="col-sm-3 btn btn-sm btn-default" href="./pages/timelineviewer/index.php">
            <strong class="text-info">
                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                Visualizza programmazioni
            </strong>
        </a>


        <?php
        if ($user_data->isAdmin()) {
            ?>

            <a class="col-sm-3 btn btn-sm btn-default" href="../admins/index.php">
                <strong class="text-warning">
                    <span class="glyphicon glyphicon-console" aria-hidden="true"></span>
                    Sezione amministratore
                </strong>
            </a>


            <?php
        }
        ?>
    </div>
</div>

<div ng-app="lss-db" ng-controller="timelineController
        as
        timCtrl" class="container top-sep">
    <h4>Programmazioni attive</h4>
    <div class="row">
        <ul>
            <li ng-repeat = "timeline in timelines.content">
                <form class="col-sm-2 dummy-form" action = "./pages/timelinemanager/editor2.php" method = "POST">
                    <a onclick="this.parentNode.submit()">
                        <div class="well well-sm">
                            {{timeline.classe}}{{timeline.sezione}} - {{timeline.materia}} - {{timeline.anno}}/{{timeline.anno2}}
                            <input type = "hidden" name = "timelineid" value = "{{timeline.id}}"/>
                        </div>
                    </a>
                </form>
            </li>
        </ul>
    </div>
</div>

