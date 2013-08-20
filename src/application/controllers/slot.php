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

function _sortMembers($m1, $m2) {
	if ($m1['enabled'] != $m2['enabled']) {
		if ($m1['enabled'])
			return -1;
		return 1;
	} else {
		return intval($m1['id']) - intval($m2['id']);
	}
}



/*
 * Controller of slots
 * A slot is a rendez-vous
 * 
 */ 
class Slot extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('slots_model', 'slots');
	}
	
	public function index()
	{
		
	}
	
	
	
	public function _wiz_take()
	{
		$this->load->helper('form');
		
		$data['header'] = '';
		$data['header_title'] = 'Choisir un créneau';
		$data['post_url'] = '/slot/take';
		$data['page'] = $this->load->view('slot/wiz_take_view', $data, true);
		
		$this->load->view('theme/layout', $data);
	}


	public function _mail_check($mail)
	{
		$this->form_validation->set_message('_mail_check', $this->lang->line('already_used'));
		return ($this->accounts->getByMail($mail) === false);
	}

	public function _register()
	{
		//$this->output->enable_profiler(TRUE);
		
		$this->session->set_userdata(array('user.logged' => false,
												   'user.id' => 0,
												   'user.rank' => 0));
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		
		$this->form_validation->set_rules('mail', 'lang:mail',
			'trim|required|valid_email|xss_clean|callback__mail_check');
		$this->form_validation->set_rules('password', 'lang:password',
			'trim|required|min_length[5]');
		$this->form_validation->set_rules('password_conf', 'lang:password_conf',
			'trim|required|matches[password]');
		
		$register = false;
		$data['register_error'] = '';
		
		
		if($this->form_validation->run() !== FALSE) {
			
			$this->load->model('ranks');
			
			// save the new account
			$id = $this->accounts->add($this->input->post('mail'),
									$this->input->post('password'),
									$this->ranks->grMember());
						
			if ($id === false) {
				$data['error'] = 'Erreur lors de l\'enregistrement !';
			} else {
				$register = true;
				
				
				$this->session->set_userdata(array('user.logged' => true,
												   'user.id' => $id,
												   'user.rank' => $this->ranks->grMember()));
			}
		}
		
		
		if (!$register) {			
			$data['header'] = '';
			$data['post_url'] = 'account/register';
			$data['header_title'] = 'Créer un compte';
			$data['header_previous_link'] = site_url('');
			$data['page'] = $this->load->view('account/register_view', $data, true);
			
			$this->load->view('theme/layout', $data);
		}
		else
		{
			$this->load->helper('url');
			redirect('/patient/wiz_create');
		}
		
	}
	
	public function _deconnect()
	{
		$this->session->unset_userdata('user.id');
		$this->session->unset_userdata('user.rank');
		$this->session->unset_userdata('user.name');
		$this->session->unset_userdata('user.fname');
		
		$this->session->set_userdata('user.logged', false);	
		
		// TODO : load view to say 'thank'
	}
	
	
	/*
	 * Choose a day
	 * generate a calendar
	 */
	public function choose($year = 0, $month = 0) {
		$first_free_slot = $this->slots->getFirstFreeSlot();
		$first_start = new DateTime($first_free_slot[$this->slots->gfDate()],
									$this->slots->getDateTimeZone());
		
		$year = intval(($year == 0) ? $first_start->format('Y') : $year);
		$month = intval(($month == 0) ? $first_start->format('m') : $month);
		
		$prefs = array (
               'start_day'    => 'monday',
               'month_type'   => 'long',
               'day_type'     => 'short',
               'show_next_prev'  => TRUE,
               'next_prev_url'   => '/slot/choose'
             );

		$this->load->library('calendar', $prefs);
		
		$cal_data = $this->slots->getAvailableDaysByMonth($year, $month, '/slot/chooseh/');
		$data['calendar'] = $this->calendar->generate($year, $month, $cal_data);
		
		$data['header_title'] = 'Choisir un jour';
		$data['url_first'] = '/slot/choose';
		
		
		$data['page'] = $this->load->view('slot/choose_view', $data, true);
		$data['header_url_home'] = '/dashboard';
		$this->load->view('theme/layout', $data);
	}
	
	
	/** choose a hour
	 * in a specific day
	 */
	public function chooseh($id_day = 0) {
		$data['slots'] = array();
		
		$year = intval($id_day / 1000);
		$month = intval((($id_day % 1000)-1) / 31);
		
		$data['b_url'] = '/slot/choosem/';
			
		if ($id_day !== 0) {
			$id_day = intval($id_day);
			$data['slots'] = $this->slots->getFreesByIdDay($id_day);
		}
		
		$day = new DateTimeFrench($data['slots'][0][$this->slots->gfDate()],
											$this->slots->getDateTimeZone());
		$day->setTime(0,0,0);
		$data['day'] = $day;
		
		$data['page'] = $this->load->view('slot/chooseh_view', $data, true);
		$data['header_url_home'] = '/dashboard';
		$data['header_url_back'] = '/slot/choose/'.$year.'/'.$month ;
		$data['header_title'] = 'Horaire';
		$this->load->view('theme/layout', $data);
	}
	
	
	
	/**
	 * Choose member
	 */
	public function choosem($id_slot = 0) {
		$data['members'] = array();
		
		$data['post_url'] 		= '/slot/take/'.$id_slot.'/';
		$data['form_post_url'] 	= '/slot/choosem/'.$id_slot.'/';
		$data['create_error'] = '';
		
		if ($id_slot !== 0) {
			$id_slot = intval($id_slot);
			
			$slot = $this->slots->getById($id_slot);
			
			if ($slot !== false) {
				$data['date'] = new DateTimeFrench($slot[$this->slots->gfDate()],
													$this->slots->getDateTimeZone());
				
				$this->load->model('patients_model', 'patients');
				$id_account = $this->session->userdata('user.id');
				$data['members'] = $this->patients->getAllByIdAccount($id_account);
				// check if members have already taken slot
				$c = count($data['members']);
				for($i=0;$i<$c;++$i) {
					$data['members'][$i]['enabled'] = 
						! $this->slots->hasRdvByIdPatient($data['members'][$i]['id']);
				}
				
				usort($data['members'], '_sortMembers');
				//var_dump($data['members']);
				
				$data['name'] = '';
				$data['phone'] = '';
				
				if (count($data['members']) > 0) {
					$data['name'] = $data['members'][0][$this->patients->gfName()];
					$data['phone'] = $data['members'][0][$this->patients->gfPhone()];	
				}
				
				// form new member				
				if (($this->input->post('submit'))) {
				
					$this->load->library('form_validation');
				
					$this->form_validation->set_rules('name', 'lang:name',
						'trim|required|xss_clean');
					$this->form_validation->set_rules('fname', 'lang:fname',
						'trim|required|xss_clean');
					$this->form_validation->set_rules('phone', 'lang:phone',
						'trim|required|numeric|min_length[10]|xss_clean');
					
					$validated = false;
					
					$id_patient = 0;
					
					if($this->form_validation->run() !== FALSE) {
						
						$id_account = $this->session->userdata('user.id');
						
						if ($id_account > 0) {
							if (($id_patient = $this->patients->add(
												$this->input->post('name'),
												$this->input->post('fname'),
												$phone = $this->input->post('phone'),
												$id_account)) > 0) {
								$validated = true;
							}
						} 
						
						if (!$validated)
							$data['create_error'] = 'Erreur lors de la création, veuillez vous reconnectez !';
					}
					
					if ($validated) {			
						redirect( $data['post_url'].$id_patient );
					} else {
					
					}
				}
				// end form
				
			} else {
				show_error('Cet identifiant n\'existe pas !');
			}
		}
		
		$this->load->helper('form');
		$data['page'] = $this->load->view('slot/choosem_view', $data, true);
		$data['header_url_home'] = '/dashboard';
		$data['header_title'] = 'Membre';
		$data['header_url_back'] = '/slot/chooseh/'. intval($id_slot / 10000);
		$this->load->view('theme/layout', $data);
	}
	
	
	
	
	
	
	
	
	
	
	
	public function take($id_slot = 0, $id_patient = 0) {
		if ($id_slot === 0)
			show_error('Paramètre manquant');
		
		$id_patient = intval($id_patient);
		
		
		$id_slot = intval($id_slot);
		
		$taken = false;
		
		$slot = $this->slots->getById($id_slot);
		$data['s_start'] = new DateTimeFrench($slot[$this->slots->gfDate()],
								$this->slots->getDateTimeZone());
		
		$this->load->model('patients_model', 'patients');
		$patient = $this->patients->getById($id_patient);
		$data['p_fname'] = $patient[$this->patients->gfFName()];
		$data['p_name']  = $patient[$this->patients->gfName()];
		
		if ($this->slots->hasRdvByIdPatient($id_patient)) {
			show_error('Un rendez-vous maximum par personne');
		} else {	
			if ($slot[$this->slots->gfIdPatient()] == NULL) {
				
				$this->slots->takeRdv($id_slot, $id_patient);
				$taken = true;
				
				$this->load->model('accounts_model', 'accounts');
				
				$id_patient = $slot[$this->slots->gfIdPatient()];
				$patient = $this->patients->getById($id_patient);
				$id_account = $patient[$this->patients->gfIdAccount()];
				$account = $this->accounts->getById($id_account);
				
				// send mail
				$this->load->library('email');
				$this->email->from($this->config->item('mail.from.mail'),
								   $this->config->item('mail.from.name'));
				$this->email->to($account[$this->accounts->gfMail()]);
				
				$this->email->subject('Votre rendez-vous');
				$this->email->message(
					'Vous avez rendez-vous le '.$data['s_start']->format('l j F Y \à H:m')
					.'
					 pour ' .$patient[$this->patients->gfFName()]. ' '.
					 $patient[$this->patients->gfName()].
					' Informations pratiques disponibles ici :
					'.site_url('')
					);
				
				$this->email->send();
				
			} else {
				$taken = false;
			}
		}
		
		// compute id_day
		$data['taken'] = $taken;
		$data['url_prev_day'] = '/slot/chooseh/'.  (intval($id_slot / 10000) );
		$data['url_end'] = '/dashboard';
		$data['page'] = $this->load->view('slot/take_view', $data, true);
		$data['header_url_home'] = '/dashboard';
		$data['header_title'] = 'Résumé';
		$this->load->view('theme/layout', $data);
	}
	
}

/* End of file session.php */
/* Location: ./application/controllers/session.php */
 
