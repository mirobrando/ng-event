'use strict';

angular.module(ngAppName)
    .directive('mlEvent', function(eventApi) {
        return {
            restrict: 'A',
            scope: {
                callback: '&mlEventCallback',
                callbackError: '&mlEventCallbackError',
                param: '=mlEventParam'
            },
            link: function (scope, elem, attrs) {
                var event = attrs.mlEvent;

                elem.on('click', function () {
                    eventApi.callFire(event, scope.param).then(
                        function() {
                            scope.callback();
                        },
                        function() {
                            scope.callbackError();
                        }
                    );
                });
            }
        }
    })
    .directive('mlEventGet', function(eventApi) {
        return {
            restrict: 'A',
            scope: {
                callback: '&mlEventGetCallback',
                callbackError: '&mlEventGetCallbackError',
                params: '=mlEventGetParam'
            },
            link: function (scope, elem, attrs) {
                var service = attrs.mlEventGet;
                var method = attrs.mlEventGetMethod;

                elem.on('click', function () {
                    eventApi.getData(service, method, scope.params).then(
                        function(data) {
                            scope.callback({data: data.message});
                        },
                        function() {
                            scope.callbackError();
                        }
                    );
                });
            }
        }
    })
    .directive('mlEventQuery', function(eventApi) {
    return {
        restrict: 'A',
        scope: {
            callback: '&mlEventQueryCallback',
            callbackError: '&mlEventQueryCallbackError',
            params: '=mlEventQueryParam'
        },
        link: function (scope, elem, attrs) {
            var service = attrs.mlEventQuery;
            var method = attrs.mlEventQueryMethod;

            elem.on('click', function () {
                eventApi.getDataQuery(service, method, scope.params).then(
                    function(data) {
                        scope.callback({data: data});
                    },
                    function() {
                        scope.callbackError();
                    }
                );
            });
        }
    }
    })
    .directive('mlEventPost', function(eventApi) {
        return {
            restrict: 'A',
            scope: {
                callback: '&mlEventPostCallback',
                callbackValidate: '&mlEventPostCallbackValidate',
                callbackError: '&mlEventPostCallbackError',
                params: '=mlEventPostParam'
            },
            link: function (scope, elem, attrs) {
                var service = attrs.mlEventPost;
                var method = attrs.mlEventPostMethod;

                elem.on('click', function () {
                    eventApi.sendPost(service, method, scope.params).then(
                        function(data) {
                            scope.callback({data: data});
                        },
                        function(responseHeader) {
                            if (responseHeader.status == 409) {
                                scope.callbackValidate({data: responseHeader.data});
                            } else {
                                scope.callbackError();
                            }
                        }
                    );
                });
            }
        }
    })
    .directive('mlEventPut', function(eventApi) {
        return {
            restrict: 'A',
            scope: {
                callback: '&mlEventPutCallback',
                callbackValidate: '&mlEventPutCallbackValidate',
                callbackError: '&mlEventPutCallbackError',
                params: '=mlEventPutParam'
            },
            link: function (scope, elem, attrs) {
                var service = attrs.mlEventPut;
                var method = attrs.mlEventPutMethod;

                elem.on('click', function () {
                    eventApi.sendPut(service, method, scope.params).then(
                        function(data) {
                            scope.callback({data: data});
                        },
                        function(responseHeader) {
                            if (responseHeader.status == 409) {
                                scope.callbackValidate({data: responseHeader.data});
                            } else {
                                scope.callbackError();
                            }
                        }
                    );
                });
            }
        }
    })
    .directive('mlEventDelete', function(eventApi) {
        return {
            restrict: 'A',
            scope: {
                callback: '&mlEventDeleteCallback',
                callbackError: '&mlEventDeleteCallbackError',
                params: '=mlEventPutParam'
            },
            link: function (scope, elem, attrs) {
                var service = attrs.mlEventDelete;
                var method = attrs.mlEventDeleteMethod;

                elem.on('click', function () {
                    eventApi.sendDelete(service, method, scope.params).then(
                        function() {
                            scope.callback();
                        },
                        function() {
                            scope.callbackError();
                        }
                    );
                });
            }
        }
    });
