pathlabServices.factory('loginService', ['$http','$state','localStorageService',function($http,$state,localStorageService){
	//this function stores the data in session
	//so it can be used throughout the app
	var storeUserInfo = function(userData){
		localStorageService.set('role',userData.data.role);
		localStorageService.set('userDetails',userData.data);
		localStorageService.set('token',userData.token);
	}
	//this removes data of the logged in user
	//from local storage
	var removeUserInfo = function(){
		localStorageService.remove('role');
		localStorageService.remove('userDetails');
		localStorageService.remove('token');
	}
	//send the roe of the user
	var getUserRole = function(){
		return localStorageService.get('role');
	}
	var getToken = function(){
		return localStorageService.get('token');
	}
	var getUserDetails = function(){
		return localStorageService.get('userDetails');
	}


	var loginUser = function(username,password){
		return $http.post(apiUrl+'login/login/login',{username:username,password:password}).then(
			function(successResp){
				//the request was successful
				//check if login was successful
				if(successResp.data.status){
					storeUserInfo(successResp.data.data);
					if(getUserRole()=='Operator'){
						$state.go('operator');
					}
					else if(getUserRole()=='Patient'){
						$state.go('patientdash');
					}
				}
				return successResp;
			},
			function(errorResp){

			}
		);
	};

	var logoutUser = function(){
		removeUserInfo();
		$state.go('login');
	}

	return {
		loginUser : loginUser,
		getUserRole : getUserRole,
		getToken : getToken,
		getUserDetails : getUserDetails
	}
}]);