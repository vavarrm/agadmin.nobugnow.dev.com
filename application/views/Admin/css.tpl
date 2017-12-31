<link href="/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<link href="/css/style.css" rel='stylesheet' type='text/css' />
<link href="/css/jquery-ui.min.css" rel='stylesheet' type='text/css' />
<link href="/css/font-awesome.min.css" rel='stylesheet' type='text/css' />

<!-- Custom and plugin javascript -->
<link href="/css/custom.css" rel="stylesheet">

<{if $cssArray}>
	<{foreach from=$cssArray item=file}>
		<link rel="stylesheet" href="/css/<{$file}>">
	<{/foreach}>
<{/if}>

