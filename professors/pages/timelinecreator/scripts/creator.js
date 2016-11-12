app.controller("classesController", ['$http', '$scope', '$rootScope', function ($http, $scope, $rootScope) {

        $scope.classi = {
            content: [],
            name: "classi",
            selected: undefined
        };

        $scope.materie = {
            content: [],
            name: "materie",
            selected: undefined
        };

        $scope.anni = {
            content: [],
            selected: undefined
        };

        $scope.timelines = {
            content: []
        };

        var baseYear = new Date().getFullYear();
        for (var i = baseYear; i < baseYear + 10; i++) {
            $scope.anni.content.push(i);
        }

        /**
         * 
         * @param {object} classe Selected class
         * @returns {undefined}
         */
        $scope.onSelectClass = function (classe) {
            $scope.classi.selected = classe;
        };

        /**
         * 
         * @param {object} classe Selected subject
         * @returns {undefined}
         */
        $scope.onSelectSubject = function (materia) {
            $scope.materie.selected = materia;
        };

        /**
         * 
         * @param {object} anno Selected year
         * @returns {undefined}
         */
        $scope.onSelectYear = function (anno) {
            $scope.anni.selected = anno;
        };

        $scope.onConfirmTimeline = function () {
            if ($scope.anni.selected && $scope.materie.selected && $scope.classi.selected) {
                $http.post(
                        '../includes/timeline-manager.php',
                        {
                            command: 'create_timeline',
                            year: $scope.anni.selected,
                            class: $scope.classi.selected,
                            subject: $scope.materie.selected
                        }
                ).then(
                        function (rx) {
                            $scope.successMessage("Timeline creata con successo");
                            $scope.reloadTimelines();
                        },
                        function (rx) {
                            $scope.errorMessage(rx.data);
                        }
                );
            } else {
                $scope.errorMessage("Inserire parametri corretti");
            }
        };
        
        $scope.onCopyTimeline = function (id) {
            if ($scope.anni.selected && $scope.materie.selected && $scope.classi.selected) {
                $http.post(
                        '../includes/timeline-manager.php',
                        {
                            command: 'copy_timeline',
                            timeline: id,
                            year: $scope.anni.selected,
                            class: $scope.classi.selected,
                            subject: $scope.materie.selected
                        }
                ).then(
                        function (rx) {
                            $scope.successMessage("Timeline copiata con successo");
                            $scope.reloadTimelines();
                        },
                        function (rx) {
                            $scope.errorMessage(rx.data);
                        }
                );
            } else {
                $scope.errorMessage("Inserire parametri corretti");
            }
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
                                '../includes/timeline-manager.php',
                                {
                                    command: 'delete_timeline',
                                    timeline: timeline.id
                                }
                        ).then(
                                function (rx) {
                                    $scope.reloadTimelines();
                                },
                                function (rx) {
                                    $scope.errorMessage(rx.data);
                                }
                        );
                    }
            );

        };

        $scope.reloadTimelines = function () {
            $http.post(
                    '../includes/timeline-manager.php',
                    {
                        command: 'list_timelines'
                    }
            ).then(
                    function (rx) {
                        $scope.timelines.content = rx.data;
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data);
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

        //MAIN
        $rootScope.$emit('load-table', {
            target: $scope.classi
        });
        $rootScope.$emit('load-table', {
            target: $scope.materie
        });

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
