function prettyConfirm(title, text, callback) {
    swal({
        title: title,
        text: text,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55"
    }, callback);
}

function prettyPrompt(title, text, inputValue, callback) {
    swal({
        title: title,
        text: text,
        type: 'input',
        showCancelButton: true,
        inputValue: inputValue
    }, callback);
}

/* global angular */

var app = angular.module('lss-db', []);
app.controller("timelineController", ['$http', '$scope', function ($http, $scope) {

        $scope.modules1 = {
            content: [],
            selected: undefined,
            searchString: ""
        };
        $scope.topics1 = {
            content: [],
            selected: undefined,
            searchString: ""
        };
        $scope.items1 = {
            content: [],
            selected: undefined,
            searchString: ""
        };

        $scope.modules2 = {
            content: [],
            selected: undefined,
            searchString: ""
        };
        $scope.topics2 = {
            content: [],
            selected: undefined,
            searchString: ""
        };
        $scope.items2 = {
            content: [],
            selected: undefined,
            searchString: ""
        };
        $scope.currentLink = undefined;

        $scope.links = [];

        $scope.exit = function () {
            prettyConfirm("Esci", "Torna al menu principale", function (ok) {
                if (ok) {
                    window.location.replace("../../main.php");
                }
            });
        };

        $scope.onLinkElements = function () {
            var e1 = $scope.firstSelected();
            var e2 = $scope.secondSelected();
            if (e1 !== undefined && e2 !== undefined) {
                $http.post('../../ajax/create-link.php',
                        {
                            link: {
                                element1: e1.id,
                                element2: e2.id
                            }
                        })
                        .then(
                                function (rx) {
                                    var addedLink = rx.data;
                                    $scope.links.push({
                                        id: addedLink,
                                        element1: e1,
                                        element2: e2
                                    });
                                    $scope.updateCurrentLink();
                                    swal("Creazione avvenuta con successo");
                                },
                                function (rx) {
                                    swal(rx.data);
                                }
                        );
            } else {
                swal("Errore", "Selezionare i due elementi da collegare");
            }
        };

        $scope.onUnlinkElements = function () {
            $http.post('../../ajax/delete-link.php', {id: $scope.currentLink.id})
                    .then(
                            function (rx) {
                                var done = false;
                                for (var i = 0; i < $scope.links.length && !done; i++) {
                                    if($scope.links[i].id === $scope.currentLink.id){
                                        $scope.links.splice(i, 1);
                                        done = true;
                                    }
                                }
                                $scope.currentLink = undefined;
                                swal("Eliminazione avvenuta con successo");
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };

        $scope.updateCurrentLink = function () {
            var element1 = $scope.firstSelected();
            var element2 = $scope.secondSelected();
            $scope.currentLink = $scope.findLinkByElements(element1, element2);
        };

        $scope.onSelectModule1 = function (module) {
            $scope.modules1.selected = module;
            $scope.topics1.selected = undefined;
            $scope.loadTopics1(module);
            $scope.items1.content.splice(0, $scope.items1.content.length);
            $scope.updateCurrentLink();
        };

        $scope.onSelectTopic1 = function (topic) {
            $scope.topics1.selected = topic;
            $scope.items1.selected = undefined;
            $scope.loadItems1(topic);
            $scope.updateCurrentLink();
        };

        $scope.onSelectItem1 = function (item) {
            $scope.items1.selected = item;
            $scope.updateCurrentLink();
        };

        $scope.onSelectModule2 = function (module) {
            $scope.modules2.selected = module;
            $scope.topics2.selected = undefined;
            $scope.loadTopics2(module);
            $scope.items2.content.splice(0, $scope.items2.content.length);
            $scope.updateCurrentLink();
        };

        $scope.onSelectTopic2 = function (topic) {
            $scope.topics2.selected = topic;
            $scope.items2.selected = undefined;
            $scope.loadItems2(topic);
            $scope.updateCurrentLink();
        };

        $scope.onSelectItem2 = function (item) {
            $scope.items2.selected = item;
            $scope.updateCurrentLink();
        };

        $scope.findLinkByElements = function (e1, e2) {
            var result = undefined;
            if (e1 !== undefined && e2 !== undefined) {
                $scope.links.forEach(function(link, index){
                    if (link.element1.id === e1.id && link.element2.id === e2.id) {
                        result = link;
                    }
                    if (link.element2.id === e1.id && link.element1.id === e2.id) {
                        result = link;
                    }
                });
            }
            return result;
        };

        $scope.loadModules1 = function () {
            $scope.loadModules($scope.modules1);
        };

        $scope.loadModules2 = function () {
            $scope.loadModules($scope.modules2);
        };

        $scope.loadTopics1 = function (module) {
            $scope.loadTopics(module, $scope.topics1);
        };

        $scope.loadTopics2 = function (module) {
            $scope.loadTopics(module, $scope.topics2);
        };

        $scope.loadItems1 = function (topic) {
            $scope.loadItems(topic, $scope.items1);
        };

        $scope.loadItems2 = function (topic) {
            $scope.loadItems(topic, $scope.items2);
        };

        $scope.loadModules = function (modules) {
            $http.post('../../../common/php/ajax/load-elements.php', {type: "module"})
                    .then(
                            function (rx) {
                                modules.content = rx.data;
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };
        $scope.loadTopics = function (module, topics) {
            $http.post('../../../common/php/ajax/load-elements.php', {parent: module.id})
                    .then(
                            function (rx) {
                                topics.content = rx.data;
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };
        $scope.loadItems = function (topic, items) {
            $http.post('../../../common/php/ajax/load-elements.php', {parent: topic.id})
                    .then(
                            function (rx) {
                                items.content = rx.data;
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };
        $scope.loadLinks = function () {
            $http.post('../../../common/php/ajax/load-links.php').then(
                    function (rx) {
                        $scope.links = rx.data;
                    },
                    function (rx) {
                        swal(rx.data);
                    }
            );
        };

        $scope.firstSelected = function () {
            var element = undefined;
            if ($scope.items1.selected !== undefined) {
                element = $scope.items1.selected;
            } else if ($scope.topics1.selected !== undefined) {
                element = $scope.topics1.selected;
            } else if ($scope.modules1.selected !== undefined) {
                element = $scope.modules1.selected;
            }
            return element;
        };

        $scope.secondSelected = function () {
            var element = undefined;
            if ($scope.items2.selected !== undefined) {
                element = $scope.items2.selected;
            } else if ($scope.topics2.selected !== undefined) {
                element = $scope.topics2.selected;
            } else if ($scope.modules2.selected !== undefined) {
                element = $scope.modules2.selected;
            }
            return element;
        };

//MAIN
        $scope.loadModules1();
        $scope.loadModules2();
        $scope.loadLinks();
    }]);