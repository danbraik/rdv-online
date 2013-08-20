<?php

class MY_Model extends CI_Model {

	var $mTable = '';

	// @param $table Name of the database table
    function __construct($table = '') {
        parent::__construct();
        $this->load->database();
        $this->mTable = $table;
    }
    
    // Get one element with filter on one field
    // @param $field Name of the filed to filter
    // @param $value Value of the filter
    // @return false if no corresponding or an array result
    protected function _getOneByX($field, $value) {
		$row = $this->db->get_where($this->mTable,
			array($field => $value))->row_array();
		if (count($row) == 0)
			return false;
		return $row;
	}
	
	// Get all elements with filter on one field
    // @param $field Name of the filed to filter
    // @param $value Value of the filter
    // @return false if no corresponding or an array result
    protected function _getAllByX($field, $value) {
		$row = $this->db->get_where($this->mTable,
			array($field => $value))->result_array();
		if (count($row) == 0)
			return false;
		return $row;
	}
	
	// Get all elements with filter on one field
    // @param $field Name of the filed to filter
    // @param $value Value of the filter
    // @return an array result in all cases (may be empty)
    protected function _getArrAllByX($field, $value) {
		return $this->db->get_where($this->mTable,
			array($field => $value))->result_array();
	}
	
	
	protected function _updateX($idField, $id, $field, $newValue) {
		return $this->db->where($idField, $id)
						->update($this->mTable,
								 array($field => $newValue)); 
	}
}
