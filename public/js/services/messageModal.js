pathlabServices.factory('messageModal', ['$http', '$uibModal',
    function($http, $uibModal) {
    	var openMessageModal = function(modalHeading,modalContent){
    		var items = {
                modalHeading : modalHeading,
                modalContent : modalContent
            };
            //console.log(items);
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: templateUrl + 'templates/messageModal.html',
                controller: 'messageModalController',
                resolve: {
                    items: function() {
                        return items;
                    }
                }
            });
            return modalInstance;
    	}
    	return {
    		openMessageModal : openMessageModal
    	};
    }]);
//controller for the message modal
pathlabControllers.controller('messageModalController', ['$scope', 'items', '$uibModalInstance',
    function($scope, items, $uibModalInstance) {
    	$scope.modalHeading = items.modalHeading;
    	$scope.modalContent = items.modalContent;
        $scope.ok = function() {
            $uibModalInstance.close();
        }
        $scope.cancel = function() {
            $uibModalInstance.dismiss();
        }
    }
]);