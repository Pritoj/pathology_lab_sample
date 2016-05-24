pathlabServices.factory('reportService', ['$http', 'loginService', function($http, loginService) {
    var addReport = function(reportInfo){
        reportInfo.token=loginService.getToken();
        return $http.post(apiUrl + 'reports/reports/addReport', reportInfo);
    }
    var editReport = function(reportInfo){
        reportInfo.token=loginService.getToken();
        return $http.post(apiUrl + 'reports/reports/editReport', reportInfo);
    }

    var getReports = function(reqUserParams) {
        var reqParams = {
            token: loginService.getToken()
        }
        return $http.post(apiUrl + 'reports/reports/getReports', reqParams);
    };
    var getReportDetails = function(reportId) {
        var reqParams = {
            reportId : reportId,
            token: loginService.getToken()
        }
        return $http.post(apiUrl + 'reports/reports/getReportDetails', reqParams);
    };
    var publishReport = function(reportId){
        var reqParams = {
            reportId : reportId,
            token: loginService.getToken(),
            status : 'Published'
        }
        return $http.post(apiUrl + 'reports/reports/editReport', reqParams);
    }
    var deleteReport = function(reportId){
        var reqParams = {
            reportId : reportId,
            token :  loginService.getToken()
        };
        return $http.post(apiUrl+'reports/reports/deleteReport',reqParams);
    }

    /**SPECIFC FOR PATIENTS ONLY**/
    var getPatientReports = function(reqUserParams) {
        var reqParams = {
            token: loginService.getToken()
        }
        return $http.post(apiUrl + 'patientreports/patientreports/getReports', reqParams);
    };
    var getPatientReportDetails = function(reportId) {
        var reqParams = {
            reportId : reportId,
            token: loginService.getToken()
        }
        return $http.post(apiUrl + 'patientreports/patientreports/getReportDetails', reqParams);
    };
    var emailReport = function(reportId,emailAddress){
        var reqParams = {
            reportId : reportId,
            emailAddress : emailAddress,
            token: loginService.getToken()
        }
        return $http.post(apiUrl + 'patientreports/patientreports/emailReport', reqParams);
    }
    
    return {
        addReport : addReport,
        getReports : getReports,
        getReportDetails : getReportDetails,
        publishReport : publishReport,
        getPatientReports : getPatientReports,
        getPatientReportDetails : getPatientReportDetails,
        emailReport : emailReport,
        editReport : editReport,
        deleteReport : deleteReport
    }
}]);