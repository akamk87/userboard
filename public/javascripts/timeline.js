/*
<li class="clearfix">
	<a href="" class="profile pull-left"><img src="/public/images/opentrade.png"></a>
	<div class="inner-wrap">
		<div class="meta">
			<a href=""><i class="icon icon-bullhorn"></i>오픈트레이드</a> · <span class="date">2012.7.10</span>
		</div>
		<p><b>위자드웍스</b>에서 <b>Seed Funding</b>이 시작 되었습니다. <b>액면가 5,000원</b>의 <b>2배수</b>로 <b>3,000 만원</b>을 <b>9월 20일</b>까지 모집 합니다. 많은 관심과 참여 부탁드립니다.</p>
	</div>
</li>
*/

var timeline = function(o) {
	this.options = {
		stream_key: null,
		page: 1,
		template: {
			open:
				'<li class="clearfix">' +
					'{profile_image}' +
					'<div class="inner-wrap">' +
						'<div class="meta">' +
							'{writer_name}{owner_name}  · <span class="date">{created_at}</span>' +
							' {delete_link}' +
						'</div>' +
						'<p>{message}</p>' +
						'{attachments}' +
					'</div>' + 
				'</li>',
			message: 
				'<li class="clearfix" style="background-color:#f1f1f1;">' +
					'{profile_image}' +
					'<div class="inner-wrap">' +
						'<div class="meta">' +
							'{writer_name}{owner_name}  · <span class="date">{created_at}</span>' +
						'</div>' + 
						'<p><b>[메세지]</b> {message}</p>' +
						'{attachments}' +
					'</div>' + 
				'</li>',
			admin_message:
				'<li class="clearfix" style="background-color:#f1f1f1;">' +
					'{profile_image}' +
					'<div class="inner-wrap">' +
						'<div class="meta">' +
							'{writer_name}{owner_name}  · <span class="date">{created_at}</span>' +
						'</div>' +
						'<div id="apply_link">{apply_icon}</div>' + 
						'<p><b>[관리자메세지]</b> {message}</p>' +
						'{attachments}' +
					'</div>' + 
				'</li>',
			system_message:
				'<li class="clearfix" style="background-color:#f1f1f1;">' +
					'{profile_image}' +
					'<div class="inner-wrap">' +
						'<div class="meta">' +
							'{writer_name}{owner_name}  · <span class="date">{created_at}</span>' +
						'</div>' +
						'<div id="apply_link">{apply_icon}</div>' + 
						'<p><b>[시스템메세지]</b> {message}</p>' +
						'{attachments}' +
					'</div>' + 
				'</li>',
			notice:
				'<li class="clearfix" style="background-color:#f1f1f1;">' +
					'{profile_image}' +
					'<div class="inner-wrap">' +
						'<div class="meta"><i class="icon icon-bullhorn"></i>' +
							'{writer_name}{owner_name}  · <span class="date">{created_at}</span>' +
						'</div>' +
						'<p><b>[공지사항]</b> {message}</p>' +
						'{attachments}' +
						'<div class="apply_link">{apply_icon}</div>' + 
					'</div>' + 
				'</li>'
		},
		container: $('div.timeline ul')
	};
	
	$.extend(this.options, o || {});
}

