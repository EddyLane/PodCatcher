'use strict';

angular.module('podcatcher')
    .controller('SecurityCtrl', function ($http, $scope, User) {

        $scope.copy = User;
        $scope.master = User;
        $scope.user = User;

        $scope.login = function () {
            $scope.loading = true;
            $http({
                method: 'POST',
                url: Routing.generate('fos_user_security_check'),
                data: $('#login-form').serialize(),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
            .success(function(data, status, headers, config) {
                $scope.user.authenticate();
                $scope.open = false;
                $scope.loading = false;
            })
            .error(function(response) {
                $scope.error = response.message;
                $scope.loading = false;
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