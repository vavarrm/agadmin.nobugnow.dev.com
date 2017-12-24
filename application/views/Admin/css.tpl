<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<link href="/css/style.css" rel='stylesheet' type='text/css' />
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" rel='stylesheet' type='text/css' />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel='stylesheet' type='text/css' />


<!-- Custom and plugin javascript -->
<link href="/css/custom.css" rel="stylesheet">

<{if $cssArray}>
	<{foreach from=$cssArray item=file}>
		<link rel="stylesheet" href="/css/<{$file}>">
	<{/foreach}>
<{/if}>

