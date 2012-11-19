$(document).ready(function() {	
	// fancyboxing
	$.fancybox.defaults.afterShow = function() {
		if($('div.fancybox-inner').css('overflow') == 'auto') {
			$('div.fancybox-inner').css('overflow-x', 'hidden');
		}
	}
	$('a.fb').fancybox();
	
	function get_unique_id() {
		return (new Date()).getTime();
	}
	
	// team member add
	$('a.team_team_add').click(function() {
		var skel = $('div.team_team_skel:last').html();

		skel = skel.replace(/\[new:[0-9]+\]/g, '[new:' + get_unique_id() + ']');
		$('<div class="team_team_skel">' + skel + '</div>').insertAfter('div.team_team_skel:last').find('input, textarea').val('');
		
		return false;
	});
	
	// team member delete
	$('a.team_team_delete').click(function() {
		var self = this;
		$.post($(this).attr('href'), {}, function(response) {
			if(response) {
				if(response.result) {
					$(self).closest('div.team_team').remove();
				} else {
					alert(response.message);
				}				
			} else {
				alert('삭제에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
			}
		});
		
		return false;
	});
	
	// profile picture upload
	if($('div#profile_upload').length > 0) {
		var uploader = new qq.FileUploader({
			element: $('div#profile_upload')[0],
			action: '/upload',
			multiple: false,
			allowedExtensions: ['jpg', 'png', 'gif', 'bmp'],
			debug: true,
	        messages: {
	            typeError: "{file} 은(는) 적절한 파일형식이 아닙니다. {extensions} 파일형식만 가능합니다.",
	            sizeError: "{file} 의 사이즈가 너무 큽니다. 최대 업로드 가능한 사이즈는 {sizeLimit} 입니다.",
	            minSizeError: "{file} 의 사이즈가 너무 작습니다. 최소 업로드 사이즈는 {minSizeLimit} 입니다..",
	            emptyError: "{file} 빈파일입니다.",
	            onLeave: "업로드 중입니다. 현재 페이지를 나가면 업로드가 취소됩니다."            
	        },
	        onComplete: function(id, file_name, response){
	        	if(response) {
	        		if(response.result) {
	        			$('form#setting').prepend('<input type="hidden" name="attachments[]" value="' + response.data.attachment_id + '" />');
	        			$('img#profile_image').attr('src', '/thumbnail/' + response.data.attachment_id + '?w=64&h=64');
	        		} else {
	        			alert(response.message);
	        		}
	        	}
	        }
		});
	}
	
	// file upload
	if($('div#file_upload').length > 0) {
		var uploader2 = new qq.FileUploader({
			element: $('div#file_upload')[0],
			action: '/upload',
			multiple: false,
			debug: true,
	        messages: {
	            typeError: "{file} 은(는) 적절한 파일형식이 아닙니다. {extensions} 파일형식만 가능합니다.",
	            sizeError: "{file} 의 사이즈가 너무 큽니다. 최대 업로드 가능한 사이즈는 {sizeLimit} 입니다.",
	            minSizeError: "{file} 의 사이즈가 너무 작습니다. 최소 업로드 사이즈는 {minSizeLimit} 입니다..",
	            emptyError: "{file} 빈파일입니다.",
	            onLeave: "업로드 중입니다. 현재 페이지를 나가면 업로드가 취소됩니다."            
	        },
	        onComplete: function(id, file_name, response){
	        	if(response) {
	        		if(response.result) {
	        			$('form#setting').prepend('<input type="hidden" name="attachments[]" value="' + response.data.attachment_id + '" />');
	        		} else {
	        			alert(response.message);
	        		}
	        	}
	        }
		});
	}
	
	// attachment delete
	$('a.attachment_delete').click(function() {
		var self = this;
		$.post($(this).attr('href'), {}, function(response) {
			if(response) {
				if(response.result) {
					$(self).closest('li').remove();
				} else {
					alert(response.message);
				}
			} else {
				alert('삭제에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
			}
		});
		
		return false;
	});
	
	// form cancel
	$('input#form_cancel').click(function () {
		location.reload();
	});
}); 