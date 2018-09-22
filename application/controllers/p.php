<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class P extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'Mod_general' );
		$this->load->library ( 'dbtable' );
		$this->load->theme ( 'layout' );
		$this->mod_general = new Mod_general ();
	}
	public function index() {
		$this->load->theme ( 'layout' );
		$get_id = $this->uri->segment ( 3 );
		$PostID = $get_id;
		$data = array ();
		if (! empty ( $PostID )) {
			$GetPost = $this->mod_general->select ( 'post', '*', array (
					'p_id' => $PostID,
					'p_type' => 'page' 
			) );
			if (! empty ( $GetPost )) {
				$data ['data_post'] = $GetPost [0];
				$data ['title'] = $GetPost [0]->p_name;
				$data ['page_content'] = $GetPost [0]->p_conent;
				$data ['site_desc'] = $GetPost [0]->p_name;
				$data ['site_keyword'] = $GetPost [0]->p_name;
				$data ['site_url'] = current_url ();
			}
		} else {
			$data ['data_post'] = array ();
			$data ['title'] = 'No data...';
		}
		$this->load->view ( 'p/index', $data );
	}
}