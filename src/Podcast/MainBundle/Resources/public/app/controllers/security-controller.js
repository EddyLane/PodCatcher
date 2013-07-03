'use strict';

angular.module('podcatcher')
    .controller('SecurityCtrl', function ($http, $scope, User) {

        $scope.copy = User;
        $scope.master = User;

        $scope.login = function (loginForm) {
            $http({
                method: 'POST',
                url: Routing.generate('fos_user_security_check')
            })
            .success(function(data, status, headers, config) {

            });
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