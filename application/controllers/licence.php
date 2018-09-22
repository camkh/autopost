<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Licence extends CI_Controller {
	protected $mod_general;
	protected $userId;
	const Day = '86400';
	const Week = '604800';
	const Month = '2592000';
	const Year = '31536000';
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'Mod_general' );
		$this->load->library ( 'dbtable' );
		$this->load->theme ( 'layout' );
		$this->mod_general = new Mod_general ();
		TIME_ZONE;
		$this->userId = $this->session->userdata ( 'user_id' );
		$this->load->library('Breadcrumbs');
	}
	public function index() {
		$this->mod_general->checkUser ();
		$log_id = $this->session->userdata ( 'user_id' );
		$user = $this->session->userdata ( 'email' );
		$provider_uid = $this->session->userdata ( 'provider_uid' );
		$provider = $this->session->userdata ( 'provider' );
		$this->load->theme ( 'layout' );
		$data ['title'] = 'Licence';
		$this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
 		if($this->uri->segment(1)) {
        	$this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
     	}
        $this->breadcrumbs->add('list', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();   
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

		$userType = $this->session->userdata ( 'user_type' );
		if($userType == 1) {
			$licenceTable = 'licence';
			$this->load->library ( 'pagination' );
			$per_page = (! empty ( $_GET ['result'] )) ? $_GET ['result'] : 10;
			$config ['base_url'] = base_url () . 'licence/index';
			$count_blog = $this->mod_general->select ( $licenceTable, '*' );
			$config ['total_rows'] = count ( $count_blog );
			$config ['per_page'] = $per_page;
			$config = $this->mod_general->paginations($config);
			$page = ($this->uri->segment ( 3 )) ? $this->uri->segment ( 3 ) : 0;
			
			$query_blog = $this->Mod_general->select($licenceTable, '*', array(), "l_id DESC", '', $config['per_page'], $page);	
			$config ["uri_segment"] = 3;
			$this->pagination->initialize ( $config );
			$data ["total_rows"] = count ( $count_blog );
			$data ["results"] = $query_blog;
			/* end get pagination */
			$this->load->view ( 'licence/index', $data );
		} else {
			$licenceTable = 'licence';
			$this->load->database('default', true);
	        $this->db->select('*');
	        $this->db->from($licenceTable);
			$this->db->where('l_status !=', 0);
			$this->db->where('user_id =', $this->userId);
			$query = $this->db->get();
	    	$result = $query->result();
	    	$data ["results"] = $result;
	    	$this->load->view ( 'licence/account', $data );
		}
	}
	public function add() {
		$userId = $this->session->userdata ( 'user_id' );
		$this->mod_general->checkUser ();
		$actions = $this->uri->segment ( 3 );
		$id = ! empty ( $_GET ['id'] ) ? $_GET ['id'] : '';
		$user = $this->session->userdata ( 'email' );
		$this->load->theme ( 'layout' );
		$data ['title'] = 'Add a new licence';
		$this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
 		if($this->uri->segment(1)) {
        	$this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
     	}
        $this->breadcrumbs->add('add', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();   		
		/* get post for each user */
		$where_so = array (
				'user_id' => $userId,
				'l_id' => $id 
		);
		$licenceTable = 'licence';
		$dataPost = $this->mod_general->select ( $licenceTable, '*', $where_so );
		$data ['data'] = $dataPost;
		/* end get post for each user */
		

		
		$ajax = base_url () . 'managecampaigns/ajax?gid=';
		$data ['js'] = array (
				'themes/layout/blueone/plugins/validation/jquery.validate.min.js',
				'themes/layout/blueone/plugins/pickadate/picker.js',
				'themes/layout/blueone/plugins/pickadate/picker.time.js' 
		);
		$data ['addJsScript'] = array (
				"
        $(document).ready(function() {
     
        $('#moneyper').change(function () {
            if($(this).val()){
            	var total = $(this).val() * 7;
                $('#getprice').html(total);
            } 
        });

		$('.changeBy').change(function () {
            if($(this).val()){
            	var perriod = $(this).find(':selected').data('id');
            	$('#getnumber').val(perriod);
            } 
        });

		$('.stransferBy').change(function () {
			if($(this).val()){
				$('.stransferOn').val($(this).val());
			}
			$('#onPaypal').slideUp();
            $('#khmer-pay').slideDown();
         });

        $('.byPaypal').click(function () {
        	$('#khmer-pay').slideUp();
            $('#onPaypal').slideDown();
         }); 
 

         $.validator.addClassRules('required', {
            required: true
         });
         $('#validate').validate({
         	rules: {
		        moneyid: {
		            required: true,
		            minlength: 6,
		        }
		    },
		    messages: {
		    	moneyid:'សូមមេត្តាបញ្ចូលលេខកូដលុយឲ្យបានត្រឹមត្រូវ, please enter the money Id',
		    }
         });
     });
    " 
		);
		
		/* get form */
		if ($this->input->post ( 'submit' )) {
			$videotype = '';
			$moneyByMonth = $this->input->post ( 'money' );
			switch ($moneyByMonth) {
				case 1:
					$num = 7;
					$endDate = $num * self::Day;
					$nameOf = 'd';
					$price = 3;
					break;
				case 2:
					$num = 14;
					$endDate = $num * self::Day;
					$nameOf = 'd';
					$price = 5;
					break;
				case 3:
					$num = 1;
					$endDate = $num * self::Month;
					$nameOf = 'm';
					$price = 7;
					break;
				case 4:
					$num = 3;
					$endDate = $num * self::Month;
					$nameOf = 'm';
					$price = 18;
					break;
				case 5:
					$num = 6;
					$endDate = $num * self::Month;
					$nameOf = 'm';
					$price = 30;
					break;
				case 6:
					$num = 1;
					$endDate = $num * self::Year;
					$nameOf = 'y';
					$price = 50;
					break;
			}
			$startDate = time();
			$endOnDate = $startDate + $endDate;
			$moneyId = $this->input->post ( 'moneyid' );
			$name = $this->input->post ( 'name' );			
			$phone = $this->input->post ( 'phone' );
			$postId = $this->input->post ( 'postid' );

			$this->load->library ( 'form_validation' );
			$this->form_validation->set_rules ( 'money', 'money', 'required' );
			$this->form_validation->set_rules ( 'moneyid', 'moneyid', 'required' );
			$this->form_validation->set_rules ( 'stransferBy', 'stransferBy', 'required' );
			if ($this->form_validation->run () == true) {
				/* add data to post */
				$licenceTable = 'licence';
				$method = (string) $this->input->post ( 'stransferBy' );
				$details = @json_decode(file_get_contents("http://ipinfo.io/json"));
				$dataLicence = array(
					'l_name' => $name,
					'l_price' => $price,
					'l_tel' => $phone,
					'l_transfer_by' => $method,
					'l_money_id' => $moneyId,
	                'l_start_date' => $startDate,
	                'l_end_date' => $endOnDate,
	                'l_status'=>2,
	                'user_id'=>$userId,
	                'l_type'=>'paid',
	                'l_loc'=>@$details->loc,
	            );
				if (! empty ( $postId )) {
					$AddToPost = $postId;
					$this->mod_general->update ( $licenceTable, $dataPostInstert, array (
							'l_id' => $postId 
					) );
				} else {
					$AddToPost = $this->mod_general->insert ( $licenceTable, $dataLicence );
					if($AddToPost) {
						$dataMial =  new stdClass ();
						$dataMial->phone = @$phone;
						$dataMial->price = @$price;
						$dataMial->name = @$name;
						$dataMial->method = @$method;
						$dataMial->moneyId = @$moneyId;
						$dataMial->startDate = @$startDate;
						$dataMial->endOnDate = @$endOnDate;
						$this->sendMail($dataMial);
					}
				}
				/* end add data to post */
			}
			redirect ( base_url () . 'licence' );
		}
		/* end form */

		$ci = get_instance();
        $account_url = $ci->config->item('account_url');
        $json = file_get_contents($account_url.'/account/' . $userId);
        $obj = json_decode($json);

        $data['user'] = $obj;
		$this->load->view ( 'licence/add', $data );
	}


	/*payment success*/
	public function success()
	{
		$payerStatus = $this->input->post ('payer_status');
		if($payerStatus == 'verified') {
			$userID = $this->input->post ('custom');
			$quantity = $this->input->post ('quantity');
			$payer_id = $this->input->post ('payer_id');
			$moneyByMonth = $this->input->post ( 'item_number' );
			switch ($moneyByMonth) {
				case 1:
					$num = 7;
					$endDate = ($num * self::Day) * $quantity;
					$nameOf = 'd';
					$price = 3 * $quantity;
					break;
				case 2:
					$num = 14;
					$endDate = ($num * self::Day) * $quantity;
					$nameOf = 'd';
					$price = 5 * $quantity;
					break;
				case 3:
					$num = 1;
					$endDate = ($num * self::Day) * $quantity;
					$nameOf = 'm';
					$price = 7 * $quantity;
					break;
				case 4:
					$num = 3;
					$endDate = ($num * self::Day) * $quantity;
					$nameOf = 'm';
					$price = 18;
					break;
				case 5:
					$num = 6;
					$endDate = ($num * self::Day) * $quantity;
					$nameOf = 'm';
					$price = 30 * $quantity;
					break;
				case 6:
					$num = 1;
					$endDate = ($num * self::Day) * $quantity;
					$nameOf = 'y';
					$price = 50 * $quantity;
					break;
			}
			$startDate = time();
			$endOnDate = $startDate + $endDate;
			$licenceTable = 'licence';
			$method = 'paypal';
			$dataLicence = array(
					'l_name' => $name,
					'l_price' => $price,
					'l_transfer_by' => $method,
					'l_money_id' => $payer_id,
	                'l_start_date' => $startDate,
	                'l_end_date' => $endOnDate,
	                'l_status'=>1,
	                'user_id'=>$userID,
	                'l_type'=>'paid',
	            );
			$AddToPost = $this->mod_general->insert ( $licenceTable, $dataLicence );
			if($AddToPost) {
				$dataMial =  new stdClass ();
				$dataMial->price = @$price;
				$dataMial->method = @$method;
				$dataMial->moneyId = @$payer_id;
				$dataMial->startDate = @$startDate;
				$dataMial->endOnDate = @$endOnDate;
				$this->sendMail($dataMial);
			}
			redirect ( base_url ());
		}  else {
			redirect ( base_url () . 'licence/add?error=paypal' );
		}
	}

	/*approve licence*/
	public function approve()
	{
		$id = $_GET['id'];
		$licenceTable = 'licence';
		$dataLicence = $this->mod_general->select ( $licenceTable, '*', array('l_id' => $id));
		if(!empty($dataLicence[0])) {
			$this->mod_general->update ( $licenceTable, array('l_status'=>0), array('user_id' => $dataLicence[0]->user_id));
			$this->mod_general->update ( $licenceTable, array('l_status'=>1), array('l_id' => $dataLicence[0]->l_id));
			redirect ( base_url () . 'licence' );
		}
	}

	public function delete()
	{
		$userType = $this->session->userdata('user_type');
		$id = $_GET['id'];
		if(!empty($id)) {
			if($userType == 1) {
				$where = array('l_id'=>$id);
			} else {
				$where = array('l_id'=>$id,'user_id'=>$this->userId);
			}
			$this->Mod_general->delete('licence', $where);
			redirect ( base_url () . 'licence' );
		}
	}

	/*send email*/
	public function sendMail($dataMial)
	{
		$this->load->library('email');
		$config['charset'] = 'utf-8';
        $config['mailtype'] = 'html';
        $config['wordwrap'] = TRUE;
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
		$this->email->from('sroulnas@gmail.com', 'Licence');
		$this->email->to('camkh13@gmail.com'); 
		$this->email->cc('socheatnews@hotmail.com'); 
		$this->email->subject('Licence: ' . $dataMial->name);
		$this->email->message('from: <b>' . 
		$dataMial->name . '</b><br/>phone: <b>' . 
		$dataMial->phone . '</b><br/>method: <b>' . 
		$dataMial->method . '</b><br/>money Id: <b>' . 
		$dataMial->moneyId . '</b><br/>start Date: <b>' . 
		date('d-m-Y', $dataMial->startDate) . '</b><br/>end On Date: <b>' . 
		date('d-m-Y', $dataMial->endOnDate) . '</b><br/>price: <b>' . 
		$dataMial->price . '$</b>');	
		$this->email->send();
	}
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
