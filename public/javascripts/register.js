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
	
	// 실명 인증
	$('input#namecheck').click(function() {
		if ($(this).val() == '실명확인') { fnPopup(); }
		else { alert('이미 인증 하셨습니다'); }
	});
	
	// email check
	$('input[name=users\\[email\\]]').blur(function() {
		var email = $(this).val();
		var self = this;
		
		$.post('/intro/register/is_email_taken', {email: email}, function(response) {
			if(response) {
				if(response.result) {
					alert(email + '은(는) 이미 사용중인 이메일입니다.');
					$(self).val('').focus();
				}
			} else {
				alert('현재 이메일 중복체크를 수행할 수 없습니다. 잠시 뒤 다시 시도하여 주십시오.');
				$(self).val('').focus();
			}
		});
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
	        			$('input[name=users_attachment]').val(response.data.attachment_id);
	        			$('img#profile_image').attr('src', '/thumbnail/' + response.data.attachment_id + '?w=64&h=64');
	        		} else {
	        			alert(response.message);
	        		}
	        	}
	        }
		});
	}
	
	// team member add
	$('a.team_team_add').click(function() {
		var skel = $('div.skel:last').html(), $clone;
		skel = skel.replace(/\[[0-9a-zA-Z]{13}\]/g, '[' + uniqid() + ']');
		$clone = $('<div class="team_team">' + skel + '</div>');
		$clone.find('div.controls:first').append('<a class="btn btn-small team_team_delete pull-right">삭제</a>');
		$clone.find('a.team_team_delete').click(function() {
			$(this).closest('div.team_team').remove();
			return false;
		});
		$clone.insertAfter('div.team_team:last').find('input, textarea').val('');
		return false;
	});
	
	// team member delete
	$('a.team_team_delete').click(function() {
		$(self).closest('div.team_team').remove();
		return false;
	});
	
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
	        			$('form.register_form').prepend('<input type="hidden" name="user_metadatas:team_unique_attachments[]" value="' + response.data.attachment_id + '" />');
	        		} else {
	        			alert(response.message);
	        		}
	        	}
	        }
		});
	}
}); 