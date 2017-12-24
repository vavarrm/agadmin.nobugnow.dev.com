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
					<h2>
						<a href="index.html">Home</a>
						<i class="fa fa-angle-right"></i>
						<span>Typography</span>
					</h2>
				</div>
				<{*banner*}>
				<!--grid-->
				<div class="typo-grid">
					<div class="typo-1">
						<div class="grid_3 grid_4">
							<div ng-view></div>
						</div>
					</div>
				</div>
			</div>
			<{include file='Admin/foot.tpl'}>	
		</div>
		<!--scrolling js-->
		<script src="/js/jquery.nicescroll.js"></script>
		<script src="/js/scripts.js"></script>
		<!--//scrolling js-->
	</div>
</body>
</html>

