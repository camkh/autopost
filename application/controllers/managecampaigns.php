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
        if(!$this->input->get('back')) {
            //$this->session->unset_userdata('back');//
        }
        
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
        $client->addScope("https://www.googleapis.com/auth/blogger.readonly");
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

            /*save account data*/
            $fbuids = $this->session->userdata('fb_user_id');
            $target_dir = './uploads/image/';
            $tmp_path = './uploads/'.$log_id.'/';
            $file_tmp_name = $fbuids . '_tmp_gmail_account.json';
            $this->json($tmp_path,$file_tmp_name, $userData);
            /*end save account data*/
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
        $this->session->unset_userdata('back');
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
        if(!empty($this->session->userdata ( 'progress' ))) {
            $data ['title'] = 'Progress post';
        } else {
            $data ['title'] = 'Admin Area :: Post list';
        }
		

		/*breadcrumb*/
		$this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add('Post', base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('list', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        /*End breadcrumb*/

        /*delete spam url*/
        if(!empty($this->input->get('spam_url'))) {
            $fbUserId = $this->session->userdata ( 'sid' );
            $url = $this->input->get('spam_url');
            if(!preg_match('/http:/', $url)) {
                $url = 'http:'.$url;
            }
            /*check spam link*/
            $url = strtok($url, "?");
            $exploded_uri = explode('/', $url);
            $getUrl =  @$exploded_uri[2];
            $whereLinkSpam = array (
                'user_id' => $log_id,
                'u_id' => $fbUserId,
            );
            $spamLink = $this->Mod_general->select ( Tbl_posts::tblName, '*', $whereLinkSpam );
            if(!empty($spamLink[0])) {
                foreach ($spamLink as $key => $sLink) {
                    $content = json_decode($sLink->p_conent);
                    //$getLink =  @parse_url($content->link)["host"];
                    $exploded_ur = explode('/', $content->link);
                    $getLink =  @$exploded_ur[2];
                    if($getLink == $getUrl) {
                        $whereSpam = array (
                            'user_id' => $log_id,
                            'p_id' => $sLink->p_id
                        );
                        @$this->Mod_general->delete('post', $whereSpam);
                    }
                }
            }
            /*End check spam link*/
            $this->load->library ( 'html_dom' );
            $getUrl = strtok($this->input->get('spam_url'), "?");
            $html = @file_get_html($getUrl);
            $title = @$html->find( 'meta[property=og:title]', 0 )->content;
            $backURL = urlencode(base_url().'facebook/shareation?post=getpost');
            $blID = false;
            if(!empty($title)) {
                $bArr = @explode('blid-', $title);
                if(!empty($bArr[1])) {
                    $blID = true;
                    $bids = $bArr[1];
                    $bArrA = @explode('|', $bArr[1]);
                    if(!empty($bArrA[0])) {
                        $bids = $bArrA[0];
                    }
                    //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/setting?blog_link_a=1&bid='.$bids.'&title=&status=2&backto='.$backURL.'";}, 30 );</script>';
                    $where_blog = array(
                        'c_name'      => 'blogger_id',
                        'c_key'     => $log_id,
                    );
                    $query_blog_exist = $this->Mod_general->select('au_config', '*', $where_blog);
                    $blogAd = array();
                    $found = false;
                    if (!empty($query_blog_exist[0])) {
                        $blogAds = json_decode($query_blog_exist[0]->c_value);
                        foreach ($blogAds as $key => $valueb) {
                            if (preg_match('/'.$valueb->bid.'/', $bids)) {
                                $found = true;
                            }
                            $blogAd[] =  $valueb->bid;
                        }
                    }
                    if ($found){
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost";}, 30 );</script>';
                    } else {
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?changeblogurl=1&bid='.trim($bids).'&backto='.$backURL.'";}, 30 );</script>';
                    }
                }
            }
            if(empty($blID)) {
                echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost";}, 30 );</script>';
            }
            /*End get blog id*/
            //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns?m=runout_post";}, 30 );</script>';
        }
        /*End delete spam url*/

        /*Check for multi Share */
        if($this->input->get('m') == 'multishare' || $this->input->get('spam_fb')==2) {
            $whereLinkMulti = array (
                'user_id' => $log_id,
                'u_id' => $fbUserId,
            );
            $MulitLink = $this->Mod_general->select ( Tbl_posts::tblName, '*', $whereLinkMulti );
            if(!empty($MulitLink[0])) {
                $Multurl = $this->input->get('link');
                $Multurl = strtok($Multurl, "?");
                foreach ($MulitLink as $key => $MulLink) {
                $Mcontent = json_decode($MulLink->p_conent);
                    if(trim($Multurl) == trim($Mcontent->link)) {
                        $whereMul = array (
                            'user_id' => $log_id,
                            'p_id' => $MulLink->p_id
                        );
                        @$this->Mod_general->delete('post', $whereMul);
                         echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost";}, 30 );</script>';
                        exit();
                    }
                }
            }
        }
        /*End Check for multi Share */

        /*check auto post*/
        if($this->input->get('m') == 'runout_post') {
            $postAto = $this->Mod_general->getActionPost();
            $arrX = array(5,10,7,6,4,3,8,9);
            $randIndex = array_rand($arrX);
            if(!empty($postAto)) {
                if (date('H') <= 23 && date('H') > 4 && date('H') !='00') {
                   //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?start=1";}, 600000 );</script>';
                    
                     
                    // output the value for the random index
                    $setTime = $arrX[$randIndex] * (1000 * 60);
                    echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postprogress";}, '.$setTime.' );</script>';
                   //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?start=1";}, 600000 );</script>';
                    //autogetpost
                } else {
                    echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/waiting";}, 30 );</script>';
                }
                //localhost/autopost/managecampaigns/autopost?start=1
            } else {
                if ($log_id == 2 || $log_id == 527 || $log_id == 511) {
                    if (date('H') <= 23 && date('H') > 3 && date('H') !='00') {
                        $setTime = $arrX[$randIndex] * (1000 * 60);
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postprogress";}, '.$setTime.' );</script>';
                    } else {
                    echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/waiting";}, 30 );</script>';
                    }
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

            if(!empty($this->input->get('progress') == 1)) {
                $this->session->set_userdata('progress', 1);
            }
            if(!empty($this->input->get('progress') == 'clear')) {
                $this->session->unset_userdata('progress');
                redirect(base_url().'managecampaigns');
            }
            if(!empty($this->session->userdata ( 'progress' ))) {
                $whereFb = array(
                    'meta_name'      => 'post_progress',
                    'meta_key'      => $sid,
                );
                $DataPostProgress = $this->Mod_general->select('meta', '*', $whereFb);
                $pprogress = array();
                if(!empty($DataPostProgress[0])) {
                    foreach ($DataPostProgress as $progress) {
                        $pprogress[] = $progress->object_id;
                    }
                }
                if(!empty($pprogress)) {
                    $where_so['where_in'] = array('user_id' => $log_id,Tbl_posts::id => $pprogress,'p_progress' => 1);
                } else {
                    $where_so = array (
                        'user_id' => $log_id,
                        'u_id' => $fbUserId,
                    );
                }                
            } else {
                $where_so = array (
                    'user_id' => $log_id,
                    'u_id' => $fbUserId,
                );
            }
            
		
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
            //redirect($setUrl);
            //exit();
        }
        if(!empty($this->input->get('renew'))) {
            $currentURL = current_url();
            $setUrl = base_url() . 'managecampaigns/account?renew=1' . '&back='. urlencode($currentURL);
            redirect($setUrl);
            exit();
        }
        if(empty($this->session->userdata ( 'sid' ))) {
            //redirect(base_url() . 'managecampaigns');
            //exit();
        }

        if(!empty($this->session->userdata('access_token'))) {
            $this->load->library('google_api');
            $client = new Google_Client();                  
            $client->setAccessToken($this->session->userdata('access_token'));
            if($client->isAccessTokenExpired()) {
                if(empty($this->input->get('action')) && empty($this->input->get('bid'))) {
                    $setUrl = base_url() . 'managecampaigns/account?renew=1' . '?back='. urlencode(current_url());
                    //redirect($setUrl);
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
            $pprefixChecked = $this->input->post ( 'pprefix' );

            $SuffixTitle = $this->input->post ( 'addtxt' );
            $psuffixChecked = $this->input->post ( 'psuffix' );

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
            $mainPostStyle = $this->input->post ( 'mpoststyle' );
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
            $post_all = @$this->input->post ( 'post_all' );
            $foldlink = @$this->input->post ( 'foldlink' );
            $youtube_link = @$this->input->post ( 'vid' );

            $fromOld_link = @$this->input->post ( 'fromoldlink' );
            $postbloglink = @$this->input->post ( 'setbloglink' );
            $saddtxt = @$this->input->post ( 'saddtxt' );
            $label = @$this->input->post ( 'label' );


            
            // for ($i = 0; $i < count($link); $i++) {
            //     $PostInput = 'smpoststyle'.($i+1);
            //     $smpoststyle = @$this->input->post ( $PostInput );
            //     $main_post_type = !empty($smpoststyle) ? $smpoststyle : $mainPostStyle;
            //     $from_old_link = !empty($fromOld_link[$i]) ? $fromOld_link[$i] : $foldlink;
            //     $post_blog_link = !empty($postbloglink[$i]) ? $postbloglink[$i] : $blogLink;
            //     $AddSuffixTitle = !empty($saddtxt[$i]) ? $saddtxt[$i] : $SuffixTitle;
            //     $labels = !empty($label[$i]) ? $label[$i] : '';
            //     echo 'i: '.$i;
            //     echo ', main blog: '.$from_old_link;
            //     echo ', main blog tyle: '.$main_post_type;
            //     echo ', blink: '.$post_blog_link;
            //     echo ', SuffixTitle: '.$AddSuffixTitle;
            //     echo ', label: '.$labels;
            //     echo '<br/>';
            // }

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
            

            /* end data schedule */  
            $this->load->library('upload');
            $postAto = $this->Mod_general->getActionPost();
            if (!empty($link)) {

                for ($i = 0; $i < count($link); $i++) {

                /*** add data to post ***/
                    $PostInput = 'smpoststyle'.($i+1);
                    $smpoststyle = @$this->input->post ( $PostInput );
                    $main_post_type = !empty($smpoststyle) ? $smpoststyle : $mainPostStyle;
                    $from_old_link = !empty($fromOld_link[$i]) ? $fromOld_link[$i] : $foldlink;
                    $post_blog_link = !empty($postbloglink[$i]) ? $postbloglink[$i] : $blogLink;
                    $AddSuffixTitle = !empty($saddtxt[$i]) ? $saddtxt[$i] : $SuffixTitle;
                    $labels = !empty($label[$i]) ? $label[$i] : '';
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
                        'prefix_checked' => @$pprefixChecked,
                        'suffix_title' => @$AddSuffixTitle,
                        'suffix_checked' => @$psuffixChecked,
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
                        'blogLink' => $post_blog_link,
                        'main_post_style' => @$main_post_type,
                        'userAgent' => $userAgent,
                        'checkImage' => $checkImage,
                        'ptype' => $postType,
                        'img_rotate' => $img_rotate,
                        'filter_contrast' => $filter_contrast,
                        'filter_brightness' => $filter_brightness,
                        'post_by_manaul' => $post_by_manaul,
                        'foldlink' => @$from_old_link,
                        'gemail' => $this->session->userdata ( 'gemail' ),
                        'label' => $labels,
                    );
                    /*save tmp data post*/
                    if($main_post_type!= 'tnews') {
                        $target_dir = './uploads/image/';
                        $tmp_path = './uploads/'.$log_id.'/';
                        $file_tmp_name = $fbuids . '_tmp_action.json';
                        $this->json($tmp_path,$file_tmp_name, $schedule);
                    }
                    /*End save tmp data post*/
                    /*upload image*/
                    //$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);


                    if(!empty($_FILES['upload']['name'])) {
                        $files = $_FILES['upload']['name'][$i];
                        $uploadOk = 1;
                        $setByTime = strtotime("now");
                        $target_file = $target_dir .$setByTime. '_'.basename($files);
                        $imageName = $setByTime. '_'.basename($files);
                        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                        
                        $check = getimagesize($_FILES["upload"]["tmp_name"][$i]);
                        if($check !== false) {
                            $uploadOk = 1;
                        } else {
                            $uploadOk = 0;
                        }
                        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
                            $uploadOk = 0;
                        }
                        if ($uploadOk == 0) {
                        } else {
                            if (move_uploaded_file($_FILES["upload"]["tmp_name"][$i], $target_file)) {
                                $uploaded = 1;
                            } else {
                                $uploaded = 0;
                            }
                        }
                    }  
                    if(!empty($uploaded)) {
                        $image = 'uploads/image/'. $imageName;
                    } else {
                        $image = @$thumb[$i];
                    }
                    /*upload image*/              

                    /* data content */
                    $txt = preg_replace('/\r\n|\r/', "\n", $conents[$i]);
                    if(!empty( $foldlink )) {
                        
                        $vid = $this->Mod_general->get_video_id($youtube_link[$i]);
                        $mainlink = $link[$i];
                    } else {
                        $setNewYtID = !empty($youtube_link[$i]) ? $youtube_link[$i] : $link[$i];
                        $vid = $this->Mod_general->get_video_id($setNewYtID);
                        $mainlink = '';
                    }                    
                    $vid = $vid['vid']; 
                    $content = array (
                            'name' => @htmlentities(htmlspecialchars(str_replace(' - YouTube', '', $title[$i]))),
                            'message' => @htmlentities(htmlspecialchars(addslashes($txt))),
                            'caption' => @$caption[$i],
                            'link' => @$link[$i],
                            'mainlink' => $mainlink,
                            'picture' => @$image,                            
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
                    /*get blog link from database*/
                    $guid = $this->session->userdata ('guid');
                    $blogLinkType = 'blog_linkA';
                    $whereLinkA = array(
                        'meta_key'      => $blogLinkType . '_'. $guid,
                    );
                    $queryLinkData = $this->Mod_general->select('meta', '*', $whereLinkA);
                    if (!empty($queryLinkData[0])) {
                        $big = array();
                        foreach ($queryLinkData as $key => $blog) {
                            if($blog->meta_value ==1) {
                                $big[] = $blog->object_id;
                            }                                
                        }
                        if(empty($big)) {
                            $currentURL = current_url(); //for simple URL
                             $params = $_SERVER['QUERY_STRING']; //for parameters
                             $fullURL = $currentURL . '?' . $params;
                             echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?createblog=1&backto='.urlencode($fullURL).'";}, 3000 );</script>';
                            //$setUrl = base_url() . 'managecampaigns/autopost?createblog=1&backto='. urlencode($fullURL);
                            //redirect($setUrl);
                            exit();
                        }
                        $brand = mt_rand(0, count($big) - 1);
                        $blogRand = $big[$brand];
                    } else {
                        $currentURL = current_url(); //for simple URL
                         $params = $_SERVER['QUERY_STRING']; //for parameters
                         $fullURL = $currentURL . '?' . $params;
                         echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?createblog=1&backto='.urlencode($fullURL).'";}, 3000 );</script>';
                        exit();
                    }
                    /*End get blog link from database*/
                    if(empty($post_by_manaul)) {
                        /*post by Google API*/
                        $setUrl = base_url().'managecampaigns/yturl?pid='.$p_id.'&bid='.$bid.'&action=postblog&blink='.$blogLink.'&autopost='.$postAto;
                        //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$p_id.'&bid='.$bid.'&action=postblog&blink='.$blogLink.'&autopost=1";}, 300 );</script>';
                        redirect($setUrl); 
                    } else {
                        $setUrl = base_url().'managecampaigns/postauto?pid='.$p_id.'&bid=' . $bid . '&action=generate&blink='.$blogLink.'&autopost='.$postAto.'&blog_link_id='.$blogRand;
                        //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$p_id.'&bid=' . $bid . '&action=generate&blink='.$blogLink.'&autopost=1&blog_link_id='.$blogRand.'";}, 300 );</script>'; 
                            redirect($setUrl); 
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
                        $setUrl = base_url() . 'managecampaigns/autopost?glogin='. urlencode($fullURL);
                        redirect($setUrl);
                        exit();
                    }
                } else {
                    $currentURL = current_url(); //for simple URL
                     $params = $_SERVER['QUERY_STRING']; //for parameters
                     $fullURL = $currentURL . '?' . $params; //full URL with parameter
                    $setUrl = base_url() . 'managecampaigns/autopost?glogin='. urlencode($fullURL);
                    redirect($setUrl);
                    exit();
                }
                $bid = $this->input->get('bid');
                $pid = $this->input->get('pid');
                $fbid = $this->session->userdata ( 'sid' );
                $autopost = @$this->input->get('autopost');
                $backto = @$this->input->get('backto');
                $blog_link_id = @$this->input->get('blog_link_id');

                /*check for loop post time*/
                $postsLoop[] = array(
                    'pid'=> $pid, 
                    'uid'=> $log_id,
                );

                $tmp_path = './uploads/'.$log_id.'/';
                if (!file_exists($tmp_path)) {
                    mkdir($tmp_path, 0700, true);
                }
                $tmp_path_sid = './uploads/'.$log_id.'/'.$fbid.'/';
                if (!file_exists($tmp_path_sid)) {
                    mkdir($tmp_path_sid, 0700, true);
                }
                $file_name = $tmp_path_sid . $pid .'-post.json';
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
                );
                $getPost = $this->Mod_general->select ( Tbl_posts::tblName, '*', $wPost );

                if(!empty($getPost[0])) {
                    $pConent = json_decode($getPost[0]->p_conent);
                    $pOption = json_decode($getPost[0]->p_schedule);
                    $main_post_style = @$pOption->main_post_style;
                    if ((!preg_match('/youtu/', @$pConent->link) && @$pOption->foldlink !=1) && empty(@$pConent->vid)) {
                        if($main_post_style != 'tnews') {
                            redirect(base_url().'facebook/shareation?post=getpost');
                        }
                        //http://localhost/autopost/facebook/shareation?post=getpost
                    }
                    $photo = array(
                        'https://lh3.googleusercontent.com/-S2xe5PHDH7S6Zg4KzKyERsg9oEuVwPYOW-gIaof4Xitston7KLtpH9F-JlxHEWhZbudA8bkE6HGWrYFtJ10uZdLDg5jQLcv3nAKK1VlzDXhGwB2YMU6m4NaoCIDV5hIp5MmVCzctSwpP_lg2rhG1XKMBxvD25FjEa4qgzzNfjD_v-xpMUPC0-FD2u9_SwJRwZCukm7cYAjv99eA1PILj2tgOF7CJWRUKp5bgEJcHHHiby9Qac479FMWYbJDPQl0a2tSP26aKZiIilPQOCWFGmBjFU_Je1IjQJrcSdz_a-yFbqRqUjViq1AOgIsv6qhmT5vbSkTIRYccdBqu5-4NlH7JGBzIlAst4tDQk8fjCLcHA0FvumJPoACEL60DzueJIFDUBRL6auJivvic5OfAM8lQRA8ndmiVxvxzPO14PxVI_ShlKu25ELfRejf6Jf0rdwcxxzwFnlW47gJRoKbQnE0sKSFVCuHvUmJ8FRAnThMhWleN-tV7zn7AHGSdffaRCfj-8ui_hNaLwzGf1bejKtAEudToYNLqCRs045lEXqvMPc_7WyAhN3pkgq1R32DJEw9lDYxPpVn6m2Rf9xlKO-_cuMUNvGFUHUveUhL8rfkHFsYRdEd0arAhcnVBpT7TcGzX6qoUaMY1LCJwJv30h3DU4zRxRQz57jStn5WEIdTjQfM2sHhIsmQXWM29jy1yTK3jXE4NJuXWgSh3Zmppy6Q_Ig=w568-h757-no',
                        'https://lh3.googleusercontent.com/2l01VH5XU5Dwc1GF9qMuc7vNHv0ZZ_MZXF5TY-4CgiiJNyZ-EGPvpdeRCGOime4oFCxQzELZ43fz-3SCjjJalIHsG-vf2Fq-JfpdoQRnerO76EU08_tUs942crf96A4L03GDguHtEqDVNugYfjDs96PxAVrhZCTadF8nFVSrnzvn0dNgUL6iAXH3-sOnCufYdgpsw8xDoEqx1tfTNyBcr7ipzqwjW8CkAWMqu3AAogYC_lsGx99kHjLjpPBY9wt-VLplSPy4SOtul7XUF1K7y-643sM0T6quKyVP9kAKJlj8tT8WoZA792k0Mi0Q_mQTnc5ow_Q1_TKhvhs-ApCurUWYoJR-znRbjYnKUwjYhlj6xZnRxOP0OhwKNPg3Gdd5n7SthKLYOco_3s9IjPZSzJYorCgHmGvYN721AxnIcGoDMv9J-tmW-3X1CGzhNkSV4drFggbcy6dp9oRdx0RvUxMxclFNr1l0ZND-yu0d1XaYYYEfwUjCRfYeNbBB4sdZjc9bnLGBRGguJ7yFkbtui2Q_QBHrG8PgGvMwgWPE1MyECJgHJYW2jZzxCfY0RFfwkrnRR1b6kQhXVqtxEhRVhcCyMk0qV0Xl-Uns7m-NW8EA2yEQqOutc1T4rHAq4ITjnF9sHGqLVmbA8tf3Xz8Ui3hWM6_1p30=w1024-h576-no',
                        'https://lh3.googleusercontent.com/O9Zb5ANk53CjsRCCKeruCsDRrlCgQceDg0aRPEQSLWxKIFuD0Vn5Yq5zDu__wWUB7gkCiaabTxuv2Gl_Tv5vTGELVtJYIjL1i4MxrPTZksCWRP4st9xh8mExLkleNhvYx9O4XFKP3LlKEJsP463XW1mCJg4lxUlP9EUQX1ob3VXrSAt_mi55P6Kpv3YIicX3DRPOMI1r-kw-Ymh7sb1SLLz4EElhxAWsH0Z_7U7qu-nGhdHWNkon26k8iO2-tSYXDw9r-uFJ_F1hyqpXp5cvU5ivtCVUPru5pqWsIKFfw4r4mMo6TD2hHudTE99njFu-B06e2P9puSF2wVGSuJoIfUI0eelKs29_kK3F9aFannbLdfWxmY4pImKh9-kW-AOBc-qemGWSSe-aAAyB1g6vnP3xzc1Qj8UubcCFDxX1ior-pCfhT_-DTgiksrqlJmIrc2qY-XLHOEZeiYwMLQ128FjYVBL0mzr0EmUcUEBNDvYrvtJRL_wJ_g61EQQpywGb4s6wQw_V6iJWXi_TNPw4UBMZ0WkVGVAn4gAVMnvtKnrqsdKNbpu4_mUoI4yqutBUc_xTqs7nq8LlkQqoC7symx1qJVtbk9NgP4-WsC2I1qhF4KkDhEgdQgiRNf_u30I4-4eC-OgsXp576TZatPp4ud4lC0rD8Tk=w1024-h576-no',
                        'https://preykabbas.files.wordpress.com/2011/03/sovanaphumi-air-106.jpg',
                        'https://preykabbas.files.wordpress.com/2011/02/e19e80e19eb6e19e9ae19e91e19f85e2808be19e9be19f81e19e84e2808be19e94e19f92e19e9ae19eb6e19e9fe19eb6e19e91e19ea2e19e84e19f92e19e82e19e9a9.jpg'
                    );
                    $brand = mt_rand(0, count($photo) - 1);
                    $imgRand = $photo[$brand];
                /*End get post from post id*/
                    $links = !empty(@$pConent->vid) ? @$pConent->vid : @$pConent->link;
                    $title = nl2br(html_entity_decode(htmlspecialchars_decode(@$pConent->name)));
                    $thai_title = $getPost[0]->p_name;
                    $message = nl2br(html_entity_decode(htmlspecialchars_decode(@$pConent->message)));                   
                    $picture = @$pConent->picture;
                    

                    /*Post to Blogger first*/
                    $vid = $this->Mod_general->get_video_id($links);
                    $vid = $vid['vid'];
                    $blink = $this->input->get('blink');

                    // if false video

                    if($main_post_style != 'tnews') {
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
                                echo '<div style="background-image:url('.$imgRand.');background-repeat: no-repeat;background-attachment: fixed;position:absolute;top:0;bottom:0;left:0;right:0;background-size: cover; background: #000 center center no-repeat; background-size: 100%;"><center>Please wait...</center></div>';
                                echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$p_id.'&bid='.$bid.'&action=postblog&blink='.$blink.'&autopost='.$autopost.'";}, 30 );</script>'; 
                            }
                        }
                    }
                    // if false video


                    /*upload photo first*/
                    $imgur = false;        
                    if(!empty($vid) || $main_post_style == 'tnews') {
                        $imgUrl = $picture;
                        if(empty($imgUrl)) {
                            $imgUrl = 'https://i.ytimg.com/vi/'.$vid.'/hqdefault.jpg';
                        }
                        if (preg_match('/uploads/', $imgUrl)) {
                            $fileName = FCPATH .$picture;
                        } else {
                            $structure = FCPATH . 'uploads/image/';
                            if (!file_exists($structure)) {
                                mkdir($structure, 0777, true);
                            }
                            //$imgUrl = @str_replace('maxresdefault', 'hqdefault', $imgUrl);

                            $file_title = basename($imgUrl);

                            $fileName = FCPATH . 'uploads/image/'.$pid.$file_title;


                            if (!preg_match('/ytimg.com/', $imgUrl)) {
                                $imgUrl = $picture;
                            } 
                        }

                        if (preg_match("/http/", $imgUrl) && preg_match('/ytimg.com/', $imgUrl)) {
                            @copy($imgUrl, $fileName);      
                            $param = array(
                                'btnplayer'=>$pOption->btnplayer,
                                'playerstyle'=>$pOption->playerstyle,
                                'imgcolor'=>$pOption->imgcolor,
                                'txtadd'=>$pOption->txtadd,
                                'filter_brightness'=>$pOption->filter_brightness,
                                'filter_contrast'=>$pOption->filter_contrast,
                                'img_rotate'=>$pOption->img_rotate,
                            );
                            if(!empty($pOption->foldlink) && !empty($pConent->picture)) {
                                $image = $pConent->picture;
                            } else {
                                if ( ! function_exists( 'exif_imagetype' ) ) {
                                    function exif_imagetype ( $filename ) {
                                        if ( ( list($width, $height, $type, $attr) = getimagesize( $filename ) ) !== false ) {
                                            return $type;
                                        }
                                    return false;
                                    }
                                }
                                $checkImage = @exif_imagetype($fileName);
                                if(empty($checkImage)) {
                                    $this->Mod_general->delete('post', array('p_id'=>$getPost[0]->p_id));
                                    echo '<center class="khmer" style="color:red;">No Image គ្មានរូបភាព</center>';
                                    echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost";}, 600 );</script>'; 
                                            exit();
                                }
                                $images = $this->mod_general->uploadMedia($fileName,$param);
                                if(!$images) {
                                    $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                                    $image = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                                } else {
                                    $image = @$images; 
                                }
                            }                          
                        } else {
                            $image = $picture;
                        }

                        if (preg_match("/http/", $image) && preg_match('/ytimg.com/', $image) && !preg_match('/maxresdefault/', $image)) {
                            echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost";}, 600 );</script>'; 
                                            exit();
                        }
                        $post_by_manaul = $pOption->post_by_manaul;
                        if(!empty($image)) {
                            /*update post*/
                            $whereUp = array('p_id' => $pid);
                            $content = array (
                                'name' => $pConent->name,
                                'message' => $pConent->message,
                                'caption' => $pConent->caption,
                                'link' => $pConent->link,
                                'mainlink' => @$pConent->mainlink,
                                'picture' => @$image,                            
                                'vid' => @$pConent->vid,                            
                            );
                            $dataPostInstert = array (
                                Tbl_posts::conent => json_encode ( $content ),
                                'yid' => $vid,
                            );
                            $updatesImg = $this->Mod_general->update( Tbl_posts::tblName,$dataPostInstert, $whereUp);
                            /*End update post*/
                            sleep(3);
                            if($updatesImg) {
                                if(empty($pOption->post_by_manaul)) {
                                    $imgur = true;
                                    /*End upload photo first*/
                                    if(!empty($pOption->foldlink) && !preg_match('/youtu/', $pConent->link) && !empty($pConent->mainlink)) {
                                        $link = @$pConent->mainlink;
                                    } else {
                                        if(empty($pConent->mainlink)) {
                                            $blogData = $this->postToBlogger($bid, $vid, $title,$image,$message,$main_post_style,@$pOption->label);
                                            //$blogData['error'] = true;
                                            if(!empty($blogData['error'])) {
                                                //redirect(base_url() . 'managecampaigns?m=blog_main_error&bid='.$bid);
                                                $p_id = $this->input->get('pid');
                                                if(count($postsLoop)>5) {
                                                   //echo $showHTHM;
                                                    //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$p_id.'&bid=' . $bid . '&action=generate&blink='.$blink.'&autopost='.$autopost.'&blog_link_id='.@$blogRand.'";}, 30 );</script>'; 
                                                    echo '<div style"text-align:center;" class="khmer">សូមមេត្តារង់ចាំ60វិនាទីសិន</div>';
                                                    sleep(60);
                                                    echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost&pid='.$p_id.'";}, 30 );</script>'; 
                                                    //http://localhost/autopost/facebook/shareation?post=getpost&pid=14502
                                                    exit();
                                                } else {
                                                    echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost&pid='.$p_id.'&waits=5";}, 30 );</script>';
                                                    die;
                                                }


                                                // echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$p_id.'&bid=' . $bid . '&action=generate&blink='.$blink.'&autopost='.$autopost.'&blog_link_id=";}, 30 );</script>';
                                                // exit();
                                            }
                                            $link = @$blogData->url;
                                            /*update post*/
                                            if(!empty($link)) {
                                                $updateMainLink = array('p_id' => $pid);
                                                $content = array (
                                                    'name' => $pConent->name,
                                                    'message' => $pConent->message,
                                                    'caption' => $pConent->caption,
                                                    'link' => $pConent->link,
                                                    'mainlink' => $link,
                                                    'picture' => !empty($image) ? $image : $pConent->picture,                            
                                                    'vid' => $pConent->vid,                            
                                                );
                                                $dataPostInstert = array (
                                                    Tbl_posts::conent => json_encode ( $content ),
                                                );
                                                $this->Mod_general->update( Tbl_posts::tblName,$dataPostInstert, $updateMainLink);
                                            }
                                            /*End update post*/ 
                                        } else {
                                            $link = @$pConent->mainlink;
                                        }
                                    }                                
                                    $mainlink = $link; 
                                    /*End Post to Blogger first*/

                                    /*blog link*/

                                    if(!empty($link)) {
                                        if(!empty($blink) && $blink == 1) {

                                            /*check ads from site*/
    //                                         $showAds = '';
    //                                         $where_blog = array(
    //                                             'c_name'      => 'blogger_id',
    //                                             'c_key'     => $log_id,
    //                                         );
    //                                         $query_blog_exist = $this->Mod_general->select('au_config', '*', $where_blog);
    //                                         if(!empty($query_blog_exist[0])) {
    //                                             $bdata = json_decode($query_blog_exist[0]->c_value);
    //                                             foreach ($bdata as $key => $bvalue) {
    //                                                 $pos = strpos($bvalue->bid, $bid);
    //                                                 if ($pos === false) {
    //                                                     $found = false; 
    //                                                 } else {
    //                                                     $bidf = @$bvalue->bid;
    //                                                     $bads = @$bvalue->bads;
    //                                                     $bslot = @$bvalue->bslot;
    //                                                     $burl = @$bvalue->burl;
    //                                                 }
    //                                             }
    // //                                             $showAds = '<center>
    // // <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    // // <ins class="adsbygoogle"
    // //      style="display:inline-block;width:336px;height:280px"
    // //      data-ad-client="ca-pub-'.$bads.'"
    // //      data-page-url="'.$burl.'"
    // //      data-ad-slot="'.$bslot.'"></ins>
    // // <script>
    // // (adsbygoogle = window.adsbygoogle || []).push({});
    // // </script>
    // // </center>';
                                                
    //                                         }

                                            /*End check ads from site*/
                                            //set blog link by ID
                                            if(!empty($blog_link_id)) {
                                                $blogRand = $blog_link_id;
                                            } else {
                                                $bLinkData = $this->getBlogLink();
                                                if(empty($bLinkData)) {
                                                    $currentURL = current_url(); //for simple URL
                                                    $params = $_SERVER['QUERY_STRING']; //for parameters
                                                    $fullURL = $currentURL . '?' . $params;
                                                    echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?createblog=1&backto='.urlencode($fullURL).'";}, 3000 );</script>';
                                                }
                                                $backto = base_url().'managecampaigns/posttotloglink?pid='.$pid.'&bid='.$bLinkData;
                                                /*check blog spam or not*/
                                                echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?checkspamurl=1&bid='.$bLinkData.'&pid='.$pid.'&backto='.urlencode($backto).'";}, 3000 );</script>';
                                                die;
                                                /*End check blog spam or not*/
                                            }

                                            if($main_post_style == 'tnews') {
                                                $message = '';
                                            }
                                            if(!empty($blogRand)) {
                                                $showAds = '<center><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><script>document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});</script></center>'; 
                                                $bodytext = $showAds.'<meta content="'.$image.'" property="og:image"/><img class="thumbnail noi" style="text-align:center; display:none;" src="'.$image.'"/><h2>'.$thai_title.'</h2><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td colspan="3" style="background:#000000;height: 280px;overflow: hidden;background: no-repeat center center;background-size: cover; background: #000 center center no-repeat; background-size: 100%;border: 1px solid #000; background-image:url('.$image.');"><a href="'.$link.'" target="_top" rel="nofollow" style="display:block;height:280px;width:100%; text-align:center; background:url(https://3.bp.blogspot.com/-3ii7X_88VLs/XEs-4wFXMXI/AAAAAAAAiaw/d_ldK-ae830UCGsyOl0oEqqwDQwd_TqEACLcBGAs/s90/youtube-play-button-transparent-png-15.png) no-repeat center center;">&nbsp;</a></td></tr><tr><td style="background:#000 url(https://2.bp.blogspot.com/-Z_lYNnmixpM/XEs6o1hpTUI/AAAAAAAAiak/uPb1Usu-F-YvHx6ivxnqc1uSTIAkLIcxwCLcBGAs/s1600/l.png) no-repeat bottom left; height:39px; width:237px;margin:0;padding:0;"><a href="'.$link.'" target="_top" rel="nofollow" style="display:block;height:39px;width:100%;">&nbsp;</a></td><td style="background:#000 url(https://1.bp.blogspot.com/-9nWJSQ3HKJs/XEs6o7cUv2I/AAAAAAAAiag/sAiHoM-9hKUOezozem6GvxshCyAMp_n_QCLcBGAs/s1600/c.png) repeat-x bottom center; height:39px;margin:0;padding:0;">&nbsp;</td><td style="background:#000 url(https://2.bp.blogspot.com/-RmcnX0Ej1r4/XEs6o-Fjn9I/AAAAAAAAiac/j50SWsyrs8sA5C8AXotVUG7ESm1waKxPACLcBGAs/s1600/r.png) no-repeat bottom right; height:39px; width:151px;margin:0;padding:0;">&nbsp;</td></tr></table>'.$showAds.'<!--more--><a id="myCheck" href="'.$link.'"></a><script>//window.opener=null;window.setTimeout(function(){if(typeof setblog!="undefined"){var link=document.getElementById("myCheck").href;var hostname="https://"+window.location.hostname;links=link.split(".com")[1];link0=link.split(".com")[0]+".com";document.getElementById("myCheck").href=hostname.links;document.getElementById("myCheck").click();};if(typeof setblog=="undefined"){document.getElementById("myCheck").click();}},2000);</script><br/>' . $message;
                                                $title = (string) $title;
                                                $dataMeta = array(
                                                    'title' => $title,
                                                    'image' => $image,
                                                    'link' => $link
                                                );
                                                $customcode = json_encode($dataMeta);
                                                $dataContent          = new stdClass();
                                                $dataContent->setdate = false;        
                                                $dataContent->editpost = false;
                                                $dataContent->pid      = 0;
                                                $dataContent->customcode = json_encode($dataMeta);
                                                $dataContent->bid     = $blogRand;
                                                $dataContent->title    = $title . ' '. $bid . '-blid-'.$blogRand;        
                                                $dataContent->bodytext = $bodytext;
                                                $dataContent->label    = 'blink';
                                                $DataBlogLink = $this->postBlogger($dataContent);
                                                if(!empty($DataBlogLink['error'])) {
                                                    //redirect(base_url() . 'managecampaigns?m=blog_link_error&bid='.$blogRand);
                                                    if(count($postsLoop)>5) {
                                                   //echo $showHTHM;
                                                        //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$p_id.'&bid=' . $blogRand . '&action=generate&blink='.$blink.'&autopost='.$autopost.'&blog_link_id='.$blogRand.'";}, 30 );</script>'; 
                                                        sleep(300);
                                                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost&pid='.$p_id.'";}, 30 );</script>'; 
                                                    //http://localhost/autopost/facebook/shareation?post=getpost&pid=14502
                                                        exit();
                                                    } else {
                                                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost&pid='.$p_id.'&waits=5";}, 30 );</script>';
                                                        die;
                                                    }

                                                    // echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$p_id.'&bid=' . $blogRand . '&action=generate&blink='.$blink.'&autopost='.$autopost.'&blog_link_id='.$blogRand.'";}, 30 );</script>'; 
                                                    // exit();
                                                }
                                                $link = $DataBlogLink->url;
                                            }
                                        }
                                        /*End blog link*/

                                        /*update post*/
                                        if(!empty($link) && !preg_match('/youtu/', $link)) {
                                            $whereUp = array('p_id' => $pid);
                                            $content = array (
                                                'name' => $pConent->name,
                                                'message' => $pConent->message,
                                                'caption' => $pConent->caption,
                                                'link' => $link,
                                                'mainlink' => $mainlink,
                                                'picture' => $pConent->picture,                            
                                                'vid' => $pConent->vid,                            
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
                                        'mainlink' => $mainlink,
                                        'picture' => $pConent->picture,                
                                        'vid' => @$pConent->vid,                
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

                    $showHTHM = '<link href="https://fonts.googleapis.com/css?family=Hanuman" rel="stylesheet"><style>.khmer{font-size:20px;padding:40px;font-family: Hanuman, serif!important;font-size: 30px;color: #fff;text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.5);}</style><div style="background-repeat: no-repeat;background-attachment: fixed;position:absolute;top:0;bottom:0;left:0;right:0;background-size: cover; background:url('.$imgRand.'); center center no-repeat; background-size: 100%;"><div style="background: rgba(255, 255, 255, 0.38);text-align:center;font-size:20px;padding:40px;font-family: Hanuman, serif!important;font-size: 30px;color: #fff;text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.5);">សូមមេត្តារង់ចាំ<br/>Please wait...<br/><table align="center" class="table table-hover table-striped table-bordered table-highlight-head"> <tbody> <tr> <td align="left" valign="middle">Post</td><td align="left" valign="middle">1</td></tr><tr> <td align="left" valign="middle">Post ID: </td><td align="left" valign="middle">'.$getPost[0]->p_id.'</td></tr><tr> <td align="left" valign="middle">ប៉ុស្តិ៍ជាលើកទី: </td><td align="left" valign="middle">0</td></tr><tr> <td align="left" valign="middle">ប្រើអ៊ីម៉ែល: </td><td align="left" valign="middle">'.@$pOption->gemail.'</td></tr><tr> <td align="left" valign="middle">Main Blog ID: </td><td align="left" valign="middle"><a class="K3JSBVB-i-F" target="_blank" href="https://www.blogger.com/blogger.g?blogID='.@$bid.'">'.@$bid.'</a></td></tr><tr> <td align="left" valign="middle">Blog Link ID: </td><td align="left" valign="middle"><a class="K3JSBVB-i-F" target="_blank" href="https://www.blogger.com/blogger.g?blogID='.@$blogRand.'">'.@$blogRand.'</a></td></tr></tbody></table></div></div>';
                            //$showHTHM .= '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost";}, 30 );</script>';
                        if(!empty($backto)) {
                            $showHTHM .= '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.$backto.'";}, 30 );</script>';
                        } else {
                            if($autopost = 1) {
                                //$showHTHM .= '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost&pid='.$getPost[0]->p_id.'&waits=5";}, 30 );</script>';
                                $showHTHM .= '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns";}, 30 );</script>';
                            } else {
                                $showHTHM .= '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns";}, 30 );</script>';
                            }
                        }

                        if(count($postsLoop)>5) {
                            echo '<div style"text-align:center;" class="khmer">សូមមេត្តារង់ចាំ៣០វិនាទីសិន</div>';
                            sleep(30);
                           //echo $showHTHM;
                            //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$getPost[0]->p_id.'&bid=' . $bid . '&action=generate&blink='.$blink.'&autopost='.$autopost.'&blog_link_id='.@$blogRand.'";}, 30 );</script>';
                            echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost&pid='.$getPost[0]->p_id.'&waits=5";}, 30 );</script>';
                            die;
                        } else {
                            echo $showHTHM;
                            die;
                        }


                        //echo $showHTHM;
                        exit();
                        // if(count($postsLoop)>5) {
                        //    //echo $showHTHM;
                        //     echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$getPost[0]->p_id.'&bid=' . $bid . '&action=generate&blink='.$blink.'&autopost='.$autopost.'&blog_link_id='.@$blogRand.'";}, 30 );</script>'; 
                        //     die;
                        // } else {
                        //     echo $showHTHM;
                        //     die;
                        // }

                    /*check next post*/
                    // $whereNext = array (
                    //     'user_id' => $log_id,
                    //     'u_id' => $fbUserId,
                    //     'p_post_to' => 1,
                    // );
                    // $nextPost = $this->Mod_general->select ( Tbl_posts::tblName, 'p_id', $whereNext );
                    // if(!empty($nextPost[0])) {
                    //     $p_id = $nextPost[0]->p_id;

                    //     /*get blog link from database*/
                    //     $guid = $this->session->userdata ('guid');
                    //     $blogLinkType = 'blog_linkA';
                    //     $whereLinkA = array(
                    //         'meta_key'      => $blogLinkType . '_'. $guid,
                    //     );
                    //     $queryLinkData = $this->Mod_general->select('meta', '*', $whereLinkA);
                    //     if (!empty($queryLinkData[0])) {
                    //         $big = array();
                    //         foreach ($queryLinkData as $key => $blog) {
                    //             if($blog->meta_value ==1) {
                    //                 $big[] = $blog->object_id;
                    //             }                                
                    //         }
                    //         if(empty($big)) {
                    //             $currentURL = current_url(); //for simple URL
                    //              $params = $_SERVER['QUERY_STRING']; //for parameters
                    //              $fullURL = $currentURL . '?' . $params;
                    //              echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?createblog=1&backto='.urlencode($fullURL).'";}, 3000 );</script>';
                    //             //$setUrl = base_url() . 'managecampaigns/autopost?createblog=1&backto='. urlencode($fullURL);
                    //             //redirect($setUrl);
                    //             exit();
                    //         }
                    //         $brand = mt_rand(0, count($big) - 1);
                    //         $blogRand = $big[$brand];
                    //     } else {
                    //         $currentURL = current_url(); //for simple URL
                    //          $params = $_SERVER['QUERY_STRING']; //for parameters
                    //          $fullURL = $currentURL . '?' . $params;
                    //          echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?createblog=1&backto='.urlencode($fullURL).'";}, 3000 );</script>';
                    //         exit();
                    //     }
                    //     /*End get blog link from database*/

                    //     $autopost = $this->input->get('autopost');
                    //     $showHTHM = '<link href="https://fonts.googleapis.com/css?family=Hanuman" rel="stylesheet"><style>.khmer{font-size:20px;padding:40px;font-family: Hanuman, serif!important;font-size: 30px;color: #fff;text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.5);}</style><div style="background-repeat: no-repeat;background-attachment: fixed;position:absolute;top:0;bottom:0;left:0;right:0;background-size: cover; background:url('.$imgRand.'); center center no-repeat; background-size: 100%;"><div style="background: rgba(255, 255, 255, 0.38);text-align:center;font-size:20px;padding:40px;font-family: Hanuman, serif!important;font-size: 30px;color: #fff;text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.5);">សូមមេត្តារង់ចាំ<br/>Please wait...<br/><table align="center" class="table table-hover table-striped table-bordered table-highlight-head"> <tbody> <tr> <td align="left" valign="middle">Post</td><td align="left" valign="middle">'.count($nextPost).'</td></tr><tr> <td align="left" valign="middle">Post ID: </td><td align="left" valign="middle">'.$p_id.'</td></tr><tr> <td align="left" valign="middle">ប៉ុស្តិ៍ជាលើកទី: </td><td align="left" valign="middle">'.count($postsLoop).'</td></tr><tr> <td align="left" valign="middle">ប្រើអ៊ីម៉ែល: </td><td align="left" valign="middle">'.@$pOption->gemail.'</td></tr><tr> <td align="left" valign="middle">Main Blog ID: </td><td align="left" valign="middle"><a class="K3JSBVB-i-F" target="_blank" href="https://www.blogger.com/blogger.g?blogID='.@$bid.'">'.@$bid.'</a></td></tr><tr> <td align="left" valign="middle">Blog Link ID: </td><td align="left" valign="middle"><a class="K3JSBVB-i-F" target="_blank" href="https://www.blogger.com/blogger.g?blogID='.@$blogRand.'">'.@$blogRand.'</a></td></tr></tbody></table></div></div>';
                    //         $showHTHM .= '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$p_id.'&bid='.$bid.'&action=postblog&blink='.$blink.'&autopost='.$autopost.'";}, 30 );</script>';
                    //     if(count($postsLoop)>5) {
                    //        //echo $showHTHM;
                    //         echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$p_id.'&bid=' . $bid . '&action=generate&blink='.$blink.'&autopost=1&blog_link_id='.$blogRand.'";}, 30 );</script>'; 
                    //         die;
                    //     } else {
                    //         echo $showHTHM;
                    //         die;
                    //     } 
                    // } else {
                    //     if(!empty($autopost)) {
                    //         redirect(base_url() . 'facebook/shareation?post=getpost');
                    //     } else {
                    //         redirect(base_url() . 'managecampaigns?m=post_success&post_by_manaul=' . @$post_by_manaul);
                    //     }                        
                    // }
                    /*End check next post*/
                }                
            }
        }
        /*End Post to blogger*/
        $this->load->view ( 'managecampaigns/yturl', $data );
    }

    public function getBlogLink()
    {
        /*get blog link from database*/
        $guid = $this->session->userdata ('guid');
        $blogLinkType = 'blog_linkA';
        $whereLinkA = array(
            'meta_key'      => $blogLinkType . '_'. $guid,
        );
        $queryLinkData = $this->Mod_general->select('meta', '*', $whereLinkA);
        if (!empty($queryLinkData[0])) {
            $big = array();
            foreach ($queryLinkData as $key => $blog) {
                if($blog->meta_value ==1) {
                    $big[] = $blog->object_id;
                }                                
            }
            if(empty($big)) {
                $blogRand = false;
            } else {
                $brand = mt_rand(0, count($big) - 1);
                $blogRand = $big[$brand];  
            }
        } else {
            $blogRand = false;
        }
        /*End get blog link from database*/
        return $blogRand;
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
    public function posttotlogLink()
    {
        $log_id = $this->session->userdata ('user_id');
        $this->Mod_general->checkUser ();
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );
        $fbUserId = $this->session->userdata ( 'sid' );

        $pid = $this->input->get('pid');
        $blogRand = $this->input->get('bid');
        $backto = @$this->input->get('backto');
        //$blogRand = $this->getBlogLink();

        $wPost = array (
            'user_id' => $log_id,
            'p_id' => $pid,
        );
        $getPost = $this->Mod_general->select ( Tbl_posts::tblName, '*', $wPost );
        if(!empty($getPost[0])) {
            $pConent = json_decode($getPost[0]->p_conent);
            $pOption = json_decode($getPost[0]->p_schedule);

            $photo = array(
                'https://lh3.googleusercontent.com/-S2xe5PHDH7S6Zg4KzKyERsg9oEuVwPYOW-gIaof4Xitston7KLtpH9F-JlxHEWhZbudA8bkE6HGWrYFtJ10uZdLDg5jQLcv3nAKK1VlzDXhGwB2YMU6m4NaoCIDV5hIp5MmVCzctSwpP_lg2rhG1XKMBxvD25FjEa4qgzzNfjD_v-xpMUPC0-FD2u9_SwJRwZCukm7cYAjv99eA1PILj2tgOF7CJWRUKp5bgEJcHHHiby9Qac479FMWYbJDPQl0a2tSP26aKZiIilPQOCWFGmBjFU_Je1IjQJrcSdz_a-yFbqRqUjViq1AOgIsv6qhmT5vbSkTIRYccdBqu5-4NlH7JGBzIlAst4tDQk8fjCLcHA0FvumJPoACEL60DzueJIFDUBRL6auJivvic5OfAM8lQRA8ndmiVxvxzPO14PxVI_ShlKu25ELfRejf6Jf0rdwcxxzwFnlW47gJRoKbQnE0sKSFVCuHvUmJ8FRAnThMhWleN-tV7zn7AHGSdffaRCfj-8ui_hNaLwzGf1bejKtAEudToYNLqCRs045lEXqvMPc_7WyAhN3pkgq1R32DJEw9lDYxPpVn6m2Rf9xlKO-_cuMUNvGFUHUveUhL8rfkHFsYRdEd0arAhcnVBpT7TcGzX6qoUaMY1LCJwJv30h3DU4zRxRQz57jStn5WEIdTjQfM2sHhIsmQXWM29jy1yTK3jXE4NJuXWgSh3Zmppy6Q_Ig=w568-h757-no',
                'https://lh3.googleusercontent.com/2l01VH5XU5Dwc1GF9qMuc7vNHv0ZZ_MZXF5TY-4CgiiJNyZ-EGPvpdeRCGOime4oFCxQzELZ43fz-3SCjjJalIHsG-vf2Fq-JfpdoQRnerO76EU08_tUs942crf96A4L03GDguHtEqDVNugYfjDs96PxAVrhZCTadF8nFVSrnzvn0dNgUL6iAXH3-sOnCufYdgpsw8xDoEqx1tfTNyBcr7ipzqwjW8CkAWMqu3AAogYC_lsGx99kHjLjpPBY9wt-VLplSPy4SOtul7XUF1K7y-643sM0T6quKyVP9kAKJlj8tT8WoZA792k0Mi0Q_mQTnc5ow_Q1_TKhvhs-ApCurUWYoJR-znRbjYnKUwjYhlj6xZnRxOP0OhwKNPg3Gdd5n7SthKLYOco_3s9IjPZSzJYorCgHmGvYN721AxnIcGoDMv9J-tmW-3X1CGzhNkSV4drFggbcy6dp9oRdx0RvUxMxclFNr1l0ZND-yu0d1XaYYYEfwUjCRfYeNbBB4sdZjc9bnLGBRGguJ7yFkbtui2Q_QBHrG8PgGvMwgWPE1MyECJgHJYW2jZzxCfY0RFfwkrnRR1b6kQhXVqtxEhRVhcCyMk0qV0Xl-Uns7m-NW8EA2yEQqOutc1T4rHAq4ITjnF9sHGqLVmbA8tf3Xz8Ui3hWM6_1p30=w1024-h576-no',
                'https://lh3.googleusercontent.com/O9Zb5ANk53CjsRCCKeruCsDRrlCgQceDg0aRPEQSLWxKIFuD0Vn5Yq5zDu__wWUB7gkCiaabTxuv2Gl_Tv5vTGELVtJYIjL1i4MxrPTZksCWRP4st9xh8mExLkleNhvYx9O4XFKP3LlKEJsP463XW1mCJg4lxUlP9EUQX1ob3VXrSAt_mi55P6Kpv3YIicX3DRPOMI1r-kw-Ymh7sb1SLLz4EElhxAWsH0Z_7U7qu-nGhdHWNkon26k8iO2-tSYXDw9r-uFJ_F1hyqpXp5cvU5ivtCVUPru5pqWsIKFfw4r4mMo6TD2hHudTE99njFu-B06e2P9puSF2wVGSuJoIfUI0eelKs29_kK3F9aFannbLdfWxmY4pImKh9-kW-AOBc-qemGWSSe-aAAyB1g6vnP3xzc1Qj8UubcCFDxX1ior-pCfhT_-DTgiksrqlJmIrc2qY-XLHOEZeiYwMLQ128FjYVBL0mzr0EmUcUEBNDvYrvtJRL_wJ_g61EQQpywGb4s6wQw_V6iJWXi_TNPw4UBMZ0WkVGVAn4gAVMnvtKnrqsdKNbpu4_mUoI4yqutBUc_xTqs7nq8LlkQqoC7symx1qJVtbk9NgP4-WsC2I1qhF4KkDhEgdQgiRNf_u30I4-4eC-OgsXp576TZatPp4ud4lC0rD8Tk=w1024-h576-no',
                'https://preykabbas.files.wordpress.com/2011/03/sovanaphumi-air-106.jpg',
                'https://preykabbas.files.wordpress.com/2011/02/e19e80e19eb6e19e9ae19e91e19f85e2808be19e9be19f81e19e84e2808be19e94e19f92e19e9ae19eb6e19e9fe19eb6e19e91e19ea2e19e84e19f92e19e82e19e9a9.jpg'
            );
            $brand = mt_rand(0, count($photo) - 1);
            $imgRand = $photo[$brand];
            /*End get post from post id*/

            $title = nl2br(html_entity_decode(htmlspecialchars_decode($pConent->name)));
            $thai_title = $getPost[0]->p_name;
            $message = nl2br(html_entity_decode(htmlspecialchars_decode($pConent->message)));                   
            $image = $pConent->picture;

            $links = $pConent->link;
            $vid = $this->Mod_general->get_video_id($links);
            $vid = $vid['vid'];



            $main_post_style = @$pOption->main_post_style;
            $bid = @$pOption->blogid;
            $mainlink = @$pConent->mainlink;

            /*Show to Detail in view*/
            $showHTHM = '<link href="https://fonts.googleapis.com/css?family=Hanuman" rel="stylesheet"><style>.khmer{font-size:20px;padding:40px;font-family: Hanuman, serif!important;font-size: 30px;color: #fff;text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.5);}</style><div style="background-repeat: no-repeat;background-attachment: fixed;position:absolute;top:0;bottom:0;left:0;right:0;background-size: cover; background:url('.$imgRand.'); center center no-repeat; background-size: 100%;"><div style="background: rgba(255, 255, 255, 0.38);text-align:center;font-size:20px;padding:40px;font-family: Hanuman, serif!important;font-size: 30px;color: #fff;text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.5);">សូមមេត្តារង់ចាំ<br/>Please wait...<br/><table align="center" class="table table-hover table-striped table-bordered table-highlight-head"> <tbody> <tr> <td align="left" valign="middle">Post</td><td align="left" valign="middle">1</td></tr><tr> <td align="left" valign="middle">Post ID: </td><td align="left" valign="middle">'.$getPost[0]->p_id.'</td></tr><tr> <td align="left" valign="middle">ប៉ុស្តិ៍ជាលើកទី: </td><td align="left" valign="middle">0</td></tr><tr> <td align="left" valign="middle">ប្រើអ៊ីម៉ែល: </td><td align="left" valign="middle">'.@$pOption->gemail.'</td></tr><tr> <td align="left" valign="middle">Main Blog ID: </td><td align="left" valign="middle"><a class="K3JSBVB-i-F" target="_blank" href="https://www.blogger.com/blogger.g?blogID='.@$bid.'">'.@$bid.'</a></td></tr><tr> <td align="left" valign="middle">Blog Link ID: </td><td align="left" valign="middle"><a class="K3JSBVB-i-F" target="_blank" href="https://www.blogger.com/blogger.g?blogID='.@$blogRand.'">'.@$blogRand.'</a></td></tr></tbody></table></div></div>';
            echo $showHTHM;
            /*End Show to Detail in view*/

            if($main_post_style == 'tnews') {
                $message = '';
            }
            $showAds = '<center><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><script>document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});</script></center>'; 
            $bodytext = $showAds.'<meta content="'.$image.'" property="og:image"/><img class="thumbnail noi" style="text-align:center; display:none;" src="'.$image.'"/><h2>'.$thai_title.'</h2><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td colspan="3" style="background:#000000;height: 280px;overflow: hidden;background: no-repeat center center;background-size: cover; background: #000 center center no-repeat; background-size: 100%;border: 1px solid #000; background-image:url('.$image.');"><a href="'.$mainlink.'" target="_top" rel="nofollow" style="display:block;height:280px;width:100%; text-align:center; background:url(https://3.bp.blogspot.com/-3ii7X_88VLs/XEs-4wFXMXI/AAAAAAAAiaw/d_ldK-ae830UCGsyOl0oEqqwDQwd_TqEACLcBGAs/s90/youtube-play-button-transparent-png-15.png) no-repeat center center;">&nbsp;</a></td></tr><tr><td style="background:#000 url(https://2.bp.blogspot.com/-Z_lYNnmixpM/XEs6o1hpTUI/AAAAAAAAiak/uPb1Usu-F-YvHx6ivxnqc1uSTIAkLIcxwCLcBGAs/s1600/l.png) no-repeat bottom left; height:39px; width:237px;margin:0;padding:0;"><a href="'.$mainlink.'" target="_top" rel="nofollow" style="display:block;height:39px;width:100%;">&nbsp;</a></td><td style="background:#000 url(https://1.bp.blogspot.com/-9nWJSQ3HKJs/XEs6o7cUv2I/AAAAAAAAiag/sAiHoM-9hKUOezozem6GvxshCyAMp_n_QCLcBGAs/s1600/c.png) repeat-x bottom center; height:39px;margin:0;padding:0;">&nbsp;</td><td style="background:#000 url(https://2.bp.blogspot.com/-RmcnX0Ej1r4/XEs6o-Fjn9I/AAAAAAAAiac/j50SWsyrs8sA5C8AXotVUG7ESm1waKxPACLcBGAs/s1600/r.png) no-repeat bottom right; height:39px; width:151px;margin:0;padding:0;">&nbsp;</td></tr></table>'.$showAds.'<!--more--><a id="myCheck" href="'.$mainlink.'"></a><script>//window.opener=null;window.setTimeout(function(){if(typeof setblog!="undefined"){var link=document.getElementById("myCheck").href;var hostname="https://"+window.location.hostname;links=link.split(".com")[1];link0=link.split(".com")[0]+".com";document.getElementById("myCheck").href=hostname.links;document.getElementById("myCheck").click();};if(typeof setblog=="undefined"){document.getElementById("myCheck").click();}},2000);</script><br/>' . $message;
            $title = (string) $title;
            $dataMeta = array(
                'title' => $title,
                'image' => $image,
                'link' => $mainlink
            );
 
            $customcode = json_encode($dataMeta);
            $dataContent          = new stdClass();
            $dataContent->setdate = false;        
            $dataContent->editpost = false;
            $dataContent->pid      = 0;
            $dataContent->customcode = json_encode($dataMeta);
            $dataContent->bid     = $blogRand;
            $dataContent->title    = $title . ' '. $bid . '-blid-'.$blogRand;        
            $dataContent->bodytext = $bodytext;
            $dataContent->label    = 'blink';

            $DataBlogLink = $this->postBlogger($dataContent);

            $postAto = $this->Mod_general->getActionPost();
            if(!empty($DataBlogLink['error'])) {
                echo '<div style="text-align:center;color:red;" class="khmer">ដោយមានបញ្ហា សូមមេត្តារង់ចាំ ៣០ វិនាទីសិន កុំបិទផ្ទាំងនេះ</div>';
                sleep(30);
                echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost&pid='.$pid.'";}, 1 );</script>'; 
                //redirect(base_url() . 'managecampaigns?m=blog_link_error&bid='.$blogRand);
                //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$pid.'&bid=' . $blogRand . '&action=generate&blink='.$blink.'&autopost='.$postAto.'&blog_link_id='.$blogRand.'";}, 30 );</script>'; 
                exit();
            }
            $link = $DataBlogLink->url;

            /*update post*/
            if(!empty($link) && !preg_match('/youtu/', $pConent->mainlink)) {
                $whereUp = array('p_id' => $pid);
                $content = array (
                    'name' => $pConent->name,
                    'message' => $pConent->message,
                    'caption' => $pConent->caption,
                    'link' => $link,
                    'mainlink' => $pConent->mainlink,
                    'picture' => $pConent->picture,                            
                    'vid' => $pConent->vid,                            
                );
                $dataPostInstert = array (
                    Tbl_posts::conent => json_encode ( $content ),
                    'p_post_to' => 0,
                );
                $this->Mod_general->update( Tbl_posts::tblName,$dataPostInstert, $whereUp);
            }
            /*End update post*/
            /*update youtube if autopost*/
            $fbid = $this->session->userdata ( 'sid' ); 
            if(!empty($postAto)) {
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
            if(!empty($backto)) {
                $runpost  = '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.$backto.'";}, 30 );</script>';
                echo $runpost;
                exit();
            } else {
                //$runpost  = '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost&pid='.$getPost[0]->p_id.'";}, 30 );</script>';
                $runpost  = '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/index";}, 30 );</script>';
                echo $runpost;
                exit();
            } 
        }
    }
    public function postBlogger($dataContent)
    {
        /*prepare post*/
        $this->load->library('google_api');
        $client = new Google_Client();
        $client->setAccessToken($this->session->userdata('access_token'));    
        return $this->Mod_general->blogger_post($client,$dataContent);
    }

    public function progresslist()
    {
        $this->Mod_general->checkUser ();
        $log_id = $this->session->userdata ( 'user_id' );
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );
        $sid = $this->session->userdata ( 'sid' );
        $this->load->theme ( 'layout' );
        $data ['title'] = 'Post progress';

        /*breadcrumb*/
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add('Posts', base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('Online', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        /*End breadcrumb*/
        $whereFb = array(
            'meta_name'      => 'post_progress',
            'meta_key'      => $sid,
        );
        $DataPostProgress = $this->Mod_general->select('meta', '*', $whereFb);
        if(!empty($DataPostProgress[0])) {
            print_r($DataPostProgress);
            die;
            foreach ($DataPostProgress as $progress) {
                $wPost = array (
                    'p_id' => $DataPostProgress[0]->object_id,
                    'user_id' => $log_id,
                    'p_progress' => 1,
                );
                $getPost = $this->Mod_general->select ( Tbl_posts::tblName, '*', $wPost );
                if(empty($getPost)) {

                }
            }
        }

        $this->load->view ( 'managecampaigns/progresslist', $data );
    }
    /*@get post that set for all facebook need post*/
    public function postprogress()
    {
        $this->Mod_general->checkUser ();
        $log_id = $this->session->userdata ( 'user_id' );
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );
        $sid = $this->session->userdata ( 'sid' );
        $pid = $this->input->get('pid', TRUE);
        $this->load->theme ( 'layout' );
        $data ['title'] = 'Post progress';

        /*breadcrumb*/
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add('Posts', base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('Online', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        /*End breadcrumb*/

        if(empty($pid)) {
            $whereFb = array(
                'meta_name'      => 'post_progress',
                'meta_key'      => $sid,
            );
            $DataPostProgress = $this->Mod_general->select('meta', '*', $whereFb);
            if(!empty($DataPostProgress[0])) {
                $pid = $DataPostProgress[0]->object_id;
                $meta_id = $DataPostProgress[0]->meta_id;
            }
        } else {
            $whereFb = array(
                'meta_name'      => 'post_progress',
                'meta_key'      => $sid,
                'object_id' => $pid, 
            );
            $DataPostProgress = $this->Mod_general->select('meta', '*', $whereFb);
            if(!empty($DataPostProgress[0])) {
                $pid = $DataPostProgress[0]->object_id;
                $meta_id = $DataPostProgress[0]->meta_id;
            }
        }

        if(!empty($pid)) {
            $wPost = array (
                'p_id' => $pid,
                'user_id' => $log_id,
                'p_progress' => 1,
            );
            $getPost = $this->Mod_general->select ( Tbl_posts::tblName, '*', $wPost );
            if(empty($getPost)) {
                @$this->Mod_general->delete ( 'meta', array (
                        'object_id' => $pid, 
                        'meta_name' => 'post_progress', 
                ) );
                redirect(base_url().'managecampaigns/postprogress');
            }
            if(!empty($getPost[0])) {
                /* data content */
                $dataPostInstert = array (
                        Tbl_posts::name => $getPost[0]->p_name,
                        Tbl_posts::conent => $getPost[0]->p_conent,
                        Tbl_posts::p_date => date('Y-m-d H:i:s'),
                        Tbl_posts::schedule => $getPost[0]->p_schedule,
                        Tbl_posts::user => $sid,
                        'user_id' => $log_id,
                        Tbl_posts::post_to => 0,
                        'p_status' => 1,
                        'p_progress' => 0,
                        Tbl_posts::type => 'Facebook' 
                );
                $AddToPost = $this->Mod_general->insert ( Tbl_posts::tblName, $dataPostInstert );
                if($AddToPost) {
                    /*update meta post*/
                    @$this->Mod_general->delete ( 'meta', array (
                        'meta_id' => $meta_id,
                    ) );
                    /*End update meta post*/
                    /*clean share*/
                    $this->Mod_general->delete ( Tbl_share::TblName, array (
                            'p_id' => $AddToPost,
                            'social_id' => @$sid,
                    ) );
                    /*clean share*/

                    /* add data to group of post */
                    /*if empty groups*/
                    $fbUserId = $this->session->userdata('fb_user_id');
                    $tmp_path = './uploads/'.$log_id.'/'. $fbUserId . '_tmp_action.json';
                    $string = file_get_contents($tmp_path);
                    $json_a = json_decode($string);

                    /*get group*/
                    $wGList = array (
                        'lname' => 'post_progress',
                        'l_user_id' => $log_id,
                        'l_sid' => $sid,
                    );
                    $geGList = $this->Mod_general->select ( 'group_list', '*', $wGList );
                    if(!empty($geGList[0])) {
                        $wGroupType = array (
                            'gu_grouplist_id' => $geGList[0]->l_id,
                            'gu_user_id' => $log_id,
                            'gu_status' => 1
                        );  
                    } else {
                        $wGroupType = array (
                            'gu_grouplist_id' => $json_a->account_group_type,
                            'gu_user_id' => $log_id,
                            'gu_status' => 1
                        );
                    }

                    $tablejoin = array('socail_network_group'=>'socail_network_group.sg_id=group_user.gu_idgroups');
                    $itemGroups = $this->Mod_general->join('group_user', $tablejoin, $fields = '*', $wGroupType);

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
                    /*End if empty groups*/
                    redirect(base_url().'facebook/shareation?post=getpost');
                }
                /* end add data to post */
            }
        } else {
            $postAto = $this->Mod_general->getActionPost();
                if(!empty($postAto)) {
                echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?start=1";}, 0 );</script>';
                exit();
            } else {
                redirect(base_url().'managecampaigns?m=runout_post');
            }
        }
        $this->load->view ( 'managecampaigns/postprogress', $data );
    }

    public function postToBlogger($bid, $vid, $title,$image,$conent='',$blink,$label='')
    {

        /*prepare post*/
        $conent = str_replace('&gt;', '>', $conent);
        $conent = str_replace('&lt;', '<', $conent);
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
                $bodytext = '<link href="'.$image.'" rel="image_src"/><meta content="'.$image.'" property="og:image"/><img class="thumbnail noi" style="text-align:center" src="'.$image.'"/><!--more--><div id="ishow"></div><div><b>'.$title.'</b></div><div class="wrapper"><div class="small"><p>'.$conent.'</p></div> <a class="readmore" href="#">... Click to read more</a></div><div style="text-align: center;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" ></script><script>document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});</script></div><div style="text-align:center"><table width="100%" border="0"><tr><td width="50%" align="right" valign="middle"><div id="setiamgelink"></div></td><td width="50%" align="left" valign="middle"><a href="https://youtu.be/'.$vid.'" target="_blank" class="youtube_link"> https://youtu.be/'.$vid.'</a></td></tr></table></div><div style="text-align: center;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" ></script><script>document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});</script></div>';
                if(!empty($label)) {
                    $label = $label;
                } else {
                    $label = 'Link';
                }
                $customcode = '';
                break;
            case 'tnews':
                //require('Adsense.php');
                //$newcontent = new adinsert($conent);
                //$setConents = $this->ad_between_paragraphs($conent);
                $setConents = $this->insertAd($conent, '<div class="setAdsSection"></div>', $pos = 1);

                if($blink == 'tnews') {
                    $pattern = "|(<div class=\"setAdsSection\">.*?<\/div>)|";
                    $adsense = '<div style="text-align: center;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" ></script><script>document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});</script></div>';
                    preg_match_all($pattern, $setConents, $matches);
                    foreach ($matches[0] as $value) {
                        $txt = str_replace($value, $adsense, $setConents);
                    }
                    // $patternA = "|(<div class=\"setAds\">.*?<\/div>)|";
                    //  preg_match_all($patternA, $txt, $matchesA);
                    //  foreach ($matchesA[0] as $valueA) {
                    //     $txt = str_replace($valueA, $adsense, $txt);
                    // }
                }
                require_once(APPPATH.'controllers/Splogr.php');
                $aObj = new Splogr(); 
                $enContent = $aObj->getpost(1);
                $enTxt = preg_replace('/\r\n|\r/', "\n", $enContent["content"][0]["content"]);
                $enTitle = $enContent["content"][0]["title"];
                $bodytext = '<link href="'.$image.'" rel="image_src"/><meta content="'.$image.'" property="og:image"/><img class="thumbnail news" style="text-align:center" src="'.$image.'"/><!--more--><div style="text-align: center;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" ></script><script>document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});</script></div>'.$txt.'<br/><br/><b>Another News:</b><br/><h2>'.$enTitle.'</h2><div class="wrapper"><div class="small"><p>'.$enTxt.'</p></div> <a class="readmore" href="#">... Click to read more</a></div><div style="text-align: center;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" ></script><script>document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});</script></div>';
                $customcode = '';
                
                if(!empty($label)) {
                    $label = $label;
                } else {
                    $label = 'News';
                }
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
        if(!empty($this->input->get('bitly')) && !empty($id)) {
            $pConent = json_decode($dataPost[0]->p_conent);
            $updateLink = array('p_id' => $this->input->get('id'));
            $content = array (
                'name' => $pConent->name,
                'message' => $pConent->message,
                'caption' => $pConent->caption,
                'link' => $this->input->get('bitly'),
                'mainlink' => @$pConent->mainlink,
                'picture' => $pConent->picture,                            
                'vid' => $pConent->vid,                            
            );
            $dataPostInstert = array (
                Tbl_posts::conent => json_encode ( $content ),
            );
            $upLink = $this->Mod_general->update( Tbl_posts::tblName,$dataPostInstert, $updateLink);
            if($upLink) {
                //http://localhost/autopost/facebook/shareation?post=getpost&pid=2491
                redirect(base_url() . 'facebook/shareation?post=getpost&pid='.$pid);
            }
        }
		
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
            $pprefixChecked = $this->input->post ( 'pprefix' );

            $SuffixTitle = $this->input->post ( 'addtxt' );
            $psuffixChecked = $this->input->post ( 'psuffix' );


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
            $pprogress = @$this->input->post ( 'pprogress' );
			
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
                    'prefix_checked' => @$pprefixChecked,
                    'suffix_title' => @$AddSuffixTitle,
                    'suffix_checked' => @$psuffixChecked,
                    'short_link' => @$short_link,
                    'check_image' => @$checkImage,
                    'random_link' => @$random_link,
                    'share_type' => @$share_type,
                    'share_schedule' => @$post_action,
                    'account_group_type' => @$account_gtype,
			);
			/* end data schedule */  
			if (!empty($link)) {
                /*facebook accounts*/
                if(!empty($pprogress)) {
                    $whereFb = array (
                            'user_id' => $log_id,
                            'u_type' => 'Facebook',
                        );
                    $dataFacebook = $this->Mod_general->select('users','*', $whereFb);
                }
                /*End facebook accounts*/
                for ($i = 0; $i < count($link); $i++) {
				/*** add data to post ***/
                    $dataPostS = $this->Mod_general->select ( Tbl_posts::tblName, '*', array(Tbl_posts::id => $postId[$i]) );
                    $pConent = json_decode($dataPostS[0]->p_conent);                
                    $pSchedule = json_decode($dataPostS[0]->p_schedule);
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
                        'prefix_checked' => @$pprefixChecked,
                        'suffix_title' => @$AddSuffixTitle,
                        'suffix_checked' => @$psuffixChecked,
                        'short_link' => @$short_link,
                        'check_image' => @$checkImage,
                        'random_link' => @$random_link,
                        'share_type' => @$share_type,
                        'share_schedule' => @$post_action,
                        'account_group_type' => @$account_gtype,
                        'imgcolor' => @$pSchedule->imgcolor,
                        'btnplayer' => @$pSchedule->btnplayer,
                        'playerstyle' => @$pSchedule->playerstyle,
                        'txtadd' => @$pSchedule->txtadd,
                        'blogid' => @$pSchedule->blogid,
                        'blogLink' => @$pSchedule->blogLink,
                        'main_post_style' => @$pSchedule->main_post_style,
                        'userAgent' => @$pSchedule->userAgent,
                        'checkImage' => @$pSchedule->checkImage,
                        'ptype' => @$pSchedule->ptype,
                        'img_rotate' => @$pSchedule->img_rotate,
                        'filter_contrast' => @$pSchedule->filter_contrast,
                        'filter_brightness' => @$pSchedule->filter_brightness,
                        'post_by_manaul' => @$pSchedule->post_by_manaul,
                        'foldlink' => @$pSchedule->foldlink,
                        'gemail' => $this->session->userdata ( 'gemail' ),
                        'label' => @$pSchedule->label,
                    );


                    /* data content */
                    $content = array (
                            'name' => @$title[$i],
                            'message' => @$message[$i],
                            'caption' => @$caption[$i],
                            'link' => @$link[$i],
                            'picture' => @$thumb[$i],                            
                            'vid' => '',                            
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
                            'p_progress' => @$pprogress,
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

                    /*data post progress*/
                    if(!empty($pprogress)) {
                        if(!empty($dataFacebook)) {
                            foreach ($dataFacebook as $key => $fb) {
                                $data_blog = array(
                                    'meta_key'      => $fb->u_id,
                                    'object_id'      => $AddToPost,
                                    'meta_value'     => 1,
                                    'meta_name'     => 'post_progress',
                                );
                                $lastID = $this->Mod_general->insert('meta', $data_blog);
                            }
                        }
                    }
                    /*data post progress*/
    				
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
                @$this->Mod_general->delete ( 'meta', array (
                        'object_id' => $id, 
                        'meta_name' => 'post_progress', 
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
        $oldurl = $this->input->get('old');
        $this->Mod_general->checkUser ();
        $log_id = @$this->session->userdata ( 'user_id' );
        /* Sidebar */
        if (! empty ( $url )) {
            preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
            if (!empty($matches[1])) {
                $content = $this->getContentfromYoutube($url);
                $conent = $content->conent;
                $vid = (!empty($matches[1]) ? $matches[1] : '');
                $from = 'yt';
            } else {
                    require_once(APPPATH.'controllers/Getcontent.php');
                    $aObj = new Getcontent(); 
                    $content = $aObj->getConentFromSite($url,$oldurl);
                if(!empty($content->fromsite)) {
                    $conTenSite = '<br/><div class="meta-from"> ดูข่าวต้นฉบับ: '.'<a href="'.$url.'" target="_blank">'.$content->fromsite.'</a></div>';
                } else {
                    $conTenSite = '';
                }
                $setConents = $content->conent.$conTenSite;
                $adsense = '<h1>test</h1><div style="text-align: center;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" ></script><script>document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});</script></div>';
                //$setConents = str_replace('<!--adsense-->', $adsense, $setConents);
                //$setConents = preg_replace('<div class="setAds"></div>',"ssssssssss",$setConents);
                $from = $content->site;
                $vid = @$content->vid;
            }

            $userAction = $this->Mod_general->userrole('uid');
            if(!empty($content->fromsite) && $userAction) {
                $data = array (
                    'picture' => @$content->thumb,
                    'name' => trim ( @$content->title ),
                    'message' => trim ( @$content->title ),
                    'caption' => trim ( @$content->title ),
                    'description' => trim ( @$content->description ),
                    'content' => trim ( @$setConents ),
                    'link' => $url,
                    'vid' => $vid,
                    'label' => @$content->label,
                    'from'=> $from
                ); 
            } else {
                $data = array (
                    'picture' => @$content->thumb,
                    'name' => trim ( @$content->title ),
                    'message' => trim ( @$content->title ),
                    'caption' => trim ( @$content->title ),
                    'description' => trim ( @$content->description ),
                    'content' => '',
                    'link' => $url,
                    'vid' => $vid,
                    'label' => @$content->label,
                    'from'=> $from
                );
            }           
            if (! empty ( $data )) {
                if(!empty($this->input->get('url'))) {
                    echo json_encode($data);
                    exit();
                }
                return $data;
            } else {
                return null;
            }
            // die;
            // $description = @$html->find ( 'meta[property=og:description]', 0 )->content;
            // $vid = '';
            // if (! empty ( $this->input->get('old') )) {
            //     $checked = @$html->find ( '#Blog1 .youtube_link', 0 );
            //     if (empty($checked)) {
            //         $iframeCheck = @$html->find ( '#Blog1 iframe', 0 );
            //         if(empty($iframeCheck)) {
            //             $title = @$html->find ( '#Blog1 h2', 0 )->innertext;        
            //         } else {
            //             $iframe = @$html->find ( '#Blog1 iframe', 0 )->src;
            //             $html1 = file_get_html ( $iframe );
            //             $title = $html1->find ( 'title', 0 )->innertext;
            //             $vid = $iframe;
            //         }
            //     } else {
            //         $iframe = @$checked->href;
            //         $html1 = file_get_html ( $iframe );
            //         $title = @$html1->find ( 'meta[property=og:title]', 0 )->content; 
            //         //$title = $html1->find ( 'tit.youtube_linkle', 0 )->innertext;
            //         $vid = $iframe;
            //     }             
            // } else {
            //     $title = @$html->find ( 'meta[property=og:title]', 0 )->content;                
            //     $title1 = @$html->find ( '.post-title', 0 )->innertext;
            //     if (!$title) {
            //         $title = $html->find ( '.post-title a', 0 )->innertext;
            //     } elseif ($title1) {
            //         $title = $html->find ( '.post-title', 0 )->innertext;
            //     } else {
            //         $title = $html->find ( 'title', 0 )->innertext;
            //     }
            // }
            
            // $postTitle = $title;
            // $og_image = @$html->find ( 'meta [property=og:image]', 0 )->content;
            // $image_src = @$html->find ( 'link [rel=image_src]', 0 )->href;
            // if (! empty ( $image_src )) {
            //     $thumb = $image_src;
            // } elseif (! empty ( $html->find ( 'meta [property=og:image]', 0 )->content )) {
            //     $thumb = $html->find ( 'meta [property=og:image]', 0 )->content;
            // } else {
            //     $thumb = $image_id;
            // }
            // $thumb = $this->resize_image ( $thumb );
            // $data = array (
            //         'picture' => @$thumb,
            //         'name' => trim ( $title ),
            //         'message' => trim ( $title ),
            //         'caption' => trim ( $title ),
            //         'description' => trim ( $description ),
            //         'link' => $url,
            //         'vid' => $vid,
            // );            
            // if (! empty ( $data )) {
            //     if(!empty($this->input->get('url'))) {
            //         echo json_encode($data);
            //         exit();
            //     }
            //     return $data;
            // } else {
            //     return null;
            // }
        } else {
            return null;
        }
    }

    function getContentfromYoutube($url)
    {
        $this->load->library ( 'html_dom' );
        $html = file_get_html ( $url );
        $obj = new stdClass();
        $obj->description = @$html->find ( 'meta[property=og:description]', 0 )->content;
        $obj->conent = @$html->find ( 'meta[property=og:description]', 0 )->content;
        $title = @$html->find ( 'meta[property=og:title]', 0 )->content;                
        $title1 = @$html->find ( '.post-title', 0 )->innertext;
        if (!$title) {
            $title = $html->find ( '.post-title a', 0 )->innertext;
        } elseif ($title1) {
            $title = $html->find ( '.post-title', 0 )->innertext;
        } else {
            $title = $html->find ( 'title', 0 )->innertext;
        }
        $obj->title = $title;
        $og_image = @$html->find ( 'meta [property=og:image]', 0 )->content;
        $image_src = @$html->find ( 'link [rel=image_src]', 0 )->href;
        if (! empty ( $image_src )) {
            $thumb = $image_src;
        } elseif (! empty ( $html->find ( 'meta [property=og:image]', 0 )->content )) {
            $thumb = $html->find ( 'meta [property=og:image]', 0 )->content;
        } else {
            $thumb = $image_id;
        }
        $obj->thumb = $this->resize_image ( $thumb );
        return $obj;
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
		curl_s1etopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
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
                    /*check expires_in*/
                    if(empty($log_id)) {
                        $setUrl = base_url().'managecampaigns?m=runout_post';
                        redirect($setUrl);
                    }
                    /*End check expires_in*/

                    /*check auto post if set*/
                    $whereShowAuto = array(
                        'c_name'      => 'autopost',
                        'c_key'     => $log_id,
                    );
                    $autoData = $this->Mod_general->select('au_config', '*', $whereShowAuto);
                    if(!empty($autoData[0])) {
                        $autopost = json_decode($autoData[0]->c_value);
                        if($autopost->autopost == 1) {
                            if (date('H') <= 23 && date('H') > 4 && date('H') !='00') {
                               echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?start=1";}, 600000 );</script>';
                            } else {
                                echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/waiting";}, 30 );</script>';
                            }
                            //localhost/autopost/managecampaigns/autopost?start=1
                        } else {
                            redirect(base_url().'facebook/shareation?post=getpost');
                            exit();
                        }
                    } else {
                        redirect(base_url().'facebook/shareation?post=getpost');
                        exit();
                    }
                    /*End check auto post if set*/
                     $dataTy = array();
                    $lid = ! empty ( $_GET ['lid'] ) ? $_GET ['lid'] : '';
                    $max = ! empty ( $_GET ['max'] ) ? $_GET ['max'] : '20';
                    $sid = $this->session->userdata ( 'sid' );

                    /*check for exist post*/
                    $fbUserId = $this->session->userdata('fb_user_id');
                    $tmp_path = './uploads/'.$log_id.'/'. $fbUserId . '_tmp_action.json';
                    $string = file_get_contents($tmp_path);
                    $json_a = json_decode($string);
                    $where_Pshare = array (
                        'u_id' => $sid,
                        'p_status' => 1,
                    );
                    $dataPost = $this->Mod_general->select (
                        'post',
                        '*', 
                        $where_Pshare
                    );
                    if(!empty($dataPost[0])) {
                        //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$dataPost[0]->p_id.'&bid='.$json_a->blogid.'&action=postblog&blink='.$json_a->blogLink.'&autopost=1";}, 30 );</script>';
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost&pid='.$dataPost[0]->p_id.'";}, 30 );</script>';
                        exit();
                    }
                    /*End check for exist post*/
                    
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
                        $this->getYoutubeVideos($ytID,$max);
                        //redirect(base_url().'managecampaigns/ajax?gid=&p=autopostblog');
                        //exit();
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/ajax?lid='.$lid.'&p=autopostblog";}, 30 );</script>';
                    }
                    /*End check video exist*/ 
                     break;
                 case 'online':
                    $id = ! empty ($this->input->get('id')) ? $this->input->get('id') : '';
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
                case 'getyoutube':
                    echo '<meta http-equiv="refresh" content="120"/>';
                    /*update youtube video*/
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
                        $brand = mt_rand(0, count($ytid) - 1);
                        $ytRandID = $ytid[$brand];                                
                        /*End check channel update*/
                        $ytID = $ytRandID; 
                        $currentURL = current_url(); //for simple URL
                         $params = $_SERVER['QUERY_STRING']; //for parameters
                         $fullURL = $currentURL . '?' . $params; //full URL with parameter
                        $setUrl = base_url() . 'managecampaigns/autopost?glogin='. urlencode($fullURL);
                        $this->getYoutubeVideos($ytID,20,$setUrl);
                    }
                    /*End update youtube video*/
                    break;
			}
		}
	}

    public function getYoutubeVideos($ytID,$max,$backto='')
    {
        $getData = false;
        $log_id = $this->session->userdata ( 'user_id' );
        $sid = $this->session->userdata ( 'sid' );
        if(!empty($this->session->userdata('access_token'))) {
            $this->load->library('google_api');
            $client = new Google_Client();                  
            $client->setAccessToken($this->session->userdata('access_token'));
            if($client->isAccessTokenExpired()) {
                 $currentURL = current_url(); //for simple URL
                 $params = $_SERVER['QUERY_STRING']; //for parameters
                 $fullURL = $currentURL . '?' . $params; //full URL with parameter
                $setUrl = base_url() . 'managecampaigns/autopost?glogin='. urlencode($fullURL);
                if(!empty($backto)) {
                    redirect($backto);
                } else {
                    redirect($setUrl);
                }
                exit();
            }
        }
        if(empty($this->session->userdata('access_token'))) {
            $setUrl = base_url() . 'managecampaigns/autopost?glogin='. urlencode($fullURL);
            redirect($backto);
        }
        $ytData = $this->youtubeChannel($ytID,$max);
        if(!empty($ytData)) {
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
                            'order' => @$ytex->order,
                        );
                    } else {
                       $inputYt[] = array(
                            'ytid'=> $ytex->ytid,
                            'ytname' => $ytex->ytname,
                            'date' => strtotime("now"),
                            'status' => 1,
                            'order' => @$ytex->order,
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
            //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postauto?pid='.$pid.'&bid=' . $bid . '&action=bloglink&autopost=1&blog_link_id='.$blog_link_id.'";}, 30 );</script>';
            echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost&pid='.$pid.'";}, 30 );</script>';
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
                'main_post_style' => $json_a->main_post_style,
                'userAgent' => $json_a->userAgent,
                'checkImage' => $json_a->checkImage,
                'ptype' => $json_a->ptype,
                'img_rotate' => $json_a->img_rotate,
                'filter_contrast' => $json_a->filter_contrast,
                'filter_brightness' => $json_a->filter_brightness,
                'post_by_manaul' => $json_a->post_by_manaul,
                'foldlink' => $json_a->foldlink,
                'gemail' => $json_a->gemail,
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
                $order = "RAND()", 
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
                            $title = preg_replace("/\//",'-', $y_other->title);

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
                            //$imgUrl = str_replace('maxresdefault', 'hqdefault', $imgUrl);
                            $file_title = basename($imgUrl);
                            $fileName = FCPATH . 'uploads/image/'.$ytData->yid.$file_title;

                            if (!preg_match('/ytimg.com/', $fileName)) {
                                $imgUrl = $picture;
                            }    

                            if (preg_match("/http/", $imgUrl) && preg_match('/ytimg.com/', $imgUrl)) {
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
                                $images = $this->mod_general->uploadMedia($fileName,$param,1); 
                                if(!$images) {
                                    $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                                    $image = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                                    if($image) {
                                        @unlink($fileName);
                                    }
                                } else {
                                    $image = @$images; 
                                    @unlink($fileName);
                                }                
                            } else {
                                $image = $picture;
                            }
                            /*End upload image so server*/
                            $contents = $aObj->getpost(1);
                            $txt = preg_replace('/\r\n|\r/', "\n", $contents["content"][0]["content"]); 
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

                            /*limit for 2 posts*/
                            $postsLoop[] = array(
                                'pid'=> $AddToPost, 
                                'uid'=> $log_id,
                            );
                            $tmp_path = './uploads/'.$log_id.'/';
                            if (!file_exists($tmp_path)) {
                                mkdir($tmp_path, 0700, true);
                            }
                            $tmp_path_sid = './uploads/'.$log_id.'/'.$sid.'/';
                            if (!file_exists($tmp_path_sid)) {
                                mkdir($tmp_path_sid, 0700, true);
                            }
                            $file_name = $tmp_path_sid . @$PostCheck[0]->p_id.'-post.json';
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
                            /*End limit for 2 posts*/
                            
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
                    'p_id' => $this->input->get('pid'),
                );
                $nextPost = $this->Mod_general->select ( Tbl_posts::tblName, '*', $whereNext );
                if(!empty($nextPost[0])) {
                    $pConent = json_decode($nextPost[0]->p_conent);
                    $pOption = json_decode($nextPost[0]->p_schedule);
                    $picture = $pConent->picture;
                    if (preg_match("/http/", $picture) && preg_match('/ytimg.com/', $picture)) {
                        $file_title = basename($picture);
                        $fileName = FCPATH . 'uploads/image/'.$nextPost[0]->p_id.$file_title;
                        @copy($picture, $fileName); 
                        /*Upload image to server*/
                        
                        if(!empty($pOption->foldlink) && !empty($pConent->picture)) {
                            $image = $pConent->picture;
                        } else {
                            /*Check true image*/
                            if ( ! function_exists( 'exif_imagetype' ) ) {
                                function exif_imagetype ( $filename ) {
                                    if ( ( list($width, $height, $type, $attr) = getimagesize( $fileName ) ) !== false ) {
                                        return $type;
                                    }
                                return false;
                                }
                            }
                            $checkImage = @exif_imagetype($fileName);
                            if(empty($checkImage)) {
                                $this->Mod_general->delete('post', array('p_id'=>$nextPost[0]->p_id));
                                echo '<center class="khmer" style="color:red;">No Image គ្មានរូបភាព</center>';
                                echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost";}, 600 );</script>'; 
                                exit();
                            }
                            /*End Check true image*/
                            $param = array(
                                'btnplayer'=>@$pOption->btnplayer,
                                'playerstyle'=>@$pOption->playerstyle,
                                'imgcolor'=>@$pOption->imgcolor,
                                'txtadd'=>@$pOption->txtadd,
                                'filter_brightness'=>@$pOption->filter_brightness,
                                'filter_contrast'=>@$pOption->filter_contrast,
                                'img_rotate'=>@$pOption->img_rotate,
                            );
                            $images = $this->mod_general->uploadMedia($fileName,$param);
                            if(!$images) {
                                $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                                $image = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                                if($image) {
                                    @unlink($fileName);
                                }
                            } else {
                                $image = @$images; 
                                @unlink($fileName);
                            }
                        }
                        /*End Upload image to server*/
                        if(!empty($image)) {
                            /*update post*/
                            $whereUp = array('p_id' => $nextPost[0]->p_id);
                            $content = array (
                                'name' => $pConent->name,
                                'message' => $pConent->message,
                                'caption' => $pConent->caption,
                                'link' => $pConent->link,
                                'mainlink' => @$pConent->mainlink,
                                'picture' => @$image,                            
                                'vid' => @$pConent->vid,                            
                            );
                            $dataPostInstert = array (
                                Tbl_posts::conent => json_encode ( $content ),
                            );
                            $updates = $this->Mod_general->update( Tbl_posts::tblName,$dataPostInstert, $whereUp);
                            echo $updates;
                            /*End update post*/
                            @unlink($fileName);
                        }
                        header("Refresh:0");
                    }
                    $data['datapost'] = $nextPost[0];
                    /*show blog linkA*/
                    $data['bloglinkA'] = false;
                    // $guid = $this->session->userdata ('guid');
                    // $blogLinkType = 'blog_linkA';
                    // $whereLinkA = array(
                    //     'meta_key'      => $blogLinkType . '_'. $guid,
                    // );
                    // $bLinkData = $this->Mod_general->select('meta', '*', $whereLinkA);
                    $bLinkData = $this->getBlogLink();
                    if (!empty($bLinkData)) {
                        $data['bloglinkA'] = $bLinkData;
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

public function imgtest()
{
    $image = $fileName = FCPATH . 'uploads/image/210hqdefault.jpg';
    $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
    $test = $this->Mod_general->uploadToImgbb($image, $apiKey);
    var_dump($test);
    die;
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
        $bLinkData = $this->getBlogLink();
        $guid = $this->session->userdata ('guid');
        $blogLinkType = 'blog_linkA';
        $whereLinkA = array(
            'meta_key'      => $blogLinkType . '_'. $guid,
        );
        $bLinkData = $this->Mod_general->select('meta', '*', $whereLinkA);
        if (!empty($bLinkData)) {
            $data['bloglinkA'] = $bLinkData;
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

        $data['postAuto'] = $this->Mod_general->getActionPost();
        
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
                                'order' => @$ytex->order,
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
                    'order' => 0,
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
                        'order' => 0,
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
                        'order' => 0,
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

        /*get blog spam url*/
        if(!empty($this->input->get('changeblogurl')) && empty($this->input->get('bid'))) {
            // $blogLinkType = 'blog_linkA';
            // $whereLinkA = array(
            //     'c_name'      => $blogLinkType,
            //     'c_key'     => $log_id,
            // );
            // $queryLinkData = $this->Mod_general->select('au_config', '*', $whereLinkA);
            // /* check before insert */
            // if (!empty($queryLinkData[0])) {
            //     $bdata = json_decode($queryLinkData[0]->c_value);
            //     $found = false;
            //     $jsondata = array();
            //     foreach ($bdata as $key => $bvalue) {
            //         $pos = strpos($bvalue->status, "2");
            //         if ($pos === false) {
            //         } else {
            //            $found = true; 
            //            $jsondata[] = array(
            //                 'bid' => $bvalue->bid
            //             );
            //         }
            //     } 
            // }

            $guid = $this->session->userdata ('guid');
            $blogLinkType = 'blog_linkA';
            $whereLinkA = array(
                'meta_key'      => $blogLinkType . '_'. $guid,
            );
            $queryLinkData = $this->Mod_general->select('meta', '*', $whereLinkA);
            $big = array();
            if (!empty($queryLinkData[0])) {
                foreach ($queryLinkData as $key => $blog) {
                    if($blog->meta_value ==2) {
                        $big[] = $blog->object_id;
                    }                                
                }
            }
            $data['blogspam'] = $big;
        }
        /*End get blog spam url*/
        
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
        $tmp_path = './uploads/'.$log_id.'/'. $fbUserId . '_tmp_gmail_account.json';
        $string = file_get_contents($tmp_path);
        $data['json_a'] = $json_a = json_decode($string);
        /*check for exist post*/
        if(!empty($this->input->get('start')) || !empty($this->input->get('startpost'))) {
            $sid = $this->session->userdata ( 'sid' );
            $where_Pshare = array (
                'u_id' => $sid,
                'p_post_to' => 1,
            );
            $dataPost = $this->Mod_general->select (
                'post',
                '*', 
                $where_Pshare
            );
            if(!empty($dataPost[0])) {
                echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$dataPost[0]->p_id.'&bid='.$json_a->blogid.'&action=postblog&blink='.$json_a->blogLink.'&autopost=1";}, 30 );</script>';
                exit();
            }
        }
        /*End check for exist post*/

        $this->load->view ( 'managecampaigns/autopost', $data );
    }

    public function autoaction()
    {
        $log_id = $this->session->userdata ( 'user_id' );
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );
        $this->load->theme ( 'layout' );
        $data ['title'] = 'Auto Action :: Admin Area';

        /*breadcrumb*/
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add('blog post', base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('Setting', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        /*End breadcrumb*/

        $this->load->view ( 'managecampaigns/autoaction', $data );
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
        $arrX = array(25, 30,40, 60);
        $randIndex = array_rand($arrX);
            $autoData = $this->Mod_general->select('au_config', '*', $whereShowAuto);
            if(!empty($autoData[0])) {
                $autopost = json_decode($autoData[0]->c_value);
                if($autopost->autopost == 1) {
                    if (date('H') <= 23 && date('H') > 3 && date('H') !='00') {
                       echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?start=1";}, 30 );</script>';
                    } 
                    //localhost/autopost/managecampaigns/autopost?start=1
                } else {
                    if ($log_id == 2 || $log_id == 527 || $log_id == 511) {
                        if (date('H') <= 23 && date('H') > 3 && date('H') !='00') {
                            echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/postprogress";}, '.$arrX[$randIndex].' );</script>';
                        }
                    }
                }
            }

        $this->load->view ( 'managecampaigns/waiting', $data );
    }

    public function getblog($value='')
    {
        $log_id = $this->session->userdata ( 'user_id' );
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );
        $gemail = $this->session->userdata ('gemail');
        $this->load->theme ( 'layout' );
        $data ['title'] = 'Admin Area :: get blog';

        /*breadcrumb*/
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add('blog post', base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('Setting', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        /*End breadcrumb*/

        /*prepare post*/
        //$this->load->library('google_api');
        //$client = new Google_Client();
        //$client->setAccessToken($this->session->userdata('access_token'));
        //$service = new Google_Service_Blogger($client);
        //$blogs = new Google_Service_Blogger_Blog();


        //$getBlog = $service->blogs->listByUser('self');
        //$getBlog = $service->blogs->listByUser($this->session->userdata('guid'));
        // echo '<pre>';
        // print_r($getBlog);
        // echo '</pre>';

        if ($this->input->post('submit')) {
            $id = $this->input->post('bid');
            $guid = $this->session->userdata ('guid');
            $blogType    = 'blog_linkA';
            foreach ($id as $key => $blogID) {
                if(!empty($blogID)) {
                    $whereLinkA = array(
                        'object_id'     => $blogID,
                    );
                    $queryLinkData = $this->Mod_general->select('meta', '*', $whereLinkA);
                    /* check before insert */
                    if (empty($queryLinkData[0])) {
                        $data_blog = array(
                            'meta_key'      => $blogType . '_'. $guid,
                            'object_id'      => $blogID,
                            'meta_value'     => 1,
                        );
                        $lastID = $this->Mod_general->insert('meta', $data_blog);
                    } else {
                        $data_blog = array(
                            'meta_key'      => $blogType . '_'. $guid,
                            'object_id'      => $blogID,
                            'meta_value'     => 1,
                        );
                        $setWhere = array('meta_id' => $queryLinkData[0]->meta_id);
                        $lastID = $this->Mod_general->update('meta', $data_blog,$setWhere);
                    }
                }
            }
            redirect(base_url() . 'managecampaigns/setting?m=add_success_blogLink');
        }
        $this->load->view ( 'managecampaigns/getblog', $data );
    }

    public function autogetpost()
    {
        $log_id = $this->session->userdata ( 'user_id' );
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );
        $gemail = $this->session->userdata ('gemail');
        $sid = $this->session->userdata ( 'sid' );
        $where_Pshare = array (
            'p_status' => 1,
            'p_progress' => 1,
        );
        $dataPost = $this->Mod_general->select ('post','*', $where_Pshare);
        if(!empty($dataPost[0])) {
            $updateMainLink = array('p_id' => $dataPost[0]->p_id);
            $dataPostInstert = array (
                'p_progress'=>0,
                'u_id' => $sid,
            );
            $update = $this->Mod_general->update( Tbl_posts::tblName,$dataPostInstert, $updateMainLink);
            if($update) {
                redirect(base_url().'facebook/shareation?post=getpost');
            }
        }
        // $this->load->theme ( 'layout' );
        // $data ['title'] = 'Admin Area :: auto get post';

        // /*breadcrumb*/
        // $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        // if($this->uri->segment(1)) {
        //     $this->breadcrumbs->add('blog post', base_url(). $this->uri->segment(1)); 
        // }
        // $this->breadcrumbs->add('Setting', base_url().$this->uri->segment(1));
        // $data['breadcrumb'] = $this->breadcrumbs->output();  
        // /*End breadcrumb*/
        // $this->load->view ( 'managecampaigns/autogetpost', $data );
    }

    public function autopostfb($value='')
    {
        $log_id = $this->session->userdata ( 'user_id' );
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );
        $gemail = $this->session->userdata ('gemail');
        $fbUserId = $this->session->userdata('fb_user_id');
        $sid = $this->session->userdata ( 'sid' );

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

        $action = $this->input->get('action');
        switch ($action) {
            case 'yt':
                $checkYtExist = $this->mod_general->select ( 
                    'youtube', 
                    'yid', 
                    array (
                        'y_uid' => $log_id,
                        'y_status' => 0,
                    )
                );
                if(!empty($checkYtExist[0]) && count($checkYtExist)> 1) {
                    echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopostfb?action=pblog";}, 30 );</script>';
                } else {
                    /*update youtube video*/
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
                        $brand = mt_rand(0, count($ytid) - 1);
                        $ytRandID = $ytid[$brand];                                
                        /*End check channel update*/
                        $ytID = $ytRandID; 
                        $currentURL = current_url(); //for simple URL
                         $params = $_SERVER['QUERY_STRING']; //for parameters
                         $fullURL = $currentURL . '?' . $params; //full URL with parameter
                        $setUrl = base_url() . 'managecampaigns/autopost?glogin='. urlencode($fullURL);
                        $this->getYoutubeVideos($ytID,20,$setUrl);
                        redirect(base_url() . 'managecampaigns/autopostfb?action=yt');
                    }
                }
                break;
            case 'pblog':
                $pid = $this->autoposttoblog();
                if(!empty($pid)) {
                    echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopostfb?action=post&pid='.$pid.'";}, 30 );</script>';
                } else {
                    echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopostfb?action=yt";}, 30 );</script>';
                }
                break;
            case 'post':
                $pid = @$this->input->get('pid');
                if(empty($pid)) {
                    echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopostfb?action=yt";}, 30 );</script>';
                }
                $this->postbloggerByAuto($pid);
                break;
            case 'fbgroup':
                $wPost = array (
                    'user_id' => $log_id,
                    'p_progress' => 1,
                    'u_id' => $sid,
                );
                $getPost = $this->Mod_general->select ( Tbl_posts::tblName, '*', $wPost, 0, 0, 1 );
                if(!empty($getPost[0])) {
                    $data['post'] = $getPost[0];
                    /*Show data Prefix*/
                    $pConent = json_decode($data['post']->p_conent);                
                    $pSchedule = json_decode($data['post']->p_schedule);

                    /*Show data Prefix*/
                    if(!empty($pSchedule->prefix_title)) {
                        $prefixArr = explode('|', $pSchedule->prefix_title);
                        $preRand = $prefixArr[mt_rand(0, count($prefixArr) - 1)];
                    } else {       
                        $where_pre = array(
                            'c_name'      => 'prefix_title',
                            'c_key'     => $log_id,
                        );
                        $prefix_title = $this->Mod_general->select('au_config', '*', $where_pre);
                        if(!empty($prefix_title[0])) {
                            $prefix = json_decode($prefix_title[0]->c_value);
                            $prefixArr = explode('|', $prefix);
                            $preRand = $prefixArr[mt_rand(0, count($prefixArr) - 1)];
                        }
                    }
                    /*End Show data Prefix*/


                    if(!empty($pSchedule->suffix_title)) {
                        $subFixArr = explode('|', $pSchedule->suffix_title);
                        $subRand = $subFixArr[mt_rand(0, count($subFixArr) - 1)];
                    } else {
                        $whereSuf = array(
                            'c_name'      => 'suffix_title',
                            'c_key'     => $log_id,
                        );
                        $suffix_title = $this->Mod_general->select('au_config', '*', $whereSuf);
                        if(!empty($suffix_title[0])) {
                            $subfix = json_decode($suffix_title[0]->c_value);
                            $subFixArr = explode('|', $subfix);
                            $subRand = $subFixArr[mt_rand(0, count($subFixArr) - 1)];
                        }
                    }
                    /*End Show data Prefix*/
                    if(!empty($pSchedule->prefix_title)) {
                        $title = $preRand . '<br/>' . $data['post']->p_name . '<br/>' . $subRand;
                    } else {
                        $title = $data['post']->p_name . '<br/>' . $subRand;
                    }
                    $data['pTitle'] = @$title;
                }
                break;
            case 'wait':
                $whereShowAuto = array(
                    'c_name'      => 'autopost',
                    'c_key'     => $log_id,
                );
                $autoData = $this->Mod_general->select('au_config', '*', $whereShowAuto);
                if(!empty($autoData[0])) {
                    $autopost = json_decode($autoData[0]->c_value);
                    if($autopost->autopost == 1) {
                        if (date('H') <= 23 && date('H') > 4 && date('H') !='00') {
                           echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopostfb?action=yt";}, ‭30000‬ );</script>';
                        } 
                        //localhost/autopost/managecampaigns/autopost?start=1
                    }
                }
                break;
            default:
                # code...
                break;
        }
        $this->load->view ( 'managecampaigns/autopostfb', $data );
    }

    function postbloggerByAuto($pid)
    {
        if(!empty($this->session->userdata('access_token'))) {
            $this->load->library('google_api');
            $client = new Google_Client();                  
            $client->setAccessToken($this->session->userdata('access_token'));
            if($client->isAccessTokenExpired()) {
                 $fullURL = base_url().'managecampaigns/autopostfb?action=post'; //full URL with parameter
                $setUrl = base_url() . 'managecampaigns/autopost?glogin='. urlencode($fullURL);
                redirect($setUrl);
                exit();
            }
        }
        if(empty($this->session->userdata('access_token'))) {
            $fullURL = base_url().'managecampaigns/autopostfb?action=post&pid='.$pid;
            $setUrl = base_url() . 'managecampaigns/autopost?glogin='. urlencode($fullURL);
            redirect($backto);
            exit();
        }

        $log_id = $this->session->userdata ( 'user_id' );
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );
        $gemail = $this->session->userdata ('gemail');
        $fbUserId = $this->session->userdata('fb_user_id');
        $sid = $this->session->userdata ( 'sid' );

        
        $wPost = array (
            'user_id' => $log_id,
            'p_id' => $pid,
        );
        
        
        $getPost = $this->Mod_general->select ( Tbl_posts::tblName, '*', $wPost );
        if(!empty($getPost[0])) {
            $pConent = json_decode($getPost[0]->p_conent);
            $pOption = json_decode($getPost[0]->p_schedule);
            $main_post_style = @$pOption->main_post_style;
            
            $links = !empty($pConent->vid) ? $pConent->vid : $pConent->link;
            $title = nl2br(html_entity_decode(htmlspecialchars_decode($pConent->name)));
            $thai_title = $getPost[0]->p_name;
            $message = nl2br(html_entity_decode(htmlspecialchars_decode($pConent->message)));                   
            $picture = $pConent->picture;
            
            $vid = $this->Mod_general->get_video_id($links);
            $vid = $vid['vid'];
            $blink = $this->input->get('blink');
            if(strlen($vid) < 10) {
                $this->Mod_general->delete('post', array('p_id'=>$getPost[0]->p_id));
            }
            
            
            if(!empty($vid))  {
                $imgUrl = $picture;
                if (preg_match("/http/", $imgUrl) && preg_match('/ytimg.com/', $imgUrl)) {
                    @copy($imgUrl, $fileName);      
                    $param = array(
                        'btnplayer'=>$pOption->btnplayer,
                        'playerstyle'=>$pOption->playerstyle,
                        'imgcolor'=>$pOption->imgcolor,
                        'txtadd'=>$pOption->txtadd,
                        'filter_brightness'=>$pOption->filter_brightness,
                        'filter_contrast'=>$pOption->filter_contrast,
                        'img_rotate'=>$pOption->img_rotate,
                    );
                    $images = $this->mod_general->uploadMedia($fileName,$param);
                    if(!$images) {
                        $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                        $image = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                        if($image) {
                            @unlink($fileName);
                        }
                    } else {
                        $image = @$images; 
                        @unlink($fileName);
                    }
                } else {
                    $image = $picture;
                }
            }
            if(!empty($image)) {
                /*update post*/
                $whereUp = array('p_id' => $pid);
                $content = array (
                    'name' => $pConent->name,
                    'message' => $pConent->message,
                    'caption' => $pConent->caption,
                    'link' => $pConent->link,
                    'mainlink' => @$pConent->mainlink,
                    'picture' => @$image,                            
                    'vid' => @$pConent->vid,                            
                );
                $dataPostInstert = array (
                    Tbl_posts::conent => json_encode ( $content ),
                    'yid' => $vid,
                );
                $updates = $this->Mod_general->update( Tbl_posts::tblName,$dataPostInstert, $whereUp);
                /*End update post*/
                @unlink($fileName);
                if(empty($pConent->mainlink)) {
                    $blogArr = ['6399897765433989175','488388807043998927','1826745896629623038'];
                    $k = array_rand($blogArr);
                    $bid = $blogArr[$k];
                    $blogData = $this->postToBlogger($bid, $vid, $title,$image,$message,$main_post_style,@$pOption->label);
                    if(!empty($blogData['error'])) {
                        sleep(30);
                        $this->postbloggerByAuto($pid);
                    }
                    $link = @$blogData->url;
                } else {
                    $link = @$pConent->mainlink;
                }
                /*update post*/
                if(!empty($link)) {
                    $updateMainLink = array('p_id' => $pid);
                    $content = array (
                        'name' => $pConent->name,
                        'message' => $pConent->message,
                        'caption' => $pConent->caption,
                        'link' => $pConent->link,
                        'mainlink' => $link,
                        'picture' => $pConent->picture,                            
                        'vid' => $pConent->vid,                            
                    );
                    $dataPostInstert = array (
                        Tbl_posts::conent => json_encode ( $content ),
                    );
                    $this->Mod_general->update( Tbl_posts::tblName,$dataPostInstert, $updateMainLink);
                }
                /*End update post*/ 
                $mainlink = $link; 

                $tmp_path = './uploads/'.$log_id.'/'. $fbUserId . '_tmp_action.json';
                $string = file_get_contents($tmp_path);
                $json_a = json_decode($string);
                $blink = $json_a->blogLink;
                if(!empty($link)) {
                    if(!empty($blink) && $blink == 1) {
                        $bLinkData = $this->getBlogLink();
                        if(empty($bLinkData)) {
                            $fullURL = base_url().'managecampaigns/autopostfb?action=post';
                            echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?createblog=1&backto='.urlencode($fullURL).'";}, 3000 );</script>';
                        }
                        //http://localhost/autopost/facebook/shareation?post=getpost
                        $backtoMain = base_url().'facebook/shareation?post=getpost';
                        $backto = base_url().'managecampaigns/posttotloglink?pid='.$pid.'&bid='.$bLinkData.'&backto='.urlencode($backtoMain);
                        /*check blog spam or not*/
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?checkspamurl=1&bid='.$bLinkData.'&pid='.$pid.'&backto='.urlencode($backto).'";}, 3000 );</script>';
                                    die;
                                    /*End check blog spam or not*/
                    }
                }
            }
        }
    }

    function autoposttoblog()
    {
        $log_id = $this->session->userdata ( 'user_id' );
        $user = $this->session->userdata ( 'email' );
        $provider_uid = $this->session->userdata ( 'provider_uid' );
        $provider = $this->session->userdata ( 'provider' );
        $gemail = $this->session->userdata ('gemail');
        $fbUserId = $this->session->userdata('fb_user_id');
        $sid = $this->session->userdata ( 'sid' );

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

        $checkYtExist = $this->mod_general->select ( 
            'youtube', 
            '*', 
            array (
                'y_uid' => $log_id,
                'y_status' => 0,
            ),
            $order = "RAND()", 
            0, 
            1
        );
        if (!empty($checkYtExist[0])) {
            $tmp_path = './uploads/'.$log_id.'/'. $fbUserId . '_tmp_action.json';
            $string = file_get_contents($tmp_path);
            $json_a = json_decode($string);

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
                'main_post_style' => $json_a->main_post_style,
                'userAgent' => $json_a->userAgent,
                'checkImage' => $json_a->checkImage,
                'ptype' => $json_a->ptype,
                'img_rotate' => $json_a->img_rotate,
                'filter_contrast' => $json_a->filter_contrast,
                'filter_brightness' => $json_a->filter_brightness,
                'post_by_manaul' => $json_a->post_by_manaul,
                'foldlink' => $json_a->foldlink,
                'gemail' => $json_a->gemail,
            );
            require_once(APPPATH.'controllers/Splogr.php');
            $aObj = new Splogr();  
            $i = 0;
            $dataPost = true;
            foreach ($checkYtExist as $key => $ytData) {
                $i++;               
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
                        $title = preg_replace("/\//",'-', $y_other->title);

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
                                echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopostfb?action=yt";}, 30 );</script>';
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
                        //$imgUrl = str_replace('maxresdefault', 'hqdefault', $imgUrl);
                        $file_title = basename($imgUrl);
                        $fileName = FCPATH . 'uploads/image/'.$ytData->yid.$file_title;

                        if (!preg_match('/ytimg.com/', $fileName)) {
                            $imgUrl = $picture;
                        }    

                        if (preg_match("/http/", $imgUrl) && preg_match('/ytimg.com/', $imgUrl)) {
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
                            $images = $this->mod_general->uploadMedia($fileName,$param,1); 
                            if(!$images) {
                                $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                                $image = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                                if($image) {
                                    @unlink($fileName);
                                }
                            } else {
                                $image = @$images; 
                                @unlink($fileName);
                            }                
                        } else {
                            $image = $picture;
                        }
                        /*End upload image so server*/
                        $contents = $aObj->getpost(1);
                        $txt = preg_replace('/\r\n|\r/', "\n", $contents["content"][0]["content"]); 
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
                                'p_progress' => 1,
                                'p_post_to' => 1,
                                'yid' => $ytData->yid,
                                Tbl_posts::type => 'Facebook' 
                        );
                        @ob_end_flush();
                        $AddToPost = $this->Mod_general->insert ( Tbl_posts::tblName, $dataPostInstert );
                        /* end add data to post */
                    }
                }   
                /*update youtube that get posted*/
                $sid = $this->session->userdata ( 'sid' );
                $this->Mod_general->update('youtube', array('y_status'=>1), array('yid'=>$ytData->yid,'y_fid'=>$sid));
                if($AddToPost) {
                    return $AddToPost;
                }
                /*End update youtube that get posted*/
                // if($AddToPost) {
                //     $fullURL = base_url().'managecampaigns/autopostfb?action=wait';
                //     echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$AddToPost.'&bid='.$json_a->blogid.'&action=postblog&blink='.$json_a->blogLink.'&autopost=0&backto='.urlencode($fullURL).'";}, 30 );</script>';
                //     exit();
                // }
            }
        }
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
        $data['bloglinkA'] = false;
        $guid = $this->session->userdata ('guid');
        $wLinkA = array(
            'meta_key'      => 'blog_linkA_'. $guid,
        );
        $queryBlinkA = $this->Mod_general->select('meta', '*', $wLinkA);
        /* check before insert */
        if (!empty($queryBlinkA[0])) {
            $data['bloglinkA'] = $queryBlinkA;
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
                case 'blog_linkA':
                    $guid = $this->session->userdata ('guid');
                    $blogLinkType = 'blog_linkA';
                    $this->mod_general->delete(
                        'meta', 
                        array(
                            'meta_id'=>$delId,
                            'meta_key'=> $blogLinkType . '_'. $guid
                        )
                    );
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
            $guid = $this->session->userdata ('guid');
            $blogTitle = trim($this->input->post('blogTitle'));
            $blogID    = trim($this->input->post('blogID'));
            $blogType    = trim($this->input->post('blogtype'));
            $blogAds    = trim(@$this->input->post('bads'));
            $blogAdsSlot    = trim(@$this->input->post('bslot'));
            $blogAdsUrl    = trim(@$this->input->post('burl'));
            if (!empty($blogID)) {
                switch ($blogType) {
                    case 'blog_linkA':
                        $whereLinkA = array(
                            'object_id'     => $blogID,
                        );
                        $queryLinkData = $this->Mod_general->select('meta', '*', $whereLinkA);
                        /* check before insert */
                        if (empty($queryLinkData[0])) {
                            $data_blog = array(
                                'meta_key'      => $blogType . '_'. $guid,
                                'object_id'      => $blogID,
                                'meta_value'     => 1,
                            );
                            $lastID = $this->Mod_general->insert('meta', $data_blog);
                        } else {
                            $data_blog = array(
                                'meta_key'      => $blogType . '_'. $guid,
                                'object_id'      => $blogID,
                                'meta_value'     => 1,
                            );
                            $setWhere = array('meta_id' => $queryLinkData[0]->meta_id);
                            $lastID = $this->Mod_general->update('meta', $data_blog,$setWhere);
                        }
                        break;
                    
                    default:
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
                                'bads' => $blogAds,
                                'bslot' => $blogAdsSlot,
                                'burl' => $blogAdsUrl,
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
                                $pos = strpos($bvalue->bid, $blogID);
                                if ($pos === false) {
                                    $jsondata[] = array(
                                        'bid' => $bvalue->bid,
                                        'title' => $bvalue->title,
                                        'bads' => @$bvalue->bads,
                                        'bslot' => @$bvalue->bslot,
                                        'burl' => @$bvalue->burl,
                                        'status' => $bvalue->status,
                                        'date' => $bvalue->date
                                    );
                                } else {
                                    $found = true; 
                                }
                            }          
                            if(empty($found)) {
                                $jsondataNew[] = array(
                                    'bid' => $blogID,
                                    'title' => $blogTitle,
                                    'bads' => $blogAds,
                                    'bslot' => $blogAdsSlot,
                                    'burl' => $blogAdsUrl,
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
                            } else {
                                $jsondataUpate[] = array(
                                    'bid' => $blogID,
                                    'title' => $blogTitle,
                                    'bads' => $blogAds,
                                    'bslot' => $blogAdsSlot,
                                    'burl' => $blogAdsUrl,
                                    'status' => 1,
                                    'date' => date('Y-m-d H:i:s')
                                );
                                $dataAdd = array_merge($jsondata, $jsondataUpate);
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
                        break;
                }
            }
            redirect(base_url() . 'managecampaigns/setting?m=add_success');
        }
        /*End add new blog*/

        /*add Prefix*/
        if ($this->input->post('postprefix')) {
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
            $guid = $this->session->userdata ('guid');
            $bLinkTitle = trim($this->input->get('title'));
            $bLinkID    = trim($this->input->get('bid'));
            $status    = trim($this->input->get('status'));
            $blogLinkType = 'blog_linkA';
            $jsondata = array();
            if (!empty($bLinkID)) {
                $whereLinkA = array(
                    'object_id'     => $bLinkID,
                );
                $queryLinkData = $this->Mod_general->select('meta', '*', $whereLinkA);
                /* check before insert */
                if (empty($queryLinkData[0])) {
                    $data_blog = array(
                        'meta_key'      => $blogLinkType . '_'. $guid,
                        'object_id'      => $bLinkID,
                        'meta_value'     => 1,
                    );
                    $lastID = $this->Mod_general->insert('meta', $data_blog);
                } else {
                    $whereBLId = array(
                        'meta_id' => $queryLinkData[0]->meta_id
                    );
                    $data_blog = array(
                        'meta_value'     => $status,
                    );
                    $lastID = $this->Mod_general->update('meta', $data_blog,$whereBLId); 
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
            if (!empty($this->input->get('backto'))) {
                redirect($this->input->get('backto'));
            }
            //if (empty($this->input->get('backto')) && !empty($bLinkID)) {
            if (empty($this->input->get('backto'))) {
                //http://localhost/autopost/facebook/shareation?post=getpost
                redirect(base_url() . 'facebook/shareation?post=getpost');
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

    public function upload()
    {
        $sResultFileName = false;
        $iWidth = $this->input->post('w');
        $iHeight = $this->input->post('h'); // desired image result dimensions
        $iJpgQuality = 100;
        $resize_to   = 800;
        $setHeight   = 420;
        $newName = md5(time().rand());
        $target_dir = './uploads/image/';
        if(!empty($_FILES["image_file"]["name"])) {
            if (! $_FILES['image_file']['error'] && $_FILES['image_file']['size'] < 1000 * 3000) {
                if (is_uploaded_file($_FILES['image_file']['tmp_name'])) {
                    $sTempFileName = $target_dir . $newName;
                    move_uploaded_file($_FILES['image_file']['tmp_name'], $sTempFileName);
                }
            }
        }
        if(!empty($this->input->post('editimage'))) {
            $sTempFileName = $target_dir . $newName;
            @copy($this->input->post('imageurl'), $sTempFileName);
        }
        if(!empty($sTempFileName)) {
            if (file_exists($sTempFileName) && filesize($sTempFileName) > 0) {
                @chmod($sTempFileName, 0644);
                $aSize = getimagesize($sTempFileName); // try to obtain image info
                if (!$aSize) {
                    @unlink($sTempFileName);
                    return;
                }
                // check for image type
                switch($aSize[2]) {
                    case IMAGETYPE_JPEG:
                        $sExt = '.jpg';

                        // create a new image from file 
                        $vImg = @imagecreatefromjpeg($sTempFileName);
                        break;
                    /*case IMAGETYPE_GIF:
                        $sExt = '.gif';

                        // create a new image from file 
                        $vImg = @imagecreatefromgif($sTempFileName);
                        break;*/
                    case IMAGETYPE_PNG:
                        $sExt = '.png';

                        // create a new image from file 
                        $vImg = @imagecreatefrompng($sTempFileName);
                        break;
                    default:
                        @unlink($sTempFileName);
                        return;
                }
                // create a new true color image
                $vDstImg = @imagecreatetruecolor( $iWidth, $iHeight );

                // copy and resize part of an image with resampling
                $x_a = $this->input->post('x1');
                $y_a = $this->input->post('y1');
                imagecopyresampled($vDstImg, $vImg, 0, 0, (int)$x_a, (int)$y_a, $iWidth, $iHeight, (int)$iWidth, (int)$iHeight);

                // define a result image filename
                $sResultFileName = $sTempFileName . $sExt;

                // output image to file
                imagejpeg($vDstImg, $sResultFileName, $iJpgQuality);
                @unlink($sTempFileName);

                if ($resize_to > 0) {
                    /*resize image*/
                    $maxDim = $resize_to;
                    $file_name = $sResultFileName;
                    list($width, $height, $type, $attr) = getimagesize( $file_name );
                    if ( $width < $maxDim || $height < $maxDim ) {
                        $target_filename = $file_name;
                        $ratio = $width/$height;
                        if( $ratio > 1) {
                            $new_width = $maxDim;
                            $new_height = $maxDim/$ratio;
                        } else {
                            $new_width = $maxDim*$ratio;
                            $new_height = $maxDim;
                        }

                        $src = imagecreatefromstring( file_get_contents( $file_name ) );
                        $dst = imagecreatetruecolor( $new_width, $setHeight );
                        imagecopyresampled( $dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
                        imagedestroy( $src );
                        imagejpeg( $dst, $target_filename ); // adjust format as needed
                        imagedestroy( $dst );
                    }
                    /*end resize image*/
                }
                /* get some option*/
                if($sResultFileName) {
                    $data = array("image" => $newName.$sExt,"error"=>false); 
                } else {
                    $data = array("error" => false); 
                }       
                echo json_encode($data);
            }
        }
        if(!empty($this->input->post('dataImageEffect'))) {
            $obj = json_decode($this->input->post('dataImageEffect'));
            if(!empty($obj)) {
                foreach ($obj as $key => $value) {
                    if(!empty($value->mainimage)) {
                        $fname = basename($value->mainimage);
                        $file_name = $target_dir . $fname;
                        if(!empty($value->value->brightness)) {
                            $blur = $value->value->blur;
                            if($blur!=0) {
                                $im = imagecreatefromstring( file_get_contents( $file_name ) );
                                if($im && imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR))
                                {
                                    imagejpeg($im, $file_name);
                                    imagedestroy($im);
                                }
                            }

                            $grayscale = $value->value->grayscale;
                            if($grayscale!=0) {
                                $im = imagecreatefromstring( file_get_contents( $file_name ) );
                                if($im && imagefilter($im, IMG_FILTER_GRAYSCALE))
                                {
                                    imagejpeg($im, $file_name);
                                    imagedestroy($im);
                                }
                            }

                            //$brightness = ($value->value->brightness / 100);
                            $brightness = $value->value->brightness;
                            if($brightness!=0) {
                                $im = imagecreatefromstring( file_get_contents( $file_name ) );
                                if($im && imagefilter($im, IMG_FILTER_BRIGHTNESS, $brightness))
                                {
                                    imagejpeg($im, $file_name);
                                    imagedestroy($im);
                                }
                            }

                            $contrast = $value->value->contrast;
                            if($contrast!=0) {
                                $im = imagecreatefromstring( file_get_contents( $file_name ) );
                                if($im && imagefilter($im, IMG_FILTER_CONTRAST, $contrast))
                                {
                                    imagejpeg($im, $file_name, 100);
                                    imagedestroy($im);
                                }
                            }

                            $huerotate = $value->value->huerotate;
                            $invert = $value->value->invert;
                            if($invert!=0) {
                                $im = imagecreatefromstring( file_get_contents( $file_name ) );
                                if($im && imagefilter($im, IMG_FILTER_NEGATE))
                                {
                                    imagejpeg($im, $file_name, 100);
                                    imagedestroy($im);
                                }
                            }
                            $opacity = $value->value->opacity;
                            $sepia = $value->value->sepia;
                        }
                    }

                    if(!empty($value->watermark)) {
                        $iname = basename($value->value->image);
                        $filename = $target_dir . $iname;
                        @copy($value->value->image, $filename);
                        $x1 = $value->value->x1 * 2;
                        $y1 = $value->value->y1 * 2;
                        $wWidth = $value->value->w * 2;
                        $wHeight = $value->value->h * 2;
                        //$this->resizeimage($filename, $wWidth, $wHeight);
                        list($width, $height, $type, $attr) = getimagesize( $filename );
                        $src = imagecreatefrompng($filename);
                        $dst = imagecreatetruecolor( $wWidth, $wHeight );
                        imagealphablending($dst, false);
                        imagesavealpha($dst,true);
                        $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
                        imagefilledrectangle($dst, 0, 0, $wWidth, $wHeight, $transparent);
                        imagecopyresampled($dst, $src, 0, 0, 0, 0, $wWidth, $wHeight, $width, $height);
                        imagepng($dst, $filename, 0);
                        imagedestroy( $dst );
                        /*End resize image*/
                        /*apply water*/
                        $watermark = imagecreatefrompng($filename);
                        imagealphablending($watermark, false);
                        imagesavealpha($watermark, true);
                        $img = imagecreatefromjpeg($file_name);
                        $wtrmrk_w = imagesx($watermark);
                        $wtrmrk_h = imagesy($watermark);
                        imagecopy($img, $watermark, $x1, $y1, 0, 0, $wtrmrk_w, $wtrmrk_h);
                        imagejpeg($img, $file_name, 100);
                        imagedestroy($img);
                        imagedestroy($watermark);
                        @unlink($filename);
                        /*End apply water*/
                    }
                }
            }
            if($file_name) {
                /*upload to imgur.com*/
                $images = $this->Mod_general->uploadtoImgur($file_name);
                $image = @$images;
                if(!$images) {
                    $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                    $image = $this->Mod_general->uploadToImgbb($file_name, $apiKey);
                } else {
                    $image = @$images; 
                }
                if(!empty($image)) {
                    @unlink($file_name);
                }
                $data = array("upload" => $image,"error"=>false); 
            } else {
                $data = array("error" => false); 
            }       
            echo json_encode($data);
        }
    }
    function resizeimage($file, $w, $h, $crop=FALSE) {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }
        $src = imagecreatefromstring( file_get_contents( $file ) );
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        return $dst;
    }
    public function test()
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

        $this->load->view ( 'managecampaigns/test', $data );

        require_once(APPPATH.'controllers/Splogr.php');
        $aObj = new Splogr(); 
        $enContent = $aObj->fromAlibaba('https://www.alibaba.com/product-detail/Portable-mini-laser-cutter-tempered-glass_60799139180.html');
        echo $enContent;
        die;
    }

    function insertAd($content, $ad, $pos = 0){
      // $pos = 0 means randomly position in the content
        $closing_p = '</p>';
        $paragraphs = explode( $closing_p, $content );
        $count = count($paragraphs);
        $midpoint = floor($count / 1.5);

        if($count == 0  or $count <= $pos){
            return $content;
        }
        else{
            for ($i=0; $i < $midpoint; $i++) { 
                if( $i%2 == 1 ) {
                    if($pos == 0){
                      $pos = rand (1, $count - 1);
                    } else {
                        $pos = $i;
                    }
                    $content = preg_replace('/<p>/', '<helper>', $content, $pos + 1);
                    $content = preg_replace('/<helper>/', '<p>', $content, $pos);
                    $content = str_replace('<helper>', $ad . "\n<p>", $content);
                }

            }
            // if($pos == 0){
            //   $pos = rand (1, $count - 1);
            // }
            // $content = preg_replace('/<p>/', '<helper>', $content, $pos + 1);
            // $content = preg_replace('/<helper>/', '<p>', $content, $pos);
            // $content = str_replace('<helper>', $ad . "\n<p>", $content);
            $content = preg_replace('/<\/iframe>(.*?)/', '$0'.$ad, $content);
            $content = preg_replace('/<iframe(.*)width="([^"]*)"(.*)>/','<iframe$1width="100%"$3>',$content);
            $content = preg_replace('/<iframe(.*)height="([^"]*)"(.*)>/','<iframe$1height="250"$3>',$content);
            return $content;
        }
    }

    function ad_between_paragraphs($content){
        /**-----------------------------------------------------------------------------
         *
         *  @author       Pieter Goosen <http://stackoverflow.com/users/1908141/pieter-goosen>
         *  @return       Ads in between $content
         *  @link         http://stackoverflow.com/q/25888630/1908141
         * 
         *  Special thanks to the following answers on my questions that helped me to
         *  to achieve this
         *     - http://stackoverflow.com/a/26032282/1908141
         *     - http://stackoverflow.com/a/25988355/1908141
         *     - http://stackoverflow.com/a/26010955/1908141
         *     - http://wordpress.stackexchange.com/a/162787/31545
         *
        *------------------------------------------------------------------------------*/ 


            /**-----------------------------------------------------------------------------
             *
             *  wptexturize is applied to the $content. This inserts p tags that will help to  
             *  split the text into paragraphs. The text is split into paragraphs after each
             *  closing p tag. Remember, each double break constitutes a paragraph.
             *  
             *  @todo If you really need to delete the attachments in paragraph one, you want
             *        to do it here before you start your foreach loop
             *
            *------------------------------------------------------------------------------*/ 
            $closing_p = '</p>';
            $paragraphs = explode( $closing_p, $content );


            /**-----------------------------------------------------------------------------
             *
             *  The amount of paragraphs is counted to determine add frequency. If there are
             *  less than four paragraphs, only one ad will be placed. If the paragraph count
             *  is more than 4, the text is split into two sections, $first and $second according
             *  to the midpoint of the text. $totals will either contain the full text (if 
             *  paragraph count is less than 4) or an array of the two separate sections of
             *  text
             *
             *  @todo Set paragraph count to suite your needs
             *
            *------------------------------------------------------------------------------*/ 
            $count = count( $paragraphs );
            echo $count%3;
            if( 4 >= $count ) {
                $totals = array( $paragraphs ); 
            }else{
                $midpoint = floor($count / 2);
                $first = array_slice($paragraphs, 0, $midpoint );
                if( $count%2 == 1 ) {
                    $second = array_slice( $paragraphs, $midpoint, $midpoint, true );
                }else{
                    $second = array_slice( $paragraphs, $midpoint, $midpoint-1, true );
                }
                $totals = array( $first, $second );
            }

            $new_paras = array();   
            //var_dump($totals);
            die;
            foreach ( $totals as $key_total=>$total ) {
                /**-----------------------------------------------------------------------------
                 *
                 *  This is where all the important stuff happens
                 *  The first thing that is done is a work count on every paragraph
                 *  Each paragraph is is also checked if the following tags, a, li and ul exists
                 *  If any of the above tags are found or the text count is less than 10, 0 is 
                 *  returned for this paragraph. ($p will hold these values for later checking)
                 *  If none of the above conditions are true, 1 will be returned. 1 will represent
                 *  paragraphs that qualify for add insertion, and these will determine where an ad 
                 *  will go
                 *  returned for this paragraph. ($p will hold these values for later checking)
                 *
                 *  @todo You can delete or add rules here to your liking
                 *
                *------------------------------------------------------------------------------*/ 
                $p = array();
                foreach ( $total as $key_paras=>$paragraph ) {
                    $word_count = count(explode(' ', $paragraph));
                    if( preg_match( '~<(?:img|ul|li)[ >]~', $paragraph ) || $word_count < 10 ) {  
                        $p[$key_paras] = 0; 
                    }else{
                        $p[$key_paras] = 1; 
                    }   
                }

                /**-----------------------------------------------------------------------------
                 *
                 *  Return a position where an add will be inserted
                 *  This code checks if there are two adjacent 1's, and then return the second key
                 *  The ad will be inserted between these keys
                 *  If there are no two adjacent 1's, "no_ad" is returned into array $m
                 *  This means that no ad will be inserted in that section
                 *
                *------------------------------------------------------------------------------*/ 
                $m = array();
                foreach ( $p as $key=>$value ) {
                    if( 1 === $value && array_key_exists( $key-1, $p ) && $p[$key] === $p[$key-1] && !$m){
                        $m[] = $key;
                    }elseif( !array_key_exists( $key+1, $p ) && !$m ) {
                        $m[] = 'no-ad';
                    }
                } 
                /**-----------------------------------------------------------------------------
                 *
                 *  Use two different ads, one for each section
                 *  Only ad1 is displayed if there is less than 4 paragraphs
                 *
                 *  @todo Replace "PLACE YOUR ADD NO 1 HERE" with your add or code. Leave p tags
                 *  @todo I will try to insert widgets here to make it dynamic
                 *
                *------------------------------------------------------------------------------*/ 
                if( $key_total == 0 ){
                    $ad = array( 'ad1' => '<div class="setAdsSection"></div>');
                }else{
                    $ad = array( 'ad2' => '<div class="setAdsSection"></div>');
                }

                /**-----------------------------------------------------------------------------
                 *
                 *  This code loops through all the paragraphs and checks each key against $mail
                 *  and $key_para
                 *  Each paragraph is returned to an array called $new_paras. $new_paras will
                 *  hold the new content that will be passed to $content.
                 *  If a key matches the value of $m (which holds the array key of the position
                 *  where an ad should be inserted) an add is inserted. If $m holds a value of
                 *  'no_ad', no ad will be inserted
                 *
                *------------------------------------------------------------------------------*/ 
                foreach ( $total as $key_para=>$para ) {
                    if( !in_array( 'no_ad', $m ) && $key_para == $m[0] ){
                        $new_paras[key($ad)] = $ad[key($ad)];
                        $new_paras[$key_para] = $para;
                    }else{
                        $new_paras[$key_para] = $para;
                    }
                }
            }
            /**-----------------------------------------------------------------------------
             *
             *  $content should be a string, not an array. $new_paras is an array, which will
             *  not work. $new_paras are converted to a string with implode, and then passed
             *  to $content which will be our new content
             *
            *------------------------------------------------------------------------------*/ 
            $content =  implode( ' ', $new_paras );
        return $content;
    }
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
