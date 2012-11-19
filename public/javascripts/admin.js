$(document).ready(function() {
	/**
	 * Sidebar gnb, lnb menu 
	 */
	$('ul.lnb').hide();
	$('ul.gnb li a.nav_selected').parent().find('ul').slideToggle('slow');
	$('ul.gnb li a.nav_gnb_anchor').click(function() {
		$(this).parent().siblings().find('ul').slideUp('normal');
		$(this).next().slideToggle('normal');
		
		return false;
	}).hover(function() {
		$(this).stop().animate({'padding-right': '25px'}, 200);
	}, function() {
		$(this).stop().animate({'padding-right': '15px'});
	});
	
	/**
	 * Table 
	 */
	// listup style first tr
	$('table.listup tr:first').addClass('top');
	// alternating rows
	$('div.tab_content tbody tr:even').addClass('alt_row');
	
	// check all
	$('input[type=checkbox].check_all').click(function() {
		var check_one = $('input[type=checkbox].check_one');
		if($(this).attr('checked')) {
			check_one.attr('checked', true);
		} else {
			check_one.attr('checked', false);
		}
	});
});