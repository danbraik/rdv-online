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

/*
 */ 
class Dashboard extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$data['header_title'] = 'Tableau de bord';
		
		$data['link_manage_patients'] 	= '/managep';
		$data['link_manage_account'] 	= '/managea';
		$data['link_take_slot'] 		= '/slot/choose';
		$data['link_to_patient_edit'] 	= '/patient/show';
		$data['burl_cancel'] 			= '/dashboard/cancel/';
		
		$id_account = $this->session->userdata('user.id');
		
		$this->load->model('slots_model', 'slots');
		$data['next_slots'] = $this->slots->getByAccount($id_account);
		
		$c = count($data['next_slots']);
		for($i=0;$i<$c;++$i) {
			$data['next_slots'][$i]['start'] = new DateTimeFrench(
				$data['next_slots'][$i][$this->slots->gfDate()], $this->slots->getDateTimeZone());
			unset($data['next_slots'][$this->slots->gfDate()]);
		}
		
		
		$data['page'] = $this->load->view('dashboard/home_view', $data, true);
		$data['header_url_disconnect'] = '/account/disconnect';
		$this->load->view('theme/layout', $data);
	}
	
	public function cancel($id_slot) {
		$id_slot = intval($id_slot);
		
		if ($id_slot > 0) {
			
			$data['url_cancel'] = '/dashboard/fcancel/'.$id_slot;
			$data['url_keep'] = '/dashboard';
			$data['page'] = $this->load->view('dashboard/cancel_view', $data, true);
		
			$this->load->view('theme/layout', $data);
			
		} else {
			show_error('Identifiant invalide');
		}
		
	}
	
	public function fcancel($id_slot) {
		$id_slot = intval($id_slot);
		
		if ($id_slot > 0) {
			$this->load->model('slots_model', 'slots');
			$this->load->model('patients_model', 'patients');
			$this->load->model('accounts_model', 'accounts');
			
			$slot = $this->slots->getById($id_slot);
			$patient = $this->patients->getById($slot[$this->slots->gfIdPatient()]);
			
			// check if user has right to cancel slot
			if ($patient[$this->patients->gfIdAccount()] == 
					$this->session->userdata['user.id']) {
				$this->slots->freeRdv($id_slot);
				
				
				$id_patient = $slot[$this->slots->gfIdPatient()];
				$patient = $this->patients->getById($id_patient);
				$id_account = $patient[$this->patients->gfIdAccount()];
				$account = $this->accounts->getById($id_account);
				
				// send mail
				$this->load->library('email');
				$this->email->from($this->config->item('mail.from.mail'),
								   $this->config->item('mail.from.name'));
				$this->email->to($account[$this->accounts->gfMail()]);
				
				$this->email->subject('Votre rendez-vous annulé');
				$this->email->message(
					'Le rendez-vous du '.date_create($slot[$this->slots->gfDate()] ,
						$this->slots->getDateTimeZone())->format('l j F Y \à H:m').
					' pour '.$patient[$this->patients->gfFName()]. ' '.
					 $patient[$this->patients->gfName()].
					' a effectivement été annulé. 
					
					'
					);
				
				$this->email->send();
				
				
			} else {
				show_error('Vous n\'avez pas le droit !');
			}
			
			redirect('/dashboard');
		} else {
			show_error('Identifiant invalide');
		}
		
	}
}

/* End of file session.php */
/* Location: ./application/controllers/session.php */
 
