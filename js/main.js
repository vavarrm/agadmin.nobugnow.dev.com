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
	}).when("/account/auditList/",{
		templateUrl: templatePath+"auditList.html"+"?"+ Math.random(),
		controller: "accountCtrl",
		cache: false,
	}).when("/system/addAdmin/",{
		templateUrl: templatePath+"addAdmin.html"+"?"+ Math.random(),
		controller: "adminCtrl",
		cache: false,
	}).when("/system/adminList/",{
		templateUrl: templatePath+"adminList.html"+"?"+ Math.random(),
		controller: "adminCtrl",
		cache: false,
	})
	.when("/user/announcemetList/",{
		templateUrl: templatePath+"announcemetList.html"+"?"+ Math.random(),
		controller: "userCtrl",
		cache: false,
	})
	.when("/user/announcemetList/add/",{
		templateUrl: templatePath+"announcemetAddForm.html"+"?"+ Math.random(),
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
		menunode :{},
		options :{},
		u_id:'',
		balance :0,
		remarks :'',
		router:''
	};
	
	$scope.addAnnouncemetClick = function(router)
	{
		if(typeof $scope.data.title =="undefined")
		{
			return false;
		}
		
		if(typeof $scope.data.content =="undefined")
		{
			return false;
		}
		
		var obj ={
			title : $scope.data.title,
			content :$scope.data.content
		}
		var promise = apiService.adminApi(router, obj);
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					var obj =
					{
						'message' :'新增成功',
						buttons: 
						[
							{
								text: "close",
								click: function() 
								{
									$( this ).dialog( "close" );
									location.href="/admin/renterTemplates#!/user/announcemetList/";
								}
							}
						]
					};
					dialog(obj);
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
	
	$scope.announcemetSearch = function()
	{
		var obj = {};
		var promise = apiService.adminApi('/getAnnouncemetList', obj);
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					$scope.data.list = r.data.body.list;
					$scope.data.actions = r.data.body.actions;
					$scope.pageinfo =r.data.body.pageinfo; 
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
	
	$scope.announcemetListinit = function()
	{
		$scope.search();
		$( ".datepicker" ).datepicker({ dateFormat:'yy-mm-dd' });
	}
	
	$scope.addBalance = function()
	{
		
		var obj ={
			u_id :$scope.data.u_id,
			u_balance :$scope.data.balance,
			uat_id :$scope.data.uat_id,
			ua_remarks :$scope.data.remarks,
			
		};
		var promise = apiService.adminApi('/addBalance', obj);
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					var obj =
					{
						'message' :'系統充值成功，请审核',
						buttons: 
						[
							{
								text: "close",
								click: function() 
								{
									$( this ).dialog( "close" );
									location.href="/admin/renterTemplates#!/account/auditList/";
								}
							}
						]
					};
					dialog(obj);
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
	$scope.rechargenit = function()
	{
		$scope.data.u_id= $routeParams.u_id;
		var obj = {
			u_id :$scope.data.u_id
		};
		var promise = apiService.adminApi('/getRechargenitInTypeList', obj);
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					$scope.data.options = r.data.body.options;
					$scope.data.user = r.data.body.user;
					console.log($scope.data.user);
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
	
	$scope.clickpage = function(p, fun)
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
		if(typeof $scope.data.u_account=="undefined")
		{
			$scope.data.u_account='';
		}
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
	}
	
	$scope.reset= function()
	{
		if(typeof $scope.data.search !="undefined")
		{
			$.each($scope.data.search,function(i,e){
				$scope.data.search[i] = '';
			})
		}
		$scope.data.p =1;
		$scope.search();
		
	}
	
	$scope.search = function()
	{
		var obj = {
			p :$scope.data.p,
		}
		if(typeof $scope.data.search !="undefined")
		{
			$.each($scope.data.search,function(i,e){
				obj[i] = e;
			})
		}
		if(typeof $scope.data.search !="undefined" && typeof $scope.data.search.start_time !="undefined" && typeof $scope.data.search.end_time !="undefined")
		{
			var obj =
			{
				'message' :'起始时间要小于结束时间',
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
			return false;
		}
		var promise = apiService.adminApi($scope.data.router, obj);
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					$scope.data.list = r.data.body.list;
					$scope.pageinfo = r.data.body.pageinfo;
					$scope.data.actions = r.data.body.actions;
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

var accountCtrl = function($scope, $http, apiService, $cookies, $routeParams, $rootScope)
{
	$scope.data=
	{
		list :{},
		p:1
	};
	
	$scope.reset= function()
	{
		$scope.data.end_time='';
		$scope.data.start_time='';
		$scope.data.p =1;
		$scope.search();
		
	}
	
	$scope.auditListInit = function()
	{
		$scope.search();
		$( ".datepicker" ).datepicker({ dateFormat:'yy-mm-dd' });
	}
	
	$scope.clickpage = function(p)
	{
		if(p>$scope.data.pages || p<1)
		{
			return ;
		}
		$scope.data.p = p;
		$scope.search();
	}
	
	$scope.click = function(router)
	{
		if($('.checkbox_u_id:checked').length == 0)
		{
			var obj =
				{
					'message' :'请选择要审核项目',
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
			return false;
		}
		
		var ua_id = new Array();
		$.each($('.checkbox_u_id:checked'), function(i, e){
			ua_id.push($(e).val())
		})
		var postobj ={
			ua_id : ua_id
		};
		
		var obj =
		{
			message :'确认审核',
			buttons: 
			[
				{
					text: "是",
					click: function() 
					{
						var promise = apiService.adminApi(router, postobj);
						promise.then
						(
							function(r) 
							{
								if(r.data.status =="100")
								{
									var obj =
									{
										message :'审核成功',
										buttons: 
										[
											{
												text: "close",
												click: function() 
												{
													$( this ).dialog( "close" );
													window.location.reload();
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
				},
				{
					text: "否",
					click: function() 
					{
						$( this ).dialog( "close" );
					}
				}
			]
		};
		dialog(obj);
		
		
		
	}
	
	$scope.search = function()
	{
		
		var nodes_id = $('input[type=hidden][name=nodes_id]', parent.document).val();
		if($scope.data.end_time < $scope.data.start_time)
		{
			var obj =
			{
				'message' :'起始时间要大于结束时间',
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
			return false;
		}
		var obj ={
			nodes_id :nodes_id,
			p	:$scope.data.p,
			start_time : $scope.data.start_time,
			end_time : $scope.data.end_time
		};
		var promise = apiService.adminApi('/auditList', obj);
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					$scope.data.list = r.data.body.list;
					$scope.data.pages = r.data.body.pageinfo.pages;
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
agApp.controller('accountCtrl',  ['$scope', '$http' ,'apiService', '$cookies', '$routeParams', '$rootScope', accountCtrl]);

var adminCtrl = function($scope, $http, apiService, $cookies, $routeParams, $rootScope)
{
	$scope.data = {
		p :1
	};
	
	$scope.reset = function()
	{
		$scope.data.p =1;
		$scope.data.s_account ='';
		$scope.search();
	}
	
	$scope.clickpage = function(p)
	{
		if(p>$scope.data.pages || p<1)
		{
			return ;
		}
		$scope.data.p = p;
		$scope.search();
	}
	
	$scope.searchClick = function()
	{
		$scope.data.p =1;
		$scope.search();
	}
	
	$scope.search = function()
	{
		
		var obj ={
			account: $scope.data.s_account,
			p :$scope.data.p
		};
		var promise = apiService.adminApi('/adminList', obj);
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					$scope.data.list = r.data.body.list;
					$scope.data.pages = r.data.body.pageinfo.pages;
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
	
	$scope.addAdminClick = function(router)
	{
		if(
			typeof $scope.data.account =="undefined"||
			typeof $scope.data.passwd =="undefined"||
			typeof $scope.data.passwd_confirm =="undefined"||
			typeof $scope.data.role =="undefined"
		){
			return false;
		}
		
		if( $scope.data.passwd !=$scope.data.passwd_confirm )
		{
			var obj =
				{
					'message' :'两次输入密码不一致',
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
			return false;
		}
		var obj ={
			account :$scope.data.account,
			passwd :$scope.data.passwd,
			passwd_confirm :$scope.data.passwd_confirm,
			role :$scope.data.role
		};
		var promise = apiService.adminApi(router,obj);
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					var obj =
					{
						'message' :'新增成功',
						buttons: 
						[
							{
								text: "close",
								click: function() 
								{
									$( this ).dialog( "close" );
									location.href='/admin/renterTemplates#!/system/adminList/';
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
	
	$scope.heard_btn = function(id)
	{
		var am_id = $('input[name=am_id]', parent.document).val(id);
	}
	
	$scope.adminListInit = function()
	{
		$scope.search();
		var promise = apiService.adminApi('/getActionList');
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
	
	$scope.addAdminInit = function()
	{
		var promise = apiService.adminApi('/getAdminRoleList');
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					$scope.data.options = r.data.body.list;
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
agApp.controller('adminCtrl',  ['$scope', '$http' ,'apiService', '$cookies', '$routeParams', '$rootScope', adminCtrl]);

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
		$scope.data.am_id = nodes_id;
		$('input[name=am_id]').val(nodes_id);
		// console.log($scope.data.am_id);
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
		// console.log(id);
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
									location.href="/Login";
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
			var am_id = $('input[name=am_id]', parent.document).val();
			var default_obj = {
				am_id :am_id
			};
			var object = $.extend(postdata, default_obj);
			return $http.post('/Api'+$router+'?sess='+sess, object ,  {headers: {'Content-Type': 'application/json'} });
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