timeline.prototype = {
	get_timelines: function() {
		var self = this;
		if(this.options.page == 0) return false;
		
		$.getJSON('/timeline/api/streams/' + this.options.stream_key + '?' + Math.random(), {
			p: this.options.page
		}, function(response) {
			if(response
				&& response.length > 0) {
				self.options.page++;
				self.render(response);
			} else {
				self.options.page = 0;
				self.options.container.append('<li class="none-result"><p>게시물이 없습니다</p></li>');
			}
		});
	},
	get_relative_time: function(time_string) {
		var parsed_date = Date.parse(time_string);
		
		if(isNaN(parsed_date)) return time_string;
		
		var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
		var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
		
		if (delta < 60) {
			return '1분 이내';
		} else if(delta < 120) {
			return '약 1분 전';
		} else if(delta < (60*60)) {
			return (parseInt(delta / 60)).toString() + '분 전';
		} else if(delta < (120*60)) {
			return '약 한 시간 전';
		} else if(delta < (24*60*60)) {
			return '약 ' + (parseInt(delta / 3600)).toString() + ' 시간 전';
		} else if(delta < (48*60*60)) {
			return '어제';
		} else {
			return (parseInt(delta / 86400)).toString() + ' 일 전';
		}
	},
	render: function(timelines) {
		var self = this;
		var ratio = 0.8;
		var beautified_height = Math.round($(window).height() * ratio);
		var max_width = 500;
		
		for(i in timelines) {
			var timeline = timelines[i];
			var template = this.options.template[timeline.privacy.toLowerCase()];
			var profile_image, writer_name, owner_name, message, attachments, apply_icon, delete_link;
			
			if (timeline.writer.type_code_key == 'admin') {
				// image
				template = template.replace('{profile_image}', '<a class="profile pull-left"><img src="/public/images/opentrade.png"></a>');
			} else {
				// image
				if(timeline.writer.attachment) {
					profile_image = '/thumbnail/' + timeline.writer.attachment.id + '?w=64&h=64'
				} else {
					profile_image = '/public/images/avatar.png';
				}
				template = template.replace('{profile_image}', '<a href="/timeline/' + timeline.writer.type_code_key + '/' + timeline.writer.serial_number + '" class="profile pull-left"><img src="' + profile_image + '" alt=""></a>');
			}
			
			// owner, writer
			if(timeline.writer.id == timeline.owner.id) {
				if (timeline.writer.type_code_key == 'admin') writer_name = '<a class="nolink">' + timeline.writer.name + '</a>';
				else writer_name = '<a href="/timeline/' + timeline.writer.type_code_key + '/' + timeline.writer.serial_number + '">' + timeline.writer.name + '</a>';
				owner_name = '';
			} else {
				if (timeline.writer.type_code_key == 'admin') writer_name = '<a class="nolink">' + timeline.writer.name + '</a>';
				else writer_name = '<a href="/timeline/' + timeline.writer.type_code_key + '/' + timeline.writer.serial_number + '">' + timeline.writer.name + '</a>';
				owner_name = ' > <a href="/timeline/' + timeline.owner.type_code_key + '/' + timeline.owner.serial_number + '">' + timeline.owner.name + '</a>';
			}
			template = template.replace('{writer_name}', writer_name);
			template = template.replace('{owner_name}', owner_name);
			
			// message
			template = template.replace('{message}', timeline.message);
			
			// created at
			var d = new Date(timeline.created_at), created_at;
			if(!isNaN(d)) {
				if(parseInt(((new Date()).getTime() - d.getTime()) / 1000) < 86400) {
					created_at = ' (' + d.getFullYear() + '.' + (d.getMonth()+1) + '.' + d.getDate() + ' ' + d.getHours() + ':' + d.getMinutes() +')';
				} else {
					created_at = ' (' + d.getFullYear() + '.' + (d.getMonth()+1) + '.' + d.getDate() + ')';
				}
			} else {
				created_at = '';
			}
			template = template.replace('{created_at}', '<span style="color: #555;">' + this.get_relative_time(timeline.created_at) + '</span>' + created_at);
			
			// delete own timeline
			if(timeline.owned) {
				if(timeline.writer.id == timeline.owner.id) {
					delete_link = '<a href="/timeline/delete/' + timeline.id +'" class="delete_link"><i class="icon-trash"></i></a>';
				} else {
					delete_link = '';
				}
			} else {
				delete_link = '';
			}
			template = template.replace('{delete_link}', delete_link);
			
			// attachments
			if(timeline.attachments.length > 0) {
				attachments = '<div class="pic">';
				for(j in timeline.attachments) {
					var attachment = timeline.attachments[j];
					if(attachment.is_image == 'Y') {
						attachments += '<a href="/thumbnail/' + attachment.id + '?h=' + beautified_height + '&ratio=y&orig_name=' + attachment.orig_name + '" class="fb" rel="photostream-' + timeline.id + '"><img src="/thumbnail/' + attachment.id + '?w=' + max_width + '&ratio=y" style="margin:5px;" alt="' + attachment.orig_name + '" /></a>';
					} else {
						attachments += '<div style="clear:both; word-wrap: break-word; overflow: hidden;"><a href="/download/' + attachment.id + '" class="btn"><i class="icon icon-download"></i> ' + attachment.orig_name + '</a></div>';
					}
				}
				attachments += '</div>';
			} else {
				attachments = '';
			}
			template = template.replace('{attachments}', attachments);
				
			// apply icon
			if($.trim(timeline.apply_icon) != '') {
				apply_icon = '<a href="' + timeline.apply_icon + '" target="_blank" class="btn btn-warning"><i class="icon icon-hand-up"></i> ' + timeline.apply_icon + '</a>';
			} else {
				apply_icon = '';
			}
			template = template.replace('{apply_icon}', apply_icon);
			
			// fancyboxing
			$(template).find('a.fb').fancybox();
			
			// append to container
			self.options.container.append(template);
		}
	}
}

