<!DOCTYPE HTML>
<html>
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
<body>
	<div class="login">
		<h1>後台管理中心</h1>
		<div class="login-bottom">
			<h2>登入</h2>
			<div class="col-md-12">
				<div class="login-mail">
					<input type="text" name="account" placeholder="請輸入帳號" required="">
					<i class="fa fa fa-user"></i>
				</div>
				<div class="login-mail">
					<input type="password" name="passwd" placeholder="請輸入密碼" required="">
					<i class="fa fa-lock"></i>
				</div>
				<div class="login-do">
					<label class="hvr-shutter-in-horizontal login-sub">
						<input type="submit" id="submit-bit" value="Submit">
					</label>
				</div>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
	<{include file='Admin/foot.tpl'}>
	<!--scrolling js-->
	<script src="/js/jquery.nicescroll.js"></script>
	<script src="/js/scripts.js"></script>
	<script>
		$('#submit-bit').bind('click', function(){
			var account = $('input[name=account]').val();
			var passwd = $('input[name=passwd]').val();
			
			if(account =="")
			{
				var obj = {
					message:'請輸入帳號',
					buttons: [
						{
							text: "close",
							click: function() 
							{
								$( this ).dialog( "close" );
								$('input[name=account]').focus();
							}
						}
					]
				};
				dialog(obj);
				return false;
			}
			
			if(passwd =="")
			{
				var obj = {
					message:'請輸入密碼',
					buttons: [
						{
							text: "close",
							click: function() 
							{
								$( this ).dialog( "close" );
								$('input[name=passwd]').focus();
							}
						}
					]
				};
				dialog(obj);
				return false;
			}
			
			 $.ajax({
				type: "POST",
				url: '/Api/login',
				dataType: 'json',
				async: false,
				//json object to sent to the authentication url
				data: JSON.stringify({ "account": account , "passwd" : passwd }),
				contentType: 'application/json',
				success: function (request) {
					console.log(request); 
				}
			})
		})
	</script>
	<!--//scrolling js-->
</body>
</html>

