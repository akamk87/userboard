<div id="search_company" class="facebox_popup">
	<h1 class="header">기업 검색</h1>
<?php echo form_open('admin/api/companies', null); ?>
	<?php echo form_input('company_name', null, 'class="text_input small_input"'); ?> 
	<?php echo form_submit('submit', '기업 검색', 'class="button" style="line-height: 19px;"'); ?>
<?php echo form_close(); ?>
	<div id="search_results">
		<ul>
		</ul>
	</div>
</div>

<script type="text/javascript">
	// for readability & adding investor
	$('div#search_results>ul').on('mouseover', 'li', function() {
		$(this).addClass('hover');
	}).on('mouseout', 'li', function() {
		$(this).removeClass('hover');
	}).on('click', 'li a', function() {
		var a = $(this), id = a.attr('href').split('#')[1];
		
		$.getJSON('/admin/api/user', {
			id: id
		}, function(response) {
			if(response) {
				var user = response.data;
				
				$('input[name=user_name]').val(user.name);
				$('input[name=user_serial_number]').val(user.serial_number);
				$('input[name=user_id]').val(user.id);
				
				$.fancybox.close();
			}
		});
		
		return false;
	});
	
	$('div#search_company form').submit(function() {
		var form = $(this);
		var search_results = $('div#search_results>ul');
		
		$.getJSON(form.attr('action'), {
			name: $('input[name=company_name]').val()
		}, function(response) {
			if(response
				&& response.data.length > 0) {
				var results = response.data;
				search_results.empty();
				
				for(x in results) {
					var result = results[x];
					var html = '<li>' +
					'<span>' + result.serial_number + ' ' + result.name + '</span>' +
					'<div class="align_right"><a href="#' + result.id + '" class="button">선택</a></div>' +
					'</li>';
					
					search_results.append(html);
				}
			}
		});
		
		return false;
	});
	
	$('input[name=company_name]').focus();
</script>
