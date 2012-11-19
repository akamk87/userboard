<!DOCTYPE html>
<html>
<head>
	<?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<title>통합보기 :: 오픈트레이드</title>
	<?php echo link_tag('public/stylesheets/front/body.css') . NL; ?>
	<?php echo link_tag('public/stylesheets/common/layer.css') . NL; ?>
	<script type="text/javascript" src="/public/javascripts/jquery-1.7.2.min.js"></script>
</head>
<body>
	<?php echo $partial_content . NL;?>
</body>
</html>