<?php
class Slots_model extends CI_Model {
	
	const TABLE_NAME = 'slots';
	const FIELD_ID = 'id';
	const FIELD_START = 'datetm_start';
	const FIELD_ID_PATIENT = 'id_patient';
	
	public function __construct()
	{
		$this->load->database();
	}
	
	public function getFieldIdPatient() {
		return Slots_model::FIELD_ID_PATIENT;
	}
	
	public function gfId() {
		return Slots_model::FIELD_ID;
	}
	
	public function gfIdPatient() {
		return Slots_model::FIELD_ID_PATIENT;
	}
	
	public function gfDate() {
		return Slots_model::FIELD_START;
	}
	
	public function getDateTimeZone() {
		return new DateTimeZone('Europe/Paris');
	}
	
	public function getIdFromDateTime($dateTime) {
		return (int)($dateTime->format('y')) * 10000000 +
						((int)($dateTime->format('m')) * 31 + 
						(int)($dateTime->format('d'))) * 10000 +
						(int)($dateTime->format('H')) * 100 + 
						(int)($dateTime->format('i'));
	}
	
	/// @param $dateTime Start of the slot
	public function create($dateTime) {
		return $this->db->insert(Slots_model::TABLE_NAME,
			array( Slots_model::FIELD_ID => $this->getIdFromDateTime($dateTime),
				   Slots_model::FIELD_START => $dateTime->format('Y-m-d H:i:s'))
		) !== false;
	}
	
	public function getLastDateTime() {
		$this->db->select_max(Slots_model::FIELD_START, 'last');
		$query = $this->db->get(Slots_model::TABLE_NAME);
		//var_dump($query);
		if ($query->num_rows() == 0)
			return false;
		$row = $query->row_array();
		return new DateTime($row['last'], $this->getDateTimeZone());
	}
	
	public function get($date_begin, $date_end) {
		
		$this->db->where(Slots_model::FIELD_ID.' >=', $this->getIdFromDateTime($date_begin))
				 ->where(Slots_model::FIELD_ID.' <', $this->getIdFromDateTime($date_end));
		$query = $this->db->get(Slots_model::TABLE_NAME);
		return $query->result_array();
	}
	
	public function getById($id) {
		return $this->db->where(Slots_model::FIELD_ID, $id)->get(Slots_model::TABLE_NAME)->row_array();
	}
	
	public function takeRdv($id_slot, $id_patient) {
		$this->db->where(Slots_model::FIELD_ID, $id_slot)
				 ->set(Slots_model::FIELD_ID_PATIENT, $id_patient)
				 ->update(Slots_model::TABLE_NAME);
	}
	
	public function freeRdv($id_slot) {
		$this->db->where(Slots_model::FIELD_ID, $id_slot)
				 ->set(Slots_model::FIELD_ID_PATIENT, NULL)
				 ->update(Slots_model::TABLE_NAME);
	}
	
	
	public function getFrees($date_begin, $limit) {
		$this->db->where(Slots_model::FIELD_ID.' >= ', $this->getIdFromDateTime($date_begin))
				 ->where(Slots_model::FIELD_ID_PATIENT, NULL);
		$query = $this->db->get(Slots_model::TABLE_NAME, $limit);
		return $query->result_array();
	}
	
