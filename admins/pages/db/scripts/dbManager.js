

app.controller("dbController", ['$http', '$scope', '$rootScope', function ($http, $scope, $rootScope) {

        $scope.argomenti = {
            content: [],
            name: 'argomenti',
            selected: undefined,
            current: {},
            newURL:
                    {
                        nome: "",
                        link: ""
                    },
            searchString: ""
        };
        $scope.voci = {
            content: [],
            name: 'voci',
            selected: undefined,
            current: {},
            newURL:
                    {
                        nome: "",
                        link: ""
                    },
            searchString: ""
        };
        $scope.moduli = {
            content: [],
            name: 'moduli',
            selected: undefined,
            current: {},
            newURL:
                    {
                        nome: "",
                        link: ""
                    },
            searchString: ""
        };
        $scope.moduliArgomentiConnected = {
            content: [],
            name: 'moduliargomenti'
        };
        $scope.moduliArgomentiToConnect = {
            content: [],
            name: 'moduliargomenti'
        };
        $scope.argomentiVociConnected = {
            content: [],
            name: 'argomentivoci'
        };
        $scope.argomentiVociToConnect = {
            content: [],
            name: 'argomentivoci'
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


        /**
         * 
         * @param {object} module Selected module
         * @returns {undefined}
         */
        $scope.onSelectModule = function (module) {
            $scope.moduli.selected = module;
            $scope.argomenti.selected = undefined;
            $rootScope.$emit('load-linked-table',
                    {
                        target: $scope.argomenti,
                        source: $scope.moduli
                    });
            $scope.voci.content.splice(0, $scope.voci.content.length);
        };

        /**
         * 
         * @param {object} topic Selected topic
         * @returns {undefined}
         */
        $scope.onSelectTopic = function (topic) {
            $scope.argomenti.selected = topic;
            $scope.voci.selected = undefined;
            $rootScope.$emit('load-linked-table',
                    {
                        target: $scope.voci,
                        source: $scope.argomenti
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

        $scope.loadAttachments = function (table) {
            $rootScope.$emit('load-urls',
                    {
                        target: table
                    });
            $rootScope.$emit('load-docs',
                    {
                        target: table
                    });
        };

        $rootScope.$on('request-connection-reload', function (event, data) {
            if (data.source.name === $scope.moduli.name) {
                $rootScope.$emit('load-connections',
                        {
                            parentTable: $scope.moduli,
                            targetTable: $scope.argomenti,
                            connectedTable: $scope.moduliArgomentiConnected,
                            toConnectTable: $scope.moduliArgomentiToConnect
                        });
            } else {
                $rootScope.$emit('load-connections',
                        {
                            parentTable: $scope.argomenti,
                            targetTable: $scope.voci,
                            connectedTable: $scope.argomentiVociConnected,
                            toConnectTable: $scope.argomentiVociToConnect,
                            parentLinkTableName: $scope.moduliArgomentiConnected.name,
                            parentItem: $scope.itemSelectedModule
                        });
            }
        });

        /**
         * Sets a module as current module
         * @param {object} module Chosen module
         * @returns {undefined}
         */
        $scope.onCurrentModule = function (module) {
            if (module !== undefined) {
                $scope.moduli.current = JSON.parse(JSON.stringify(module));
                $scope.loadAttachments($scope.moduli);
                $scope.moduli.newURL.nome = "";
                $scope.moduli.newURL.link = "";
            } else {
                $scope.moduli.current = {};
            }
        };

        /**
         * Sets a topic as current topic
         * @param {type} topic Chosen topic
         * @returns {undefined}
         */
        $scope.onCurrentTopic = function (topic) {
            if (topic !== undefined) {
                $scope.argomenti.current = JSON.parse(JSON.stringify(topic));
                $rootScope.$emit('load-connections',
                        {
                            parentTable: $scope.moduli,
                            targetTable: $scope.argomenti,
                            connectedTable: $scope.moduliArgomentiConnected,
                            toConnectTable: $scope.moduliArgomentiToConnect
                        });
                $scope.loadAttachments($scope.argomenti);
            } else {
                $scope.argomenti.current = {};
            }
            $scope.argomenti.newURL.nome = "";
            $scope.argomenti.newURL.link = "";
        };

        /**
         * Sets an item as current item
         * @param {type} item Chosen item
         * @returns {undefined}
         */
        $scope.onCurrentItem = function (item) {
            if (item !== undefined) {
                $scope.voci.current = JSON.parse(JSON.stringify(item));
                if ($scope.itemSelectedModule === undefined) {
                    $scope.itemSelectedModule = $scope.moduli.content[0];
                }
                $rootScope.$emit('load-connections',
                        {
                            parentTable: $scope.argomenti,
                            targetTable: $scope.voci,
                            connectedTable: $scope.argomentiVociConnected,
                            toConnectTable: $scope.argomentiVociToConnect,
                            parentLinkTableName: $scope.moduliArgomentiConnected.name,
                            parentItem: $scope.itemSelectedModule
                        });
                $scope.loadAttachments($scope.voci);
            } else {
                $scope.voci.current = {};
            }
            $scope.voci.newURL.nome = "";
            $scope.voci.newURL.link = "";
        };

        /**
         * Adds module to database
         * @returns {undefined}
         */
        $scope.onConfirmAddModule = function () {
            if ($scope.moduli.current.nome !== undefined) {
                if ($scope.moduli.current.descrizione === undefined) {
                    $scope.moduli.current.descrizione = "";
                }
                $rootScope.$emit('add-element',
                        {
                            target: $scope.moduli
                        });
            }
        };

        /**
         * Adds topic to database
         * @returns {undefined}
         */
        $scope.onConfirmAddTopic = function () {
            if ($scope.argomenti.current.nome !== undefined) {
                if ($scope.argomenti.current.descrizione === undefined) {
                    $scope.argomenti.current.descrizione = "";
                }
                $scope.moduli.current = $scope.moduli.selected;
                if ($scope.moduli.current !== undefined) {
                    $rootScope.$emit('add-linked-element',
                            {
                                target: $scope.argomenti,
                                source: $scope.moduli
                            });
                } else {
                    $scope.errorMessage("Selezionare un modulo");
                }
            }
        };

        /**
         * Adds item to database
         * @returns {undefined}
         */
        $scope.onConfirmAddItem = function () {
            if ($scope.voci.current.nome !== undefined) {
                if ($scope.voci.current.descrizione === undefined) {
                    $scope.voci.current.descrizione = "";
                }
                $scope.argomenti.current = $scope.argomenti.selected;
                if ($scope.argomenti.current !== undefined) {
                    $rootScope.$emit('add-linked-element',
                            {
                                target: $scope.voci,
                                source: $scope.argomenti
                            });
                } else {
                    $scope.errorMessage("Selezionare un argomento");
                }
            }
        };

        /**
         * Edit module in database
         */
        $scope.onConfirmEditModule = function () {
            $rootScope.$emit('commit-edit',
                    {
                        target: $scope.moduli
                    });
        };

        /**
         * Edit topic in database
         */
        $scope.onConfirmEditTopic = function () {
            $rootScope.$emit('commit-edit',
                    {
                        target: $scope.argomenti
                    });
        };

        /**
         * Edit item in database
         */
        $scope.onConfirmEditItem = function () {
            $rootScope.$emit('commit-edit',
                    {
                        target: $scope.voci
                    });
        };

        /**
         * Delete current module
         * @returns {undefined}
         */
        $scope.onDeleteCurrentModule = function () {
            $rootScope.$emit('commit-delete',
                    {
                        target: $scope.moduli
                    });
        };

        /**
         * Delete current topic
         * @returns {undefined}
         */
        $scope.onDeleteCurrentTopic = function () {
            $rootScope.$emit('commit-delete',
                    {
                        target: $scope.argomenti
                    });
        };

        /**
         * Delete current item
         * @returns {undefined}
         */
        $scope.onDeleteCurrentItem = function () {
            $rootScope.$emit('commit-delete',
                    {
                        target: $scope.voci
                    });
        };


        /**
         * Add a link between a module and current topic
         * @param {type} module Parent module
         * @returns {undefined}
         */
        $scope.onLinkModuleTopic = function (module) {
            $scope.moduli.current = module;
            $rootScope.$emit('add-link',
                    {
                        source: $scope.moduli,
                        target: $scope.argomenti
                    });
        };

        /**
         * Add a link between a topic and current item
         * @param {type} topic Parent topic
         * @returns {undefined}
         */
        $scope.onLinkTopicItem = function (topic) {
            $scope.argomenti.current = topic;
            $rootScope.$emit('add-link',
                    {
                        source: $scope.argomenti,
                        target: $scope.voci
                    });
        };

        /**
         * Removes link between a module and current topic
         * @param {object} module Module to unlink
         * @returns {undefined}
         */
        $scope.onUnlinkModule = function (module) {
            $scope.moduli.current = module;
            $rootScope.$emit('remove-link',
                    {
                        source: $scope.moduli,
                        target: $scope.argomenti
                    });
        };

        /**
         * Removes link between a topic and current item
         * @param {object} topic Topic to unlink
         * @returns {undefined}
         */
        $scope.onUnlinkTopic = function (topic) {
            $scope.argomenti.current = topic;
            $rootScope.$emit('remove-link',
                    {
                        source: $scope.argomenti,
                        target: $scope.voci
                    });
        };

        /**
         * Called when user selects a module while changing an item's links
         * @param {object} module Module selected
         * @returns {undefined}
         */
        $scope.onSelectBaseModule = function (module) {
            $scope.itemSelectedModule = module;
            if ($scope.itemSelectedModule === undefined) {
                $scope.itemSelectedModule = $scope.moduli.content[0];
            }
            $rootScope.$emit('load-connections',
                    {
                        parentTable: $scope.argomenti,
                        targetTable: $scope.voci,
                        connectedTable: $scope.argomentiVociConnected,
                        toConnectTable: $scope.argomentiVociToConnect,
                        parentLinkTableName: $scope.moduliArgomentiConnected.name,
                        parentItem: $scope.itemSelectedModule
                    });
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



        //MAIN
        //$scope.netLoadTable('moduli');
        $rootScope.$emit('load-table',
                {
                    target: $scope.moduli
                });
    }]);
