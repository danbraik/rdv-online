<?php
class Accounts_model extends MY_Model {

	const TABLE_NAME 	= 'accounts';
	const FIELD_ID			= 'id';
	const FIELD_MAIL 		= 'mail'; // mail is also an unique login
	const FIELD_PASSWORD	= 'password';
	const FIELD_RANK		= 'rank';

	public function __construct() {
		parent::__construct(Accounts_model::TABLE_NAME);
	}
	
	public function gfId() {
		return Accounts_model::FIELD_ID;
	}
	
	// Get array index corresponding to mail data
	// @return [string] Array index of the mail
	public function gfMail() {
		return Accounts_model::FIELD_MAIL;
	}
	
	public function gfPassword() {
		return Accounts_model::FIELD_PASSWORD;
	}
	
	public function gfRank() {
		return Accounts_model::FIELD_RANK;
	}
	
	public function getById($id) {
		return $this->_getOneByX(Accounts_model::FIELD_ID, $id);
	}
	
	public function getByMail($mail) {
		return $this->_getOneByX(Accounts_model::FIELD_MAIL, $mail);
	}
	
	public function add($mail, $passwd, $rank) {
		$this->db->insert(Accounts_model::TABLE_NAME, array(
			Accounts_model::FIELD_MAIL		=> $mail,
			Accounts_model::FIELD_PASSWORD	=> $this->_compute_stored_passwd($passwd),
			Accounts_model::FIELD_RANK		=> $rank
		));
		return $this->db->insert_id();
	}
	
	public function updatePassword($id, $newPassword) {
		return $this->_updateX(Accounts_model::FIELD_ID, $id, 
			Accounts_model::FIELD_PASSWORD,
			$this->_compute_stored_passwd($newPassword));
	}
	
	public function updateRank($id, $newRank) {
		return $this->_updateX(Accounts_model::FIELD_ID, $id, 
			Accounts_model::FIELD_RANK, $newRank);
	}
	
	public function comparePasswords($submited, $stored) {
		return strcmp($this->_compute_stored_passwd($submited), $stored) == 0;
	}
	
	private function _compute_stored_passwd($passwd) {
		$a= sha1( $this->config->item('passwd_salt') . $passwd . $this->config->item('passwd_salt'));
		echo $a;
		return $a;
	}
	
	public function remove($id) {
		$this->db->where(Accounts_model::FIELD_ID, $id)
				 ->delete(Accounts_model::TABLE_NAME); 
	}
	
} 
 
