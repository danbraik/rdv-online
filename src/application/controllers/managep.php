<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Manage patients from dashboard
 */ 
class Managep extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		//$this->output->enable_profiler(TRUE);
	}
	
	public function index()
	{
		
		
		$data['url_back'] = '/dashboard';
		$data['url_add_patient'] = '/managep/add';
		$data['burl_modify'] = '/managep/modify/';
		$data['burl_remove'] = '/managep/remove/';
		
		
		$id_account = $this->session->userdata('user.id');
		
		$this->load->model('patients_model', 'patients');
		$data['members'] = $this->patients->getAllByIdAccount($id_account);
		
		
		$data['page'] = $this->load->view('managep/home_view', $data, true);
		$data['header_url_home'] = '/dashboard';
		$data['header_title'] = 'Membres';
		$this->load->view('theme/layout', $data);
	}
	
	
	public function remove($id_patient, $force = 'false') {
		$id_patient = intval($id_patient);
		
		if ($id_patient > 0) {
			$this->load->model('slots_model', 'slots');
			$this->load->model('patients_model', 'patients');
			
			
			$patient = $this->patients->getById($id_patient);
			
			// check if user has right to cancel slot
			if ($patient[$this->patients->gfIdAccount()] == 
					$this->session->userdata['user.id']) {
				$toRemove = false;
				// check if patient has some rdv
				if ($this->slots->hasRdvByIdPatient($id_patient)) {
					if ($force == 'false') {
						// view to be sure
						$data['url_rm_yes'] = '/managep/remove/'.$id_patient.'/true';
						$data['url_rm_no'] = '/managep';
						
						$data['page'] = $this->load->view('managep/remove_conf_view', $data, true);
						$data['header_title'] = 'Suppression';
						$this->load->view('theme/layout', $data);
					} else if ($force == 'true') {
						// remove all slots with this patient
						$this->slots->freeRdvsByIdPatient($id_patient);
						
						$this->load->model('accounts_model', 'accounts');
						
						$id_account = $patient[$this->patients->gfIdAccount()];
						$account = $this->accounts->getById($id_account);
						
						// send mail
						$this->load->library('email');
						$this->email->from($this->config->item('mail.from.mail'),
										   $this->config->item('mail.from.name'));
						$this->email->to($account[$this->accounts->gfMail()]);
						
						$this->email->subject('Suppression du membre');
						$this->email->message(
							'Tous les rendez-vous du membre '
							.$patient[$this->patients->gfFName].' '.
							.$patient[$this->patients->gfName].
							' ont bien étés annulés lors de
							la suppression du membre.
							Informations pratiques disponibles ici :
							'
							);
						$this->email->send();
						
						$toRemove = true;
					}
				} else {
					$toRemove = true;
				}
				
				if ($toRemove) {
					$this->patients->remove($id_patient);			
					redirect('/managep');
				}
				
			} else {
				show_error('Vous n\'avez pas le droit !');
			}
		} else {
			show_error('Identifiant invalide');
		}
	}
	
	
	public function add() {
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', 'lang:name',
			'trim|required|xss_clean');
		$this->form_validation->set_rules('fname', 'lang:fname',
			'trim|required|xss_clean');
		$this->form_validation->set_rules('phone', 'lang:phone',
			'trim|required|numeric|min_length[10]|xss_clean');
		
		$validated = false;
		$data['create_error'] = '';
		
		$this->load->model('patients_model', 'patients');
		$id_account = $this->session->userdata('user.id');
		
		if($this->form_validation->run() !== FALSE) {
			if ($id_account > 0) {
				if ($this->patients->add($this->input->post('name'),
										 $this->input->post('fname'),
										 $this->input->post('phone'),
										 $id_account) > 0) {
					$validated = true;
				}
			} 
			
			if (!$validated)
				$data['create_error'] = 'Erreur lors de la création, veuillez vous reconnectez !';
		} else {
			$members = $this->patients->getAllByIdAccount($id_account);
				
			$data['fname'] = '';
			$data['name'] = '';
			$data['phone'] = '';
			
			if (count($members) > 0) {
				$data['name'] = $members[0][$this->patients->gfName()];
				$data['phone'] = $members[0][$this->patients->gfPhone()];	
			}
		}
		
		if (!$validated) {
			$data['url_form_post'] = '/managep/add';
			$data['page'] = $this->load->view('managep/add_view', $data, true);
			
			$data['header_url_back'] = '/managep';
			$data['header_title'] = 'Ajouter';
			$data['header_url_home'] = '/dashboard';
			$this->load->view('theme/layout', $data);
		} else {
			redirect('/managep');
		}
		
	}
	
	
	public function modify($id_patient) {
		$id_patient = intval($id_patient);
		
		$this->load->model('patients_model', 'patients');
		$member = $this->patients->getById($id_patient);
		$id_account = $this->session->userdata('user.id');
		
		if ($member === NULL) {
			show_error('Identifiant invalide');
		} else if ($member[$this->patients->gfIdAccount()] != $id_account) {
			show_error('Vous n\'avez pas le droit de modifier ce membre !');
		} else {
			
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('name', 'lang:name',
				'trim|required|xss_clean');
			$this->form_validation->set_rules('fname', 'lang:fname',
				'trim|required|xss_clean');
			$this->form_validation->set_rules('phone', 'lang:phone',
				'trim|required|numeric|min_length[10]|xss_clean');
			
			$validated = false;
			$data['create_error'] = '';
			$data['fname'] = 	$member[$this->patients->gfFName()];
			$data['name']  = 	$member[$this->patients->gfName()];
			$data['phone'] = 	$member[$this->patients->gfPhone()];
			
			if($this->form_validation->run() !== FALSE) {
				if ($id_account > 0) {
					if ($this->patients->updateFNP($id_patient,
								$this->input->post('name'),
								$this->input->post('fname'),
								$this->input->post('phone'))) {
						$validated = true;
					}
				} 
				
				if (!$validated)
					$data['create_error'] = 'Erreur lors de la création, veuillez vous reconnectez !';
			}
			
			if (!$validated) {
				$data['url_form_post'] = '/managep/modify/'.$id_patient;
				
				// we really use add_view
				$data['page'] = $this->load->view('managep/add_view', $data, true);
				$data['header_url_back'] = '/managep';
				$data['header_title'] = 'Modifier';
				$data['header_url_home'] = '/dashboard';
				$this->load->view('theme/layout', $data);
			} else {
				redirect('/managep');
			}
		
		}
		
	}
}

/* End of file session.php */
/* Location: ./application/controllers/session.php */
 
