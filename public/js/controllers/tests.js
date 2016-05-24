pathlabControllers.controller('tests', ['$scope', '$http', '$stateParams', 'testService', 'messageModal',
    function($scope, $http, $stateParams, testService, messageModal) {
        //to show alert messages
        $scope.alerts = [];
        //set patient list to false to show 
        //a loader perhaps
        $scope.testList = [];
        $scope.addTest = function(testTypeName) {
            //empty out the alerts
            $scope.alerts = [];
            testService.addTest(testTypeName).then(function(resp) {
                if (resp.data.status) {
                    //hide the add patient elements
                    $scope.addTestShow = false;
                    //show a success notification
                    $scope.alerts.push({
                        type: 'success',
                        timeout: 5,
                        msg: 'Test has been added successfully'
                    });
                    $scope.testList.push(resp.data.data[0]);
                    //reinnitialize patient info
                    $scope.testTypeName = '';
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
        $scope.addTestField = function(test) {
            testService.addTestField({
                testTypeId: test.testTypeId,
                testFieldName: test.testFieldName
            }).then(function(resp) {
                //innitialize the alerts
                test.alerts = [];
                if (resp.data.status) {
                    //show a success notification
                    test.alerts.push({
                        type: 'success',
                        timeout: 5,
                        msg: 'Field has been added to the test successfully'
                    });
                    test.fields.push(resp.data.data[0]);
                    //reinnitialize patient info
                    test.testFieldName = '';
                } else {
                    for (x in resp.data.data.err) {
                        test.alerts.push({
                            type: 'danger',
                            timeout: false,
                            msg: resp.data.data.err[x]
                        });
                    }
                }
            });
        };
        $scope.editTest = function(test) {
            test.editName = test.testTypeName;
            test.$edit = true;
        };
        $scope.updateTest = function(test) {
            //empty out the alerts
            $scope.alerts = [];
            testService.editTest(test.testTypeId, test.editName).then(function(resp) {
                if (resp.data.status) {
                    //hide the add patient elements
                    $scope.addTestShow = false;
                    //show a success notification
                    $scope.alerts.push({
                        type: 'success',
                        timeout: 5,
                        msg: 'Test has been updated successfully'
                    });
                    test.testTypeName = test.editName;
                    delete test.editName;
                    test.$edit = false;
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
        $scope.closeEditTest = function(test) {
            test.$edit = false;
            delete test.editName;
        };
        $scope.deleteTest = function(test) {
            $scope.alerts = [];
            messageModal.openMessageModal('Delete Test', 'are you sure you want to delete this test?').result.then(function(resp) {
                //confirmed.
                //lets delete
                testService.deleteTest(test.testTypeId).then(function(resp) {
                    if (resp.data.status) {
                        //show a success notification
                        $scope.alerts.push({
                            type: 'info',
                            timeout: 5,
                            msg: 'Test has been deleted successfully'
                        });
                        //delete the edit obj
                        $scope.testList.splice($scope.testList.indexOf(test), 1);
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
        $scope.editTestField = function(test,field){
            field.editTestFieldName = field.testFieldName;
            field.$edit = true;
        };
        $scope.closeFieldEdit = function(test,field) {
            field.$edit = false;
            delete field.editTestFieldName;
        };
        $scope.updateTestField = function(test,field) {
            //empty out the alerts
            test.alerts = [];
            testService.editTestField(field.testFieldId,field.editTestFieldName).then(function(resp) {
                if (resp.data.status) {
                    //show a success notification
                    test.alerts.push({
                        type: 'success',
                        timeout: 5,
                        msg: 'Test field has been updated successfully'
                    });
                    field.testFieldName = field.editTestFieldName;
                    field.$edit = false;
                    delete field.editTestFieldName;
                } else {
                    for (x in resp.data.data.err) {
                        test.alerts.push({
                            type: 'danger',
                            timeout: false,
                            msg: resp.data.data.err[x]
                        });
                    }
                }
            });
        };
        $scope.deleteTestField = function(test,field) {
            $scope.alerts = [];
            messageModal.openMessageModal('Delete Test Field', 'Are you sure you want to delete this test field?').result.then(function(resp) {
                //confirmed.
                //lets delete
                testService.deleteTestField(field.testFieldId).then(function(resp) {
                    if (resp.data.status) {
                        //show a success notification
                        $scope.alerts.push({
                            type: 'info',
                            timeout: 5,
                            msg: 'Test field has been deleted successfully'
                        });
                        //delete the edit obj
                        test.fields.splice(test.fields.indexOf(field), 1);
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
        testService.getTests().then(function(resp) {
            if (resp.data.status) {
                $scope.testList = resp.data.data;
            }
        });
    }
]);