pathlabControllers.controller('patients', ['$scope', '$http', '$stateParams', '$uibModal','patientService',
    function ($scope, $http, $stateParams, $uibModal, patientService) {
    	//to show alert messages
    	$scope.alerts = [];
    	//set patient list to false to show 
    	//a loader perhaps
    	$scope.patientList = [];
    	$scope.addPatient = function(info){
    		//empty out the alerts
    		$scope.alerts = [];
    		patientService.addPatient(info).then(function(resp){

    			if(resp.data.status){
    				//hide the add patient elements
    				$scope.addPatientShow = false;
    				//show a success notification
    				$scope.alerts.push({
    					type : 'success',
    					timeout : 5,
    					msg : 'Patient has been added successfully'
    				});

    				$scope.patientList.push(resp.data.data[0]);
    				//reinnitialize patient info
    				$scope.newPatientInfo = {};
    			}
    			else{
    				for(x in resp.data.data.err){
    					$scope.alerts.push({
	    					type : 'danger',
	    					timeout : false,
	    					msg : resp.data.data.err[x]
	    				});
    				}
    			}
    		});
    	};
        $scope.editPatient = function(patient){
            let editVals = angular.copy(patient);
            patient.edit = editVals;
            patient.$edit=true;
        };
        $scope.cancelEdit = function(patient){
            delete patient.edit;
            patient.$edit=false;
        }
        $scope.updatePatient = function(patient){
            //empty out the alerts
            $scope.alerts = [];
            var info = {
                patientId : patient.userId,
                emailAddress : patient.edit.emailAddress,
                firstName : patient.edit.firstName,
                lastName : patient.edit.lastName
            };
            patientService.editPatient(info).then(function(resp){

                if(resp.data.status){
                    //hide the add patient elements
                    $scope.addPatientShow = false;
                    //show a success notification
                    $scope.alerts.push({
                        type : 'success',
                        timeout : 5,
                        msg : 'Patient has been Edited successfully'
                    });
                    //delete the edit obj
                    delete patient.edit;
                    
                    
                    for(x in patient){
                        if(resp.data.data[0][x]){
                            patient[x] = resp.data.data[0][x];
                        }
                    }

                    patient.$edit=false;
                    //close editing
                    
                }
                else{
                    for(x in resp.data.data.err){
                        $scope.alerts.push({
                            type : 'danger',
                            timeout : false,
                            msg : resp.data.data.err[x]
                        });
                    }
                }
            });
        };
        $scope.changePassword = function(patient){
            var modalInstance = $uibModal.open({
                templateUrl: templateUrl + 'templates/password.html',
                controller: 'password',
                resolve: {
                    items: function() {
                        return {
                            userId : patient.userId
                        };
                    }
                }
            });
            return modalInstance.result;
        };
        $scope.openDeletePatient = function(patient){
            patient.deletePatientConfirm = true;
        }
        $scope.deletePatient = function(patient){
            $scope.alerts = [];
            patientService.deletePatient(patient.userId).then(function(resp){

                if(resp.data.status){
                    //show a success notification
                    $scope.alerts.push({
                        type : 'info',
                        timeout : 5,
                        msg : 'Patient has been deleted successfully'
                    });
                    //delete the edit obj
                    $scope.patientList.splice($scope.patientList.indexOf(patient),1);
                    patient.deletePatientConfirm=false;
                    //close editing
                    
                }
                else{
                    for(x in resp.data.data.err){
                        $scope.alerts.push({
                            type : 'danger',
                            timeout : false,
                            msg : resp.data.data.err[x]
                        });
                    }
                }
            });
        }
    	patientService.getPatients().then(function(resp){
    		if(resp.data.status){
				$scope.patientList = resp.data.data;
			}
    	});


    }
]
);