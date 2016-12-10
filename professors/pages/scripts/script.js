
var app = angular.module('lss-db', []);

app.controller("timelineController", ['$http', '$scope', function ($http, $scope) {

        $scope.currentTimeline = "";

        $scope.timelines = {
            content: []
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
            $http.post(
                    './pages/includes/timeline-manager.php',
                    {
                        command: 'list_not_stored_timelines'
                    }
            ).then(
                    function (rx) {
                        $scope.timelines.content = rx.data;
                        for(var i = 0;i < $scope.timelines.content.length;i++){
                            $scope.timelines.content[i].anno2 = parseInt($scope.timelines.content[i].anno) + 1;
                        }
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
