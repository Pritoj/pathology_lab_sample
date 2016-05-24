pathlabControllers.controller('patientreportdetails', ['$scope', '$http', '$stateParams', '$state', '$uibModal', 'patientService','reportService','testService',
    function($scope, $http, $stateParams, $state, $uibModal, patientService,reportService, testService) {
	   


    	reportService.getPatientReportDetails($stateParams.reportId).then(function(resp){
            if(resp.data.status){
                $scope.reportDetails = resp.data.data;
            }
            else{
            	$state.go('patientdash');
            }
        });
    }]);

