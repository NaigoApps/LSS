<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">
                <span class="glyphicon glyphicon-list"></span>
                Elenco delle programmazioni
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
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="container under-nav" ng-app="lss-db" ng-controller="timelineController as tCtrl">

    <div class="row">

        <div class="col-sm-6 col-sm-offset-3">
            <ul>
                <li ng-repeat="timeline in timelines.content">
                    <div class="well well-sm clearfix">
                        {{timeline.classe}}{{timeline.sezione}} - {{timeline.materia}} - {{timeline.anno}}/{{timeline.anno2}}
                        <div class="pull-right">
                            <form class="dummy-form" action="viewer.php" method="POST">
                                <input type="hidden" name="timelineid" value="{{timeline.id}}"/>    
                                <button type="submit" class="btn btn-xs btn-success tooltip-base">
                                    <span class="tooltip">Visualizza</span>
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

    </div>

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