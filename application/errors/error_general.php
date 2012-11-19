<!DOCTYPE html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
		
		<title>ERROR</title>
		
		<link href="/public/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="/public/stylesheets/common/common.css" rel="stylesheet" type="text/css" />
		<link href="/public/stylesheets/common/etc.css" rel="stylesheet" type="text/css" />
		<style>
			::selection{ background-color: #E13300; color: white; }
			::moz-selection{ background-color: #E13300; color: white; }
			::webkit-selection{ background-color: #E13300; color: white; }
		</style>
	</head>
	<!-- body s -->
	<body>
		<!-- content s -->
		<div class="container body-wrap error">
			<div class="main-wrap">
				<h1><?php echo $heading; ?></h1>
				<?php echo $message; ?>
				<font style="font-size:12px; text-align:right; font-style:italic; float:right; padding-bottom:20px;">Apache/2.2.17 Server at gym.opentrade.co.kr Port 80</font>
			</div>
		</div>
		<div class="thingy" style="margin-bottom: 100px;"><img src="/public/images/thingy.png"></div>
		<!-- content e -->
	</body>
	<!-- body e -->
</html>