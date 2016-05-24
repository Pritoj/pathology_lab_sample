pathlabControllers.controller('operator', ['$scope', '$http', '$stateParams', 'loginService',
    function ($scope, $http, $stateParams, loginService) {
        $scope.operatorInfo = loginService.getUserDetails();
    }
]
);