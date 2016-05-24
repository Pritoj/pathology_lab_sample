pathlabControllers.controller('email', ['$scope', 'reportService', '$uibModalInstance', 'items',
    function($scope, reportService, $uibModalInstance, items) {
        $scope.emailReport = function(email) {
        	$scope.alerts = [];
            reportService.emailReport(items.reportId, email).then(function(resp) {
                if (resp.data.status) {
                    //hide the add patient elements
                    $scope.email = '';
                    //show a success notification
                    $scope.alerts.push({
                        type: 'success',
                        timeout: 5,
                        msg: 'Email has been sent successfully'
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