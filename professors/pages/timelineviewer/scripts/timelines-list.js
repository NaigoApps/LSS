
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
        $scope.onViewTimeline = function (timeline) {
            $http.post(
                    './includes/timeline-viewer.php',
                    {
                        command: 'view_timeline',
                        timeline: timeline
                    }
            ).then(
                    function (rx) {
                        window.location.replace("./viewer.php");
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
