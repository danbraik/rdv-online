<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Controller of accounts
 * An account is a secure group of patient
 * 
 */ 
class Account extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('accounts_model', 'accounts');
	}
	
	public function index()
	{
		$this->connect();
	}
	
	public function connect()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$data['title'] = 'Create a news item';
		$data['url_forgotten'] = '/account/forgotten';
		
		$this->form_validation->set_rules('mail', 'lang:mail',
			'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('password', 'lang:password',
			'required|min_length[5]');
		
		
		$connected = false;
		$data['connect_error'] = '';
		
		
		if($this->form_validation->run() !== FALSE) {
			
			$account = $this->accounts->getByMail($this->input->post('mail'));
			
			// Set error message for the most of next cases
			$correct_account = false;
			
			if ($account !== false)
			{
				if ($this->accounts->comparePasswords($this->input->post('password'),
													  $account[$this->accounts->gfPassword()])) {
					$connected = true;
					
					// connect user and save some data
					$this->session->set_userdata(array('user.logged' => true,
												   'user.id' => $account[$this->accounts->gfId()],
												   'user.rank' => $account[$this->accounts->gfRank()],
												   ));
				} else {
					$connected = false;
					$this->session->set_userdata(array('user.logged' => false,
												   'user.id' => 0,
												   'user.rank' => 0,
												   ));
				}
			}
			
			if (!$connected)
				$data['connect_error']  = $this->lang->line('wrong_account');
						
		}
		
		if (!$connected) {
			$data['header'] = '';
			$data['header_title'] = 'Se connecter';
			$data['header_previous_link'] = site_url('');
			$data['post_url'] = '/account/connect';
			
			$data['page'] = $this->load->view('account/connect_view', $data, true);
			$data['header_url_back'] = '/';
			$this->load->view('theme/layout', $data);
		} else {
			$this->load->model('ranks');
			
			if ($this->session->userdata('user.rank') >= $this->ranks->grSecretary())
				redirect('/boss');
			else
				redirect('/dashboard');
		}
	}


	public function _mail_check($mail)
	{
		$this->form_validation->set_message('_mail_check', $this->lang->line('already_used'));
		return ($this->accounts->getByMail($mail) === false);
	}

	public function register()
	{
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
			$data['post_url'] = '/account/register';
			$data['header_title'] = 'Créer un compte';
			$data['header_previous_link'] = site_url('');
			
			$data['page'] = $this->load->view('account/register_view', $data, true);
			$data['header_url_back'] = '/';
			$this->load->view('theme/layout', $data);
		}
		else
		{
			$this->load->helper('url');
			//redirect('/dashboard');
			redirect('/patient/wiz_create');
		}
		
	}
	
	public function disconnect()
	{
		$this->session->unset_userdata('user.id');
		$this->session->unset_userdata('user.rank');
		$this->session->unset_userdata('user.name');
		$this->session->unset_userdata('user.fname');
		
		$this->session->set_userdata('user.logged', false);	
		
		redirect('/');
	}
	
	
		
	public function forgotten() {
		
		
		$this->session->set_userdata(array('user.logged' => false,
												   'user.id' => 0,
												   'user.rank' => 0));
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		
		$this->form_validation->set_rules('mail', 'lang:mail',
			'trim|required|valid_email|xss_clean');
		
		$send = false;
		$data['error'] = '';
		
		
		if($this->form_validation->run() !== FALSE) {
			
			// save the new account
			$account = $this->accounts->getByMail($this->input->post('mail'));
						
			if ($account === false) {
				$data['error'] = 'Aucun compte n\'a été associé à ce mail.';
			} else {
				
				mt_srand((double)microtime()*1000000);
				
				
				$this->load->library('email');
				$this->email->from($this->config->item('mail.from.mail'),
								   $this->config->item('mail.from.name'));
				$this->email->to($account[$this->accounts->gfMail()]);
				
				$this->email->subject('Générer un nouveau mot de passe');
				$this->email->message(
					'Vous avez demandé à générer un nouveau mot de passe.
					Si ce n\'est pas le cas, ignorez simplement ce message.
					
					Sinon, cliquez sur ce lien ou recopiez le dans la barre d\'adresse.
					
					
					'. site_url('/account/genpass/'.
						
						bin2hex(rand()%10) .'/'.
						bin2hex(strval(rand())%100).'/'.
						strval(rand(11,99)).'/'.
						$account['id'].'/'.
						bin2hex(strval(rand()%600)).'/'.
						strval(rand()%1000)
						)
					
					);
				
				if (!$this->email->send()) {
					$data['error'] = 'Erreur lors de l\'envoi du mail';			
				} else {
					$send = true;
				}
			}
		}
		
		
		if (!$send) {			
			$data['header'] = '';
			$data['url_form_post'] = '/account/forgotten';
			
			
			$data['url_back'] = '/';
			
			$data['page'] = $this->load->view('account/forgotten_view', $data, true);
			$data['header_url_back'] = '/account/connect';
			$data['header_title'] = 'Oubli';
			$data['header_url_home'] = '/';
			$this->load->view('theme/layout', $data);
		}
		else
		{
			$data['url_back'] = '/account/forgotten';
			
			$data['page'] = $this->load->view('account/forgotten_ok_view', $data, true);
			$data['header_title'] = 'Résumé';
			$data['header_url_home'] = '/';
			$this->load->view('theme/layout', $data);
		}
	}
	
	
	/*
	 * Generate new password
	 */
	public function genpass($a, $b, $c, $id_account, $d, $e) {
		
		$account = $this->accounts->getById($id_account);
		
		$data['error'] = '';
		$data['url_next'] = '/account/connect';
		
		if ($account) {
			


			mt_srand((double)microtime()*1000000);
			
			// generate new password
			$list = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabc';
			$newPassword = '';
			while(strlen($newPassword) < 6 ) {
				$newPassword .= $list[mt_rand(0, strlen($list)-1)];
			}
			
			
			$this->accounts->updatePassword($id_account, $newPassword);
			
			$this->load->library('email');
			$this->email->from($this->config->item('mail.from.mail'),
							   $this->config->item('mail.from.name'));
			$this->email->to($account[$this->accounts->gfMail()]);
			
			$this->email->subject('Nouveau mot de passe');
			$this->email->message(
				'Voici votre nouveau mot de passe :'
				.$newPassword
				.' Il est conseillé de le changez rapidement.'
				);
			
			if (!$this->email->send()) {
				$data['error'] = 'Erreur lors de l\'envoi du mail';
			} else {
				$send = true;
			}
		}
		
		$data['page'] = $this->load->view('account/genpass_view', $data, true);
		$this->load->view('theme/layout', $data);
	}
	
	
}

/* End of file session.php */
/* Location: ./application/controllers/session.php */
 
