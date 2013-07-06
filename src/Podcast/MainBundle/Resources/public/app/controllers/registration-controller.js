'use strict';

angular.module('podcatcher')
    .controller('RegistrationCtrl', function ($http, $scope, User) {

        $scope.user = User;

        $scope.register = function (user) {
            $http({
                method: 'POST',
                url: Routing.generate('register'),
                data: $('#registration-form').serialize(),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .success(function(data, status, headers, config) {
                    $scope.user.authenticate();
                    $scope.shouldBeOpen = false;
                })
                .error(function(response) {
                    $scope.errors = response;
                });
        };

        $scope.options = {
            backdropFade: true,
            dialogFade: true
        };
    });