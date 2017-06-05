/* global angular */

var app = angular.module('lss-db', []);

app.controller("timelineController", ['$http', '$scope', function ($http, $scope) {

        $scope.currentTimeline = "";

        $scope.timelines = {
            content: []
        };

        $scope.storedSchedules = {
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

        $scope.reloadSchedules = function () {
            $scope.loadUnstoredSchedules();
            if (!$scope.storedSchedules.hidden) {
                $scope.loadStoredSchedules();
            }
        };

        $scope.loadUnstoredSchedules = function () {
            $("#bar-unstored").show();

            $http.post('../common/php/ajax/load-unstored-schedules.php')
                    .then(
                            function (rx) {
                                $scope.timelines.content = rx.data;
                                for (var i = 0; i < $scope.timelines.content.length; i++) {
                                    $scope.timelines.content[i].year2 = parseInt($scope.timelines.content[i].year) + 1;
                                }
                                $("#bar-unstored").hide();
                            },
                            function (rx) {
                                swal(rx.data);
                                $("#bar-unstored").hide();
                            }
                    );
        };

        $scope.loadStoredSchedules = function () {
            $("#bar-stored").show();
            $http.post('../common/php/ajax/load-stored-schedules.php')
                    .then(
                            function (rx) {
                                $scope.storedSchedules.content = rx.data;
                                for (var i = 0; i < $scope.storedSchedules.content.length; i++) {
                                    $scope.storedSchedules.content[i].year2 = parseInt($scope.storedSchedules.content[i].year) + 1;
                                }
                                $scope.storedSchedules.hidden = false;
                                $("#btn-stored").text("Nascondi archiviate");
                                $("#bar-stored").hide();
                            },
                            function (rx) {
                                swal(rx.data);
                                $("#bar-stored").hide();
                            }
                    );
        };

        $scope.onStoreSchedule = function (timeline) {
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
                        $http.post('./ajax/store-schedule.php', {id: timeline.id})
                                .then(
                                        function (rx) {
                                            swal("Programmazione", "archiviata correttamente", "success");
                                            $scope.reloadSchedules();
                                        },
                                        function (rx) {
                                            swal(rx.data);
                                        }
                                );
                    }
            );

        };

        $scope.onUnstoreSchedule = function (timeline) {
            swal(
                    {
                        title: "Archiviazione programmazione",
                        text: "Recuperare la programmazione dall'archivio?",
                        type: "warning", showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sì",
                        cancelButtonText: "No"
                    },
                    function () {
                        $http.post('./ajax/unstore-schedule.php', {id: timeline.id})
                                .then(
                                        function (rx) {
                                            swal("Programmazione", "recuperata correttamente", "success");
                                            $scope.reloadSchedules();
                                        },
                                        function (rx) {
                                            swal(rx.data);
                                        }
                                );
                    }
            );

        };

        $scope.onShowStored = function () {
            if ($scope.storedSchedules.hidden) {
                $scope.loadStoredSchedules();
            } else {
                $scope.storedSchedules.hidden = true;
                $scope.storedSchedules.content = [];
                $("#btn-stored").text("Mostra archiviate");
            }
        };



        $scope.onDeleteSchedule = function (schedule) {
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
                        $http.post('./ajax/delete-schedule.php', {id: schedule.id})
                                .then(
                                        function (rx) {
                                            swal("Programmazione", "eliminata correttamente", "success");
                                            $scope.reloadSchedules();
                                        },
                                        function (rx) {
                                            swal(rx.data);
                                        }
                                );
                    }
            );

        };

        $scope.reloadSchedules();

    }]);