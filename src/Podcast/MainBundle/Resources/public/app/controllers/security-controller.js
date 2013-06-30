'use strict';

angular.module('podcatcher')
    .controller('SecurityCtrl', function ($http, $scope, User) {

        $scope.copy = User;
        $scope.master = User;

        $scope.register = function (userForm) {

            $http({
                method: 'POST',
                url: Routing.generate('register'),
                data: $('#registration-form').serialize(),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .success(function (data, status, headers, config) {
                    console.log(data);
                }).error(function (data, status, headers, config) {
                    console.log(status);
                    console.log(data);
                });


            $scope.master = angular.copy($scope.copy);
            $scope.master.persist();
        };

        $scope.options = {
            backdropFade: true,
            dialogFade: true
        };

        $scope.reset = function () {
            $scope.copy = angular.copy($scope.master);
        };

        $scope.reset();
    });