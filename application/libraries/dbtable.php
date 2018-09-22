<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dbtable {
    public function __construct()
    {
        require_once('tables/tbl_cat_term.php');
        require_once('tables/tbl_cat_term_relationships.php');
        require_once('tables/tbl_cat_term_taxonomy.php');
        require_once('tables/tbl_social_network.php');
        require_once('tables/tbl_user.php');
        require_once('tables/tbl_social_network_group.php');
        require_once('tables/tbl_posts.php');
        require_once('tables/tbl_share.php');
        require_once('tables/tbl_share_progress.php');
        require_once('tables/share_history.php');
        require_once('tables/networkblogs.php');
    }

    public function dbtable() {
        
    }

}