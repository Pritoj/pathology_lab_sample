pathlabControllers.controller('reportdetails', ['$scope', '$http', '$stateParams', 'patientService','reportService','testService',
    function($scope, $http, $stateParams, patientService,reportService, testService) {
    	$scope.testList = [];

    	$scope.addTestResult = function(newTest){
    		//empty out the alerts
            $scope.alerts = [];
            var testFields = newTest.values;
            testService.addTestResult($stateParams.reportId,testFields).then(function(resp) {
                if (resp.data.status) {
                    //hide the add patient elements
                    $scope.addReportShow = false;
                    //show a success notification
                    $scope.alerts.push({
                        type: 'success',
                        timeout: 5,
                        msg: 'Test has been added successfully'
                    });
                    $scope.reportDetails = resp.data.data[0];
                    //reinnitialize report info
                    $scope.newTest = {};
                    //close the add dialog
                    $scope.addTestResultShow = false;
                } else {
                    for (x in resp.data.data.err) {
                        $scope.alerts.push({
                            type: 'danger',
                            timeout: false,
                            msg: resp.data.data.err[x]
                        });
                    }
                }
            });
    	};


    	reportService.getReportDetails($stateParams.reportId).then(function(resp){
            if(resp.data.status){
                $scope.reportDetails = resp.data.data;
            }
            else{
            	//$state.go('reports');
            }
        });
        testService.getTests().then(function(resp){
            if(resp.data.status){
                $scope.testList = resp.data.data;
            }
        });
    }]);