/*
 *  newsfeed
 */
var newsfeed = function(o) {
	this.options = {
		stream_key: null,
		page: 1,
		template: {
			open:
				'<li class="clearfix">' +
					'{profile_image}' +
					'<div class="inner-wrap">' +
						'<div class="meta">' +
							'{writer_name}{owner_name}  · <span class="date">{created_at}</span>' +
						'</div>' +
						//'<div id="timeline_icon">{apply_icon}</div>' + 
						'<p>{message}</p>' +
						'{attachments}' +
					'</div>' + 
				'</li>'
		},
		container: $('div.newsfeed ul')
	};
	
	$.extend(this.options, o || {});
}

newsfeed.prototype = {
	get_newsfeeds: function() {
		var self = this;
		if(this.options.page == 0) return false;
		
		$.getJSON('/timeline/api/newsfeeds/' + this.options.stream_key + '?' + Math.random(), {
			p: this.options.page
		}, function(response) {
			if(response
				&& response.length > 0) {
				self.options.page++;
				self.render(response);
			} else {
				self.options.page = 0;
				self.options.container.append('<li class="none-result"><p>뉴스피드가 없습니다</p></li>');
			}
		});
	},
	get_relative_time: function(time_string) {
		var parsed_date = Date.parse(time_string);
		
		if(isNaN(parsed_date)) return time_string;
		
		var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
		var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
		
		if (delta < 60) {
			return '1분 이내';
		} else if(delta < 120) {
			return '약 1분 전';
		} else if(delta < (60*60)) {
			return (parseInt(delta / 60)).toString() + '분 전';
		} else if(delta < (120*60)) {
			return '약 한 시간 전';
		} else if(delta < (24*60*60)) {
			return '약 ' + (parseInt(delta / 3600)).toString() + ' 시간 전';
		} else if(delta < (48*60*60)) {
			return '어제';
		} else {
			return (parseInt(delta / 86400)).toString() + ' 일 전';
		}
	},
	render: function(newsfeeds) {
		var self = this;
		var ratio = 0.8;
		var beautified_height = Math.round($(window).height() * ratio);		
		var max_width = 500;
		
		for(i in newsfeeds) {
			var newsfeed = newsfeeds[i];
			var template = this.options.template[newsfeed.privacy.toLowerCase()];
			var profile_image, writer_name, owner_name, message, attachments, apply_icon;
			
			if (newsfeed.writer.type_code_key == 'admin') {
				// image
				template = template.replace('{profile_image}', '<a class="profile pull-left"><img src="/public/images/opentrade.png"></a>');
			} else {
				// image
				if(newsfeed.writer.attachment) {
					profile_image = '/thumbnail/' + newsfeed.writer.attachment.id + '?w=64&h=64'
				} else {
					profile_image = '/public/images/avatar.png';
				}
				template = template.replace('{profile_image}', '<a href="/timeline/' + newsfeed.writer.type_code_key + '/' + newsfeed.writer.serial_number + '" class="profile pull-left"><img src="' + profile_image + '" alt=""></a>');
			}
			
			// owner, writer
			if(newsfeed.writer.id == newsfeed.owner.id) {
				writer_name = '<a href="/timeline/' + newsfeed.writer.type_code_key + '/' + newsfeed.writer.serial_number + '">' + newsfeed.writer.name + '</a>';
				owner_name = '';
			} else {
				if (newsfeed.writer.type_code_key == 'admin') writer_name = '<a class="nolink">' + newsfeed.writer.name + '</a>';
				else writer_name = '<a href="/timeline/' + newsfeed.writer.type_code_key + '/' + newsfeed.writer.serial_number + '">' + newsfeed.writer.name + '</a>';
				owner_name = ' > <a href="/timeline/' + newsfeed.owner.type_code_key + '/' + newsfeed.owner.serial_number + '">' + newsfeed.owner.name + '</a>';
			}
			template = template.replace('{writer_name}', writer_name);
			template = template.replace('{owner_name}', owner_name);
			
			// message
			template = template.replace('{message}', newsfeed.message);
			
			// created at
			var d = new Date(newsfeed.created_at), created_at;
			if(!isNaN(d)) {
				if(parseInt(((new Date()).getTime() - d.getTime()) / 1000) < 86400) {
					created_at = ' (' + d.getFullYear() + '.' + (d.getMonth()+1) + '.' + d.getDate() + ' ' + d.getHours() + ':' + d.getMinutes() +')';
				} else {
					created_at = ' (' + d.getFullYear() + '.' + (d.getMonth()+1) + '.' + d.getDate() + ')';
				}
			} else {
				created_at = '';
			}
			template = template.replace('{created_at}', '<span style="color: #555;">' + this.get_relative_time(newsfeed.created_at) + '</span>' + created_at);
			
			// attachments
			if(newsfeed.attachments.length > 0) {
				attachments = '<div class="pic">';
				for(j in newsfeed.attachments) {
					var attachment = newsfeed.attachments[j];
					if(attachment.is_image == 'Y') {
						attachments += '<a href="/thumbnail/' + attachment.id + '?h=' + beautified_height + '&ratio=y&orig_name=' + attachment.orig_name + '" class="fb" rel="photostream-' + newsfeed.id + '"><img src="/thumbnail/' + attachment.id + '?w=' + max_width + '&ratio=y" style="margin:5px;" alt="' + attachment.orig_name + '" /></a>';
					} else {
						attachments += '<div style="clear:both; word-wrap: break-word; overflow: hidden;"><a href="/download/' + attachment.id + '" class="btn"><i class="icon icon-download"></i> ' + attachment.orig_name + '</a></div>';
					}
				}
				attachments += '</div>';
			} else {
				attachments = '';
			}
			template = template.replace('{attachments}', attachments);
			
			// fancyboxing
			$(template).find('a.fb').fancybox();
			
			// append to container
			self.options.container.append(template);
		}
	}
}

