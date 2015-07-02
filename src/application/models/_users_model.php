<?php
class Users_model extends CI_Model {

	const TABLE_NAME = 'users';
	const FIELD_ID = 'id';
	const FIELD_LOGIN = 'login';
	const FIELD_PASSWD = 'passwd';
	const FIELD_RANK = 'rank';
	const FIELD_MAIL = 'mail';
	const FIELD_NAME = 'name';
	const FIELD_FNAME = 'firstname';
	

	public function __construct()
	{
		$this->load->database();
	}
	
	public function get_user_by_login($login)
	{
		$query = $this->db->get_where(Users_model::TABLE_NAME, array(Users_model::FIELD_LOGIN => $login));
		return $query->row_array();
	}
	
	public function add_user($login, $passwd, $rank, $mail, $name, $fname)
	{
		$insert = $this->db->insert(Users_model::TABLE_NAME, array(
			Users_model::FIELD_LOGIN	=> $login,
			Users_model::FIELD_PASSWD	=> $passwd,
			Users_model::FIELD_RANK		=> $rank,
			Users_model::FIELD_MAIL		=> $mail,
			Users_model::FIELD_NAME		=> $name,
			Users_model::FIELD_FNAME	=> $fname
		));
		
		return $insert;
	}
	
} 
 
