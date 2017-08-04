function prettyConfirm(title, text, callback) {
    swal({
        title: title,
        text: text,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55"
    }, callback);
}

/* global angular */

var app = angular.module('lss-db', []);

app.controller("classesController", ['$http', '$scope', function ($http, $scope) {

        $scope.classes = {
            content: []
        };

        $scope.onAddClass = function () {
            if ($scope.section && $scope.year) {
                $http.post('./ajax/insert-class.php', {section: $scope.section, year: $scope.year}).then(
                        function (rx) {
                            swal("Inserimento", "completato");
                            $scope.loadClasses();
                        },
                        function (rx) {
                            swal("Errore", rx.data);
                        });
            }else{
                swal("Errore", "Parametri non corretti");
            }
        };

        $scope.onEditClass = function (classroom) {
            $http.post('./ajax/edit-class.php',
                    {
                        id: classroom.id,
                        section: classroom.section,
                        year: classroom.year
                    }).then(
                    function (rx) {
                        swal("Modifica", "completata");
                        $scope.loadClasses();
                    },
                    function (rx) {
                        swal("Errore", rx.data);
                    });
        };

        $scope.onDeleteClass = function (classroom) {
            prettyConfirm("Cancellazione", "Sicuro di voler cancellare la classe?", function () {
                $http.post('./ajax/remove-class.php',
                        {
                            id: classroom.id,
                        }).then(
                        function (rx) {
                            swal("Eliminazione", "completata");
                            $scope.loadClasses();
                        },
                        function (rx) {
                            swal("Errore", rx.data);
                        });
            });
        };
        
        $scope.loadClasses = function () {
            $http.post('../../../common/php/ajax/load-classes.php').then(
                    function (rx) {
                        $scope.classes.content = rx.data;
                    },
                    function (rx) {
                        swal("Errore", rx.data);
                    }
            );
        };

        //MAIN
        $scope.loadClasses();
    }]);