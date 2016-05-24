pathlabControllers.controller('password', ['$scope', 'patientService', '$uibModalInstance', 'items',
    function($scope, patientService, $uibModalInstance, items) {
        console.log(items);
        $scope.editPatientPassword = function(password) {
            var patientInfo = {
                userId : items.userId,
                password : password
            };
            console.log(patientInfo);
        	$scope.alerts = [];
            patientService.editPatientPassword(patientInfo).then(function(resp) {
                if (resp.data.status) {
                    //hide the add patient elements
                    $scope.password = '';
                    //show a success notification
                    $scope.alerts.push({
                        type: 'success',
                        timeout: 5,
                        msg: 'Password has been updated successfully'
                    });
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
        }
        $scope.cancel = function() {
            $uibModalInstance.close();
        }
    }
]);