<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Login extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->library ( array (
				'session',
				'lib_login' 
		) );
	}
	
	/**
	 * facebook login
	 *
	 * @return void
	 * @author appleboy
	 *        
	 */
	public function facebook() {
		$fb_data = $this->lib_login->facebook ();
		
		// check login data
		if (isset ( $fb_data ['me'] )) {
			echo '<a href="' . $fb_data ['logOutUrl'] . '">Logout</a>';
			var_dump ( $fb_data );
		} else {
			echo '<a href="' . $fb_data ['loginUrl'] . '">Login</a>';
		}
	}
	
	/**
	 * facebook logout all before add
	 *
	 * @return void
	 * @author appleboy
	 *        
	 */
	public function loutfb() {
		// $fb_data = $this->lib_login->facebook();
		// $ci = get_instance();
		// // check login data
		// if (!empty($fb_data['me'])) {
		// $logoutUrl = $fb_data['logOutUrl'];
		// redirect($logoutUrl);
		// } else {
		// redirect(base_url().'login/facebook');
		// echo 11111;
		// }
		$this->load->library ( 'facebook' );
		if ($this->facebook->logged_in ()) {
			echo '<a href="' . $this->facebook->logout_url () . '">Logout</a>';
			$fbUserID = $this->facebook->user_id () ['data'] ['user_id'];
			$queries = array (
					array (
							'method' => 'GET',
							'relative_url' => '/' . $fbUserID 
					),
					array (
							'method' => 'GET',
							'relative_url' => '/' . $fbUserID . '/groups?limit=5000' 
					),
					array (
							'method' => 'GET',
							'relative_url' => '/' . $fbUserID . '/likes?limit=5000' 
					) 
			);
			$fbGroups = $this->getUserGroup($queries);
			var_dump($fbGroups);
			// $this->facebook->destroy_session();
		} else {
			echo '<a href="' . $this->facebook->login_url () . '">Login</a>';
		}
	}
	
	/**
	 * load the user latest activity
	 * - timeline : all the stream
	 * - me : the user activity only
	 */
	public function getUserGroup($queries) {
		$this->load->library ( 'facebook' );
		$fbUserID = $this->facebook->user_id () ['data'] ['user_id'];
		// POST your queries to the batch endpoint on the graph.
		try {
			$batchResponse = $this->facebook->api( '?batch=' . json_encode ( $queries ), 'POST' );
		} catch ( Exception $o ) {
			throw new Exception ( "User activity stream request failed! {$fbUserID} returned an error: $e" );
		}
		return $batchResponse;
	}
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */
