var app = angular.module('lss-db', []);

app.controller("linkController", ['$http', '$scope', '$rootScope', function ($http, $scope, $rootScope) {

        /**
         * Object type "table"
         * table -> name = table name
         * table -> content = table local content
         * table -> current = currently selected item
         */

        $scope.searching = false;

        /**
         * Fetch data from database
         * @param {table} data.target
         */
        $rootScope.$on('load-table', function (event, data) {
            $http.post(
                    '../../../common/db-manager.php',
                    {
                        command: 'load_table',
                        table: data.target.name
                    }
            ).then(
                    function (rx) {
                        data.target.content.splice(0, data.target.content.length);
                        for (var i = 0; i < rx.data.length; i++) {
                            data.target.content.push(rx.data[i]);
                        }
                    },
                    function (rx) {
                        error_message(rx.data);
                    }
            );
        });

        /**
         * Fetch data from database
         * @param {table} data.target
         * @param {table} data.source
         */
        $rootScope.$on('load-linked-table', function (event, data) {
            $http.post(
                    '../../../common/db-manager.php',
                    {
                        command: 'load_linked_table',
                        target_table: data.target.name,
                        source_table: data.source.name,
                        source_id: data.source.selected.id
                    }
            ).then(function (rx) {
                data.target.content.splice(0, data.target.content.length);
                for (var i = 0; i < rx.data.length; i++) {
                    data.target.content.push(rx.data[i]);
                }
            });
        });


        $rootScope.$on('load-urls', function (event, data) {
            $http.post(
                    './includes/attachments.php',
                    {
                        command: 'geturl',
                        table: data.target.name,
                        obj: data.target.current
                    }
            ).then(
                    function (rx) {
                        data.target.current.links = rx.data;
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data);
                    }
            );
        });

        $rootScope.$on('load-docs', function (event, data) {
            $http.post(
                    './includes/attachments.php',
                    {
                        command: 'getdocs',
                        table: data.target.name,
                        obj: data.target.current
                    }
            ).then(
                    function (rx) {
                        data.target.current.docs = rx.data;
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data);
                    }
            );
        });

        /**
         * 
         * @param {table} target Name of table in which search
         * @returns {undefined}
         */
        $rootScope.$on('search-object', function (event, data) {
            if (!$scope.searching) {
                $scope.searching = true;
                $http.post(
                        '../../../common/db-manager.php',
                        {
                            command: 'search_object',
                            table: data.target.name,
                            hint: data.target.searchString
                        }
                ).then(
                        function (rx) {
                            data.target.content.splice(0, data.target.content.length);
                            for (var i = 0; i < rx.data.length; i++) {
                                data.target.content.push(rx.data[i]);
                            }
                            $scope.searching = false;
                        },
                        function (rx) {
                            $scope.errorMessage(rx.data);
                            $scope.searching = false;
                        }
                );
            }
        });

        /**
         * 
         * @param {table} data.target target table
         * @returns {undefined}
         */
        $rootScope.$on('add-element', function (event, data) {
            $http.post(
                    './includes/db-editor.php',
                    {
                        command: 'addobj',
                        table: data.target.name,
                        obj: data.target.current
                    }
            ).then(
                    function (rx) {
                        data.target.current.id = rx.data;
                        data.target.content.push(JSON.parse(JSON.stringify(data.target.current)));
                        $scope.successMessage(data.target.current.nome + " inserito");
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data);
                    }
            );
        });

        /**
         * 
         * @param {string} mainTable
         * @param {string} parentTable
         * @param {object} element
         * @param {object} parentElement
         * @returns {undefined}
         */
        $rootScope.$on('add-linked-element', function (event, data) {
            $http.post(
                    './includes/db-editor.php',
                    {
                        command: 'addobj',
                        table: data.target.name,
                        obj: data.target.current
                    }
            ).then(
                    function (rx) {
                        data.target.current.id = rx.data;
                        data.target.content.push(JSON.parse(JSON.stringify(data.target.current)));
                        $scope.successMessage(data.target.current.nome + " inserito");

                        if (data.source !== undefined) {
                            $rootScope.$emit('add-link', data);
                        }
                    },
                    function (rx) {
                        $scope.errorMessage("Impossibile aggiungere " + data.target.current.nome);
                    }
            );
        });

        /**
         * Puts new element in place of old element (same ID) in specified table
         * @param {string} table Name of table to edit
         * @param {object} element Modified element
         * @returns {undefined}
         */
        $rootScope.$on('commit-edit', function (event, data) {
            $http.post(
                    './includes/db-editor.php',
                    {
                        command: 'updateobj',
                        table: data.target.name,
                        obj: data.target.current
                    }
            ).then(
                    function (rx) {
                        var index = $scope.findById(data.target.content, data.target.current.id);
                        data.target.content[index] = JSON.parse(JSON.stringify(data.target.current));
                        $scope.successMessage(data.target.current.nome + " modificato");
                    },
                    function (rx) {
                        $scope.errorMessage("Impossibile modificare " + data.target.current.nome);
                    }
            );
        });


        /**
         * Deletes an element from specified table
         * @param {string} table Name of the target table
         * @param {object} element Element to delete
         * @returns {undefined}
         */
        $rootScope.$on('commit-delete', function (event, data) {
            $http.post(
                    './includes/db-editor.php',
                    {
                        command: 'deleteobj',
                        table: data.target.name,
                        obj: data.target.current
                    }
            ).then(
                    function (rx) {
                        var index = $scope.findById(data.target.content, data.target.current.id);
                        data.target.content.splice(index, 1);
                        $scope.successMessage(data.target.current.nome + " eliminato");
                    },
                    function (rx) {
                        $scope.errorMessage("Impossibile eliminare" + data.target.current.nome);
                    }
            );
        });


        $rootScope.$on('load-connections', function (event, data) {
            $http.post(
                    '../../../common/db-manager.php',
                    {
                        command: 'get-elements-to-link',
                        table: data.parentTable.name,
                        obj: data.targetTable.current,
                        link_table: data.toConnectTable.name,
                        parent_link_table: data.parentLinkTableName,
                        parent: data.parentItem
                    }
            ).then(
                    function (rx1) {
                        data.toConnectTable.content = rx1.data;
                        $http.post(
                                '../../../common/db-manager.php',
                                {
                                    command: 'get-elements-linked',
                                    table: data.parentTable.name,
                                    obj: data.targetTable.current,
                                    link_table: data.connectedTable.name
                                }
                        ).then(
                                function (rx2) {
                                    data.connectedTable.content = rx2.data;
                                },
                                function (rx2) {
                                    $scope.errorMessage("Errore durante il caricamento dei collegamenti");
                                }
                        );
                    },
                    function (rx1) {
                        $scope.errorMessage("Errore durante il caricamento dei collegamenti");
                    }
            );
        });

        $rootScope.$on('find-topics-by-item', function (event, data) {
            $http.post(
                    '../../../common/db-manager.php',
                    {
                        command: 'find-topics-by-item',
                        obj: data.item
                    }
            ).then(
                    function (rx1) {
                        data.target.topic = rx1.data[0];
                        $http.post(
                                '../../../common/db-manager.php',
                                {
                                    command: 'find-modules-by-topic',
                                    obj: rx1.data[0]
                                }
                        ).then(
                                function (rx2) {
                                    data.target.module = rx2.data[0];
                                    if (data.callback !== undefined) {
                                        data.callback();
                                    }
                                },
                                function (rx2) {
                                    $scope.errorMessage("Errore durante il caricamento dei collegamenti");
                                }
                        );
                    },
                    function (rx1) {
                        $scope.errorMessage("Errore durante il caricamento dei collegamenti");
                    }
            );
        });

        /**
         * Links source element to destination element from specified tables
         * @param {string} srcTable Name of source table
         * @param {string} dstTable Name of source table
         * @param {object} srcElement source element
         * @param {object} dstElement destination element
         * @returns {undefined}
         */
        $rootScope.$on('add-link', function (event, data) {
            $http.post(
                    './includes/db-editor.php',
                    {
                        command: 'addlink',
                        src_table: data.source.name,
                        dst_table: data.target.name,
                        src_element: data.source.current,
                        dst_element: data.target.current
                    }
            ).then(
                    function (rx) {
                        $scope.successMessage("Collegamento effettuato");
                        $rootScope.$emit('request-connection-reload',
                                {
                                    source: data.source,
                                    target: data.target
                                });
                    },
                    function (rx) {
                        $scope.errorMessage("Collegamento non effettuato");
                    }
            );
        });

        /**
         * 
         * @param {string} srcTable Name of source table
         * @param {string} dstTable Name of destination table
         * @param {object} srcElement Parent object to unlink
         * @param {object} dstElement Children element to unlink
         * @returns {undefined}
         */
        $rootScope.$on('remove-link', function (event, data) {
            $http.post(
                    './includes/db-editor.php',
                    {
                        command: 'removelink',
                        src_table: data.source.name,
                        dst_table: data.target.name,
                        src_element: data.source.current,
                        dst_element: data.target.current
                    }
            ).then(
                    function (rx) {
                        $scope.successMessage("Collegamento rimosso");
                        $rootScope.$emit('request-connection-reload',
                                {
                                    source: data.source,
                                    target: data.target
                                });
                    },
                    function (rx) {
                        $scope.errorMessage("Collegamento non rimosso");
                    }
            );
        });

        $rootScope.$on('add-url', function (event, data) {
            $http.post(
                    './includes/attachments.php',
                    {
                        command: 'addurl',
                        table: data.target.name,
                        obj: data.target.current,
                        url: data.target.newURL
                    }
            ).then(
                    function (rx) {
                        data.target.newURL.modified = false;
                        data.target.newURL.id = rx.data;
                        data.target.current.links.push(JSON.parse(JSON.stringify(data.target.newURL)));
                        $scope.successMessage("Link inserito");
                    },
                    function (rx) {
                        $scope.errorMessage("Impossibile inserire il link");
                    }
            );
        });

        $rootScope.$on('edit-url', function (event, data) {
            $http.post(
                    './includes/attachments.php',
                    {
                        command: 'updateurl',
                        table: data.target.name,
                        url: data.url
                    }
            ).then(
                    function (rx) {
                        data.url.modified = false;
                        $scope.successMessage("Link modificato");
                    },
                    function (rx) {
                        $scope.errorMessage("Impossibile modificare il link");
                    }
            );
        });

        $rootScope.$on('remove-url', function (event, data) {
            $http.post(
                    './includes/attachments.php',
                    {
                        command: 'deleteurl',
                        table: data.target.name,
                        url: data.url
                    }
            ).then(
                    function (rx) {
                        var index = data.target.current.links.indexOf(data.url);
                        data.target.current.links.splice(index, 1);
                        $scope.successMessage("Link eliminato");
                    },
                    function (rx) {
                        $scope.errorMessage("Impossibile eliminare il link");
                    }
            );
        });

        $rootScope.$on('add-document', function (event, data) {
            var formData = new FormData($('#' + data.target.name + 'FileForm')[0]);
            formData.append("type", data.target.name);
            formData.append("id", data.target.current.id);
            $.ajax({
                url: './includes/upload.php',
                type: 'POST',
                xhr: function () {
                    var myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload) {
                        myXhr.upload.addEventListener('progress',
                                function (e) {
                                    console.log(e.loaded);
                                    if (e.lengthComputable) {
                                        $('#progress-' + data.target.name + "-" + data.target.current.id).attr(
                                                {
                                                    'aria-valuenow': e.loaded,
                                                    'aria-valuemax': e.total
                                                }
                                        );
                                        $('#progress-' + data.target.name + "-" + data.target.current.id).width(e.loaded * 100 / e.total + '%');
                                    }
                                }
                        , false);
                    }
                    return myXhr;
                },
                success: function (rx) {
                    $scope.successMessage("Trasferimento completato");
                    $rootScope.$emit('load-urls', {
                        target: data.target
                    });
                    $rootScope.$emit('load-docs', {
                        target: data.target
                    });
                },
                error: function (rx) {
                    $scope.errorMessage("Errore durante il trasferimento");
                },
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            });
        });


        $rootScope.$on('remove-document', function (event, data) {
            $http.post(
                    './includes/attachments.php',
                    {
                        command: 'deletedoc',
                        table: data.target.name,
                        obj: data.target.current,
                        document: data.document
                    }
            ).then(
                    function (rx) {
                        var index = data.target.current.docs.indexOf(data.document);
                        data.target.current.docs.splice(index, 1);
                        $scope.successMessage("Documento eliminato");
                    },
                    function (rx) {
                        $scope.errorMessage("Impossibile eliminare il documento");
                    }
            );
        });

        $scope.findById = function (vector, id) {
            for (var i = 0; i < vector.length; i++) {
                if (vector[i].id === id) {
                    return i;
                }
            }
            return -1;
        };

        $scope.findObjectById = function (vector, id) {
            var index = $scope.findById(vector, id);
            if (index !== -1) {
                return vector[index];
            }
            return undefined;
        };

        $scope.copyObject = function (source, destination) {
            destination.id = source.id;
            destination.nome = source.nome;
            destination.descrizione = source.descrizione;
            destination.links = source.links;
            if (destination.links === undefined) {
                destination.links = [];
            }
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
