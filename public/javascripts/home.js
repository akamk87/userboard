var recent_timeline = function(o) {
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
				'</li>',
			notice:
				'<li class="clearfix" style="background-color:#f1f1f1;">' +
					'{profile_image}' +
					'<div class="inner-wrap">' +
						'<div class="meta"><i class="icon icon-bullhorn"></i>' +
							'{writer_name}{owner_name}  · <span class="date">{created_at}</span>' +
						'</div>' +
						'<p><b>[공지]</b> {message}</p>' +
						'<div class="timeline_icon">{apply_icon}</div>' + 
						'<div class="pic">{attachments}</div>' +
					'</div>' + 
				'</li>'
			},
		container: $('div.recents ul')
	};
	
	$.extend(this.options, o || {});
}

recent_timeline.prototype = {
	get_recent_timelines: function() {
		var self = this;
		if(this.options.page == 0) return false;
		
		$.getJSON('/timeline/api/recent_timelines/' + this.options.stream_key + '?' + Math.random(), {
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
	render: function(recent_timelines) {
		var self = this;
		var ratio = 0.8;
		var beautified_height = Math.round($(window).height() * ratio);
		
		for(i in recent_timelines) {
			var recent_timeline = recent_timelines[i];
			var template = this.options.template[recent_timeline.privacy.toLowerCase()];
			var profile_image, writer_name, owner_name, message, attachments, apply_icon;
			
			if (recent_timeline.writer.type_code_key == 'admin') {
				// image
				template = template.replace('{profile_image}', '<a class="profile pull-left"><img src="/public/images/opentrade.png"></a>');
			} else {
				// image
				if(recent_timeline.writer.attachment) {
					profile_image = '/thumbnail/' + recent_timeline.writer.attachment.id + '?w=64&h=64'
				} else {
					profile_image = '/public/images/avatar.png';
				}
				template = template.replace('{profile_image}', '<a href="/timeline/' + recent_timeline.writer.type_code_key + '/' + recent_timeline.writer.serial_number + '" class="profile pull-left"><img src="' + profile_image + '" alt=""></a>');
			}
			
			// owner, writer
			if(recent_timeline.writer.id == recent_timeline.owner.id) {
				if (recent_timeline.writer.type_code_key == 'admin') writer_name = '<a class="nolink">' + recent_timeline.writer.name + '</a>';
				else writer_name = '<a href="/timeline/' + recent_timeline.writer.type_code_key + '/' + recent_timeline.writer.serial_number + '">' + recent_timeline.writer.name + '</a>';
				owner_name = '';
			} else {
				if (recent_timeline.writer.type_code_key == 'admin') writer_name = '<a class="nolink">' + recent_timeline.writer.name + '</a>';
				else writer_name = '<a href="/timeline/' + recent_timeline.writer.type_code_key + '/' + recent_timeline.writer.serial_number + '">' + recent_timeline.writer.name + '</a>';
				owner_name = ' > <a href="/timeline/' + recent_timeline.owner.type_code_key + '/' + recent_timeline.owner.serial_number + '">' + recent_timeline.owner.name + '</a>';
			}
			template = template.replace('{writer_name}', writer_name);
			template = template.replace('{owner_name}', owner_name);
			
			// message
			template = template.replace('{message}', recent_timeline.message);
			
			// created at
			var d = new Date(recent_timeline.created_at), created_at;
			if(!isNaN(d)) {
				if(parseInt(((new Date()).getTime() - d.getTime()) / 1000) < 86400) {
					created_at = ' (' + d.getFullYear() + '.' + (d.getMonth()+1) + '.' + d.getDate() + ' ' + d.getHours() + ':' + d.getMinutes() +')';
				} else {
					created_at = ' (' + d.getFullYear() + '.' + (d.getMonth()+1) + '.' + d.getDate() + ')';
				}
			} else {
				created_at = '';
			}
			template = template.replace('{created_at}', '<span style="color: #555;">' + this.get_relative_time(recent_timeline.created_at) + '</span>' + created_at);
			
			// attachments
			if(recent_timeline.attachments.length > 0) {
				attachments = '<div class="pic" style="clear:both; margin:0px;">';
				for(j in recent_timeline.attachments) {
					var attachment = recent_timeline.attachments[j];
					//alert(attachment.orig_name);
					if(attachment.is_image == 'Y') {
						attachments += '<a href="/thumbnail/' + attachment.id + '?h=' + beautified_height + '&ratio=y&orig_name=' + attachment.orig_name + '" class="fb" rel="photostream-' + recent_timeline.id + '"><img src="/thumbnail/' + attachment.id + '?w=112&h=112" style="margin:5px;" alt="' + attachment.orig_name + '" /></a>';
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
			if($.trim(recent_timeline.apply_icon) != '') {
				apply_icon = '<a href="' + recent_timeline.apply_icon + '" target="_blank"><img src="/public/images/front/icon_86x86_applynow.gif" /></a>';
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

$(document).ready(function() {
	// get timelines
	var rl = new recent_timeline();
	rl.get_recent_timelines();
	
	// timeline more
	$('div.recents > div.more')
	.click(function() {
		rl.get_recent_timelines();
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
});