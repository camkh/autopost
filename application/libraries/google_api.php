<?php
if (!defined('BASEPATH'))

    exit('No direct script access allowed');

class Google_api {
	public function __construct()
    {
    	require_once('Google/autoload.php');
    }
}