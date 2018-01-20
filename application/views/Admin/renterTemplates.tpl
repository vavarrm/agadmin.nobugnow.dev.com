<!DOCTYPE HTML>
<html class="no-js" ng-app="agApp">
<head>
	<title><{$website.title}></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<!-- IE可能不見得有效 -->
	<META HTTP-EQUIV="EXPIRES" CONTENT="0">
	<!-- 設定成馬上就過期 -->
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">	
	<{include file='Admin/css.tpl'}>
	<!-- Custom Theme files -->
	<{include file='Admin/js.tpl'}>
</head>
<body >
	<div class="typo-grid">
		<div class="typo-1">
			<div ng-view></div>
		</div>
	</div>
	<div class="modal"><!-- Place at bottom of page --></div>
</body>
<script>
	var FRONT_URL ="<{$FRONT_URL}>"; 
	$body = $("body");
	$(document).on({
		ajaxStart: function() { $body.addClass("loading");    },
		ajaxStop: function() { $body.removeClass("loading"); }    
	});
</script>
</html>



