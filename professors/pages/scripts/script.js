
var app = angular.module('lss-db', []);

app.controller("timelineController", ['$http', '$scope', function ($http, $scope) {

        $scope.currentTimeline = "";

        $scope.timelines = {
            content: []
        };

        $scope.storedTimelines = {
            content: [],
            hidden: true
        };

        /**
         * 
         * @param {object} timeline Selected timeline
         * @returns {undefined}
         */
        $scope.onCurrentTimeline = function (timeline) {
            $scope.currentTimeline = timeline;
        };

        $scope.reloadTimelines = function () {
            $("#bar-timeline").show();
            $http.post(
                    './pages/includes/timeline-manager.php',
                    {
                        command: 'list_not_stored_timelines'
                    }
            ).then(
                    function (rx) {
                        $scope.timelines.content = rx.data;
                        for (var i = 0; i < $scope.timelines.content.length; i++) {
                            $scope.timelines.content[i].anno2 = parseInt($scope.timelines.content[i].anno) + 1;
                        }
                        $("#bar-timeline").hide();
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data);
                    }
            );
            if (!$scope.storedTimelines.hidden) {
                $scope.loadStoredTimelines();
            }
        };

        $scope.onStoreTimeline = function (timeline) {
            swal(
                    {
                        title: "Archiviazione programmazione",
                        text: "Spostare la programmazione nell'archivio?",
                        type: "warning", showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sì",
                        cancelButtonText: "No"
                    },
            function () {
                $http.post(
                        './pages/includes/timeline-manager.php',
                        {
                            command: 'store_timeline',
                            timeline: timeline.id
                        }
                ).then(
                        function (rx) {
                            swal("Programmazione", "archiviata correttamente", "success");
                            $scope.reloadTimelines();
                        },
                        function (rx) {
                            $scope.errorMessage(rx.data);
                        }
                );
            }
            );

        };

        $scope.onUnStoreTimeline = function (timeline) {
            swal(
                    {
                        title: "Archiviazione programmazione",
                        text: "Recuperare la programmazione dall'archivio?",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sì",
                        cancelButtonText: "No"
                    },
            function () {
                $http.post(
                        './pages/includes/timeline-manager.php',
                        {
                            command: 'unstore_timeline',
                            timeline: timeline.id
                        }
                ).then(
                        function (rx) {
                            swal("Programmazione", "recuperata correttamente", "success");
                            $scope.reloadTimelines();
                        },
                        function (rx) {
                            $scope.errorMessage(rx.data);
                        }
                );
            }
            );

        };

        $scope.onShowStored = function () {
            if ($scope.storedTimelines.hidden) {
                $scope.loadStoredTimelines();
            } else {
                $scope.storedTimelines.hidden = true;
                $scope.storedTimelines.content = [];
                $("#btn-stored").text("Mostra archiviate");
            }
        };

        $scope.loadStoredTimelines = function () {
            $("#bar-stored").show();
            $http.post(
                    './pages/includes/timeline-manager.php',
                    {
                        command: 'list_stored_timelines'
                    }
            ).then(
                    function (rx) {
                        $scope.storedTimelines.content = rx.data;
                        for (var i = 0; i < $scope.storedTimelines.content.length; i++) {
                            $scope.storedTimelines.content[i].anno2 = parseInt($scope.storedTimelines.content[i].anno) + 1;
                        }
                        $scope.storedTimelines.hidden = false;
                        $("#btn-stored").text("Nascondi archiviate");
                        $("#bar-stored").hide();
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data);
                    }
            );
        };


        $scope.onDeleteTimeline = function (timeline) {
            swal(
                    {
                        title: "Cancellazione programmazione",
                        text: "Tutte le informazioni saranno perdute, continuare?",
                        type: "warning", showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sì",
                        cancelButtonText: "No"
                    },
            function () {
                $http.post(
                        './pages/includes/timeline-manager.php',
                        {
                            command: 'delete_timeline',
                            timeline: timeline.id
                        }
                ).then(
                        function (rx) {
                            swal("Programmazione", "eliminata correttamente", "success");
                            $scope.reloadTimelines();
                        },
                        function (rx) {
                            $scope.errorMessage(rx.data);
                        }
                );
            }
            );

        };

        $scope.errorMessage = function (message) {
            $scope.lastErrorMessage = message;
            $(".error-message").show();
        };
        $scope.successMessage = function (message) {
            $scope.lastSuccessMessage = message;
            $(".success-message").show();
        };

        $scope.reloadTimelines();

    }]);

$(document).ready(function () {
    $(".success-message").click(function () {
        $(this).hide();
    });
    $(".error-message").click(function () {
        $(this).hide();
    });
});
