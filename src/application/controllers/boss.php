<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class DateTimeFrench extends DateTime {
	public function format($format) {
		$english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		$french_days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
		$english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$french_months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
		return str_replace($english_months, $french_months, str_replace($english_days, $french_days, parent::format($format)));
	}
}

function date_swap($dat)
{	
	$day = intval($dat);
	$dat = substr($dat, strpos($dat, '/')+1);
	$month = intval($dat);
	$dat = substr($dat, strpos($dat, '/')+1);
	$year = intval($dat);
	
	return $month.'/'.$day.'/'.$year;
}

/*
 */ 
class Boss extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('ranks');
		
		if ($this->session->userdata('user.rank') < $this->ranks->grSecretary()) {
			redirect('');
			exit;
		}
		
		$this->load->model('slots_model', 'slots');
		$this->load->model('patients_model', 'patients');
		$this->load->model('accounts_model', 'accounts');
	}
	
	public function index($year = 0, $month = 0, $day = 0)
	{
		$data['header_title'] = 'Tableau de bord';
		
		$data['url_today'] 		= '/boss/index';
		$data['url_managep']	= '/boss/managep';
		$data['url_planning']	= '/boss/planning';
		
		
		// generate big calendar
		$today = new DateTimeFrench('now', $this->slots->getDateTimeZone());
		$today->setTime(0,0,0);
		
		$data['url_goto_today'] = '/boss/index/'.$today->format('Y').'/'
												.$today->format('m').'/'
												.$today->format('d');
		
		$byear = intval(($year == 0) ? $today->format('Y') : $year);
		$bmonth = intval(($month == 0) ? $today->format('m') : $month);
		$bday = intval(($day == 0) ? $today->format('d') : $day);
		
		$date_begin = clone $today; // fast allocation
		$date_begin->setDate($byear, $bmonth, $bday);
		$date_begin->setTime(0,0,0);
		if ($date_begin->format('N') != '1')
			$date_begin->modify('last monday');
		
		$date_end = clone $date_begin;
		$date_end->modify('+1 week');
		
		$allslots = $this->slots->get($date_begin, $date_end);
		
		$week = array();
		$week_h = array();
		
		// create rows (correspond to hours)
		foreach ($allslots as $slot) {
			$id_slot = intval($slot['id']);
			
			$id_time = intval($id_slot % 10000);
			
			if (!isset($week[$id_time])) {
				$week[$id_time] = array();
				$week[$id_time]['time'] = $id_time;
				$week[$id_time]['slots'] = array();
			}
			
		}
		
		ksort($week);
		
		$iter = clone $date_begin;
		
		for($i=0;$i<7;++$i) {
			$id_day = intval($this->slots->getIdFromDateTime($iter) / 10000);
			
			$keys_id_time = array_keys($week);
			foreach($keys_id_time as $k_id_time)
				if (!isset($week[$k_id_time]['slots'][$id_day]))
					$week[$k_id_time]['slots'][$id_day] = 0;
			if (!isset($week_h[$id_day])) {
				$week_h[$id_day]['date'] = clone $iter;
				$week_h[$id_day]['url'] = '/boss/show/'.$id_day;
			}
					
			$iter->modify('+1 day');		
		}
		
		// fill rows with columns data (column is a day)
		foreach ($allslots as $slot) {
			$id_slot = intval($slot['id']);
			
			$id_day = intval($id_slot / 10000);
			$id_time = $id_slot - $id_day * 10000;
			
			$keys_id_time = array_keys($week);
			foreach($keys_id_time as $k_id_time)
				if (!isset($week[$k_id_time]['slots'][$id_day]))
					$week[$k_id_time]['slots'][$id_day] = 0;
			
			$week[$id_time]['slots'][$id_day] = $slot;
			
			if (!isset($week_h[$id_day])) {
				$week_h[$id_day]['date'] = new DateTimeFrench($slot[$this->slots->gfDate()],
											$this->slots->getDateTimeZone());
				$week_h[$id_day]['url'] = '/boss/show/'.$id_day;
			}
		}
		
		ksort($week_h);
		
		//print_r($week_h);
		
		$slot_first_free = $this->slots->getFirstFreeSlot();
		$date_first_free = new DateTimeFrench($slot_first_free[$this->slots->gfDate()],
											$this->slots->getDateTimeZone());
		
		$data['url_goto_first_free'] = '/boss/index/'.$date_first_free->format('Y').'/'
										.$date_first_free->format('m').'/'
										.$date_first_free->format('d');
		
		 
		$data['cal_header'] = $week_h;
		$data['cal_content'] = $week;
		
		$date_before = clone $date_begin;
		$date_before->modify('-1 week');
		$data['url_prev'] = '/boss/index/'.$date_before->format('Y').'/'
										.$date_before->format('m').'/'
										.$date_before->format('d');
		
		$data['url_next'] = '/boss/index/'.$date_end->format('Y').'/'
										.$date_end->format('m').'/'
										.$date_end->format('d');
		
		$date_end = clone $date_begin;
		$date_end->modify('+4 weeks');
		$data['url_next_month'] = '/boss/index/'.$date_end->format('Y').'/'
										.$date_end->format('m').'/'
										.$date_end->format('d');
		$date_end = clone $date_begin;
		$date_end->modify('-4 weeks');
		$data['url_prev_month'] = '/boss/index/'.$date_end->format('Y').'/'
										.$date_end->format('m').'/'
										.$date_end->format('d');
																		
										
		$data['url_form_post'] = '/boss/gotod/'.$byear.'/'.$bmonth.'/'.$bday;
		$data['year'] = $byear;
		$data['base_url_choose'] = '/boss/choose/';
		
		
		
		$this->load->helper('form');
		
		$data['page'] = $this->load->view('boss/home_view', $data, true);
		$data['mq'] = false;
		$data['s_header'] = false;
		$this->load->view('theme/layout', $data);
	}
	
	
	
	
	
	public function _date_check($dat)
	{
		$this->form_validation->set_message('_date_check', "Ce n'est pas une date valide.");
		return preg_match('/^[0-3]?[0-9]\/[01]?[0-9]\/[0-9]{1,4}$/', $dat) == 1;
	}
	
	
	/*
	 * show links to manage the planning
	 */
	public function planning() {
		
		$data['url_holiday'] 	= '/boss/holiday';
		$data['url_rm_slots'] 	= '/boss/rmslots';
		
		$data['url_create_slots_per_day']		= '/boss/csd';
		$data['url_create_many_slots']	= '/boss/';
				
		$data['page'] = $this->load->view('boss/planning/home_view', $data, true);
		$this->load->view('theme/layout', $data);
	}
	
	/*
	 * Long holidays
	 */
	public function holiday() {
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('start', 'lang:start',
			'trim|required|callback__date_check|xss_clean');
		$this->form_validation->set_rules('end', 'lang:end',
			'trim|required|callback__date_check|xss_clean');
		
		$validated = false;
		$data['error'] = '';
		$data['msg'] = '';
		
		if($this->form_validation->run() !== FALSE) {
			
			$date_begin = new DateTimeFrench(
				date_swap($this->input->post('start')), $this->slots->getDateTimeZone());
			$date_end = new DateTimeFrench(
				date_swap($this->input->post('end')), $this->slots->getDateTimeZone());
			$date_end->setTime(23,59,59);
			
			$this->slots->removeAvailableSlots($date_begin, $date_end);
			
			$taken_slots = $this->slots->getTakenSlots($date_begin, $date_end);
			
			
			$data['out'] = '<table>
			<tr>
			<th>Date</th>
			<th>Prénom</th>
			<th>Nom</th>
			<th>Téléphone</th>
			<th>Mail</th>
			<th></th>
			</tr>
			
			';
			
			foreach($taken_slots as $ts) {
				$data['out'] .= '<tr>';
				
				$id_slot = $ts[$this->slots->gfId()];
				$id_patient = $ts[$this->slots->gfIdPatient()];
				$patient = $this->patients->getById($id_patient);
				$id_account = $patient[$this->patients->gfIdAccount()];
				$account = $this->accounts->getById($id_account);
				
				$this->slots->remove($id_slot);
				
				$date_slot = new DateTimeFrench($ts[$this->slots->gfDate()], $this->slots->getDateTimeZone());
				
				$data['out'] .= '<td>'.$date_slot->format('j F Y\<\b\r\/\>H:i').'</td>';
				$data['out'] .= '<td>'.$patient[$this->patients->gfFName()].'</td>';
				$data['out'] .= '<td>'.$patient[$this->patients->gfName()].'</td>';
				$data['out'] .= '<td>'.$patient[$this->patients->gfPhone()].'</td>';
				$data['out'] .= '<td>'.$account[$this->accounts->gfMail()].'</td>';
				
				
				// send mail
				$this->load->library('email');
				$this->email->from($this->config->item('mail.from.mail'),
								   $this->config->item('mail.from.name'));
				$this->email->to($account[$this->accounts->gfMail()]);
				
				$this->email->subject('Annulation du rendez-vous');
				$this->email->message(
					'Le rendez vous du '. $date_slot->format('l j F Y \à H:m').
					'  pour '.$patient[$this->patients->gfFName()]. ' '.
					 $patient[$this->patients->gfName()]. ' a été annulé pour cause de congé.
					Reprenez un rendez-vous ou prenez contact avec le secrétariat.'
					);
				
				if (!$this->email->send()) {
					$data['out'] .= '<td><strong>'."Erreur d'envoi mail".'</strong></td>';		
				} else {
					$data['out'] .= '<td></td>';
				}
				
				$data['out'] .= '</tr>';
			}
			
			$data['out'] .= '</table>';
			
			$validated = true;
		}
		
		if ($validated) {
			$data['msg'] = 'Le congé a bien été enregistré';
		}
		
		$data['header_title'] = '';
		$data['url_form_post'] = '/boss/holiday';
		$data['url_back'] = '/boss';
		
		$this->load->helper('form');
		$data['page'] = $this->load->view('boss/planning/holiday_view', $data, true);
		$this->load->view('theme/layout', $data);
	}
	
	
	
	
	
	
	
	
	/*
	 * Short holidays
	 */
	public function rmslots($id_slot = 0, $y='', $m='', $d='') {
		$id_slot = intval($id_slot) ;
		
		$data['msg'] = '';
		$data['error'] = '';
		$data['slots'] = array();
		$data['val_day'] = '';
		
		
		if ($id_slot != 0) {
			// rm slot
			
			$slot = $this->slots->getById($id_slot);
			
			if ($slot) {
			
				$this->slots->remove($id_slot);
				
				$id_patient = $slot[$this->slots->gfIdPatient()];
				
				if ($id_patient != NULL) {
					$patient = $this->patients->getById($id_patient);
					$id_account = $patient[$this->patients->gfIdAccount()];
					$account = $this->accounts->getById($id_account);
					
					// send mail
					$this->load->library('email');
					$this->email->from($this->config->item('mail.from.mail'),
									   $this->config->item('mail.from.name'));
					$this->email->to($account[$this->accounts->gfMail()]);
					
					$this->email->subject('Annulation du rendez-vous');
					$this->email->message(
						'Le rendez vous du ...
						à .... pour Mmme..... a été annulé pour cause de congé.
						Reprenez un rendez-vous ou prenez contact avec le secrétariat.'
						);
					
					if (!$this->email->send()) {
						$data['error'] .= 'Rendez-vous annulé. <strong>'."Erreur d'envoi mail".'</strong>';		
					} else {
						$data['msg'] .= 'Rendez-vous annulé.';
					}
				}
			}
		}
		
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('day', 'lang:day',
			'trim|required|callback__date_check|xss_clean');
		
		$validated = false;
		
		if($this->form_validation->run() !== FALSE || $y!='') {
			
			if ($this->input->post('submit'))
				$day = date_swap($this->input->post('day'));
			else
				$day = $m.'/'.$d.'/'.$y;
			
			$date_begin = new DateTimeFrench($day, $this->slots->getDateTimeZone());
			$y = $date_begin->format('Y');
			$m = $date_begin->format('m');
			$d = $date_begin->format('d');
			$data['val_day'] = $d.'/'.$m.'/'.$y;
			$date_end = clone $date_begin;
			$date_end->setTime(23,59,59);
			
			$data['slots'] = $this->slots->get($date_begin, $date_end);
			
			$date_prev = clone $date_begin;
			$date_prev->modify('-1 day');
			$date_next = clone $date_begin;
			$date_next->modify('+1 day');
			$data['url_prev_day'] = '/boss/rmslots/0/'.$date_prev->format('Y').'/'.
						$date_prev->format('m').'/'.
						$date_prev->format('d');
			$data['url_next_day'] = '/boss/rmslots/0/'.$date_next->format('Y').'/'.
						$date_next->format('m').'/'.
						$date_next->format('d');
			
			$this->load->model('patients_model', 'patients');
			
			$c=count($data['slots']);
			for($i=0;$i<$c;++$i) {
				$data['slots'][$i]['date'] = new DateTimeFrench(
					$data['slots'][$i][$this->slots->gfDate()],
					$this->slots->getDateTimeZone());
				unset($data['slots'][$i][$this->slots->gfDate()]);
				
				$patient = $this->patients->getById(
					$data['slots'][$i][$this->slots->gfIdPatient()]);
				
				$data['slots'][$i]['p.fname'] = $patient[$this->patients->gfFName()];
				$data['slots'][$i]['p.name'] = $patient[$this->patients->gfName()];
				$data['slots'][$i]['p.phone'] = $patient[$this->patients->gfPhone()];
			}
		}
		
		$data['url_back'] = '/boss';
		$data['url_form_post'] = '/boss/rmslots';
		$data['base_url_rm'] = '/boss/rmslots/';
		$data['basep_url_rm'] = '/'.$y.'/'.$m.'/'.$d;
		
		$this->load->helper('form');
		$data['page'] = $this->load->view('boss/planning/rmslots_view', $data, true);
		$data['header_title'] = 'Supprimer créneaux';
		$data['header_url_home'] = '/boss';
		$this->load->view('theme/layout', $data);		
	}
	
	
	
	
	
	public function _time_check($dat)
	{
		$this->form_validation->set_message('_time_check', "Ce n'est pas un horaire valide.");
		return preg_match('/^[0-2]?[0-9]:[0-5]?[0-9]$/', $dat) == 1;
	}
	
	
	private function compare($date1, $date2) {
		$diff = $date1->diff($date2);
		$sec = (((((($diff->y * 12) + $diff->m) * 31) + $diff->d) * 24 +  $diff->h) * 60 + $diff->i) * 60 + $diff->s;
		if ($diff->invert === 0)
			return -$sec;
		return $sec;
	}
	
	
	/*
	 * Create slots in one day
	 */
	public function csd() {
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('day', 'jour',
			'trim|required|callback__date_check|xss_clean');
		$this->form_validation->set_rules('begin', 'début de plage',
			'trim|required|callback__time_check|xss_clean');
		$this->form_validation->set_rules('end', 'fin de plage',
			'trim|required|callback__time_check|xss_clean');
		$this->form_validation->set_rules('duration', 'durée',
			'trim|required|is_numeric|xss_clean');
		
		$validated = false;
		$data['error'] = '';
		$data['msg'] = '';
		
		if($this->form_validation->run() !== FALSE) {
			
			
			$date_day = new DateTimeFrench(
				date_swap($this->input->post('day')), $this->slots->getDateTimeZone());
			
			list($begin_h, $begin_m) = explode(':', $this->input->post('begin'));
			list($end_h, $end_m) = explode(':', $this->input->post('end'));
			
			$time_begin = new DateInterval('PT'.$begin_h.'H'.$begin_m.'M');
			$time_end = new DateInterval('PT'.$end_h.'H'.$end_m.'M');
						
			$duration = new DateInterval('PT'.$this->input->post('duration').'M');
			
			
			// init the iterator to the period begining
			$iterator_slot = clone $date_day;
			$iterator_slot->add($time_begin);
			
			// compute end of the period to proceed
			$end_period = clone $date_day;
			$end_period->add($time_end);
			$end_period->sub($duration);
			
			$pre_exist = 0;
			
			while ($this->compare($iterator_slot, $end_period) <= 0) {
				//De::bug_d2($iterator_slot);
				
				if ($this->slots->create($iterator_slot) == false)
					++$pre_exist;
				
				// go to the next slot
				$iterator_slot->add($duration);
			}
			
			$data['msg'] = 'Les créneaux ont bien été créés<br/>
			'.$pre_exist.' existaient déjà';
		}
				
		$data['header_title'] = '';
		$data['url_form_post'] = '/boss/csd';
		
		$this->load->helper('form');
		$data['page'] = $this->load->view('boss/planning/csd_view', $data, true);
		//$data['njqm'] = true;
		$data['header_title'] = 'Créer créneaux';
		$data['header_url_home'] = '/boss';
		$this->load->view('theme/layout', $data);
	}
	
	
	
	
	
	
	
	
	
	
	/*
	 * 
	 */
	public function gotod($y,$m,$d) {
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('day', 'lang:day',
			'trim|required|callback__date_check|xss_clean');
		
		$validated = false;
		
		if($this->form_validation->run() !== FALSE) {
			$day = date_swap($this->input->post('day'));
			
			$date_begin = new DateTimeFrench($day, $this->slots->getDateTimeZone());
			$by = $date_begin->format('Y');
			$bm = $date_begin->format('m');
			$bd = $date_begin->format('d');
			
			redirect('/boss/index/'.$by.'/'.$bm.'/'.$bd);
		}
		
		redirect('/boss/index/'.$y.'/'.$m.'/'.$d);
	}
	
	
	
	public function show($id_day) {
		
		$slots = $this->slots->getSlotsByIdDay($id_day);
		
		$nb_patient = 0;
		
		$c=count($slots);
		for($i=0;$i<$c;++$i) {
			$slots[$i]['date'] = new DateTimeFrench($slots[$i][$this->slots->gfDate()],
													$this->slots->getDateTimeZone());
			$id_patient = intval($slots[$i][$this->slots->gfIdPatient()]);
			if ($id_patient > 0) {
				++$nb_patient;
				$patient = $this->patients->getById($id_patient);
				$slots[$i]['p.fname'] = $patient[$this->patients->gfFName()];
				$slots[$i]['p.name'] = $patient[$this->patients->gfName()];
				$slots[$i]['p.phone'] = $patient[$this->patients->gfPhone()];
			} else {
				$slots[$i]['p.fname'] = '';
				$slots[$i]['p.name'] = '';
				$slots[$i]['p.phone'] = '';
			}
		}
		
		$data['no_patient'] = $nb_patient == 0;
		$data['slots'] = $slots;
		$data['day'] = new DateTimeFrench(
				(count($slots) == 0 ? '' : $slots[0][$this->slots->gfDate()]),
										   $this->slots->getDateTimeZone());
		
		$data['page'] = $this->load->view('boss/show_view', $data, true);
		$data['njqm'] = true;
		$this->load->view('theme/layout', $data);	
	}
	
	
	public function choose($id_slot) {
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', 'lang:name',
			'trim|required|xss_clean');
		
		$validated = false;
		$data['error'] = '';
		$members = array();
		
		if($this->form_validation->run() !== FALSE) {
			$members = $this->patients->search($this->input->post('name'));
			
			$c=count($members);
			for($i=0;$i<$c;++$i)
				$members[$i]['account'] = $this->accounts->getById(
							$members[$i][$this->patients->gfIdAccount()]);
		}
		
		$data['members'] = $members;
		
		$data['header_title'] = '';
		$data['url_form_search'] = '/boss/choose/'.$id_slot;
		$data['url_form_post'] = '/boss/take/'.$id_slot.'/new';
		$data['base_url_take'] = '/boss/take/'.$id_slot.'/';
			
		$this->load->helper('form');
		$data['page'] = $this->load->view('boss/choose_view', $data, true);
		$this->load->view('theme/layout', $data);
	}
	
	
	public function take($id_slot, $id_patient) {
		$id_slot = intval($id_slot);
		$id_patient = intval($id_patient);
		
		$taken = false;
		
		
		
		if ($id_patient == 'new') {
			$id_patient = 0;
			$id_account = $this->session->userdata('user.id');
						
			if ($id_account > 0) {
				$name = $this->input->post('name');
				$name = ($name == '') ? 'XXX' : $name;
				$id_patient = $this->patients->add(
									$name,
									$this->input->post('fname'),
									$phone = $this->input->post('phone'),
									$id_account);
			}
		}

		$patient = $this->patients->getById($id_patient);
		$data['p_fname'] = $patient[$this->patients->gfFName()];
		$data['p_name']  = $patient[$this->patients->gfName()];
		
		var_dump($patient);
		
		$slot = $this->slots->getById($id_slot);
		$data['s_start'] = new DateTimeFrench($slot[$this->slots->gfDate()],
								$this->slots->getDateTimeZone());
		
		//if ($this->slots->hasRdvByIdPatient($id_patient)) {
			//show_error('Un rendez-vous maximum par personne');
		//} else {
			if ($slot[$this->slots->gfIdPatient()] == NULL) {	
				$this->slots->takeRdv($id_slot, $id_patient);
				$taken = true;
			} else {
				$taken = false;
			}
		//}
		
		// compute id_day
		$data['taken'] = $taken;
		$data['url_prev_day'] = '/boss/index/'. $data['s_start']->format('Y').'/'.
												$data['s_start']->format('m').'/'.
												$data['s_start']->format('d');
		$data['url_end'] = $data['url_prev_day'];
		
		$data['page'] = $this->load->view('boss/take_view', $data, true);
		$this->load->view('theme/layout', $data);
	}
	
	
	
	
	public function managep() {
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', 'lang:name',
			'trim|required|xss_clean');
		
		$validated = false;
		$data['error'] = '';
		$members = array();
		
		if($this->form_validation->run() !== FALSE) {
			$members = $this->patients->search($this->input->post('name'));
			
			$c=count($members);
			for($i=0;$i<$c;++$i) {
				$members[$i]['account'] = $this->accounts->getById(
							$members[$i][$this->patients->gfIdAccount()]);
				$members[$i]['next'] = $this->slots->getByIdPatient(
							$members[$i][$this->patients->gfId()]);
				if (isset($members[$i]['next'][$this->slots->gfDate()])) {
					$members[$i]['next']['date'] = 
						new DateTimeFrench($members[$i]['next'][$this->slots->gfDate()],
								$this->slots->getDateTimeZone());
				}
			}
		}
		
		$data['members'] = $members;
		
		$data['header_title'] = '';
		$data['url_form_search'] = '/boss/managep/';
		
		$data['base_url_modify_p'] = '/boss/modifyp/';
		$data['base_url_rm_s'] = '/boss/rmrdv/';
		
		$data['url_back'] = '/boss';
			
		$this->load->helper('form');
		$data['page'] = $this->load->view('boss/managep_view', $data, true);
		$data['mq'] = false;
		$this->load->view('theme/layout', $data);
	}
	
	
	
	public function modifyp($id_patient) {
		
		$patient = $this->patients->getById($id_patient);
		
		
		
		$this->load->library('form_validation');
				
		$this->form_validation->set_rules('name', 'lang:name',
			'trim|required|xss_clean');
		$this->form_validation->set_rules('fname', 'lang:fname',
			'trim|required|xss_clean');
		$this->form_validation->set_rules('phone', 'lang:phone',
			'trim|required|xss_clean');
		
		$validated = false;
		$data['error'] = '';
		
		if($this->form_validation->run() !== FALSE) {		
			if ($this->patients->updateFNP($id_patient,
						$this->input->post('name'),
						$this->input->post('fname'),
						$this->input->post('phone'))) {
				$validated = true;
			} else {
				$data['error'] = 'Erreur lors de la mise à jour';
			}
		}
		
		if (!$validated) {					
			$data['url_form_post'] = '/boss/modifyp/'.$id_patient;
			$data['p_fname'] = $patient[$this->patients->gfFName()];
			$data['p_name'] = $patient[$this->patients->gfName()];
			$data['p_phone'] = $patient[$this->patients->gfPhone()];
			
			$this->load->helper('form');
			$data['page'] = $this->load->view('boss/modifyp_view', $data, true);
			$data['njqm'] = true;
			$this->load->view('theme/layout', $data);
		} else {
			redirect('/boss/managep');
		}
		
		
	}
	
	
	public function rmrdv($id_slot) {
		$id_slot = intval($id_slot);
		
		$data['error'] = '';
		$data['msg'] = '';
		
		if ($id_slot != 0) {
			
			$slot = $this->slots->getById($id_slot);
			
			if ($slot) {
			
				$this->slots->freeRdv($id_slot);
				
				$id_patient = $slot[$this->slots->gfIdPatient()];
				
				if ($id_patient != NULL) {
					$patient = $this->patients->getById($id_patient);
					$id_account = $patient[$this->patients->gfIdAccount()];
					$account = $this->accounts->getById($id_account);
					
					$data['date'] = new DateTimeFrench($slot[$this->slots->gfDate()],
											$this->slots->getDateTimeZone());
					$data['p_fname'] = $patient[$this->patients->gfFName()];
					$data['p_name'] = $patient[$this->patients->gfName()];
					$data['p_phone'] = $patient[$this->patients->gfPhone()];
					
					// send mail
					$this->load->library('email');
					$this->email->from($this->config->item('mail.from.mail'),
									   $this->config->item('mail.from.name'));
					$this->email->to($account[$this->accounts->gfMail()]);
					
					$this->email->subject('Annulation du rendez-vous');
					$this->email->message(
						'Le rendez vous du ...
						à .... pour Mmme..... a été annulé par le secrétariat.
						'
						);
					
					if (!$this->email->send()) {
						$data['error'] .= 'Rendez-vous annulé. <strong>'."Erreur d'envoi mail".'</strong>';		
					} else {
						$data['msg'] .= 'Rendez-vous annulé.';
					}
				}
			} else
				show_error('Identifiant slot invalide.');
		}
		
		$data['url_next'] = '/boss/managep/'.$id_patient;
		
		$data['page'] = $this->load->view('boss/rmrdv_view', $data, true);
		$this->load->view('theme/layout', $data);
		
	}
	
	
	
}


	

/* End of file session.php */
/* Location: ./application/controllers/session.php */
 
