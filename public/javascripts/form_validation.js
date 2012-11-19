var form_validation = function() {
	/**
	 * rule foramt
	 * {
	 * 	'selector': {
	 * 		label: '필드명',
	 * 		rule: {
	 * 			min: 2,
	 * 			required: true
	 * 		}
	 * 	}
	 * }
	 */
		
	this.data = {
		error_message: {
			min: '{field}은(는) 최소 {arg1}자 이상이어야 합니다.',
			max: '{field}은(는) 최대 {arg1}자를 넘을 수 없습니다.',
			minmax: '{field}은(는) 최소 {arg1}자 이상 {arg2}자 이하여야 합니다.',
			numeric: '{field}은(는) 숫자, \'.\', \',\', \'-\' 만 입력 가능합니다.',
			integer: '{field}은(는) 숫자만 입력 가능합니다.',
			email: '{field}은(는) 올바른 이메일 형식이 아닙니다.',
			required: '{field}은(는) 비워둘 수 없습니다.',
			equal_to: '{field}와(과) {arg1}의 값이 일치하지 않습니다.'
		},
		ruleset: {},
		$form: $('form:last'),
		callback: function() {return true;}
	}

	this.initialize = function(data) {
		var self = this;
		
		$.extend(this.data, data || {});
		
		this.data.$form.submit(function() {
			return self.validate();
		});
	}
	
	this.validate = function() {
		if(this.data.ruleset) {
			var form_values = this.data.$form.serializeArray();
			var ruleset = this.data.ruleset;
			var self = this;
			
			for(i in form_values) {
				var item = form_values[i];
				var selector = item.name;
				var form_value = item.value;
				var rule = ruleset[selector];

				if($.type(rule) != 'undefined') {
					for(test_function_name in rule.rule) {
						var test_function_param = rule.rule[test_function_name];
						var test_function = self['_' + test_function_name];

						if($.type(test_function) == 'function') {
							if(!test_function(form_value, test_function_param)) {
								// test failed then prepare a error message
								var message = self.data.error_message[test_function_name] || false;
								
								if(message) {
									message = message.replace('{field}', rule.label);
								
									if(test_function_name == 'equal_to') {
										message = message.replace('{arg1}', ruleset[test_function_param].label);
									} else {
										if($.isArray(test_function_param)) {
											message = message.replace('{arg1}', test_function_param[0]).replace('{arg2}', test_function_param[1]);
										} else {
											message = message.replace('{arg1}', test_function_param);
										}
									}
								}

								$('[name=' + selector.replace(/\[/g, '\\[').replace(/\]/g, '\\]') + ']').css({
									border: '1px solid #E81010',
									'background-color': '#FFDBDB'
								}).focus();
								alert(message);								
								
								return false;
							}  else {
								$('[name=' + selector.replace(/\[/g, '\\[').replace(/\]/g, '\\]') + ']').css({
									border: '1px solid #aaa',
									'background-color': '#fff'
								});
							}
						}
					}
				}
			}
		}
		
		return this.data.callback();
	}
	
	this._min = function(value, param) {
		if(value.length >= param) {
			return true;
		} else {
			return false;
		}
	}
	this._max = function(value, param) {
		if(value.length <= param) {
			return true;
		} else {
			return false;
		}
	}
	this._minmax = function(value, param) {
		if(value.length >= param[0]
			&& value.length <= param[1]) {
			return true;
		} else {
			return false;
		}
	}
	this._numeric = function(value, param) {
		if($.trim(value) == '') return true;
		
		return /^[0-9.,-]+$/.test(value);
	}
	this._integer = function(value, param) {
		return /^[0-9]+$/.test(value);
	}
	this._email = function(value, param) {
		if($.trim(value) == '') return true;
		
		return /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value);
	}
	this._required = function(value, param) {
		return $.trim(value) == '' ? false : true;
	}
	this._equal_to = function(value, param) {
		var equal_to_value = $('input[name=' + param + ']').val();
		
		return value == equal_to_value ? true : false;
	}
}
