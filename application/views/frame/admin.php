<?php echo doctype('html5'); ?>
<html>
<head>
	<?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<title>헬스장 회원 관리 시스템</title>
	<?php echo link_tag('public/stylesheets/reset.css') . NL; ?>
	<?php echo link_tag('public/bootstrap/css/bootstrap.min.css') . NL; ?>
	<?php echo link_tag('public/stylesheets/common/layer.css') . NL; ?>
	<?php echo link_tag('public/stylesheets/admin.css') . NL; ?>
	<?php echo link_tag('public/stylesheets/jquery-ui-1.8.21.custom.css') . NL; ?>
	<?php echo link_tag('public/stylesheets/jquery.fancybox.css') . NL; ?>	
	<?php echo link_tag('public/stylesheets/fileuploader.css') . NL; ?>
	<script type="text/javascript" src="/public/javascripts/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="/public/javascripts/jquery-ui-1.8.21.custom.min.js"></script>
	<script type="text/javascript" src="/public/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/public/javascripts/admin.js"></script>
	<script type="text/javascript" src="/public/javascripts/jquery.fancybox.pack.js"></script>
	<script type="text/javascript" src="/public/javascripts/upload.js"></script>
	<script type="text/javascript" src="/public/javascripts/form_validation.js"></script>
	<script type="text/javascript" src="/public/javascripts/front.js"></script>
</head>
<body>
<div id="container">
	<div id="sidebar">
		<div id="sidebar_wrapper">
			<div class="logo">
				<h1>헬스장 회원</h1>
				<h1>관리 시스템</h1>
			</div>
			<div class="profile">
				<a href="/">로그아웃</a>
			</div>
<?php
$page_title = '';
$html = '<ul class="gnb">' . NL;
foreach($this->config->item('admin_menu') as $gnb) {
	$is_this_gnb_selected = false;
	if(isset($gnb['children'])
		&& !empty($gnb['children'])) {
		$lnb_html = '<ul class="lnb">' . NL;
		
		foreach($gnb['children'] as $lnb) {
			$lnb_html .= '<li>' . NL;
			
			// special routing
			if(strpos($lnb['link'], $this->router->fetch_directory() . $this->router->fetch_class()) !== false
				|| strpos($lnb['link'], $this->router->fetch_directory() . str_replace('statistic_', 'statistic/', $this->router->fetch_class())) !== false) {
				$is_this_gnb_selected = true;
				$page_title = $gnb['name'] . ' &gt ' . $lnb['name'];
				$lnb_html .= '<a href="' . $lnb['link'] . '" class="nav_lnb_anchor nav_selected">' . $lnb['name'] . '</a>' . NL;
			} else {
				$lnb_html .= '<a href="' . $lnb['link'] . '" class="nav_lnb_anchor">' . $lnb['name'] . '</a>' . NL;
			}
			
			$lnb_html .= '</li>' . NL;
		}

		$lnb_html .= '</ul>' . NL;
	}
	
	$html .= 
	'<li>' . NL .
		'<a href="' . $gnb['link'] . '" class="nav_gnb_anchor' . ($is_this_gnb_selected ? ' nav_selected' : ' nav_default') . '">' . $gnb['name'] . '</a>' . NL .
		$lnb_html . NL .
	'</li>' . NL;
}
echo $html . '</ul>' . NL;
?>
		</div>		
	</div>
	<div id="content">
		<h2><?php echo $page_title; ?></h2>
		
		<?php echo $partial_content . NL; ?>
	</div>
</div>
<script type="text/javascript">
var otd_flash_error = [
<?php
foreach(get_error() as $error) {
	echo '{type:\'' . $error['type'] . '\', message:\'' . $error['message'] . '\'},';
}
?>
];
</script>
</body>
</html>