<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
	오픈트레이드 전역 설정
 */

// DB Replications
$config['replications'] = array('replica1');

// Paths
$config['attachment_path'] = './public/_attachments';
$config['thumbnail_path'] = './public/_thumbnails';

// Audit
$config['audit_user_logging'] = true;
$config['audit_admin_logging'] = true;
$config['audit_system_logging'] = true;

// Pagination default
$config['pagination_default'] = array(
	'first_link' => false,
	'prev_link' => '« 이전',
	'last_link' => false,
	'next_link' => '다음 »',
	'cur_tag_open' => '<li class="active"><a href="#" onclick="return false;">',
	'cur_tag_close' => '</a></li>',
	'num_tag_open' => '<li>',
	'num_tag_close' => '</li>',
	'prev_tag_open' => '<li>',
	'prev_tag_close' => '</li>',
	'next_tag_open' => '<li>',
	'next_tag_close' => '</li>',
//	'anchor_class' => 'class="number"',
	'use_page_numbers' => true,
	'page_query_string' => true,
	'per_page' => 10,
	'num_links' => 4,
	'full_tag_open' => '<div class="pagination"><ul>',
	'full_tag_close' => '</ul></div>'
);

// Admin menu
$config['admin_menu'] = array(
	array(
		'name' => '회원 관리',
		'link' => '#',
		'perm' => array(),
		'children' => array(
			array(
				'name' => '회원 목록',
				'link' => '/admin/member',
				'perm' => array()
			),
			array(
				'name' => '신규 추가',
				'link' => '/admin/add',
				'perm' => array()
			)
		)
	),
	array(
		'name' => '통계',
		'link' => '#',
		'perm' => array(),
		'children' => array(
			array(
				'name' => '회원 통계',
				'link' => '/admin/statistic_member',
				'perm' => array()
			),
			array(
				'name' => '등록비 통계',
				'link' => '/admin/statistic_reg',
				'perm' => array()
			)
		)
	)
);
/* EOF */