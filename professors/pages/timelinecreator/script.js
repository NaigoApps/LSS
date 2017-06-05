function prettyConfirm(title, text, callback) {
    swal({
        title: title,
        text: text,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55"
    }, callback);
}

function prettyPrompt(title, text, inputValue, callback) {
    swal({
        title: title,
        text: text,
        type: 'input',
        showCancelButton: true,
        inputValue: inputValue
    }, callback);
}


/* global angular */

var app = angular.module('lss-db', []);
app.controller("timelineController", ['$http', '$scope', function ($http, $scope) {


        $scope.subjects = {
            content: [],
            selected: undefined
        };

        $scope.classes = {
            content: [],
            selected: undefined
        };

        $scope.years = {
            content: [],
            selected: undefined
        };

        $scope.schedules = {
            content: []
        };

        $scope.onSelectClass = function (classroom) {
            $scope.classes.selected = classroom;
        };

        $scope.onSelectSubject = function (subject) {
            $scope.subjects.selected = subject;
        };

        $scope.onSelectYear = function (year) {
            $scope.years.selected = year;
        };

        $scope.onConfirmTimeline = function () {
            if ($scope.years.selected && $scope.subjects.selected && $scope.classes.selected) {
                $http.post(
                        '../../ajax/create-schedule.php',
                        {
                            year: $scope.years.selected,
                            class: $scope.classes.selected.id,
                            subject: $scope.subjects.selected.id
                        }
                ).then(
                        function (rx) {
                            swal("Programmazione", "creata correttamente", "success");
                            $scope.loadSchedules();
                        },
                        function (rx) {
                            swal(rx.data);
                        }
                );
            } else {
                swal("Errore", "parametri non corretti", "warning");
            }
        };

        $scope.onCopyTimeline = function (id) {
            if ($scope.years.selected && $scope.subjects.selected && $scope.classes.selected) {
                $http.post(
                        '../../ajax/copy-schedule.php',
                        {
                            id: id,
                            year: $scope.years.selected,
                            class: $scope.classes.selected.id,
                            subject: $scope.subjects.selected.id
                        }
                ).then(
                        function (rx) {
                            swal("Timeline copiata con successo");
                            $scope.loadSchedules();
                        },
                        function (rx) {
                            swal(rx.data);
                        }
                );
            } else {
                $scope.errorMessage("Inserire parametri corretti");
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
                        $http.post('../../ajax/delete-schedule.php', {id: schedule.id})
                                .then(
                                        function (rx) {
                                            swal("Programmazione", "eliminata correttamente", "success");
                                            $scope.loadSchedules();
                                        },
                                        function (rx) {
                                            swal(rx.data);
                                        }
                                );
                    }
            );

        };


        $scope.exit = function () {
            prettyConfirm("Esci", "I dati non salvati saranno persi", function (ok) {
                if (ok) {
                    window.location.replace("../../main.php");
                }
            });
        };


        $scope.replaceContent = function (array, data) {
            array.content.splice(0, array.content.length);
            for (var i = 0; i < data.length; i++) {
                array.content.push(data[i]);
            }
        };

        $scope.loadSubjects = function () {
            $http.post('../../../common/php/ajax/load-subjects.php')
                    .then(
                            function (rx) {
                                $scope.replaceContent($scope.subjects, rx.data);
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };
        $scope.loadClasses = function () {
            $http.post('../../../common/php/ajax/load-classes.php')
                    .then(
                            function (rx) {
                                $scope.replaceContent($scope.classes, rx.data);
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };


        $scope.loadSchedules = function () {
            $http.post('../../../common/php/ajax/load-unstored-schedules.php')
                    .then(
                            function (rx) {
                                $scope.replaceContent($scope.schedules, rx.data);
                                for (var i = 0; i < $scope.schedules.content.length; i++) {
                                    $scope.schedules.content[i].year2 = parseInt($scope.schedules.content[i].year) + 1;
                                }
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };

        //MAIN

        $scope.loadSubjects();
        $scope.loadClasses();
        $scope.loadSchedules();

        var baseYear = new Date().getFullYear() - 1;
        for (var i = baseYear; i < baseYear + 10; i++) {
            $scope.years.content.push(i);
        }

    }]);