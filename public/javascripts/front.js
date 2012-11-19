/**
 * Utility
 */
function uniqid(prefix, more_entropy) {
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    revised by: Kankrelune (http://www.webfaktory.info/)
    // %        note 1: Uses an internal counter (in php_js global) to avoid collision
    // *     example 1: uniqid();
    // *     returns 1: 'a30285b160c14'
    // *     example 2: uniqid('foo');
    // *     returns 2: 'fooa30285b1cd361'
    // *     example 3: uniqid('bar', true);
    // *     returns 3: 'bara20285b23dfd1.31879087'
    if (typeof prefix == 'undefined') {
        prefix = "";
    }

    var retId;
    var formatSeed = function (seed, reqWidth) {
        seed = parseInt(seed, 10).toString(16); // to hex str
        if (reqWidth < seed.length) { // so long we split
            return seed.slice(seed.length - reqWidth);
        }
        if (reqWidth > seed.length) { // so short we pad
            return Array(1 + (reqWidth - seed.length)).join('0') + seed;
        }
        return seed;
    };

    // BEGIN REDUNDANT
    if (!this.php_js) {
        this.php_js = {};
    }
    // END REDUNDANT
    if (!this.php_js.uniqidSeed) { // init seed with big random int
        this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
    }
    this.php_js.uniqidSeed++;

    retId = prefix; // start with prefix, add current milliseconds hex string
    retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
    retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
    if (more_entropy) {
        // for more entropy we add a float lower to 10
        retId += (Math.random() * 10).toFixed(8).toString();
    }

    return retId;
}

/**
 * Flash screen
 */
var flash_screen = function() {
	this.errors = null;
	this.html = '<div id="flash_screen_overlay"><div id="flash_screen"><h1>알립니다!</h1><ul></ul></div><div id="close_flash_screen" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-close"></span></div></div>';
	this.$container = null;
	this.$overlay = null;
	this.$screen = null;
	this.duration = 1500;
	this.is_closed = false;
	
	this.set_errors = function(errors) {
		this.errors = errors || null;
		
		return this;
	}
	
	this.observe = function() {
		if($.type(this.errors) != 'null'
			&& this.errors.length > 0) {
			var self = this;
			
			this._create_screen();
			this._render_errors();
			this._show_screen(function() {
				if(!self.is_closed) self._hide_screen();
			});
		}
	}
	
	this._create_screen = function() {
		var width = $(window).width(), min_height = 150, self = this;
		
		$(this.html).appendTo('body');
		this.$overlay = $('div#flash_screen_overlay');
		this.$screen = $('div#flash_screen');
		$('div#close_flash_screen').click(function() {
			if(!self.is_closed) self._hide_screen('now');
		});
	}
	
	this._render_errors = function() {
		var ul = this.$screen.find('ul'), li;
		
		for(i in this.errors) {
			var error = this.errors[i];
			
			if(error.type == 'error') {
				li = '<li class="error">';
				li += '<div class="ui-state-error ui-corner-all"><span class="ui-icon ui-icon-alert"></span></div>';
			} else {
				li = '<li class="notice">';
				li += '<div class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-info"></span></div>';
			}
			
			li += error.message + '</span></li>';
			
			ul.append(li);
		}
	}
	
	this._show_screen = function(callback) {
		this.$overlay.show('blind', null, null, callback);
	}
	
	this._hide_screen = function(now) {
		var self = this;
				
		this.is_closed = true;
				
		if($.type(now) == 'undefined') {
			setTimeout(function() {
				self.$overlay.hide('blind');
			}, this.duration);			
		} else {
			this.$overlay.hide('blind');
		}
	}
}

$(document).ready(function() {
	(new flash_screen()).set_errors(otd_flash_error).observe();
});
