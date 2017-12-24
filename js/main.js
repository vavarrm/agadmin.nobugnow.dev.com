


var templatePath = "template/admin/";

var agApp = angular.module("agApp", ['ngRoute']);

agApp.config(function($routeProvider){
	$routeProvider.when("/",{
		templateUrl: templatePath+"index.html"+"?"+ Math.random(),
		controller: "indexCtrl",
		cache: false,
    }).when("/login/",{
		templateUrl: templatePath+"login.html"+"?"+ Math.random(),
		controller: "loginCtrl",
		cache: false,
	})
});

var indexCtrl = function($scope, $http){
	
}
agApp.controller('indexCtrl',  ['$scope', '$http', indexCtrl]);
var loginCtrl =function($scope, $http){
	
}
agApp.controller('loginCtrl',  ['$scope', '$http', loginCtrl]);

var bodyCtrl = function($scope, apiService){
		
	$scope.init= function()
	{
		// var promise = apiService.getMenu();
		// promise.then
		// (
			// function() { 
				// $scope.listingData = 'd';
			// },
			// function(errorPayload) {
           
			// }
		// )
	}
	
}
agApp.controller('bodyCtrl',  ['$scope', 'apiService', bodyCtrl]);


var apiService = function($http)
{
	return {
		getMenu: function(){
			return $http.get('/Api/test');
		}
    };
}
agApp.factory('apiService', ['$http',apiService]);









