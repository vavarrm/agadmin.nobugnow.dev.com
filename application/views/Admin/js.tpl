<script src="/js/jquery/1.9.1/jquery.min.js"></script>
<script src="/js/jquery.cookie.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>

<script type="text/javascript" src="/js/angular/angular.min.js"></script>
<script type="text/javascript" src="/js/angular/angular-route.min.js"></script>
<script type="text/javascript" src="/js/angular/angular-cookies.min.js"></script>


  
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
 
<div id="dialog_frame" class="hidden" >
    <iframe frameborder="0" src="" allowfullscreen width="560"></iframe>
</div>

