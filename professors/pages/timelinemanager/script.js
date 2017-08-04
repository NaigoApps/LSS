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
/* global schedule_id */

var app = angular.module('lss-db', []);
app.controller("timelineController", ['$http', '$scope', '$rootScope', function ($http, $scope, $rootScope) {

        /*
         * nome, descrizione, data, performance
         */

        $scope.subjects = {
            content: []
        };
        $scope.months = {
            content: [
                {name: 'Settembre', number: 9},
                {name: 'Ottobre', number: 10},
                {name: 'Novembre', number: 11},
                {name: 'Dicembre', number: 12},
                {name: 'Gennaio', number: 1},
                {name: 'Febbraio', number: 2},
                {name: 'Marzo', number: 3},
                {name: 'Aprile', number: 4},
                {name: 'Maggio', number: 5},
                {name: 'Giugno', number: 6},
                {name: 'Luglio', number: 7},
                {name: 'Agosto', number: 8}
            ],
            selected: undefined
        };
        $scope.modules = {
            content: [],
            selected: undefined,
            searchString: ""
        };
        $scope.topics = {
            content: [],
            selected: undefined,
            searchString: ""
        };
        $scope.items = {
            content: [],
            selected: undefined,
            searchString: ""
        };

        $scope.schedule = {
            content: []
        };
        $scope.saveData = function () {
            prettyConfirm("Salvataggio", "Salvare i dati?", function (ok) {
                if (ok) {
                    $http.post('../../ajax/save_schedule.php', {schedule: $scope.schedule})
                            .then(
                                    function (rx) {
                                        swal("Programmazione", "salvata correttamente", "success");
                                    },
                                    function (rx) {
                                        swal(rx.data);
                                    }
                            );
                }
            });
        };
        $scope.saveDataExit = function () {
            prettyConfirm("Esci", "Salvare i dati ed uscire?", function (ok) {
                if (ok) {
                    $http.post('../../ajax/save_schedule.php', {schedule: $scope.schedule})
                            .then(
                                    function (rx) {
                                        swal({
                                            title: "Programmazione",
                                            text: "salvata correttamente",
                                            type: "success",
                                            closeOnConfirm: false
                                        },
                                                function () {
                                                    window.location.replace("../../main.php");
                                                });
                                    },
                                    function (rx) {
                                        swal(rx.data);
                                    }
                            );
                }
            });
        };
        $scope.exit = function () {
            prettyConfirm("Esci", "I dati non salvati saranno persi", function (ok) {
                if (ok) {
                    window.location.replace("../../main.php");
                }
            });
        };
        /**
         * 
         * @param {object} module Selected module
         * @returns {undefined}
         */
        $scope.onSelectModule = function (module) {
            $scope.modules.selected = module;
            $scope.topics.selected = undefined;
            $scope.loadTopics(module);
            $scope.items.content.splice(0, $scope.items.content.length);
        };
        /**
         * 
         * @param {object} topic Selected topic
         * @returns {undefined}
         */
        $scope.onSelectTopic = function (topic) {
            $scope.topics.selected = topic;
            $scope.items.selected = undefined;
            $scope.loadItems(topic);
        };
        /**
         * 
         * @param {object} item Selected item
         * @returns {undefined}
         */
        $scope.onSelectItem = function (item) {
            $scope.items.selected = item;
        };
        /**
         * 
         * @param {object} month Selected month
         * @returns {undefined}
         */
        $scope.onSelectMonth = function (month) {
            $scope.months.selected = month;
        };
        /**
         * Searches a matching module
         * @returns {undefined}
         */
        $scope.onSearchModule = function () {
            $rootScope.$emit('search-object',
                    {
                        target: $scope.moduli
                    });
            $scope.moduli.selected = undefined;
        };
        /**
         * Searches a matching topic
         * @returns {undefined}
         */
        $scope.onSearchTopic = function () {
            $rootScope.$emit('search-object',
                    {
                        target: $scope.argomenti
                    });
            $scope.moduli.selected = undefined;
            $scope.argomenti.selected = undefined;
        };
        /**
         * Searches a matching item
         * @returns {undefined}
         */
        $scope.onSearchItem = function () {
            $rootScope.$emit('search-object',
                    {
                        target: $scope.voci
                    });
            $scope.moduli.selected = undefined;
            $scope.argomenti.selected = undefined;
            $scope.voci.selected = undefined;
        };
        $scope.onSetDate = function (index) {
            if ($scope.schedule.elements[index].settingDate) {
                $("#picker" + index).datepicker("destroy");
                $scope.schedule.elements[index].settingDate = false;
            } else {
                $scope.schedule.elements[index].settingDate = true;
                $("#picker" + index).datepicker();
                $("#picker" + index).datepicker("setDate", $scope.schedule.elements[index].date);
                $("#picker" + index).datepicker("option", "minDate", new Date($scope.schedule.year, 9 - 1, 1));
                $("#picker" + index).datepicker("option", "maxDate", new Date(parseInt($scope.schedule.year) + 1, 6 - 1, 30));
                $("#picker" + index).css("top", $("#picker" + index).parent().css("height"));
                $("#picker" + index).datepicker("option", "onSelect",
                        function (date, picker) {
                            var d = $("#picker" + index).datepicker("getDate");
                            $scope.schedule.elements[index].settingDate = false;
                            $scope.schedule.elements[index].date = d;
                            $("#picker" + index).datepicker("destroy");
                            $scope.sortSchedule();
                            $scope.$apply();
                        }
                );
            }
        };
        $scope.setTodo = function (element, index) {
            element.status = "todo";
        };
        $scope.setAssigned = function (element, index) {
            element.status = "assigned";
        };
        $scope.setDone = function (element, index) {
            element.date = new Date();
            element.status = "done";
        };
        $scope.onAddToTimeline = function () {
            var itemsToAdd = [];
            if ($scope.topics.selected) {
                if ($scope.items.selected) {
                    itemsToAdd = [$scope.items.selected];
                } else {
                    itemsToAdd = $scope.items.content;
                }
            }
            var str = "Confermare l'aggiunta dei seguenti elementi? ";
            var realItemsToAdd = [];
            for (var i = 0; i < itemsToAdd.length; i++) {
                if ($scope.findById($scope.schedule.elements, itemsToAdd[i].id) === -1) {
                    str += " - " + itemsToAdd[i].name;
                    realItemsToAdd.push(itemsToAdd[i]);
                }
            }
            if (realItemsToAdd.length > 0) {
                prettyConfirm("Aggiunta elementi", str, function () {
                    for (var i = 0; i < realItemsToAdd.length; i++) {
                        var year;
                        if ($scope.months.selected.number < 6) {
                            year = parseInt($scope.schedule.year) + 1;
                        } else {
                            year = $scope.schedule.year;
                        }
                        var date = new Date(year, $scope.months.selected.number - 1, 1);
                        var element = $scope.initElement(realItemsToAdd[i], date);
                        $scope.reloadElementStatus(element);
                        $scope.schedule.elements.push(element);
                    }
                    $scope.sortSchedule();
                    $scope.$apply();
                });
            } else {
                swal("Errore", "Le voci sono gia' tutte presenti", "warning");
            }

        };
        $scope.initElement = function (element, date) {
            var newElement = {
                id: undefined,
                element: element,
                date: date,
                schedule: schedule_id,
                status: "todo",
                fullStatus: []
            };
            return newElement;
        };
        $scope.reloadElementStatus = function (element) {
            $http.post('../../../common/php/ajax/load-status.php', {element: element.element.id, schedule: schedule_id})
                    .then(
                            function (rx) {
                                element.fullStatus = rx.data;
                                element.fullStatus.forEach(function (element, index) {
                                    var d = new Date();
                                    d.setTime(element.date * 1000);
                                    element.date = d;
                                });
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };
        $scope.onRemoveFromTimeline = function (element, index) {
            prettyConfirm('Rimozione elemento', 'Vuoi davvero rimuovere l\'elemento?', function (ok) {
                if (ok) {
                    $scope.schedule.elements.splice(index, 1);
                    $scope.$apply();
                }
            });
        };
        $scope.findById = function (vector, id) {
            for (var i = 0; i < vector.length; i++) {
                if (vector[i].element.id === id) {
                    return i;
                }
            }
            return -1;
        };
        $scope.replaceContent = function (array, data) {
            array.content.splice(0, array.content.length);
            for (var i = 0; i < data.length; i++) {
                array.content.push(data[i]);
            }
        };
        $scope.loadModules = function () {
            $http.post('../../../common/php/ajax/load-elements.php', {type: "module"})
                    .then(
                            function (rx) {
                                $scope.replaceContent($scope.modules, rx.data);
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };
        $scope.loadTopics = function (module) {
            $http.post('../../../common/php/ajax/load-elements.php', {parent: module.id})
                    .then(
                            function (rx) {
                                $scope.replaceContent($scope.topics, rx.data);
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };
        $scope.loadItems = function (topic) {
            $http.post('../../../common/php/ajax/load-elements.php', {parent: topic.id})
                    .then(
                            function (rx) {
                                $scope.replaceContent($scope.items, rx.data);
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };
        $scope.loadSubjects = function () {
            $http.post('../../../common/php/ajax/load-subjects.php')
                    .then(
                            function (rx) {
                                $scope.replaceContent($scope.subjects, rx.data);
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };
        $scope.subjectStatus = function (element, subject) {
            for (var i = 0; i < element.fullStatus.length; i++) {
                if (element.fullStatus[i].subject === subject.id) {
                    return element.fullStatus[i].status;
                }
            }
            return "todo";
        };
        $scope.subjectDate = function (element, subject) {
            for (var i = 0; i < element.fullStatus.length; i++) {
                if (element.fullStatus[i].subject === subject.id) {
                    return element.fullStatus[i].date;
                }
            }
            return -1;
        };
        $scope.merge = function (left, right) {
            var result = [];
            while (left.length && right.length) {
                if (left[0].date <= right[0].date) {
                    result.push(left.shift());
                } else {
                    result.push(right.shift());
                }
            }
            while (left.length) {
                result.push(left.shift());
            }
            while (right.length) {
                result.push(right.shift());
            }
            return result;
        };
        $scope.mergeSort = function (arr) {
            if (arr.length < 2) {
                return arr;
            }
            var middle = parseInt(arr.length / 2);
            var left = arr.slice(0, middle);
            var right = arr.slice(middle, arr.length);
            return $scope.merge($scope.mergeSort(left), $scope.mergeSort(right));
        };
        $scope.sortSchedule = function () {
            $scope.schedule.elements = $scope.mergeSort($scope.schedule.elements);
        };
        $scope.loadSchedule = function () {
            $http.post('../../../common/php/ajax/load-schedule.php', {id: schedule_id})
                    .then(
                            function (rx) {
                                $scope.schedule = rx.data;
                                $scope.schedule.year2 = parseInt($scope.schedule.year) + 1;
                                $scope.schedule.elements.forEach(function (element, index) {
                                    var d = new Date();
                                    d.setTime(element.date * 1000);
                                    element.date = d;
                                });
                                $scope.sortSchedule();
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };
//MAIN
        $scope.loadModules();
        $scope.loadSubjects();
        $scope.loadSchedule();
    }]);