<nav class="navbar-default navbar-static-top" role="navigation">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<h1><a class="navbar-brand" href="/">後台管理</a></h1>         
	</div>
	<div class=" border-bottom">
	<!-- Brand and toggle get grouped for better mobile display -->
	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="drop-men" >
	</div><!-- /.navbar-collapse -->
	<div class="clearfix"></div>
	<div class="navbar-default sidebar" role="navigation">
		<div class="sidebar-nav navbar-collapse">
			<ul class="nav" id="side-menu" ng-init="getMenu()">
				<li ng-repeat="root in menuList" data-root_id="{{root.id}}" class="menulist" ng-class="data.selected == root.id ?'active':''">
					<a href="#" class=" hvr-bounce-to-right"  ng-click="menuClick(root.id) ; $event.preventDefault();">
						<i class="fa  nav_icon" ng-class="root.nodes?'fa-indent':'fa-cog'"></i> 
						<span class="nav-label" ng-bind="root.title"></span>
						<span class="fa arrow" ng-show="root.nodes"></span>
					</a>
					<ul ng-if="root.nodes" class="nav nav-second-level">
						<li ng-repeat="nodes in root.nodes">
							<a ng-if="nodes.type=='menu'" ng-click="navclick(nodes.title,root.router,nodes.router,nodes.id);" href="/admin/renterTemplates#!{{root.router}}{{nodes.router}}" target="iframe_a"  class="hvr-bounce-to-right" ng-show="data.selected == root.id" > 
								<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<i class="fa fa-cog nav_icon "></i>
								<span ng-bind="nodes.title"></span>
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>