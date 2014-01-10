<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico"/>
<title>Edenred Backoffice</title>

<link rel="stylesheet" media="screen" href="/css/reset.css" />
<link rel="stylesheet" media="screen" href="/css/style.css" />
<link rel="stylesheet" media="screen" href="/css/messages.css" />
<link rel="stylesheet" media="screen" href="/css/forms.css" />

<script src="/js/html5.js"></script>
<!-- jquerytools -->
<script src="/js/jquery.tools.min.js"></script>
<!--[if lte IE 9]>
<link rel="stylesheet" media="screen" href="/css/ie.css" />
<script type="text/javascript" src="/js/ie.js"></script>
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" media="screen" href="/css/ie8.css" />
<![endif]-->

<script>
	$(document).ready(function(){
    $("#form").validator({
    	position: 'top',
    	offset: [25, 10],
    	messageClass:'form-error',
    	message: '<div><em/></div>' // em element is the arrow
    });
});
</script>


</head>
<body class="login">
    <div class="login-box">
    	<img src="/img/logo.gif" />
    	<div class="login-box-top">
    		<div class="login-hd">Reset password</div>
    		<p>Forgot your password? No problem, just enter your email here and we'll send you a new one!</p>
    		
    		<?php
    		//show error message
    		if(isset($errorMsg)){
    			?>
			<div class="message error"><?php echo $errorMsg; ?></div>
    			<?php
    		} else if(isset($notificationMsg)){
    			?>
			<div class="message info"><?php echo $notificationMsg; ?></div>
    			<?php
    		}
    		?>  
    		
    		<form id="form"  action="/users/resetPassword" method="post">    		
			<p>
				<label for="username">Email: *</label><br/>
				<input type="text" id="username"  class="full" value="" name="data[User][email]" required="required"/>
			</p>						
			<p>
				<button class="button button-gray" type="submit">Reset</button>
			</p>
		</form>
    		<div id="linebreak"></div>
		<a href="/users/login">Login</a>
    	</div>
	</div>
</body>
</html>