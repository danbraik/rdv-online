<?php

class De {
	static $output = '';
	
	static function getOutput() {
		return De::$output;
	}
	
	static function bug($msg) {
		De::$output .= $msg.'<br/>';
	}
	
	static function bug_d($date) {
		De::bug($date->format('D d M Y'));
	}
	
	static function bug_d2($date) {
		De::bug($date->format(DateTime::RSS));
	}
	
	static function dump($obj) {
		De::bug( var_export($obj, true));
	}
}

class Slots extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('slots_model', 'slots');
	}
		
	
	function index() {
		
	}
	
	function generate() {
		
		
		$d1 = new DateInterval("PT9H");
		$d2 = new DateInterval("PT11H30M");
		$d3 = new DateInterval("PT13H30M");
		$d4 = new DateInterval("PT18H45M");
		
		$duration = new DateInterval("PT15M");
		
		$week = array(
			array( // dimanche
				),
			array( // lundi
				),
			array( // mardi
				array( $d1, $d2 ), array( $d3, $d4)
				),
			array( // mercredi
				array( $d1, $d2 )
				),
			array( // jeudi
				array( $d1, $d2 ), array( $d3, $d4)
				),
			array( // vendredi
				array( $d1, $d2 ), array( $d3, $d4)
				),
			array( // samedi
				)
			);
		
		$world_begin = $this->slots->getLastDateTime();
		$world_begin->modify('1 day');
		
		$world_end = clone $world_begin;
		$world_end->modify('2 weeks');
		
		//De::dump(get_object_vars($d1));
		//De::bug( date_create(1200)->diff(date_create(1000))->invert); 
		
// todo : world begin		
		$this->_gen($week, $duration, $world_begin, $world_end);
			
		$this->load->view('dev/done', array("txt" => De::getOutput()));
	}
	
	private function compare($date1, $date2) {
		$diff = $date1->diff($date2);
		
		//De::dump($diff);
		
		$sec = (((((($diff->y * 12) + $diff->m) * 31) + $diff->d) * 24 +  $diff->h) * 60 + $diff->i) * 60 + $diff->s;
		
		if ($diff->invert === 0)
			return -$sec;
		return $sec;
	}
	
	private function _gen($week, $duration, $pWorld_begin, $pWorld_end) {
		
		$iterator_day = clone $pWorld_begin;
		$iterator_day->setTime(0,0,0);
		
		$day_end = clone $pWorld_end;
		$day_end->setTime(0,0,0);
		
		De::bug('World begin :');
		De::bug_d2($iterator_day);
		De::bug('World end :');
		De::bug_d2($day_end);
		
		
		De::bug("Days to proceed : ".$iterator_day->diff($day_end)->days);
		
		De::bug('****************************************');
		
		while ($this->compare($iterator_day, $day_end) < 0) {
			De::bug_d($iterator_day);
			
			
			$day_of_week_index = $iterator_day->format('w'); // 0..6
			
			// iterate over each slot period corresponding the day of the week
			foreach ($week[$day_of_week_index] as $period) {
				//De::dump($period);
				
				// init the iterator to the period begining
				$iterator_slot = clone $iterator_day;
				$iterator_slot->add($period[0]);
				
				// compute end of the period to proceed
				$end_period = clone $iterator_day;
				$end_period->add($period[1]);
				$end_period->sub($duration);
				
				De::bug('Begin period :');
				De::bug_d2($iterator_slot);
				De::bug('End period :');
				De::bug_d2($end_period);
				
				De::bug('---');
				while ($this->compare($iterator_slot, $end_period) <= 0) {
					//De::bug_d2($iterator_slot);
					
					$this->slots->create($iterator_slot);
					
					// go to the next slot
					$iterator_slot->add($duration);
				}
				De::bug('');
			}
			
			// go to the next day
			$iterator_day->modify('tomorrow');
			De::bug('');De::bug('');De::bug('');
		}
		
		
		
		
		
	}
	

	public function show($type='list') {
		$this->output->enable_profiler(true);
		
		$disp_begin = new DateTime('now', $this->slots->getDateTimeZone());
		$disp_begin->setTime(0,0,0);
		
		$disp_end = clone $disp_begin;
		$disp_end->modify('1 week');
		
		$slots = $this->slots->get($disp_begin, $disp_end);
		
		$this->load->model('patients_model', 'patients');
		
		$filled_slots = array();
		
		
		foreach ($slots as $slot) {
			$id_patient = $slot[$this->slots->getFieldIdPatient()];
			$patient = NULL;
			
			if ((int)($id_patient) != 0)
				$patient = $this->patients->get((int)($id_patient));
			
			array_push($filled_slots, 
				array( "slot" => $slot,
						"patient" => $patient));
			
		}
		
		$this->load->helper('url');
		$this->load->view('dev/template_patient');
		$this->load->view('dev/template_slot');
		
		if ($type == 'list')
			$this->load->view('dev/list_slots', array("txt" => De::getOutput(),
												"slots" => $filled_slots));
		else if ($type == 'calendar')
			$this->load->view('dev/cal_slots', array("txt" => De::getOutput(),
												"slots" => $filled_slots));
	}
	
	
	public function take_rdv($id_slot = '', $id_patient = '') {
		$slot = $this->slots->getById($id_slot);
		var_dump($slot);
		if (count($slot) > 0) {
			if ($slot[$this->slots->getFieldIdPatient()] == NULL) {
				$this->slots->takeRdv($id_slot, $id_patient);
				
				$this->load->helper('url');
				redirect('dev/slots/show');
			} else {
				// error
				echo 'NOT POSSIBLE !';
			}
		}
	}
	
	public function near() {
		$disp_begin = new DateTime('now', $this->slots->getDateTimeZone());
		$slots = $this->slots->getFrees($disp_begin, 30);
		
		$filled_slots = array();
		
		
		foreach ($slots as $slot) {
			array_push($filled_slots, 
				array( "slot" => $slot
						));
		}
		
		$this->load->helper('url');
		$this->load->view('dev/template_patient');
		$this->load->view('dev/template_slot');
		
		$this->load->view('dev/list_slots', array("txt" => De::getOutput(),
												"slots" => $filled_slots));
	}
}
