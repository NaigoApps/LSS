
app.controller("timelineController", ['$http', '$scope', '$rootScope', function ($http, $scope, $rootScope) {

        $scope.loaded = 0;

        $scope.mesi = [
            {nome: 'Settembre', numero: 9},
            {nome: 'Ottobre', numero: 10},
            {nome: 'Novembre', numero: 11},
            {nome: 'Dicembre', numero: 12},
            {nome: 'Gennaio', numero: 1},
            {nome: 'Febbraio', numero: 2},
            {nome: 'Marzo', numero: 3},
            {nome: 'Aprile', numero: 4},
            {nome: 'Maggio', numero: 5},
            {nome: 'Giugno', numero: 6}
        ];

        $scope.exit = function () {
            window.location.replace("../..");
        };

        $scope.print = function () {
            window.print();
        };

        $scope.errorMessage = function (message) {
            $scope.lastErrorMessage = message;
            $(".error-message").show();
        };
        $scope.successMessage = function (message) {
            $scope.lastSuccessMessage = message;
            $(".success-message").show();
        };

        $scope.assignPerformance = function (perf) {
            for (var i = 0; i < $scope.elements.length; i++) {
                if ($scope.elements[i].id === perf.id) {
                    var d = new Date();
                    d.setTime(perf.data * 1000);
                    $scope.elements[i].performance.push({
                        id: perf.idmateria,
                        data: d
                    });
                    if (perf.idmateria === $scope.timeline.idmateria) {
                        $scope.elements[i].performed = true;
                    }
                }
            }
        };

        $scope.reloadPerformances = function () {
            $http.post(
                    'includes/load_data.php',
                    {
                        command: 'load_performances',
                        classe: $scope.timeline.idclasse,
                        anno: $scope.timeline.anno
                    }
            ).then(
                    function (rx) {
                        var performances = rx.data;
                        for (var i = 0; i < performances.length; i++) {
                            $scope.assignPerformance(performances[i]);
                        }
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data.msg);
                    }
            );
        };

        $scope.sortTimeline = function () {
            $scope.elements.sort(function (a, b) {
                if (a.data.getTime() < b.data.getTime()) {
                    return -1;
                }else if(a.data.getTime() === b.data.getTime() && a.module !== undefined && b.module !== undefined){
                    if(a.module.nome < b.module.nome){
                        return -1;
                    }else if(a.module.nome === b.module.nome &&
                            a.topic !== undefined && b.topic !== undefined){
                        if(a.topic.nome <= b.topic.nome){
                            return -1;
                        }
                    }
                }
                return 1;
            });
        };

        $scope.doUpdateView = function () {
            $scope.sortTimeline();
            for (var i = 0; i < $scope.elements.length; i++) {
                if (i === 0 || $scope.elements[i].data.getMonth() !== $scope.elements[i - 1].data.getMonth() ||
                        $scope.elements[i].module.id !== $scope.elements[i - 1].module.id) {
                    $scope.elements[i].moduleVisible = true;

                }
                if (i === 0 || $scope.elements[i].data.getMonth() !== $scope.elements[i - 1].data.getMonth() ||
                        $scope.elements[i].topic.id !== $scope.elements[i - 1].topic.id) {
                    $scope.elements[i].topicVisible = true;
                }
            }
        };

        $scope.updateView = function () {
            $scope.loaded++;
            if ($scope.loaded === $scope.elements.length) {
                $scope.doUpdateView();
            }
        };

        $scope.loadTimeline = function () {
            $http.post(
                    'includes/load_data.php',
                    {
                        command: 'load_timeline',
                        id: timeline_id
                    }
            ).then(
                    function (rx) {
                        $scope.timeline = rx.data.timeline;
                        $scope.timeline.anno2 = parseInt($scope.timeline.anno) + 1;
                        $scope.elements = rx.data.elements;
                        for (var i = 0; i < $scope.elements.length; i++) {
                            var d = new Date();
                            d.setTime($scope.elements[i].data * 1000);
                            $scope.elements[i].data = d;
                            $scope.elements[i].performance = [];
                            $scope.elements[i].performed = false;
                            $rootScope.$emit('find-topics-by-item', {
                                item: $scope.elements[i],
                                target: $scope.elements[i],
                                callback: $scope.updateView
                            });
                        }
                        $scope.reloadPerformances();
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data.msg);
                    }
            );
        };

        $scope.loadTimeline();
    }]);

$(document).ready(function () {
    $(".success-message").click(function () {
        $(this).hide();
    });
    $(".error-message").click(function () {
        $(this).hide();
    });
});
