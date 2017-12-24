<!DOCTYPE html>
<html class="no-js" ng-app="agApp">
     <head>
        <title><{$website.title}></title>
        <{include file='Admin/css.tpl'}>
		<{include file='Admin/js.tpl'}>
    </head>
    <body>
		<ng-view></ng-view>
    </body>
</html>