var templatePath = "/template/admin/";
var apiRouter ;
var agApp = angular.module("agApp", ['ngRoute', 'ngCookies']);

agApp.config(function($routeProvider){
	$routeProvider.when("/",{
		templateUrl: templatePath+"index.html"+"?"+ Math.random(),
		controller: "indexCtrl",
		cache: false,
    }).when("/home/",{
		templateUrl: templatePath+"home.html"+"?"+ Math.random(),
		controller: "indexCtrl",
		cache: false,
    }).when("/user/list/",{
		templateUrl: templatePath+"user_list.html"+"?"+ Math.random(),
		controller: "userCtrl",
		cache: false,
	}).when("/user/list/addChildUser/:superior_id",{
		templateUrl: templatePath+"addChildUser.html"+"?"+ Math.random(),
		controller: "userCtrl",
		cache: false,
	}).when("/user/list/addParentUser/",{
		templateUrl: templatePath+"addParentUser.html"+"?"+ Math.random(),
		controller: "userCtrl",
		cache: false,
	}).when("/user/list/setUserPasswd/:u_id",{
		templateUrl: templatePath+"setUserPasswd.html"+"?"+ Math.random(),
		controller: "userCtrl",
		cache: false,
	}).when("/user/list/setMoneyPasswd/:u_id",{
		templateUrl: templatePath+"setMoneyPasswd.html"+"?"+ Math.random(),
		controller: "userCtrl",
		cache: false,
	}).when("/user/list/setParent/:u_id",{
		templateUrl: templatePath+"setParent.html"+"?"+ Math.random(),
		controller: "userCtrl",
		cache: false,
	}).when("/user/list/recharge/:u_id",{
		templateUrl: templatePath+"recharge.html"+"?"+ Math.random(),
		controller: "userCtrl",
		cache: false,
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
		p:1,
		user :{},
		node :{},
		menunode :{}
	};
	$scope.setParentInit = function()
	{
		var obj ={
			u_id :u_id
		};
		$scope.api('/getParentInfo',obj);
	}
	$scope.setUserPasswd = function()
	{
		var u_id = $routeParams.u_id;
		if(
			typeof u_id =="undefined" || 
			typeof $scope.data.passwd =="undefined" || 
			typeof $scope.data.passwd_confirm =="undefined" || 
			$scope.data.passwd =="" ||
			$scope.data.passwd_confirm =="" 
		){

			return false;
		}
		
		if($scope.data.passwd !=$scope.data.passwd_confirm)
		{
			var obj ={
				'message' :'两次输入密码不一致',
			
			};
			dialog(obj);
			return false;
		}
		
		if($scope.data.passwd.length <8 || $scope.data.passwd.length >12)
		{
			var obj ={
				'message' :'密码长度在8~12位',
			
			};
			dialog(obj);
			return false;
		}
		var obj ={
			u_id :u_id
		};
		$scope.api('/setUserPasswd',obj);
	}
	
	$scope.setMoneyPasswd = function()
	{
		var u_id = $routeParams.u_id;
		if(
			typeof u_id =="undefined" || 
			typeof $scope.data.passwd =="undefined" || 
			typeof $scope.data.passwd_confirm =="undefined" || 
			$scope.data.passwd =="" ||
			$scope.data.passwd_confirm =="" 
		){

			return false;
		}
		
		if($scope.data.passwd !=$scope.data.passwd_confirm)
		{
			var obj ={
				'message' :'两次输入密码不一致',
			
			};
			dialog(obj);
			return false;
		}
		
		if($scope.data.passwd.length <8 || $scope.data.passwd.length >12)
		{
			var obj ={
				'message' :'密码长度在8~12位',
			
			};
			dialog(obj);
			return false;
		}
		var obj ={
			u_id :u_id
		};
		$scope.api('/setMoneyPasswd',obj);
	}
	
	$scope.showChild = function(row, u_id)
	{
		var postdata={
			superior_id : u_id
		};
		if(row.show == 'false')
		{
			row.show =true;
		}else{
			// row.show =false;
			row.show ='false';
		}
       
		var promise = apiService.adminApi('/childUserList',postdata);
		promise.then
			(
				function(r) 
				{
					if(r.data.status =="100")
					{
						row.nodes = r.data.body.list;
						// row.show =true;
					}else{
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
	$scope.api = function(router, obj)
	{
		var error = false;
		var postdata={
			am_id :$routeParams.am_id
		};
		if(typeof obj =="object")
		{
			$.extend( postdata, obj );
		}
		
		$.each($('input[required=required]'), function(i, e){
			if($(e).val() =="")
			{
				error = true;
				return false;
			}
			if(typeof $(e).attr('name') !="undefined")
			{
				postdata[$(e).attr('name')] = $(e).val();
			}
		});
		
		$.each($('input[type=text]'), function(i, e){
			if(typeof $(e).attr('name') !="undefined")
			{
				postdata[$(e).attr('name')] = $(e).val();
			}
		});
		
		if(!error)
		{
			
			$.each($('input[type=hidden]'), function(i, e){
				if(typeof $(e).attr('name') !="undefined")
				{
					postdata[$(e).attr('name')] = $(e).val();
				}
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
										location.href="/admin/renterTemplates#!/user/list/";
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
		
		if(typeof $routeParams.u_id !='undefined')
		{
			var obj ={
				u_id : $routeParams.u_id
			};
			var promise = apiService.adminApi('/getUserByID',obj);
			promise.then
			(
				function(r) 
				{
					if(r.data.status =="100")
					{
						$scope.data.user = r.data.body.user;
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
		var nodes_id = $('input[type=hidden][name=nodes_id]', parent.document).val();
		var obj ={
			am_id :nodes_id,
		};
		var promise = apiService.adminApi('/getActionList',obj);
		
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					$scope.data.actions = r.data.body.actionlist;
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
	
	$scope.reset= function()
	{
		$scope.data.u_account='';
		$scope.data.p =1;
		$scope.search();
		
	}
	
	$scope.search = function()
	{
		if(typeof $scope.data.u_account=="undefined")
		{
			$scope.data.u_account='';
		}
		var obj = {
			p :$scope.data.p,
			u_account : $scope.data.u_account
		}
		var promise = apiService.adminApi('/userList', obj);
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
		action:{},
		root :{}
	};
	
	$scope.init = function()
	{
		
	}
	
	$scope.navclick = function(title, root_router, nodes_router ,nodes_id)
	{
		// console.log(nodes_id);
		$scope.data.root.router = root_router;
		$scope.data.nodes.router = nodes_router;
		$scope.data.nodes.title = title;
		$scope.data.nodes_id = nodes_id;
		// $scope.data.nodes.router='s';
	}
	
	$rootScope.$on('setMenuList', function(event, data){	
		$scope.data.selected = data.root_id;
		$scope.data.root_id = data.root_id;
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
		var promise = apiService.adminApi('/getMenu');
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					$scope.menuList =r.data.body.menulist;
					var menulist = $cookies.getObject('menulist');
					$cookies.putObject('menulist', r.data.body.menulist, { path: '/'});
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
		adminApi :function($router, postdata, root_id, nodes_id){
			
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

function resizeIframe(obj) {
	obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}


