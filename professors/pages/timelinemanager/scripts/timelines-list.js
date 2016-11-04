
var app = angular.module('lss-db', []);

app.controller("timelineController", ['$http', '$scope', function ($http, $scope) {

        $scope.currentTimeline = "";

        $scope.timelines = {
            content: []
        };
        
        $scope.onExit = function(){
            window.location.replace("../..");
        };

        /**
         * 
         * @param {object} timeline Selected timeline
         * @returns {undefined}
         */
        $scope.onCurrentTimeline = function (timeline) {
            $scope.currentTimeline = timeline;
        };
        
        /**
         * 
         * @returns {undefined}
         */
        $scope.onDeleteCurrentTimeline = function () {
            $http.post(
                    '../includes/timeline-manager.php',
                    {
                        command: 'delete_timeline',
                        timeline: $scope.currentTimeline
                    }
            ).then(
                    function (rx) {
                        window.location.replace(".");
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data);
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
