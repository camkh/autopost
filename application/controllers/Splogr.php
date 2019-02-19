<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Splogr extends CI_Controller
{
    protected $mod_general;
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $data['title'] = 'Autopost';
        $this->load->view('layout/splogr/index', $data);
    }

    public function getpost()
    {
        # code...
    }
}

/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */
