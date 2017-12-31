var templatePath = "/template/admin/";
var apiRouter ;
var agApp = angular.module("agApp", ['ngRoute', 'ngCookies']);

agApp.config(function($routeProvider){
	$routeProvider.when("/",{
		templateUrl: templatePath+"index.html"+"?"+ Math.random(),
		controller: "indexCtrl",
		cache: false,
    }).when("/user/list/:root_id/:nodes_id/",{
		templateUrl: templatePath+"user_list.html"+"?"+ Math.random(),
		controller: "userCtrl",
		cache: false,
	}).when("/addChildUser/:root_id/:nodes_id/:am_id/:superior_id",{
		templateUrl: templatePath+"addChildUser.html"+"?"+ Math.random(),
		controller: "userCtrl",
		cache: false,
	}).when("/addParentUser/:root_id/:nodes_id/:am_id/:superior_id",{
		templateUrl: templatePath+"addParentUser.html"+"?"+ Math.random(),
		controller: "userCtrl",
		cache: false,
	}).otherwise({
        redirectTo: '/'
    })
});

var indexCtrl = function($scope, $http, $rootScope){
	var obj =
	{
		root_id:0,
		nodes_id:'',
		nodes_router:'',
		nodes_title:'' ,
		action_title:'' ,
		am_id:'' ,
		action_router:'' 
	};
	$rootScope.$broadcast('setMenuList', obj);
}
agApp.controller('indexCtrl',  ['$scope', '$http' ,'$rootScope', indexCtrl]);


var userCtrl = function($scope, $http, apiService, $cookies, $routeParams, $rootScope){

	$scope.data ={
		root_id:$routeParams.root_id,
		menulist: $cookies.getObject('menulist'),
		actions:{},
		action_title:'',
		action_router:'',
		superior_id:$routeParams.superior_id,
		p:1
	};
	$scope.api = function(router)
	{
		var error = false;
		var postdata={};
		$.each($('input[required=required]'), function(i, e){
			if($(e).val() =="")
			{
				error = true;
				return false;
			}
			postdata[$(e).attr('name')] = $(e).val();
		});
		if(!error)
		{
			
			$.each($('input[type=hidden]'), function(i, e){
				postdata[$(e).attr('name')] = $(e).val();
			})
			
			var promise = apiService.adminApi(router, postdata);
			promise.then
			(
				function(r) 
				{
					if(r.data.status =="100")
					{
						var obj =
						{
							'message' :r.data.message,
							buttons: 
							[
								{
									text: "close",
									click: function() 
									{
										$( this ).dialog( "close" );
										location.href="/#!/user/list/"+$routeParams.root_id+"/"+$routeParams.nodes_id;
									}
								}
							]
						};
						dialog(obj);
					}else
					{
						var obj =
						{
							'message' :r.data.message,
							buttons: 
							[
								{
									text: "close",
									click: function() 
									{
										$( this ).dialog( "close" );
									}
								}
							]
						};
						dialog(obj);
					}
				},
				function() {
					var obj ={
						'message' :'系統錯誤'
					};
					 dialog(obj);
				}
			)
		}
	}
	
	$scope.clickpage = function(p)
	{
		if(p>$scope.pageinfo.pages || p<1)
		{
			return ;
		}
		$scope.data.p = p;
		$scope.search();
	}
	
	$scope.init = function()
	{
		$scope.search();
		var actions = findObjectByKey($scope.data.menulist, 'id', $routeParams.root_id);
		actions = findObjectByKey(actions.nodes, 'id', $routeParams.nodes_id);	
		var nodes_title = actions.title;
		$scope.data.actions = actions.nodes;
		var obj =
		{
			root_id:$routeParams.root_id,
			nodes_id:$routeParams.nodes_id,
			nodes_title:nodes_title ,
			nodes_router:actions.router,
			action_title:'' ,
			am_id:'' ,
			router:'' 
		};
		if(typeof $routeParams.am_id !="undefined")
		{
			var action = findObjectByKey($scope.data.actions, 'id', $routeParams.am_id);
			obj.action_title = action.title;
			obj.am_id = $routeParams.am_id;
			obj.router = action.router;
			$scope.data.action_title = action.title;
			$scope.data.action_router =  action.router;
		}
		$rootScope.$broadcast('setMenuList', obj);
	
	}
	
	$scope.search = function()
	{
		var obj = {
			p :$scope.data.p
		}
		var promise = apiService.userList(obj);
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					$scope.list = r.data.body.list;
					$scope.pageinfo = r.data.body.pageinfo;
				}else
				{
					var obj =
					{
						'message' :r.data.message,
						buttons: 
						[
							{
								text: "close",
								click: function() 
								{
									$( this ).dialog( "close" );
								}
							}
						]
					};
					dialog(obj);
				}
			},
			function() {
				var obj ={
					'message' :'系統錯誤'
				};
				 dialog(obj);
			}
		)
	}
}
agApp.controller('userCtrl',  ['$scope', '$http' ,'apiService', '$cookies', '$routeParams', '$rootScope', userCtrl]);

var bodyCtrl = function($scope, apiService, $cookies, $rootScope, $routeParams){
	$scope.data =
	{
		selected 	:0,
		nodes	:{},
		action:{}
	};
	
	$scope.navclick = function()
	{
		location.href="/#!/";
	}
	
	$rootScope.$on('setMenuList', function(event, data){	
		$scope.data.selected = data.root_id;
		$scope.data.nodes.title = data.nodes_title;
		$scope.data.nodes.id = data.nodes_id;
		$scope.data.nodes.router = data.nodes_router;
		$scope.data.action.title = data.action_title;
		$('.banner h2').removeClass('hide');
    })

	$scope.menuClick = function(id)
	{
		if(id == $scope.data.selected)
		{
			$scope.data.selected = 0;
		}else
		{
			$scope.data.selected=id;
		}
	}
	
	$scope.getMenu = function()
	{
		var promise = apiService.getMenu();
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					$scope.menuList =r.data.body.menulist;
					var menulist = $cookies.getObject('menulist');
					if(typeof menulist =="undefined")
					{
						$cookies.putObject('menulist', r.data.body.menulist, { path: '/'});
					}	
				}else
				{
					var obj =
					{
						'message' :r.data.message,
						buttons: 
						[
							{
								text: "close",
								click: function() 
								{
									$( this ).dialog( "close" );
								}
							}
						]
					};
					dialog(obj);
				}
			},
			function() {
				var obj ={
					'message' :'系統錯誤'
				};
				 dialog(obj);
			}
		)
	}
}
agApp.controller('bodyCtrl',  ['$scope','apiService' , '$cookies', '$rootScope' ,'$routeParams', bodyCtrl]);


var apiService = function($http, $cookies)
{
	var sess = $cookies.get('sess');
	return {
		getMenu: function(){
			return $http.get('/Api/getMenu?sess='+sess);
		},
		userList :function(postdata){
			return $http.post('/Api/userList?sess='+sess, postdata,  {headers: {'Content-Type': 'application/json'}});
		},
		adminApi :function($router, postdata){
			
			return $http.post('/Api'+$router+'?sess='+sess, postdata,  {headers: {'Content-Type': 'application/json'} });
		}
    };
}
agApp.factory('apiService', ['$http', '$cookies',apiService]);

agApp.filter('range', function() {
  return function(input, total) {
    total = parseInt(total);

    for (var i=0; i<total; i++) {
      input.push(i);
    }

    return input;
  };
});

function findObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return array[i];
        }
    }
    return null;
}


function dialog(object2)
{
	if(typeof object2 !="object")
	{
		object2 ={};
	}
	var  object1 ={
		message:"",
		title:"系統提示訊息",
		buttons: [
			{
				text: "close",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	};
	$.extend( object1, object2 );
	$( "#dialog").attr('title', object1.title); 
	$( "#dialog p").text(object1.message); 
	$( "#dialog" ).dialog(object1);
}




