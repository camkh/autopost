<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->model('Mod_general');

        $this->load->theme('layout');

    }

    public function index() {
        $this->load->theme('layout');

        $data['title'] = 'List of user';

        $data['user_list'] = $this->Mod_general->getuser('*');

        if ($this->session->flashdata('edituser')) {

            $data['addJsScript'] = array(

                'var success = generate(\'success\');',

                'setTimeout(function () {

            $.noty.setText(success.options.id, \'Modified user has success!\');

        }, 1000);',

                'setTimeout(function () {

                        $.noty.closeAll();

                    }, 4000);');

        }

        $data['addJsScript'] = array('$(\'.del-user\').click(function() {

                    Confirms(\'error\', \'top\');

                });');

        $this->load->view('user/index', $data);

    }

    public function getlogin() {

        $this->load->theme('layout');

        $data['title'] = 'User logins';

        $data['bodyClass'] = 'login';

        /* form */
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'required');

        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == false) {

        } else {

            if ($this->input->post('username')) {

                $user = $this->input->post('username');

                $password = $this->input->post('password');

                $field = array(

                    'username',

                    'log_id',

                    'user_type');

                $where = array('username' => $user, 'password' => md5($password));

                $query = $this->Mod_general->getuser($field, $where);

                if (count($query) > 0) {

                    foreach ($query as $row) {

                        $this->session->set_userdata('username', $row->username);

                        $this->session->set_userdata('user_type', $row->user_type);

                        $this->session->set_userdata('log_id', $row->log_id);

                        redirect(base_url() . 'home/index');

                    }

                }

            }

        }

        $user = $this->session->userdata('username');

        if ($user) {

            redirect(base_url() . 'home');

        } else {

            $this->load->view('login', $data);

        }

    }

    public function permission() {

        $data['title'] = 'User permission';

        $this->Mod_general->checkUser();

        $data_menu = array(

            Tbl_title::type => 'nav_menu_item',

            Tbl_title::status => 1,

            );

        $data['userpage'] = $this->Mod_general->select(Tbl_title::tblname, '*', $data_menu);

        $data['user_list'] = $this->Mod_general->getuser('*');

        $userid = $this->input->post('userid');

        $pageid = $this->input->post('pageid');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('pageid', 'pageid', 'required');

        $this->form_validation->set_rules('userid', 'userid', 'required');

        if ($this->form_validation->run() == true) {

            foreach ($pageid as $value) {

                $data_sub = array(

                    'per_user_id' => $userid,

                    'per_value' => $value,

                    'per_type' => 'page_permission',

                    'per_status' => '1',

                    );

                $dataLogin = $this->Mod_general->insert('permission', $data_sub);

            }

        }

        $this->load->view('user/permission', $data);

    }

    public function perpage() {

        $data['title'] = 'User permission';

        $this->Mod_general->checkUser();

    }

    public function permissionpage() {

        $data['title'] = 'User permission';

        $this->Mod_general->checkUser();

        $data['title'] = 'Permission page';

        $data['js'] = array(

            'themes/layout/blueone/plugins/nestable/jquery.nestable.min.js',

            'themes/layout/blueone/assets/js/demo/ui_nestable_list.js',

            );

        $data['addJsScript'] = array(

            '$(\'#addmenu\').click(function() {

                $.ajax

                ({

                    type: \'POST\',

                    url: \'addmoremenu\',

                    data: {id: $(\'#menuID\').val(), name: $(\'#txtlabel\').val(), url: $(\'#menuURL\').val(), class: $(\'#txtclass\').val()},

                    cache: false,

                    success: function(html){

                        $(\'.dd-list\').first().append(html);

                    } 

                });

           });

           $(\'#updatesubmenu\').click(function() {

                $.ajax

                ({

                    type: \'POST\',

                    url: \'updatesubmenu\',

                    data: {updatemenu: $(\'.updatemenu\').val()},

                    cache: false,

                    success: function(html){

                        if(html ==1) {

                            var success = generate(\'success\');',

            'setTimeout(function () {

                        $.noty.setText(success.options.id, \'Has been save!\');

            }, 1000);',

            'setTimeout(function () {

                            $.noty.closeAll();

                        }, 4000);

                        }

                        if(html ==0) {

                            var error = generate(\'error\');',

            'setTimeout(function () {

                        $.noty.setText(error.options.id, \'Can not save!\');

            }, 1000);',

            'setTimeout(function () {

                            $.noty.closeAll();

                        }, 4000);

                        }

                    } 

                });

           });

           $(\'.removelist\').live(\'click\', function() {

                var id = $(this).attr(\'data\');

                var thisid = $(this);

                $.ajax

                ({

                    type: \'POST\',

                    url: \'removelist\',

                    data: {id: id},

                    cache: false,

                    success: function(html){

                        if(html ==true) {

                            $(thisid).parent().fadeOut();

                            var success = generate(\'success\');',

            'setTimeout(function () {

                        $.noty.setText(success.options.id, \'Delete successfully!\');

            }, 1000);',

            'setTimeout(function () {

                            $.noty.closeAll();

                        }, 4000);

                        }

                    } 

                });

           });');

        $data_sel = array('term_id' => 1, );

        $dataLogin = $this->Mod_general->select('cat_term', '*', $data_sel);

        if (!empty($dataLogin)) {

            foreach ($dataLogin as $value) {

                $term_id = $value->term_id;

                $term_name = $value->name;

            }

        } else {

            $term_id = '';

            $term_name = '';

        }

        $data['titlemenu'] = $term_name;

        $data['menuid'] = $term_id;

        $build_tree_edit = $this->Mod_general->build_tree_edit(0, 0);

        $data['getmenu'] = $build_tree_edit;

        /* form */

        if ($this->input->post('nav_menu')) {

            $nav_name = $this->input->post('name');

            $cat_slugs = preg_replace("/[[:space:]]/", "-", $nav_name);

            $cat_slug = strtolower($cat_slugs);

            if (!empty($nav_name)) {

                $data = array(

                    'name' => $nav_name,

                    'slug' => $cat_slug,

                    );

                $dataLogin = $this->Mod_general->insert('cat_term', $data);

                $data_sub = array(

                    'term_id' => $dataLogin,

                    'taxonomy' => $this->input->post('nav_menu'),

                    );

                $dataLogin = $this->Mod_general->insert('cat_term_taxonomy', $data_sub);

            }

        }

        /* end form */

        $this->load->view('user/permissionpage', $data);

    }

    function getChild($value) {

        $mainid = $value->id;

        foreach ($value->children as $value_sub) {

            $data = array('mo_parent' => $mainid);

            $where = array('id' => $value_sub->id);

            $mainsub = $this->Mod_general->update('title', $data, $where);

            if (!empty($value_sub->children)) {

                $this->getChild($value_sub);

            }

        }

        return $mainsub;

    }

    public function addmoremenu() {

        /* add sub */

        $this->load->library('form_validation');

        $menuid = $this->input->post('id');

        $nav_url = $this->input->post('url');

        $nav_class = $this->input->post('class');

        $nav_txtlabel = $this->input->post('name');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('url', 'url', 'required');

        $this->form_validation->set_rules('name', 'name', 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($menuid)) {

                $data_sub = array(

                    Tbl_title::value => $nav_url,

                    Tbl_title::title => $nav_txtlabel,

                    Tbl_title::type => 'nav_menu_item',

                    Tbl_title::status => 1,

                    Tbl_title::object_id => $nav_class,

                    );

                $dataid = $this->Mod_general->insert('title', $data_sub);

                $data_sub = array(

                    'object_id' => $dataid,

                    'term_taxonomy_id' => $menuid,

                    );

                $dataLogin = $this->Mod_general->insert('cat_term_relationships', $data_sub);

                /* show to view */

                $data_sel = array(

                    Tbl_title::type => 'nav_menu_item',

                    Tbl_title::status => 1,

                    Tbl_title::id => $dataid);

                $dataLogin = $this->Mod_general->select('title', '*', $data_sel);

                if (!empty($dataLogin)) {

                    foreach ($dataLogin as $value) {

                        echo "<li class='dd-item' data-id='" . $value->{Tbl_title::id} .
                            "'><div class='dd-handle'>" . $value->{Tbl_title::title} .
                            '<span class="pull-right" style="margin-right:35px;">' . $value->{Tbl_title::
                            value} . "<span></div><a class='btn btn-sm pull-right removelist' data='" . $value->{
                            Tbl_title::id} . "'><i class='icol-cross'></i></a></li>";

                    }

                }

                /* show to view */

            }

        }

        /* end add sub */

    }

    public function updatesubmenu() {

        /* update menu */

        if ($this->input->post('updatemenu')) {

            $datamenu = json_decode($this->input->post('updatemenu'));

            foreach ($datamenu as $key => $value) {

                if (!empty($value->children)) {

                    $mainsub = $this->getChild($value);

                } else {

                    $mainid = $value->id;

                    $data = array('mo_parent' => 0);

                    $where = array('id' => $mainid);

                    $mainsub = $this->Mod_general->update('title', $data, $where);

                }

            }

        } else {

            $mainsub = '';

        }

        /* end update menu */

        if ($mainsub == true) {

            echo '1';

        } else {

            echo '0';

        }

    }

    public function action() {

        $action = $this->uri->segment(3);

        $id = $this->uri->segment(4);

        $this->load->theme('layout');

        $data['title'] = 'Add new user';

        $data['js'] = array('themes/layout/blueone/plugins/validation/jquery.validate.min.js', );

        $data['addJsScript'] = array('$(document).ready(function(){

                $.validator.addClassRules(\'required\', {

                    required: true

                });

                $(\'#validate-4\').validate();

            });');

        /* before submit form */

        if ($action == 'edit' && !empty($id)) {

            $whereEdit = array('log_id' => $id);

            $data['userID'] = $id;

            $data['editAction'] = $action;

            $data['editUser'] = $this->Mod_general->getuser('*', $whereEdit);

        }

        /* end before submit form */

        /* submit form */

        if ($this->input->post('email')) {

            $name = $this->input->post('fname');

            $usertype = $this->input->post('usertype');

            $email = $this->input->post('email');

            $password = $this->input->post('password');

            $GetAction = $this->input->post('action');

            $GetUserID = $this->input->post('id');

            $userstatus = $this->input->post('userstatus');

            /* Add new suer */

            if ($action != 'edit' && empty($id)) {

                $where = array('username' => $email);

                $dataUser = $this->Mod_general->getuser('*', $where);

                if (!empty($dataUser)) {

                    $data['addJsScript'] = array(

                        'var error = generate(\'error\');',

                        'setTimeout(function () {

            $.noty.setText(error.options.id, \'This email is already exist!\');

        }, 1000);',

                        'setTimeout(function () {

                        $.noty.closeAll();

                    }, 4000);');

                } else {

                    $data = array(

                        'username' => $email,

                        'password' => md5($password),

                        'dtime' => strtotime(date('Y-m-d h:i:s')),

                        );

                    $dataLogin = $this->Mod_general->insert('login', $data);

                    $dataUser = array(

                        'log_id' => $dataLogin,

                        'name' => trim($name),

                        'email' => trim($email),

                        'user_type' => trim($usertype));

                    $dataUser = $this->Mod_general->insert('users', $dataUser);

                    //                    $data_sub = array(

                    //                        'per_user_id' => $dataLogin,

                    //                        'per_value' => 'home',

                    //                        'per_type' => 'page_permission',

                    //                        'per_status' => '1',

                    //                    );

                    //                    $dataLogin = $this->Mod_general->insert('permission', $data_sub);

                    if (!empty($dataUser)) {

                        $data['addJsScript'] = array(

                            'var success = generate(\'success\');',

                            'setTimeout(function () {

            $.noty.setText(success.options.id, \'Add user success!\');

        }, 1000);',

                            'setTimeout(function () {

                        $.noty.closeAll();

                    }, 4000);');

                    }

                }

            }

            /* end add user */

            /* Edit user */  else
                if ($GetAction == 'edit' && !empty($GetUserID)) {

                    if (!empty($password)) {

                        $data = array(

                            'username' => $email,

                            'password' => md5($password),

                            'dmodify' => strtotime(date('Y-m-d h:i:s')),

                            );

                    } else {

                        $data = array(

                            'username' => $email,

                            'dmodify' => strtotime(date('Y-m-d h:i:s')),

                            );

                    }

                    $whereEdit = array('id' => $GetUserID);

                    $dataUser = array(

                        'name' => trim($name),

                        'user_status' => trim($userstatus),

                        'email' => trim($email),

                        'user_type' => trim($usertype));

                    $whereEditUser = array('log_id' => $GetUserID);

                    $dataLogin = $this->Mod_general->update('login', $data, $whereEdit);

                    $dataUser = $this->Mod_general->update('users', $dataUser, $whereEditUser);

                    if (!empty($dataUser)) {

                        $this->session->set_flashdata('edituser', 'edituser');

                        redirect(base_url() . 'user');

                    }

                }

            /* end Edit user */

        }

        /* end submit form */

        $this->load->view('user/action', $data);

    }

    public function delete($id = '') {

        if (!empty($id)) {

            $login = array('id' => $id);

            $users = array('log_id' => $id);

            $dataUser = $this->Mod_general->delete('users', $users);

            $dataUser = $this->Mod_general->delete('login', $login);

            redirect(base_url() . 'user');

        }

    }

    public function removelist() {

        if ($this->input->post('id')) {

            $id = $this->input->post('id');

            $title = array('id' => $id);

            $object_id = array('object_id' => $id);

            $dataUser = $this->Mod_general->delete('cat_term_relationships', $object_id);

            $title = $this->Mod_general->delete('title', $title);

        }

        if ($title) {

            echo 1;

        } else {

            echo 0;

        }

    }

    public function userblog() {

        $data['title'] = 'User permission';

        $this->Mod_general->checkUser();

    }

}

/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */
