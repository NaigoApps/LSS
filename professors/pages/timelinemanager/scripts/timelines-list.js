
var app = angular.module('lss-db', []);

app.controller("timelineController", ['$http', '$scope', function ($http, $scope) {

        $scope.currentTimeline = "";

        $scope.onExit = function(){
            window.location.replace("../..");
        }

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
         * @param {object} timeline Selected timeline
         * @returns {undefined}
         */
        $scope.onEditTimeline = function (timeline) {
            $http.post(
                    '../includes/timeline-manager.php',
                    {
                        command: 'edit_timeline',
                        timeline: timeline
                    }
            ).then(
                    function (rx) {
                        window.location.replace("./editor2.php");
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data);
                    }
            );
        };
        
        $scope.onPrintTimeline = function (timeline) {
            $http.post(
                    '../includes/timeline-manager.php',
                    {
                        command: 'edit_timeline',
                        timeline: timeline
                    }
            ).then(
                    function (rx) {
                        window.location.replace("./print.php");
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data);
                    }
            );
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
        
        $scope.errorMessage = function (message) {
            $scope.lastErrorMessage = message;
            $(".error-message").show();
        };
        $scope.successMessage = function (message) {
            $scope.lastSuccessMessage = message;
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
