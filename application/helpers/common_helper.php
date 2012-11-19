<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
if(!function_exists('ifempty')) {
	function ifempty(&$value, $alternative = null) {
		if(isset($value) && $value) {
			return $value;
		} else {
			return $alternative;
		}
	}
}

if(!function_exists('array_getter')) {
	function array_getter(&$array, $index) {
		return isset($array[$index]) ? $array[$index] : '';
	}
}

if(!function_exists('split_getter')) {
	function split_getter($string, $delimiter, $index) {
		$splited = explode($delimiter, $string);
		
		return ifempty($splited[$index], '');
	}
}

if(!function_exists('set_error')) {
	// set_error('error', '메세지...');
	// set_error('notice', '메세지...');
	function set_error($type, $message) {
		$CI =& get_instance();
		
		if($type == 'error') $CI->otd->is_error = true;		
		if(!isset($CI->otd->errors)) $CI->otd->errors = array();
		
		$CI->otd->errors[] = array(
			'type' => $type,
			'message' => $message
		);
		
		return $CI->session->set_flashdata('errors', $CI->otd->errors);
	}
}

if(!function_exists('get_error')) {
	function get_error() {
		$CI =& get_instance();
		$errors = $CI->session->flashdata('errors');
		
		return $errors ? $errors : array();
	}
}

if(!function_exists('error_exists')) {
	function error_exists() {
		$CI =& get_instance();
		
		return isset($CI->otd->is_error) && $CI->otd->is_error ? true : false;
	}
}
/* EOF */