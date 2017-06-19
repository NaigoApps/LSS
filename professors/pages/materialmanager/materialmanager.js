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

        $scope.materials = {
            content: []
        };

        $scope.newMaterial = {};



        $scope.addMode = false;

        $scope.exit = function () {
            prettyConfirm("Esci", "I dati non salvati saranno persi", function (ok) {
                if (ok) {
                    window.location.replace("../../main.php");
                }
            });
        };

        $scope.onAddMaterial = function () {
            $scope.newMaterial = {
                name: undefined,
                url: undefined,
                file: undefined,
                private: false,
                internal: false
            };

            $scope.addMode = true;
        };

        $scope.onConfirmNewMaterial = function () {
            $scope.uploadFile();
        };

        $scope.onDiscardNewMaterial = function () {
            $scope.addMode = false;
            $scope.newMaterial = {};
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
            $scope.loadMaterial($scope.modules.selected.id);
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
            $scope.loadMaterial($scope.topics.selected.id);
        };
        /**
         * 
         * @param {object} item Selected item
         * @returns {undefined}
         */
        $scope.onSelectItem = function (item) {
            $scope.items.selected = item;
            $scope.loadMaterial($scope.items.selected.id);
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
        $scope.loadMaterial = function (element) {
            var id = (element !== undefined) ? element.id : undefined;
            $http.post('../../../common/php/ajax/load-materials.php', {element: id})
                    .then(
                            function (rx) {
                                $scope.replaceContent($scope.materials, rx.data);
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };
        $scope.uploadFile = function () {
            var formData = new FormData();
            formData.append('document', $('#file-loader')[0].files[0]); 
            $.ajax({
                url: '../../ajax/file-upload.php',
                type: 'POST',
                xhr: function () {
                    var myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload) {
                        myXhr.upload.addEventListener('progress',
                                function (e) {
                                    console.log(e.loaded);
                                    if (e.lengthComputable) {
                                        $('#file-upload-progress').attr(
                                                {
                                                    'aria-valuenow': e.loaded,
                                                    'aria-valuemax': e.total
                                                }
                                        );
                                        $('#file-upload-progress').width(e.loaded * 100 / e.total + '%');
                                    }
                                }
                        , false);
                    }
                    return myXhr;
                },
                success: function (rx, status) {
                    swal(rx);
                },
                error: function (rx, status) {
                    swal(rx.responseText);
                },
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            });
        };
//MAIN
        $scope.loadModules();
        $scope.loadMaterial(undefined);
    }]);