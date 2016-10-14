app.controller("classesController", ['$http', '$scope', '$rootScope', function ($http, $scope, $rootScope) {

        $scope.classi = {
            content : [],
            name : "classi",
            selected : undefined
        };
        
        $scope.materie = {
            content : [],
            name : "materie",
            selected : undefined
        };
        
        $scope.anni = [];
        var baseYear = new Date().getFullYear();
        for (var i = baseYear; i < baseYear + 10; i++) {
            $scope.anni.push(i);
        }
        $scope.anno = $scope.anni[0] + "/" + $scope.anni[1];

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

        $scope.onConfirmTimeline = function () {
            if ($scope.anno !== "") {
                $http.post(
                        '../includes/timeline-manager.php',
                        {
                            command: 'create_timeline',
                            year: $scope.anno,
                            class: $scope.classi.selected,
                            subject: $scope.materie.selected
                        }
                ).then(
                        function (rx) {
                            window.location.replace("../timelinemanager/editor2.php");
                        },
                        function (rx) {
                            $scope.errorMessage(rx.data);
                        }
                );
            }
        };

        $scope.onCancelTimeline = function () {
            window.location.replace("../..");
        };


        /**
         * 
         * @param {string} table Name of table in which search
         * @param {object} object Object to search
         * @returns {undefined}
         */
        $scope.searchObject = function (table, object) {
            if (!$scope.searching) {
                $scope.searching = true;
                $http.post(
                        '../../../common/db-manager.php',
                        {
                            command: 'search-object',
                            table: table,
                            obj: object
                        }
                ).then(
                        function (rx) {
                            $scope[table].splice(0, $scope[table].length);
                            for (var i = 0; i < rx.data.length; i++) {
                                $scope[table].push(rx.data[i]);
                            }
                            $scope.searching = false;
                        },
                        function (rx) {
                            $scope.errorMessage(rx.data);
                            $scope.searching = false;
                        }
                );
            }
        };


        $scope.findById = function (vector, id) {
            for (var i = 0; i < vector.length; i++) {
                if (vector[i].id === id) {
                    return i;
                }
            }
            return -1;
        };

        $scope.findObjectById = function (vector, id) {
            var index = $scope.findById(vector, id);
            if (index !== -1) {
                return vector[index];
            }
            return undefined;
        };

        $scope.copyObject = function (source, destination) {
            destination.id = source.id;
            destination.nome = source.nome;
            destination.descrizione = source.descrizione;
            destination.links = source.links;
            if (destination.links === undefined) {
                destination.links = [];
            }
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
        $rootScope.$emit('load-table',{
            target: $scope.classi
        });
        $rootScope.$emit('load-table',{
            target: $scope.materie
        });
    }]);

$(document).ready(function () {
    $(".success-message").click(function () {
        $(this).hide();
    });
    $(".error-message").click(function () {
        $(this).hide();
    });
});
