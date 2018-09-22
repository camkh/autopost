<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Socialaccounts extends CI_Controller {
	protected $mod_general;
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'Mod_general' );
		$this->load->library ( 'dbtable' );
		$this->load->theme ( 'layout' );
		$this->mod_general = new Mod_general ();
		$this->mod_general->checkUser ();
		$this->load->library ( array (
				'session',
				'lib_login'
		) );
	}
	public function index() {
		$log_id = $this->session->userdata ( 'user_id' );
		$user = $this->session->userdata ( 'email' );
		$provider_uid = $this->session->userdata ( 'provider_uid' );
		$provider = $this->session->userdata ( 'provider' );
		$this->load->theme ( 'layout' );
		$data ['title'] = 'Movies list';
		$data ['addJsScript'] = array (
				"$('#checkAll').click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });
 $('#multidel').click(function () {
     if (!$('#itemid:checked').val()) {
            alert('please select one');
            return false;
    } else {
            return confirm('Do you want to delete all?');
    }
 });" 
		);
		// $backto = base_url() . 'post/blogpassword';
		// $query_blog = $this->Mod_general->blogcheck(current_url(), $backto);
		$provider = str_replace ( 'facebook', 'Facebook', $provider );
		$where_so = array (
				Tbl_social::s_type => $provider,
				Tbl_social::u_id => $log_id 
		);
		$this->load->library ( 'pagination' );
		$per_page = (! empty ( $_GET ['result'] )) ? $_GET ['result'] : 10;
		$config ['base_url'] = base_url () . 'post/bloglist/';
		$count_blog = $this->Mod_general->select ( Tbl_social::tblName, '*', $where_so );
		$config ['total_rows'] = count ( $count_blog );
		$config ['per_page'] = $per_page;
		$config ['cur_tag_open'] = '<li class="active"><a>';
		$config ['cur_tag_close'] = '</a></li>';
		$config ['num_tag_open'] = '<li>';
		$config ['num_tag_close'] = '</li>';
		$config ['next_tag_open'] = '<li>';
		$config ['next_tag_close'] = '</li>';
		$config ['prev_tag_open'] = '<li>';
		$config ['prev_tag_close'] = '</li>';
		$page = ($this->uri->segment ( 3 )) ? $this->uri->segment ( 3 ) : 0;
		
		$i = 0;
		$query_blog = array ();
		$dataSocial = array ();
		if (empty ( $filtername )) {
			$query_blog = $this->Mod_general->select ( Tbl_social::tblName, '*', $where_so, "s_id DESC", '', $config ['per_page'], $page );
			if (! empty ( $query_blog )) {
				foreach ( $query_blog as $social ) {
					$i ++;
					$dataSocial [$i] [Tbl_social::s_id] = $social->{Tbl_social::s_id};
					$dataSocial [$i] [Tbl_social::s_name] = $social->{Tbl_social::s_name};
					$dataSocial [$i] [Tbl_social::s_image] = $social->{Tbl_social::s_image};
					$dataSocial [$i] [Tbl_social::s_social_id] = $social->{Tbl_social::s_social_id};
					$dataSocial [$i] [Tbl_social::s_type] = $social->{Tbl_social::s_type};
					$dataSocial [$i] [Tbl_social::u_id] = $social->{Tbl_social::u_id};
					$dataSocial [$i] [Tbl_social::s_access_token] = $social->{Tbl_social::s_access_token};
					$dataSocial [$i] [Tbl_social::s_status] = $social->{Tbl_social::s_status};
					$dataSocial [$i] [Tbl_social::s_email] = $social->{Tbl_social::s_email};
					$dataCountGroups = $this->mod_general->count ( Tbl_social_group::tblName, array (
							Tbl_social_group::socail_id => $social->{Tbl_social::s_id} 
					) );
					// var_dump($dataCountGroups);
					$dataSocial [$i] ['group'] = $dataCountGroups;
				}
			}
		}
		$object = json_decode ( json_encode ( $dataSocial ), FALSE );
		
		$data ['socialList'] = $object;
		
		$config ["uri_segment"] = 3;
		$this->pagination->initialize ( $config );
		$data ["total_rows"] = count ( $count_blog );
		$data ["results"] = $query_blog;
		$data ["links"] = $this->pagination->create_links ();
		/* end get pagination */
		
		$log_id = $this->session->userdata ( 'log_id' );
		$user = $this->session->userdata ( 'username' );
		$this->load->view ( 'socialaccounts/index', $data );
	}
	public function logintosocial() {
		$this->load->library('facebook');
		$fbData = $this->lib_login->facebook ();
		try {
			if ($this->facebook->logged_in()) {
				var_dump($userID);
				die;
				$data_sel = array (
						Tbl_social::s_type => $provider,
						Tbl_social::s_social_id => $user_profile->identifier,
						Tbl_social::u_id => $this->session->userdata ( 'user_id' ) 
				);
				$checkuser = $this->Mod_general->select ( Tbl_social::tblName, '*', $data_sel );
				if (! empty ( $checkuser [0]->s_social_id )) {
					/* ++++++++++ CHECK USER EXSIT ++++++++++ */
					// $this->session->set_userdata('user_id', $checkuser[0]->user_id);
					// $this->session->set_userdata('email', $checkuser[0]->email);
					// $this->session->set_userdata('provider_uid', $checkuser[0]->provider_uid);
					// $this->session->set_userdata('provider', $checkuser[0]->provider);
					/* ++++++++++ END CHECK USER EXSIT ++++++++++ */
				} else {
					/* ++++++++++ USER NOT EXSIT ++++++++++ */
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
					$password = rand (); // for the password we generate something random
					$DataAdd = array (
							Tbl_social::s_name => $display_name,
							Tbl_social::s_email => $email,
							Tbl_social::s_image => $user_profile->photoURL,
							Tbl_social::s_type => $provider,
							Tbl_social::u_id => $this->session->userdata ( 'user_id' ),
							Tbl_social::s_social_id => $provider_uid,
							Tbl_social::s_access_token => $getAccessToken ['access_token'] 
					);
					$AddUser = $this->Mod_general->insert ( Tbl_social::tblName, $DataAdd );
					// $this->session->set_userdata('user_id', $AddUser);
					// $this->session->set_userdata('email', $email);
					// $this->session->set_userdata('provider_uid', $provider_uid);
					// $this->session->set_userdata('provider', $provider);
					/* ++++++++++ END USER NOT EXSIT ++++++++++ */
					
					/* add group */
					if ($provider == 'Facebook' || $provider == 'facebook') {
						$queries = array (
								array (
										'method' => 'GET',
										'relative_url' => '/' . $provider_uid 
								),
								array (
										'method' => 'GET',
										'relative_url' => '/' . $provider_uid . '/groups?limit=5000' 
								),
								array (
										'method' => 'GET',
										'relative_url' => '/' . $provider_uid . '/likes?limit=5000' 
								) 
						);
						$batchResponse = $service->getUserGroup ( $queries );
						// Return values are indexed in order of the original array, content is in ['body'] as a JSON
						// string. Decode for use as a PHP array.
						$groups = json_decode ( $batchResponse [1] ['body'], true );
						$pages = json_decode ( $batchResponse [2] ['body'], true );
						if (! empty ( $groups )) {
							$this->addsocialGroup ( $groups, 'groups', $AddUser );
						}
						if (! empty ( $pages )) {
							$this->addsocialGroup ( $pages, 'pages', $AddUser );
						}
					}
					/* end add group */
				}
				$set_url = base_url () . 'socialaccounts';
				header ( "Location: " . $set_url );
				exit ();
				
				// $this->load->view('hauth/done',$data);
			} else {
				/*if not login to FB account*/
				echo '<a href="' . $fbData ['loginUrl'] . '">Login</a>';
				/*end if not login to FB account*/
			}
		} catch ( exception $e ) {
			$error = 'Unexpected error';
		}
	}
	function addsocialGroup($data, $type, $uid) {
		$i = 0;
		foreach ( $data ['data'] as $group ) {
			$name = $group ['name'];
			$id = $group ['id'];
			$unread = $group ['unread'];
			$data_ch_group = array (
					Tbl_social_group::page_id => $id,
					Tbl_social_group::socail_id => $uid 
			);
			/* Add group for each user */
			$checkGroup = $this->Mod_general->select ( Tbl_social_group::tblName, '*', $data_ch_group );
			if (empty ( $checkGroup )) {
				$DataGoupAdd = array (
						Tbl_social_group::page_id => $id,
						Tbl_social_group::socail_id => $uid,
						Tbl_social_group::name => $name,
						Tbl_social_group::member => $unread,
						Tbl_social_group::type => $type 
				);
				$AddGroup = $this->Mod_general->insert ( Tbl_social_group::tblName, $DataGoupAdd );
			}
			/* end Add group for each user */
		}
	}
	public function logoutall() {
		$this->load->library ( array (
				'session',
				'lib_login' 
		) );
		$this->load->library ( 'facebook' );
		if ($this->facebook->logged_in ()) {
			$this->facebook->destroy_session ();
			redirect ( base_url () . 'login/facebook' );
		} else {
			redirect ( base_url () . 'socialaccounts/logintosocial' );
		}
	}
	public function delete() {
		$actions = $this->uri->segment ( 3 );
		$id = $this->uri->segment ( 4 );
		switch ($actions) {
			case "deletesocial" :
				$this->Mod_general->delete ( Tbl_social::tblName, array (
						Tbl_social::s_id => $id 
				) );
				redirect ( 'socialaccounts', 'location' );
				break;
		}
	}
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
