<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Otd_tag {
	public $CI;
	
	public function __construct() {
		$this->CI =& get_instance();
		
		// load libraries
		$this->CI->load->model('Tag_model');
		$this->CI->load->model('Tag_relation_model');
	}
	
	public function get_references_by_tag($tag) {
		$sql = 'SELECT tag_relations.reference, tag_relations.reference_id
		FROM tags
		INNER JOIN tag_relations ON tag_relations.tag_id = tags.id
		WHERE tags.name = ?';		
		$result = $this->CI->Tag_relation_model->query($sql, array($tag));
		
		return $result->num_rows() > 0 ? $result->result() : array();		
	}
	
	public function get_tag($reference, $reference_id) {
		$sql = 'SELECT tags.name
		FROM tag_relations
		INNER JOIN tags ON tags.id = tag_relations.tag_id
		WHERE tag_relations.reference = ?
		AND tag_relations.reference_id = ?';
		$result = $this->CI->Tag_relation_model->query($sql, array($reference, $reference_id));
		
		return $result->num_rows() > 0 ? $result->result() : array();
	}
	
	public function set_tag($tags, $reference, $reference_id) {
		// tags
		$new_tags = array();
		foreach(explode(',', $tags) as $tag) {
			$tag = trim($tag);
			if($tag) {
				if($tag_data = $this->CI->Tag_model->find_one(array('name' => $tag))) {
					$new_tags[] = $tag_data->id;
				} else {
					$new_tags[] = $this->CI->Tag_model->create(array('name' => $tag));
				}
			}
		}
		
		// filtering
		$old_tags = array();
		foreach($this->CI->Tag_relation_model->find(array('reference' => $reference, 'reference_id' => $reference_id)) as $tag_relation) {
			$old_tags[] = $tag_relation->tag_id;
		}
		
		$this->CI->db->trans_start();
			// add new tag relation		
			foreach(array_diff($new_tags, $old_tags) as $tag_id) {
				$this->CI->Tag_relation_model->create(array('tag_id' => $tag_id, 'reference' => $reference, 'reference_id' => $reference_id));
			}
			
			// delete old tag relation
			foreach(array_diff($old_tags, $new_tags) as $tag_id) {
				$this->CI->Tag_relation_model->delete(array('tag_id' => $tag_id, 'reference' => $reference, 'reference_id' => $reference_id));
			}
		$this->CI->db->trans_complete();
		
		return $this->CI->db->trans_status();
	}
}
/* EOF */