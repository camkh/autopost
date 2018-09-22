<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class HAuth extends CI_Controller {
    public function __construct() {
        parent::__construct();
		$this->load->model('Mod_general');
        $this->load->library('dbtable');
        $this->load->theme('layout');
    }
	public function index()
	{
        $this->load->theme('layout');
        $data['title'] = 'User login';
        $data['css'] = array(
            'themes/layout/blueone/assets/css/login',
            'themes/layout/blueone/assets/css/responsive',
            'themes/layout/blueone/assets/css/plugins',
            'themes/layout/blueone/assets/css/icons',
            'themes/layout/blueone/bootstrap/css/bootstrap.min',
            'themes/layout/blueone/assets/css/main',
        );
        $data['addJsScript'] = array(
            "if('ontouchend' in document) document.write('<script src='assets/js/jquery.mobile.custom.min.js'>'+'<'+'/script>');
",
            "function show_box(id) {
			 jQuery('.widget-box.visible').removeClass('visible');
			 jQuery('#'+id).addClass('visible');
			}
"
        );
        $data['bodyClass'] = 'login'; 
		$backto = !empty($_GET['backto'])?$_GET['backto']:'';
		if(!empty($backto)) {
			$this->session->set_userdata('return_to', $backto);
		}
		$this->load->view('hauth/home',$data);
	}

	public function login($provider)
	{
		log_message('debug', "controllers.HAuth.login($provider) called");

		try
		{
			log_message('debug', 'controllers.HAuth.login: loading HybridAuthLib');
			$this->load->library('HybridAuthLib');

			if ($this->hybridauthlib->providerEnabled($provider))
			{
				log_message('debug', "controllers.HAuth.login: service $provider enabled, trying to authenticate.");
				$service = $this->hybridauthlib->authenticate($provider);
				if ($service->isUserConnected())
				{
				    $getAccessToken = $service->getAccessToken();
                    if(!empty($getAccessToken)) {
                        $this->session->set_userdata('access_token', $getAccessToken['access_token']);
                    }
					log_message('debug', 'controller.HAuth.login: user authenticated.');

					$user_profile = $service->getUserProfile();
					log_message('info', 'controllers.HAuth.login: user profile:'.PHP_EOL.print_r($user_profile, TRUE));

					$data['user_profile'] = $user_profile;
					
					 $data_sel = array(
						'provider' => $provider,
						'provider_uid' => $user_profile->identifier,
					);
            		$checkuser = $this->Mod_general->select2('authentications', '*', $data_sel,'', '','1');
					if(!empty($checkuser[0]->email)) {
					   $uid = $checkuser[0]->user_id;
					   $display_name = $checkuser[0]->display_name;
					   $provider_uid = $user_profile->identifier;
						/*++++++++++ CHECK USER EXSIT ++++++++++*/
						$this->session->set_userdata('user_id', $checkuser[0]->user_id);
						$this->session->set_userdata('email', $user_profile->email);
                        $this->session->set_userdata('provider_uid', $user_profile->identifier);
                        $this->session->set_userdata('provider', $checkuser[0]->provider);
						/*++++++++++ END CHECK USER EXSIT ++++++++++*/
					} else {
						/*++++++++++ USER NOT EXSIT ++++++++++*/
						$provider_uid = $user_profile->identifier;
						$email = $user_profile->email;
						$first_name = $user_profile->firstName;
						$last_name = $user_profile->lastName;
						$display_name = $user_profile->displayName;
						$profile_url = $user_profile->profileURL;
						$website_url = $user_profile->webSiteURL;
						$birthDay = $user_profile->birthDay;
						$birthMonth = $user_profile->birthMonth;
						$birthYear = $user_profile->birthYear;
						$age = $user_profile->age;
						$password = rand(); # for the password we generate something random
						$DataAdd = array(
							'email' => $email,
							'password' => $password,
							'first_name' => $first_name,
							'last_name' => $last_name,
							'created_at' => date("Y-m-d H:i:s")
						);
						$AddUser = $this->Mod_general->insert2('users', $DataAdd);
						$DataAddAuth = array(
							'user_id' => $AddUser,
							'provider' => $provider,
							'provider_uid' => $provider_uid,
							'email' => $email,
							'display_name' => $display_name,
							'first_name' => $first_name,
							'last_name' => $last_name,
							'profile_url' => $profile_url,
							'website_url' => $website_url,
							'au_d_en' => $birthDay,
							'au_m_en' => $birthMonth,
							'au_y_en' => $birthYear,
							'au_age' => $age,
							'created_at' => date("Y-m-d H:i:s")
						);
						$AddUserAuth = $this->Mod_general->insert2('authentications', $DataAddAuth);
                        $uid = $AddUser;
						$this->session->set_userdata('user_id', $AddUser);
						$this->session->set_userdata('email', $email);
                        $this->session->set_userdata('provider_uid', $provider_uid);
                        $this->session->set_userdata('provider', $provider);
						/*++++++++++ END USER NOT EXSIT ++++++++++*/
					}
                    
                    $checkCurUser = $this->Mod_general->select(Tbl_user::tblUser, '*', array(Tbl_user::u_id=>$uid));
                    if(empty($checkCurUser)) {
                        $DataAddUser = array(
                            Tbl_user::u_id => $uid,
                            Tbl_user::u_email => $this->session->userdata('email'),
                            Tbl_user::u_name => $display_name,
                            Tbl_user::u_type => $provider,
                            Tbl_user::u_provider_uid => $provider_uid,
                            Tbl_user::u_dtime => date("Y-m-d H:i:s"),
                            Tbl_user::u_status => 1,
                        );
                        $AddUserAuth = $this->Mod_general->insert(Tbl_user::tblUser, $DataAddUser);
                        $this->session->set_userdata('user_id', $AddUserAuth);
                    } else {
                        $uid = $checkCurUser[0]->{Tbl_user::u_id};
                        $this->session->set_userdata('user_id', $uid);
                    }
					$return_to = $this->session->userdata('return_to');
					if (!empty($return_to)) {
						$set_url = $return_to;
					} else {
						$set_url = base_url();
					}
					header("Location: " . $set_url);
					$this->session->unset_userdata('return_to');
					exit();
					
					//$this->load->view('hauth/done',$data);
				}
				else // Cannot authenticate user
				{
					show_error('Cannot authenticate user');
				}
			}
			else // This service is not enabled.
			{
				log_message('error', 'controllers.HAuth.login: This provider is not enabled ('.$provider.')');
				show_404($_SERVER['REQUEST_URI']);
			}
		}
		catch(Exception $e)
		{
			$error = 'Unexpected error';
			switch($e->getCode())
			{
				case 0 : $error = 'Unspecified error.'; break;
				case 1 : $error = 'Hybriauth configuration error.'; break;
				case 2 : $error = 'Provider not properly configured.'; break;
				case 3 : $error = 'Unknown or disabled provider.'; break;
				case 4 : $error = 'Missing provider application credentials.'; break;
				case 5 : log_message('debug', 'controllers.HAuth.login: Authentification failed. The user has canceled the authentication or the provider refused the connection.');
				         //redirect();
				         if (isset($service))
				         {
				         	log_message('debug', 'controllers.HAuth.login: logging out from service.');
				         	$service->logout();
				         }
				         show_error('User has cancelled the authentication or the provider refused the connection.');
				         break;
				case 6 : $error = 'User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again.';
				         break;
				case 7 : $error = 'User not connected to the provider.';
				         break;
			}

			if (isset($service))
			{
				$service->logout();
			}

			log_message('error', 'controllers.HAuth.login: '.$error);
			show_error('Error authenticating user.');
		}
	}

	public function endpoint()
	{

		log_message('debug', 'controllers.HAuth.endpoint called.');
		log_message('info', 'controllers.HAuth.endpoint: $_REQUEST: '.print_r($_REQUEST, TRUE));

		if ($_SERVER['REQUEST_METHOD'] === 'GET')
		{
			log_message('debug', 'controllers.HAuth.endpoint: the request method is GET, copying REQUEST array into GET array.');
			$_GET = $_REQUEST;
		}

		log_message('debug', 'controllers.HAuth.endpoint: loading the original HybridAuth endpoint script.');
		require_once APPPATH.'/third_party/hybridauth/index.php';

	}
	public function logout() {
		$this->session->sess_destroy();
		redirect(base_url());
	}
}

/* End of file hauth.php */
/* Location: ./application/controllers/hauth.php */
