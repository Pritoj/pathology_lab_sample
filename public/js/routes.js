pathlab.config(['$stateProvider', '$urlRouterProvider', '$locationProvider', function($stateProvider, $urlRouterProvider, $locationProvider) {
    $locationProvider.html5Mode(true);
    // For any unmatched url, send to /route1
    $urlRouterProvider.otherwise("/login");
    

    $stateProvider.state('login', {
        url: "/login",
        templateUrl: templateUrl + 'templates/login.html',
        controller: 'login'
    });

    /****** OPERATOR STATES **********/

    $stateProvider.state('operator', {
        url: "/operator",
        templateUrl: templateUrl + 'templates/operator.html',
        controller: 'operator'
    });

    $stateProvider.state('patients', {
        url: "/patients",
        templateUrl: templateUrl + 'templates/patients.html',
        controller: 'patients'
    });

    $stateProvider.state('tests', {
        url: "/tests",
        templateUrl: templateUrl + 'templates/tests.html',
        controller: 'tests'
    });

    $stateProvider.state('reports', {
        url: "/reports",
        templateUrl: templateUrl + 'templates/reports.html',
        controller: 'reports'
    });

    

    $stateProvider.state('reportdetails', {
        url: "/report/:reportId",
        templateUrl: templateUrl + 'templates/reportdetails.html',
        controller: 'reportdetails'
    });


    /****PATIENT ROUTES *****/
    $stateProvider.state('patientdash', {
        url: "/patientdash",
        templateUrl: templateUrl + 'templates/patientdash.html',
        controller: 'patientdash'
    });

    $stateProvider.state('patientreportdetails', {
        url: "/patientreportdetails/:reportId",
        templateUrl: templateUrl + 'templates/patientreportdetails.html',
        controller: 'patientreportdetails'
    });
}]);




//Login checking
pathlab.run(['$state','$rootScope','loginService',function($state,$rootScope,loginService){
	var stateCheck = {
		//states restricted to operators only
		'operator' : ['operator','patients','tests','reports','reportdetails'],
		//states restricted to patients only
		'patient' : ['patient']
	};
	$rootScope.$on('$stateChangeStart',function(ev,toState,toParams,fromState,fromParams){
		if(stateCheck.operator.indexOf(toState.name)>-1){
			//this state is for operators check if token exists and if 
			//the logged in role is that of an operator
			if(loginService.getUserRole() != 'Operator' || !loginService.getToken()){
				//this person is not logged in
				//redirect to login page
				ev.preventDefault();
				$state.go('login');
			} 
		}
		else if(stateCheck.patient.indexOf(toState.name)>-1){

		}
	});
}]);