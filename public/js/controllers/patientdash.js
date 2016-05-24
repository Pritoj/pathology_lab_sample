pathlabControllers.controller('patientdash', ['$scope', '$http', '$stateParams', '$uibModal', 'loginService', 'reportService',
    function ($scope, $http, $stateParams, $uibModal, loginService,reportService) {
        $scope.patientInfo = loginService.getUserDetails();
        $scope.reportList = [];
        $scope.apiUrl = apiUrl;
        $scope.openEmail = function(report){
             var modalInstance = $uibModal.open({
                templateUrl: templateUrl + 'templates/email.html',
                controller: 'email',
                resolve: {
                    items: function() {
                        return {
                            reportId : report.reportId
                        };
                    }
                }
            });
            return modalInstance.result;
       };


        reportService.getPatientReports().then(function(resp){
            if(resp.data.status){
                $scope.reportList = resp.data.data;
            }
        });
    }

]
);