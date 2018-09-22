<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {
	protected $mod_general;
    public function __construct() {        
        parent::__construct();
        $this->load->model('Mod_general');
        $this->load->theme('layout');
        $this->mod_general = new Mod_general ();
        date_default_timezone_set('Asia/Phnom_Penh');
    }

    public function index() {
        if(!empty($_GET['id'])) {
            $ci = get_instance();
            $account_url = $ci->config->item('account_url');
            $getUrl = base_url();
            $contentUrl = $account_url.'/account/' . $_GET['id'];
            $json = file_get_contents($contentUrl);
            $obj = json_decode($json);
            if(empty($obj->error)) {
                if($obj->u_type=='normal') {
                    $u_type = 2;
                } else if($obj->u_type=='admin'){
                    $u_type = 1;
                }
                $this->session->set_userdata('email', $obj->email);
                $this->session->set_userdata('user_type', $u_type);
                $this->session->set_userdata('user_id', $obj->id);
                setcookie('rememberUsername', $obj->email, time() + (86400 * 30));
                $this->createUserLicence($obj->id);
                redirect(base_url() . 'home/index');
            } else {
                $ci = get_instance();
                $account_url = $ci->config->item('account_url');
                redirect($account_url . '?continue=' . urlencode(base_url()));
            }
        } else if(empty($_GET['id']) && $this->session->userdata('user_id')) {
            redirect(base_url() . 'home/index');
        } else {
            $ci = get_instance();
            $account_url = $ci->config->item('account_url');
            redirect($account_url . '?continue=' . urlencode(base_url()));
        }
        
        die;
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
        /* form */

        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            if ($this->input->post('username')) {
                $user = $this->input->post('username');
                $password = $this->input->post('password');
                $where = array('u_email' => $user, 'u_password' => md5($password),'u_status'=>1);
                $query = $this->mod_general->select('users','*',$where);
                if (count($query) > 0) {
                    foreach ($query as $row) {
                        $this->session->set_userdata('password', $password);
                        $this->session->set_userdata('email', $row->u_email);
                        $this->session->set_userdata('user_type', $row->u_type);
                        $this->session->set_userdata('user_id', $row->u_id);
                        redirect(base_url() . 'home/index');
                    }
                }
            }
        }
        $user = $this->session->userdata('email');
        if ($user) {
            redirect(base_url() . 'home');
        } else {
            //$this->load->view('login', $data);
            //redirect(base_url() . 'hauth');
        	$this->load->view('login', $data);
        }
    }

    public function login() {
        $this->load->theme('layout');
        $data['title'] = 'User login';
        $data['css'] = array(
            'themes/layout/blueone/assets/css/login',
            'themes/layout/blueone/assets/css/responsive',
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

        /* form */

        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            if ($this->input->post('username')) {
                $user = $this->input->post('username');
                $password = $this->input->post('password');
                $field = array(
                    'username',
                    'log_id',
                    'user_type'
                );
                $where = array(
                    'username' => $user, 
                    'password' => md5($password),
                    'user_status' => 1,
                    );
                $query = $this->Mod_general->getuser($field, $where);
                if (count($query) > 0) {
                    foreach ($query as $row) {
                        $this->session->set_userdata('username', $row->username);
                        $this->session->set_userdata('user_type', $row->user_type);
                        $this->session->set_userdata('log_id', $row->log_id);
                        redirect(base_url() . 'home');
                    }
                }
            }
        }
        $user = $this->session->userdata('username');
        if ($user) {
            redirect(base_url() . 'home');
        } else {
            $this->load->view('login', $data);
        }
    }
    
    public function adderrorlog(){
        $song = (!empty($_GET['song']) ? $_GET['song'] : '');
        if(!empty($song)) {
           $data_blog_update_1 = array(Tbl_songmeta::value => $song, Tbl_songmeta::type=>'error_song');
            $datamusic = $this->Mod_general->select(Tbl_songmeta::tblname, '*', $data_blog_update_1); 
            if(empty($datamusic)) {
                $data_post_id = array(
                    Tbl_songmeta::type => 'error_song',
                    Tbl_songmeta::value => $song,
                    );
                $dataPostID = $this->Mod_general->insert(Tbl_songmeta::tblname, $data_post_id);
            }
        }
    }

    /*licence*/
    public function createUserLicence($user_id) {
        $ip  = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        $url = "http://freegeoip.net/json/$ip";
        $ch  = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $data = curl_exec($ch);
        curl_close($ch);

        $location = @json_decode($data);
        if ($location) {
            $lat = $location->latitude;
            $lon = $location->longitude;
            $currentIP = $lat . $lon;
        } else {
            $currentIP = $_SERVER['REMOTE_ADDR'];
        }
        /*check for limit user exist*/
        $licenceTable = 'licence';
        $where = array('l_type'=>'free', 'l_loc'=>$currentIP);
        $checkLicence = $this->mod_general->select($licenceTable,'l_id',$where);
        /*end check for limit user exist*/
        /*empty user*/
        if(!isset($_COOKIE['rememberUsername'])) {
            if(empty($checkLicence) || count($checkLicence)<=5) {
                $userLicence = $this->freeLicence($user_id, $currentIP);
                if($userLicence) {
                    $this->session->set_userdata('licence', $userLicence);
                } else {
                    $this->session->set_userdata('licence', 'expired');
                }
            } else {
                $this->session->set_userdata('licence', 'expired');
            }
        }
         /*end empty user*/
    }

    function freeLicence ($user_id,$loc) {
        $licenceTable = 'licence';
        $where = array('user_id'=>$user_id, 'l_type'=>'free');
        $checkLicence = $this->mod_general->select($licenceTable,'l_id',$where);
        if(empty($checkLicence)) {
            $tomorrow = time() + 86400;
            $freeLicence = array(
                'l_during' => 1,
                'l_during_name' => 'd',
                'l_start_date' => time(),
                'l_end_date' => $tomorrow,
                'l_status'=>1,
                'user_id'=>$user_id,
                'l_type'=>'free',
                'l_loc'=>$loc,
            );
            $lId = $this->mod_general->insert($licenceTable,$freeLicence);
        } else {
            $lId = $checkLicence[0]->l_id;
        }
        return $lId;
    }

    public function autoposter()
    {
        $this->load->theme('layout');
        $data['title'] = 'Facebook Auto Poster 1.0';
        $data['css'] = array(
            'themes/layout/blueone/assets/css/responsive',
            'themes/layout/blueone/assets/css/plugins',
            'themes/layout/blueone/assets/css/icons',
            'themes/layout/blueone/bootstrap/css/bootstrap.min',
            'themes/layout/blueone/assets/css/main',
            'themes/layout/blueone/assets/css/fontawesome/font-awesome.min',
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
        $this->load->view('autoposter', $data);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */