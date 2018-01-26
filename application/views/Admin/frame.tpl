<!DOCTYPE HTML>
<html class="no-js" ng-app="agApp">
<head>
	<title><{$website.title}></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
	<{include file='Admin/css.tpl'}>
	<!-- Custom Theme files -->
	<{include file='Admin/js.tpl'}>
	<script>
		var FRONT_URL ="<{$FRONT_URL}>"; 
		$(function () {
			$('#supported').text('Supported/allowed: ' + !!screenfull.enabled);

			if (!screenfull.enabled) {
				return false;
			}

			$('#toggle').click(function () {
				screenfull.toggle($('#container')[0]);
			});
		});
	</script>
</head>
<body ng-controller="bodyCtrl" ng-init="init()">
	<div id="wrapper">
		<{*nav*}>
		<{include file='Admin/nav.tpl'}>	
		<{*nav*}>
		<div id="page-wrapper" class="gray-bg dashbard-1">
			<div class="content-main">
				<{*banner*}>
				<div class="banner">
					<h2 >
						<a  ng-click="navclick('','','');" href="/admin/renterTemplates#!/home/" target="iframe_a" >Home</a>
						<i   ng-show="data.nodes.router" class="fa fa-angle-right"></i>
						<a  style="color:#999" ng-click="navclick(data.nodes.title,data.root.router,data.nodes.router,data.nodes_id);"   target="iframe_a" href="/admin/renterTemplates#!{{data.root.router}}{{data.nodes.router}}" ng-show="data.nodes" ng-bind="data.nodes.title"></a>
					</h2>
					</h2>
				</div>
				<{*banner*}>
				<iframe    allowtransparency="yes" frameborder="no" src=""  width="100%" height="800px" border="0" name="iframe_a"  ></iframe>
			</div>
			<input type="hidden" name="root_id" ng-model="data.root_id" value="{{data.root_id}}">
			<input type="hidden" name="nodes_id" ng-model="data.nodes_id" value="{{data.nodes_id}}">
			<input type="hidden" name="am_id" ng-model="data.am_id" value="{{data.am_id}}">
			<{include file='Admin/foot.tpl'}>	
		</div>
		<!--scrolling js-->
		<script src="/admin/js/jquery.nicescroll.js"></script>
		<script>
		
		</script>
		<!--//scrolling js-->
	</div>
</body>
</html>

