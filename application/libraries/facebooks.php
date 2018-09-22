<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Facebooks {
	public function __construct()
    {
    }
    public function facebooks() {
        require_once ('facebook/facebook.php');
    }

}
