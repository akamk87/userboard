<div id="search_investor" class="facebox_popup">
	<h1 class="header">투자자 검색</h1>
<?php echo form_open('admin/api/investors', null); ?>
	<?php echo form_input('investor_name', null, 'class="text_input small_input"'); ?> 
	<?php echo form_submit('submit', '투자자 검색', 'class="button" style="line-height: 19px;"'); ?>
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
				var table = $('table#funding_participatings');
				var html = '<tr>' +
				'<td><input type="checkbox" name="funding_participating_checkbox" value="' + user.id + '" /></td>' +
				'<td>' + user.name + '</td>' +
				'<td>' + user.serial_number + '</td>' +
				'<td><input type="text" name="funding_participating_new[' + user.id + '][funding_amount]" class="text_input small_input" />만원</td>' +
				'<td><input type="text" name="funding_participating_new[' + user.id + '][stock_amount]" class="text_input small_input" /> 주</td>' +
				'</tr>';
				
				table.find('tbody').append(html).find('tr').removeClass('alt_row');
				table.find('tbody tr:even').addClass('alt_row');
				
				$.fancybox.close();
			}
		});
		
		return false;
	});
	
	$('div#search_investor form').submit(function() {
		var form = $(this);
		var search_results = $('div#search_results>ul');
		
		$.getJSON(form.attr('action'), {
			name: $('input[name=investor_name]').val()
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
	
	$('input[name=investor_name]').focus();
</script>
