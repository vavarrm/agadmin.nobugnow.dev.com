


var templatePath = "template/admin/";

var agApp = angular.module("agApp", ['ngRoute']);

agApp.config(function($routeProvider){
	$routeProvider.when("/admin/",{
		templateUrl: templatePath+"index.html"+"?"+ Math.random(),
		controller: "indexCtrl",
		cache: false,
    })
});

var indexCtrl = function(){
	alert('d');
}

agApp.controller('indexCtrl',  ['$scope', '$http', indexCtrl]);









