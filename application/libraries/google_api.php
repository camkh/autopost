<?php
if (!defined('BASEPATH'))

    exit('No direct script access allowed');

class Google_api {
	public function __construct()
    {
    }
    public function google_api() {
        require_once('Google/autoload.php');

    }
}