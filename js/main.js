var templatePath = "/admin/template/";
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
	}).when("/user/list/rechargeForm/:u_id",{
		templateUrl: templatePath+"rechargeForm.html"+"?"+ Math.random(),
		controller: "userCtrl",
		cache: false,
	}).when("/account/rechargeAuditList/",{
		templateUrl: templatePath+"rechargeAuditList.html"+"?"+ Math.random(),
		controller: "accountCtrl",
		cache: false,
	}).when("/account/withdrawalAuditList/",{
		templateUrl: templatePath+"withdrawalAuditList.html"+"?"+ Math.random(),
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
	}).when("/user/announcemetList/edit/:an_id",{
		templateUrl: templatePath+"announcemeEditForm.html"+"?"+ Math.random(),
		controller: "userCtrl",
		cache: false,
	}).when("/account/withdrawalRecordList/",{
		templateUrl: templatePath+"withdrawalRecordList.html"+"?"+ Math.random(),
		controller: "accountCtrl",
		cache: false,
	}).when("/website/bigBannerList",{
		templateUrl: templatePath+"bigBannerList.html"+"?"+ Math.random(),
		controller: "websiteCtrl",
		cache: false,
	}).when("/website/addBigBanner",{
		templateUrl: templatePath+"addBigBanner.html"+"?"+ Math.random(),
		controller: "websiteCtrl",
		cache: false,
	}).when("/website/editBigBanner/:bb_id",{
		templateUrl: templatePath+"editBigBanner.html"+"?"+ Math.random(),
		controller: "websiteCtrl",
		cache: false,
	}).when("/website/editFooter/",{
		templateUrl: templatePath+"editFooter.html"+"?"+ Math.random(),
		controller: "websiteCtrl",
		cache: false,
	}).when("/admin/menuList/",{
		templateUrl: templatePath+"menuList.html"+"?"+ Math.random(),
		controller: "websiteCtrl",
		cache: false,
	})
});
var websiteCtrl = function($scope, $http, apiService, $cookies, $routeParams, $rootScope)
{
	$( ".datepicker" ).datepicker({ dateFormat:'yy-mm-dd' });
	$scope.data={
		actions:{},
		from_post :{},
		posturl :'',
		list:{},
		order:{},
		p:1,
		row:{}
	}
	
	$scope.editFooterInit = function()
	{
		var obj ={};
		var promise = apiService.adminApi('/editFooterInit', obj);
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					$scope.data.row =r.data.body.list;
					$scope.data.front_image_url =FRONT_URL+'images/webconfig/';
					$scope.data.am_id =$('input[name=am_id]', parent.document).val();
					$scope.data.posturl ='/Admin/Api/editFooter?sess='+$cookies.get('sess');
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
	
	$scope.actionClick = function($event,func)
	{
		if( func !=null)
		{
			$event.preventDefault();
			if(typeof $scope[func] =='function')
			{
				$scope[func]();
			}
		}
		
	}
	
	$scope.deleteBanner = function()
	{
		if($('.checkbox:checked').length =='0')
		{
			var obj =
				{
					'message' :'请选则要删除项',
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
	
		var obj =
		{
			'message' :'确认删除',
			buttons: 
			[
				{
					text: "是",
					click: function() 
					{
						$( this ).dialog( "close" );
						var bb_id = new Array();
						$.each($('.checkbox:checked'), function(i, e){
							bb_id.push($(e).val())
						})
						var obj={
							bb_id :bb_id
						}
						var promise = apiService.adminApi('/delBigBanner', obj);
						promise.then
						(
							function(r) 
							{
								if(r.data.status =="100")
								{
									var obj =
									{
										'message' :'刪除成功',
										buttons: 
										[
											{
												text: "close",
												click: function() 
												{
													$( this ).dialog( "close" );
													$scope.search();
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
	
	$scope.editBigBannerInit = function()
	{
		$scope.data.front_image_url =FRONT_URL+'images/big_banner/';
		var obj={
			bb_id : $routeParams.bb_id
		};
		var promise = apiService.adminApi('/editBigBalanceInit', obj);
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					$scope.data.row =r.data.body.row;
					$scope.data.am_id =$('input[name=am_id]', parent.document).val();
					$scope.data.posturl ='/Admin/Api/editBigBalance?sess='+$cookies.get('sess');
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
	
	$scope.orderClick = function(order_key)
	{

		if( typeof $scope.data.order[order_key] =='undefined')
		{
			$scope.data.order[order_key] ='DESC';
		}else if($scope.data.order[order_key] =='DESC'){
			$scope.data.order[order_key] ='ASC';
		}else
		{
			$scope.data.order[order_key] ='DESC';
		}
		$scope.data.p=1;
		$scope.search();
	}
	
	$scope.addBigBannerInit = function()
	{
		$scope.data.am_id =$('input[name=am_id]', parent.document).val();
		$scope.data.posturl ='/Admin/Api/addBigBalance?sess='+$cookies.get('sess');
	}
	
	$scope.bigBannerListInit = function()
	{
		$scope.search();
		var obj={};
		var promise = apiService.adminApi('/getActionList', obj);
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
	
		
	$scope.search = function()
	{
		var obj ={
			p	:$scope.data.p,
			order:$scope.data.order
		};
		
		if(typeof $scope.data.search !="undefined")
		{
			$.each($scope.data.search,function(i,e){
				obj[i] = e;
			})
		}
		
		if(typeof $scope.data.search !="undefined" && typeof $scope.data.search.start_time !="undefined" && typeof $scope.data.search.end_time !="undefined")
		{
			if($scope.data.search.start_time > $scope.data.search.end_time )
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
agApp.controller('websiteCtrl',  ['$scope', '$http' ,'apiService', '$cookies', '$routeParams', '$rootScope', websiteCtrl]);

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
		router:'',
		order:{}
	};
	
	
	$scope.search_click = function()
	{
		$scope.data.p= 1;
		$scope.search();
	}
	
	$scope.orderClick = function(order_key)
	{

		if( typeof $scope.data.order[order_key] =='undefined')
		{
			$scope.data.order[order_key] ='DESC';
		}else if($scope.data.order[order_key] =='DESC'){
			$scope.data.order[order_key] ='ASC';
		}else
		{
			$scope.data.order[order_key] ='DESC';
		}
		$scope.data.p=1;
		$scope.search();
	}
	
	$scope.addAnnouncemetInit = function()
	{
		$scope.data.am_id =$('input[name=am_id]', parent.document).val();
		$scope.data.posturl="/Admin/Api/addAnnouncemet?sess="+$cookies.get('sess');
	}
	
	$scope.setMoneyPasswdInit = function()
	{
		var obj={
			u_id:$routeParams.u_id
		};
		var promise = apiService.adminApi('/setMoneyPasswdForm', obj);
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					$scope.data.user = r.data.body.user;
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
	
	$scope.setUserPasswdInit = function()
	{
		var obj={
			u_id:$routeParams.u_id
		};
		var promise = apiService.adminApi('/setUserPasswdForm', obj);
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					$scope.data.user = r.data.body.user;
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
	
	$scope.buttonClick = function(action)
	{
		$('input[name=am_id]', parent.document).val(action.id);
	}
	
	$scope.editAnnouncemetClick = function(router)
	{
		var obj ={
			an_id :	$routeParams.an_id,
			an_title :	$scope.data.row.title,
			an_content :	$scope.data.row.content
			
		};
		var promise = apiService.adminApi(router, obj);
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					var obj =
					{
						'message' :'更新成功',
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
	
	$scope.announcemeEditFormInit = function()
	{
		$scope.data.am_id =$('input[name=am_id]', parent.document).val();
		$scope.data.posturl="/Admin/Api/editAnnouncemet?sess="+$cookies.get('sess');
		$scope.data.front_image_url =FRONT_URL+'images/announcemet/';
		$scope.data.an_id = $routeParams.an_id;
		var obj ={
			an_id :	$scope.data.an_id
		};
		var promise = apiService.adminApi('/getAnnouncemet', obj);
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					$scope.data.row = r.data.body.row;

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
	
	$scope.deleteAnnouncemet = function()
	{
		if($('.checkbox:checked').length =='0')
		{
			var obj =
				{
					'message' :'请选则要删除项',
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
		
			var obj =
			{
				'message' :'确认删除',
				buttons: 
				[
					{
						text: "是",
						click: function() 
						{
							$( this ).dialog( "close" );
							var an_id = new Array();
							$.each($('.checkbox:checked'), function(i, e){
								an_id.push($(e).val())
							})
							var obj={
								an_id :an_id
							}
							var promise = apiService.adminApi('/delAnnouncemet', obj);
							promise.then
							(
								function(r) 
								{
									if(r.data.status =="100")
									{
										var obj =
										{
											'message' :'刪除成功',
											buttons: 
											[
												{
													text: "close",
													click: function() 
													{
														$( this ).dialog( "close" );
														$scope.search();
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
	
	$scope.actionClick = function($event,func)
	{
		if( func !=null)
		{
			$event.preventDefault();
			if(typeof $scope[func] =='function')
			{
				$scope[func]();
			}
		}
		
	}
	
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
									$('input[name=am_id]', parent.document).val('19');
									location.href="/admin/renterTemplates#!/account/rechargeAuditList/";
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
	$scope.rechargeInit = function()
	{
		$scope.data.u_id= $routeParams.u_id;
		var obj = {
			u_id :$scope.data.u_id
		};
		var promise = apiService.adminApi('/rechargeForm', obj);
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
		$scope.api('/doSetUserPasswd',obj);
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
									$('input[name=am_id]', parent.document).val('2');
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
		$('input[name=am_id]', parent.document).val('2');
		if(typeof $scope.data.u_account=="undefined")
		{
			$scope.data.u_account='';
		}
		$scope.search();
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
		var obj ={
			p	:$scope.data.p,
			order:$scope.data.order
		};
		
		if(typeof $scope.data.search !="undefined")
		{
			$.each($scope.data.search,function(i,e){
				obj[i] = e;
			})
		}
		if(typeof $scope.data.search !="undefined" && typeof $scope.data.search.start_time !="undefined" && typeof $scope.data.search.end_time !="undefined")
		{
			if($scope.data.search.start_time > $scope.data.search.end_time )
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
		p:1,
		search:{},
		search_api:'',
		order:{}
	};
	
	$scope.orderClick = function(order_key)
	{
		if( typeof $scope.data.order[order_key] =='undefined')
		{
			$scope.data.order[order_key] ='DESC';
		}else if($scope.data.order[order_key] =='DESC'){
			$scope.data.order[order_key] ='ASC';
		}else
		{
			$scope.data.order[order_key] ='DESC';
		}
		$scope.search();
	}
	
	$scope.withdrawalRecordListInit = function()
	{
		$( ".datepicker" ).datepicker({ dateFormat:'yy-mm-dd' });
		$scope.search();
	}
	
	$scope.withdrawalAuditClick=function(action,ua_status,ua_id)
	{
		var obj =
		{
			'message' :'确认审核',
			buttons: 
			[
				{
					text: "是",
					click: function() 
					{
						$('input[name=am_id]', parent.document).val(action.id);
						var obj ={
							ua_status :ua_status,
							ua_id :ua_id
						};
						var promise = apiService.adminApi(action.router, obj);
						$( this ).dialog( "close" );
						promise.then
						(
							function(r) 
							{
								if(r.data.status =="100")
								{
									$('input[name=am_id]', parent.document).val('19');
									var obj =
									{
										'message' :'成功更新',
										buttons: 
										[
											{
												text: "close",
												click: function() 
												{
													$( this ).dialog( "close" );
													location.reload() ;
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

	
	$scope.withdrawalAuditListInit = function()
	{
		$( ".datepicker" ).datepicker({ dateFormat:'yy-mm-dd' });
		$scope.search();
	}
	
	$scope.reset= function()
	{
		$scope.data.p =1;
		$.each($scope.data.order,function(i,e){
			$scope.data.order[i] ="";
		})
		$.each($scope.data.search,function(i,e){
			$scope.data.search[i] ="";
		})
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
	
	
	
	$scope.checkRechargeAuditClick= function(action, ua_status, ua_id)
	{
		var postobj ={
			ua_id : ua_id,
			ua_status: ua_status
		};
		$('input[name=am_id]', parent.document).val(action.id);
		var obj =
		{
			message :'确认审核',
			buttons: 
			[
				{
					text: "是",
					click: function() 
					{
						var promise = apiService.adminApi(action.router, postobj);
						promise.then
						(
							function(r) 
							{
								if(r.data.status =="100")
								{
									$('input[name=am_id]', parent.document).val('19');
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
		
		if($scope.data.search.end_time < $scope.data.search.start_time)
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
			p	:$scope.data.p,
			order:$scope.data.order
		};
		
		$.each($scope.data.search,function(i,e){
			obj[i] =e;
		})
		
		var promise = apiService.adminApi($scope.data.search_api, obj);
		promise.then
		(
			function(r) 
			{
				if(r.data.status =="100")
				{
					$scope.data.list = r.data.body.list;
					$scope.data.pages = r.data.body.pageinfo.pages;
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
			var object = $.extend(default_obj, postdata);
			return $http.post('/Admin/Api'+$router+'?sess='+sess, object ,  {headers: {'Content-Type': 'application/json'} });
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


