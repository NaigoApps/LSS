
var app = angular.module('lss-db', []);

app.controller("newUserController", ['$http', '$scope', '$rootScope', function ($http, $scope, $rootScope) {

        $scope.requestUser = function (email) {
            $http.post(
                    '../common/db-manager.php',
                    {
                        command: 'add-user',
                        email: email
                    }
            ).then(
                    function (rx) {
                        alert(rx.data);
                    },
                    function (rx) {
                        alert(rx.data);
                    }
            );
        };

    }]);