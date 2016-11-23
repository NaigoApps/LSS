var app = angular.module('lss-db', []);

app.controller("linkController", ['$http', '$scope', '$rootScope', function ($http, $scope, $rootScope) {

        /**
         * Object type "table"
         * table -> name = table name
         * table -> content = table local content
         * table -> current = currently selected item
         */

        $scope.searching = false;

        /**
         * Fetch data from database
         * @param {table} data.target
         */
        $rootScope.$on('load-table', function (event, data) {
            $http.post(
                    '../../../common/db-manager.php',
                    {
                        command: 'load_table',
                        table: data.target.name
                    }
            ).then(
                    function (rx) {
                        data.target.content.splice(0, data.target.content.length);
                        for (var i = 0; i < rx.data.length; i++) {
                            data.target.content.push(rx.data[i]);
                        }
                    },
                    function (rx) {
                        error_message(rx.data);
                    }
            );
        });

        /**
         * 
         * @param {table} target Name of table in which search
         * @returns {undefined}
         */
        $rootScope.$on('search-object', function (event, data) {
            if (!$scope.searching) {
                $scope.searching = true;
                $http.post(
                        '../../../common/db-manager.php',
                        {
                            command: 'search_object',
                            table: data.target.name,
                            hint: data.target.searchString
                        }
                ).then(
                        function (rx) {
                            data.target.content.splice(0, data.target.content.length);
                            for (var i = 0; i < rx.data.length; i++) {
                                data.target.content.push(rx.data[i]);
                            }
                            $scope.searching = false;
                        },
                        function (rx) {
                            $scope.errorMessage(rx.data);
                            $scope.searching = false;
                        }
                );
            }
        });

        /**
         * 
         * @param {table} data.target target table
         * @returns {undefined}
         */
        $rootScope.$on('add-class', function (event, data) {
            $http.post(
                    './includes/classes-editor.php',
                    {
                        command: 'addclass',
                        obj: data.target.current
                    }
            ).then(
                    function (rx) {
                        data.target.current.id = rx.data;
                        data.target.content.push(JSON.parse(JSON.stringify(data.target.current)));
                        swal("Inserimento", data.target.current.anno + data.target.current.sezione + " inserito", "success");
                    },
                    function (rx) {
                        swal("Inserimento", data.target.current.anno + data.target.current.sezione + " non inserito", "success");
                    }
            );
        });

        
        /**
         * Puts new element in place of old element (same ID) in specified table
         * @param {string} table Name of table to edit
         * @param {object} element Modified element
         * @returns {undefined}
         */
        $rootScope.$on('commit-class-edit', function (event, data) {
            $http.post(
                    './includes/classes-editor.php',
                    {
                        command: 'updateclass',
                        obj: data.target.current
                    }
            ).then(
                    function (rx) {
                        var index = $scope.findById(data.target.content, data.target.current.id);
                        data.target.current.modified = false;
                        data.target.content[index] = JSON.parse(JSON.stringify(data.target.current));
                        swal("Aggiornamento", data.target.current.anno + data.target.current.sezione + " modificato", "success");
                    },
                    function (rx) {
                        swal("Aggiornamento", data.target.current.anno + data.target.current.sezione + " non modificato", "success");
                    }
            );
        });

        /**
         * Deletes an element from specified table
         * @param {string} table Name of the target table
         * @param {object} element Element to delete
         * @returns {undefined}
         */
        $rootScope.$on('commit-delete', function (event, data) {
            $http.post(
                    './includes/classes-editor.php',
                    {
                        command: 'deleteclass',
                        obj: data.target.current
                    }
            ).then(
                    function (rx) {
                        var index = $scope.findById(data.target.content, data.target.current.id);
                        data.target.content.splice(index, 1);
                        swal("Eliminazione", data.target.current.anno + data.target.current.sezione + " eliminato", "success");
                    },
                    function (rx) {
                        swal("Eliminazione", data.target.current.anno + data.target.current.sezione + " non eliminato", "error");
                    }
            );
        });

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

        $rootScope.errorMessage = function (message) {
            $rootScope.lastErrorMessage = message;
            $(".error-message").show();
        };
        $rootScope.successMessage = function (message) {
            $rootScope.lastSuccessMessage = message;
            $(".success-message").show();
        };

    }]);

$(document).ready(function () {
    $(".success-message").click(function () {
        $(this).hide();
    });
    $(".error-message").click(function () {
        $(this).hide();
    });
});