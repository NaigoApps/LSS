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

        $scope.files = {
            content: []
        };

        $scope.newMaterial = {};

        $scope.addMode = false;

        $scope.exit = function () {
            window.location.replace("../../main.php");
        };

        $scope.onAddMaterial = function () {
            $scope.newMaterial = {
                name: undefined,
                url: undefined,
                file: false,
                private: false
            };
            if ($scope.items.selected !== undefined) {
                $scope.newMaterial.element = $scope.items.selected;
            } else if ($scope.topics.selected !== undefined) {
                $scope.newMaterial.element = $scope.topics.selected;
            } else if ($scope.modules.selected !== undefined) {
                $scope.newMaterial.element = $scope.modules.selected;
            }

            $scope.addMode = true;
        };

        $scope.onUploadFile = function () {
            $scope.uploadFile();
        };

        $scope.materialPost = function (material) {
            var forPost = {};
            forPost.name = (material.name !== undefined) ? material.name : undefined;
            forPost.url = (material.url !== undefined) ? material.url : undefined;
            forPost.file = (material.file !== undefined) ? material.file.id : undefined;
            forPost.private = (material.private !== undefined) ? material.private : undefined;
            forPost.element = (material.element !== undefined) ? material.element.id : undefined;
            return forPost;
        }

        $scope.onConfirmNewMaterial = function () {
            $http.post('../../ajax/create-material.php', {material: $scope.materialPost($scope.newMaterial)})
                    .then(
                            function (rx) {
                                $scope.loadMaterial($scope.newMaterial.element.id);
                                $scope.addMode = false;
                                $scope.newMaterial = {};
                                swal("Creazione avvenuta con successo");
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };

        $scope.resetNewMaterial = function () {
            $scope.addMode = false;
            $scope.newMaterial = {};
        };

        $scope.onDiscardNewMaterial = function () {
            $scope.resetNewMaterial();
        };

        $scope.onSelectModule = function (module) {
            $scope.modules.selected = module;
            $scope.topics.selected = undefined;
            $scope.loadTopics(module);
            $scope.items.content.splice(0, $scope.items.content.length);
            $scope.loadMaterial($scope.modules.selected.id);
            $scope.resetNewMaterial();
        };

        $scope.onSelectTopic = function (topic) {
            $scope.topics.selected = topic;
            $scope.items.selected = undefined;
            $scope.loadItems(topic);
            $scope.loadMaterial($scope.topics.selected.id);
            $scope.resetNewMaterial();
        };

        $scope.onSelectItem = function (item) {
            $scope.items.selected = item;
            $scope.loadMaterial($scope.items.selected.id);
            $scope.resetNewMaterial();
        };

        $scope.onSelectFile = function (file) {
            $scope.newMaterial.file = file;
        };

        $scope.onDeleteFile = function (file) {
            prettyConfirm("Cancellazione file", "Eliminare " + file.name + "?", function (ok) {
                if (ok) {
                    $http.post('../../ajax/delete-file.php', {file: file})
                            .then(
                                    function (rx) {
                                        $scope.loadFiles();
                                        if ($scope.newMaterial.file === file) {
                                            $scope.newMaterial.file = undefined;
                                        }
                                        swal("Cancellazione avvenuta con successo");
                                    },
                                    function (rx) {
                                        swal(rx.data);
                                    }
                            );
                }
            });
        };

        $scope.onDeleteMaterial = function (material) {
            prettyConfirm("Cancellazione materiale", "Eliminare " + material.name + "?", function (ok) {
                if (ok) {
                    $http.post('../../ajax/delete-material.php', {material: material})
                            .then(
                                    function (rx) {
                                        $scope.loadRightMaterial();
                                        swal("Cancellazione avvenuta con successo");
                                    },
                                    function (rx) {
                                        swal(rx.data);
                                    }
                            );
                }
            });
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
        $scope.loadFiles = function () {
            $http.post('../../../common/php/ajax/load-files.php')
                    .then(
                            function (rx) {
                                $scope.replaceContent($scope.files, rx.data);
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
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

        $scope.getRightElement = function () {
            var element = undefined;
            if ($scope.items.selected !== undefined) {
                element = $scope.items.selected;
            } else if ($scope.topics.selected !== undefined) {
                element = $scope.topics.selected;
            } else if ($scope.modules.selected !== undefined) {
                element = $scope.modules.selected;
            }
            return element;
        };

        $scope.loadRightMaterial = function () {
            var e = $scope.getRightElement();
            if (e !== undefined) {
                $scope.loadMaterial(e.id);
            }else{
                $scope.loadMaterial(undefined);
            }
        };

        $scope.loadMaterial = function (id) {
            $("#material-load-progress-container").show();
            $("#material-load-progress").width("100%");
            $http.post('../../../common/php/ajax/load-materials.php', {element: id})
                    .then(
                            function (rx) {
                                $scope.replaceContent($scope.materials, rx.data);
                                $scope.materials.content.forEach(function (element) {
                                    if (element.file) {
                                        element.file.url = "http://localhost/LSS/materials/" + element.file.name;
                                    }
                                });
                                $("#material-load-progress-container").hide();
                            },
                            function (rx) {
                                $("#material-load-progress-container").hide();
                                swal(rx.data);
                            }
                    );
        };
        $scope.uploadFile = function () {
            var formData = new FormData();
            if ($('#file-loader')[0].files[0].size <= 5 * 1024 * 1024) {
                formData.append('document', $('#file-loader')[0].files[0]);
                $('#file-upload-progress-container').show();
                $('#file-upload-progress').width('100%');
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
                        $scope.loadFiles();
                        $('#file-upload-progress-container').hide();
                        swal(rx);
                    },
                    error: function (rx, status) {
                        $('#file-upload-progress-container').hide();
                        swal(rx.responseText);
                    },
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
                });
            } else {
                swal("Dimensione eccessiva");
            }
        };
//MAIN
        $scope.loadModules();
        $scope.loadFiles();
        $scope.loadMaterial(undefined);
    }]);