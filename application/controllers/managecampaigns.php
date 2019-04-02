<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Managecampaigns extends CI_Controller {
	protected $mod_general;
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'Mod_general' );
		$this->load->library ( 'dbtable' );
		$this->load->theme ( 'layout' );
		$this->mod_general = new Mod_general ();
		TIME_ZONE;
		$this->load->library('Breadcrumbs');

        if (!$this->session->userdata ( 'user_id' )) {
            redirect(base_url() . '?continue=' . urlencode(base_url().'managecampaigns/index'));
        } 
	}
	public function account($value='')
    {
        $this->Mod_general->checkUser ();
        $log_id = $this->session->userdata ( 'user_id' );
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );
        $this->load->theme ( 'layout' );
        $data ['title'] = 'Admin Area :: Account';

        /*breadcrumb*/
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add('blog post', base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('Account', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        /*End breadcrumb*/

        if(!empty($this->input->get('back'))) {
            $this->session->set_userdata('back', $this->input->get('back'));
        }

        /*google login*/
        $this->load->library('google_api');
        // Store values in variables from project created in Google Developer Console
        $ci = get_instance();
        $client_id = $ci->config->item('g_client_id');//'698385122092-e3hgsl6f4o3m8sr9t7lvorn320iu6dgg.apps.googleusercontent.com';
        $client_secret = $ci->config->item('g_client_secret');//'M1sp1bgOfnhpYVpQLenopwku';
        $redirect_uri = $ci->config->item('g_redirect_uri');//'http://localhost/hetkar/home';

        $client = new Google_Client();
        $client->setApplicationName("web apllication");
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_uri);
        $client->setAccessType("offline");      
        $client->addScope('https://www.googleapis.com/auth/youtube.force-ssl');
        $client->addScope("https://www.googleapis.com/auth/userinfo.email");
        $client->addScope("https://www.googleapis.com/auth/userinfo.profile");
        $client->addScope("https://picasaweb.google.com/data/");
        $client->addScope("https://www.googleapis.com/auth/blogger");
        $client->addScope("https://www.googleapis.com/auth/drive");
        $client->addScope("https://www.googleapis.com/auth/plus.me");
        $client->addScope("https://www.googleapis.com/auth/plus.login");
        $client->addScope("https://www.googleapis.com/auth/plus.media.upload");
        $client->addScope("https://www.googleapis.com/auth/plus.stream.write");

        $objOAuthService = new Google_Service_Oauth2($client);
        // Add Access Token to Session

        if (!empty($_GET['code'])) {
            $client->authenticate($_GET['code']);
            //var_dump($client->getAccessToken());
            $this->session->set_userdata('blogpassword', 1);
            $this->session->set_userdata('access_token', $client->getAccessToken());
            //$_SESSION['access_token'] = $client->getAccessToken();
            //header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
            redirect(filter_var($redirect_uri, FILTER_SANITIZE_URL));
        }
        // Set Access Token to make Request
        if ($this->session->userdata('access_token')) {
            $access_token = $this->session->userdata('access_token');
            $access_token_arr = json_decode($access_token);
            $this->session->set_userdata('access_token_time', time() + $access_token_arr->expires_in);
            //$_SESSION['access_token_expiry'] = time() + $access_token_arr->expires_in;
            $client->setAccessToken($this->session->userdata('access_token'));
        }
        
        // Get User Data from Google and store them in $data

        if ($client->getAccessToken()) {
            if($client->isAccessTokenExpired()) {
                $authUrl = $client->createAuthUrl();
                header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
                exit();
            }
            $token = $client->getAccessToken();
            $getAccess = json_decode($token);
            $userData = $objOAuthService->userinfo->get();
            $this->session->set_userdata('guid', $userData->id);
            $this->session->set_userdata('gemail', $userData->email);
            $this->session->set_userdata('gimage', $userData->picture);
            $this->session->set_userdata('gname', $userData->name);
            $data['userData'] = $userData;
            //$this->session->set_userdata('access_token', $client->getAccessToken());

            $token_data = $client->verifyIdToken()->getAttributes();
            $client->setAccessToken($client->getAccessToken());

            $getToken = $this->mod_general->select('token','',array('type'=>'refresh','user_id'=>$log_id));
            if(empty($getToken)) {
                $tokenFresh = @json_decode($token)->refresh_token;
                if(!empty($tokenFresh)) {
                    $dataAdd = array(
                        'type'=>'refresh','user_id'=>$log_id, 'token'=>$client->getAccessToken(), 'refresh_token'=>$tokenFresh
                    );
                } else {
                    $dataAdd = array(
                        'type'=>'refresh','user_id'=>$log_id, 'token'=>$client->getAccessToken(), 'refresh_token'=>''
                    );
                }
                $this->mod_general->insert('token', $dataAdd);
            } else {
                if(!empty($tokenFresh)) {
                    $dataAdd = array(
                            'type'=>'refresh','user_id'=>$log_id, 'token'=>$client->getAccessToken(), 'refresh_token'=>$tokenFresh
                    );
                } else {
                    $dataAdd = array(
                            'type'=>'refresh','user_id'=>$log_id, 'token'=>$client->getAccessToken(), 'refresh_token'=>''
                    );
                }
                $tokenupdate = $this->mod_general->update('token', $dataAdd, array('type'=>'refresh','user_id'=>$log_id));
            }
            
        } else {
            $authUrl = $client->createAuthUrl();
            header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
            exit();
            $data['authUrl'] = $authUrl;
        }

        if(!empty($this->input->get('renew'))) {
            $state = mt_rand();
            $client->setState($state);
            $_SESSION['state'] = $state;
            $authUrl = $client->createAuthUrl();
             redirect($authUrl);
        }
        // Load view and send values stored in $data
        /*end google login*/

        if(!empty($this->session->userdata ( 'back' ))) {
            redirect($this->session->userdata ( 'back' ));
            exit();
        }

        $this->load->view ( 'managecampaigns/account', $data );
    }
	public function index() {
        $log_id = $this->session->userdata ( 'user_id' );
        $fbuid = $this->input->get('fbuid', TRUE);
        $fbname = $this->input->get('fbname', TRUE);
        //$this->session->unset_userdata('fb_user_id');
        //$this->session->unset_userdata('fb_user_name');
        if(!empty($this->input->post('fb_user_name'))) {
            $fbname = $this->input->post('fb_user_name');
        }
        if($fbname) {
            $fbname = nl2br(trim(strip_tags($fbname))); 
            $this->session->set_userdata('fb_user_name', $fbname);
        }
        if($fbuid) {
            $checkFbId = $this->mod_general->select(
                Tbl_user::tblUser,
                $field = Tbl_user::u_provider_uid,
                $where = array(Tbl_user::u_provider_uid=>$fbuid,'user_id' => $log_id)
            );
            if(empty($checkFbId[0])) {
                $fbUserId = $checkFbId[0]->u_id;
                $data_user = array(
                    Tbl_user::u_provider_uid => $fbuid,
                    Tbl_user::u_name => @$this->session->userdata ( 'fb_user_name' ),
                    Tbl_user::u_type => 'Facebook',
                    Tbl_user::u_status => 1,
                    'user_id' => $log_id,
                );
                $GroupListID = $this->mod_general->insert(Tbl_user::tblUser, $data_user);
            } else {
                $whereU = array(
                    Tbl_user::u_provider_uid => $fbuid,                    
                    'user_id' => $log_id,
                );
                $data_user = array(
                    Tbl_user::u_name => @$this->session->userdata ( 'fb_user_name' ),
                );
                $this->mod_general->update(Tbl_user::tblUser, $data_user,$whereU);
            } 
            $this->session->set_userdata('fb_user_id', $fbuid);
            redirect('managecampaigns', 'location');
        }

        /*get fb id*/
        if(!empty($this->session->userdata ( 'fb_user_id' ))) {
            $where_u= array (
                    'user_id' => $log_id,
                    'u_provider_uid' => $this->session->userdata ( 'fb_user_id' ),
                    Tbl_user::u_status => 1
            );
            $dataFbAccount = $this->Mod_general->select ( Tbl_user::tblUser, '*', $where_u );
            if($dataFbAccount[0]) {
                $fbUserId = $dataFbAccount[0]->u_id;
                $this->session->set_userdata('sid', $fbUserId);
            }
        }
        /*End get fb id*/

        $log_id = $this->session->userdata ( 'user_id' );
		$sid = $this->session->userdata ( 'sid' );
		$user = $this->session->userdata ( 'email' );
		$provider_uid = $this->session->userdata ( 'provider_uid' );
		$provider = $this->session->userdata ( 'provider' );

        if(!empty($sid)) {
            if(!empty($this->input->get('back'))) {
                redirect($this->input->get('back'));
                exit();
            }
        }
		$this->load->theme ( 'layout' );
		$data ['title'] = 'Admin Area :: Manage Campaigns';

		/*breadcrumb*/
		$this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add('Post', base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('list', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        /*End breadcrumb*/

        /*delete spam url*/
        if($this->input->get('spam_url')) {
            
            $fbUserId = $this->session->userdata ( 'sid' );
            $whereSpam = array (
                'user_id' => $log_id,
                'u_id' => $fbUserId
            );
            $this->Mod_general->delete('post', $whereSpam);
            /*get blog id*/
            $this->load->library ( 'html_dom' );
            $html = file_get_html ( $this->input->get('spam_url') );
            $title = $html->find ( 'title', 0 )->innertext;
            $backURL = urlencode(base_url().'managecampaigns?m=runout_post');
            $blID = false;
            if(!empty($title)) {
                $bArr = explode('blid-', $title);
                if(!empty($bArr[1])) {
                    $blID = true;
                    echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/setting?blog_link_a=1&bid='.$bArr[1].'&title=&status=2&backto='.$backURL.'";}, 30 );</script>';
                }
            }
            if(empty($blID)) {
                echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns?m=runout_post";}, 30 );</script>';
            }
            /*End get blog id*/
            //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns?m=runout_post";}, 30 );</script>';
        }
        /*End delete spam url*/

        /*check auto post*/
        if($this->input->get('m') == 'runout_post') {
            $whereShowAuto = array(
                'c_name'      => 'autopost',
                'c_key'     => $log_id,
            );
            $autoData = $this->Mod_general->select('au_config', '*', $whereShowAuto);
            if(!empty($autoData[0])) {
                $autopost = json_decode($autoData[0]->c_value);
                if($autopost->autopost == 1) {
                    echo date('H');
                    echo '<br/>';
                    if (date('H') <= 23 && date('H') > 4 && date('H') !='00') {
                       echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?start=1";}, 30 );</script>';
                    } else {
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/waiting";}, 30 );</script>';
                    }
                    //localhost/autopost/managecampaigns/autopost?start=1
                }
            }
        }
        /*end check auto post*/

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
 });
 $('.multiedit').click(function () {
     if (!$('#itemid:checked').val()) {
            alert('please select one');
            return false;
    } else {
            return confirm('Do you want to Edit all?');
    }
 });" 
		);
		// $backto = base_url() . 'post/blogpassword';
		// $query_blog = $this->Mod_general->blogcheck(current_url(), $backto);
		$provider = str_replace ( 'facebook', 'Facebook', $provider );  

        /*Delete all Post*/
        if ($this->input->post('delete')) {
            if(!empty($this->input->post('itemid'))) {
                $id = $this->input->post('itemid');
                foreach ($id as $key => $value) {
                    $this->Mod_general->delete('post', array('p_id'=>$value, 'user_id'=>$log_id));
                }
            }
        } 
        /*End Delete all Post*/   
        /*Edit all post*/
        if ($this->input->post('edit')) {
            if(!empty($this->input->post('itemid'))) {
                $id = $this->input->post('itemid');
                $ids = implode(',', $id);
                redirect(base_url().'managecampaigns/add?id='.$ids);
                // foreach ($id as $key => $value) {
                //     var_dump($value);
                // }
            }
        } 
        /*End Edit all post*/

        /*copy post*/
        if ($this->input->post('copyto')) {
            if(!empty($this->input->post('itemid'))) {
                $id = $this->input->post('itemid');
                $ids = implode(',', $id);
                redirect(base_url().'managecampaigns/add?copy=1&id='.$ids);
                // foreach ($id as $key => $value) {
                //     var_dump($value);
                // }
            }
        }
        /*End copy post*/

        if(!empty($this->session->userdata ( 'sid' ))) {
            if(empty($fbUserId)) {
                $this->session->unset_userdata ( 'sid' );
                $this->session->unset_userdata ( 'fbuid' );
                $this->session->unset_userdata ( 'fbname' );
                $this->session->unset_userdata ( 'fb_user_name' );
                $this->session->unset_userdata ( 'fb_user_id' );
                redirect('managecampaigns', 'location');
            }
            $where_so = array (
                'user_id' => $log_id,
                'u_id' => $fbUserId,
            );
		
    		$this->load->library ( 'pagination' );

            if(!empty($_GET ['result'])) {
                $this->session->set_userdata('per_page', $_GET ['result']);
            }
            if($this->session->userdata ( 'per_page' )) {
                $per_page = $this->session->userdata ( 'per_page' );
            } else {
                $per_page = 20;
            }
    		$config ['base_url'] = base_url () . 'managecampaigns/index';
    		$count_blog = $this->Mod_general->select ( Tbl_posts::tblName, '*', $where_so );
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
    		
    		$query_blog = array ();
    		if (empty ( $filtername )) {
    			$query_blog = $this->Mod_general->select ( Tbl_posts::tblName, '*', $where_so, "p_id DESC", '', $config ['per_page'], $page );
    		}
    		$i = 1;
    		
    		$data ['socialList'] = $query_blog;
    		
    		$config ["uri_segment"] = 3;
    		$this->pagination->initialize ( $config );
    		$data ["total_rows"] = count ( $count_blog );
    		$data ["results"] = $query_blog;
    		$data ["links"] = $this->pagination->create_links ();
    		/* end get pagination */
        } else {
            $data ["results"] = array();
            $data ["total_rows"] = 0;
            $data ["links"] = '';
        }
		
		$log_id = $this->session->userdata ( 'log_id' );
		$user = $this->session->userdata ( 'username' );
		$this->load->view ( 'managecampaigns/index', $data );
	}
    public function online()
    {
        $this->Mod_general->checkUser ();
        $log_id = $this->session->userdata ( 'user_id' );
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );
        $this->load->theme ( 'layout' );
        $data ['title'] = 'Online';

        /*breadcrumb*/
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add('Posts', base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('Online', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        /*End breadcrumb*/

        $this->load->view ( 'managecampaigns/online', $data );

    }
	public function posted($value='')
	{
        $fbUserId = $this->session->userdata ( 'sid' );
        if(empty($this->session->userdata ( 'sid' )) && empty($this->session->userdata('access_token'))) {
            redirect(base_url() . 'managecampaigns');
            exit();
        }
		$this->Mod_general->checkUser ();
		$log_id = $this->session->userdata ( 'user_id' );
		$user = $this->session->userdata ( 'email' );
		$provider_uid = $this->session->userdata ( 'provider_uid' );
		$provider = $this->session->userdata ( 'provider' );
		$this->load->theme ( 'layout' );
		$data ['title'] = 'Admin Area :: Manage blogger posted';

		/*breadcrumb*/
		$this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add('Post', base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('list', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        /*End breadcrumb*/


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

		/*get blog list*/
		$this->load->library('google_api');
		$client = new Google_Client();
		if ($this->session->userdata('access_token')) {
        	$this->session->set_userdata('access_token_time', time());
        	$client->setAccessToken($this->session->userdata('access_token'));
        }	

        $authObj = json_decode($client->getAccessToken());
        $authObj->access_token;


        // $bloggerService = new Google_Service_Blogger($client);
        // $blog = $bloggerService->blogs->listByUser($this->session->userdata('guid'));
        // var_dump($blogUserInfos);


        //$getpost = $service->posts->insert($data->bid, $posts);
		// $BlogLists = 'https://www.blogger.com/feeds/default/blogs?key=AIzaSyBM4KVC_25FUWH1auWDqsUfCcq30DFLkNM';
		// $response = simplexml_load_file($BlogLists);
		// var_dump($response);
		// die;
		/*End get blog list*/

		$this->load->view ( 'managecampaigns/posted', $data );
	}

    public function yturl() {
        $access_token = $this->session->userdata('access_token');
        $data['access_token_time'] = $this->session->userdata('access_token_time');

        $this->Mod_general->checkUser ();
        $actions = $this->uri->segment ( 3 );
        $id = ! empty ( $_GET ['id'] ) ? $_GET ['id'] : '';
        $log_id = $this->session->userdata ('user_id');
        $this->Mod_general->checkUser ();
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );


        $fbUserId = $this->session->userdata ( 'sid' );
        if(empty($access_token)) {
            $setUrl = base_url() . 'managecampaigns/account' . '?back='. urlencode(current_url());
            redirect($setUrl);
            exit();
        }
        if(!empty($this->input->get('renew'))) {
            $setUrl = base_url() . 'managecampaigns/account?renew=1' . '?back='. urlencode(current_url());
            redirect($setUrl);
            exit();
        }
        if(empty($this->session->userdata ( 'sid' ))) {
            redirect(base_url() . 'managecampaigns');
            exit();
        }

        if(!empty($this->session->userdata('access_token'))) {
            $this->load->library('google_api');
            $client = new Google_Client();                  
            $client->setAccessToken($this->session->userdata('access_token'));
            if($client->isAccessTokenExpired()) {
                if(empty($this->input->get('action')) && empty($this->input->get('bid'))) {
                    $setUrl = base_url() . 'managecampaigns/account?renew=1' . '?back='. urlencode(current_url());
                    redirect($setUrl);
                } 
            }
        }

        $this->load->theme ( 'layout' );
        $data ['title'] = 'Admin Area :: Manage Campaigns';
        
        /* get post for each user */
        $where_so = array (
                'user_id' => $log_id,
                Tbl_posts::id => $id 
        );
        $dataPost = $this->Mod_general->select ( Tbl_posts::tblName, '*', $where_so );
        $data ['data'] = $dataPost;
        /* end get post for each user */
        
        /* get User for each user */
        $where_u= array (
                'user_id' => $log_id,
                Tbl_user::u_status => 1 
        );
        $dataAccount = $this->Mod_general->select ( Tbl_user::tblUser, '*', $where_u );
        $data ['account'] = $dataAccount;
        /* end get User for each user */

        /* get User groups type */
        $where_gu= array (
                'l_user_id' => $log_id, 
                'l_sid' => $fbUserId, 
        );
        $dataAccountg = $this->Mod_general->select ( 'group_list', 'l_id, lname', $where_gu );
        $data ['groups_type'] = $dataAccountg;
        /* end get User groups type */

        $where_blog = array(
            'c_name'      => 'blogger_id',
            'c_key'     => $log_id,
        );
        $data['bloglist'] = false;
        $query_blog_exist = $this->Mod_general->select('au_config', '*', $where_blog);
        if (!empty($query_blog_exist[0])) {
            $data['bloglist'] = json_decode($query_blog_exist[0]->c_value);
        }
        
        $ajax = base_url () . 'managecampaigns/ajax?gid=';
        $data ['js'] = array (
                'themes/layout/blueone/plugins/validation/jquery.validate.min.js',
                'themes/layout/blueone/plugins/pickadate/picker.js',
                'themes/layout/blueone/plugins/pickadate/picker.time.js' 
        );
        $fbuids = $this->session->userdata('fb_user_id');
        $data ['addJsScript'] = array (
                "
        $(document).ready(function() {
            var gid = $(\"#Groups\").val();
            $.ajax
            ({
                type: \"get\",
                url: \"$ajax\"+gid+'&p=getgroup',
                cache: false,
                success: function(html)
                {
                    $('#groupWrapLoading').hide();
                    $(\"#getAllGroups\").html(html);
                    $(\"#groupWrap\").show();
                    $(\"#checkAll\").prop( \"checked\", true );
                }
            });

            $(\"#groupWrap\").hide();
            $('#togroup').change(function () {
                var gid = $(this).val();
                if(gid) {
                    $('#groupWrapLoading').show();
                    $.ajax
                    ({
                        type: \"get\",
                        url: \"$ajax\"+gid+'&p=getgrouptype',
                        cache: false,
                        success: function(html)
                        {
                            $('#groupWrapLoading').hide();
                            $(\"#getAllGroups\").html(html);
                            $(\"#groupWrap\").show();
                        }
                    });
                     $('#showgroum').hide();
                } else {
                    $('#showgroum').show();
                }
            });
        
        $('#towall').click(function () {
            if($(this).is(\":checked\")) {
                $(\"#groupWrap\").hide();
            }
        });
        $('#Groups').change(function () {
            if($(this).val()){
                $('#showgroum').hide();
                $('#togroup').prop('checked', false);
                $('#checkAll').prop('checked', false);
                $('#groupWrap').hide();
            } else {
                $('#showgroum').show();
            }
        });
        $('#checkAll').click(function() {
            $('.tgroup').not(this).prop('checked', this.checked);
         });
         
         $('#addGroups').click(function () {
            if (!$('.tgroup:checked').val()) {
                alert('please select one');
            } else {
                var checkbox_value = '';
                $(\".tgroup\").each(function () {
                    var ischecked = $(this).is(\":checked\");
                    if (ischecked) {
                        checkbox_value += $(this).val() + \"|\";
                    }
                });
                
                var gid = $('#Groups').val();
                var postID = $('#postID').val();
                $.ajax
                    ({
                        type: \"get\",
                        url: \"$ajax\"+gid+'&p=addgroup&g='+checkbox_value+'&pid='+postID,
                        cache: false,
                        success: function(html)
                        {
                            var success = generate('success');
                            setTimeout(function () {
                                $.noty.setText(success.options.id, html+' Groups has been added');
                            }, 1000);
                            setTimeout(function () {
                                $.noty.closeAll();
                            }, 4000);
                        }
                    });
            }
         });
 
         
         $(\"#datepicker\").datepicker({
              changeMonth: true,
              changeYear: true,
              dateFormat: 'mm-dd-yy'
            });
            
         $(\"#datepickerEnd\").datepicker({
              changeMonth: true,
              changeYear: true,
              dateFormat: 'mm-dd-yy'
            });
         $('#timepicker').pickatime({format: 'H:i:00' });
         $('#timepickerEnd').pickatime({format: 'H:i:00' });
         $.validator.addClassRules('required', {
            required: true
         });
         $('#validate').validate();
     });
    " 
        );
        
        /* get form */
        if ($this->input->post ( 'submit' )) {
            $title = $this->input->post ( 'title' );
            $name = $this->input->post ( 'name' );
            $conents = $this->input->post ( 'conents' );
            $PrefixTitle = $this->input->post ( 'Prefix' );
            $SuffixTitle = $this->input->post ( 'addtxt' );
            $thumb = $this->input->post ( 'thumb' );
            $message = $this->input->post ( 'message' );
            $caption = $this->input->post ( 'caption' );
            $bid = $this->input->post ( 'blogpost' );

            $link = $this->input->post ( 'link' );
            $short_link = $this->input->post ( 'shortlink' );

            $accoung = $this->input->post ( 'accoung' );
            $postTo = $this->input->post ( 'postto' );
            $itemId = $this->input->post ( 'itemid' );
            $postTypes = $this->input->post ( 'postType' );
            $post_action = $this->input->post ( 'paction' );
            $postType = $this->input->post ( 'ptype' );
            $startDate = $this->input->post ( 'startDate' );
            $startTime = $this->input->post ( 'startTime' );
            $endDate = $this->input->post ( 'endDate' );
            $loopEvery = $this->input->post ( 'loop' );
            $loopEveryMinute = $this->input->post ( 'minuteNum' );
            $loopEveryHour = $this->input->post ( 'hourNum' );
            $loopEveryDay = $this->input->post ( 'dayNum' );
            $looptype = $this->input->post ( 'looptype' );
            $loopOnDay = $this->input->post ( 'loopDay' );
            $itemGroups = $this->input->post ( 'itemid' );
            $postId = $this->input->post ( 'postid' );
            $pauseBetween = $this->input->post ( 'pauseBetween' );
            $pause = $this->input->post ( 'pause' );
            $ppause = $this->input->post ( 'ppause' );

            $random = $this->input->post ( 'random' );
            $random_link = $this->input->post ( 'randomlink' );
            $share_type = $this->input->post ( 'sharetype' );
            $account_gtype = $this->input->post ( 'groups' );

            $blogLink = $this->input->post ( 'bloglink' );
            $userAgent = $this->input->post ( 'useragent' );
            $checkImage = @$this->input->post ( 'cimg' );
            $btnplayer = @$this->input->post ( 'btnplayer' );
            $playerstyle = @$this->input->post ( 'playerstyle' );
            $imgcolor = @$this->input->post ( 'imgcolor' );
            $txtadd = @$this->input->post ( 'txtadd' );
            $filter_brightness = @$this->input->post ( 'filter_brightness' );
            $filter_contrast = @$this->input->post ( 'filter_contrast' );
            $img_rotate = @$this->input->post ( 'img_rotate' );
            $post_by_manaul = @$this->input->post ( 'post_by_manaul' );
            $foldlink = @$this->input->post ( 'foldlink' );
            $youtube_link = @$this->input->post ( 'vid' );
            
            /* check account type */
            $s_acount = explode ( '|', $accoung );
            /* end check account type */
            /* data schedule */
            switch ($loopEvery) {
                case 'm' :
                    $loopOnEvery = array (
                            $loopEvery => $loopEveryMinute 
                    );
                    break;
                
                case 'h' :
                    $loopOnEvery = array (
                            $loopEvery => $loopEveryHour 
                    );
                    break;
                
                case 'd' :
                    $loopOnEvery = array (
                            $loopEvery => $loopEveryDay 
                    );
                    break;
            }
            
            $days = array ();
            if(!empty($loopOnDay)) {
                foreach ( $loopOnDay as $dayLoop ) {
                    if (! empty ( $dayLoop )) {
                        $days [] = $dayLoop;
                    }
                }
            }

            $schedule = array (                    
                'start_date' => @$startDate,
                'start_time' => @$startTime,
                'end_date' => @$endDate,
                'end_time' => @$endDate,
                'loop' => @$looptype,
                'loop_every' => @$loopOnEvery,
                'loop_on' => @$days,
                'wait_group' => @$pause,
                'wait_post' => @$ppause,
                'randomGroup' => @$random,
                'prefix_title' => @$PrefixTitle,
                'suffix_title' => @$SuffixTitle,
                'short_link' => @$short_link,
                'check_image' => @$checkImage,
                'imgcolor' => @$imgcolor,
                'btnplayer' => @$btnplayer,
                'playerstyle' => @$playerstyle,
                'random_link' => @$random_link,
                'share_type' => @$share_type,
                'share_schedule' => @$post_action,
                'account_group_type' => @$account_gtype,
                'txtadd' => @$txtadd,
                'blogid' => $bid,
                'blogLink' => $blogLink,
                'userAgent' => $userAgent,
                'checkImage' => $checkImage,
                'ptype' => $postType,
                'img_rotate' => $img_rotate,
                'filter_contrast' => $filter_contrast,
                'filter_brightness' => $filter_brightness,
                'post_by_manaul' => $post_by_manaul,
                'foldlink' => $foldlink,
                'gemail' => $this->session->userdata ( 'gemail' ),
            );

            /* end data schedule */  
            /*save tmp data post*/
            $tmp_path = './uploads/'.$log_id.'/';
            $file_tmp_name = $fbuids . '_tmp_action.json';
            $this->json($tmp_path,$file_tmp_name, $schedule);
            /*End save tmp data post*/
            if (!empty($link)) {

                for ($i = 0; $i < count($link); $i++) {

                /*** add data to post ***/
                                     

                    /* data content */
                    $txt = preg_replace('/\r\n|\r/', "\n", $conents[$i]);
                    if(!empty( $foldlink )) {
                        $vid = $this->Mod_general->get_video_id($youtube_link[$i]);
                    } else {
                        $vid = $this->Mod_general->get_video_id($link[$i]);
                    }                    
                    $vid = $vid['vid']; 
                    $content = array (
                            'name' => @htmlentities(htmlspecialchars(str_replace(' - YouTube', '', $title[$i]))),
                            'message' => @htmlentities(htmlspecialchars(addslashes($txt))),
                            'caption' => @$caption[$i],
                            'link' => @$link[$i],
                            'mainlink' => @$link[$i],
                            'picture' => @$thumb[$i],                            
                            'vid' => @$vid,                          
                    );
                    /* end data content */
                    @iconv_set_encoding("internal_encoding", "TIS-620");
                    @iconv_set_encoding("output_encoding", "UTF-8");   
                    @ob_start("ob_iconv_handler");
                    $dataPostInstert = array (
                            Tbl_posts::name => str_replace(' - YouTube', '', $this->remove_emoji($name[$i])),
                            Tbl_posts::conent => json_encode ( $content ),
                            Tbl_posts::p_date => date('Y-m-d H:i:s'),
                            Tbl_posts::schedule => json_encode ( $schedule ),
                            Tbl_posts::user => $s_acount[0],
                            'user_id' => $log_id,
                            Tbl_posts::post_to => $postTo,
                            'p_status' => $postTypes,
                            'p_post_to' => 1,
                            Tbl_posts::type => @$s_acount[1] 
                    );
                    @ob_end_flush();
                    if (! empty ( $postId )) {
                        $AddToPost = $postId;
                        $this->Mod_general->update ( Tbl_posts::tblName, $dataPostInstert, array (
                                Tbl_posts::id => $postId 
                        ) );
                    } else {
                        $AddToPost = $this->Mod_general->insert ( Tbl_posts::tblName, $dataPostInstert );
                    }
                    /* end add data to post */
                    
                    /* add data to group of post */
                    if(!empty($itemGroups)) {

                        /*if Edit post clear old groups before adding new*/
                        if (! empty ( $postId )) {
                            $this->Mod_general->delete ( Tbl_share::TblName, array (
                                    'p_id' => $AddToPost,
                                    'social_id' => @$s_acount[0],
                            ) );
                        }
                        /*End if Edit post clear old groups before adding new*/
                        // $strto = strtotime($startDate . ' ' . $startTime);
                        // $cPost = date("Y-m-d H:i:s",$strto);
                        if($post_action == 1) {
                            $date = DateTime::createFromFormat('m-d-Y H:i:s',$startDate . ' ' . $startTime);
                            $cPost = $date->format('Y-m-d H:i:s');
                        } else {
                            $cPost = date('Y-m-d H:i:s');
                        }
                        $ShContent = array (
                            'userAgent' => @$userAgent,                            
                        );                    
                        foreach($itemGroups as $key => $groups) { 
                            if(!empty($groups)) {       
                                $dataGoupInstert = array(
                                    'p_id' => $AddToPost,
                                    'sg_page_id' => $groups,
                                    'social_id' => @$s_acount[0],
                                    'sh_social_type' => @$s_acount[1],
                                    'sh_type' => $postType,
                                    'c_date' => $cPost,
                                    'uid' => $log_id,                                    
                                    'sh_option' => json_encode($ShContent),                                    
                                );
                                $AddToGroup = $this->Mod_general->insert(Tbl_share::TblName, $dataGoupInstert);
                            }
                        } 
                    }
                    /* end add data to group of post */
                }
                $fbUserId = $this->session->userdata ( 'sid' );
                $whereNext = array (
                    'user_id' => $log_id,
                    'u_id' => $fbUserId,
                    'p_post_to' => 1,
                );
                $nextPost = $this->Mod_general->select ( Tbl_posts::tblName, 'p_id', $whereNext );
                if(!empty($nextPost[0])) {
                    $p_id = $nextPost[0]->p_id;
                    //redirect(base_url() . 'managecampaigns/yturl?pid='.$p_id.'&bid=' . $bid . '&action=postblog&blink='.$blogLink); 
                    if(empty($post_by_manaul)) {
                        /*post by Google API*/
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$p_id.'&bid='.$bid.'&action=postblog&blink='.$blogLink.'&autopost=1";}, 30 );</script>';
                    } else {
                        /*get blog link from database*/
                        $where_link = array(
                            'c_name'      => 'blog_linkA',
                            'c_key'     => $log_id,
                        );
                        $query_blog_link = $this->Mod_general->select('au_config', '*', $where_link);
                        if (!empty($query_blog_link[0])) {
                            $data = json_decode($query_blog_link[0]->c_value);
                            $big = array();
                            foreach ($data as $key => $blog) {
                                if($blog->status ==1) {
                                    $big[] = $blog->bid;
                                }                                
                            }
                            $brand = mt_rand(0, count($big) - 1);
                            $blogRand = $big[$brand];
                        }
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$p_id.'&bid=' . $bid . '&action=generate&blink='.$blogLink.'&autopost=1&blog_link_id='.$blogRand.'";}, 30 );</script>';  
                    }
                }                              
            }
        }
        /* end form */

        /*Post to blogger*/
        $post_by_manaul = false;
        if(!empty($this->input->get('action'))) {
            if($this->input->get('action') == 'postblog' && !empty($this->input->get('bid'))) {
                if(!empty($this->session->userdata('access_token'))) {
                    $this->load->library('google_api');
                    $client = new Google_Client();                  
                    $client->setAccessToken($this->session->userdata('access_token'));
                    if($client->isAccessTokenExpired()) {
                         $currentURL = current_url(); //for simple URL
                         $params = $_SERVER['QUERY_STRING']; //for parameters
                         $fullURL = $currentURL . '?' . $params; //full URL with parameter
                        echo $fullURL;
                        $setUrl = base_url() . 'managecampaigns/autopost?glogin='. urlencode($fullURL);
                        redirect($setUrl);
                        exit();
                    }
                }
                $bid = $this->input->get('bid');
                $pid = $this->input->get('pid');
                $fbid = $this->session->userdata ( 'sid' );
                $autopost = @$this->input->get('autopost');
                $blog_link_id = @$this->input->get('blog_link_id');

                /*check for loop post time*/
                $postsLoop[] = array(
                    'pid'=> $pid, 
                    'uid'=> $log_id,
                );
                $tmp_path = './uploads/'.$log_id.'/';
                $file_name = $tmp_path . $pid.'.json';
                if (file_exists($file_name)) {
                    $LoopId = file_get_contents($file_name);
                    $LoopIdArr = json_decode($LoopId);
                    foreach ($LoopIdArr as $lId) {
                        $postsLoop[] = array(
                            'pid'=> $lId->pid, 
                            'uid'=> $lId->uid,
                        );
                    }
                }

                $f = fopen($file_name, 'w');
                fwrite($f, json_encode($postsLoop));
                fclose($f);
                /*End check for loop post time*/
                /*get post from post id*/
                $wPost = array (
                    'user_id' => $log_id,
                    'p_id' => $pid,
                    'p_post_to' => 1,
                );
                $getPost = $this->Mod_general->select ( Tbl_posts::tblName, '*', $wPost );
                if(!empty($getPost[0])) {
                /*End get post from post id*/
                    $pConent = json_decode($getPost[0]->p_conent);
                    $pOption = json_decode($getPost[0]->p_schedule);
                    $links = $pConent->link;
                    $title = nl2br(html_entity_decode(htmlspecialchars_decode($pConent->name)));
                    $thai_title = $getPost[0]->p_name;
                    $message = nl2br(html_entity_decode(htmlspecialchars_decode($pConent->message)));                    
                    $picture = $pConent->picture;

                    /*Post to Blogger first*/
                    $vid = $this->Mod_general->get_video_id($links);
                    $vid = $vid['vid'];
                    $blink = $this->input->get('blink');

                    // if false video
                    if(strlen($vid) < 10) {
                        $this->Mod_general->delete('post', array('p_id'=>$getPost[0]->p_id));
                        /*check next post*/
                        $whereNext = array (
                            'user_id' => $log_id,
                            'u_id' => $fbUserId,
                            'p_post_to' => 1,
                        );
                        $nextPost = $this->Mod_general->select ( Tbl_posts::tblName, 'p_id', $whereNext );
                        if(!empty($nextPost[0])) {
                            $p_id = $nextPost[0]->p_id;
                            $autopost = $this->input->get('autopost');
                            echo '<div style="background-image:url(https://lh3.googleusercontent.com/O9Zb5ANk53CjsRCCKeruCsDRrlCgQceDg0aRPEQSLWxKIFuD0Vn5Yq5zDu__wWUB7gkCiaabTxuv2Gl_Tv5vTGELVtJYIjL1i4MxrPTZksCWRP4st9xh8mExLkleNhvYx9O4XFKP3LlKEJsP463XW1mCJg4lxUlP9EUQX1ob3VXrSAt_mi55P6Kpv3YIicX3DRPOMI1r-kw-Ymh7sb1SLLz4EElhxAWsH0Z_7U7qu-nGhdHWNkon26k8iO2-tSYXDw9r-uFJ_F1hyqpXp5cvU5ivtCVUPru5pqWsIKFfw4r4mMo6TD2hHudTE99njFu-B06e2P9puSF2wVGSuJoIfUI0eelKs29_kK3F9aFannbLdfWxmY4pImKh9-kW-AOBc-qemGWSSe-aAAyB1g6vnP3xzc1Qj8UubcCFDxX1ior-pCfhT_-DTgiksrqlJmIrc2qY-XLHOEZeiYwMLQ128FjYVBL0mzr0EmUcUEBNDvYrvtJRL_wJ_g61EQQpywGb4s6wQw_V6iJWXi_TNPw4UBMZ0WkVGVAn4gAVMnvtKnrqsdKNbpu4_mUoI4yqutBUc_xTqs7nq8LlkQqoC7symx1qJVtbk9NgP4-WsC2I1qhF4KkDhEgdQgiRNf_u30I4-4eC-OgsXp576TZatPp4ud4lC0rD8Tk=w1024-h576-no);background-repeat: no-repeat;background-attachment: fixed;position:absolute;top:0;bottom:0;left:0;right:0;background-size: cover; background: #000 center center no-repeat; background-size: 100%;"><center>Please wait...</center></div>';
                            echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$p_id.'&bid='.$bid.'&action=postblog&blink='.$blink.'&autopost='.$autopost.'";}, 30 );</script>'; 
                        }
                    }
                    // if false video


                    /*upload photo first*/
                    $imgur = false;        
                    if(!empty($vid)) {
                        $imgUrl = $picture;
                        
                        $structure = FCPATH . 'uploads/image/';
                        if (!file_exists($structure)) {
                            mkdir($structure, 0777, true);
                        }
                        $imgUrl = str_replace('maxresdefault', 'hqdefault', $imgUrl);
                        $file_title = basename($imgUrl);
                        $fileName = FCPATH . 'uploads/image/'.$pid.$file_title;

                        if (!preg_match('/ytimg.com/', $fileName)) {
                            $imgUrl = $picture;
                        }    

                        if(!preg_match('/blogspot.com/', $fileName) || !preg_match('/googleusercontent.com/', $fileName)) {
                            copy($imgUrl, $fileName);      
                            $param = array(
                                'btnplayer'=>$pOption->btnplayer,
                                'playerstyle'=>$pOption->playerstyle,
                                'imgcolor'=>$pOption->imgcolor,
                                'txtadd'=>$pOption->txtadd,
                                'filter_brightness'=>$pOption->filter_brightness,
                                'filter_contrast'=>$pOption->filter_contrast,
                                'img_rotate'=>$pOption->img_rotate,
                            );
                            if(!empty($pOption->foldlink)) {
                                $image = $pConent->picture;
                            } else {
                                $image = $this->mod_general->uploadMedia($fileName,$param);
                            }                            
                        } else {
                            $image = $picture;
                        }
                        $post_by_manaul = $pOption->post_by_manaul;
                        if(!empty($image)) {
                            @unlink($fileName);
                            if(empty($pOption->post_by_manaul)) {
                                $imgur = true;
                                /*End upload photo first*/
                                if(!empty($pOption->foldlink)) {
                                    $link = @$pConent->mainlink;
                                } else {
                                    $blogData = $this->postToBlogger($bid, $vid, $title,$image,$message,$blink);
                                    $link = @$blogData->url; 
                                }                                
                                $mainlink = $link; 
                                /*End Post to Blogger first*/

                                /*blog link*/
                                if(!empty($link)) {
                                    if(!empty($blink) && $blink == 1) {
                                        //set blog link by ID
                                        if(!empty($blog_link_id)) {
                                             $blogRand = $blog_link_id;
                                        } else {
                                            /*get blog link from database*/
                                            $where_link = array(
                                                'c_name'      => 'blog_linkA',
                                                'c_key'     => $log_id,
                                            );
                                            $query_blog_link = $this->Mod_general->select('au_config', '*', $where_link);
                                            if (!empty($query_blog_link[0])) {
                                                $data = json_decode($query_blog_link[0]->c_value);
                                                $big = array();
                                                foreach ($data as $key => $blog) {
                                                    if($blog->status ==1) {
                                                        $big[] = $blog->bid;
                                                    }                                
                                                }
                                                $brand = mt_rand(0, count($big) - 1);
                                                $blogRand = $big[$brand];
                                            }
                                        }
                                         
                                        if(!empty($blogRand)) {
                                            $bodytext = '<meta content="'.$image.'" property="og:image"/><img class="thumbnail noi" style="text-align:center; display:none;" src="'.$image.'"/><h2>'.$thai_title.'</h2><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td colspan="3" style="background:#000000;height: 280px;overflow: hidden;background: no-repeat center center;background-size: cover; background: #000 center center no-repeat; background-size: 100%;border: 1px solid #000; background-image:url('.$image.');"><a href="'.$link.'" target="_top" rel="nofollow" style="display:block;height:280px;width:100%; text-align:center; background:url(https://3.bp.blogspot.com/-3ii7X_88VLs/XEs-4wFXMXI/AAAAAAAAiaw/d_ldK-ae830UCGsyOl0oEqqwDQwd_TqEACLcBGAs/s90/youtube-play-button-transparent-png-15.png) no-repeat center center;">&nbsp;</a></td></tr><tr><td style="background:#000 url(https://2.bp.blogspot.com/-Z_lYNnmixpM/XEs6o1hpTUI/AAAAAAAAiak/uPb1Usu-F-YvHx6ivxnqc1uSTIAkLIcxwCLcBGAs/s1600/l.png) no-repeat bottom left; height:39px; width:237px;margin:0;padding:0;"><a href="'.$link.'" target="_top" rel="nofollow" style="display:block;height:39px;width:100%;">&nbsp;</a></td><td style="background:#000 url(https://1.bp.blogspot.com/-9nWJSQ3HKJs/XEs6o7cUv2I/AAAAAAAAiag/sAiHoM-9hKUOezozem6GvxshCyAMp_n_QCLcBGAs/s1600/c.png) repeat-x bottom center; height:39px;margin:0;padding:0;">&nbsp;</td><td style="background:#000 url(https://2.bp.blogspot.com/-RmcnX0Ej1r4/XEs6o-Fjn9I/AAAAAAAAiac/j50SWsyrs8sA5C8AXotVUG7ESm1waKxPACLcBGAs/s1600/r.png) no-repeat bottom right; height:39px; width:151px;margin:0;padding:0;">&nbsp;</td></tr></table><!--more--><a id="myCheck" href="'.$link.'"></a><script>//window.opener=null;window.setTimeout(function(){if(typeof setblog!="undefined"){var link=document.getElementById("myCheck").href;var hostname="https://"+window.location.hostname;links=link.split(".com")[1];link0=link.split(".com")[0]+".com";document.getElementById("myCheck").href=hostname.links;document.getElementById("myCheck").click();};if(typeof setblog=="undefined"){document.getElementById("myCheck").click();}},2000);</script><br/>' . $message;
                                            $title = (string) $title;
                                            $dataContent          = new stdClass();
                                            $dataContent->setdate = false;        
                                            $dataContent->editpost = false;
                                            $dataContent->pid      = 0;
                                            $dataContent->customcode = '';
                                            $dataContent->bid     = $blogRand;
                                            $dataContent->title    = $title . ' '. $bid . '-blid-'.$blogRand;        
                                            $dataContent->bodytext = $bodytext;
                                            $dataContent->label    = 'blink';
                                            $DataBlogLink = $this->postBlogger($dataContent);
                                            $link = $DataBlogLink->url;
                                        }
                                    }
                                    /*End blog link*/

                                    /*update post*/
                                    if(!empty($link)) {
                                        $whereUp = array('p_id' => $pid);
                                        $content = array (
                                            'name' => $pConent->name,
                                            'message' => $pConent->message,
                                            'caption' => $pConent->caption,
                                            'link' => $link,
                                            'mainlink' => $mainlink,
                                            'picture' => @$image,                            
                                        );
                                        $dataPostInstert = array (
                                            Tbl_posts::conent => json_encode ( $content ),
                                            'p_post_to' => 0,
                                            'yid' => $vid,
                                        );
                                        $this->Mod_general->update( Tbl_posts::tblName,$dataPostInstert, $whereUp);
                                    }
                                    /*End update post*/
                                } 
                            } else {
                                /*update post*/
                                $whereUp = array('p_id' => $pid);
                                $content = array (
                                    'name' => $pConent->name,
                                    'message' => $pConent->message,
                                    'caption' => $pConent->caption,
                                    'link' => $pConent->link,
                                    'picture' => @$image,                            
                                );
                                $dataPostInstert = array (
                                    Tbl_posts::conent => json_encode ( $content ),
                                    'p_post_to' => 2,
                                    'yid' => $vid,
                                );
                                $updates = $this->Mod_general->update( Tbl_posts::tblName,$dataPostInstert, $whereUp);
                                /*End update post*/
                            }
                        }

                        /*update youtube if autopost*/
                        if(!empty($autopost)) {
                            $whereYtup = array(
                                'yid' => $vid,
                                'y_fid' => $fbid,
                            );
                            $ytInstert = array (
                                'y_status' => 1,
                            );
                            $updates = $this->Mod_general->update( 'youtube',$ytInstert, $whereYtup);
                        }
                        /*End update youtube if autopost*/
                    }                    


                    

                    /*check next post*/
                    $whereNext = array (
                        'user_id' => $log_id,
                        'u_id' => $fbUserId,
                        'p_post_to' => 1,
                    );
                    $nextPost = $this->Mod_general->select ( Tbl_posts::tblName, 'p_id', $whereNext );
                    if(!empty($nextPost[0])) {
                        $p_id = $nextPost[0]->p_id;
                        $autopost = $this->input->get('autopost');
                        if(count($postsLoop)>5) {
                            echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$p_id.'&bid=' . $bid . '&action=generate&blink='.$blink.'&autopost=1&blog_link_id='.$blogRand.'";}, 30 );</script>'; 
                        } else {
                            echo '<link href="https://fonts.googleapis.com/css?family=Koulen" rel="stylesheet"><div style="background-repeat: no-repeat;background-attachment: fixed;position:absolute;top:0;bottom:0;left:0;right:0;background-size: cover; background:url(https://lh3.googleusercontent.com/O9Zb5ANk53CjsRCCKeruCsDRrlCgQceDg0aRPEQSLWxKIFuD0Vn5Yq5zDu__wWUB7gkCiaabTxuv2Gl_Tv5vTGELVtJYIjL1i4MxrPTZksCWRP4st9xh8mExLkleNhvYx9O4XFKP3LlKEJsP463XW1mCJg4lxUlP9EUQX1ob3VXrSAt_mi55P6Kpv3YIicX3DRPOMI1r-kw-Ymh7sb1SLLz4EElhxAWsH0Z_7U7qu-nGhdHWNkon26k8iO2-tSYXDw9r-uFJ_F1hyqpXp5cvU5ivtCVUPru5pqWsIKFfw4r4mMo6TD2hHudTE99njFu-B06e2P9puSF2wVGSuJoIfUI0eelKs29_kK3F9aFannbLdfWxmY4pImKh9-kW-AOBc-qemGWSSe-aAAyB1g6vnP3xzc1Qj8UubcCFDxX1ior-pCfhT_-DTgiksrqlJmIrc2qY-XLHOEZeiYwMLQ128FjYVBL0mzr0EmUcUEBNDvYrvtJRL_wJ_g61EQQpywGb4s6wQw_V6iJWXi_TNPw4UBMZ0WkVGVAn4gAVMnvtKnrqsdKNbpu4_mUoI4yqutBUc_xTqs7nq8LlkQqoC7symx1qJVtbk9NgP4-WsC2I1qhF4KkDhEgdQgiRNf_u30I4-4eC-OgsXp576TZatPp4ud4lC0rD8Tk=w1024-h576-no); center center no-repeat; background-size: 100%;"><div style="background: rgba(255, 255, 255, 0.38);text-align:center;font-size:20px;padding:40px;font-family: Hanuman, serif;font-size: 30px;color: #fff;text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.5);">សូមមេត្តារង់ចាំ<br/>Please wait...<br/><table align="center" class="table table-hover table-striped table-bordered table-highlight-head"> <tbody> <tr> <td align="left" valign="middle">Post</td><td align="left" valign="middle">'.count($nextPost).'</td></tr><tr> <td align="left" valign="middle">Post ID: </td><td align="left" valign="middle">'.$p_id.'</td></tr></tbody></table></div></div>';
                            echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$p_id.'&bid='.$bid.'&action=postblog&blink='.$blink.'&autopost='.$autopost.'";}, 30 );</script>';
                        } 
                    } else {
                        if(!empty($autopost)) {
                            redirect(base_url() . 'facebook/shareation?post=getpost');
                        } else {
                            redirect(base_url() . 'managecampaigns?m=post_success&post_by_manaul=' . @$post_by_manaul);
                        }                        
                    }
                    /*End check next post*/
                }                
            }
        }
        /*End Post to blogger*/
        $this->load->view ( 'managecampaigns/yturl', $data );
    }

    public function blogData()
    {
        $bid = !empty($this->input->get('bid'))? $this->input->get('bid') : '';
        $this->load->library ( 'html_dom' );
        $url = 'http://www.blogger.com/feeds/'.$bid.'/posts/default?max-results=1&alt=json-in-script';
        $response = file_get_contents($url);
        $response = str_replace('gdata.io.handleScriptLoaded({', '{',$response);
        $response = str_replace('}}]}});', '}}]}}',$response);
        $html = json_decode($response); 

        if(!empty($this->input->get('totalResults'))) {
            $data = $html->feed->{'openSearch$totalResults'}->{'$t'};
        }
        return $data;
    }
    public function testimage()
    {

        $imgUrl = 'https://i.ytimg.com/vi/5nHYH4O27Jw/hqdefault.jpg';
        $file_title = basename($imgUrl);
        $fileName = FCPATH . 'uploads/image/'.$file_title;
        copy($imgUrl, $fileName);
        $image = $this->Mod_general->uploadMediaWithText($fileName);
        die;
    }
    public function postBlogger($dataContent)
    {
        /*prepare post*/
        $this->load->library('google_api');
        $client = new Google_Client();
        $client->setAccessToken($this->session->userdata('access_token'));
        $service = new Google_Service_Blogger($client);
        $posts   = new Google_Service_Blogger_Post();        
        return $this->Mod_general->blogger_post($client,$dataContent);
    }

    public function postToBlogger($bid, $vid, $title,$image,$conent='',$blink)
    {

        /*prepare post*/
        $this->load->library('google_api');
        $client = new Google_Client();
        $client->setAccessToken($this->session->userdata('access_token'));

        $service = new Google_Service_Blogger($client);
        $posts   = new Google_Service_Blogger_Post();

        $strTime = strtotime(date("Y-m-d H:i:s"));
        $dataContent          = new stdClass();

        switch ($blink) {
            case '2':
                $dataMeta = array(
                    'titleEn' => $title,
                    'image' => $image,
                    'videoID' => $vid
                );
                $customcode = json_encode($dataMeta);
                $bodytext = '<link href="'.$image.'" rel="image_src"/><meta content="'.$image.'" property="og:image"/><img class="thumbnail noi" style="text-align:center" src="'.$image.'"/><!--more--><div id="ishow"></div><div><b>'.$title.'</b></div><div class="wrapper"><div class="small"><p>'.$conent.'</p></div> <a class="readmore" href="#">... Click to read more</a></div><div style="text-align: center;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" ></script><script>document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});</script></div><div id="cshow"></div><div style="text-align: center;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" ></script><script>document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});</script></div>';
                break;
            case 'link':
                $bodytext = '<link href="'.$image.'" rel="image_src"/><meta content="'.$image.'" property="og:image"/><img class="thumbnail noi" style="text-align:center" src="'.$image.'"/><!--more--><div id="ishow"></div><div><b>'.$title.'</b></div><div class="wrapper"><div class="small"><p>'.$conent.'</p></div> <a class="readmore" href="#">... Click to read more</a></div><div style="text-align: center;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" ></script><script>document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});</script></div><div>คลิปดูวีดีโอ==>> <a href="https://youtu.be/'.$vid.'" target="_blank"> https://youtu.be/'.$vid.'</a></div><div style="text-align: center;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" ></script><script>document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});</script></div>';
                $label = 'link';
                $customcode = '';
                break;
            default:
                $bodytext = '<img class="thumbnail noi" style="text-align:center" src="'.$image.'"/><!--more--><div><b>'.$title.'</b></div><div class="wrapper"><div class="small"><p>'.$conent.'</p></div> <a href="#" class="readmore">... Click to read more</a></div><div style="text-align: center;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" ></script><script>document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});</script></div><div>Others news:</div><iframe width="100%" height="280" src="https://www.youtube.com/embed/'.$vid.'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe><div style="text-align: center;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" ></script><script>document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});</script></div>';
                $customcode = '';
                break;
        }
        $bodytext = str_replace("<br />", "\n", $bodytext);
        $title = (string) $title;
        
        $dataContent->setdate = false;        
        $dataContent->editpost = false;
        $dataContent->pid      = 0;
        $dataContent->customcode = $customcode;
        $dataContent->bid     = $bid;
        $dataContent->title    = $title;        
        $dataContent->bodytext = $bodytext;
        if(!empty($label)) {
            $dataContent->label    = $label;
        } else {
            $dataContent->label    = 'default';
        }
        return $this->Mod_general->blogger_post($client,$dataContent);

        /*end prepare post*/
    }

    public function fromyoutube()
    {
        if(empty($this->session->userdata('access_token'))) {
            redirect(base_url() . 'managecampaigns/account');
        }
        $this->Mod_general->checkUser ();
        $log_id = $this->session->userdata ( 'user_id' );
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );
        $this->load->theme ( 'layout' );
        $data ['title'] = 'Add from youtube Channel';

        /*breadcrumb*/
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add('Post', base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('From Youtube Channel', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        /*End breadcrumb*/

        /*from form*/
        if ($this->input->post ( 'ytid' )) {
            $this->load->library('google_api');
            $client = new Google_Client();
            if ($this->session->userdata('access_token')) {
                $client->setAccessToken($this->session->userdata('access_token'));
            }
        }
        /*End from form*/

        $this->load->view ( 'managecampaigns/fromyoutube', $data );
    }
	public function add() {
		$this->Mod_general->checkUser ();
		$actions = $this->uri->segment ( 3 );
		$id = ! empty ( $_GET ['id'] ) ? $_GET ['id'] : '';
		$log_id = $this->session->userdata ('user_id');
        $sid = $this->session->userdata ( 'sid' );
		$this->Mod_general->checkUser ();
		$user = $this->session->userdata ( 'email' );
		$provider_uid = $this->session->userdata ( 'provider_uid' );
		$provider = $this->session->userdata ( 'provider' );
		$this->load->theme ( 'layout' );
		$data ['title'] = 'Admin Area :: Manage Campaigns';
		
		/* get post for each user */
        $id = explode(',', $id);
        $where_so['where_in'] = array('user_id' => $log_id,Tbl_posts::id => $id);
		$dataPost = $this->Mod_general->select ( Tbl_posts::tblName, '*', $where_so );
		$data ['data'] = $dataPost;
		/* end get post for each user */
		
		/* get User for each user */
		$where_u= array (
				'user_id' => $log_id,
				Tbl_user::u_status => 1 
		);
		$dataAccount = $this->Mod_general->select ( Tbl_user::tblUser, '*', $where_u );
		$data ['account'] = $dataAccount;
		/* end get User for each user */

        /* get User groups type */
        $where_gu= array (
                'l_user_id' => $log_id, 
                'l_sid' => $sid, 
        );
        $dataAccountg = $this->Mod_general->select ( 'group_list', 'l_id, lname', $where_gu );
        $data ['groups_type'] = $dataAccountg;
        /* end get User groups type */
		
		$ajax = base_url () . 'managecampaigns/ajax?gid=';
		$data ['js'] = array (
				'themes/layout/blueone/plugins/validation/jquery.validate.min.js',
				'themes/layout/blueone/plugins/pickadate/picker.js',
				'themes/layout/blueone/plugins/pickadate/picker.time.js' 
		);
        $fbuids = $this->session->userdata('fb_user_id');
		$data ['addJsScript'] = array (
				"
        $(document).ready(function() {
            var gid = $(\"#togroup\").val();
            if(gid) {
            $.ajax
            ({
                type: \"get\",
                url: \"$ajax\"+gid+'&p=getgrouptype',
                cache: false,
                success: function(html)
                {
                    $('#groupWrapLoading').hide();
                    $(\"#getAllGroups\").html(html);
                    $(\"#groupWrap\").show();
                    $(\"#checkAll\").prop( \"checked\", true );
                }
            });
        }
            $(\"#groupWrap\").hide();            
            $('#togroup').change(function () {
                var gid = $(this).val();
                if(gid) {
                    $('#groupWrapLoading').show();
                    $.ajax
                    ({
                        type: \"get\",
                        url: \"$ajax\"+gid+'&p=getgrouptype',
                        cache: false,
                        success: function(html)
                        {
                            $('#groupWrapLoading').hide();
                            $(\"#getAllGroups\").html(html);
                            $(\"#groupWrap\").show();
                        }
                    });
                     $('#showgroum').hide();
                } else {
                    $('#showgroum').show();
                }
            });
        
        $('#towall').click(function () {
            if($(this).is(\":checked\")) {
                $(\"#groupWrap\").hide();
            }
        });
        $('#Groups').change(function () {
            if($(this).val()){
                $('#showgroum').hide();
                $('#togroup').prop('checked', false);
                $('#checkAll').prop('checked', false);
                $('#groupWrap').hide();
            } else {
                $('#showgroum').show();
            }
        });
        $('#checkAll').click(function() {
            $('.tgroup').not(this).prop('checked', this.checked);
         });
         
         $('#addGroups').click(function () {
            if (!$('.tgroup:checked').val()) {
                alert('please select one');
            } else {
                var checkbox_value = '';
                $(\".tgroup\").each(function () {
                    var ischecked = $(this).is(\":checked\");
                    if (ischecked) {
                        checkbox_value += $(this).val() + \"|\";
                    }
                });
                
                var gid = $('#Groups').val();
                var postID = $('#postID').val();
                $.ajax
                    ({
                        type: \"get\",
                        url: \"$ajax\"+gid+'&p=addgroup&g='+checkbox_value+'&pid='+postID,
                        cache: false,
                        success: function(html)
                        {
                            var success = generate('success');
                            setTimeout(function () {
                                $.noty.setText(success.options.id, html+' Groups has been added');
                            }, 1000);
                            setTimeout(function () {
                                $.noty.closeAll();
                            }, 4000);
                        }
                    });
            }
         });
 
         
         $(\"#datepicker\").datepicker({
              changeMonth: true,
              changeYear: true,
              dateFormat: 'mm-dd-yy'
            });
            
         $(\"#datepickerEnd\").datepicker({
              changeMonth: true,
              changeYear: true,
              dateFormat: 'mm-dd-yy'
            });
         $('#timepicker').pickatime({format: 'H:i:00' });
         $('#timepickerEnd').pickatime({format: 'H:i:00' });
         $.validator.addClassRules('required', {
            required: true
         });
         $('#validate').validate();
     });
    " 
		);
		
		/* get form */
		if ($this->input->post ( 'submit' )) {
            $title = $this->input->post ( 'title' );
            $PrefixTitle = $this->input->post ( 'Prefix' );
			$SuffixTitle = $this->input->post ( 'addtxt' );
			$thumb = $this->input->post ( 'thumb' );
			$message = $this->input->post ( 'message' );
			$caption = $this->input->post ( 'caption' );

            $link = $this->input->post ( 'link' );
			$short_link = $this->input->post ( 'shortlink' );

			$accoung = $this->input->post ( 'accoung' );
			$postTo = $this->input->post ( 'postto' );
			$itemId = $this->input->post ( 'itemid' );
            $postTypes = $this->input->post ( 'postType' );
            $post_action = $this->input->post ( 'paction' );
			$postType = $this->input->post ( 'ptype' );
			$startDate = $this->input->post ( 'startDate' );
			$startTime = $this->input->post ( 'startTime' );
			$endDate = $this->input->post ( 'endDate' );
			$loopEvery = $this->input->post ( 'loop' );
			$loopEveryMinute = $this->input->post ( 'minuteNum' );
			$loopEveryHour = $this->input->post ( 'hourNum' );
			$loopEveryDay = $this->input->post ( 'dayNum' );
			$looptype = $this->input->post ( 'looptype' );
			$loopOnDay = $this->input->post ( 'loopDay' );
			$itemGroups = $this->input->post ( 'itemid' );
			$postId = $this->input->post ( 'postid' );
			$pauseBetween = $this->input->post ( 'pauseBetween' );
            $pause = $this->input->post ( 'pause' );
			$ppause = $this->input->post ( 'ppause' );

            $random = $this->input->post ( 'random' );
            $random_link = $this->input->post ( 'randomlink' );
			$share_type = $this->input->post ( 'sharetype' );

            $account_gtype = $this->input->post ( 'groups' );
            $userAgent = $this->input->post ( 'useragent' );
            $checkImage = @$this->input->post ( 'cimg' );
			
			/* check account type */
			$s_acount = explode ( '|', $accoung );
			/* end check account type */
			/* data schedule */
			switch ($loopEvery) {
				case 'm' :
					$loopOnEvery = array (
							$loopEvery => $loopEveryMinute 
					);
					break;
				
				case 'h' :
					$loopOnEvery = array (
							$loopEvery => $loopEveryHour 
					);
					break;
				
				case 'd' :
					$loopOnEvery = array (
							$loopEvery => $loopEveryDay 
					);
					break;
			}
			
			$days = array ();
            if(!empty($loopOnDay)) {
    			foreach ( $loopOnDay as $dayLoop ) {
    				if (! empty ( $dayLoop )) {
    					$days [] = $dayLoop;
    				}
    			}
            }
			$schedule = array (                    
					'start_date' => @$startDate,
					'start_time' => @$startTime,
					'end_date' => @$endDate,
					'end_time' => @$endDate,
					'loop' => @$looptype,
					'loop_every' => @$loopOnEvery,
					'loop_on' => @$days,
                    'wait_group' => @$pause,
					'wait_post' => @$ppause,
					'randomGroup' => @$random,
                    'prefix_title' => @$PrefixTitle,
                    'suffix_title' => @$SuffixTitle,
                    'short_link' => @$short_link,
                    'check_image' => @$checkImage,
                    'random_link' => @$random_link,
                    'share_type' => @$share_type,
                    'share_schedule' => @$post_action,
                    'account_group_type' => @$account_gtype,
			);
			/* end data schedule */  
			if (!empty($link)) {

                for ($i = 0; $i < count($link); $i++) {
				/*** add data to post ***/
                                     

                    /* data content */
                    $content = array (
                            'name' => @$title[$i],
                            'message' => @$message[$i],
                            'caption' => @$caption[$i],
                            'link' => @$link[$i],
                            'picture' => @$thumb[$i],                            
                    );
                    /* end data content */
                    @iconv_set_encoding("internal_encoding", "TIS-620");
                    @iconv_set_encoding("output_encoding", "UTF-8");   
                    @ob_start("ob_iconv_handler");
    				$dataPostInstert = array (
    						Tbl_posts::name => $this->remove_emoji($title[$i]),
    						Tbl_posts::conent => json_encode ( $content ),
    						Tbl_posts::p_date => date('Y-m-d H:i:s'),
    						Tbl_posts::schedule => json_encode ( $schedule ),
                            Tbl_posts::user => $s_acount[0],
    						'user_id' => $log_id,
                            Tbl_posts::post_to => $postTo,
    						'p_status' => $postTypes,
    						Tbl_posts::type => @$s_acount[1] 
    				);
                    @ob_end_flush();
    				if (! empty ( $postId )) {
    					$AddToPost = $postId[$i];
    					$this->Mod_general->update ( Tbl_posts::tblName, $dataPostInstert, array (
    							Tbl_posts::id => $postId[$i]
    					) );
    				} else {
    					$AddToPost = $this->Mod_general->insert ( Tbl_posts::tblName, $dataPostInstert );
    				}
    				/* end add data to post */
    				
    				/* add data to group of post */
    				if(!empty($itemGroups)) {

                        /*if Edit post clear old groups before adding new*/
                        if (! empty ( $postId )) {
                            $this->Mod_general->delete ( Tbl_share::TblName, array (
                                    'p_id' => $AddToPost,
                                    'social_id' => @$s_acount[0],
                            ) );
                        }
                        /*End if Edit post clear old groups before adding new*/
                        // $strto = strtotime($startDate . ' ' . $startTime);
                        // $cPost = date("Y-m-d H:i:s",$strto);
                        if($post_action == 1) {
                            $date = DateTime::createFromFormat('m-d-Y H:i:s',$startDate . ' ' . $startTime);
                            $cPost = $date->format('Y-m-d H:i:s');
                        } else {
                            $cPost = date('Y-m-d H:i:s');
                        }     
                        $ShContent = array (
                            'userAgent' => @$userAgent,                            
                        );                   
        				foreach($itemGroups as $key => $groups) { 
                            if(!empty($groups)) {       
                				$dataGoupInstert = array(
                    				'p_id' => $AddToPost,
                    				'sg_page_id' => $groups,
                    				'social_id' => @$s_acount[0],
                                    'sh_social_type' => @$s_acount[1],
                                    'sh_type' => $postType,
                                    'c_date' => $cPost,
                                    'uid' => $log_id,  
                                    'sh_option' => json_encode($ShContent),                                  
                				);
                				$AddToGroup = $this->Mod_general->insert(Tbl_share::TblName, $dataGoupInstert);
                            }
        				}
                        
    				}
    				/* end add data to group of post */
                    //
                }
                
			}
			redirect ( base_url () . 'managecampaigns' );
		}
		/* end form */
		$this->load->view ( 'managecampaigns/add', $data );
	}

    function remove_emoji($text){
         $clean_text = "";

        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text = preg_replace($regexEmoticons, '', $text);

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text = preg_replace($regexSymbols, '', $clean_text);

        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text = preg_replace($regexTransport, '', $clean_text);

        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        $clean_text = preg_replace($regexMisc, '', $clean_text);

        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace($regexDingbats, '', $clean_text);

        // Match Flags
        $regexDingbats = '/[\x{1F1E6}-\x{1F1FF}]/u';
        $clean_text = preg_replace($regexDingbats, '', $clean_text);

        // Others
        $regexDingbats = '/[\x{1F910}-\x{1F95E}]/u';
        $clean_text = preg_replace($regexDingbats, '', $clean_text);

        $regexDingbats = '/[\x{1F980}-\x{1F991}]/u';
        $clean_text = preg_replace($regexDingbats, '', $clean_text);

        $regexDingbats = '/[\x{1F9C0}]/u';
        $clean_text = preg_replace($regexDingbats, '', $clean_text);

        $regexDingbats = '/[\x{1F9F9}]/u';
        $clean_text = preg_replace($regexDingbats, '', $clean_text);

        return $clean_text;
    }
	public function fromurl() {
		$data ['title'] = 'Get from url';
		// $this->Mod_general->checkUser();
		// $backto = base_url() . 'post/blogpassword';
		// $query_blog = $this->Mod_general->blogcheck(current_url(), $backto);
		$log_id = $this->session->userdata ( 'user_id' );
		
		/* Sidebar */
		// $menuPermission = $this->Mod_general->getMenuUser();
		// $data['menuPermission'] = $menuPermission;
		/* form */
		if ($this->input->post ( 'submit' )) {
			$videotype = '';
			$this->load->library ( 'form_validation' );
			$this->form_validation->set_rules ( 'blogid', 'blogid', 'required' );
			if ($this->form_validation->run () == true) {
				$xmlurl = $this->input->post ( 'blogid' );
				$thumb = $this->input->post ( 'imageid' );
				$title = $this->input->post ( 'title' );
				$code = $this->get_from_url_id ( $xmlurl, $thumb );
				if (! empty ( $code )) {
					$dataPostID = $this->addToPost ( $code ['name'], $code );
					if ($dataPostID) {
						redirect ( base_url () . 'managecampaigns/add?id=' . $dataPostID );
					}
				}
			}
			die ();
		}
		/* end form */
		
		/* show to view */
		
		$data ['js'] = array (
				'themes/layout/blueone/plugins/validation/jquery.validate.min.js' 
		);
		$data ['addJsScript'] = array (
				"$(document).ready(function(){
                $.validator.addClassRules('required', {
                required: true
                });                
            });
            $('#validate').validate();
            " 
		);
		$this->load->view ( 'managecampaigns/fromurl', $data );
	}
	
	/**
	 * *
	 * Get post from network blog
	 */
	public function networkblogs() {
		$log_id = $this->session->userdata ( 'user_id' );
		$data ['title'] = 'Get from Networkblogs';
		
		$url = base_url () . 'managecampaigns/ajax?p=networkblogs';
		$baseUrl = base_url () . 'managecampaigns/networkblogs?id=';
		$data ['addJsScript'] = array (
				"$('#checkAll').click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 }); $('#addUrl').click(function () {
		var url = $('#url').val(); 
		var title = $('#title').val(); 
		if(url) {
				$.ajax
                        ({
                            type: \"get\",
                            url: \"$url\",
                            data: {id: url,t: title},
                            cache: false,
                            datatype: 'json',
                            success: function(data)
                            {
								var json = $.parseJSON(data);
                                if(json.result) {
									window.location = \"$baseUrl\" + json.result;
								}
                            } 
                        });
		}
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
		
		$where = array (
				Tbl_networkBlog::userID => $log_id 
		);
		$this->load->library ( 'pagination' );
		$per_page = (! empty ( $_GET ['result'] )) ? $_GET ['result'] : 10;
		$config ['base_url'] = base_url () . 'post/movies/';
		$count_blog = $this->Mod_general->select ( Tbl_networkBlog::Tbl, '*' );
		$config ['total_rows'] = count ( $count_blog );
		$config ['per_page'] = $per_page;
		$config = $this->Mod_general->paginations ( $config );
		$page = ($this->uri->segment ( 3 )) ? $this->uri->segment ( 3 ) : 0;
		$query_blog = $this->Mod_general->select ( Tbl_networkBlog::Tbl, '*', $where, "ntb_id DESC", '', $config ['per_page'], $page );
		$data ['dataList'] = $query_blog;
		$this->load->view ( 'managecampaigns/networkblogs', $data );
	}
	
	/**
	 * *
	 * Get post from network blog list
	 */
	public function ntblist() {
		$log_id = $this->session->userdata ( 'user_id' );
		$data ['title'] = 'Get from Networkblogs';
		$id = ($this->uri->segment ( 3 )) ? $this->uri->segment ( 3 ) : 0;
		$next = ($this->uri->segment ( 4 )) ? $this->uri->segment ( 4 ) : 0;
		$url = base_url () . 'managecampaigns/ajax?p=networkblogs';
		$baseUrl = base_url () . 'managecampaigns/networkblogs?id=';
		$data ['addJsScript'] = array (
				"$('#checkAll').click(function () {
				$('input:checkbox').not(this).prop('checked', this.checked);
	}); $('#addUrl').click(function () {
				var url = $('#url').val();
				var title = $('#title').val();
				if(url) {
				$.ajax
				({
				type: \"get\",
				url: \"$url\",
				data: {id: url,t: title},
				cache: false,
				datatype: 'json',
				success: function(data)
				{
				var json = $.parseJSON(data);
				if(json.result) {
				window.location = \"$baseUrl\" + json.result;
	}
	}
	});
	}
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
		
		$where = array (
				Tbl_networkBlog::userID => $log_id,
				Tbl_networkBlog::id => $id 
		);
		$query_blog = $this->Mod_general->select ( Tbl_networkBlog::Tbl, '*', $where );
		if (! empty ( $query_blog [0] )) {
			$cursors = $query_blog [0]->{Tbl_networkBlog::cursor};
			if (! empty ( $next )) {
				$getData = $query_blog [0]->{Tbl_networkBlog::url} . '/posts?offset=' . $next . '&limit=10&cursor=' . @$cursors . '&parent_page_name=source';
			} else {
				$getData = $query_blog [0]->{Tbl_networkBlog::url} . '/posts';
			}
			$dataNtb = json_decode ( file_get_contents ( $getData ) );
			$htmls = $dataNtb->html->{'divStream+'};
			$this->mod_general->update ( Tbl_networkBlog::Tbl, array (
					Tbl_networkBlog::cursor => $dataNtb->cursor 
			), array (
					Tbl_networkBlog::id => $id 
			) );
		}
		$this->load->library ( 'html_dom' );
		$str = <<<HTML
$htmls
HTML;
		$html = str_get_html ( $str );
		$dataArr = array ();
		$i = 0;
		foreach ( $html->find ( 'script' ) as $e ) {
			$i ++;
			$getCode = explode ( '"', $e->innertext );
			$ntbLink = $getCode [1];
			$image = $getCode [5];
			$realLink = $getCode [13];
			$title = $getCode [17];
			$dataArr [$i] ['ntbLink'] = $ntbLink;
			$dataArr [$i] ['image'] = $image;
			$dataArr [$i] ['realLink'] = $realLink;
			$dataArr [$i] ['title'] = $title;
		}
		$data ['dataList'] = $dataArr;
		$this->load->view ( 'managecampaigns/ntblist', $data );
	}
	public function delete() {
		$actions = $this->uri->segment ( 3 );
		$id = $this->uri->segment ( 4 );
		switch ($actions) {
			case "deletecampaigns" :
				$this->Mod_general->delete ( Tbl_posts::tblName, array (
						Tbl_posts::id => $id 
				) );
				redirect ( 'managecampaigns' );
				break;
			
			case "networkblogs" :
				$this->Mod_general->delete ( Tbl_networkBlog::Tbl, array (
						Tbl_networkBlog::id => $id 
				) );
				redirect ( 'managecampaigns/networkblogs' );
				break;
		}
	}
	function get_from_url_id($url, $image_id = '') {
		$this->Mod_general->checkUser ();
		$log_id = $this->session->userdata ( 'user_id' );
		/* Sidebar */
		if (! empty ( $url )) {
			$this->load->library ( 'html_dom' );
			$html = file_get_html ( $url );
			$title = @$html->find ( '.post-title a', 0 )->innertext;
			$title1 = @$html->find ( '.post-title', 0 )->innertext;
			if ($title) {
				$title = $html->find ( '.post-title a', 0 )->innertext;
			} elseif ($title1) {
				$title = $html->find ( '.post-title', 0 )->innertext;
			} else {
				$title = $html->find ( 'title', 0 )->innertext;
			}
			$postTitle = $title;
			$og_image = @$html->find ( 'meta [property=og:image]', 0 )->content;
			$image_src = @$html->find ( 'link [rel=image_src]', 0 )->href;
			if (! empty ( $image_src )) {
				$thumb = $image_src;
			} elseif (! empty ( $html->find ( 'meta [property=og:image]', 0 )->content )) {
				$thumb = $html->find ( 'meta [property=og:image]', 0 )->content;
			} else {
				$thumb = $image_id;
			}
			$thumb = $this->resize_image ( $thumb );
			$short_url = $this->get_bitly_short_url ( $url, BITLY_USERNAME, BITLY_API_KEY );
			$data = array (
					'picture' => @$thumb,
					'name' => trim ( $title ),
					'message' => trim ( $title ),
					'caption' => trim ( $title ),
					'description' => trim ( $title ),
					'link' => $short_url 
			);            
			if (! empty ( $data )) {
				return $data;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}

    function get_from_url($url='', $image_id = '') {
        if(!empty($this->input->get('url'))) {
            $url = $this->input->get('url');
        }
        $this->Mod_general->checkUser ();
        $log_id = $this->session->userdata ( 'user_id' );
        /* Sidebar */
        if (! empty ( $url )) {
            $this->load->library ( 'html_dom' );
            $html = file_get_html ( $url );
            $description = @$html->find ( 'meta[property=og:description]', 0 )->content;
            $vid = '';
            if (! empty ( $this->input->get('old') )) {
                $iframeCheck = @$html->find ( '#Blog1 iframe', 0 );
                if(empty($iframeCheck)) {
                    $title = @$html->find ( '#Blog1 h2', 0 )->innertext;        
                } else {
                    $iframe = @$html->find ( '#Blog1 iframe', 0 )->src;
                    $html1 = file_get_html ( $iframe );
                    $title = $html1->find ( 'title', 0 )->innertext;
                    $vid = $iframe;
                }                
            } else {
                $title = @$html->find ( 'meta[property=og:title]', 0 )->content;                
                $title1 = @$html->find ( '.post-title', 0 )->innertext;
                if (!$title) {
                    $title = $html->find ( '.post-title a', 0 )->innertext;
                } elseif ($title1) {
                    $title = $html->find ( '.post-title', 0 )->innertext;
                } else {
                    $title = $html->find ( 'title', 0 )->innertext;
                }
            }
            
            $postTitle = $title;
            $og_image = @$html->find ( 'meta [property=og:image]', 0 )->content;
            $image_src = @$html->find ( 'link [rel=image_src]', 0 )->href;
            if (! empty ( $image_src )) {
                $thumb = $image_src;
            } elseif (! empty ( $html->find ( 'meta [property=og:image]', 0 )->content )) {
                $thumb = $html->find ( 'meta [property=og:image]', 0 )->content;
            } else {
                $thumb = $image_id;
            }
            $thumb = $this->resize_image ( $thumb );
            $data = array (
                    'picture' => @$thumb,
                    'name' => trim ( $title ),
                    'message' => trim ( $title ),
                    'caption' => trim ( $title ),
                    'description' => trim ( $description ),
                    'link' => $url,
                    'vid' => $vid,
            );            
            if (! empty ( $data )) {
                if(!empty($this->input->get('url'))) {
                    echo json_encode($data);
                    exit();
                }
                return $data;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
	function resize_image($url, $imgsize = 0) {
		if (preg_match ( '/blogspot/', $url )) {
			// inital value
			$newsize = "s" . $imgsize;
			$newurl = "";
			// Get Segments
			$path = parse_url ( $url, PHP_URL_PATH );
			$segments = explode ( '/', rtrim ( $path, '/' ) );
			// Get URL Protocol and Domain
			$parsed_url = parse_url ( $url );
			$domain = $parsed_url ['scheme'] . "://" . $parsed_url ['host'];
			
			$newurl_segments = array (
					$domain . "/",
					$segments [1] . "/",
					$segments [2] . "/",
					$segments [3] . "/",
					$segments [4] . "/",
					$newsize . "/", // change this value
					$segments [6] 
			);
			$newurl_segments_count = count ( $newurl_segments );
			for($i = 0; $i < $newurl_segments_count; $i ++) {
				$newurl = $newurl . $newurl_segments [$i];
			}
			return $newurl;
		} else if (preg_match ( '/googleusercontent/', $url )) {
			// inital value
			$newsize = "s" . $imgsize;
			$newurl = "";
			// Get Segments
			$path = parse_url ( $url, PHP_URL_PATH );
			$segments = explode ( '/', rtrim ( $path, '/' ) );
			// Get URL Protocol and Domain
			$parsed_url = parse_url ( $url );
			$domain = $parsed_url ['scheme'] . "://" . $parsed_url ['host'];
			$newurl_segments = array (
					$domain . "/",
					$segments [1] . "/",
					$segments [2] . "/",
					$segments [3] . "/",
					$segments [4] . "/",
					$newsize . "/", // change this value
					$segments [6] 
			);
			$newurl_segments_count = count ( $newurl_segments );
			for($i = 0; $i < $newurl_segments_count; $i ++) {
				$newurl = $newurl . $newurl_segments [$i];
			}
			return $newurl;
		} else {
			return $url;
		}
	}
	
	/* returns the shortened url */
	function get_bitly_short_url($url, $login, $appkey, $format = 'txt') {
		$connectURL = 'http://api.bit.ly/v3/shorten?login=' . $login . '&apiKey=' . $appkey . '&uri=' . urlencode ( $url ) . '&format=' . $format;
		return $this->curl_get_result ( $connectURL );
	}
	
	/* returns expanded url */
	function get_bitly_long_url($url, $login, $appkey, $format = 'txt') {
		$connectURL = 'http://api.bit.ly/v3/expand?login=' . $login . '&apiKey=' . $appkey . '&shortUrl=' . urlencode ( $url ) . '&format=' . $format;
		return $this->curl_get_result ( $connectURL );
	}
	
	/* returns a result form url */
	function curl_get_result($url) {
		$ch = curl_init ();
		$timeout = 5;
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
		$data = curl_exec ( $ch );
		curl_close ( $ch );
		return $data;
	}
	
	/* returns a result form url */
	function ajax() {
		// getgroup
		$id = ! empty ( $_GET ['gid'] ) ? $_GET ['gid'] : '';
		$page = ! empty ( $_GET ['p'] ) ? $_GET ['p'] : '';
		$log_id = $this->session->userdata ( 'user_id' );
		$data = '';
		if ($log_id) {
			switch ($page) {
                case 'grouplist':
                    $where_gu= array (
                        'l_user_id' => $log_id, 
                        'l_sid' => $id, 
                    );
                    $dataAccountg = $this->Mod_general->select ( 'group_list', 'l_id, lname', $where_gu );
                    echo json_encode($dataAccountg);
                    break;
				case 'getgroup' :
                    $ids = explode('|', $id);
					$where_uGroup = array (
							Tbl_social_group::socail_id => $ids[0],
							Tbl_social_group::status => 1,
							Tbl_social_group::type => 'groups' 
					);
					$dataGroup = $this->Mod_general->select ( Tbl_social_group::tblName, '*', $where_uGroup );
					$i = 0;
					foreach ( $dataGroup as $gvalue ) {
						$i ++;
						$data .= '<label class="checkbox"><input type="checkbox" class="tgroup" name="itemid[]" value="' . $gvalue->sg_id . '"/>' . $i . ' - ' . $gvalue->sg_page_id . ' | ' . $gvalue->{
                            Tbl_social_group::name} . '</label>';
					}
					echo $data;
					break;
                case 'getgrouptype' :
                    $wGroupType = array (
                            'gu_grouplist_id' => $id,
                            'gu_user_id' => $log_id,
                            'gu_status' => 1
                    );
                    $tablejoin = array('socail_network_group'=>'socail_network_group.sg_id=group_user.gu_idgroups');
                    $dataGroup = $this->Mod_general->join('group_user', $tablejoin, $fields = '*', $wGroupType);
                    $i = 0;
                    foreach ( $dataGroup as $gvalue ) {
                        $i ++;
                        $data .= '<label class="checkbox"><input type="checkbox" class="tgroup" name="itemid[]" value="' . $gvalue->sg_id . '" checked/>' . $i . ' - '  . $gvalue->sg_page_id . ' | ' . $gvalue->{
                            Tbl_social_group::name} . '</label>';
                    }
                    echo $data;
                    break;                    
				
				case 'addgroup' :
					$groups = ! empty ( $_GET ['g'] ) ? $_GET ['g'] : '';
					$pid = ! empty ( $_GET ['pid'] ) ? $_GET ['pid'] : '';
					if (! empty ( $groups )) {
						$groupsArr = explode ( '|', $groups );
						$s_value = explode ( '|', $id );
						$groupCount = array ();
						foreach ( $groupsArr as $group ) {
							$checkExist = $this->mod_general->select ( Tbl_share::TblName, '*', array (
									Tbl_share::group_id => $group,
									Tbl_share::post_id => $pid,
									Tbl_share::social_id => @$s_value [0] 
							) );
							if (empty ( $checkExist ) && ! empty ( $s_value [0] ) && ! empty ( $group )) {
								$dataGoupInstert = array (
										Tbl_share::post_id => $pid,
										Tbl_share::group_id => $group,
										Tbl_share::social_id => @$s_value [0],
										Tbl_share::type => @$s_value [1] 
								);
								$AddToGroup = $this->Mod_general->insert ( Tbl_share::TblName, $dataGoupInstert );
								array_push ( $groupCount, $group );
							}
						}
						echo count ( $groupCount );
					}
					break;
				
				case 'networkblogs' :
					$id = ! empty ( $_GET ['id'] ) ? $_GET ['id'] : '';
					$title = ! empty ( $_GET ['t'] ) ? $_GET ['t'] : '';
					if (! empty ( $id )) {
						$AddToGroup = $this->Mod_general->insert ( Tbl_networkBlog::Tbl, array (
								Tbl_networkBlog::url => $id,
								Tbl_networkBlog::title => @$title,
								Tbl_networkBlog::userID => $log_id 
						) );
						echo json_encode ( array (
								'result' => $AddToGroup 
						) );
					}
					break;
				
				case 'addToPost' :
					$title = $this->input->post ( 't' );
					$link = $this->input->post ( 'l' );
					$image = $this->input->post ( 'i' );
					if (! empty ( $title ) && ! empty ( $link ) && ! empty ( $image )) {
						$data = array (
								'picture' => @$image,
								'name' => trim ( $title ),
								'message' => trim ( $title ),
								'caption' => trim ( $title ),
								'description' => trim ( $title ),
								'link' => $link
						);
						$dataPostID = $this->addToPost ( $title, $data );
						if($dataPostID) {
							echo json_encode ( array (
									'result' => $dataPostID
							) );
						} else {
							echo json_encode ( array (
									'result' => false
							) );
						}
					} else {
						echo json_encode ( array (
								'result' => false
						) );
					}
					break;
                case 'ytid' :
                    $dataTy = array();
                    $id = ! empty ( $_GET ['gid'] ) ? $_GET ['gid'] : '';
                    $max = ! empty ( $_GET ['max'] ) ? $_GET ['max'] : '10';
                    if (! empty ( $id )) {
                        $ytData = $this->youtubeChannel($id,$max);
                        foreach ($ytData as $key => $ytArr) {
                            $dataContent          = new stdClass();
                            $dataContent->title    = $ytArr['snippet']['title'];
                            $dataContent->vid    = $ytArr['id'];
                            $dataContent->description    = $ytArr['snippet']['description'];
                            $dataContent->duration    = $ytArr['contentDetails']['duration'];
                            $dataContent->viewCount    = $this->thousandsCurrencyFormat($ytArr['statistics']['viewCount']);
                            $dataContent->publishedAt    = $this->time_elapsed_string($ytArr['snippet']['publishedAt']);
                            $dataTy[] = $dataContent;
                        }                  
                    }
                    echo json_encode($dataTy);
                 break;
                 case 'autopostblog':
                    //echo '<meta http-equiv="refresh" content="30">';
                     $dataTy = array();
                    $lid = ! empty ( $_GET ['lid'] ) ? $_GET ['lid'] : '';
                    $max = ! empty ( $_GET ['max'] ) ? $_GET ['max'] : '20';
                    $sid = $this->session->userdata ( 'sid' );

                    /*update for bloglink*/
                    if(!empty($lid)) {
                        $blogLinkType = 'blog_linkA';
                        if (!empty($lid)) {
                            $whereLinkA = array(
                                'c_name'      => $blogLinkType,
                                'c_key'     => $log_id,
                            );
                            $queryLinkData = $this->Mod_general->select('au_config', '*', $whereLinkA);
                            /* check before insert */
                            if (!empty($queryLinkData[0])) {
                                $bdata = json_decode($queryLinkData[0]->c_value);
                                $found = false;
                                $jsondata = array();
                                foreach ($bdata as $key => $bvalue) {
                                    $pos = strpos($bvalue->bid, $lid);
                                    if ($pos === false) {
                                        $jsondata[] = array(
                                            'bid' => $bvalue->bid,
                                            'title' => $bvalue->title,
                                            'status' => $bvalue->status,
                                            'date' => @$bvalue->date
                                        );
                                    } else {
                                       $jsondata[] = array(
                                            'bid' => $bvalue->bid,
                                            'title' => $bvalue->title,
                                            'status' => 1,
                                            'date' => date('Y-m-d H:i:s')
                                        ); 
                                    }
                                } 
                                $data_blog = array(
                                    'c_value'      => json_encode($jsondata),
                                );
                                $WhereLinkA = array(
                                    'c_key'     => $log_id,
                                    'c_name'      => $blogLinkType
                                );
                                $lastID = $this->Mod_general->update('au_config', $data_blog,$WhereLinkA);
                            }
                        }
                    }
                    
                    /*check video exist*/
                    $checkYtExist = $this->mod_general->select ( 
                        'youtube', 
                        'yid', 
                        array (
                            'y_fid' => $sid,
                            'y_uid' => $log_id,
                            'y_status' => 0,
                        )
                    );
                    if(!empty($checkYtExist[0]) && count($checkYtExist)> 2) {
                        //$this->postauto();
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?lid='.$lid.'";}, 30 );</script>';
                    } else {
                        if (! empty ( $id )) {
                            $ytID = $id;
                        } else {
                            $where_yt = array(
                                'c_name'      => 'youtubeChannel',
                                'c_key'     => $log_id,
                            );
                            $query_yt = $this->Mod_general->select('au_config', '*', $where_yt);
                            if (!empty($query_yt[0])) {
                                $data = json_decode($query_yt[0]->c_value);
                                $ytid = array();
                                $chID = array();
                                $chStatus = array();
                                $inputYt = array();
                                foreach ($data as $key => $config) {
                                    $inputYt[] = array(
                                        'ytid'=> $config->ytid,
                                        'ytname' => $config->ytname,
                                        'date' => strtotime("now"),
                                        'status' => 0,
                                    );
                                    $chID[] = $config->ytid;
                                    if($config->status == 1) {
                                        $chStatus[] = $config->status;
                                    }
                                    if($config->status == 0) {
                                        $ytid[] = $config->ytid;
                                    }
                                }                                

                                /*check channel update*/
                                if(count($chID) == count($chStatus)) {
                                    $data_yt = array(
                                        'c_value'      => json_encode($inputYt)
                                    );
                                    $whereYT = array(
                                        'c_key'     => $log_id,
                                        'c_name'      => 'youtubeChannel'
                                    );
                                    $this->Mod_general->update('au_config', $data_yt,$whereYT);
                                }
                                if(empty($ytid)) {
                                    //redirect(base_url().'managecampaigns/ajax?gid=&p=autopostblog');
                                    //exit();
                                    echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/ajax?lid='.$lid.'&p=autopostblog";}, 30 );</script>'; 
                                }
                                $brand = mt_rand(0, count($ytid) - 1);
                                $ytRandID = $ytid[$brand];                                
                                /*End check channel update*/
                            }
                            $ytID = $ytRandID;                
                        }
                        $this->getYoutubeVideos($ytID,$max,$lid);
                        //redirect(base_url().'managecampaigns/ajax?gid=&p=autopostblog');
                        //exit();
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/ajax?lid='.$lid.'&p=autopostblog";}, 30 );</script>';
                    }
                    /*End check video exist*/ 
                     break;
                 case 'online':
                    $id = ! empty ( $_GET ['id'] ) ? $_GET ['id'] : '';
                    $url = 'https://whos.amung.us/stats/data/?jtdrz87p&k='.$id.'&list=recents&max=1';
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    $result = curl_exec($ch);
                    curl_close($ch);

                    $obj = json_decode($result);
                    echo $obj->total_count;
                    die;
                    // $description = @$html->find ( 'meta[property=og:description]', 0 )->content;
                     break;
			}
		}
	}

    public function getYoutubeVideos($ytID,$max)
    {
        $getData = false;
        $log_id = $this->session->userdata ( 'user_id' );
        $sid = $this->session->userdata ( 'sid' );
        $ytData = $this->youtubeChannel($ytID,$max);
        if(!empty($ytData)) {
        if(!empty($this->session->userdata('access_token'))) {
                $this->load->library('google_api');
                $client = new Google_Client();                  
                $client->setAccessToken($this->session->userdata('access_token'));
                if($client->isAccessTokenExpired()) {
                     $currentURL = current_url(); //for simple URL
                     $params = $_SERVER['QUERY_STRING']; //for parameters
                     $fullURL = $currentURL . '?' . $params; //full URL with parameter
                    echo $fullURL;
                    $setUrl = base_url() . 'managecampaigns/autopost?glogin='. urlencode($fullURL);
                    redirect($setUrl);
                    exit();
                }
            }
            foreach ($ytData as $key => $ytArr) {
                $dataContent          = new stdClass();
                $dataContent->title    = $ytArr['snippet']['title'];
                $dataContent->vid    = $ytArr['id'];
                $dataContent->description    = $ytArr['snippet']['description'];
                $dataContent->duration    = $ytArr['contentDetails']['duration'];
                //$dataContent->viewCount    = $this->thousandsCurrencyFormat($ytArr['statistics']['viewCount']);
                $dataContent->viewCount    = $ytArr['statistics']['viewCount'];
                //$dataContent->publishedAt    = $this->time_elapsed_string($ytArr['snippet']['publishedAt']);
                $dataContent->publishedAt    = $ytArr['snippet']['publishedAt'];

                $ago = new DateTime($ytArr['snippet']['publishedAt']);
                $twoDaysAgo = new DateTime(date('Y-m-d', strtotime('-1 days')));
                $dateModify = new DateTime(date('Y-m-d', strtotime($ytArr['snippet']['publishedAt'])));
                //echo $ytArr['snippet']['publishedAt'];

                /*if video date is >= before yesterday*/
                if($dateModify >= $twoDaysAgo) { 
                    if($ytArr['snippet']['liveBroadcastContent'] != 'upcoming') {
                        $dataTy[] = $dataContent;
                        /*check data exist*/
                        $checkExist = $this->mod_general->select ( 
                            'youtube', 
                            'yid', 
                            array (
                                'yid' => $dataContent->vid,
                                'y_fid' => $sid,
                                'y_uid' => $log_id,
                            )
                        );
                        /*End check data exist*/
                        if(empty($checkExist[0])) {
                            if(strlen($dataContent->vid) > 10) {
                                $dataYtInstert = array (
                                    'yid' => $dataContent->vid,
                                    'y_date' => $ytArr['snippet']['publishedAt'],
                                    'y_other' => json_encode($dataContent),
                                    'y_status' => 0,
                                    'y_fid' => $sid,
                                    'y_uid' => $log_id,
                                );
                                $ytData = $this->Mod_general->insert ( 'youtube', $dataYtInstert );
                            } else {
                                continue;
                            }
                        }
                    }
                }
            }
            $getData = true;

            /*update youtube channel*/
            $where_yt = array(
                'c_name'      => 'youtubeChannel',
                'c_key'     => $log_id,
            );
            $query_yt = $this->Mod_general->select('au_config', '*', $where_yt);
            if (!empty($query_yt[0])) {
                $found = false;
                $inputYt = array();
                $ytexData = json_decode($query_yt[0]->c_value);
                foreach ($ytexData as $key => $ytex) {                    
                    $pos = strpos($ytex->ytid, $ytID);
                    if ($pos === false) {
                        $inputYt[] = array(
                            'ytid'=> $ytex->ytid,
                            'ytname' => $ytex->ytname,
                            'date' => $ytex->date,
                            'status' => $ytex->status,
                        );
                    } else {
                       $inputYt[] = array(
                            'ytid'=> $ytex->ytid,
                            'ytname' => $ytex->ytname,
                            'date' => strtotime("now"),
                            'status' => 1,
                        );
                    }
                }
                $data_yt = array(
                    'c_value'      => json_encode($inputYt)
                );
                $whereYT = array(
                    'c_key'     => $log_id,
                    'c_name'      => 'youtubeChannel'
                );
                $this->Mod_general->update('au_config', $data_yt,$whereYT);
            }
            /*End update youtube channel*/
        }
        return $getData;
    }

    public function postauto()
    {
        $lid = $this->input->get('lid');
        if(!empty($lid)) {
            $this->session->set_userdata('lid', $lid);
        }
        $log_id = $this->session->userdata ( 'user_id' );
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );
        $this->load->theme ( 'layout' );
        $data ['title'] = 'Admin Area :: Setting';

        /*breadcrumb*/
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add('blog post', base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('Setting', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        /*End breadcrumb*/


        $fbUserId = $this->session->userdata('fb_user_id');
        $sid = $this->session->userdata ( 'sid' );

        $tmp_path = './uploads/'.$log_id.'/'. $fbUserId . '_tmp_action.json';
        $string = file_get_contents($tmp_path);
        $json_a = json_decode($string);
        echo '<meta http-equiv="refresh" content="20"/>';

        /*update main blog link*/
        if(!empty($this->input->get('addbloglink')) && strlen($this->input->get('addbloglink')) > 20) {
            $addbloglink = $this->input->get('addbloglink');
            $pid = $this->input->get('pid');
            $bid = $this->input->get('bid');
            $blog_link_id = $this->input->get('blog_link_id');
            $wPost = array (
                'user_id' => $log_id,
                'p_id' => $pid,
                'p_post_to' => 1,
            );
            $getPost = $this->Mod_general->select ( Tbl_posts::tblName, '*', $wPost );
            if(!empty($getPost[0])) {
                $pConent = json_decode($getPost[0]->p_conent);
                //$pOption = json_decode($getPost[0]->p_schedule);
                $whereUp = array('p_id' => $pid,'user_id' => $log_id);
                $content = array (
                    'name' => $pConent->name,
                    'message' => $pConent->message,
                    'caption' => $pConent->caption,
                    'link' => $pConent->link,
                    'mainLink' => $addbloglink,
                    'picture' => $pConent->picture,                            
                    'vid' => @$pConent->vid,                            
                );
                $dataPostInstert = array (
                    Tbl_posts::conent => json_encode ( $content ),
                    'p_post_to' => 1,
                    'yid' => $pConent->vid,
                );
                $this->Mod_general->update( Tbl_posts::tblName,$dataPostInstert, $whereUp);
            }
            echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$pid.'&bid=' . $bid . '&action=bloglink&autopost=1&blog_link_id='.$blog_link_id.'";}, 30 );</script>';
        } else if($this->input->get('addbloglink') == 'null') {
            echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$pid.'&bid=' . $bid . '&action=bloglink&autopost=1&blog_link_id='.$blog_link_id.'";}, 30 );</script>';

        }
        /*End update main blog link*/

        /*update blog link*/
        if(!empty($this->input->get('linkbloglink')) && strlen($this->input->get('linkbloglink')) > 20) {
            $bloglink = $this->input->get('linkbloglink');
            $pid = $this->input->get('pid');
            $bid = $this->input->get('bid');
            $blog_link_id = $this->input->get('blog_link_id');
            $wPost = array (
                'user_id' => $log_id,
                'p_id' => $pid,
                'p_post_to' => 1,
            );
            $getPost = $this->Mod_general->select ( Tbl_posts::tblName, '*', $wPost );
            if(!empty($getPost[0])) {
                $pConent = json_decode($getPost[0]->p_conent);
                //$pOption = json_decode($getPost[0]->p_schedule);
                $whereUp = array('p_id' => $pid,'user_id' => $log_id);
                $content = array (
                    'name' => $pConent->name,
                    'message' => $pConent->message,
                    'caption' => $pConent->caption,
                    'link' => $bloglink,
                    'mainLink' => $pConent->mainLink,
                    'picture' => $pConent->picture,                            
                    'vid' => @$pConent->vid,                            
                );
                $dataPostInstert = array (
                    Tbl_posts::conent => json_encode ( $content ),
                    'p_post_to' => 0,
                    'yid' => $pConent->vid,
                );
                $blogLinkUpdate = $this->Mod_general->update( Tbl_posts::tblName,$dataPostInstert, $whereUp);
                if($blogLinkUpdate) {
                    $sid = $this->session->userdata ( 'sid' );
                    $whereNext = array (
                        'user_id' => $log_id,
                        'u_id' => $sid,
                        'p_post_to' => 1,
                    );
                    $nextPost = $this->Mod_general->select ( Tbl_posts::tblName, 'p_id', $whereNext );
                    if(!empty($nextPost[0])) {
                        $p_id = $nextPost[0]->p_id;
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$p_id.'&bid=' . $bid . '&action=generate&blink=&autopost=1&blog_link_id='.$blog_link_id.'";}, 30 );</script>';  
                    } else {
                        //http://localhost/autopost/facebook/shareation?post=getpost
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost";}, 30 );</script>'; 
                    }
                }
            }
        } else if($this->input->get('linkbloglink') < 15) {
            //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$pid.'&bid=' . $bid . '&action=generate&blink=&autopost=1&blog_link_id='.$blog_link_id.'";}, 30 );</script>';
        }
        /*End update blog link*/

        if(empty($this->input->get('action'))) {
            $schedule = array (                    
                'start_date' => $json_a->start_date,
                'start_time' => $json_a->start_time,
                'end_date' => $json_a->end_date,
                'end_time' => $json_a->end_time,
                'loop' => $json_a->loop,
                'loop_every' => $json_a->loop_every,
                'loop_on' => $json_a->loop_on,
                'wait_group' => $json_a->wait_group,
                'wait_post' => $json_a->wait_post,
                'randomGroup' => $json_a->randomGroup,
                'prefix_title' => $json_a->prefix_title,
                'suffix_title' => $json_a->suffix_title,
                'short_link' => $json_a->short_link,
                'check_image' => $json_a->check_image,
                'imgcolor' => $json_a->imgcolor,
                'btnplayer' => $json_a->btnplayer,
                'playerstyle' => $json_a->playerstyle,
                'random_link' => $json_a->random_link,
                'share_type' => $json_a->share_type,
                'share_schedule' => $json_a->share_schedule,
                'account_group_type' => $json_a->account_group_type,
                'txtadd' => $json_a->txtadd,
                'blogid' => $json_a->blogid,
                'blogLink' => $json_a->blogLink,
                'userAgent' => $json_a->userAgent,
                'checkImage' => $json_a->checkImage,
                'ptype' => $json_a->ptype,
                'img_rotate' => $json_a->img_rotate,
                'filter_contrast' => $json_a->filter_contrast,
                'filter_brightness' => $json_a->filter_brightness,
                'post_by_manaul' => $json_a->post_by_manaul,
                'foldlink' => $json_a->foldlink,
            );


            /* end data schedule */  

            $checkYtExist = $this->mod_general->select ( 
                'youtube', 
                '*', 
                array (
                    'y_fid' => $sid,
                    'y_uid' => $log_id,
                    'y_status' => 0,
                ),
                0, 
                0, 
                2
            );
           
           /*get group*/
           $wGroupType = array (
                    'gu_grouplist_id' => $json_a->account_group_type,
                    'gu_user_id' => $log_id,
                    'gu_status' => 1
            );
            $tablejoin = array('socail_network_group'=>'socail_network_group.sg_id=group_user.gu_idgroups');
            $itemGroups = $this->Mod_general->join('group_user', $tablejoin, $fields = '*', $wGroupType);
           /*End get group*/
           $dataPost = false;
           $titleExcept = false;
           $autoposts = false;
           $posttype = false;

           /*config autopost*/
            $whereShowAuto = array(
                'c_name'      => 'autopost',
                'c_key'     => $log_id,
            );
            $autoData = $this->Mod_general->select('au_config', '*', $whereShowAuto);
            if(!empty($autoData[0])) {
                $autopost = json_decode($autoData[0]->c_value);
                if(!empty($autopost)) {
                    $titleExcept = $autopost->titleExcept;
                    $autoposts = $autopost->autopost;
                    $posttype = $autopost->posttype;
                }
            } 
           /*end config autopost*/
            if (!empty($checkYtExist)) {
                require_once(APPPATH.'controllers/Splogr.php');
                $aObj = new Splogr();  
                $i = 0;
                $dataPost = true;
                foreach ($checkYtExist as $key => $ytData) {
                    $i++;
                    $contents = $aObj->getpost(1);
                    $txt = preg_replace('/\r\n|\r/', "\n", $contents["content"][0]["content"]);                   
                    $vid = $ytData->yid; 
                    if(strlen($vid) > 10) {
                        $whereNext = array (
                            'user_id' => $log_id,
                            'u_id' => $sid,
                            'yid' => $ytData->yid,
                        );
                        $PostCheck = $this->Mod_general->select ( Tbl_posts::tblName, 'p_id', $whereNext );
                        if(empty($PostCheck[0])) {
                            $y_other = json_decode($ytData->y_other);
                            $title = $y_other->title;

                            if(!empty($titleExcept)) {
                                $arr = explode('|',$titleExcept);
                                $found = false;
                                foreach ($arr as $test) {
                                    if (preg_match('/'.$test.'/', $title)) {
                                        $found = true;
                                    }
                                }
                                if(empty($found)) {
                                    /*update youtube that get posted*/
                                    $sid = $this->session->userdata ( 'sid' );
                                    $this->Mod_general->update('youtube', array('y_status'=>1), array('yid'=>$ytData->yid,'y_fid'=>$sid));
                                    /*End update youtube that get posted*/
                                    $lid = $this->session->userdata ( 'lid' );
                                    echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?lid='.$lid.'";}, 30 );</script>';
                                    continue;
                                }
                            }
                            /*upload image so server*/
                            $picture = 'https://i.ytimg.com/vi/'.$ytData->yid.'/hqdefault.jpg';
                            $imgUrl = $picture;
                                
                                $structure = FCPATH . 'uploads/image/';
                                if (!file_exists($structure)) {
                                    mkdir($structure, 0777, true);
                                }
                                $imgUrl = str_replace('maxresdefault', 'hqdefault', $imgUrl);
                                $file_title = basename($imgUrl);
                                $fileName = FCPATH . 'uploads/image/'.$ytData->yid.$file_title;

                                if (!preg_match('/ytimg.com/', $fileName)) {
                                    $imgUrl = $picture;
                                }    

                                if(!preg_match('/blogspot.com/', $fileName) || !preg_match('/googleusercontent.com/', $fileName)) {
                                    copy($imgUrl, $fileName);      
                                    $param = array(
                                        'btnplayer'=>$json_a->btnplayer,
                                        'playerstyle'=>$json_a->playerstyle,
                                        'imgcolor'=>$json_a->imgcolor,
                                        'txtadd'=>$json_a->txtadd,
                                        'filter_brightness'=>$json_a->filter_brightness,
                                        'filter_contrast'=>$json_a->filter_contrast,
                                        'img_rotate'=>$json_a->img_rotate,
                                    );
                                    $image = $this->mod_general->uploadMedia($fileName,$param);                  
                                } else {
                                    $image = $picture;
                                }
                            /*End upload image so server*/
                            $content = array (
                                'name' => @htmlentities(htmlspecialchars(str_replace(' - YouTube', '', $contents["content"][0]["title"]))),
                                'message' => @htmlentities(htmlspecialchars(addslashes($txt))),
                                'caption' => @$y_other->description,
                                'link' => 'https://www.youtube.com/watch?v='.$ytData->yid,
                                'picture' => $image,                            
                                'vid' => @$ytData->yid,                          
                            );
                            /* end data content */
                            /*check for exist video in old link*/
                            $whExist = array (
                                'user_id' => $log_id,
                                'yid' => $ytData->yid,
                            );
                            $PostExCheck = $this->Mod_general->select ( Tbl_posts::tblName, 'p_id', $whExist );
                            if(!empty($PostExCheck[0])) {
                                $pConent = json_decode($PostExCheck[0]->p_conent);
                                //$pOption = json_decode($PostExCheck[0]->p_schedule);
                                $schedule = array (                    
                                    'start_date' => $json_a->start_date,
                                    'start_time' => $json_a->start_time,
                                    'end_date' => $json_a->end_date,
                                    'end_time' => $json_a->end_time,
                                    'loop' => $json_a->loop,
                                    'loop_every' => $json_a->loop_every,
                                    'loop_on' => $json_a->loop_on,
                                    'wait_group' => $json_a->wait_group,
                                    'wait_post' => $json_a->wait_post,
                                    'randomGroup' => $json_a->randomGroup,
                                    'prefix_title' => $json_a->prefix_title,
                                    'suffix_title' => $json_a->suffix_title,
                                    'short_link' => $json_a->short_link,
                                    'check_image' => $json_a->check_image,
                                    'imgcolor' => $json_a->imgcolor,
                                    'btnplayer' => $json_a->btnplayer,
                                    'playerstyle' => $json_a->playerstyle,
                                    'random_link' => $json_a->random_link,
                                    'share_type' => $json_a->share_type,
                                    'share_schedule' => $json_a->share_schedule,
                                    'account_group_type' => $json_a->account_group_type,
                                    'txtadd' => $json_a->txtadd,
                                    'blogid' => $json_a->blogid,
                                    'blogLink' => $json_a->blogLink,
                                    'userAgent' => $json_a->userAgent,
                                    'checkImage' => $json_a->checkImage,
                                    'ptype' => $json_a->ptype,
                                    'img_rotate' => $json_a->img_rotate,
                                    'filter_contrast' => $json_a->filter_contrast,
                                    'filter_brightness' => $json_a->filter_brightness,
                                    'post_by_manaul' => $json_a->post_by_manaul,
                                    'foldlink' => 1,
                                );
                                $content = array (
                                    'name' => @htmlentities(htmlspecialchars(str_replace(' - YouTube', '', $contents["content"][0]["title"]))),
                                    'message' => @htmlentities(htmlspecialchars(addslashes($txt))),
                                    'caption' => @$y_other->description,
                                    'link' => $pConent->link,
                                    'mainlink' => $pConent->mainlink,
                                    'picture' => $image,                            
                                    'vid' => @$ytData->yid,                          
                                );
                            }
                            /*End check for exist video in old link*/

                            @iconv_set_encoding("internal_encoding", "TIS-620");
                            @iconv_set_encoding("output_encoding", "UTF-8");   
                            @ob_start("ob_iconv_handler");
                            $dataPostInstert = array (
                                    Tbl_posts::name => str_replace(' - YouTube', '', $this->remove_emoji($y_other->title)),
                                    Tbl_posts::conent => json_encode ( $content ),
                                    Tbl_posts::p_date => date('Y-m-d H:i:s'),
                                    Tbl_posts::schedule => json_encode ( $schedule ),
                                    Tbl_posts::user => $sid,
                                    'user_id' => $log_id,
                                    Tbl_posts::post_to => 0,
                                    'p_status' => 1,
                                    'p_post_to' => 1,
                                    'yid' => $ytData->yid,
                                    Tbl_posts::type => 'Facebook' 
                            );
                            @ob_end_flush();
                            $AddToPost = $this->Mod_general->insert ( Tbl_posts::tblName, $dataPostInstert );
                            /* end add data to post */
                            
                            /* add data to group of post */
                            if(!empty($itemGroups)) {
                                if($json_a->share_schedule == 1) {
                                    $date = DateTime::createFromFormat('m-d-Y H:i:s',$startDate . ' ' . $startTime);
                                    $cPost = $date->format('Y-m-d H:i:s');
                                } else {
                                    $cPost = date('Y-m-d H:i:s');
                                }
                                $ShContent = array (
                                    'userAgent' => @$json_a->userAgent,                            
                                );                    
                                foreach($itemGroups as $key => $groups) { 
                                    if(!empty($groups)) {       
                                        $dataGoupInstert = array(
                                            'p_id' => $AddToPost,
                                            'sg_page_id' => $groups->sg_id,
                                            'social_id' => @$sid,
                                            'sh_social_type' => 'Facebook',
                                            'sh_type' => $json_a->ptype,
                                            'c_date' => $cPost,
                                            'uid' => $log_id,                                    
                                            'sh_option' => json_encode($ShContent),                                    
                                        );
                                        $AddToGroup = $this->Mod_general->insert(Tbl_share::TblName, $dataGoupInstert);
                                    }
                                } 
                            }
                            /* end add data to group of post */
                        } else {
                            continue;
                        }
                    }   
                    /*update youtube that get posted*/
                    $sid = $this->session->userdata ( 'sid' );
                    $this->Mod_general->update('youtube', array('y_status'=>1), array('yid'=>$ytData->yid,'y_fid'=>$sid));
                    /*End update youtube that get posted*/
                }
                /*end foreach*/
                $whereNext = array (
                    'user_id' => $log_id,
                    'u_id' => $sid,
                    'p_post_to' => 1,
                );
                $nextPost = $this->Mod_general->select ( Tbl_posts::tblName, 'p_id', $whereNext );
                if(!empty($nextPost[0])) {
                    $p_id = $nextPost[0]->p_id;
                    if(!empty($posttype)) {
                        /*post by Google API*/
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$p_id.'&bid='.$json_a->blogid.'&action=postblog&blink='.$json_a->blogLink.'&autopost=1";}, 30 );</script>';
                    } else {
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$p_id.'&bid=' . $json_a->blogid . '&action=generate&blink='.$json_a->blogLink.'&autopost=1&blog_link_id='.$lid.'";}, 300 );</script>';  
                    }
                }                              
            } else {
                if(empty($dataPost)) {
                    //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/ajax?gid=&p=autopostblog";}, 30 );</script>'; 
                    redirect(base_url().'managecampaigns/ajax?gid=&p=autopostblog');
                    exit();
                }
                redirect(base_url().'managecampaigns/ajax?gid=&p=autopostblog');
                exit();
            }
        } else {
            $sid = $this->session->userdata ( 'sid' );
            if(!empty($this->input->get('pid'))) {
                $whereNext = array (
                    'user_id' => $log_id,
                    'u_id' => $sid,
                    'p_post_to' => 1,
                    'p_id' => $this->input->get('pid'),
                );
                $nextPost = $this->Mod_general->select ( Tbl_posts::tblName, '*', $whereNext );
                if(!empty($nextPost[0])) {
                    $data['datapost'] = $nextPost[0];
                    /*show blog linkA*/
                    $where_link = array(
                        'c_name'      => 'blog_linkA',
                        'c_key'     => $log_id,
                    );
                    $data['bloglinkA'] = false;
                    $query_blog_link = $this->Mod_general->select('au_config', '*', $where_link);
                    if (!empty($query_blog_link[0])) {
                        $data['bloglinkA'] = json_decode($query_blog_link[0]->c_value);
                    }
                    /*End show blog link*/
                    // $p_id = $nextPost[0]->p_id;
                    // $yid = $nextPost[0]->yid;
                    // $p_conent = json_decode($nextPost[0]->p_conent);
                    // $bTitle = $p_conent->name;
                    // $bContent = $p_conent->message;
                    // var_dump($bContent);
                    // die;
                    //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$p_id.'&bid=' . $json_a->blogid . '&action=generate&blink='.$json_a->blogLink.'&autopost=1&blog_link_id='.$lid.'";}, 300 );</script>';  
                    // redirect(base_url().'managecampaigns/postauto?pid='.$p_id.'&bid=' . $json_a->blogid . '&action=generate&blink='.$json_a->blogLink.'&autopost=1&blog_link_id='.$lid);
                    // exit();
                }
            } else {
                echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?start=1";}, 300 );</script>';
            }
        }
        $data['staticdata'] = $json_a;
        $this->load->view ( 'managecampaigns/postauto', $data );
    }

    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'ឆ្នាំ/year',
            'm' => 'ខែ/month',
            'w' => 'សប្ដាហ៍/week',
            'd' => 'ថ្ងៃ/day',
            'h' => 'ម៉ោង/hour',
            'i' => 'នាទី/minute',
            's' => 'វិនាទី/second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' មុន/ago' : 'អម្បាញ់មិញ/just now';
    }

    function thousandsCurrencyFormat($num) {
      if($num>1000) {
            $x = round($num);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $x_display = $x;
            $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $x_display .= $x_parts[$x_count_parts - 1];
            return $x_display;
      }

      return $num;
    }

	public function schedules() {
		$today = strtotime ( "now" );
		ob_start ();
		$getPosts = $this->mod_general->select ( Tbl_posts::tblName, '', array (
				Tbl_posts::status => 1 
		) );
		if (! empty ( $getPosts )) {
			foreach ( $getPosts as $toPost ) {
				$getTimes = json_decode ( $toPost->{Tbl_posts::schedule}, true );
				$postTo = $toPost->{Tbl_posts::post_to};
				$postProgress = $toPost->{Tbl_posts::progress};
				$postTime = $toPost->{Tbl_posts::post_time};
				
				$currentTime = time ();
				$start_date = $getTimes ['start_date'];
				$start_time = $getTimes ['start_time'];
				$loop = $getTimes ['loop'];
				$loopEvery = $getTimes ['loop_every'];
				$loop_on = $getTimes ['loop_on'];
				$time = strtotime ( $start_date . ' ' . $start_time );
				$newformat = date ( 'Y-m-d H:i:s', $time );
				$date = strtotime ( $start_date );
				$newDate = date ( 'Y-m-d', $date );
				$end_date = $getTimes ['end_date'];
				$endDate = strtotime ( $end_date );
				
				/* if post in the first time */
				if ($loop == 1) {
					/* get post if not the first time */
					if (! empty ( $end_date ) && $currentTime > $endDate) {
						$this->checkLoopUpdatePost ( $postTime, $loopEvery, $loop_on, $toPost->{Tbl_posts::id} );
					} else if (empty ( $end_date )) {
						$this->checkLoopUpdatePost ( $postTime, $loopEvery, $loop_on, $toPost->{Tbl_posts::id} );
					}
					/* end get post if not the first time */
				}
			}
		}
		
		/* delete the preveous post */
		$deleteOn = strtotime ( "last month" );
		$getPostsHistory = $this->mod_general->select ( Tbl_share_history::TblName, '', array (
				Tbl_share_history::status => 1 
		) );
		if (! empty ( $getPostsHistory )) {
			foreach ( $getPostsHistory as $history ) {
				$postOn = $history->{Tbl_share_history::timePost};
				if ($deleteOn > $postOn) {
					$this->mod_general->delete ( Tbl_share_history::TblName, array (
							Tbl_share_history::id => $history->{Tbl_share_history::id} 
					) );
				}
			}
		}
		/* end delete the preveous post */
		
		ob_flush ();
	}

    public function autopost()
    {
        $log_id = $this->session->userdata ( 'user_id' );
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );
        $fbUserId = $this->session->userdata ( 'fb_user_id' );
        $this->load->theme ( 'layout' );
        $data ['title'] = 'Autopost :: Admin Area';

        /*breadcrumb*/
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add('blog post', base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('Setting', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        /*End breadcrumb*/
        $data['isAccessTokenExpired'] = true;
        if(!empty($this->session->userdata('access_token'))) {
            $this->load->library('google_api');
            $client = new Google_Client();                  
            $client->setAccessToken($this->session->userdata('access_token'));
            $data['isAccessTokenExpired'] = false;
            if($client->isAccessTokenExpired()) {
                $data['isAccessTokenExpired'] = true;
            }
        }

        /*show blog linkA*/
        $where_link = array(
            'c_name'      => 'blog_linkA',
            'c_key'     => $log_id,
        );
        $data['bloglinkA'] = false;
        $query_blog_link = $this->Mod_general->select('au_config', '*', $where_link);
        if (!empty($query_blog_link[0])) {
            $data['bloglinkA'] = json_decode($query_blog_link[0]->c_value);
        }
        /*End show blog link*/

        /*AutoPost*/
        $whereShowAuto = array(
            'c_name'      => 'autopost',
            'c_key'     => $log_id,
        );
        $autoData = $this->Mod_general->select('au_config', '*', $whereShowAuto);
        if(!empty($autoData[0])) {
            $data['autopost'] = json_decode($autoData[0]->c_value);
        } 
        /*End AutoPost*/
        
        /*show youtube Channel*/
        $whereTYshow = array(
            'c_name'      => 'youtubeChannel',
            'c_key'     => $log_id,
        );
        $ytdata = $this->Mod_general->select('au_config', '*', $whereTYshow);
        if(!empty($ytdata[0])) {
            $data['ytdata'] = json_decode($ytdata[0]->c_value);
        } 
        /*End show youtube Channel*/
        
        /*delete blog data*/
        if(!empty($this->input->get('del'))) {
            $delId = $this->input->get('del');
            $detType = $this->input->get('type');
            switch ($detType) {
                case 'fb':
                    $this->mod_general->delete(
                        'users', 
                        array(
                            'u_id'=>$delId,
                            'user_id'=>$log_id,
                        )
                    );
                    $this->mod_general->delete(
                        'post', 
                        array(
                            'u_id'=>$delId,
                            'user_id'=>$log_id,
                        )
                    );
                    /*clean from post*/
                    /*End clean from post*/
                    redirect(base_url() . 'managecampaigns/setting?m=del_success');
                    break;
                case 'youtubeChannel':
                    $where_del = array(
                        'c_key'     => $log_id,
                        'c_name'      => $detType
                    );
                    $query_yt_del = $this->Mod_general->select('au_config', '*', $where_del);
                    $ytdata = json_decode($query_yt_del[0]->c_value);
                    $inputYt = array();
                    foreach ($ytdata as $key => $ytex) {
                        $pos = strpos($ytex->ytid, $this->input->get('del'));
                        if ($pos === false) {
                            $inputYt[] = array(
                                'ytid'=> $ytex->ytid,
                                'ytname' => $ytex->ytname,
                                'date' => $ytex->date,
                                'status' => $ytex->status,
                            );
                        }
                    }
                    $data_insert = array(
                        'c_value'      => json_encode($inputYt),
                    );
                    $where = array(
                        'c_key'     => $log_id,
                        'c_name'      => $detType
                    );
                    $lastID = $this->Mod_general->update('au_config', $data_insert,$where);
                    redirect(base_url() . 'managecampaigns/setting?m=del_success');
                    break;
                
                default:
                    $where_del = array(
                        'c_key'     => $log_id,
                        'c_name'      => $detType
                    );
                    $query_blog_del = $this->Mod_general->select('au_config', '*', $where_del);
                    $bdata = json_decode($query_blog_del[0]->c_value);
                    $jsondata = array();
                    
                    foreach ($bdata as $key => $bvalue) {
                        $pos = strpos($bvalue->bid, $this->input->get('del'));
                        if ($pos === false) {
                            $jsondata[] = array(
                                'bid' => $bvalue->bid,
                                'title' => $bvalue->title,
                                'status' => $bvalue->status,
                            );
                        }
                    }
                    $data_blog = array(
                        'c_value'      => json_encode($jsondata),
                    );
                    $where = array(
                        'c_key'     => $log_id,
                        'c_name'      => $detType
                    );
                    $lastID = $this->Mod_general->update('au_config', $data_blog,$where);
                    redirect(base_url() . 'managecampaigns/setting?m=del_success');
                    break;
            }
        }
        /*End delete blog data*/
        
        /*save data Autopost*/
        if ($this->input->post('setPostAuto')) {
            $inputAuto = $this->input->post('autopost');
            $titleExcept = $this->input->post('titleExcept');
            $bloggerTemplate = $this->input->post('bloggerTemplate');
            $posttype = $this->input->post('posttype');
            $fbUserId = $this->session->userdata ( 'sid' );
            $autopost = 'autopost';
            $whereAuto = array(
                'c_name'      => $autopost,
                'c_key'     => $log_id,
            );
            $query_ran = $this->Mod_general->select('au_config', '*', $whereAuto);
            $dataAutoInsert = array(
                'autopost' => $inputAuto,
                'templateLink' => $bloggerTemplate,
                'titleExcept' => $titleExcept,
                'posttype' => $posttype,
            );
            /* check before insert */
            if (empty($query_ran)) {
                $dataAuto = array(
                    'c_name'      => $autopost,
                    'c_value'      => json_encode($dataAutoInsert),
                    'c_key'     => $log_id,
                );
                $this->Mod_general->insert('au_config', $dataAuto);
            } else {
                $dataAuto = array(
                    'c_value'      => json_encode($dataAutoInsert)
                );
                $whereAuto = array(
                    'c_key'     => $log_id,
                    'c_name'      => $autopost
                );
                $this->Mod_general->update('au_config', $dataAuto,$whereAuto);
            }
            //redirect(base_url() . 'managecampaigns/setting?m=add_success');
        }
        /*End save data Autopost*/
        
        
        /*youtube channel*/
        if ($this->input->post('ytid')) {
            $ytid = $this->input->post('ytid');
            $ytname = $this->input->post('ytname');
            $ytID = 'youtubeChannel';
            $whereYt = array(
                'c_name'      => $ytID,
                'c_key'     => $log_id,
            );
            $query_yt = $this->Mod_general->select('au_config', '*', $whereYt);

            /* check before insert */
            if (empty($query_yt)) {
                $inputYt[] = array(
                    'ytid'=> $ytid,
                    'ytname' => $ytname,
                    'date' => strtotime("now"),
                    'status' => 0,
                );
                $data_yt = array(
                    'c_name'      => $ytID,
                    'c_value'      => json_encode($inputYt),
                    'c_key'     => $log_id,
                );
                $this->Mod_general->insert('au_config', $data_yt);
            } else {
                $found = false;
                $inputYt = array();
                $ytexData = json_decode($query_yt[0]->c_value);
                foreach ($ytexData as $key => $ytex) {
                    $inputYt[] = array(
                        'ytid'=> $ytex->ytid,
                        'ytname' => $ytex->ytname,
                        'date' => $ytex->date,
                        'status' => $ytex->status,
                    );
                    $pos = strpos($ytex->ytid, $ytid);
                    if ($pos === false) {
                    } else {
                       $found = true; 
                    }
                }

                if(empty($found)) {
                    $ytDataNew[] = array(
                        'ytid'=> $ytid,
                        'ytname' => $ytname,
                        'date' => strtotime("now"),
                        'status' => 0,
                    );

                    $dataYtAdd = array_merge($inputYt, $ytDataNew);
                    $data_yt = array(
                        'c_value'      => json_encode($dataYtAdd)
                    );
                    $whereYT = array(
                        'c_key'     => $log_id,
                        'c_name'      => $ytID
                    );
                    $this->Mod_general->update('au_config', $data_yt,$whereYT);
                }
            }
            redirect(base_url() . 'managecampaigns/setting?m=add_success_yt#YoutubeChannel');
        }
        /*End youtube channel*/
        
        /*add blog link by Imacros*/
        if (!empty($this->input->get('blog_link_a'))) {
            $bLinkTitle = trim($this->input->get('title'));
            $bLinkID    = trim($this->input->get('bid'));
            $blogLinkType = 'blog_linkA';
            if (!empty($bLinkID)) {
                $whereLinkA = array(
                    'c_name'      => $blogLinkType,
                    'c_key'     => $log_id,
                );
                $queryLinkData = $this->Mod_general->select('au_config', '*', $whereLinkA);
                /* check before insert */
                if (empty($queryLinkData[0])) {
                    $jsondata[] = array(
                        'bid' => $bLinkID,
                        'title' => $bLinkTitle,
                        'status' => 1,
                        'date' => date('Y-m-d H:i:s')
                    );
                    $data_blog = array(
                        'c_name'      => $blogLinkType,
                        'c_value'      => json_encode($jsondata),
                        'c_key'     => $log_id,
                    );
                    $lastID = $this->Mod_general->insert('au_config', $data_blog);
                } else {
                    $bdata = json_decode($queryLinkData[0]->c_value);
                    $found = false;
                    $jsondata = array();
                    foreach ($bdata as $key => $bvalue) {
                        $jsondata[] = array(
                            'bid' => $bvalue->bid,
                            'title' => $bvalue->title,
                            'status' => $bvalue->status,
                            'date' => @$bvalue->date
                        );
                        $pos = strpos($bvalue->bid, $bLinkID);
                        if ($pos === false) {
                        } else {
                           $found = true; 
                        }
                    }          
                    if(empty($found)) {
                        $jsondataNew[] = array(
                            'bid' => $bLinkID,
                            'title' => $bLinkTitle,
                            'status' => 1,
                            'date' => date('Y-m-d H:i:s')
                        );
                        $dataAdd = array_merge($jsondata, $jsondataNew);
                        $data_blog = array(
                            'c_value'      => json_encode($dataAdd),
                        );
                        $where = array(
                            'c_key'     => $log_id,
                            'c_name'      => $blogLinkType
                        );
                        $lastID = $this->Mod_general->update('au_config', $data_blog,$where);
                    }
                    //----------


                    // $whereBlink = array(
                    //     'c_key'     => $log_id,
                    //     'c_name'      => $blogLinkType
                    // );
                    // $lastID = $this->Mod_general->update('au_config', $data_blog,$whereBlink);
                }
                if($lastID) {
                    //redirect(base_url().'managecampaigns/ajax?gid=&p=autopostblog');
                    //exit();
                }
            }

        }
        /*End add blog link by Imacros*/
        $tmp_path = './uploads/'.$log_id.'/'. $fbUserId . '_tmp_action.json';
        $string = file_get_contents($tmp_path);
        $data['json_a'] = json_decode($string);

        $this->load->view ( 'managecampaigns/autopost', $data );
    }

    public function waiting()
    {
        $log_id = $this->session->userdata ( 'user_id' );
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );
        $this->load->theme ( 'layout' );
        $data ['title'] = 'Waiting for post :: Admin Area';

        /*breadcrumb*/
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add('blog post', base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('Setting', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        /*End breadcrumb*/
        $whereShowAuto = array(
                'c_name'      => 'autopost',
                'c_key'     => $log_id,
            );
            $autoData = $this->Mod_general->select('au_config', '*', $whereShowAuto);
            if(!empty($autoData[0])) {
                $autopost = json_decode($autoData[0]->c_value);
                if($autopost->autopost == 1) {
                    if (date('H') <= 23 && date('H') > 4 && date('H') !='00') {
                       echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?start=1";}, 30 );</script>';
                    } 
                    //localhost/autopost/managecampaigns/autopost?start=1
                }
            }

        $this->load->view ( 'managecampaigns/waiting', $data );
    }

    public function setting()
    {
        $log_id = $this->session->userdata ( 'user_id' );
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );
        $this->load->theme ( 'layout' );
        $data ['title'] = 'Admin Area :: Setting';

        /*breadcrumb*/
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add('blog post', base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('Setting', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        /*End breadcrumb*/
        $where_blog = array(
            'c_name'      => 'blogger_id',
            'c_key'     => $log_id,
        );
        $data['bloglist'] = false;
        $query_blog_exist = $this->Mod_general->select('au_config', '*', $where_blog);
        if (!empty($query_blog_exist[0])) {
            $data['bloglist'] = json_decode($query_blog_exist[0]->c_value);
        }

        /*show blog link*/
        $where_link = array(
            'c_name'      => 'blog_link',
            'c_key'     => $log_id,
        );
        $data['bloglink'] = array();
        $query_blog_link = $this->Mod_general->select('au_config', '*', $where_link);
        if (!empty($query_blog_link[0])) {
            $data['bloglink'] = json_decode($query_blog_link[0]->c_value);
        }
        /*End show blog link*/

        /*show blog linkA*/
        $where_link = array(
            'c_name'      => 'blog_linkA',
            'c_key'     => $log_id,
        );
        $data['bloglinkA'] = false;
        $query_blog_link = $this->Mod_general->select('au_config', '*', $where_link);
        if (!empty($query_blog_link[0])) {
            $data['bloglinkA'] = json_decode($query_blog_link[0]->c_value);
        }
        /*End show blog link*/

        /*Show data Prefix*/
        $where_pre = array(
            'c_name'      => 'prefix_title',
            'c_key'     => $log_id,
        );
        $prefix_title = $this->Mod_general->select('au_config', '*', $where_pre);
        if(!empty($prefix_title[0])) {
            $data['prefix_title'] = json_decode($prefix_title[0]->c_value);
        }
        /*End Show data Prefix*/
        /*Show data Prefix*/
        $whereSuf = array(
            'c_name'      => 'suffix_title',
            'c_key'     => $log_id,
        );
        $suffix_title = $this->Mod_general->select('au_config', '*', $whereSuf);
        if(!empty($suffix_title[0])) {
            $data['suffix_title'] = json_decode($suffix_title[0]->c_value);
        }        
        /*End Show data Prefix*/

        /*show random Link*/
        $whereRan = array(
            'c_name'      => 'randdom_link',
            'c_key'     => $log_id,
        );
        $randdom_link = $this->Mod_general->select('au_config', '*', $whereRan);
        if(!empty($randdom_link[0])) {
            $data['randdom_link'] = $randdom_link[0]->c_value;
        } 
        /*End show random Link*/

        /*bitly account*/
        $whereBit = array(
            'c_name'      => 'bitlyaccount',
            'c_key'     => $log_id,
        );
        $bitlyAc = $this->Mod_general->select('au_config', '*', $whereBit);
        if(!empty($bitlyAc[0])) {
            $data['bitly'] = json_decode($bitlyAc[0]->c_value);
        } 
        /*End bitly account*/

        /*AutoPost*/
        $whereShowAuto = array(
            'c_name'      => 'autopost',
            'c_key'     => $log_id,
        );
        $autoData = $this->Mod_general->select('au_config', '*', $whereShowAuto);
        if(!empty($autoData[0])) {
            $data['autopost'] = $autoData[0]->c_value;
        } 
        /*End AutoPost*/

        /*show youtube Channel*/
        $whereTYshow = array(
            'c_name'      => 'youtubeChannel',
            'c_key'     => $log_id,
        );
        $ytdata = $this->Mod_general->select('au_config', '*', $whereTYshow);
        if(!empty($ytdata[0])) {
            $data['ytdata'] = json_decode($ytdata[0]->c_value);
        } 
        /*End show youtube Channel*/

        /*delete blog data*/
        if(!empty($this->input->get('del'))) {
            $delId = $this->input->get('del');
            $detType = $this->input->get('type');
            switch ($detType) {
                case 'fb':
                    $this->mod_general->delete(
                        'users', 
                        array(
                            'u_id'=>$delId,
                            'user_id'=>$log_id,
                        )
                    );
                    $this->mod_general->delete(
                        'post', 
                        array(
                            'u_id'=>$delId,
                            'user_id'=>$log_id,
                        )
                    );
                    /*clean from post*/
                    /*End clean from post*/
                    redirect(base_url() . 'managecampaigns/setting?m=del_success');
                    break;
                case 'youtubeChannel':
                    $where_del = array(
                        'c_key'     => $log_id,
                        'c_name'      => $detType
                    );
                    $query_yt_del = $this->Mod_general->select('au_config', '*', $where_del);
                    $ytdata = json_decode($query_yt_del[0]->c_value);
                    $inputYt = array();
                    foreach ($ytdata as $key => $ytex) {
                        $pos = strpos($ytex->ytid, $this->input->get('del'));
                        if ($pos === false) {
                            $inputYt[] = array(
                                'ytid'=> $ytex->ytid,
                                'ytname' => $ytex->ytname,
                                'date' => $ytex->date,
                                'status' => $ytex->status,
                            );
                        }
                    }
                    $data_insert = array(
                        'c_value'      => json_encode($inputYt),
                    );
                    $where = array(
                        'c_key'     => $log_id,
                        'c_name'      => $detType
                    );
                    $lastID = $this->Mod_general->update('au_config', $data_insert,$where);
                    redirect(base_url() . 'managecampaigns/setting?m=del_success');
                    break;
                
                default:
                    $where_del = array(
                        'c_key'     => $log_id,
                        'c_name'      => $detType
                    );
                    $query_blog_del = $this->Mod_general->select('au_config', '*', $where_del);
                    $bdata = json_decode($query_blog_del[0]->c_value);
                    $jsondata = array();
                    
                    foreach ($bdata as $key => $bvalue) {
                        $pos = strpos($bvalue->bid, $this->input->get('del'));
                        if ($pos === false) {
                            $jsondata[] = $bvalue;
                        }
                    }
                    $data_blog = array(
                        'c_value'      => json_encode($jsondata),
                    );
                    $where = array(
                        'c_key'     => $log_id,
                        'c_name'      => $detType
                    );
                    $lastID = $this->Mod_general->update('au_config', $data_blog,$where);
                    redirect(base_url() . 'managecampaigns/setting?m=del_success');
                    break;
            }
        }
        /*End delete blog data*/


        /*add new blog*/
        if ($this->input->post('submit')) {
            $blogTitle = trim($this->input->post('blogTitle'));
            $blogID    = trim($this->input->post('blogID'));
            $blogType    = trim($this->input->post('blogtype'));
            if (!empty($blogID)) {
                $where_blog = array(
                    'c_name'      => $blogType,
                    'c_key'     => $log_id,
                );
                $query_blog_exist = $this->Mod_general->select('au_config', '*', $where_blog);
                /* check before insert */
                if (empty($query_blog_exist)) {
                    $jsondata[] = array(
                        'bid' => $blogID,
                        'title' => $blogTitle,
                        'status' => 1,
                        'date' => date('Y-m-d H:i:s')
                    );
                    $data_blog = array(
                        'c_name'      => $blogType,
                        'c_value'      => json_encode($jsondata),
                        'c_key'     => $log_id,
                    );
                    $lastID = $this->Mod_general->insert('au_config', $data_blog);
                } else { 
                    $bdata = json_decode($query_blog_exist[0]->c_value);
                    $found = false;
                    $jsondata = array();
                    foreach ($bdata as $key => $bvalue) {
                        $jsondata[] = array(
                            'bid' => $bvalue->bid,
                            'title' => $bvalue->title,
                            'status' => $bvalue->status,
                            'date' => $bvalue->date
                        );
                        $pos = strpos($bvalue->bid, $blogID);
                        if ($pos === false) {
                        } else {
                           $found = true; 
                        }
                    }          
                    if(empty($found)) {
                        $jsondataNew[] = array(
                            'bid' => $blogID,
                            'title' => $blogTitle,
                            'status' => 1,
                            'date' => date('Y-m-d H:i:s')
                        );
                        $dataAdd = array_merge($jsondata, $jsondataNew);
                        $data_blog = array(
                            'c_value'      => json_encode($dataAdd),
                        );
                        $where = array(
                            'c_key'     => $log_id,
                            'c_name'      => $blogType
                        );
                        $lastID = $this->Mod_general->update('au_config', $data_blog,$where);
                    }                   
                }
                /* end check before insert */
            }
            redirect(base_url() . 'managecampaigns/setting?m=add_success');
        }
        /*End add new blog*/

        /*add Prefix*/
        if ($this->input->post('Prefix')) {
            $inputPre = $this->input->post('Prefix');
            $preType = 'prefix_title';
            $where_pre = array(
                'c_name'      => $preType,
                'c_key'     => $log_id,
            );
            $query_pre = $this->Mod_general->select('au_config', '*', $where_pre);
            /* check before insert */
            if (empty($query_pre)) {
                $data_pre = array(
                    'c_name'      => $preType,
                    'c_value'      => json_encode($inputPre),
                    'c_key'     => $log_id,
                );
                $lastID = $this->Mod_general->insert('au_config', $data_pre);
            } else {
                $data_blog = array(
                    'c_value'      => json_encode($inputPre)
                );
                $where = array(
                    'c_key'     => $log_id,
                    'c_name'      => $preType,
                );
                $lastID = $this->Mod_general->update('au_config', $data_blog,$where);
            }
            redirect(base_url() . 'managecampaigns/setting?m=add_success');
        }
        /*End add Prefix and Subfix*/

        /*add Suffix*/
        if ($this->input->post('Suffix')) {
            $inputSub = $this->input->post('Suffix');
            $preType = 'suffix_title';
            $where_pre = array(
                'c_name'      => $preType,
                'c_key'     => $log_id,
            );
            $query_pre = $this->Mod_general->select('au_config', '*', $where_pre);
            /* check before insert */
            if (empty($query_pre)) {
                $data_pre = array(
                    'c_name'      => $preType,
                    'c_value'      => json_encode($inputSub),
                    'c_key'     => $log_id,
                );
                $lastID = $this->Mod_general->insert('au_config', $data_pre);
            } else {
                $data_blog = array(
                    'c_value'      => json_encode($inputSub)
                );
                $where = array(
                    'c_key'     => $log_id,
                    'c_name'      => $preType
                );
                $lastID = $this->Mod_general->update('au_config', $data_blog,$where);
            }
            redirect(base_url() . 'managecampaigns/setting?m=add_success');
        }
        /*End add Subfix*/

        /*save data Autopost*/
        if ($this->input->post('setLink')) {
            // $inputAuto = $this->input->post('autopost');
            // $autopost = 'autopost';
            // $whereAuto = array(
            //     'c_name'      => $autopost,
            //     'c_key'     => $log_id,
            // );
            // $query_ran = $this->Mod_general->select('au_config', '*', $whereAuto);
            // /* check before insert */
            // if (empty($query_ran)) {
            //     $dataAuto = array(
            //         'c_name'      => $autopost,
            //         'c_value'      => $inputAuto,
            //         'c_key'     => $log_id,
            //     );
            //     $this->Mod_general->insert('au_config', $dataAuto);
            // } else {
            //     $dataAuto = array(
            //         'c_value'      => $inputAuto
            //     );
            //     $whereAuto = array(
            //         'c_key'     => $log_id,
            //         'c_name'      => $autopost
            //     );
            //     $this->Mod_general->update('au_config', $dataAuto,$whereAuto);
            // }
            //redirect(base_url() . 'managecampaigns/setting?m=add_success');
        }
        /*End save data Autopost*/

        /*save data random*/
        if ($this->input->post('setLink')) {
            $inputRan = $this->input->post('randomLink');
            $randomLink = 'randomLink';
            $whereRan = array(
                'c_name'      => $randomLink,
                'c_key'     => $log_id,
            );
            $query_ran = $this->Mod_general->select('au_config', '*', $whereRan);
            /* check before insert */
            if (empty($query_ran)) {
                $data_ran = array(
                    'c_name'      => $randomLink,
                    'c_value'      => $inputRan,
                    'c_key'     => $log_id,
                );
                $this->Mod_general->insert('au_config', $data_ran);
            } else {
                $data_ran = array(
                    'c_value'      => $inputRan
                );
                $whereRan = array(
                    'c_key'     => $log_id,
                    'c_name'      => $randomLink
                );
                $this->Mod_general->update('au_config', $data_ran,$whereRan);
            }
            //redirect(base_url() . 'managecampaigns/setting?m=add_success');
        }
        /*End save data random*/

        /*bitly account*/
        if ($this->input->post('buserid')) {
            $buserid = $this->input->post('buserid');
            $bapi = $this->input->post('bapi');
            $inputBit = array(
                'username'=> $buserid,
                'api' => $bapi
            );
            $bitly = 'bitlyaccount';
            $whereBit = array(
                'c_name'      => $bitly,
                'c_key'     => $log_id,
            );
            $query_bit = $this->Mod_general->select('au_config', '*', $whereBit);

            /* check before insert */
            if (empty($query_bit)) {
                $data_bit = array(
                    'c_name'      => $bitly,
                    'c_value'      => json_encode($inputBit),
                    'c_key'     => $log_id,
                );
                $this->Mod_general->insert('au_config', $data_bit);
            } else {
                $data_bit = array(
                    'c_value'      => json_encode($inputBit)
                );
                $whereBit = array(
                    'c_key'     => $log_id,
                    'c_name'      => $bitly
                );
                $this->Mod_general->update('au_config', $data_bit,$whereBit);
            }
            redirect(base_url() . 'managecampaigns/setting?m=add_success_bitly');
        }
        /*End bitly account*/

        /*youtube channel*/
        if ($this->input->post('ytid')) {
            $ytid = $this->input->post('ytid');
            $ytname = $this->input->post('ytname');
            $ytID = 'youtubeChannel';
            $whereYt = array(
                'c_name'      => $ytID,
                'c_key'     => $log_id,
            );
            $query_yt = $this->Mod_general->select('au_config', '*', $whereYt);

            /* check before insert */
            if (empty($query_yt)) {
                $inputYt[] = array(
                    'ytid'=> $ytid,
                    'ytname' => $ytname,
                    'date' => strtotime("now"),
                    'status' => 0,
                );
                $data_yt = array(
                    'c_name'      => $ytID,
                    'c_value'      => json_encode($inputYt),
                    'c_key'     => $log_id,
                );
                $this->Mod_general->insert('au_config', $data_yt);
            } else {
                $found = false;
                $inputYt = array();
                $ytexData = json_decode($query_yt[0]->c_value);
                foreach ($ytexData as $key => $ytex) {
                    $inputYt[] = array(
                        'ytid'=> $ytex->ytid,
                        'ytname' => $ytex->ytname,
                        'date' => $ytex->date,
                        'status' => $ytex->status,
                    );
                    $pos = strpos($ytex->ytid, $ytid);
                    if ($pos === false) {
                    } else {
                       $found = true; 
                    }
                }

                if(empty($found)) {
                    $ytDataNew[] = array(
                        'ytid'=> $ytid,
                        'ytname' => $ytname,
                        'date' => strtotime("now"),
                        'status' => 0,
                    );

                    $dataYtAdd = array_merge($inputYt, $ytDataNew);
                    $data_yt = array(
                        'c_value'      => json_encode($dataYtAdd)
                    );
                    $whereYT = array(
                        'c_key'     => $log_id,
                        'c_name'      => $ytID
                    );
                    $this->Mod_general->update('au_config', $data_yt,$whereYT);
                }
            }
            redirect(base_url() . 'managecampaigns/setting?m=add_success_yt#YoutubeChannel');
        }
        /*End youtube channel*/

        /*facebook accounts*/
        $whereFb = array (
                'user_id' => $log_id,
                'u_type' => 'Facebook',
            );
        $data['facebook'] = $this->Mod_general->select('users','*', $whereFb);
        /*End facebook accounts*/

        /*add blog link by Imacros*/
        if (!empty($this->input->get('blog_link_a'))) {
            $bLinkTitle = trim($this->input->get('title'));
            $bLinkID    = trim($this->input->get('bid'));
            $status    = trim($this->input->get('status'));
            $blogLinkType = 'blog_linkA';
            $jsondata = array();
            if (!empty($bLinkID)) {
                $whereLinkA = array(
                    'c_name'      => $blogLinkType,
                    'c_key'     => $log_id,
                );
                $queryLinkData = $this->Mod_general->select('au_config', '*', $whereLinkA);
                /* check before insert */
                if (empty($queryLinkData[0])) {
                    $jsondata[] = array(
                        'bid' => $bLinkID,
                        'title' => $bLinkTitle,
                        'status' => 1,
                        'date' => date('Y-m-d H:i:s')
                    );
                    $data_blog = array(
                        'c_name'      => $blogLinkType,
                        'c_value'      => json_encode($jsondata),
                        'c_key'     => $log_id,
                    );
                    $lastID = $this->Mod_general->insert('au_config', $data_blog);
                } else {
                    $bdata = json_decode($queryLinkData[0]->c_value);
                    $found = false;
                    $jsondata = array();
                    if(!empty($queryLinkData[0])) {
                        foreach ($bdata as $key => $bvalue) {
                            $jsondata[] = $bvalue;
                            $pos = strpos($bvalue->bid, $bLinkID);
                            if ($pos === false) {
                            } else {
                                $found = true; 
                                $bLinkID = !empty($bLinkID) ? $bLinkID : $bvalue->bid;
                                $bLinkTitle = !empty($bLinkTitle) ? $bLinkTitle : $bvalue->title;
                                $status = !empty($status) ? $status : $bvalue->status;
                                $jsondata[] = array(
                                    'bid' => $bLinkID,
                                    'title' => $bLinkTitle,
                                    'status' => $status,
                                    'date' => date('Y-m-d H:i:s')
                                );
                            }
                        }
                    }
                    if (!empty($this->input->get('add'))) {
                        $jsondata[] = array(
                            'bid' => $bLinkID,
                            'title' => $bLinkTitle,
                            'status' => $status,
                            'date' => date('Y-m-d H:i:s')
                        );
                    }
                    $data_blog = array(
                        'c_value'      => json_encode($jsondata),
                    );
                    $where = array(
                        'c_key'     => $log_id,
                        'c_name'      => $blogLinkType
                    );
                    $lastID = $this->Mod_general->update('au_config', $data_blog,$where); 
                    // $whereBlink = array(
                    //     'c_key'     => $log_id,
                    //     'c_name'      => $blogLinkType
                    // );
                    // $lastID = $this->Mod_general->update('au_config', $data_blog,$whereBlink);
                }
                // if($lastID) {
                //     //redirect(base_url().'managecampaigns/ajax?gid=&p=autopostblog');
                //     //exit();
                // }
            }
        }
        /*End add blog link by Imacros*/
        if (!empty($this->input->get('backto'))) {
            redirect($this->input->get('backto'));
        }
        $this->load->view ( 'managecampaigns/setting', $data );
    }
	public function socailpost() {
		$postProgress = $this->mod_general->select ( Tbl_posts::tblName, '', array (
				Tbl_posts::status => 1,
				Tbl_posts::lastPostStatus => 0 
		), null, null, 1 );
		$today = time ();
		if (! empty ( $postProgress [0] )) {
			$getTimes = json_decode ( $postProgress [0]->{Tbl_posts::schedule}, true );
			$loop = $getTimes ['loop'];
			$waiting = $getTimes ['waiting'];
			$randomGroup = $getTimes ['randomGroup'];
			
			$getGroups = $this->mod_general->select ( Tbl_share::TblName, '', array (
					Tbl_share::post_id => $postProgress [0]->{Tbl_posts::id} 
			) );
			$i = 0;
			if (! empty ( $getGroups )) {
				foreach ( $getGroups as $group ) {
					$i ++;
					
					/* set to random group */
					if ($randomGroup) {
						$oderby = Tbl_share::id . ' random';
					} else {
						$oderby = '';
					}
					/* end set to random group */
					
					/* get Access token from socail account */
					$getAccessToken = $this->mod_general->select ( Tbl_social::tblName, '*', array (
							Tbl_social::s_id => $group->{Tbl_share::social_id} 
					), $oderby );
					if (! empty ( $getAccessToken [0] ) && $getAccessToken [0]->{Tbl_social::s_type} == 'Facebook' && $group->{Tbl_share::type} == 'Facebook') {
						
						/* post to facebook */
						$postFB = $this->postToFacebook ( $postProgress, $getAccessToken, $group->{Tbl_share::group_id} );
						if (! empty ( $postFB ['id'] )) {
							$splitId = explode ( "_", $postFB ['id'] );
							if (! empty ( $splitId [1] )) {
								$dataHistory = array (
										Tbl_share_history::timePost => time (),
										Tbl_share_history::status => 1,
										Tbl_share_history::groupID => $splitId [1],
										Tbl_share_history::shareID => $group->{Tbl_share::id},
										Tbl_share_history::type => $group->{Tbl_share::type},
										Tbl_share_history::postId => $postProgress [0]->{Tbl_posts::id} 
								);
								$this->mod_general->insert ( Tbl_share_history::TblName, $dataHistory );
							} elseif (! empty ( $postFB ['error'] )) {
								$dataHistory = array (
										Tbl_share_history::timePost => time (),
										Tbl_share_history::status => 0,
										Tbl_share_history::groupID => $group->{Tbl_share::group_id},
										Tbl_share_history::shareID => $group->{Tbl_share::id},
										Tbl_share_history::type => $group->{Tbl_share::type},
										Tbl_share_history::postId => $postProgress [0]->{Tbl_posts::id} 
								);
								$this->mod_general->insert ( Tbl_share_history::TblName, $dataHistory );
								// error_log(print_r($postFB['error'], true));
							}
							if ($i % 5 == 0) {
								$waiting = $waiting ? $waiting : 10;
								sleep ( $waiting );
							} else {
								if ($i > 10) {
									sleep ( 5 );
								} else {
									sleep ( 2 );
								}
							}
							/* end post to facebook */
						}
					}
				}
			}
			/* set status post */
			if ($loop == 1) {
				$dataSetPost = array (
						Tbl_posts::lastPostStatus => 1 
				);
				$wherePost = array (
						Tbl_posts::id => $postProgress [0]->{Tbl_posts::id} 
				);
				$dataid = $this->mod_general->update ( Tbl_posts::tblName, $dataSetPost, $wherePost );
			} else {
				$dataSetPost = array (
						Tbl_posts::status => 0,
						Tbl_posts::lastPostStatus => 1 
				);
				$wherePost = array (
						Tbl_posts::id => $postProgress [0]->{Tbl_posts::id} 
				);
				$dataid = $this->mod_general->update ( Tbl_posts::tblName, $dataSetPost, $wherePost );
			}
			/* end set status post */
		}
	}
	
	/* post to facebook api */
	public function postToFacebook($getPostData, $getAccessToken, $group) {
		$DataArr = json_decode ( $getPostData [0]->{Tbl_posts::conent}, true );
		$ValueArr = array (
				'access_token' => $getAccessToken [0]->s_access_token 
		);
		$dataArrs = array_merge ( $DataArr, $ValueArr );
		
		$this->load->library ( 'HybridAuthLib' );
		$provider = ($this->uri->segment ( 3 )) ? $this->uri->segment ( 3 ) : $getAccessToken [0]->{
            Tbl_social::s_type};
		try {
			if ($this->hybridauthlib->providerEnabled ( $provider )) {
				$service = $this->hybridauthlib->authenticates ( $provider );
				$facebook = new Facebook ( array (
						'appId' => $service->config ['keys'] ['id'],
						'secret' => $service->config ['keys'] ['secret'],
						'cookie' => true 
				) );
				// $getAccessToken = $this->mod_general->select(Tbl_social::tblName);
				// $access_token = $getAccessToken[1]->s_access_token;
				// $post = array(
				// 'access_token' => $access_token,
				// 'message' => $getPostData[0]->{Tbl_posts::conent},
				// 'name' =>$getPostData[0]->{Tbl_posts::name},
				// 'link' =>$getPostData[0]->{Tbl_posts::modify},
				// 'caption' =>'How to compare car insurance quotes to get the cheapest deal',
				// 'picture' =>'https://lh6.googleusercontent.com/-CmaOJMcoRqs/VSh-LvE70OI/AAAAAAAAKMg/5QI9bRuufpc/w800/_epLGtneZ_1421754324.jpg',
				// );
				
				// and make the request
				$res = $facebook->api ( '/' . $group . '/feed', 'POST', $dataArrs );
				if ($res) {
					return $res;
				}
			}
		} catch ( exception $e ) {
		}
	}
	/* end post to facebook api */
	public function getLoopPost($lastTimePost, $loopEvery, $loop_on) {
		foreach ( $loopEvery as $every => $num ) {
			switch ($every) {
				case 'd' :
					$loopOn = 60 * 60 * 24 * $num;
					break;
				case 'h' :
					$loopOn = 60 * 60 * $num;
					break;
				case 'm' :
					$loopOn = 60 * $num;
					break;
			}
		}
		
		$today = strtotime ( "now" );
		$dates = $lastTimePost;
		$onTime = $dates + $loopOn;
		$loopOnDay = date ( 'D', $today );
		
		/* check loop on day */
		if (in_array ( $loopOnDay, $loop_on )) {
			if ($onTime == $today) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * check loop and update post to 0
	 */
	public function checkLoopUpdatePost($postTime, $loopEvery, $loop_on, $postId) {
		/* loop post */
		$onTime = $this->getLoopPost ( $postTime, $loopEvery, $loop_on );
		if ($onTime) {
			$this->mod_general->update ( Tbl_posts::tblName, array (
					Tbl_posts::lastPostStatus => 0 
			), array (
					Tbl_posts::id => $postId 
			) );
		}
		/* end loop post */
	}
	
	/*
	 * add to post
	 */
	public function addToPost($title, $code) {
		$log_id = $this->session->userdata ( 'user_id' );
		$data_post_id = array (
				Tbl_posts::name => $title,
				Tbl_posts::user => $log_id,
				Tbl_posts::status => 2,
				Tbl_posts::conent => json_encode ( $code ) 
		);
		$dataPostID = $this->Mod_general->insert ( Tbl_posts::tblName, $data_post_id );
		if ($dataPostID) {
			return $dataPostID;
		} else {
			return false;
		}
	}

    public function youtubeChannel($channelId='',$max=10)
    {
        $this->load->library('google_api');
        $client = new Google_Client();
        if ($this->session->userdata('access_token')) {
            $client->setAccessToken($this->session->userdata('access_token'));
        }
        $youtube = new Google_Service_YouTube($client);
        try {

            // Call the search.list method to retrieve results matching the specified
            // query term.
            //$channelsResponse = channelsListById($youtube,'snippet,contentDetails', array('id' => 'UCZOADi6O8-iMe8EYadWwh_w'));
            $channelsResponse = $youtube->channels->listChannels('contentDetails', array(
              'id' => $channelId,
            ));

            $setPost = [];
            foreach ($channelsResponse['items'] as $channel) {
              // Extract the unique playlist ID that identifies the list of videos
              // uploaded to the channel, and then call the playlistItems.list method
              // to retrieve that list.
              $uploadsListId = $channel['contentDetails']['relatedPlaylists']['uploads'];

              $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
                'playlistId' => $uploadsListId,
                'maxResults' => $max,
              ));

              $service = new Google_Service_Blogger($client);
              $posts   = new Google_Service_Blogger_Post();
              foreach ($playlistItemsResponse['items'] as $playlistItem) {
                $videoId = $playlistItem['snippet']['resourceId']['videoId'];
                 $videos = $youtube->videos->listVideos("snippet,contentDetails,statistics", array(
                      'id' => $videoId
                  ));
                 foreach ($videos['items'] as $vItem) {
                    $setPost[] = $vItem;
                 }
              } 
              return $setPost;        
            }
            die();
          } catch (Google_Service_Exception $e) {
            return array('error' => $e->getMessage());
          } catch (Google_Exception $e) {
            return array('error' => 'Authorization Required ' . $e->getMessage());
          }
    }

    public function json($upload_path,$file_name, $list = array(),$do='update')
    {
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0700);
        }
        if (!file_exists($upload_path.$file_name)) {
            $f = fopen($upload_path.$file_name, 'w');
            $fwrite = fwrite($f, json_encode($list));
            fclose($f);
        } else {
            $f = fopen($upload_path.$file_name, 'w');
            $fwrite = fwrite($f, json_encode($list));
            fclose($f);
        }
        if ($do == 'update') {
            $f = fopen($upload_path.$file_name, 'w');
            $fwrite = fwrite($f, json_encode($list));
            fclose($f);
        } else if ($do == 'delete') {
            unlink($upload_path.$file_name);
            $f = fopen($upload_path.$file_name, 'w');
            $fwrite = fwrite($f, json_encode($list));
            fclose($f);
        }
        if ($fwrite === false) {
            return TRUE;
        } else {
            return false;
        }
    }
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
