<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.4/angular-route.js"></script>
  
<!-- Mainly scripts -->
<script src="/js/jquery.metisMenu.js"></script>
<script src="/js/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="/js/custom.js"></script>
<script src="/js/screenfull.js"></script>

<script src="/js/main.js?<{$randseed}>"></script>

<{if $jsArray}>
	<{foreach from=$jsArray item=file}>
		<link rel="stylesheet" href="/js/<{$file}>">
	<{/foreach}>
<{/if}>


<div id="dialog" title="">
  <p></p>
</div>
 



        