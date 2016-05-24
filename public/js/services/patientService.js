pathlabServices.factory('patientService', ['$http','loginService',function($http,loginService){
	var addPatient = function(patientInfo){
		var userToken = loginService.getToken();
		var reqParams = {
			firstName : patientInfo.firstName,
			lastName : patientInfo.lastName,
			emailAddress : patientInfo.emailAddress,
			password : patientInfo.password,
			token : userToken
		};
		return $http.post(apiUrl+'patients/patients/addPatient',reqParams);
	}
	var editPatient = function(patientInfo){
		var reqParams = {
			userId : patientInfo.patientId,
			firstName : patientInfo.firstName,
			lastName : patientInfo.lastName,
			emailAddress : patientInfo.emailAddress,
			token :  loginService.getToken()
		};
		return $http.post(apiUrl+'patients/patients/editPatient',reqParams);
	}
	var editPatientPassword = function(patientInfo){
		var reqParams = {
			userId : patientInfo.userId,
			password : patientInfo.password,
			token :  loginService.getToken()
		};
		return $http.post(apiUrl+'patients/patients/editPatient',reqParams);
	}

	var deletePatient = function(patientId){
		var reqParams = {
			userId : patientId,
			token :  loginService.getToken()
		};
		return $http.post(apiUrl+'patients/patients/deletePatient',reqParams);
	}

	var getPatients = function(reqUserParams){
		var reqParams = {
			token : loginService.getToken()
		}
		return $http.post(apiUrl+'patients/patients/getPatients',reqParams);
	};
	return {
		addPatient : addPatient,
		getPatients : getPatients,
		editPatient : editPatient,
		editPatientPassword : editPatientPassword,
		deletePatient : deletePatient
	}
}]);