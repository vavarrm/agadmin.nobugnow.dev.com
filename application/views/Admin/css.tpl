<link href="/admin/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<link href="/admin/css/style.css" rel='stylesheet' type='text/css' />
<link href="/admin/css/jquery-ui.min.css" rel='stylesheet' type='text/css' />
<link href="/admin/css/font-awesome.min.css" rel='stylesheet' type='text/css' />

<!-- Custom and plugin javascript -->
<link href="/admin/css/custom.css" rel="stylesheet">

<{if $cssArray}>
	<{foreach from=$cssArray item=file}>
		<link rel="stylesheet" href="/admin/css/<{$file}>">
	<{/foreach}>
<{/if}>

