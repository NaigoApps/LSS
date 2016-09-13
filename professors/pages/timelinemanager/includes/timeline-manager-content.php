
<?php require_once '../../../common/authentication-bar.php'; ?>
<h1 class="text-center">Elenco delle timelines</h1>
<div class="wrapper" ng-app="lss-db" ng-controller="timelineController as tCtrl">
    <?php
    $dir = "../../" . $user_data->getId();
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
                    ?>

                    <div class="well col-sm-4">
                        <div class="col-sm-6">
                            <?php
                            echo $timeline;
                            ?>
                        </div>
                        <a class="btn btn-default col-sm-2" ng-click="onEditTimeline('<?php echo $timeline; ?>')">
                            <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a class="btn btn-default col-sm-2" ng-click="onPrintTimeline('<?php echo $timeline; ?>')">
                            <span class="glyphicon glyphicon-print"></span>
                        </a>
                        <a class="btn btn-default col-sm-2" ng-click="onCurrentTimeline('<?php echo $timeline; ?>')" data-toggle="modal" data-target="#deleteTimeline">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    </div>

                    <?php
                }
            }
            ?>
        </div>
        <?php
    }
    ?>
    <button class="btn btn-warning col-sm-2 col-sm-offset-5" ng-click="onExit()">
        Esci
    </button>

    <!-- Delete Modal -->
    <div id="deleteTimeline" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center">Elminazione timeline</h3>
                </div>
                <div class="modal-body">
                    <form name="timelineDeleteForm" novalidate>
                        <p>Confermare l'eliminazione?</p>
                        <button type="submit" class="btn btn-success col-sm-2 col-sm-offset-3"
                                ng-click="onDeleteCurrentTimeline()"
                                data-dismiss="modal">
                            <span class="glyphicon glyphicon-ok"></span>
                        </button>
                        <button type="submit" class="btn btn-danger col-sm-2 col-sm-offset-1"
                                data-dismiss="modal">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>