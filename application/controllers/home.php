<?php



if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Home extends CI_Controller
{
    protected $mod_general;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_general');
        $this->load->theme('layout');
        $this->mod_general = new Mod_general();
        if(!$this->session->userdata('user_id')) {
            $ci = get_instance();
            $account_url = $ci->config->item('account_url');
            redirect($account_url . '?continue=' . urlencode(base_url()));
        }
        $this->lang->load('en', 'en');
        $this->load->library('Breadcrumbs');
    }

    public function index()
    {
        /*get licence*/
        $licence = $this->session->userdata('licence');
        $user_id = $this->session->userdata('user_id');
        $dataLicence = $this->mod_general->select('licence','*',array('user_id'=>$user_id,'l_status'=>1));
        $endDateStr = @$dataLicence[0]->l_end_date;
        $yourLicence = date('d-m-Y', $endDateStr);
        $yourLicenceStr = strtotime($yourLicence);
        $this->session->set_userdata('licence', $yourLicenceStr);
        /*end get licence*/
        $this->load->theme('layout');
        $data['title'] = 'Facebook Auto Poster 1.0';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        $this->breadcrumbs->add('index', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output(); 
        $data['licence'] = $dataLicence;
        $data['addJsScript'] = array(
            "$(document).ready(function(){
                   var loading = false;
            });
            "
        );
        $this->load->view('home', $data);

    }
    
    public function managecampaigns()
    {
        $this->load->theme('layout');
        $data['title'] = 'Admin Area :: Manage Campaigns';
        $this->load->view('managecampaigns/list', $data);

    }

    public function logout()
    {
        $this->session->sess_destroy();
        $ci = get_instance();
        $account_url = $ci->config->item('account_url');
        redirect($account_url . '/logout?continue=' . urlencode(base_url()));
    }


}


/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */
