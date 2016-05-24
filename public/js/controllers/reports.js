pathlabControllers.controller('reports', ['$scope', '$http', '$stateParams', 'patientService','reportService','messageModal',
    function($scope, $http, $stateParams, patientService,reportService,messageModal) {
        //to show alert messages
        $scope.alerts = [];
        //set patient list to false to show 
        //a loader perhaps
        $scope.patientList = [];
        $scope.reportList = [];

        $scope.addReport = function(newReport) {
            //empty out the alerts
            $scope.alerts = [];
            var reportInfo = {
                patientId : newReport.patient.userId,
                doctorName : newReport.doctorName,
                diagnosis : newReport.diagnosis,
                microscopicExam : newReport.microscopicExam,
                grossExam : newReport.grossExam,
                otherComments : newReport.otherComments,
                status : 'Draft'
            };

            reportService.addReport(reportInfo).then(function(resp) {
                if (resp.data.status) {
                    //hide the add patient elements
                    $scope.addReportShow = false;
                    //show a success notification
                    $scope.alerts.push({
                        type: 'success',
                        timeout: 5,
                        msg: 'Report has been added successfully'
                    });
                    $scope.reportList.push(resp.data.data[0]);
                    //reinnitialize report info
                    $scope.newReport = {};
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

        $scope.publishReport = function(report){
            reportService.publishReport(report.reportId).then(function(resp) {
                if (resp.data.status) {
                    //hide the add patient elements
                    $scope.addReportShow = false;
                    //show a success notification
                    $scope.alerts.push({
                        type: 'success',
                        timeout: 5,
                        msg: 'Report has been published successfully'
                    });
                    report.status = 'Published';
                    //reinnitialize report info
                    $scope.newReport = {};
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

        $scope.editReport = function(report){
            //copy for editing
            report.edit = angular.copy(report);
            //show editor
            report.$edit = true;
        };
        $scope.closeEditReport = function(report){
            //delete the editing object
            delete report.edit;
            //close editor
            report.$edit = false;
        };
        $scope.updateReport = function(report){
            $scope.alerts = [];
            var reportInfo = {
                reportId : report.reportId,
                doctorName : report.edit.doctorName,
                diagnosis : report.edit.diagnosis,
                microscopicExam : report.edit.microscopicExam,
                grossExam : report.edit.grossExam,
                otherComments : report.edit.otherComments,
            };

            reportService.editReport(reportInfo).then(function(resp) {
                if (resp.data.status) {
                    //hide the add patient elements
                    $scope.addReportShow = false;
                    //show a success notification
                    $scope.alerts.push({
                        type: 'success',
                        timeout: 5,
                        msg: 'Report has been edited successfully'
                    });
                    for(x in report){
                        if(resp.data.data[0][x]){
                            report[x] = resp.data.data[0][x];
                        }
                    }
                    delete report.edit;
                    report.$edit = false;
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
        $scope.deleteReport = function(report) {
            $scope.alerts = [];
            messageModal.openMessageModal('Delete Test Report?', 'Are you sure you want to delete this test report?').result.then(function(resp) {
                //confirmed.
                //lets delete
                reportService.deleteReport(report.reportId).then(function(resp) {
                    if (resp.data.status) {
                        //show a success notification
                        $scope.alerts.push({
                            type: 'info',
                            timeout: 5,
                            msg: 'Test report has been deleted successfully'
                        });
                        //delete the edit obj
                        $scope.reportList.splice($scope.reportList.indexOf(report), 1);
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
            },
            function(){
                console.log('cenceled');
            });
        };
        patientService.getPatients().then(function(resp){
            if(resp.data.status){
                $scope.patientList = resp.data.data;
            }
        });
        reportService.getReports().then(function(resp){
            if(resp.data.status){
                $scope.reportList = resp.data.data;
            }
        });
    }
]);