<?php //require_once('http://143.248.179.23:9000/webserverapi/countryimageurl'); ?>
<div id="result">
	<div class="click">click</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('div.click').click(function() {
		alert('adsffdas');
		$.getJSON("143.248.179.23:9000/webserverapi/countryimageurl", {}, function(data) {
			$('#result').html(data);
		});		
	});
});
</script>
