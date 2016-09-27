
<?php require_once '../../../common/authentication-bar.php'; ?>
<h1 class="text-center">Elenco delle programmazioni</h1>
<div class="wrapper" ng-app="lss-db" ng-controller="timelineController as tCtrl">
    <?php
    $dir = "../../timelines/" . $user_data->getId();
    if (!file_exists($dir)) {
        mkdir($dir);
    }

    $files = scandir($dir);
    for ($i = 0; $i < count($files); $i+= $j) {
        ?>
        <div class="row">
            <?php
            $counter = 0;
            for ($j = $i; $counter < 3 && $j < count($files); $j++) {
                if (strpos($files[$j], ".json") !== false) {
                    $counter++;
                    $timeline = substr($files[$j], 0, strpos($files[$j], ".json"));
                    $descr = explode("-", $timeline);
                    ?>

                    <div class="well col-sm-4 btn btn-lg" ng-click="onViewTimeline()">
                        <div class="col-sm-6">
                            <?php
                            echo $descr[4] . $descr[1] . $descr[2] . $descr[0];
                            ?>
                        </div>
                    </div>

                    <?php
                }
            }
            ?>
        </div>
        <?php
    }
    ?>
    <a class="btn btn-warning col-sm-2 col-sm-offset-5" ng-click="onExit()">
        Esci
    </a>


</div>

