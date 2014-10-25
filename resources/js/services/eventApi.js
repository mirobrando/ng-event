'use strict';

angular.module(ngAppName)
    .service('eventApi', ['$resource', '$q', function($resource, $q) {

        var fireApi = $resource("/:lang/event/fire", {lang: "@lang"}, {
            save: {method: 'POST', params:{}}
        });

        var getApi = $resource("/:lang/event/get/:service/:method",
            {lang: "@lang", service: "@service", method: "@method"}, {
            get: {method: 'GET', params:{}, isArray:false}
        });

        var queryApi = $resource("/:lang/event/query/:service/:method",
            {lang: "@lang", service: "@service", method: "@method"}, {
                query: {method: 'GET', params:{}, isArray:true}
        });

        var serviceApi = $resource("/:lang/event/:service/:method",
            {lang: "@lang", service: "@service", method: "@method"}, {
            save: {method: 'POST', params:{}},
            put: {method: 'PUT', params:{}},
            delete: {method: 'DELETE', params:{}}

        });




        this.callFire = function (eventName, param) {
            var deferred = $q.defer();
            fireApi.save({}, {'eventName': eventName, 'param': param, 'lang': language},
                function () {
                    deferred.resolve();
                },
                function () {
                    deferred.reject();
                });

            return deferred.promise;
        };

        this.getData = function (service, method, params) {
            var deferred = $q.defer();
            getApi.get(params, {'method': method, 'service': service, 'lang': language},
                function (data) {
                    deferred.resolve(data);
                },
                function () {
                    deferred.reject();
                });

            return deferred.promise;
        };

        this.getDataQuery = function (service, method, params) {
            var deferred = $q.defer();
            queryApi.query(params, {'method': method, 'service': service, 'lang': language},
                function (data) {
                    deferred.resolve(data);
                },
                function () {
                    deferred.reject();
                });

            return deferred.promise;
        };

        this.sendPost = function (service, method, param) {
            var deferred = $q.defer();
            serviceApi.save({}, {'service': service, 'method': method, 'param': param, 'lang': language},
                function (data) {
                    deferred.resolve(data);
                },
                function (responseHeader) {
                    deferred.reject(responseHeader);
                });

            return deferred.promise;
        };

        this.sendPut = function (service, method, param) {
            var deferred = $q.defer();
            serviceApi.put({}, {'service': service, 'method': method, 'param': param, 'lang': language},
                function (data) {
                    deferred.resolve(data);
                },
                function (responseHeader) {
                    deferred.reject(responseHeader);
                });

            return deferred.promise;
        };

        this.sendDelete = function (service, method, param) {
            var deferred = $q.defer();
            serviceApi.delete({}, {'service': service, 'method': method, 'param': param, 'lang': language},
                function () {
                    deferred.resolve();
                },
                function (responseHeader) {
                    deferred.reject();
                });

            return deferred.promise;
        };

    }])