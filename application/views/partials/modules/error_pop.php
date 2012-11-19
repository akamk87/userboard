<!--<link rel="stylesheet" type="text/css" href="/public/stylesheets/common/common.css">-->
<!--<link rel="stylesheet" type="text/css" href="/public/stylesheets/common/etc.css">-->
<style>
	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }
	
	.pop_error { width:480px !important; margin:5px 0; }
	h1 { font-size:30px; padding-bottom:10px; border-bottom:2px solid #ddd; margin-bottom:20px; }
	p { font-size:16px; }
</style>

<!-- content s -->
<div class="container body-wrap pop_error">
	<div style="padding:20px 40px;">
		<h1><?php echo $heading; ?></h1>
		<p><?php echo $message; ?></p>
	</div>
</div>
<!-- content e -->