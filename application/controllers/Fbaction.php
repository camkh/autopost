<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Fbaction extends CI_Controller
{
    protected $mod_general;
    const Day = '86400';
    const Week = '604800';
    const Month = '2592000';
    const Year = '31536000';
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $data['title'] = 'Autopost';
        $this->load->view('layout/facebook/postaction', $data);
    }
}

/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */
