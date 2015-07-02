<?php
class Users_model extends CI_Model {
	
	const TABLE_NAME = 'rdv';
	const FIELD_ID = 'id';
	const FIELD_;
	
	public function __construct()
	{
		$this->load->database();
	}
	
	
	public function get_rdv_by_user_id($user_id) {
		
	}
	
	public function get_all_rdv() {
		
	}
	
	
} 
