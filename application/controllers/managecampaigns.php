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
            header('Location: ' . $authUrl);
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
    		$per_page = (! empty ( $_GET ['result'] )) ? $_GET ['result'] : 10;
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
        if(empty($this->session->userdata ( 'sid' ))) {
            redirect(base_url() . 'managecampaigns');
            exit();
        }

        if(!empty($this->session->userdata('access_token'))) {
            $this->load->library('google_api');
            $client = new Google_Client();                  
            $client->setAccessToken($this->session->userdata('access_token'));
            if($client->isAccessTokenExpired()) {
                redirect(base_url() . 'managecampaigns/account?renew=1');
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
                    'prefix_title' => @$SuffixTitle,
                    'suffix_title' => @$SuffixTitle,
                    'short_link' => @$short_link,
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
                    $txt = preg_replace('/\r\n|\r/', "\n", $conents[$i]); 
                    $content = array (
                            'name' => @htmlentities(htmlspecialchars(str_replace(' - YouTube', '', $title[$i]))),
                            'message' => @htmlentities(htmlspecialchars(addslashes($txt))),
                            'caption' => @$caption[$i],
                            'link' => @$link[$i],
                            'picture' => @$thumb[$i],                            
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
                    redirect(base_url() . 'managecampaigns/yturl?pid='.$p_id.'&bid=' . $bid . '&action=postblog&blink='.$blogLink); 
                }                              
            }
        }
        /* end form */

        /*Post to blogger*/
        if(!empty($this->input->get('action'))) {
            if($this->input->get('action') == 'postblog' && !empty($this->input->get('bid'))) {
                $bid = $this->input->get('bid');
                $pid = $this->input->get('pid');
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
                    $links = $pConent->link;
                    $title = nl2br(html_entity_decode(htmlspecialchars_decode($pConent->name)));
                    $message = nl2br(html_entity_decode(htmlspecialchars_decode($pConent->message)));                    
                    $picture = $pConent->picture;

                    /*Post to Blogger first*/
                    $vid = $this->Mod_general->get_video_id($links);
                    $vid = $vid['vid'];

                    /*upload photo first*/
                    $imgur = false;        
                    if(!empty($vid)) {
                        $imgUrl = $picture;
                        
                        $structure = FCPATH . 'uploads/image/';
                        if (!file_exists($structure)) {
                            mkdir($structure, 0777, true);
                        }

                        /*check url status*/
                        $handle = curl_init($imgUrl);                        
                        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

                        /* Get the HTML or whatever is linked in $url. */
                        $response = curl_exec($handle);

                        /* Check for 404 (file not found). */
                        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
                        if($httpCode == 404) {
                            $imgUrl = 'https://i.ytimg.com/vi/'.$vid.'/hqdefault.jpg';
                        }
                        curl_close($handle);
                        /*check url status*/
                        $file_title = basename($imgUrl);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        copy($imgUrl, $fileName);                        
                        $image = $this->mod_general->uploadMedia($fileName);
                        @unlink($fileName);
                        $imgur = true;


                        /*End upload photo first*/
                        $blink = $this->input->get('blink');
                        $blogData = $this->postToBlogger($bid, $vid, $title,$image,$message,$blink);
                        $link = $blogData->url;

                        /*End Post to Blogger first*/

                        /*blog link*/
                        
                        if(!empty($blink) && $blink == 1) {
                            /*show blog link*/
                            $where_link = array(
                                'c_name'      => 'blog_linkA',
                                'c_key'     => $log_id,
                            );
                            $query_blog_link = $this->Mod_general->select('au_config', '*', $where_link);
                            if (!empty($query_blog_link[0])) {
                                $data = json_decode($query_blog_link[0]->c_value);
                                $big = array();
                                foreach ($data as $key => $blog) {
                                    $big[] = $blog->bid;                                
                                }
                                $brand = mt_rand(0, count($big) - 1);
                                $blogRand = $big[$brand];
                                // if($blink == 2) {
                                //     $blogRand = $bid;
                                // }
                                
                                $bodytext = '<meta content="'.$image.'" property="og:image"/><div style="text-align: center;"><a href="'.$link.'" rel="nofollow"><span style="color: red;"><span style="font-size: 20px;">👇👇👇กด Link ข้างล่างได้เลย👇👇👇</span></span><div style="font-size: 25px;">'.$getPost[0]->p_name.'</div><img class="thumbnail noi" style="text-align:center" src="'.$image.'"/></a></div><!--more--><a id="myCheck" href="'.$link.'"></a><script>//window.opener=null;window.setTimeout(function(){if(typeof setblog!="undefined"){var link=document.getElementById("myCheck").href;var hostname="https://"+window.location.hostname;links=link.split(".com")[1];link0=link.split(".com")[0]+".com";document.getElementById("myCheck").href=hostname.links;document.getElementById("myCheck").click();};if(typeof setblog=="undefined"){document.getElementById("myCheck").click();}},2000);</script>';
                                $title = (string) $title;
                                $dataContent          = new stdClass();
                                $dataContent->setdate = false;        
                                $dataContent->editpost = false;
                                $dataContent->pid      = 0;
                                $dataContent->customcode = '';
                                $dataContent->bid     = $blogRand;
                                $dataContent->title    = $bid . $title;        
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
                                'link' => @$link,
                                'picture' => @$image,                            
                            );
                            $dataPostInstert = array (
                                Tbl_posts::conent => json_encode ( $content ),
                                'p_post_to' => 0,
                            );
                            $this->Mod_general->update( Tbl_posts::tblName,$dataPostInstert, $whereUp);
                        }
                        /*End update post*/
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
                        echo '<center>Please wait...</center>';
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$p_id.'&bid='.$bid.'&action=postblog&blink='.$blink.'";}, 15 );</script>'; 
                    } else {
                        redirect(base_url() . 'managecampaigns?m=post_success');
                    }
                    /*End check next post*/
                }                
            }
        }
        /*End Post to blogger*/
        $this->load->view ( 'managecampaigns/yturl', $data );
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
                $bodytext = '<link href="'.$image.'" rel="image_src"/><meta content="'.$image.'" property="og:image"/><img class="thumbnail noi" style="text-align:center" src="'.$image.'"/><!--more--><div id="ishow"></div><div><b>'.$title.'</b></div><div class="wrapper"><div class="small"><p>'.$conent.'</p></div> <a class="readmore" href="#">... Click to read more</a></div><div id="someAdsA"></div><div id="cshow"></div><div id="someAds"></div>';
                break;
            case 'link':
                $bodytext = '<link href="'.$image.'" rel="image_src"/><meta content="'.$image.'" property="og:image"/><img class="thumbnail noi" style="text-align:center" src="'.$image.'"/><!--more--><div id="ishow"></div><div><b>'.$title.'</b></div><div class="wrapper"><div class="small"><p>'.$conent.'</p></div> <a class="readmore" href="#">... Click to read more</a></div><div id="someAdsA"></div><div>คลิปดูวีดีโอ==>> <a href="https://youtu.be/'.$vid.'" target="_blank"> https://youtu.be/'.$vid.'</a></div><div id="someAds"></div>';
                $label = 'link';
                $customcode = '';
                break;
            default:
                $bodytext = '<img class="thumbnail noi" style="text-align:center" src="'.$image.'"/><!--more--><div><b>'.$title.'</b></div><div class="wrapper"><div class="small"><p>'.$conent.'</p></div> <a href="#" class="readmore">... Click to read more</a></div><div id="someAdsA"></div><iframe width="100%" height="280" src="https://www.youtube.com/embed/'.$vid.'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe><div id="someAds"></div>';
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
                    'prefix_title' => @$SuffixTitle,
                    'suffix_title' => @$SuffixTitle,
                    'short_link' => @$short_link,
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
            $title = @$html->find ( 'meta[property=og:title]', 0 )->content;
            $description = @$html->find ( 'meta[property=og:description]', 0 )->content;
            $title1 = @$html->find ( '.post-title', 0 )->innertext;
            if (!$title) {
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
            $data = array (
                    'picture' => @$thumb,
                    'name' => trim ( $title ),
                    'message' => trim ( $title ),
                    'caption' => trim ( $title ),
                    'description' => trim ( $description ),
                    'link' => $url
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
			}
		}
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
        $data['bloglink'] = false;
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
            $data['randdom_link'] = json_decode($randdom_link[0]->c_value);
        } 
        /*End show random Link*/

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

        /*facebook accounts*/
        $whereFb = array (
                'user_id' => $log_id,
                'u_type' => 'Facebook',
            );
        $data['facebook'] = $this->Mod_general->select('users','*', $whereFb);
        /*End facebook accounts*/

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
                'maxResults' => $max
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
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
