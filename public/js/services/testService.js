pathlabServices.factory('testService', ['$http', 'loginService', function($http, loginService) {
    var addTest = function(testTypeName) {
        var reqParams = {
            testTypeName: testTypeName,
            token: loginService.getToken()
        };
        return $http.post(apiUrl + 'tests/tests/addTest', reqParams);
    }
    var getTests = function(reqUserParams) {
        var reqParams = {
            token: loginService.getToken()
        }
        return $http.post(apiUrl + 'tests/tests/getTests', reqParams);
    };
    var addTestField = function(reqUserParams){
    	var reqParams = {
            token: loginService.getToken(),
            testTypeId : reqUserParams.testTypeId,
            testFieldName : reqUserParams.testFieldName
        }
        return $http.post(apiUrl + 'tests/tests/addTestField', reqParams);
    }
    var addTestResult = function(reportId,values){
        var reqParams = {
            token: loginService.getToken(),
            reportId : reportId,
            values : values
        }
        return $http.post(apiUrl + 'tests/tests/addTestResult', reqParams);
    }
    var editTest = function (testTypeId,testTypeName){
        var reqParams = {
            token: loginService.getToken(),
            testTypeId : testTypeId,
            testTypeName : testTypeName
        }
        return $http.post(apiUrl + 'tests/tests/editTest', reqParams);
    }
    var editTestField = function (testFieldId,testFieldName){
        var reqParams = {
            token: loginService.getToken(),
            testFieldId : testFieldId,
            testFieldName : testFieldName
        }
        return $http.post(apiUrl + 'tests/tests/editTestField', reqParams);
    }
    var deleteTest = function(testTypeId){
        var reqParams = {
            token: loginService.getToken(),
            testTypeId : testTypeId,
        }
        return $http.post(apiUrl + 'tests/tests/deleteTest', reqParams);
    }
    var deleteTestField = function(testFieldId){
        var reqParams = {
            token: loginService.getToken(),
            testFieldId : testFieldId,
        }
        return $http.post(apiUrl + 'tests/tests/deleteTestField', reqParams);
    }
    return {
        addTest : addTest,
        getTests : getTests,
        addTestField : addTestField,
        addTestResult : addTestResult,
        editTest : editTest,
        deleteTest : deleteTest,
        editTestField : editTestField,
        deleteTestField : deleteTestField

    }
}]);