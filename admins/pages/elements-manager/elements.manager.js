
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
app.controller("dbController", ['$http', '$scope', '$rootScope', function ($http, $scope, $rootScope) {

        $scope.modules = {
            content: [],
            selected: undefined,
            searchString: "",
            addMode: false
        };
        $scope.newModule = {};

        $scope.topics = {
            content: [],
            selected: undefined,
            searchString: "",
            addMode: false
        };
        $scope.newTopic = {};

        $scope.items = {
            content: [],
            selected: undefined,
            searchString: "",
            addMode: false
        };
        $scope.newItem = {};

        $scope.modalData = {
            type: "",
            modalTitle: "",
            name: "",
            description: ""
        };

        $scope.searching = false;

        $scope.currentElement = undefined;
        $scope.currentURL = {nome: "", link: ""};

        $scope.srcModName = "";
        $scope.srcTopName = "";
        $scope.srcIteName = "";

        $scope.candidateModules = [];
        $scope.linkedModules = [];

        $scope.candidateTopics = [];

        $scope.compareTo = function (e1, e2) {
            return e1.name.localeCompare(e2.name);
        };

        $scope.exit = function () {
            prettyConfirm("Esci", "I dati non salvati saranno persi", function (ok) {
                if (ok) {
                    window.location.replace("../../main.php");
                }
            });
        };

        $scope.resetTopics = function (reload) {
            $scope.topics.addMode = false;
            $scope.topics.selected = undefined;
            $scope.topics.content = [];
            if (reload) {
                $scope.loadTopics($scope.modules.selected, $scope.topics);
            }
            $scope.resetItems(false);
        };

        $scope.resetItems = function (reload) {
            $scope.items.addMode = false;
            $scope.items.selected = undefined;
            $scope.items.content = [];
            if (reload) {
                $scope.loadItems($scope.topics.selected);
            }
        };

        $scope.onSelectModule = function (module) {
            $scope.modules.selected = module;
            $scope.resetTopics(true);
            $scope.resetItems(false);
        };

        $scope.onSelectTopic = function (topic) {
            $scope.topics.selected = topic;
            $scope.resetItems(true);
        };

        $scope.onAddModule = function () {
            $scope.modules.addMode = true;
        };

        $scope.onAddTopic = function () {
            $scope.topics.addMode = true;
        };

        $scope.onAddItem = function () {
            $scope.items.addMode = true;
        };

        $scope.onConfirmAddModule = function () {
            $http.post('../../ajax/insert-element.php',
                    {
                        type: "module",
                        name: $scope.newModule.name,
                        description: $scope.newModule.description
                    })
                    .then(
                            function (rx) {
                                $scope.newModule.id = rx.data;
                                $scope.modules.content.push($scope.newModule);
                                $scope.newModule = {};
                                $scope.modules.content.sort($scope.compareTo);
                                $scope.modules.addMode = false;
                                swal({
                                    title: "Modulo",
                                    text: "inserito correttamente",
                                    type: "success",
                                    closeOnConfirm: false
                                });
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };

        $scope.onConfirmAddTopic = function () {
            $http.post('../../ajax/insert-element.php',
                    {
                        type: "topic",
                        name: $scope.newTopic.name,
                        description: $scope.newTopic.description,
                        parent: $scope.modules.selected
                    })
                    .then(
                            function (rx) {
                                $scope.newTopic.id = rx.data;
                                $scope.topics.content.push($scope.newTopic);
                                $scope.newTopic = {};
                                $scope.topics.content.sort($scope.compareTo);
                                $scope.topics.addMode = false;
                                swal({
                                    title: "Argomento",
                                    text: "inserito correttamente",
                                    type: "success",
                                    closeOnConfirm: false
                                });
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };

        $scope.onConfirmAddItem = function () {
            $http.post('../../ajax/insert-element.php',
                    {
                        type: "item",
                        name: $scope.newItem.name,
                        description: $scope.newItem.description,
                        parent: $scope.topics.selected
                    })
                    .then(
                            function (rx) {
                                $scope.newItem.id = rx.data;
                                $scope.items.content.push($scope.newItem);
                                $scope.newItem = {};
                                $scope.items.content.sort($scope.compareTo);
                                $scope.items.addMode = false;
                                swal({
                                    title: "Voce",
                                    text: "inserita correttamente",
                                    type: "success",
                                    closeOnConfirm: false
                                });
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };

        $scope.onDiscardAddModule = function () {
            $scope.modules.addMode = false;
        };

        $scope.onDiscardAddTopic = function () {
            $scope.topics.addMode = false;
        };

        $scope.onDiscardAddItem = function () {
            $scope.items.addMode = false;
        };

        $scope.onEditModule = function (module) {
            module.editMode = true;
            module.newName = module.name;
            module.newDescription = module.description;
            module.newParent = undefined;
        };

        $scope.onEditTopic = function (topic) {
            topic.editMode = true;
            topic.newName = topic.name;
            topic.newDescription = topic.description;
            topic.newParent = topic.parent;
        };

        $scope.onEditItem = function (item) {
            item.editMode = true;
            item.newName = item.name;
            item.newDescription = item.description;
            item.newParent = item.parent;
            item.baseModule = item.parent.parent;
            item.editTopics = {};
            $scope.loadTopics(item.baseModule, item.editTopics);
        };

        $scope.onDiscardEditModule = function (module) {
            module.editMode = false;
            module.newName = "";
            module.newDescription = "";
        };

        $scope.onDiscardEditTopic = function (topic) {
            topic.editMode = false;
            topic.newName = "";
            topic.newDescription = "";
        };

        $scope.onDiscardEditItem = function (item) {
            item.editMode = false;
            item.newName = "";
            item.newDescription = "";
        };

        $scope.onConfirmEditElement = function (element) {
            if (element.name !== element.newName ||
                    element.description !== element.newDescription ||
                    element.newParent !== undefined && element.newParent !== null && element.parent.id !== element.newParent.id) {
                var update = JSON.parse(JSON.stringify(element));
                update.name = element.newName;
                update.description = element.newDescription;
                update.parent = element.newParent;
                $http.post('../../ajax/update-element.php',
                        {
                            element: update
                        })
                        .then(
                                function (rx) {
                                    element.editMode = false;
                                    if (element.type === "topic") {
                                        $scope.loadTopics($scope.modules.selected, $scope.topics);
                                    } else if (element.type === "item") {
                                        $scope.loadItems($scope.topics.selected);
                                    }
                                    swal({
                                        title: "Elemento",
                                        text: "modificato correttamente",
                                        type: "success",
                                        closeOnConfirm: false
                                    });
                                },
                                function (rx) {
                                    swal(rx.data);
                                }
                        );
            } else {
                swal("Nessuna modifica da salvare");
            }
        };

        $scope.onDeleteElement = function (element) {
            swal(
                    {
                        title: "Cancellazione elemento",
                        text: "Rimuovere " + element.name + "?",
                        type: "warning", showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sì",
                        cancelButtonText: "No"
                    },
                    function () {
                        $http.post('../../ajax/delete-element.php', {id: element.id})
                                .then(
                                        function (rx) {
                                            if (element.type === "module") {
                                                $scope.modules.content.splice($scope.modules.content.indexOf(element), 1);
                                                $scope.resetTopics(true);
                                            }else if (element.type === "topic") {
                                                //TODO: EDIT INDEXOF
                                                $scope.topics.content.splice($scope.topics.content.indexOf(element), 1);
                                                $scope.resetItems(true);
                                            }else if (element.type === "item") {
                                                $scope.items.content.splice($scope.items.content.indexOf(element), 1);
                                            }
                                            swal("Elemento", "eliminato correttamente", "success");
                                        },
                                        function (rx) {
                                            swal(rx.data);
                                        }
                                );
                    }
            );

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

        /**
         * Called when user selects a module while changing an item's links
         * @param {object} module Module selected
         * @returns {undefined}
         */
        $scope.onSelectBaseModule = function (item) {
            $scope.loadTopics(item.baseModule, item.editTopics);
        };


        $scope.onAddModuleURL = function () {
            $rootScope.$emit('add-url',
                    {
                        target: $scope.moduli
                    });
        };

        $scope.onAddTopicURL = function () {
            $rootScope.$emit('add-url',
                    {
                        target: $scope.argomenti
                    });
        };

        $scope.onAddItemURL = function () {
            $rootScope.$emit('add-url',
                    {
                        target: $scope.voci
                    });
        };

        $scope.onEditModuleURL = function (URL) {
            $rootScope.$emit('edit-url',
                    {
                        target: $scope.moduli,
                        url: URL
                    });
        };

        $scope.onEditTopicURL = function (URL) {
            $rootScope.$emit('edit-url',
                    {
                        target: $scope.argomenti,
                        url: URL
                    });
        };

        $scope.onEditItemURL = function (URL) {
            $rootScope.$emit('edit-url',
                    {
                        target: $scope.voci,
                        url: URL
                    });
        };

        $scope.onDeleteModuleURL = function (URL) {
            $rootScope.$emit('remove-url',
                    {
                        target: $scope.moduli,
                        url: URL
                    });
        };

        $scope.onDeleteTopicURL = function (URL) {
            $rootScope.$emit('remove-url',
                    {
                        target: $scope.argomenti,
                        url: URL
                    });
        };

        $scope.onDeleteItemURL = function (URL) {
            $rootScope.$emit('remove-url',
                    {
                        target: $scope.voci,
                        url: URL
                    });
        };

        $scope.onAddModuleDocument = function () {
            $rootScope.$emit('add-document', {
                target: $scope.moduli
            });
            //$scope.netAddDocument('moduli', $scope.moduli.current);
        };
        $scope.onAddTopicDocument = function () {
            $rootScope.$emit('add-document', {
                target: $scope.argomenti
            });
        };
        $scope.onAddItemDocument = function () {
            $rootScope.$emit('add-document', {
                target: $scope.voci
            });
        };

        $scope.onDeleteModuleDocument = function (doc) {
            $rootScope.$emit('remove-document', {
                target: $scope.moduli,
                document: doc
            });
        };

        $scope.onDeleteTopicDocument = function (doc) {
            $rootScope.$emit('remove-document', {
                target: $scope.argomenti,
                document: doc
            });
        };

        $scope.onDeleteItemDocument = function (doc) {
            $rootScope.$emit('remove-document', {
                target: $scope.voci,
                document: doc
            });
        };

        $scope.loadModules = function () {
            $http.post('../../../common/php/ajax/load-elements.php', {type: "module"})
                    .then(
                            function (rx) {
                                $scope.modules.content = rx.data;
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };
        $scope.loadTopics = function (module, target) {
            $http.post('../../../common/php/ajax/load-elements.php', {parent: module.id})
                    .then(
                            function (rx) {
                                target.content = rx.data;
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
                                $scope.items.content = rx.data;
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };


        //MAIN
        //$scope.netLoadTable('moduli');
        $scope.loadModules();
    }]);
