pathlabControllers.controller('login', ['$scope', '$http', '$stateParams', 'loginService',
    function ($scope, $http, $stateParams, loginService) {
        $scope.login = function(){
        	//set logging in to true
        	//to check how long this lasts
        	$scope.loggingIn = true;
        	//empty the error array
        	$scope.errs = [];
        	loginService.loginUser($scope.loginDetails.username,$scope.loginDetails.password).then(function(resp){
        		//show that request is over
        		$scope.loggingIn = false;
        		if(resp.data.status === false){
        			//if login was un successful
        			//show errors
        			$scope.errs = resp.data.data.err;
        		}
        	});
        };
    }
]
);