	public function getByAccount($id_account) {
		
		$this->load->model('patients_model', 'patients');
		
		$this->db->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID)
				->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_START)
				->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID_PATIENT)
				
				->select($this->patients->getTableName().'.'.$this->patients->gfName())
				->select($this->patients->getTableName().'.'.$this->patients->gfFName())
				->from(Slots_model::TABLE_NAME)
				//->from($this->patients->getTableName())
			->join( $this->patients->getTableName(),
					$this->patients->getTableName().'.'.$this->patients->gfId().'='.
				Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID_PATIENT)
			
			->where(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID. ' >= ', 
					$this->getIdFromDateTime( new DateTime('now', $this->getDateTimeZone()) ))
					  
			->where( $this->patients->getTableName().'.'.$this->patients->gfIdAccount(),
					$id_account)
			
			->order_by(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID, 'asc');
		 
		$query = $this->db->get();
		
		if($query !== false)
			return $query->result_array();
		else
			return array();
	}
	
	public function getAvailableDaysByMonth($year, $month, $base_url) {
		$start_day = new DateTime('', $this->getDateTimeZone());
		$start_day->setDate($year, $month, 1);
		$start_day->setTime(0,0,0);
		
		$end_day = clone $start_day;
		$end_day->modify('+1 month');
		
		$today_id = $this->getIdFromDateTime(new DateTime('now', $this->getDateTimeZone()));
		$start_id = $this->getIdFromDateTime( $start_day );
		
		$start_id = ($start_id > $today_id) ? $start_id : $today_id;
		
		//var_dump( $start_day );
		//var_dump( $end_day );
		
		$this->db
		
		//->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID)
		->select('SUBSTRING(`'.Slots_model::TABLE_NAME.'`.`'.Slots_model::FIELD_ID.'`, 1, 5) AS id_day', false)
		->select('DAYOFMONTH(`'.Slots_model::TABLE_NAME.'`.`'.Slots_model::FIELD_START.'`) AS day_of_month', false)		
				
				
				->from(Slots_model::TABLE_NAME)
				
				->where(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID. ' >= ', 
					$start_id)
				
				->where(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID. ' < ', 
					$this->getIdFromDateTime( $end_day ))
				
				->where(Slots_model::FIELD_ID_PATIENT, NULL)
				
				->group_by('id_day');
				
		
		$query = $this->db->get();
		if($query !== false)
			$qresult_arr = $query->result_array();
		
		$res = array();
		
		foreach($qresult_arr as $row) {
			$res[$row['day_of_month']] = $base_url.$row['id_day'];
		}
		
		//var_dump( $res);
		
		return $res;
	}
	
	
	public function getFreesByIdDay($id_day) {
		$start_id = $id_day * 10000;
		$end_id = $start_id + 9999;
		
		$now_id = $this->getIdFromDateTime(new DateTime('now', $this->getDateTimeZone()));
		$start_id = ($start_id > $now_id) ? $start_id : $now_id;
		
		
		$this->db
		
				->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID)
				->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_START)
				
				->from(Slots_model::TABLE_NAME)
				
				->where(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID. ' >= ', 
					$start_id )
				
				->where(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID. ' < ', 
					$end_id )
				
				->where(Slots_model::FIELD_ID_PATIENT, NULL);
				
				
		
		$query = $this->db->get();
		if($query !== false)
			return $query->result_array();
		else
			return array();
	}
	
	public function hasRdvByIdPatient($id_patient) {
		$this->db->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID)
				 ->from(Slots_model::TABLE_NAME)
				
			->where(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID. ' >= ', 
					$this->getIdFromDateTime( new DateTime('now', $this->getDateTimeZone()) ))
					  
			->where(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID_PATIENT, 
					$id_patient);
			
		return $this->db->count_all_results() > 0;
	}
	
	public function freeRdvsByIdPatient($id_patient) {
		$this->db->where(Slots_model::FIELD_ID_PATIENT, $id_patient)
				 ->set(Slots_model::FIELD_ID_PATIENT, NULL)
				 ->update(Slots_model::TABLE_NAME);
	}
	
	
	
	/*
	 * For boss
	 * get days which have a slot (taken or not)
	 */
	public function getSlotsByMonth($year, $month, $base_url) {
		$start_day = new DateTime('', $this->getDateTimeZone());
		$start_day->setDate($year, $month, 1);
		$start_day->setTime(0,0,0);
		
		$end_day = clone $start_day;
		$end_day->modify('+1 month');
		
		$start_id = $this->getIdFromDateTime( $start_day );
		$end_id = $this->getIdFromDateTime( $end_day );
		
		//$today_id = $this->getIdFromDateTime(new DateTime('now', $this->getDateTimeZone()));
		//$start_id = ($start_id > $today_id) ? $start_id : $today_id;
		
		
		$this->db
		
				->select('SUBSTRING(`'.Slots_model::TABLE_NAME.'`.`'.Slots_model::FIELD_ID.'`, 1, 5) AS id_day', false)
				->select('DAYOFMONTH(`'.Slots_model::TABLE_NAME.'`.`'.Slots_model::FIELD_START.'`) AS day_of_month', false)		
				
				->from(Slots_model::TABLE_NAME)
				
				->where(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID. ' >= ', $start_id)
				
				->where(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID. ' < ', $end_id)
				
				->group_by('id_day');
		
		$query = $this->db->get();
		if($query !== false)
			$qresult_arr = $query->result_array();
		else
			$qresult_arr = array();
		
		$res = array();
		
		foreach($qresult_arr as $row) {
			$res[$row['day_of_month']] = $base_url.$row['id_day'];
		}
			
		return $res;
	}
	
	
	public function getSlotsByIdDay($id_day) {
		$start_id = $id_day * 10000;
		$end_id = $start_id + 9999;
		
		$this->db
				->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID)
				->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_START)
				->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID_PATIENT)
				
				->from(Slots_model::TABLE_NAME)
				
				->where(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID. ' >= ', 
					$start_id )
				
				->where(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID. ' < ', 
					$end_id );
				
		$query = $this->db->get();
		if($query !== false)
			return $query->result_array();
		else
			return array();
	}
	
	
	public function getFirstFreeSlot() {
		$this->db->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID)
				->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_START)
						
				->from(Slots_model::TABLE_NAME)
				
				->where(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID. ' >= ', 
					$this->getIdFromDateTime( new DateTime('now', $this->getDateTimeZone()) ))
					  
				->where(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID_PATIENT, NULL)
				
				->limit(1);
			
		return $this->db->get()->row_array();
	}
	
	public function removeAvailableSlots($date_begin, $date_end) {
		$this->db->where(Slots_model::FIELD_ID.' >=', $this->getIdFromDateTime($date_begin))
				 ->where(Slots_model::FIELD_ID.' <', $this->getIdFromDateTime($date_end))
				 ->where(Slots_model::FIELD_ID_PATIENT, NULL)
				 ->delete(Slots_model::TABLE_NAME); 	
	}
	
	public function getTakenSlots($date_begin, $date_end) {
		$this->db->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID)
				->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_START)
				->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID_PATIENT)
						
				->from(Slots_model::TABLE_NAME)
				
				->where(Slots_model::FIELD_ID.' >=', $this->getIdFromDateTime($date_begin))
				->where(Slots_model::FIELD_ID.' <', $this->getIdFromDateTime($date_end))
					  
				->where(Slots_model::FIELD_ID_PATIENT.' >', 0);
		
		$query = $this->db->get();
		if($query !== false)
			return $query->result_array();
		else
			return array();
	}
	
	public function remove($id) {
		$this->db->where(Slots_model::FIELD_ID, $id)
				 ->delete(Slots_model::TABLE_NAME); 					 
	}
	
	
	public function getByIdPatient($id_patient) {
		$this->db->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID)
				 ->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_START)
				 ->select(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID_PATIENT)
				 
				 ->from(Slots_model::TABLE_NAME)
				 
				 ->where(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID. ' >= ', 
					$this->getIdFromDateTime( new DateTime('now', $this->getDateTimeZone()) ))
					  
			->where(Slots_model::TABLE_NAME.'.'.Slots_model::FIELD_ID_PATIENT, 
					$id_patient);
			
		return $this->db->get()->row_array();
	}
} 
