<?php
class Patients_model extends MY_Model {
	
	const TABLE_NAME = 'patients';
	const FIELD_ID 			= 'id';
	const FIELD_ID_ACCOUNT 	= 'id_account';
	const FIELD_NAME 		= 'name';
	const FIELD_FNAME 		= 'fname';
	const FIELD_PHONE 		= 'phone';
	
	public function __construct() {
		parent::__construct(Patients_model::TABLE_NAME);
	}
	
	public function getTableName() {
		return Patients_model::TABLE_NAME;
	}
	
	public function gfId() {
		return Patients_model::FIELD_ID;
	}
	
	public function gfIdAccount() {
		return Patients_model::FIELD_ID_ACCOUNT;
	}
	
	public function gfName() {
		return Patients_model::FIELD_NAME;
	}
	
	public function gfFName() {
		return Patients_model::FIELD_FNAME;
	}
	
	public function gfPhone() {
		return Patients_model::FIELD_PHONE;
	}
	
	public function getById($id) {
		return $this->_getOneByX(Patients_model::FIELD_ID, $id);
	}
	
	public function getAllByIdAccount($id) {
		return $this->_getArrAllByX(Patients_model::FIELD_ID_ACCOUNT, $id);
	}
	
	public function add($name, $fname, $phone = '', $id_account = 0) {
		$data = array (
			Patients_model::FIELD_NAME	=> $name,
			Patients_model::FIELD_FNAME	=> $fname
		);
		if ($phone != '')
			$data[Patients_model::FIELD_PHONE] = $phone;
		if ($id_account != 0)
			$data[Patients_model::FIELD_ID_ACCOUNT] = $id_account;
			
		$this->db->insert(Patients_model::TABLE_NAME, $data);
		return $this->db->insert_id();
	}
	
	public function updateFNP($id, $name, $fname, $phone) {
		return $this->db->where(Patients_model::FIELD_ID, $id)
				 ->set(Patients_model::FIELD_NAME, $name)
				 ->set(Patients_model::FIELD_FNAME, $fname)
				 ->set(Patients_model::FIELD_PHONE, $phone)
				 ->update(Patients_model::TABLE_NAME) !== FALSE;
	}
	
	public function remove($id) {
		$this->db->where(Patients_model::FIELD_ID, $id)
				 ->delete(Patients_model::TABLE_NAME); 
	}
	
	public function search($name) {
		$this->db->like(Patients_model::FIELD_NAME, $name, 'after')
				 ->from(Patients_model::TABLE_NAME);
		
		$query = $this->db->get();
		if($query !== false)
			return $query->result_array();
		else
			return array();
	}
} 
