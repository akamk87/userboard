<!DOCTYPE html>
	<head>
		<?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv');?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="minki">
		<meta name="description" content="">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		
		<title>오픈트레이드</title>
		
		<?php echo link_tag('public/bootstrap/css/bootstrap.min.css') . NL; ?>
		<?php echo link_tag('public/stylesheets/common/gym.css') . NL; ?>
		<?php echo link_tag('public/stylesheets/jquery-ui-1.8.21.custom.css') . NL; ?>
		<?php echo link_tag('public/stylesheets/jquery.fancybox.css') . NL; ?>
		
		<script type="text/javascript" src="/public/javascripts/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="/public/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/public/javascripts/jquery-ui-1.8.21.custom.min.js"></script>
		<script type="text/javascript" src="/public/javascripts/jquery.fancybox.pack.js"></script>
	</head>
	<!-- body s -->
	<body>
		<!-- content s -->
		<?php echo $partial_content . NL; ?>
		<!-- content e -->
	</body>
	<!-- body e -->
</html>