$(document).ready(function() {
	// get timelines
	var tl = new timeline({stream_key: stream_key});
	tl.get_timelines();
	
/*	// scroll bottom event
	var w = $(window), d = $(document);
	w.scroll(function() {
		if(d.height() == w.height() + d.scrollTop()) {
			tl.get_timelines();
		}
	});
*/	
	
	// timeline more
	$('div.timeline > div.more')
	.click(function() {
		tl.get_timelines();
	})
	.ajaxStart(function() {
		$(this).children().html('<img src="/public/images/ajax-loader.gif">');
	})
	.ajaxStop(function() {
		$(this).children().text('더 불러오기');
		if ($('li.none-result').size() != 0) {
			$('div.more').hide();
		}
	});
	
	// newsfeed
	var nf = new newsfeed({stream_key: stream_key});
	nf.get_newsfeeds();
	
	// newsfeed more
	$('div.newsfeed > div.more')
	.click(function() {
		nf.get_newsfeeds();
	})
	.ajaxStart(function() {
		$(this).children().html('<img src="/public/images/ajax-loader.gif">');
	})
	.ajaxStop(function() {
		$(this).children().text('더 불러오기')
		if ($('li.none-result').size() != 0) {
			$('div.more').hide();
		}
	});
	
	// fancyboxing
	$.fancybox.defaults.afterShow = function() {
		if($('div.fancybox-inner').css('overflow') == 'auto') {
			$('div.fancybox-inner').css('overflow-x', 'hidden');
		}
	}
	$('a.fb').fancybox({width: 700, height: 420});
	
	// set timeline
	$('form.timeline_post').submit(function() {
		if($.trim($('textarea[name=message]').val()) != '') {
			$.post($(this).attr('action'), $(this).serialize(), function(response) {
				if(response
					&& response.result) {
					window.location.reload();
				} else {
					alert(response.message);
				}
			});
		} else {
			alert('메세지를 입력해주십시오.');
		}
		
		return false;
	});
	
	$('a.timeline_write').click(function() {
		$('form.timeline_post').submit();
	});
	
	// add favorite
	$('a#add_favorite').click(function() {
		$.getJSON($(this).attr('href'), {}, function(response) {
			if(response) {
				if(response.result) {
					alert(response.message);
					window.location.reload();
				} else {
					alert(response.message);
				}
			} else {
				alert('관심등록에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
			}
		});
		return false;
	});
	
	// upload
	if($('div#file_upload').length > 0) {
		var uploader = new qq.FileUploader({
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
	        			$('form.timeline_post').prepend('<input type="hidden" name="attachments[]" value="' + response.data.attachment_id + '" />');
	        		} else {
	        			alert(response.message);
	        		}
	        	}
	        }
		});
	}
})
.ajaxStop(function() {
	$('a.delete_link').click(function() {
		if(!confirm('정말 삭제하시겠습니까?')) {
			return false;
		}
		$.getJSON($(this).attr('href'), {}, function(response) {
			if(response) {
				if(response.result) {
					window.location.reload();
				} else {
					alert(response.message);
					window.location.reload();
				}
			} else {
				alert('타임라인 삭제에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
			}
		});
		return false;
	});
});