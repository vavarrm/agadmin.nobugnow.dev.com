<!DOCTYPE HTML>
<html class="no-js" ng-app="agApp" style="background:#ffffff">
<head>
	<title><{$website.title}></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<{include file='Admin/css.tpl'}>
	<!-- Custom Theme files -->
	<{include file='Admin/js.tpl'}>
</head>
<body ng-controller="bodyCtrl" style="background:#ffffff">
	<div ng-view></div>
</body>
</html>

