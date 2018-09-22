<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Post extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *     	http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('Mod_general');
        $this->load->library('dbtable');
        $this->load->theme('layout');
        $this->mod_general = new Mod_general();
        $this->load->library('Breadcrumbs');
    }

    public function index() {
        
    }

    public function newpost() {
        if ($this->input->post('btnSubmitNewPost')) {
            $titleName = trim($this->input->post('txtposttitle'));
            $textAreaContent = trim($this->input->post('textAreaContent'));
            $txtTypePost = trim($this->input->post('txtTypePost'));
            $txttAreaValuePost = trim($this->input->post('txttAreaValuePost'));
            $selectLabelPost = trim($this->input->post('selectLabelPost'));
            $table = Tablepost::post1;
            $tablePart = Tablepart::pparts;
            $tableLabel = Tablelabel::fac_label_relationships;
            $slugName = strtolower(str_replace(' ', '-', $titleName));
            $where = array(Tablepost::slug => $slugName);
            $getpostName = $this->Mod_post->select(Tablepost::slug, $table, $where);
            if (count($getpostName) > 0) {
                foreach ($getpostName as $rows) {
                    if ($rows->slug == $slugName) {

                        $slug = $slugName . '-' . strtolower($this->random(1));
                    } else {
                        $slug = $slugName;
                    }
                }
            } else {
                $slug = $slugName;
            }
            $arrayAddNews = array(
                Tablepost::post_title => $textAreaContent,
                Tablepost::post_content => $titleName,
                Tablepost::post_date => date('Y-d-m h:i:s'),
                Tablepost::slug => $slug
            );

            $idpost = $this->mod_post->insert($table, $arrayAddNews);
            $arrayToPart = array(
                Tablepart::post_id => $idpost,
                Tablepart::par_type => $txtTypePost,
                Tablepart::par_video_id => $txttAreaValuePost
            );
            $this->mod_post->insert($tablePart, $arrayToPart);
            $arrayToCategory = array(
                Tablelabel::post_id => $idpost,
                Tablelabel::label_id => $selectLabelPost
            );
            $this->Mod_post->insert($tableLabel, $arrayToCategory);
        }
        $this->load->view('newpost');
    }

    public function listpost() {

        $table = Tablepost::post1;
        $order = $table . '.' . Tablepost::ID;
        $arrayJoin = array(
            '1' => array(
                'table' => Tablelabel::fac_label_relationships,
                'field1' => $table . '.' . Tablepost::ID,
                'field2' => Tablelabel::fac_label_relationships . '.' . Tablelabel::post_id,
            ),
            '1' => array(
                'table' => Tablepart::pparts,
                'field1' => $table . '.' . Tablepost::ID,
                'field2' => Tablepart::pparts . '.' . Tablepart::post_id,
            )
        );
        $getListPost['data'] = $this->Mod_post->join($table, $arrayJoin, $fields = '*', $where = NULL, $order);
        $this->load->view('listpost', $getListPost);
    }

    public function download($code = '') {
        $actions = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        $data['title'] = 'List Blogs Categories';
        $backto = base_url() . 'post/blogpassword';
        $query_blog = $this->Mod_general->blogcheck(current_url(), $backto);
        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */
        $log_id = $this->session->userdata('log_id');

        /* show current categories */
        $query_singer = $this->Mod_general->select('singer');
        $sig = array();
        foreach ($query_singer as $value_sing) {
            $sig[] = $value_sing->sing_name;
        }



        /* form */
        if ($this->input->post('submit')) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('txtwebsiteurl', 'txtwebsiteurl', 'required');
            if ($this->form_validation->run() == TRUE) {
                $xmlurl = $this->input->post('txtwebsiteurl');
                $thumb = $this->input->post('txtimageurl');
                $txtlisten = $this->input->post('txtlisten');
                $label_s = $this->input->post('otherlabel');
                if (is_array($label_s)) {
                    $labels = array();
                    foreach ($label_s as $key => $value) {
                        $labels[] = $value;
                    }
                    $label = implode(',', $labels);
                }
                $label = @$label;
                $code = $this->get_from_download($xmlurl, $label, '', $thumb, $txtlisten);
            }
            if (!empty($id)) {
                redirect(base_url() . 'post/getcode/edit/' . $id);
            } else {
                redirect(base_url() . 'post/getcode?id=' . $code);
            }
        }
        /* end form */



        $data['js'] = array(
            'themes/layout/blueone/plugins/validation/jquery.validate.min.js',
        );
        $data['addJsScript'] = array(
            "$(document).ready(function(){
                $.validator.addClassRules('required', {
                required: true
                }); 
            });
            $('#validate').validate();
            "
        );

        $data['singer'] = $sig;
        $this->load->view('post/download', $data);
    }

    public function delete() {
        $actions = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        switch ($actions) {
            case "delete":
                $table_del = Tablepost::post1;
                $field_del = Tablepost::ID;
                $where_del = $id;
                $this->Mod_post->delete($table_del, $field_del, $where_del);
                $this->Mod_post->delete(Tablelabel::fac_label_relationships, Tablelabel::post_id, $id);
                redirect('post/listpost', 'location');

                break;
            case "deleteblog":
                $table_del = Tbl_title::tblname;
                $where_del = array(
                    Tbl_title::id => $id
                );
                $this->Mod_general->delete($table_del, $where_del);
                redirect(base_url() . 'post/bloglist');

                break;
            case "delblogcat":
                $table_del = Tbl_title::tblname;
                $where_del = array(
                    Tbl_title::id => $id
                );
                $this->Mod_general->delete($table_del, $where_del);
                redirect(base_url() . 'post/blogcate');
                break;
            case "delsinger":
                $where_del = array(
                    Tbl_singer::id => $id
                );
                $this->Mod_general->delete(Tbl_singer::tblname, $where_del);
                redirect(base_url() . 'post/singerlist');
                break;

            case "deletemovies":
                $table_del = Tbl_title::tblname;
                $where_del = array(
                    Tbl_title::id => $id
                );
                $this->Mod_general->delete($table_del, $where_del);
                $this->Mod_general->delete(Tbl_meta::tblname, array(Tbl_meta::object_id => $id, Tbl_meta::type => 'vdolist'));
                $this->Mod_general->delete(Tbl_meta::tblname, array(Tbl_meta::object_id => $id, Tbl_meta::type => 'by_post_id'));
                redirect(base_url() . 'post/movies');

                break;
        }
    }

    /* delete blogs post */

    public function delete_blog_post($service, $bid, $pid) {
        if (!empty($service) && !empty($bid) && !empty($pid)) {
            $uri = 'http://www.blogger.com/feeds/' . $bid . '/posts/default/' . $pid;
            return $service->delete($uri);
        } else {
            return FALSE;
        }
    }

    public function delete_site_post($keyword, $pid = '') {
        if (!empty($keyword)) {
            $check_unig = $this->Mod_general->like3('vdo_videos', array('uniq_id', 'id'), array('video_title' => $keyword));
            if (!empty($check_unig)) {
                foreach ($check_unig as $value_ch) {
                    $uniq_id = $value_ch->uniq_id;
                    $video_id = $value_ch->id;
                    $this->Mod_general->delete3('vdo_videos', array('id' => $video_id));
                    $this->Mod_general->delete3('vdo_meta', array('item_id' => $uniq_id));
                }
            }
        } else if (!empty($pid)) {
            $check_unig = $this->Mod_general->select3('vdo_videos', '', array('id' => $pid));
            if (!empty($check_unig)) {
                foreach ($check_unig as $value_ch) {
                    $uniq_id = $value_ch->uniq_id;
                    $video_id = $value_ch->id;
                    $this->Mod_general->delete3('vdo_meta', array('item_id' => $uniq_id));
                }
            }
            $this->Mod_general->delete3('vdo_videos', array('id' => $pid));
        }
    }

    public function edit() {
        $where = $this->uri->segment(4);
        $table = Tablepost::post1;
        $order = $table . '.' . Tablepost::ID;
        if ($this->input->post('btnSubmitNewPost')) {
            
        } else {
            $arrayJoin = array(
                '1' => array(
                    'table' => Tablelabel::fac_label_relationships,
                    'field1' => $table . '.' . Tablepost::ID,
                    'field2' => Tablelabel::fac_label_relationships . '.' . Tablelabel::post_id,
                ),
                '1' => array(
                    'table' => Tablepart::pparts,
                    'field1' => $table . '.' . Tablepost::ID,
                    'field2' => Tablepart::pparts . '.' . Tablepart::post_id,
                )
            );
            $getListPost['data'] = $this->Mod_post->join($table, $arrayJoin, $fields = '*', $where, $order);
            $this->load->view('edit', $getListPost);
        }
    }

    /**
     * 
     * @param undefined $length
     * 
     */
    public function random($length) {
        $chars = '1234567890';
        $size = strlen($chars);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }

    function RandomStr($length = 50) {
        $char = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= $char [rand(0, strlen($char) - 1)];
        }
        return $random;
    }

    /* Blog Categories */

    public function blogcate() {
        $actions = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        $data['title'] = 'List Blogs Categories';
        $backto = base_url() . 'post/blogpassword';
        $query_blog = $this->Mod_general->blogcheck(current_url(), $backto);
        /* Sidebar */
        $menuPermission = array();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */
        $log_id = $this->session->userdata('user_id');

        /* edit view */
        if ($actions == 'edit' && $id > 0) {
            $data_sel = array(
                Tbl_title::id => $id,
                Tbl_title::type => 'blog_category',
            );
            $dataBlogCate = $this->Mod_general->select('title', '*', $data_sel);
            $data['blogcatEdit'] = $dataBlogCate;
        }
        /* end edit view */
        /* form */
        if ($this->input->post('submit')) {
            $cat_name = $this->input->post('name');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'name', 'required');
            if ($this->form_validation->run() == TRUE) {
                $data_sub = array(
                    'mo_title' => $cat_name,
                    'mo_type' => 'blog_category',
                    'mo_status' => 1,
                    Tbl_title::object_id => $log_id,
                );
                $dataid = $this->Mod_general->insert('title', $data_sub);
            }
            redirect(base_url() . 'post/blogcate/add');
        } elseif ($this->input->post('update')) {
            $cat_name = $this->input->post('name');
            $cat_id = $this->input->post('id');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'name', 'required');
            $this->form_validation->set_rules('id', 'id', 'required');
            if ($this->form_validation->run() == TRUE) {
                $data_sub = array(
                    'mo_title' => $cat_name,
                    'mo_type' => 'blog_category',
                    'mo_status' => 1,
                    Tbl_title::object_id => $log_id,
                );
                $where_cat = array(Tbl_title::id => $cat_id);
                $dataid = $this->Mod_general->update(Tbl_title::tblname, $data_sub, $where_cat);
            }
            redirect(base_url() . 'post/blogcate/update');
        } else {
            /* show to view */
            $data_sel = array(
                'mo_type' => 'blog_category',
                Tbl_title::object_id => $log_id,
            );
            $dataBlogCate = $this->Mod_general->select('title', '*', $data_sel);
            $data['blogcatlist'] = $dataBlogCate;
        }
        /* end form */

        $this->load->view('post/blogcate', $data);
    }

    /* end Blog Categories */

    public function getfromb($id = '') {
        $data['title'] = 'Get from Web blog';
        $this->Mod_general->checkUser();
        $backto = base_url() . 'post/blogpassword';
        $query_blog = $this->Mod_general->blogcheck(current_url(), $backto);

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */


        /* form */
        if ($this->input->post('submit')) {
            $videotype = '';
            $this->load->library('form_validation');
            $this->form_validation->set_rules('blogid', 'blogid', 'required');
            if ($this->form_validation->run() == TRUE) {
                $xmlurl = $this->input->post('blogid');
                $thumb = $this->input->post('imageid');
                $videotype = $this->input->post('videotype');
                $title = $this->input->post('title');
                if (preg_match('/kmobilemovie/', $xmlurl)) {
                    $xmlurl = $this->sitekmobilemovie($xmlurl, $title, $thumb, $id, $videotype);
                }
                $code = $this->get_from_site_id($xmlurl, $id, $thumb, '', '', $videotype);
            }
            if (!empty($id)) {
                redirect(base_url() . 'post/getcode/edit/' . $id);
            } else {
                redirect(base_url() . 'post/getcode?id=' . $code);
            }
        }
        /* end form */

        /* show to view */

        $data['js'] = array(
            'themes/layout/blueone/plugins/validation/jquery.validate.min.js',
        );
        $data['addJsScript'] = array(
            "$(document).ready(function(){
                $.validator.addClassRules('required', {
                required: true
                });                
            });
            $('#validate').validate();
            "
        );

        $this->load->view('post/getfromb', $data);
    }

    public function continues($id = '') {
        if (empty($id)) {
            redirect(base_url() . 'post/movies');
        }
        $data['title'] = 'Get from Web blog';
        $this->Mod_general->checkUser();
        $backto = base_url() . 'post/blogpassword';
        $query_blog = $this->Mod_general->blogcheck(current_url(), $backto);

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */

        $data['id'] = $id;
        $this->load->view('post/continues', $data);
    }

    public function vdokh() {
        $data['title'] = 'Get from Web blog';
        $this->Mod_general->checkUser();
        $backto = base_url() . 'post/blogpassword';
        $query_blog = $this->Mod_general->blogcheck(current_url(), $backto);
        $log_id = $this->session->userdata('log_id');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */
        $continue = (!empty($_GET['continue']) ? $_GET['continue'] : '');
        $postid = (!empty($_GET['postid']) ? $_GET['postid'] : '');
        $order = (!empty($_GET['order']) ? $_GET['order'] : '');
        /* form */
        if ($this->input->post('mid')) {
            $this->load->library('html_dom');
            $mid = $this->input->post('mid');
            $urlid = $this->input->post('blogid');
            $orderby = $this->input->post('orderby');
            $imageid = $this->input->post('imageid');
            $title = $this->input->post('title');
            $rest = substr($urlid, 0, -11);
            $part = 0;

            /* delete */
            $data_post_del = array(
                Tbl_meta::type => 'not_in_use',
                Tbl_meta::user_id => $log_id,
            );
            $clean_data = $this->Mod_general->delete(Tbl_meta::tblname, $data_post_del);
            /* end delete */
            for ($a = 1; $a <= $mid; $a++) {
                $url_id = $rest . 'page-' . $a . '.html';
                $html = file_get_html($url_id);
                $count = array();
                foreach ($html->find('.blog-content .movie-item .movie-thumb a') as $e) {
                    $data_post_id = array(
                        Tbl_meta::type => 'not_in_use',
                        Tbl_meta::user_id => $log_id,
                        Tbl_meta::value => $e->href,
                    );
                    $dataPostID = $this->Mod_general->insert(Tbl_meta::tblname, $data_post_id);
                }
            }

            $data_title = array(
                Tbl_title::title => @$title,
                Tbl_title::type => 'vdolist',
                Tbl_title::object_id => $log_id,
                Tbl_title::image => $imageid,
            );
            $vdo_title_d = $this->Mod_general->insert(Tbl_title::tblname, $data_title);
            redirect(base_url() . 'post/vdokh?continue=' . $dataPostID . '&postid=' . $vdo_title_d . '&order=' . $orderby);
        }
        /* end form */

        /* get link */
        $orderby = (!empty($orderby) ? $orderby : '');
        if (!empty($continue)) {
            $get_data_link = $this->Mod_general->getvdo($continue, $postid);
            if (!empty($get_data_link)) {
                //sleep(2);
                echo '<script type="text/javascript">window.location = "' . base_url() . 'post/vdokh?continue=' . $get_data_link . '&postid=' . $postid . '&order=' . $order . '"</script>';
            } else {
                redirect(base_url() . 'post/getcode?id=' . $postid . '&order=' . $order);
                exit();
            }
        }

        /* end get link */
        $this->load->view('post/vdokh', $data);
    }

    public function getkhdrama() {
        $data['title'] = 'Get from Web blog';
        $this->Mod_general->checkUser();
        $backto = base_url() . 'post/blogpassword';
        $query_blog = $this->Mod_general->blogcheck(current_url(), $backto);
        $log_id = $this->session->userdata('log_id');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */
        $continue = (!empty($_GET['continue']) ? $_GET['continue'] : '');
        $postid = (!empty($_GET['postid']) ? $_GET['postid'] : '');
        $order = (!empty($_GET['order']) ? $_GET['order'] : '');
        $update = (!empty($_GET['update']) ? $_GET['update'] : '');
        /* form */
        if ($this->input->post('mid')) {
            $this->load->library('html_dom');
            $mid = $this->input->post('mid');
            $urlid = $this->input->post('blogid');
            $orderby = $this->input->post('orderby');
            $imageid = $this->input->post('imageid');
            $part = 0;

            /* delete */
            $data_post_del = array(
                Tbl_meta::type => 'not_in_use',
                Tbl_meta::user_id => $log_id,
            );
            $clean_data = $this->Mod_general->delete(Tbl_meta::tblname, $data_post_del);
            /* end delete */

            $html = file_get_html($urlid);
            $count = array();
            $last_key = end(array_keys($html->find('#pagination-digg a')));
            $page = array(
                '0' => $urlid,
            );
            foreach ($html->find('#pagination-digg a') as $key => $e) {
                if ($key == $last_key) {
                    //echo $urlid;
                } else {
                    array_push($page, $e->href);
                    // not last element
                }
            }
            foreach ($page as $key => $value) {
                $html_page = file_get_html($value);
                $i = 0;
                foreach ($html_page->find('.content .main li center a') as $center) {
                    $i++;
                    if ($i % 3 == 0) {
                        $data_post_id = array(
                            Tbl_meta::type => 'not_in_use',
                            Tbl_meta::user_id => $log_id,
                            Tbl_meta::value => $center->href,
                        );
                        $dataPostID = $this->Mod_general->insert(Tbl_meta::tblname, $data_post_id);
                    }
                }
            }
            $data_title = array(
                Tbl_title::title => '',
                Tbl_title::type => 'vdolist',
                Tbl_title::object_id => $log_id,
                Tbl_title::image => $imageid,
            );
            $vdo_title_d = $this->Mod_general->insert(Tbl_title::tblname, $data_title);
            redirect(base_url() . 'post/getkhdrama?continue=' . $dataPostID . '&postid=' . $vdo_title_d . '&order=' . $orderby . '&update=1');
        }
        /* end form */

        /* get link */
        $orderby = (!empty($orderby) ? $orderby : '');
        if (!empty($continue)) {
            $get_data_link = $this->Mod_general->getkhdrama($continue, $postid, $update);
            if (!empty($get_data_link)) {
                //sleep(2);
                echo '<script type="text/javascript">window.location = "' . base_url() . 'post/getkhdrama?continue=' . $get_data_link . '&postid=' . $postid . '&order=' . $order . '"</script>';
            } else {
                redirect(base_url() . 'post/getcode?id=' . $postid . '&order=' . $order);
                exit();
            }
        }

        /* end get link */
        $this->load->view('post/getkhdrama', $data);
    }

    /* get from blogger site by Socheat Ngann */

    function get_from_site_id($site_url = '', $post_id = '', $thumb = '', $New_title = '', $New_label = '', $videotype = '') {
        $log_id = $this->session->userdata('log_id');
        if (!empty($post_id)) {
            $vdo_type = 'vdolist';
            $where_del = array(
                Tbl_meta::object_id => $post_id,
                Tbl_meta::user_id => $log_id,
                Tbl_meta::type => $vdo_type,
            );
        } else {
            $vdo_type = 'vdolist';
            $where_del = array(
                Tbl_meta::object_id => $log_id,
                Tbl_meta::user_id => $log_id,
                Tbl_meta::type => $vdo_type,
            );
        }
        /* delete before insert new list */
        $dataLogin = $this->Mod_general->delete(Tbl_meta::tblname, $where_del);
        /* end delete before insert new list */
        $this->load->library('html_dom');
        $html = file_get_html($site_url);
        $title = @$html->find('.post-title a', 0)->innertext;
        $title1 = @$html->find('.post-title', 0)->innertext;
        if ($title) {
            $title = $html->find('.post-title a', 0)->innertext;
        } elseif ($title1) {
            $title = $html->find('.post-title', 0)->innertext;
        } else {
            $title = $html->find('title', 0)->innertext;
        }
        $postTitle = $title;
        $list_id = $this->get_site_content($html);
        if (preg_match('/[class=noi]/', $html)) {
            foreach ($html->find('.noi img') as $e)
                $thumbIn = $e->src;
        } else if (preg_match('/[id=noi]/', $html)) {
            foreach ($html->find('#noi img') as $e)
                $thumbIn = $e->src;
        } else {
            foreach ($html->find('#noi') as $e)
                $thumbIn = $e->src;
        }
        $thumbIn = (!empty($thumbIn) ? $thumbIn : '0');
        /* insert new list */
        $title = (!empty($title) ? $title : $postTitle);
        if (preg_match('/]/', $title)) {
            $title = explode('[', $title);
            $title = $title[0];
        } else {
            $title = $title;
        }
        $title = (!empty($New_title) ? $New_title : strip_tags($title));
        /* check title */
        if (!empty($post_id)) {
            $where_title = array(
                Tbl_title::object_id => $log_id,
                Tbl_title::id => $post_id,
            );
            $dataCheckTitle = $this->Mod_general->select(Tbl_title::tblname, Tbl_title::id, $where_title);
        } else {
            $where_movie = array(
                Tbl_title::type => 'vdolist',
                Tbl_title::object_id => $log_id,
            );
            $seach_movie = array(
                Tbl_title::title => trim($title),
            );
            $dataCheckTitle = $this->Mod_general->like(Tbl_title::tblname, '*', $seach_movie, $where_movie);
        }

        $thumb = (!empty($thumb) ? $thumb : $thumbIn);
        if (!empty($dataCheckTitle)) {
            $post_id = $dataCheckTitle[0]->{Tbl_title::id};
            if (!empty($thumb)) {
                $where_titles = array(
                    Tbl_title::id => $post_id,
                );
                $data_titles = array(
                    Tbl_title::image => $thumb,
                );
                $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_titles, $where_titles);
            }
        } else {
            $data_title = array(
                Tbl_title::title => trim($title),
                Tbl_title::type => 'vdolist',
                Tbl_title::object_id => $log_id,
                Tbl_title::image => $thumb,
                Tbl_title::value => @$New_label,
            );
            $post_id = $this->Mod_general->insert(Tbl_title::tblname, $data_title);
        }
        /* check title */
        if (!empty($post_id)) {
            $i = 0;
            foreach ($list_id as $value) {
                $i++;
                if (!empty($videotype)) {
                    $v_type = $videotype;
                } else {
                    $v_type = $value['vtype'];
                }
                $dataLogin = $this->Mod_general->add_meta($value['list'], $i, $title, $v_type, $post_id);
            }
        }
        /* end insert new list */
        return $post_id;
    }

    function get_from_download($site_url = '', $label = '', $post_id = '', $thumb = '', $txtlisten = '') {
        $log_id = $this->session->userdata('log_id');
        $this->load->library('html_dom');
        $html = file_get_html($site_url);

        $html = file_get_html($site_url);
        $title = @$html->find('.post-title a', 0)->innertext;
        $title1 = @$html->find('.post-title', 0)->innertext;
        if ($title) {
            $title = $html->find('.post-title a', 0)->innertext;
        } elseif ($title1) {
            $title = $html->find('.post-title', 0)->innertext;
        } else {
            $title = $html->find('title', 0)->innertext;
        }
        $postTitle = $title;

        /* if listen link */
        if (!empty($txtlisten)) {
            $musicOnline = '<script type="text/javascript">window.onload = function(){var iframe = document.createElement("iframe"); iframe.frameBorder = 0; iframe.width = "100%"; iframe.height = "635px"; iframe.id = "randomid"; iframe.scrolling = "no"; iframe.setAttribute("src", musicplayer + "music/mp3/mp3.php?label=' . $txtlisten . '"); iframe.setAttribute("allowfullscreen", ""); document.getElementById("musicplayer").appendChild(iframe); }</script>';
//                $listen = '</a><a class="listenbtn" href="' . $txtlisten . '" target="_blank">Listen Online';
//                $listen_a = '<a class="listenbtn" href="' . $txtlisten . '" target="_blank">Listen Online</a>';
        } else {
            $musicOnline = '';
        }

        $ContentTop = '<div style="clear:both;"></div>Dear valued visitors, We try to find the songs which produce by this Production to post them in this website, because we have many visitors over the world request us to post more Khmer songs in our site.<br /><br />&nbsp;Well, we would like to thank to visitors who support us for taking their times to write the comments to us to tell about problem of this website. We want to say that, please feel free to tell us. Anyway, if you want to request more Khmer songs? you can post your request in the our forum. This album has 10 Songs.<br /><br /><div style="text-align: center;">[ <span style="color: green;"><i>Please buy CD, VCD original for support this production and your favorite star</i></span> ]</div>';
        if (preg_match('/khmer7/', $site_url)) {
            $content = $html->find('#Blog1 .post-body', 0)->innertext;
            //<div style="text-align: center;">
            $download_link = @$html->find('#Blog1 .post-body div[style=text-align: center;] b a', 0)->href;
            $download_link_a = @$html->find('#Blog1 .post-body div[style=text-align: center;] span span a', 0)->href;
            if (!empty($download_link_a)) {
                $download_link = $download_link_a;
            } else if (!empty($download_link)) {
                $download_link = $download_link;
            } else {
                $download_link = '';
            }
            if (!empty($download_link)) {
                $down = '<a class="downloadbtn" href="' . $download_link . '" target="_blank">Get It All</a>';
                if (preg_match('/Download Full/', $content)) {
                    $downLink = '</a><script>var dlink ="' . $download_link . '";</script><a>';
                    $content = str_replace('Download Full', $downLink, $content);
                } else if (preg_match('/Full DOWNLOAD/', $content)) {
                    $downLink = '</a><script>var dlink ="' . $download_link . '";</script><a>';
                    $content = str_replace('Full DOWNLOAD', $downLink, $content);
                } else if (preg_match('/Full Download/', $content)) {
                    $downLink = '</a><script>var dlink ="' . $download_link . '";</script><a>';
                    $content = str_replace('Full Download', $downLink, $content);
                } else if (preg_match('/Get It All/', $content)) {
                    $downLink = '</a><script>var dlink ="' . $download_link . '";</script><a>';
                    $content = str_replace('Get It All', $downLink, $content);
                }
            } else {
                $down = '';
            }
            $content = explode('<!-- End BidVertiser code -->', $content);
            if (!empty($content[1])) {
                $content = $content[1];
                $content = explode('<!-- Begin Zhakkas Ads Code -->', $content);
                $content = @$content[0];
            }


            if (preg_match('/Listen Online/', $content)) {
                $content = str_replace('Listen Online', '', $content);
            } else {
                //$content = $content . '' . $listen_a;
            }
            $content = str_replace('separator', 'separator-hidden', $content);
        } else
        if (preg_match('/khmerpm/', $site_url)) {
            $content = $html->find('#Blog1 .post-body', 0)->innertext;
            $download_link = @$html->find('#Blog1 .post-body div[style=text-align: center;] b a', 0)->href;
            if (!empty($download_link)) {
                $down = '</a><a class="downloadbtn" href="' . $download_link . '" target="_blank">Get It All';
                $content = str_replace('Get It All', $down, $content);
            }

            $content = explode('<script async', $content);
            if (!empty($content[0])) {
                $content = $content[0];
                if (!empty($txtlisten)) {
                    $listen = '</a><a class="listenbtn" href="' . $txtlisten . '" target="_blank">Listen Online';
                } else {
                    $listen = '';
                }

                $content = str_replace('Listen Online', $listen, $content);
                $content = str_replace('separator', 'separator-hidden', $content);
                $content = $content . '</div></div>';
            } else {
                $content = $content;
            }
        } else {
            $content = $html->find('#Blog1 .post-body', 0)->innertext;
            $download_link = @$html->find('#Blog1 .post-body div[style=text-align: center;] b a', 0)->href;
            if (!empty($download_link)) {
                $down = '</a><a class="downloadbtn" href="' . $download_link . '" target="_blank">Get It All';
                $content = str_replace('Get It All', $down, $content);
            }

            $content = explode('<script async', $content);
            if (!empty($content[0])) {
                $content = $content[0];
                if (!empty($txtlisten)) {
                    $listen = '</a><a class="listenbtn" href="' . $txtlisten . '" target="_blank">Listen Online';
                } else {
                    $listen = '';
                }

                $content = str_replace('Listen Online', $listen, $content);
                $content = str_replace('separator', 'separator-hidden', $content);
                $content = $content . '</div></div>';
            } else {
                $content = $content;
            }
        }

        if (!empty($thumb)) {
            $thumb = $thumb;
        } else {
            $thumb = @$html->find('link[rel=image_src]', 0)->href;
            if (!empty($thumb)) {
                $thumb = $thumb;
            } else {
                $thumb = $this->get_the_image($content);
                $thumb = $thumb['url'];
            }
        }
        $contentT = $ContentTop . '' . $content . '' . $musicOnline;
        /* inter new data for title */
        $data_title = array(
            Tbl_title::title => trim($title),
            Tbl_title::type => 'vdolist',
            Tbl_title::object_id => $log_id,
            Tbl_title::image => trim($thumb),
            Tbl_title::content => trim($contentT),
            Tbl_title::value => @$label,
        );
        $post_id = $this->Mod_general->insert(Tbl_title::tblname, $data_title);
        /* end inter new data for title */
        if (!empty($post_id)) {
            return $post_id;
        } else {
            return FALSE;
        }
    }

    function getcode($urls = '', $code = '') {
        $data['title'] = 'Get from Web blog';
        $this->Mod_general->checkUser();
        $backto = base_url() . 'post/blogpassword';
        $query_blog = $this->Mod_general->blogcheck(current_url(), $backto);
        $log_id = $this->session->userdata('log_id');
        $title_id = (!empty($_GET['id']) ? $_GET['id'] : '');
        $user = $this->session->userdata('username');
        $post_movie_website = $this->session->userdata('movivewebsite');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */


        /* form */
        $submit1 = $this->input->post('submit1');
        if ($this->input->post('submit') || $submit1) {
            $videotype = $this->input->post('videotype');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('idblog', 'idblog', 'required');
            $this->form_validation->set_rules('codetype', 'codetype', 'required');
            $this->form_validation->set_rules('videotype', 'videotype', 'required');
            if ($this->form_validation->run() == TRUE) {
                /* get permission from blog account */
                $this->load->library('zend');
                $this->zend->load('Zend/Loader');
                Zend_Loader::loadClass('Zend_Gdata');
                Zend_Loader::loadClass('Zend_Gdata_Query');
                Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
                // set credentials for ClientLogin authentication
                $user = $this->session->userdata('username');
                $pass = $this->session->userdata('blogpassword');
                /* end get permission from blog account */

                $blog_ids = $this->input->post('idblog');
                $orderby = $this->input->post('orderby');
                $title = trim($this->input->post('title'));

                $limon_title = trim($this->input->post('limontitle'));
                $limon_title = str_replace('<p', '<span', $limon_title);
                $limon_title = str_replace('</p', '</span', $limon_title);
                $limon_title = strip_tags($limon_title, '<div><span>');

                $vdo_title_d = $this->input->post('vdo_title_d');
                $codetype = $this->input->post('codetype');
                $ytplayerBody = $this->input->post('ytplayerBody');
                $jwplayerBody = $this->input->post('jwplayerBody');
                $jwplayerYtlist = $this->input->post('jwplayerYtlist');
                $onyoutbueBody = $this->input->post('onyoutbueBody');
                $onyoutbueBody1 = $this->input->post('onyoutbueBody1');
                $label_add = $this->input->post('labeladd');
                $label_add = str_replace("'", '�', $label_add);
                $label_add = addslashes($label_add);

                $label = $this->input->post('label');
                $label = str_replace("'", '�', $label);
                $label = addslashes($label);

                $editaction = $this->input->post('editaction');
                $vdo_id = $this->input->post('vdo_id');
                $imagepost = trim($this->input->post('imagepost'));

                $copyright_video = $this->input->post('copyrightvideo');
                $datepost = $this->input->post('datepost');


                /* get label */
                if (!empty($label)) {
                    $label = implode(",", $label) . $label_add;
                } else {
                    $label = $label_add;
                }
                /* end get label */

                /* body text */
                switch ($codetype) {
                    case 'tynewplayer':
                        $bodytext = $ytplayerBody;
                        break;
                    case 'jwplayer':
                        $bodytext = $jwplayerBody;
                        break;
                    case 'ytlist':
                        $bodytext = $jwplayerYtlist;
                        break;
                    case 'onyoutbue':
                        $bodytext = $onyoutbueBody;
                        break;
                    case 'onyoutbue1':
                        $bodytext = $onyoutbueBody1;
                        break;
                }
                $titles = explode('-:-', $title);
                if (!empty($titles)) {
                    $title_ogrin = trim($titles[0]);
                } else {
                    $title_ogrin = trim($title);
                }

                $breaks = array("\r\n", "\n", "\r");
                $bodytext_normal = str_replace($breaks, "", $bodytext);
                $addOnBody = '<a href="' . $imagepost . '" target="_blank"><img border="0" id="noi" src="' . $imagepost . '" /></a><meta property="og:image" content="' . $imagepost . '"/><link href="' . $imagepost . '" rel="image_src"/><!--more-->';
                $bodytext = str_replace($breaks, "", $bodytext);
                $bodytext_normal = $bodytext;
                include 'addon_post_keywords.php';
                $bodytext = $addOnBody . $bodytext;
                /* end body text */

                /* add to website */
                $bloginCat = $this->input->post('bloginCat');
                $data_movies = array(
                    Tbl_title::type => 'config',
                    Tbl_title::title => 'movie_post',
                );
                $movie_post = $this->Mod_general->select(Tbl_title::tblname, '*', $data_movies);
                foreach ($movie_post as $value_site) {
                    $cat_movie = $value_site->{Tbl_title::value};
                }

                /* end add to website */


                /* title */
                if (empty($editaction)) {
                    /* post movie website */
                    if (!empty($post_movie_website) || $this->session->userdata('user_type') == 1) {
                        if (!empty($cat_movie)) {
                            if ($bloginCat == $cat_movie) {
                                $this->post_to_website($title_ogrin, $vdo_id, $label, 'yt', '', $codetype, $copyright_video, $imagepost, $orderby);
                            }
                        }
                    }
                    /* end post movie website */
                    if ($videotype == '1') {
                        if (empty($vdo_id)) {
                            /* add new title */
                            $data_title = array(
                                Tbl_title::title => trim($title_ogrin),
                                Tbl_title::type => 'vdolist',
                                Tbl_title::object_id => $log_id,
                                Tbl_title::content => $bodytext_normal,
                                Tbl_title::image => $imagepost,
                                Tbl_title::khmer_title => @$limon_title,
                            );
                            $vdo_title_d = $this->Mod_general->insert(Tbl_title::tblname, $data_title);
                            /* end add new title */
                            $vdo_id = $vdo_title_d;
                        } else {
                            $where_title = array(
                                Tbl_title::type => 'vdolist',
                                Tbl_title::id => $vdo_id,
                            );
                            $data_title = array(
                                Tbl_title::title => trim($title_ogrin),
                                Tbl_title::content => $bodytext_normal,
                                Tbl_title::status => 0,
                                Tbl_title::image => $imagepost,
                                Tbl_title::khmer_title => @$limon_title
                            );
                            $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_title, $where_title);
                        }
                    }
                    /* history record */
                    $this->Mod_general->addHistory($vdo_id, $bloginCat);
                    /* end history record */
                }
                /* end title */

                /* ADD TO BLOG */
                try {
                    // perform login 
                    // initialize service object
                    $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, 'blogger');
                    $service = new Zend_Gdata($client);

                    // save entry to server
                    // return unique id for new post
                    $num = 0;
                    foreach ($blog_ids as $key => $blog_id) {
                        $num++;
                        /* EDIT POST */
                        if (!empty($editaction)) {
                            /* history record */
                            $this->Mod_general->addHistory($vdo_id, 'edit');
                            /* end history record */

                            /* post movie website */
                            if (!empty($post_movie_website) || $this->session->userdata('user_type') == 1) {
                                $this->post_to_website($title_ogrin, $vdo_id, $label, 'yt', 1, $codetype, $copyright_video, $imagepost, $orderby);
                            }
                            /* end post movie website */

                            /* get post id */
                            $data_post_id = array(
                                Tbl_meta::object_id => $vdo_title_d,
                                Tbl_meta::type => 'by_post_id',
                                Tbl_meta::user_id => $log_id,
                                Tbl_meta::value => $blog_id,
                            );
                            //var_dump($data_post_id);
                            $GetPost_id = $this->Mod_general->select(Tbl_meta::tblname, array(Tbl_meta::key), $data_post_id);
                            if (!empty($GetPost_id[0])) {
                                $pid = $GetPost_id[0]->{Tbl_meta::key};
                            } else {
                                $pid = '';
                            }
                            /* end get post id */

                            $uri = 'http://www.blogger.com/feeds/' . $blog_id . '/posts/default/' . $pid;
                            $response = $service->get($uri);
                            $entry = new Zend_Gdata_App_Entry($response->getBody());
                            $entry->title = $service->newTitle($title);

                            $str = stripslashes($bodytext);
                            $str = str_replace("<br />", "\n", $str);
                            $entry->content = $service->newContent($str);
                            $entry->content->setType('html');

                            //$tags = $_POST['label'];
                            $tags = explode(",", $label);
                            if (is_array($tags)) {
                                /*clean before add new*/
                                $CatData_tax_del = array(
                                    Tbl_cat_term_relationships::object_id => trim($vdo_title_d)
                                );
                                $add_category = $this->Mod_general->delete(Tbl_cat_term_relationships::TBL, $CatData_tax_del);
                                /*end clean before add new*/
                                $labels = array();
                                foreach ($tags as $tag) {
                                    if (!empty($tag)) {
                                        /* insert Category to DB */
                                        $where_category = array(
                                            Tbl_cat_term::name => $tag,
                                        );
                                        $Ch_categorys = $this->Mod_general->select(Tbl_cat_term::TBL, '*', $where_category);
                                        foreach ($Ch_categorys as $value) {
                                            $nameCats = $value->{Tbl_cat_term::name};
                                        }
                                        if (empty($nameCats)) {
                                            /* check slug */
                                            $where_category_slug = array(
                                                Tbl_cat_term::slug => strtolower(str_replace(' ', '-', trim($tag)))
                                            );
                                            $Ch_categorys_slug = $this->Mod_general->select(Tbl_cat_term::TBL, '*', $where_category_slug);
                                            foreach ($Ch_categorys_slug as $value_slug) {
                                                
                                            }
                                            if (!empty($value_slug)) {
                                                $slug_id = strtolower(str_replace(' ', '-', trim($tag))) . '-' . $this->generateRandomString(1);
                                            } else {
                                                $slug_id = strtolower(str_replace(' ', '-', trim($tag)));
                                            }
                                            /* end check slug */

                                            $CatData = array(
                                                Tbl_cat_term::name => trim($tag),
                                                Tbl_cat_term::slug => trim($slug_id),
                                                Tbl_cat_term::term_group => 'category',
                                            );
                                            $add_category = $this->Mod_general->insert(Tbl_cat_term::TBL, $CatData);
                                            $CatData_tax = array(
                                                Tbl_cat_term_taxonomy::term_id => $add_category,
                                                Tbl_cat_term_taxonomy::taxonomy => 'category',
                                                Tbl_cat_term_taxonomy::parent => 0,
                                            );
                                            $add_category_tax = $this->Mod_general->insert(Tbl_cat_term_taxonomy::TBL, $CatData_tax);
                                        }
                                        /* end insert Category to DB */


                                        /* continue movies category */
//                                        $where_category_cont = array(
//                                            Tbl_cat_term_relationships::object_id => $vdo_title_d,
//                                            Tbl_cat_term_relationships::name => $tag,
//                                        );
//                                        $Ch_category_cont = $this->Mod_general->select(Tbl_cat_term_relationships::TBL, '*', $where_category_cont);
//                                        if (empty($Ch_category_cont)) {
//                                            
//                                        }
                                        $CatData_tax = array(
                                                Tbl_cat_term_relationships::object_id => trim($vdo_title_d),
                                                Tbl_cat_term_relationships::name => trim($tag),
                                            );
                                        $add_category = $this->Mod_general->insert(Tbl_cat_term_relationships::TBL, $CatData_tax);
                                        if ($videotype == '1') {
//                                            $where_category_cont = array(
//                                                Tbl_cat_term_relationships::object_id => $vdo_title_d,
//                                                Tbl_cat_term_relationships::name => $tag,
//                                            );
//                                            $Ch_category_cont = $this->Mod_general->select(Tbl_cat_term_relationships::TBL, '*', $where_category_cont);
//                                            if (empty($Ch_category_cont)) {
//                                                $CatData_tax = array(
//                                                    Tbl_cat_term_relationships::object_id => trim($vdo_title_d),
//                                                    Tbl_cat_term_relationships::name => trim($tag),
//                                                );
//                                                $add_category = $this->Mod_general->insert(Tbl_cat_term_relationships::TBL, $CatData_tax);
//                                            }
                                        } else {
//                                            $CatData_tax_del = array(
//                                                Tbl_cat_term_relationships::object_id => trim($vdo_title_d)
//                                            );
//                                            $add_category = $this->Mod_general->delete(Tbl_cat_term_relationships::TBL, $CatData_tax_del);
                                        }
                                        /* enc continue movies category */

                                        //$labels[] = $service->newCategory(trim($tag), 'http:www.blogger.com/atom/ns#');
                                        $labels[] = new Zend_Gdata_App_Extension_Category(trim($tag), 'http://www.blogger.com/atom/ns#');
                                    }
                                }
                                /* Adding tags to post */
                                $entry->setCategory($labels);
                            }

                            /* for Limon Title */
                            if (!empty($limon_title)) {
                                $related = new Zend_Gdata_App_Extension_Link();
                                $related->setHref('http://khmer.com');
                                $related->setRel('related');
                                $related->setTitle($title);
                                $limonArray[] = $related;
                                $entry->getEditLink($limonArray);

                                $limon_arr = new Zend_Gdata_App_Extension_Link();
                                $limon_arr->setHref('http://title.com');
                                $limon_arr->setRel('enclosure');
                                $limon_arr->setType($limon_title);
                                $limon_arr->setLength('0');
                                $limonArray[] = $limon_arr;
                                $entry->getEditLink($limonArray);
                            }
                            /* end for Limon Title */

                            /* update time */
                            date_default_timezone_set('Asia/Phnom_Penh');
                            if ($datepost == 0) {
                                $date = date("c");
                                $dated = new Zend_Gdata_App_Extension_Updated($date);
                                $date_pub = new Zend_Gdata_App_Extension_Published($date);
                                $entry->setUpdated($dated);
                                $entry->setPublished($date_pub);
                            } else if ($datepost == 2) {
                                $dateset = $this->input->post('dateset');
                                $date = $dateset . 'T';
                                $date .= date("H:i:sP");
                                $dated = new Zend_Gdata_App_Extension_Updated($date);
                                $date_pub = new Zend_Gdata_App_Extension_Published($date);
                                $entry->setUpdated($dated);
                                $entry->setPublished($date_pub);
                            }
                            /* end update time */

                            $update = $service->updateEntry($entry);
                            $where_title = array(
                                Tbl_title::type => 'vdolist',
                                Tbl_title::id => $vdo_title_d,
                            );
                            $data_title = array(
                                Tbl_title::title => trim($title_ogrin),
                                Tbl_title::content => $str,
                                Tbl_title::image => $imagepost,
                                Tbl_title::khmer_title => @$limon_title
                            );
                            $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_title, $where_title);
                            /* END EDIT POST */
                        } else {

                            $bloginCat = $this->input->post('bloginCat');
                            // create a new entry object
                            // populate it with user input      
                            $entry = $service->newEntry();
                            $entry->title = $service->newTitle($title);

                            $str = stripslashes($bodytext);
                            $str = $str = str_replace("<br />", "\n", $str);
                            $entry->content = $service->newContent($str);
                            $entry->content->setType('html');

                            //$tags = $_POST['label'];
                            $tags = explode(",", $label);
                            if (is_array($tags)) {
                                $labels = array();
                                foreach ($tags as $tag) {
                                    if (!empty($tag)) {
                                        /* insert Category to DB */
                                        $where_category = array(
                                            Tbl_cat_term::name => trim($tag)
                                        );
                                        $Ch_categorys = $this->Mod_general->select(Tbl_cat_term::TBL, '*', $where_category);
                                        if (empty($Ch_categorys)) {
                                            /* check slug */
                                            $where_category_slug = array(
                                                Tbl_cat_term::slug => strtolower(str_replace(' ', '-', trim($tag)))
                                            );
                                            $Ch_categorys_slug = $this->Mod_general->select(Tbl_cat_term::TBL, '*', $where_category_slug);
                                            if (!empty($Ch_categorys_slug)) {
                                                $slug_id = strtolower(str_replace(' ', '-', trim($tag))) . '-' . $this->generateRandomString(1);
                                            } else {
                                                $slug_id = strtolower(str_replace(' ', '-', trim($tag)));
                                            }
                                            /* end check slug */

                                            $CatData = array(
                                                Tbl_cat_term::name => trim($tag),
                                                Tbl_cat_term::slug => trim($slug_id),
                                                Tbl_cat_term::term_group => 'category',
                                            );
                                            $add_category = $this->Mod_general->insert(Tbl_cat_term::TBL, $CatData);
                                            $CatData_tax = array(
                                                Tbl_cat_term_taxonomy::term_id => $add_category,
                                                Tbl_cat_term_taxonomy::taxonomy => 'category',
                                                Tbl_cat_term_taxonomy::parent => 0,
                                            );
                                            $add_category_tax = $this->Mod_general->insert(Tbl_cat_term_taxonomy::TBL, $CatData_tax);
                                        }
                                        /* end insert Category to DB */

                                        /* continue movies category */
                                        if ($videotype == '1') {
                                            $where_category_cont = array(
                                                Tbl_cat_term_relationships::object_id => $vdo_id,
                                                Tbl_cat_term_relationships::name => $tag,
                                            );
                                            $Ch_category_cont = $this->Mod_general->select(Tbl_cat_term_relationships::TBL, '*', $where_category_cont);
                                            if (empty($Ch_category_cont)) {
                                                $CatData_tax = array(
                                                    Tbl_cat_term_relationships::object_id => trim($vdo_id),
                                                    Tbl_cat_term_relationships::name => trim($tag),
                                                );
                                                $add_category = $this->Mod_general->insert(Tbl_cat_term_relationships::TBL, $CatData_tax);
                                            }
                                        } else {
//                                            $CatData_tax_del = array(
//                                                Tbl_cat_term_relationships::object_id => trim($vdo_id)
//                                            );
//                                            $add_category = $this->Mod_general->delete(Tbl_cat_term_relationships::TBL, $CatData_tax_del);
                                        }
                                        /* enc continue movies category */

                                        //$labels[] = $service->newCategory(trim($tag), 'http:www.blogger.com/atom/ns#');
                                        $labels[] = new Zend_Gdata_App_Extension_Category(trim($tag), 'http://www.blogger.com/atom/ns#');
                                    }
                                }
                                /* for Limon Title */
                                if (!empty($limon_title)) {
                                    $related = new Zend_Gdata_App_Extension_Link();
                                    $related->setHref('http://khmer.com');
                                    $related->setRel('related');
                                    $related->setTitle($title);
                                    $limonArray[] = $related;
                                    $entry->setLink($limonArray);

                                    $limon_arr = new Zend_Gdata_App_Extension_Link();
                                    $limon_arr->setHref('http://title.com');
                                    $limon_arr->setRel('enclosure');
                                    $limon_arr->setType($limon_title);
                                    $limon_arr->setLength('0');
                                    $limonArray[] = $limon_arr;
                                    $entry->setLink($limonArray);
                                }

                                /* end for Limon Title */

                                /* Adding tags to post */
                                $entry->setCategory($labels);
                            }

                            $uri = 'http://www.blogger.com/feeds/' . $blog_id . '/posts/default';
                            $post_blog_id = $this->insertNewPost($entry, $uri, $service);
                            /* update VDO if not finish */
                            $data_post_id = array(
                                Tbl_meta::object_id => $vdo_id,
                                Tbl_meta::type => 'by_post_id',
                                Tbl_meta::user_id => $log_id,
                                Tbl_meta::value => $blog_id,
                                Tbl_meta::key => $post_blog_id,
                            );
                            $dataPostID = $this->Mod_general->insert(Tbl_meta::tblname, $data_post_id);
                        }
                    }
                } catch (Exception $e) {
                    $e->getMessage();
                    //die('ERROR:' . $e->getMessage());
                }
                /* END ADD TO BLOG */

                /* update VDO if not finish */
                if ($videotype == '1') {
                    $where_title = array(
                        Tbl_title::type => 'vdolist',
                        Tbl_title::object_id => $log_id,
                        Tbl_title::id => $vdo_title_d,
                    );
                    $data_title = array(
                        Tbl_title::title => $title_ogrin,
                        Tbl_title::status => 0,
                        Tbl_title::image => $imagepost,
                        Tbl_title::value => implode(",", $blog_ids),
                    );
                    $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_title, $where_title);
                } elseif ($videotype == '0') {
                    $where_title = array(
                        Tbl_title::type => 'vdolist',
                        Tbl_title::object_id => $log_id,
                        Tbl_title::id => $vdo_title_d,
                    );
                    $data_title = array(
                        Tbl_title::title => $title_ogrin,
                        Tbl_title::status => 1,
                        Tbl_title::image => $imagepost,
                        Tbl_title::value => implode(",", $blog_ids),
                    );
                    $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_title, $where_title);
                }
                /* end update VDO if not finish */
            }
            if ($submit1) {
                redirect(base_url() . 'post/getcode/edit/' . $vdo_id);
            } else {
                if ($videotype == '1') {
                    redirect(base_url() . 'post/movies?id=' . $vdo_id);
                } else {
                    redirect(base_url() . 'post/movies');
                }
            }
        }
        /* end form */

        /* show to view */
        if ($post_movie_website) {
            $data_sel = array(
                Tbl_title::type => 'blog_category',
                Tbl_title::object_id => 1,
            );
        } else {
            $data_sel = array(
                Tbl_title::type => 'blog_category',
                Tbl_title::object_id => $log_id,
            );
        }

        $dataBlogCate = $this->Mod_general->select(Tbl_title::tblname, '*', $data_sel);
        $data['blogcatlist'] = $dataBlogCate;

        /* EDIT */
        if ($urls == 'edit' && !empty($code)) {
            /* get video list */
            $where_vdo_list = array(
                Tbl_meta::object_id => $code,
                Tbl_meta::type => 'vdolist',
                Tbl_meta::user_id => $log_id,
            );
            /* bloglist */
            $where_b_list = array(
                Tbl_meta::object_id => $code,
                Tbl_meta::type => 'by_post_id',
                Tbl_meta::user_id => $log_id,
            );
//            $where = array(
//                Tbl_title::type => 'vdolist',
//                Tbl_title::object_id => $log_id,
//            );
//            $query_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $where);
//            $i = 0;
//            
//            foreach ($query_blog as $value_blog_list) {
//                $blogID = $value_blog_list->{Tbl_title::value};
//                $blogID = explode(',', $blogID);
//                $blogData = array();
//                foreach ($blogID as $key => $IDblog) {
//                    $getIDblog = array(
//                        Tbl_title::object_id => $IDblog,
//                        Tbl_title::value => $log_id,
//                        Tbl_title::type => 'blogger_id');
//                    $query_blog_id = $this->Mod_general->select(Tbl_title::tblname, '*', $getIDblog);
//                    foreach ($query_blog_id as $data_Blog) {
//                        $i++;
//                        $blogData[$i][Tbl_title::title] = $data_Blog->{Tbl_title::title};
//                        $blogData[$i][Tbl_title::object_id] = $data_Blog->{Tbl_title::object_id};
//                    }
//                }
//            }
            $query_b_list = $this->Mod_general->select(Tbl_meta::tblname, '*', $where_b_list);
            $i = 0;
            $blogData = array();
            foreach ($query_b_list as $data_Blogs) {
                $getIDblog = array(
                    Tbl_title::object_id => $data_Blogs->{Tbl_meta::value},
                    Tbl_title::value => $log_id,
                    Tbl_title::type => 'blogger_id');
                $query_blog_id = $this->Mod_general->select(Tbl_title::tblname, '*', $getIDblog);
                foreach ($query_blog_id as $value_b) {
                    $blogData[$i][Tbl_title::title] = $value_b->{Tbl_title::title};
                    $blogData[$i][Tbl_title::object_id] = $value_b->{Tbl_title::object_id};
                }
                $i++;
            }
            $data['editaction'] = 1;


            /* show title */
            $where_title = array(
                Tbl_title::type => 'vdolist',
                Tbl_title::object_id => $log_id,
                Tbl_title::id => $code,
            );

            /* show current categories */
            $where_cur_cat = array(
                Tbl_cat_term_relationships::object_id => $code,
            );
            $query_cur_cat = $this->Mod_general->select(Tbl_cat_term_relationships::TBL, '*', $where_cur_cat);
            $current_cat = array();
            foreach ($query_cur_cat as $value_cur_cat) {
                $current_cat[] = $value_cur_cat->{Tbl_cat_term_relationships::name};
            }
            $data['current_cat'] = implode(", ", $current_cat);
            /* end show current categories */

            if (empty($blogData)) {
                /* bloglist */
                $where = array(
                    Tbl_title::type => 'blogger_id',
                    Tbl_title::value => $log_id,
                    Tbl_title::status => 1,
                );
                $query_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $where);
                $data['bloglist'] = $query_blog;
            } else {
                $data['bloglist_edit'] = $blogData;
            }
        } else {
            $where_vdo_list = array(
                Tbl_meta::type => 'vdolist',
                Tbl_meta::object_id => $title_id,
                Tbl_meta::user_id => $log_id,
            );
            /* bloglist */
            $where = array(
                Tbl_title::type => 'blogger_id',
                Tbl_title::value => $log_id,
                Tbl_title::status => 1,
            );
            $query_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $where);
            $data['bloglist'] = $query_blog;

            $where_title = array(
                Tbl_title::id => $title_id,
            );
        }


        /* get video list */
        $order = (!empty($_GET['order']) ? $_GET['order'] : '');
        if (!empty($order)) {
            $oderby = Tbl_meta::id . ' ' . $order;
        } else {
            $oderby = '';
        }
        $query_vdo_list = $this->Mod_general->select(Tbl_meta::tblname, '*', $where_vdo_list, $oderby);
        $data['vdolist'] = $query_vdo_list;
        $query_vdo_title = $this->Mod_general->select(Tbl_title::tblname, '*', $where_title);
        foreach ($query_vdo_title as $value_title) {
            $valueTitle = $value_title->{Tbl_title::title};
            $valuekhmer_title = $value_title->{Tbl_title::khmer_title};
            $valueTitleID = $value_title->{Tbl_title::id};
            $valueTitleThumb = $value_title->{Tbl_title::image};
            $valueTitle_content = $value_title->{Tbl_title::content};
            $valueTitle_label = $value_title->{Tbl_title::value};
        }
        $data['vdotitle'] = (!empty($valueTitle) ? $valueTitle : '');
        $data['khmertitle'] = (!empty($valuekhmer_title) ? $valuekhmer_title : '');
        $data['vdo_title_id'] = (!empty($valueTitleID) ? $valueTitleID : '');
        $data['Thumbnail'] = (!empty($valueTitleThumb) ? $valueTitleThumb : '');
        $data['content'] = (!empty($valueTitle_content) ? $valueTitle_content : '');
        /* end get video list */

        $where_cat_ob = array(
            Tbl_cat_term::term_group => 'category',
        );
        $query_song_product = $this->Mod_general->select(Tbl_cat_term::TBL, '*', $where_cat_ob);
        $pro = array();
        foreach ($query_song_product as $value) {
            $pro[] = $value->{Tbl_cat_term::name};
        }
        if ($urls != 'edit') {
            if (!empty($valueTitle_label)) {
                $data['current_cat'] = $valueTitle_label;
            }
        }
        $product = implode("','", $pro);
        $data['js'] = array(
            'themes/layout/blueone/plugins/validation/jquery.validate.min.js',
            'themes/layout/blueone/plugins/pickadate/picker.js',
            'themes/layout/blueone/plugins/pickadate/picker.date.js',
            'themes/layout/blueone/plugins/pickadate/picker.time.js',
        );
        $ajax = base_url() . 'post/ajax';
        $data['addJsScript'] = array(
            "$(document).ready(function(){
                $.validator.addClassRules('required', {
                required: true
                }); 
                $( \"#datepicker\" ).hide().datepicker({ dateFormat: \"yy-mm-dd\"});
                $( \".hasdate\" ).click(function(){var c = $(this).val();
                    if(c==2){
                        $( \"#datepicker\" ).show();
                    } else {
                         $( \"#datepicker\" ).hide();
                    }
                });
                $(\"#blogCat\").change(function() {
                    var count = $(\".listofsong\").length;
                    $(\"#countdiv\").text(\"ចំនួន \" + count + \" បទ\");
                    $.ajax
                    ({
                        type: \"POST\",
                        url: \"$ajax\",
                        data: {page_type: 'bloglist', catid: $(this).val()},
                        cache: false,
                        success: function(html)
                        {
                        $(\"#bloglist\").html(html);
                        } 
                    });


                });
                $(\".select2-select-02\").select2({tags:['$product']});  
            });
            $('#validate').validate();
            "
        );

        $this->load->view('post/getcode', $data);
    }

    /* end get from blogger site by Socheat Ngann */

    function userpost($urls = '', $code = '') {
        $data['title'] = 'Get from Web blog';
        $this->Mod_general->checkUser();
        $log_id = $this->session->userdata('log_id');
        $title_id = (!empty($_GET['id']) ? $_GET['id'] : '');
        $user = $this->session->userdata('username');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */


        /* form */
        if ($this->input->post('submit')) {
            $videotype = $this->input->post('videotype');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('idblog', 'idblog', 'required');
            $this->form_validation->set_rules('codetype', 'codetype', 'required');
            $this->form_validation->set_rules('videotype', 'videotype', 'required');
            if ($this->form_validation->run() == TRUE) {
                /* get permission from blog account */
                $this->load->library('zend');
                $this->zend->load('Zend/Loader');
                Zend_Loader::loadClass('Zend_Gdata');
                Zend_Loader::loadClass('Zend_Gdata_Query');
                Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
                // set credentials for ClientLogin authentication
                $user = $this->session->userdata('username');
                $pass = $this->session->userdata('blogpassword');
                /* end get permission from blog account */

                $blog_ids = $this->input->post('idblog');
                $title = $this->input->post('title');
                $vdo_title_d = $this->input->post('vdo_title_d');
                $codetype = $this->input->post('codetype');
                $jwplayerBody = $this->input->post('jwplayerBody');
                $jwplayerYtlist = $this->input->post('jwplayerYtlist');
                $onyoutbueBody = $this->input->post('onyoutbueBody');
                $label_add = $this->input->post('labeladd');
                $label = $this->input->post('label');

                /* get label */
                if (!empty($label)) {
                    $label = implode(",", $label) . $label_add;
                } else {
                    $label = $label_add;
                }
                /* end get label */

                /* body text */
                switch ($codetype) {
                    case 'jwplayer':
                        $bodytext = $jwplayerBody;
                        break;
                    case 'ytlist':
                        $bodytext = $jwplayerYtlist;
                        break;
                    case 'onyoutbue':
                        $bodytext = $onyoutbueBody;
                        break;
                }
                /* end body text */
                /* ADD TO BLOG */
                try {
                    // perform login 
                    // initialize service object
                    $client = Zend_Gdata_ClientLogin::getHttpClient(
                                    $user, $pass, 'blogger');
                    $service = new Zend_Gdata($client);

                    // save entry to server
                    // return unique id for new post
                    foreach ($blog_ids as $key => $blog_id) {
                        /* EDIT POST */
                        if ($urls == 'edit' && !empty($code)) {
                            /* get post id */
                            $data_post_id = array(
                                Tbl_meta::object_id => $vdo_title_d,
                                Tbl_meta::type => 'by_post_id',
                                Tbl_meta::user_id => $log_id,
                                Tbl_meta::value => $blog_id,
                            );
                            //var_dump($data_post_id);
                            $GetPost_id = $this->Mod_general->select(Tbl_meta::tblname, array(Tbl_meta::key), $data_post_id);
                            if (!empty($GetPost_id[0])) {
                                $pid = $GetPost_id[0]->{Tbl_meta::key};
                            } else {
                                $pid = '';
                            }
                            /* end get post id */

                            $uri = 'http://www.blogger.com/feeds/' . $blog_id . '/posts/default/' . $pid;
                            $response = $service->get($uri);
                            $entry = new Zend_Gdata_App_Entry($response->getBody());
                            $entry->title = $service->newTitle($title);

                            $str = stripslashes($bodytext);
                            $str = $str = str_replace("<br />", "\n", $str);
                            $entry->content = $service->newContent($str);
                            $entry->content->setType('html');

                            //$tags = $_POST['label'];
                            $tags = explode(",", $label);
                            if (is_array($tags)) {
                                $labels = array();
                                foreach ($tags as $tag) {
                                    /* insert Category to DB */
                                    $where_category = array(
                                        Tbl_cat_term::name => $tag,
                                    );
                                    $Ch_categorys = $this->Mod_general->select(Tbl_cat_term::TBL, '*', $where_category);
                                    foreach ($Ch_categorys as $value) {
                                        $nameCats = $value->{Tbl_cat_term::name};
                                    }
                                    if (empty($nameCats)) {
                                        $CatData = array(
                                            Tbl_cat_term::name => trim($tag),
                                            Tbl_cat_term::slug => strtolower(str_replace(' ', '-', trim($tag))),
                                            Tbl_cat_term::term_group => 'category',
                                        );
                                        $add_category = $this->Mod_general->insert(Tbl_cat_term::TBL, $CatData);
                                        $CatData_tax = array(
                                            Tbl_cat_term_taxonomy::term_id => $add_category,
                                            Tbl_cat_term_taxonomy::taxonomy => 'category',
                                            Tbl_cat_term_taxonomy::parent => 0,
                                        );
                                        $add_category_tax = $this->Mod_general->insert(Tbl_cat_term_taxonomy::TBL, $CatData_tax);
                                    }
                                    /* end insert Category to DB */


                                    /* continue movies category */
                                    if ($videotype == '1') {
                                        $where_category_cont = array(
                                            Tbl_cat_term_relationships::object_id => $vdo_title_d,
                                            Tbl_cat_term_relationships::name => $tag,
                                        );
                                        $Ch_category_cont = $this->Mod_general->select(Tbl_cat_term_relationships::TBL, '*', $where_category_cont);
                                        if (empty($Ch_category_cont)) {
                                            $CatData_tax = array(
                                                Tbl_cat_term_relationships::object_id => trim($vdo_title_d),
                                                Tbl_cat_term_relationships::name => trim($tag),
                                            );
                                            $add_category = $this->Mod_general->insert(Tbl_cat_term_relationships::TBL, $CatData_tax);
                                        }
                                    } else {
                                        $CatData_tax_del = array(
                                            Tbl_cat_term_relationships::object_id => trim($vdo_title_d)
                                        );
                                        $add_category = $this->Mod_general->delete(Tbl_cat_term_relationships::TBL, $CatData_tax_del);
                                    }
                                    /* enc continue movies category */

                                    //$labels[] = $service->newCategory(trim($tag), 'http:www.blogger.com/atom/ns#');
                                    $labels[] = new Zend_Gdata_App_Extension_Category(trim($tag), 'http://www.blogger.com/atom/ns#');
                                }
                                /* Adding tags to post */
                                $entry->setCategory($labels);
                            }
                            $service->updateEntry($entry);

                            $where_title = array(
                                Tbl_title::type => 'vdolist',
                                Tbl_title::id => $vdo_title_d,
                            );
                            $data_title = array(
                                Tbl_title::title => $title,
                            );
                            $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_title, $where_title);
                            /* END EDIT POST */
                        } else {
                            // create a new entry object
                            // populate it with user input      
                            $entry = $service->newEntry();
                            $entry->title = $service->newTitle($title);

                            $str = stripslashes($bodytext);
                            $str = $str = str_replace("<br />", "\n", $str);
                            $entry->content = $service->newContent($str);
                            $entry->content->setType('html');

                            //$tags = $_POST['label'];
                            $tags = explode(",", $label);
                            if (is_array($tags)) {
                                $labels = array();
                                foreach ($tags as $tag) {
                                    /* insert Category to DB */
                                    $where_category = array(
                                        Tbl_cat_term::name => $tag,
                                    );
                                    $Ch_categorys = $this->Mod_general->select(Tbl_cat_term::TBL, '*', $where_category);
                                    foreach ($Ch_categorys as $value) {
                                        $nameCat = $value->{Tbl_cat_term::name};
                                    }
                                    if (empty($nameCat)) {
                                        $CatData = array(
                                            Tbl_cat_term::name => trim($tag),
                                            Tbl_cat_term::slug => strtolower(str_replace(' ', '-', trim($tag))),
                                            Tbl_cat_term::term_group => 'category',
                                        );
                                        $add_category = $this->Mod_general->insert(Tbl_cat_term::TBL, $CatData);
                                        $CatData_tax = array(
                                            Tbl_cat_term_taxonomy::term_id => $add_category,
                                            Tbl_cat_term_taxonomy::taxonomy => 'category',
                                            Tbl_cat_term_taxonomy::parent => 0,
                                        );
                                        $add_category_tax = $this->Mod_general->insert(Tbl_cat_term_taxonomy::TBL, $CatData_tax);
                                    }
                                    /* end insert Category to DB */

                                    /* continue movies category */
                                    if ($videotype == '1') {
                                        /* add new title */
//                                        $data_title = array(
//                                            Tbl_title::title => $title,
//                                            Tbl_title::type => 'vdolist',
//                                        );
//                                        $vdo_title_d = $this->Mod_general->insert(Tbl_title::tblname, $data_title);
                                        /* end add new title */

                                        $where_category_cont = array(
                                            Tbl_cat_term_relationships::object_id => $vdo_title_d,
                                            Tbl_cat_term_relationships::name => $tag,
                                        );
                                        $Ch_category_cont = $this->Mod_general->select(Tbl_cat_term_relationships::TBL, '*', $where_category_cont);
                                        if (empty($Ch_category_cont)) {
                                            $CatData_tax = array(
                                                Tbl_cat_term_relationships::object_id => trim($vdo_title_d),
                                                Tbl_cat_term_relationships::name => trim($tag),
                                            );
                                            $add_category = $this->Mod_general->insert(Tbl_cat_term_relationships::TBL, $CatData_tax);
                                        }
                                    } else {
                                        $CatData_tax_del = array(
                                            Tbl_cat_term_relationships::object_id => trim($vdo_title_d)
                                        );
                                        $add_category = $this->Mod_general->delete(Tbl_cat_term_relationships::TBL, $CatData_tax_del);
                                    }
                                    /* enc continue movies category */

                                    //$labels[] = $service->newCategory(trim($tag), 'http:www.blogger.com/atom/ns#');
                                    $labels[] = new Zend_Gdata_App_Extension_Category(trim($tag), 'http://www.blogger.com/atom/ns#');
                                }
                                /* Adding tags to post */
                                $entry->setCategory($labels);
                            }

                            $uri = 'http://www.blogger.com/feeds/' . $blog_id . '/posts/default';
                            $post_blog_id = $this->insertNewPost($entry, $uri, $service);
                            /* update VDO if not finish */
                            if ($videotype == '1') {
                                $data_post_id = array(
                                    Tbl_meta::object_id => $vdo_title_d,
                                    Tbl_meta::type => 'by_post_id',
                                    Tbl_meta::user_id => $log_id,
                                    Tbl_meta::value => $blog_id,
                                    Tbl_meta::key => $post_blog_id,
                                );
                                $dataPostID = $this->Mod_general->insert(Tbl_meta::tblname, $data_post_id);
                            }
                        }
                    }
                } catch (Exception $e) {
                    $e->getMessage();
                    //die('ERROR:' . $e->getMessage());
                }
                /* END ADD TO BLOG */


                /* update VDO if not finish */
                if ($videotype == '1') {
                    $where_title = array(
                        Tbl_title::type => 'vdolist',
                        Tbl_title::object_id => $log_id,
                        Tbl_title::id => $vdo_title_d,
                    );
                    $data_title = array(
                        Tbl_title::title => $title,
                        Tbl_title::status => 0,
                        Tbl_title::value => implode(",", $blog_ids),
                    );
                    $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_title, $where_title);
                } elseif ($videotype == '0') {
                    $where_title_del = array(
                        Tbl_title::object_id => $log_id,
                        Tbl_title::id => $vdo_title_d,
                    );
                    $query_blog = $this->Mod_general->delete(Tbl_title::tblname, $where_title_del);
                    $where_contine_del = array(
                        Tbl_meta::type => 'vdolist',
                        Tbl_meta::object_id => $vdo_title_d,
                        Tbl_meta::user_id => $log_id,
                    );
                    $query_blog = $this->Mod_general->delete(Tbl_meta::tblname, $where_contine_del);
                }
                /* end update VDO if not finish */
            }
            if ($videotype == '1') {
                redirect(base_url() . 'post/movies?id=' . $code);
            } else {
                redirect(base_url() . 'post/movies');
            }
        }
        /* end form */

        /* show to view */
        $data_sel = array(
            'mo_type' => 'blog_category'
        );
        $dataBlogCate = $this->Mod_general->select('title', '*', $data_sel);
        $data['blogcatlist'] = $dataBlogCate;

        /* EDIT */
        if ($urls == 'edit' && !empty($code)) {
            /* get video list */
            $where_vdo_list = array(
                Tbl_meta::object_id => $code,
                Tbl_meta::user_id => $log_id,
            );
            /* bloglist */
            $where = array(
                Tbl_title::type => 'vdolist',
                Tbl_title::object_id => $log_id,
            );
            $query_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $where);
            $i = 0;
            $blogData = array();
            foreach ($query_blog as $value_blog_list) {
                $blogID = $value_blog_list->{Tbl_title::value};
                $blogID = explode(',', $blogID);
                foreach ($blogID as $key => $IDblog) {
                    $getIDblog = array(
                        Tbl_title::object_id => $IDblog,
                        Tbl_title::value => $log_id,
                        Tbl_title::type => 'blogger_id');
                    $query_blog_id = $this->Mod_general->select(Tbl_title::tblname, '*', $getIDblog);
                    foreach ($query_blog_id as $data_Blog) {
                        $i++;
                        $blogData[$i][Tbl_title::title] = $data_Blog->{Tbl_title::title};
                        $blogData[$i][Tbl_title::object_id] = $data_Blog->{Tbl_title::object_id};
                    }
                }
            }
            $data['bloglist'] = $blogData;
            $data['editaction'] = 1;


            /* show title */
            $where_title = array(
                Tbl_title::type => 'vdolist',
                Tbl_title::object_id => $log_id,
                Tbl_title::id => $code,
            );

            /* show current categories */
            $where_cur_cat = array(
                Tbl_cat_term_relationships::object_id => $code,
            );
            $query_cur_cat = $this->Mod_general->select(Tbl_cat_term_relationships::TBL, '*', $where_cur_cat);
            $current_cat = array();
            foreach ($query_cur_cat as $value_cur_cat) {
                $current_cat[] = $value_cur_cat->{Tbl_cat_term_relationships::name};
            }
            $data['current_cat'] = implode(", ", $current_cat);
            /* end show current categories */
        } else {
            $where_vdo_list = array(
                Tbl_meta::type => 'vdolist',
                Tbl_meta::object_id => $title_id,
                Tbl_meta::user_id => $log_id,
            );
            /* bloglist */
            $where = array(
                Tbl_title::type => 'blogger_id',
                Tbl_title::value => $log_id,
                Tbl_title::status => 1,
            );
            $query_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $where);
            $data['bloglist'] = $query_blog;

            $where_title = array(
                Tbl_title::id => $title_id,
            );
        }


        /* get video list */
        $query_vdo_list = $this->Mod_general->select(Tbl_meta::tblname, '*', $where_vdo_list);
        $data['vdolist'] = $query_vdo_list;
        $query_vdo_title = $this->Mod_general->select(Tbl_title::tblname, '*', $where_title);
        foreach ($query_vdo_title as $value_title) {
            $valueTitle = $value_title->{Tbl_title::title};
            $valueTitleID = $value_title->{Tbl_title::id};
            $valueTitleThumb = $value_title->{Tbl_title::image};
        }
        $data['vdotitle'] = (!empty($valueTitle) ? $valueTitle : '');
        $data['vdo_title_id'] = (!empty($valueTitleID) ? $valueTitleID : '');
        $data['Thumbnail'] = (!empty($valueTitleThumb) ? $valueTitleThumb : '');
        /* end get video list */

        $where_cat_ob = array(
            Tbl_cat_term::term_group => 'category',
        );
        $query_song_product = $this->Mod_general->select(Tbl_cat_term::TBL, '*', $where_cat_ob);
        $pro = array();
        foreach ($query_song_product as $value) {
            $pro[] = $value->{Tbl_cat_term::name};
        }
        $product = implode("','", $pro);

        $data['js'] = array(
            'themes/layout/blueone/plugins/validation/jquery.validate.min.js',
        );
        $data['addJsScript'] = array(
            "$(document).ready(function(){
                $.validator.addClassRules('required', {
                required: true
                }); 
                
                $(\"#blogCat\").change(function() {
                    var count = $(\".listofsong\").length;
                    $(\"#countdiv\").text(\"ចំនួន \" + count + \" បទ\");
                    $.ajax
                    ({
                        type: \"POST\",
                        url: \"ajax\",
                        data: {page_type: 'bloglist', catid: $(this).val()},
                        cache: false,
                        success: function(html)
                        {
                        $(\"#bloglist\").html(html);
                        } 
                    });


                });
                $(\".select2-select-02\").select2({tags:['$product']});  
            });
            $('#validate').validate();
            "
        );

        $this->load->view('post/getcode', $data);
    }

    /* end get from blogger site by Socheat Ngann */

    function usertopost() {
        $data['title'] = 'User post to Movies site';
        $this->Mod_general->checkUser();
        $data['listdata'] = $this->Mod_general->getuser('*');
        /* catgory */
        /* show to view */
        $data_sel = array(
            'mo_type' => 'blog_category'
        );
        $dataBlogCate = $this->Mod_general->select('title', '*', $data_sel);
        $data['blogcatlist'] = $dataBlogCate;
        /* end catgory */

        /* form */
        if ($this->input->post('itemid')) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('itemid', 'itemid', 'required');
            if ($this->form_validation->run() == TRUE) {
                $id = $this->input->post('itemid');
                $bloginCat = $this->input->post('bloginCat');
                if (!empty($id)) {
                    $id_cat = implode(',', $id);
                    $where_video = array(
                        Tbl_title::type => 'config',
                        Tbl_title::title => 'user_to_post',
                    );
                    $data_video = array(
                        Tbl_title::value => $id_cat,
                    );
                    $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_video, $where_video);
                }
            }
            redirect(base_url() . 'post/blogpost');
        }
        /* end form */

        $this->load->view('post/usertopost', $data);
    }

    function post_to_website($title, $where, $label = '', $type = 'yt', $edit = 0, $copy = '', $copyright, $imagepost = '', $orderby = '', $search_title = '') {
        $log_id = $this->session->userdata('log_id');
        /* Video list */
        $where_vdo_list = array(
            Tbl_meta::type => 'vdolist',
            Tbl_meta::object_id => $where,
            Tbl_meta::user_id => $log_id,
        );
        if (!empty($orderby)) {
            $order_list = Tbl_meta::id . " $orderby";
        } else {
            $order_list = '';
        }
        $query_vdo_list = $this->Mod_general->select(Tbl_meta::tblname, '*', $where_vdo_list, $order_list);
        $query_get_image = $this->Mod_general->select(Tbl_title::tblname, array(Tbl_title::image), array(Tbl_title::id => $where, Tbl_title::type => 'vdolist'));
        if (!empty($query_get_image)) {
            $image_url = $query_get_image[0]->{Tbl_title::image};
        }
        /* end Video list */
        if (!empty($query_vdo_list)) {
            $st_id = $query_vdo_list[0]->{Tbl_meta::value};
            /* check before add */
            //$obCheck = mysql_query("SELECT uniq_id FROM vdo_videos WHERE video_title = '" . $title . "'");
            if (!empty($search_title)) {
                $seach_titles = $search_title;
            } else {
                $seach_titles = $title;
            }
            $seach_titles = str_replace('+', ' ', $seach_titles);
            $check_unig = $this->Mod_general->like3('vdo_videos', array('uniq_id', 'id'), array('video_title' => $seach_titles));
            foreach ($check_unig as $value_ch) {
                $uniq_id = $value_ch->uniq_id;
                $video_id = $value_ch->id;
            }
            /* image */
            if (preg_match('/ytimg/', $image_url)) {
                $image_id = '';
            } else if (!empty($image_url)) {
                $image_id = $image_url;
                $image_id = str_replace("https:", "http:", $image_url);
            } else {
                $image_id = '';
            }
            if (preg_match('/ytimg/', $imagepost)) {
                $imagepost = '';
            }
            $image_id = (!empty($imagepost) ? $imagepost : $image_id);

            /* end image */
            /* label */
            $label = explode(",", $label);
            $labels = array();
            foreach ($label as $cate) {
                if ($cate == 'Movies') {
                    //$results = mysql_query("SELECT id FROM vdo_categories WHERE name = 'Movies'");
                    $get_id = $this->Mod_general->select3('vdo_categories', array('id'), array('name' => 'Movies'));
                } else if ($cate == 'News') {
                    $get_id = $this->Mod_general->select3('vdo_categories', array('id'), array('name' => 'News'));
                } else {
                    $get_id = $this->Mod_general->select3('vdo_categories', array('id'), array('name' => trim($cate)));
                }
                foreach ($get_id as $value_id) {
                    $labels[] = $value_id->id;
                }
            }
            if (!empty($copyright)) {
                $get_id = $this->Mod_general->select3('vdo_categories', array('id'), array('name' => 'mymovie'));
                if (!empty($get_id)) {
                    array_push($labels, $get_id[0]->id);
                }
            }

            if (!empty($labels)) {
                $label = implode(',', $labels);
            } else {
                $label = '';
            }
            /* end label */
            if ($query_vdo_list[0]->{Tbl_meta::name} == 'yt' || $query_vdo_list[0]->{Tbl_meta::name} == 'yt_single') {
                $source_id = 3;
                $vid_type = 'yt';
            } elseif ($query_vdo_list[0]->{Tbl_meta::name} == 'iframe' || $query_vdo_list[0]->{Tbl_meta::name} == 'vimeo' || $query_vdo_list[0]->{Tbl_meta::name} == 'dailymotion' || $query_vdo_list[0]->{Tbl_meta::name} == 'docs.google') {
                $source_id = 62;
                $vid_type = 'iframe';
            } elseif ($query_vdo_list[0]->{Tbl_meta::name} == 'fbvid') {
                $source_id = 74;
                $vid_type = 'iframe';
            } else {
                $source_id = 2;
                $vid_type = 'file';
            }
            /* =================== UPDATE VIDEO ================ */
            if (!empty($value_ch)) {
                //$data = "UPDATE vdo_videos SET added='" . time() . "' WHERE uniq_id='" . $uniq_ids . "'";
                $video_details = array(
                    'uniq_id' => $uniq_id,
                    'video_title' => $title,
                    'description' => ' ',
                    'yt_id' => $st_id,
                    'yt_length' => '0',
                    'category' => $label,
                    'submitted' => 'admin',
                    'source_id' => $source_id,
                    'language' => '1',
                    'age_verification' => '',
                    'url_flv' => $st_id,
                    'yt_thumb' => $image_id,
                    'featured' => 0,
                    'restricted' => 0,
                    'added' => time(),
                    'allow_comments' => 1);
                $update_post = $this->Mod_general->update3('vdo_videos', $video_details, array('uniq_id' => $uniq_id));
                if ($query_vdo_list[0]->{Tbl_meta::name} == 'yt' || $query_vdo_list[0]->{Tbl_meta::name} == 'yt_single' || $query_vdo_list[0]->{Tbl_meta::name} == 'iframe') {
                    /* delete list */
                    $delete_list = $this->Mod_general->delete3('vdo_meta', array('item_id' => $uniq_id));
                    /* add video list to meta */
                    foreach ($query_vdo_list as $value_vdo_list) {
                        $data_list = array(
                            "item_id" => $uniq_id,
                            "meta_key" => $vid_type,
                            "meta_value" => $value_vdo_list->{Tbl_meta::value});
                        $vdo_title_d = $this->Mod_general->insert3('vdo_meta', $data_list);
                    }
                    /* end add video list to meta */
                } else if ($query_vdo_list[0]->{Tbl_meta::name} == 'embed') {
                    $delete_list = $this->Mod_general->delete3('vdo_embed_code', array('uniq_id' => $uniq_id));
                    $vid = $query_vdo_list[0]->{Tbl_meta::value};
                    $data_list = array(
                        "uniq_id" => $uniq_id,
                        "embed_code" => trim($vid),
                    );
                    $vdo_title_d = $this->Mod_general->insert3('vdo_embed_code', $data_list);
                } else if ($query_vdo_list[0]->{Tbl_meta::name} == 'docs.google') {
                    /* delete list */
                    $delete_list = $this->Mod_general->delete3('vdo_meta', array('item_id' => $uniq_id));
                    /* add video list to meta */
                    $i = 0;
                    foreach ($query_vdo_list as $value_vdo_list) {
                        $i++;
                        if ($i == 1) {
                            $start = '';
                        } else {
                            $start = '?start=1';
                        }
                        $data_list = array(
                            "item_id" => $uniq_id,
                            "meta_key" => $vid_type,
                            "meta_value" => 'https://docs.google.com/file/d/' . $value_vdo_list->{Tbl_meta::value} . '/preview' . $start);
                        $vdo_title_d = $this->Mod_general->insert3('vdo_meta', $data_list);
                    }
                    /* end add video list to meta */
                } else if ($query_vdo_list[0]->{Tbl_meta::name} == 'dailymotion') {
                    /* delete list */
                    $delete_list = $this->Mod_general->delete3('vdo_meta', array('item_id' => $uniq_id));
                    /* add video list to meta */
                    $i = 0;
                    foreach ($query_vdo_list as $value_vdo_list) {
                        $i++;
                        if ($i == 1) {
                            $start = '?autoPlay=0&hideInfos=0';
                        } else {
                            $start = '?autoPlay=1&hideInfos=0';
                        }
                        $data_list = array(
                            "item_id" => $uniq_id,
                            "meta_key" => $vid_type,
                            "meta_value" => 'http://www.dailymotion.com/embed/video/' . $value_vdo_list->{Tbl_meta::value} . $start);
                        $vdo_title_d = $this->Mod_general->insert3('vdo_meta', $data_list);
                    }
                    /* end add video list to meta */
                } else if ($query_vdo_list[0]->{Tbl_meta::name} == 'vimeo') {
                    /* delete list */
                    $delete_list = $this->Mod_general->delete3('vdo_meta', array('item_id' => $uniq_id));
                    /* add video list to meta */
                    $i = 0;
                    foreach ($query_vdo_list as $value_vdo_list) {
                        $i++;
                        if ($i == 1) {
                            $start = '&autoplay=0';
                        } else {
                            $start = '&autoplay=1';
                        }
                        $data_list = array(
                            "item_id" => $uniq_id,
                            "meta_key" => $vid_type,
                            "meta_value" => 'http://player.vimeo.com/video/' . $value_vdo_list->{Tbl_meta::value} . '?color=00adef&api=1&player_id=player_0' . $start);
                        $vdo_title_d = $this->Mod_general->insert3('vdo_meta', $data_list);
                    }
                    /* end add video list to meta */
                }
            } else {
                if ($query_vdo_list[0]->{Tbl_meta::name} == 'yt' || $query_vdo_list[0]->{Tbl_meta::name} == 'yt_single') {
                    $source_id = 3;
                    $vid_type = 'yt';
                } elseif ($query_vdo_list[0]->{Tbl_meta::name} == 'iframe' || $query_vdo_list[0]->{Tbl_meta::name} == 'vimeo' || $query_vdo_list[0]->{Tbl_meta::name} == 'dailymotion' || $query_vdo_list[0]->{Tbl_meta::name} == 'docs.google') {
                    $source_id = 62;
                    $vid_type = 'iframe';
                } elseif ($query_vdo_list[0]->{Tbl_meta::name} == 'fbvid') {
                    $source_id = 74;
                    $vid_type = 'iframe';
                } else {
                    $source_id = 2;
                    $vid_type = 'file';
                }
                /* =================== ADD NEW VIDEO ================ */

                if (empty($edit)) {
                    /* add new Movies */
                    $str = time();
                    $str = md5($str);
                    $uniq_id = substr($str, 0, 9);
                    $video_details = array(
                        'uniq_id' => $uniq_id,
                        'video_title' => $title,
                        'description' => ' ',
                        'yt_id' => $st_id,
                        'yt_length' => '0',
                        'category' => $label,
                        'submitted' => 'admin',
                        'source_id' => $source_id,
                        'language' => '1',
                        'age_verification' => '',
                        'url_flv' => $st_id,
                        'yt_thumb' => $image_id,
                        'featured' => 0,
                        'restricted' => 0,
                        'added' => time(),
                        'allow_comments' => 1);
                    $vdo_title_d = $this->Mod_general->insert3('vdo_videos', $video_details);
                    $video_id = $vdo_title_d;
                    /* end add new Movies */
                    if ($query_vdo_list[0]->{Tbl_meta::name} == 'yt' || $query_vdo_list[0]->{Tbl_meta::name} == 'yt_single' || $query_vdo_list[0]->{Tbl_meta::name} == 'iframe' || $query_vdo_list[0]->{Tbl_meta::name} == 'fbvid') {
                        /* add video list to meta */
                        foreach ($query_vdo_list as $value_vdo_list) {
                            $data_list_movive = array(
                                "item_id" => $uniq_id,
                                "meta_key" => $vid_type,
                                "meta_value" => $value_vdo_list->{Tbl_meta::value});
                            $vdo_title_meta = $this->Mod_general->insert3('vdo_meta', $data_list_movive);
                        }
                        /* end add video list to meta */
                    } else if ($query_vdo_list[0]->{Tbl_meta::name} == 'embed') {
                        $delete_list = $this->Mod_general->delete3('vdo_embed_code', array('uniq_id' => $uniq_id));
                        $vid = $query_vdo_list[0]->{Tbl_meta::value};
                        $data_list = array(
                            "uniq_id" => $uniq_id,
                            "embed_code" => trim($vid),
                        );
                        $vdo_title_d = $this->Mod_general->insert3('vdo_embed_code', $data_list);
                    } else if ($query_vdo_list[0]->{Tbl_meta::name} == 'docs.google') {
                        /* delete list */
                        $delete_list = $this->Mod_general->delete3('vdo_meta', array('item_id' => $uniq_id));
                        /* add video list to meta */
                        $i = 0;
                        foreach ($query_vdo_list as $value_vdo_list) {
                            $i++;
                            if ($i == 1) {
                                $start = '';
                            } else {
                                $start = '?start=1';
                            }
                            $data_list = array(
                                "item_id" => $uniq_id,
                                "meta_key" => $vid_type,
                                "meta_value" => 'https://docs.google.com/file/d/' . $value_vdo_list->{Tbl_meta::value} . '/preview' . $start);
                            $vdo_title_d = $this->Mod_general->insert3('vdo_meta', $data_list);
                        }
                        /* end add video list to meta */
                    } else if ($query_vdo_list[0]->{Tbl_meta::name} == 'dailymotion') {
                        /* delete list */
                        $delete_list = $this->Mod_general->delete3('vdo_meta', array('item_id' => $uniq_id));
                        /* add video list to meta */
                        $i = 0;
                        foreach ($query_vdo_list as $value_vdo_list) {
                            $i++;
                            if ($i == 1) {
                                $start = '?autoPlay=0&hideInfos=0';
                            } else {
                                $start = '?autoPlay=1&hideInfos=0';
                            }
                            $data_list = array(
                                "item_id" => $uniq_id,
                                "meta_key" => $vid_type,
                                "meta_value" => 'http://www.dailymotion.com/embed/video/' . $value_vdo_list->{Tbl_meta::value} . $start);
                            $vdo_title_d = $this->Mod_general->insert3('vdo_meta', $data_list);
                        }
                        /* end add video list to meta */
                    } else if ($query_vdo_list[0]->{Tbl_meta::name} == 'vimeo') {
                        /* delete list */
                        $delete_list = $this->Mod_general->delete3('vdo_meta', array('item_id' => $uniq_id));
                        /* add video list to meta */
                        $i = 0;
                        foreach ($query_vdo_list as $value_vdo_list) {
                            $i++;
                            if ($i == 1) {
                                $start = '&autoplay=0';
                            } else {
                                $start = '&autoplay=1';
                            }
                            $data_list = array(
                                "item_id" => $uniq_id,
                                "meta_key" => $vid_type,
                                "meta_value" => 'http://player.vimeo.com/video/' . $value_vdo_list->{Tbl_meta::value} . '?color=00adef&api=1&player_id=player_0' . $start);
                            $vdo_title_d = $this->Mod_general->insert3('vdo_meta', $data_list);
                        }
                        /* end add video list to meta */
                    }
                }
            }
            /* end check before add */
            /* video copy right */
            if (!empty($video_id)) {
                if ($copy == 'onyoutbue' || $copy == 'onyoutbue1') {
                    $get_id_copy = $this->Mod_general->select3('vdo_meta', array('id'), array('item_id' => $video_id, 'meta_key' => 'copy'));
                    if (empty($get_id_copy)) {
                        $data_list_copy = array(
                            "item_id" => $video_id,
                            "meta_key" => "copy",
                            "item_type" => 1,
                            "meta_value" => 1);
                        $vdo_title_copy = $this->Mod_general->insert3('vdo_meta', $data_list_copy);
                    }
                } else {
                    $delete_list_copy = $this->Mod_general->delete3('vdo_meta', array('item_id' => $video_id, "meta_key" => "copy"));
                }
            }
            /* end video copy right */
        }
    }

    public function magazine() {
        $data['title'] = 'Post magazine';
        $this->Mod_general->checkUser();
        $log_id = $this->session->userdata('log_id');
        $user = $this->session->userdata('username');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */

        $this->load->view('post/magazine', $data);
    }

    public function news() {
        $data['title'] = 'New post';
        $this->Mod_general->checkUser();
        $log_id = $this->session->userdata('log_id');
        $user = $this->session->userdata('username');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */
        /* form */
        $create = (!empty($_GET['create']) ? $_GET['create'] : '');
        if (@$create == 1) {
            $data_title = array(
                Tbl_title::title => ' ',
                Tbl_title::type => 'vdolist',
                Tbl_title::object_id => $log_id,
                Tbl_title::image => ' ',
            );
            $post_id = $this->Mod_general->insert(Tbl_title::tblname, $data_title);
            /* end insert title db */
            redirect(base_url() . 'post/getcode?id=' . $post_id);
            exit();
        }
        /* end form */
        /* form */
        if ($this->input->post('url_id')) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('url_id', 'url_id', 'required');
            if ($this->form_validation->run() == TRUE) {
                $xmlurl = $this->input->post('url_id');
                $imageid = $this->input->post('imageid');
                $code = $this->get_from_url_id($xmlurl, $imageid);
                if (!empty($code)) {
                    /* insert to DB */
                    $data_title = array(
                        Tbl_title::title => $code['title'],
                        Tbl_title::type => 'vdolist',
                        Tbl_title::object_id => $log_id,
                        Tbl_title::image => (!empty($code['image']) ? $code['image'] : ' '),
                        Tbl_title::khmer_title => @$code['limon_title'],
                        Tbl_title::content => $code['content'],
                    );
                    $post_id = $this->Mod_general->insert(Tbl_title::tblname, $data_title);
                }
            }
            if (!empty($post_id)) {
                //redirect(base_url() . 'post/getcode/edit/' . $post_id);
                redirect(base_url() . 'post/getcode?id=' . $post_id);
            } else {
                redirect(base_url() . 'post/news?create=2');
            }
        }
        /* end form */
        $this->load->view('post/news', $data);
    }

    function insertNewPost($entry, $uri, $service) {
        $response = $service->insertEntry($entry, $uri);
        $arr = explode('-', $response->getId());
        $id = $arr[2];
        return $id;
    }

    /* show list of movie not finish by Socheat Ngann */

    function movies() {
        $data['title'] = 'Movies list';
        $this->Mod_general->checkUser();
        $backto = base_url() . 'post/blogpassword';
        $query_blog = $this->Mod_general->blogcheck(current_url(), $backto);
        $log_id = $this->session->userdata('log_id');
        $user = $this->session->userdata('username');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */

        $data['addJsScript'] = array(
            "$('#checkAll').click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });
 $('#multidel').click(function () {
     if (!$('#itemid:checked').val()) {
            alert('please select one');
            return false;
    } else {
            return confirm('Do you want to delete all?');
    }
 });"
        );

        /* data movies */
        $filtername = $this->input->post('filtername');
        $where_movie = array(
            Tbl_title::type => 'vdolist',
            Tbl_title::object_id => $log_id,
        );
        if (!empty($filtername)) {
            $seach_movie = array(
                Tbl_title::title => $filtername,
            );
            $query_blog = $this->Mod_general->like(Tbl_title::tblname, '*', $seach_movie, $where_movie);
        }
        $this->load->library('pagination');
        $per_page = (!empty($_GET['result'])) ? $_GET['result'] : 10;
        $config['base_url'] = base_url() . 'post/movies/';
        $count_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $where_movie);
        $config['total_rows'] = count($count_blog);
        $config['per_page'] = $per_page;
        $config = $this->Mod_general->paginations($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;


        if (empty($filtername)) {
            $query_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $where_movie, "id DESC", '', $config['per_page'], $page);
        }
        $i = 1;
        $value_movie_arr = array();
        foreach ($query_blog as $value_movie) {
            $where_movie_post = array(
                Tbl_meta::type => 'vdolist',
                Tbl_meta::object_id => $value_movie->{Tbl_title::id},
            );
            $query_post_id = $this->Mod_general->select(Tbl_meta::tblname, '*', $where_movie_post);
            $value_movie_arr[$i]['count_part'] = count($query_post_id);
            $value_movie_arr[$i][Tbl_title::id] = $value_movie->{Tbl_title::id};
            $value_movie_arr[$i][Tbl_title::title] = $value_movie->{Tbl_title::title};
            $value_movie_arr[$i][Tbl_title::status] = $value_movie->{Tbl_title::status};
            $value_movie_arr[$i][Tbl_title::image] = $value_movie->{Tbl_title::image};
            $value_movie_arr[$i][Tbl_title::object_id] = $value_movie->{Tbl_title::object_id};
            $i++;
        }
        $data['bloglist'] = $value_movie_arr;


//        $query_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $where_blog, '', '', $config['per_page'], $page);
//        $data['bloglist'] = $query_blog;


        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $data["total_rows"] = count($count_blog);
        $data["results"] = $query_blog;
        $data["links"] = $this->pagination->create_links();
        /* end get pagination */
        /* end data movies */

        /* form */
        if ($this->input->post('itemid')) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('itemid', 'itemid', 'required');
            if ($this->form_validation->run() == TRUE) {
                $id = $this->input->post('itemid');
                foreach ($id as $key => $value) {
                    $table_del = Tbl_title::tblname;
                    $where_del = array(
                        Tbl_title::id => $value
                    );
                    $this->Mod_general->delete($table_del, $where_del);
                    $this->Mod_general->delete(Tbl_meta::tblname, array(Tbl_meta::object_id => $value, Tbl_meta::type => 'vdolist'));
                    $this->Mod_general->delete(Tbl_meta::tblname, array(Tbl_meta::object_id => $value, Tbl_meta::type => 'by_post_id'));
                    $this->Mod_general->delete(Tbl_cat_term_relationships::TBL, array(Tbl_cat_term_relationships::object_id => $value));
                }
            }
            redirect(base_url() . 'post/movies?deleted=1');
        }
        /* end form */


        $this->load->view('post/movies', $data);
    }

    function movieview() {
        $data['title'] = 'Movie detail';
        $this->Mod_general->checkUser();
        $movieid = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $log_id = $this->session->userdata('log_id');
        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        $data['movieid'] = $movieid;

        $where_movie = array(
            Tbl_title::type => 'vdolist',
            Tbl_title::object_id => $log_id,
            Tbl_title::id => $movieid,
        );
        $query_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $where_movie);
        $data['bloglist'] = $query_blog;

        $where_movie_post = array(
            Tbl_meta::type => 'by_post_id',
            Tbl_meta::object_id => $movieid,
        );
        $query_post_id = $this->Mod_general->select(Tbl_meta::tblname, '*', $where_movie_post);
        $data['post_id'] = $query_post_id;
        /* form */
        $idpost = $this->input->post('idpost');

        /* ======== DELETE ========= */
        if ($this->input->post('itemid')) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('itemid', 'itemid', 'required');
            if ($this->form_validation->run() == TRUE) {
                $id = $this->input->post('itemid');
                foreach ($id as $key => $value) {
                    $this->Mod_general->delete(Tbl_meta::tblname, array(Tbl_meta::id => $value, Tbl_meta::type => 'by_post_id'));
                }
            }
            redirect(base_url() . 'post/movieview/' . $movieid);
        } else
        /* ======== end DELETE ========= */
        /* ======== UPDATE ========= */
        if ($idpost) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('idpost', 'idpost', 'required');
            $this->form_validation->set_rules('movieid', 'movieid', 'required');
            $this->form_validation->set_rules('movie_pid', 'movie_pid', 'required');
            if ($this->form_validation->run() == TRUE) {
                $movies_id = $this->input->post('movieid');
                $movie_pid = $this->input->post('movie_pid');
                $update = $this->Mod_general->update(Tbl_meta::tblname, array(Tbl_meta::key => $idpost), array(Tbl_meta::id => $movie_pid));
            }
            if ($update) {
                redirect(base_url() . 'post/movieview/' . $movies_id);
            }
        }
        /* ======== end UPDATE ========= */
        /* ======== ADD NEW ========= */
        $moreid = $this->input->post('moreid');
        if ($moreid) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('moreid', 'moreid', 'required');
            $this->form_validation->set_rules('movieid', 'movieid', 'required');
            if ($this->form_validation->run() == TRUE) {
                $bid = explode('blogID=', $moreid);
                $bid = explode('#editor', $bid[1]);
                $pid = explode('postID=', $bid[1]);
                $pid = explode(';onPublishe', $pid[1]);
                $movies_id = $this->input->post('movieid');
                $where_check = array(
                    Tbl_meta::object_id => $movies_id,
                    Tbl_meta::type => 'by_post_id',
                    Tbl_meta::key => trim($pid[0]),
                    Tbl_meta::value => trim($bid[0]),
                    Tbl_meta::user_id => $log_id,
                );
                $CheckdataID = $this->Mod_general->select(Tbl_meta::tblname, '*', $where_check);
                if (empty($CheckdataID)) {
                    $data_sub = array(
                        Tbl_meta::object_id => $movies_id,
                        Tbl_meta::type => 'by_post_id',
                        Tbl_meta::key => trim($pid[0]),
                        Tbl_meta::value => trim($bid[0]),
                        Tbl_meta::name => ' ',
                        Tbl_meta::user_id => $log_id,
                    );
                    $dataPostID = $this->Mod_general->insert(Tbl_meta::tblname, $data_sub);
                }
                redirect(base_url() . 'post/movieview/' . $movies_id);
            }
        }
        /* ======== end ADD NEW ========= */
        /* end form */

        $this->load->view('post/movieview', $data);
        /* Sidebar */
    }

    /* end show list of movie not finish by Socheat Ngann */

    function bloglist() {
        $backto = base_url() . 'post/blogpassword';
        $log_id = $this->session->userdata('log_id');
        $query_blog = $this->Mod_general->blogcheck(current_url(), $backto);

        $data['title'] = 'Blog list';
        $this->Mod_general->checkUser();
        $bloginCat = (!empty($_GET['catid']) ? $_GET['catid'] : "");

        $blogCat = $this->input->post('bloginCat');
        $blog_Cat = explode('catid=', $blogCat);
        $user_id = $this->input->post('userid');
        $own_user = (!empty($user_id) ? $user_id : $log_id);
        $b_cat = (!empty($user_id) ? $user_id : $log_id);

        if (!empty($user_id)) {
            if (empty($blog_Cat[1])) {
                $where_blog = array(
                    Tbl_title::type => 'blogger_id',
                    Tbl_title::value => $user_id,
                    Tbl_title::status => '1',
                );
            } else {
                $where_blog = array(
                    Tbl_title::type => 'blogger_id',
                    Tbl_title::value => $user_id,
                    Tbl_title::status => '1',
                    Tbl_title::parent => $blog_Cat[1],
                );
            }
        } else {
            if (empty($bloginCat)) {
                $where_blog = array(
                    Tbl_title::type => 'blogger_id',
                    Tbl_title::value => $log_id,
                    Tbl_title::status => '1',
                );
            } else {
                $where_blog = array(
                    Tbl_title::type => 'blogger_id',
                    Tbl_title::value => $log_id,
                    Tbl_title::status => '1',
                    Tbl_title::parent => $bloginCat,
                );
            }
        }
        $this->load->library('pagination');
        $per_page = (!empty($_GET['result'])) ? $_GET['result'] : 20;
        $config['base_url'] = base_url() . 'post/bloglist/';
        $count_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $where_blog);
        $config['total_rows'] = count($count_blog);
        $config['per_page'] = $per_page;
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $query_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $where_blog, '', '', $config['per_page'], $page);
        $data['bloglist'] = $query_blog;


        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $data["total_rows"] = count($count_blog);
        $data["results"] = $query_blog;
        $data["links"] = $this->pagination->create_links();
        /* end get pagination */

        $data['user_list'] = $this->Mod_general->getuser('*');

        /* catgory */
        /* show to view */
        $data_sel = array(
            'mo_type' => 'blog_category',
            Tbl_title::object_id => $log_id,
        );
        $dataBlogCate = $this->Mod_general->select('title', '*', $data_sel);
        $data['blogcatlist'] = $dataBlogCate;
        /* end catgory */

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */

        if (!empty($_GET['add'])) {
            $data['addJsScript'] = array(
                'var success = generate(\'success\');',
                'setTimeout(function () {
            $.noty.setText(success.options.id, \'Add blogs success!\');
        }, 1000);',
                'setTimeout(function () {
                        $.noty.closeAll();
                    }, 4000);'
            );
        }
        $data['addJsScript'] = array(
            "$('#checkAll').click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });
 $('#multidel').click(function () {
     if (!$('#itemid:checked').val()) {
            alert('please select one');
            return false;
    } else {
            return confirm('Do you want to delete all?');
    }
 });
 $('#multiadd').click(function () {
     if (!$('#itemid:checked').val()) {
            alert('please select one');
            return false;
    } else {
            return confirm('Do you want to Add to this user?');
    }
 });"
        );

        /* form */
        if ($this->input->post('itemid')) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('itemid', 'itemid', 'required');
            if ($this->form_validation->run() == TRUE) {
                $id = $this->input->post('itemid');
                $bloginCat = $this->input->post('bloginCat');
                foreach ($id as $key => $value) {
                    $blog = explode('[=]', $value);
                    $blogtitle = explode('[blogid]', $blog[1]);
                    if ($this->input->post('delete')) {
                        $table_del = Tbl_title::tblname;
                        $where_del = array(
                            Tbl_title::id => $blog[0],
                            Tbl_title::type => 'blogger_id'
                        );
                        $this->Mod_general->delete($table_del, $where_del);
                        /* add */
                    } else if ($this->input->post('userblog')) {
                        $blogCat = explode('catid=', $bloginCat);
                        $userid = $this->input->post('userid');
                        $where_blog = array(
                            Tbl_title::type => 'blogger_id',
                            Tbl_title::object_id => $blogtitle[1],
                            Tbl_title::value => $userid,
                            Tbl_title::parent => $blogCat[1],
                        );
                        $query_blog_exist = $this->Mod_general->select(Tbl_title::tblname, Tbl_title::object_id, $where_blog);
                        /* check before insert */

                        if (empty($query_blog_exist)) {
                            /* add category if not exist */
                            $queryCat = $this->Mod_general->select(Tbl_title::tblname, array(Tbl_title::id, Tbl_title::title), array(Tbl_title::id => $blogCat[1]));
                            if (!empty($queryCat)) {
                                //var_dump($queryCat[0]->{Tbl_title::title});
                                $dataCat = array(
                                    'mo_title' => $queryCat[0]->{Tbl_title::title},
                                    'mo_type' => 'blog_category',
                                    Tbl_title::object_id => $userid,
                                );
                                $queryCatCh = $this->Mod_general->select(Tbl_title::tblname, '*', $dataCat);
                                if (empty($queryCatCh)) {
                                    $dataCatAdd = array(
                                        'mo_title' => $queryCat[0]->{Tbl_title::title},
                                        'mo_type' => 'blog_category',
                                        'mo_status' => 1,
                                        Tbl_title::object_id => $userid,
                                    );
                                    $CatAdd = $this->Mod_general->insert('title', $dataCatAdd);
                                } else {
                                    $CatAdd = $queryCatCh[0]->{Tbl_title::id};
                                }
                            }
                            $where_blog = array(
                                Tbl_title::type => 'blogger_id',
                                Tbl_title::object_id => $blogtitle[1],
                                Tbl_title::value => $userid,
                                Tbl_title::parent => $blogCat[1],
                            );
                            $query_blog_exist = $this->Mod_general->select(Tbl_title::tblname, Tbl_title::object_id, $where_blog);
                            /* end add category if not exist */

                            $data_blog = array(
                                Tbl_title::title => $blogtitle[0],
                                Tbl_title::status => 1,
                                Tbl_title::type => 'blogger_id',
                                Tbl_title::object_id => $blogtitle[1],
                                Tbl_title::parent => $CatAdd,
                                Tbl_title::value => $userid,
                            );
                            $lastID = $this->Mod_general->insert(Tbl_title::tblname, $data_blog);
                        }
                        /* end check before insert */
                    }
                }
            }
            redirect(base_url() . 'post/bloglist');
        }
        /* end form */
        $this->load->view('post/bloglist', $data);
    }

    function addblog() {
        $backto = base_url() . 'post/blogpassword';
        $query_blog = $this->Mod_general->blogcheck(current_url(), $backto);
        $data['title'] = 'Get from Web blog';
        $this->Mod_general->checkUser();
        $log_id = $this->session->userdata('log_id');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */

        /* show to view */
        $data_sel = array(
            Tbl_title::type => 'blog_category',
            Tbl_title::object_id => $log_id,
        );

        $dataBlogCate = $this->Mod_general->select('title', '*', $data_sel);
        $data['blogcatlist'] = $dataBlogCate;

//        $data['js'] = array(
//            'themes/layout/blueone/plugins/inputlimiter/jquery.inputlimiter.min.js',
//            'themes/layout/blueone/plugins/uniform/jquery.uniform.min.js',
//            'themes/layout/blueone/plugins/tagsinput/jquery.tagsinput.min.js',
//            'themes/layout/blueone/plugins/select2/select2.min.js',
//            'themes/layout/blueone/plugins/bootstrap-inputmask/jquery.inputmask.min.js',
//            'themes/layout/blueone/plugins/bootstrap-multiselect/bootstrap-multiselect.min.js',
//            'themes/layout/blueone/plugins/bootstrap-switch/bootstrap-switch.min.js',
//            'themes/layout/blueone/plugins/globalize/globalize.js',
//        );
        $data['addJsScript'] = array(
            "$('#checkAll').click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });"
        );

        /* form */
        if ($this->input->post('submit')) {
            $blogID = $this->input->post('blogID');
            $blogName = $this->input->post('blogName');
            $bloginCat = $this->input->post('bloginCat');
            $bloginCat = (($bloginCat) ? $bloginCat : 0);
            for ($i = 0; $i < count($blogID); $i++) {
                $blog = explode("[=]", $blogID[$i]);
                $where_blog = array(
                    Tbl_title::type => 'blogger_id',
                    Tbl_title::object_id => $blog[0],
                    Tbl_title::parent => $bloginCat,
                    Tbl_title::value => $log_id,
                );
                $query_blog_exist = $this->Mod_general->select(Tbl_title::tblname, Tbl_title::object_id, $where_blog);
                /* check before insert */
                if (empty($query_blog_exist)) {
                    $data_blog = array(
                        Tbl_title::title => $blog[1],
                        Tbl_title::status => 1,
                        Tbl_title::type => 'blogger_id',
                        Tbl_title::object_id => $blog[0],
                        Tbl_title::parent => $bloginCat,
                        Tbl_title::value => $log_id,
                    );
                    $lastID = $this->Mod_general->insert(Tbl_title::tblname, $data_blog);
                }
                /* end check before insert */
            }
            redirect(base_url() . 'post/bloglist?add=1');
        } else {
            /* end form */

            $this->load->library('zend');
            $this->zend->load('Zend/Loader');
            Zend_Loader::loadClass('Zend_Gdata');
            Zend_Loader::loadClass('Zend_Gdata_Query');
            Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
            Zend_Loader::loadClass('Zend_Gdata_Feed');
            $user = $this->session->userdata('username');
            $pass = $this->session->userdata('blogpassword');
            try {
                // perform login 
                // initialize service object
                $client = Zend_Gdata_ClientLogin::getHttpClient(
                                $user, $pass, 'blogger');
                $service = new Zend_Gdata($client);

                //$uri = 'http://www.blogger.com/feeds/' . $id . '/posts/default';
                //insertNewPost($entry, $uri,$service); 

                $query = new Zend_Gdata_Query('http://www.blogger.com/feeds/default/blogs?max-results=500');
                $feed = $service->getFeed($query);
            } catch (Exception $e) {
                $data['addJsScript'] = array(
                    'var error = generate(\'error\');',
                    'setTimeout(function () {
            $.noty.setText(error.options.id, "' . $e->getMessage() . '. អ៊ិនធើនែតអាចមានបញ្ហា ឬមានអ្វីមួយមិនដំណើរការល្អ សូមព្យាយាមម្ដងទៀត!");
        }, 1000);',
                    'setTimeout(function () {
                        $.noty.closeAll();
                    }, 10000);'
                );
                $feed = array();
            }
            $data['feed'] = $feed;
            /* end load data blogs */
        }
        $this->load->view('post/addblog', $data);
    }

    function blogpassword() {
        $data['title'] = 'Get from Web blog';
        if ($this->input->post('blogpass')) {
            $blogpass = $this->input->post('blogpass');
            $backto = $this->input->post('backto');
            $backto = (!empty($backto) ? $backto : 'home');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('blogpass', 'blogpass', 'required');
            if ($this->form_validation->run() == TRUE) {
                $this->load->library('zend');
                $this->zend->load('Zend/Loader');
                Zend_Loader::loadClass('Zend_Gdata');
                Zend_Loader::loadClass('Zend_Gdata_Query');
                Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
                // set credentials for ClientLogin authentication
                $user = $this->session->userdata('username');
                $pass = $blogpass;
                try {
                    // perform login 
                    // initialize service object
                    $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, 'blogger');
                    $service = new Zend_Gdata($client);

                    // get list of all blogs
                    $this->session->set_userdata('blogpassword', $blogpass);
                    redirect(base_url() . $backto);
                    exit();
                } catch (Exception $e) {
                    $data['blogerror'] = $e->getMessage();
                    //die('ERROR:' . $e->getMessage());
                }
                //redirect(base_url() . $backto);
            }
        }
        $this->load->view('post/blogpassword', $data);
    }

    function ajax() {
        $page_type = $this->input->post('page_type');
        $log_id = $this->session->userdata('log_id');
        switch ($page_type) {
            case 'bloglist':
                $bloginCat = $this->input->post('catid');
                if (!empty($bloginCat)) {
                    $where = array(
                        Tbl_title::parent => $bloginCat,
                        Tbl_title::type => 'blogger_id',
                        Tbl_title::value => $log_id,
                    );
                    $check = 'checked';
                } else {
                    $where = array(
                        Tbl_title::type => 'blogger_id',
                        Tbl_title::value => $log_id,
                    );
                    $check = '';
                }
                $query_singer = $this->Mod_general->select(Tbl_title::tblname, '*', $where);
                foreach ($query_singer as $value) {
                    $id = $value->{Tbl_title::object_id};
                    $name = $value->{Tbl_title::title};
                    echo '<label class="checkbox"><input type="checkbox" value="' . $id . '" name="idblog[]" ' . $check . ' />' . $name . '</label>';
                }
                break;
            case 'addsong':
                $title = $this->input->post('title');
                $data = array(
                    Tbl_meta::name => 'add_yt_id',
                    Tbl_meta::user_id => $log_id,
                );
                $lastID = $this->Mod_general->insert('meta', $data);
                include 'ajax_yt.php';
                break;
            default:
                break;
        }
    }

    function ajax_custom() {
        $page = !empty($_GET['p']) ? $_GET['p'] : '';
        $id = (int) $this->input->post('id');
        if ($page == 'blogsearch') {
            $where_title = array(
                Tbl_title::id => $id,
            );
            $query_vdo_title = $this->Mod_general->select(Tbl_title::tblname, '*', $where_title);
            $dataJson = array();
            if (!empty($query_vdo_title)) {
                foreach ($query_vdo_title as $value_title) {
                    $vdotitle = $value_title->{Tbl_title::title};
                    $valueTitleID = $value_title->{Tbl_title::id};
                    $Thumbnail = $value_title->{Tbl_title::image};
                    $valueTitle_label = $value_title->{Tbl_title::value};
                    $dataJson[] = array(
                        'title' => $vdotitle,
                        'id' => $valueTitleID,
                        'thumbnail' => $this->resize_image($Thumbnail, '0'),
                        'label' => $valueTitle_label,
                    );
                }
                echo json_encode($dataJson);
            }
        }
    }

    public function blogpost() {
        $data['title'] = 'Config blog to post music';
        $this->Mod_general->checkUser();
        $backto = base_url() . 'post/blogpassword';
        $query_blog = $this->Mod_general->blogcheck(current_url(), $backto);
        $log_id = $this->session->userdata('log_id');
        $user = $this->session->userdata('username');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */

        $data['addJsScript'] = array(
            "$('#checkAll').click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });
 $('#multidel').click(function () {
     if (!$('#itemid:checked').val()) {
            alert('please select one');
            return false;
    } else {
            return confirm('Do you want to delete all?');
    }
 });"
        );
        /* form */
        if ($this->input->post('submit')) {
            $musicblog = $this->input->post('muiscblog');
            $videoblog = $this->input->post('videoblog');
            $moviesite = $this->input->post('moviesite');
            $username = $this->input->post('user');
            $userpass = $this->input->post('user_pass');
            if ($username) {
                $where_username = array(
                    Tbl_title::type => 'config',
                    Tbl_title::title => 'my_user_username',
                );
                $data_username = array(
                    Tbl_title::object_id => $username,
                );
                $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_username, $where_username);
            }
            if ($userpass) {
                $where_userpass = array(
                    Tbl_title::type => 'config',
                    Tbl_title::title => 'my_user_pass',
                );
                $data_userpass = array(
                    Tbl_title::object_id => $userpass,
                );
                $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_userpass, $where_userpass);
            }

            if ($musicblog) {
                $where_music = array(
                    Tbl_title::type => 'config',
                    Tbl_title::title => 'music_blog',
                );
                $data_music = array(
                    Tbl_title::value => $musicblog,
                );
                $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_music, $where_music);
            }
            if ($videoblog) {
                $where_video = array(
                    Tbl_title::type => 'config',
                    Tbl_title::title => 'video_blog',
                );
                $data_video = array(
                    Tbl_title::value => $videoblog,
                );
                $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_video, $where_video);
            }
            if ($moviesite) {
                $where_moviesite = array(
                    Tbl_title::type => 'config',
                    Tbl_title::title => 'movie_post',
                );
                $data_site = array(
                    Tbl_title::value => $moviesite,
                );
                $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_site, $where_moviesite);
            }
            redirect(base_url() . 'post/blogpost');
        }
        /* end form */
        /* show to view */
        $data_sel = array(
            'mo_type' => 'blog_category'
        );
        $dataBlogCate = $this->Mod_general->select(Tbl_title::tblname, '*', $data_sel);
        $data['blogcatlist'] = $dataBlogCate;
        /* get current config */

        /* user */
        $data_user = array(
            Tbl_title::type => 'config',
            Tbl_title::title => 'my_user_username',
        );
        $data_user_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $data_user);
        $data['user_blog'] = $data_user_blog;

        /* userpass */
        $data_user_pass = array(
            Tbl_title::type => 'config',
            Tbl_title::title => 'my_user_pass',
        );
        $data_user_pass_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $data_user_pass);
        $data['user_pass'] = $data_user_pass_blog;

        /* music */
        $data_music = array(
            Tbl_title::type => 'config',
            Tbl_title::title => 'music_blog',
        );
        $datamusic = $this->Mod_general->select(Tbl_title::tblname, '*', $data_music);
        $data['music_blog'] = $datamusic;

        /* video */
        $data_video = array(
            Tbl_title::type => 'config',
            Tbl_title::title => 'video_blog',
        );
        $datavideo = $this->Mod_general->select(Tbl_title::tblname, '*', $data_video);
        $data['video_blog'] = $datavideo;
        $data_movies = array(
            Tbl_title::type => 'config',
            Tbl_title::title => 'movie_post',
        );
        $movie_post = $this->Mod_general->select(Tbl_title::tblname, '*', $data_movies);
        $data['movie_post'] = $movie_post;

        $this->load->view('post/blogpost', $data);

        /* end show list of movie not finish by Socheat Ngann */
    }

    function singerlist() {
        $data['title'] = 'Singer';
        $this->Mod_general->checkUser();
        $log_id = $this->session->userdata('log_id');
        $user = $this->session->userdata('username');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */
        $action = $this->uri->segment(3);
        $id = $this->uri->segment(4);


        /* search */
        $filtername = $this->input->post('filtername');
        if (!empty($filtername)) {
            $seach_movie = array(
                'sing_name' => $filtername,
            );
            $query_singer = $this->Mod_general->like('singer', '*', $seach_movie);
        }
        /* end search */
        $offset = (($action) ? $action : '');
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'post/singerlist';
        $count = $this->Mod_general->count('singer');
        $config['total_rows'] = $count;
        $data['total_rows'] = $count;
        $offset = $offset;
        if ($offset == false) {
            $offset = 0;
        }
        if (empty($filtername)) {
            if ($action == 'edit' && !empty($id)) {
                $where_sing = array(
                    Tbl_singer::id => $id,
                );
                $query_singer = $this->Mod_general->select('singer', '', $where_sing);
                $data['edititem'] = $id;
            } else {

                $query_singer = $this->Mod_general->select('singer', '', '', '', '', 10, $offset);
            }
        }
        $config = $this->Mod_general->paginations($config);
        $this->pagination->initialize($config);
        $data['singerlist'] = $query_singer;
        /* form */
        if ($this->input->post('submit')) {
            $singerid = $this->input->post('singerid');
            $mame = $this->input->post('singername');
            $singerimage = $this->input->post('singerimage');
            if ($singerid) {
                $where_singer = array(
                    Tbl_singer::id => $singerid,
                );
                $data_blog = array(
                    Tbl_singer::name => $mame,
                    Tbl_singer::image => $singerimage,
                );
                $lastID = $this->Mod_general->update(Tbl_singer::tblname, $data_blog, $where_singer);
            } else {
                $data_blog = array(
                    Tbl_singer::name => $mame,
                    Tbl_singer::image => $singerimage,
                );
                $lastID = $this->Mod_general->insert(Tbl_singer::tblname, $data_blog);
            }
            if ($lastID == true) {
                redirect(base_url() . 'post/singerlist/1');
            }
            $data_title = array(
                Tbl_title::title => trim($title),
                Tbl_title::type => 'vdolist',
                Tbl_title::object_id => $log_id,
                Tbl_title::image => $thumb,
            );
            $post_id = $this->Mod_general->insert(Tbl_title::tblname, $data_title);
            redirect(base_url() . 'post/singerlist/0');
        }
        /* end form */
        $this->load->view('post/singerlist', $data);
    }

    public function searchbloggerbost() {
        $data['title'] = 'Youtube Video';
        $this->Mod_general->checkUser();
        $log_id = $this->session->userdata('log_id');
        $user = $this->session->userdata('username');
        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $post_movie_website = $this->session->userdata('movivewebsite');
        $data['menuPermission'] = $menuPermission;
        /* end Sidebar */

        /* by cate */
        if ($post_movie_website) {
            $data_sel = array(
                Tbl_title::type => 'blog_category',
                Tbl_title::object_id => 1,
            );
        } else {
            $data_sel = array(
                Tbl_title::type => 'blog_category',
                Tbl_title::object_id => $log_id,
            );
        }
        $dataBlogCate = $this->Mod_general->select(Tbl_title::tblname, '*', $data_sel);
        $data['blogcatlist'] = $dataBlogCate;
        /* by end cate */
        $searchName = (!empty($_GET['q']) ? urlencode($_GET['q']) : '');
        $searchName_orgin = (!empty($_GET['q']) ? $_GET['q'] : '');
        $category = (!empty($_GET['category']) ? $_GET['category'] : '');
        $blogids = (!empty($_GET['blogid']) ? $_GET['blogid'] : '');
        $type = (!empty($_GET['types']) ? $_GET['types'] : '');
        /* for edit */
        $editid = (!empty($_GET['editid']) ? $_GET['editid'] : '');
        $idpost_eidt = (!empty($_GET['idpost']) ? $_GET['idpost'] : '');
        $title = (!empty($_GET['title']) ? $_GET['title'] : '');
        $label = (!empty($_GET['label']) ? $_GET['label'] : '');
        $site_url = (!empty($_GET['url']) ? $_GET['url'] : '');
        $thumb = (!empty($_GET['image']) ? $_GET['image'] : '');
        $onyoutube = (!empty($_GET['onyoutube']) ? $_GET['onyoutube'] : '');
        $order_by = (!empty($_GET['order']) ? $_GET['order'] : '');
        $to_site = (!empty($_GET['tosite']) ? $_GET['tosite'] : '');

        /* for website */
        $posttowebsite = (!empty($_GET['posttowebsite']) ? $_GET['posttowebsite'] : '');
        $copy_right = (!empty($_GET['copyright']) ? $_GET['copyright'] : '');
        if ($onyoutube == 1) {
            $copy = 'onyoutbue';
        } else {
            $copy = '';
        }
        if (!empty($copy_right)) {
            $copyright = '1';
        } else {
            $copyright = '';
        }
        /* end for website */
        if (!empty($searchName)) {
            /* get blog to search */
            if ($type == 'edit' && !empty($site_url)) {
                /* and new post to blog? */
                $edit_id = $this->get_from_site_id($site_url, '', $thumb, $title, $label);

                /* end and new post to blog? */
            } else {
                $edit_id = $idpost_eidt;
                if ($type == 'edit') {
                    $where_title = array(
                        Tbl_title::id => $edit_id,
                    );
                    $data_title = array(
                        Tbl_title::title => trim($title),
                        Tbl_title::image => $thumb,
                        Tbl_title::value => $label,
                    );
                    $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_title, $where_title);


                    /* get content */
                    $title_id = $editid;
                    $where_title = array(
                        Tbl_title::id => $title_id,
                    );
                    $query_vdo_title = $this->Mod_general->select(Tbl_title::tblname, '*', $where_title);
                    foreach ($query_vdo_title as $value_title) {
                        $vdotitle = $value_title->{Tbl_title::title};
                        $valueTitleID = $value_title->{Tbl_title::id};
                        $Thumbnail = $value_title->{Tbl_title::image};
                        $valueTitle_label = $value_title->{Tbl_title::value};
                    }
                    $where_vdo_list = array(
                        Tbl_meta::type => 'vdolist',
                        Tbl_meta::object_id => $title_id,
                        Tbl_meta::user_id => $log_id,
                    );
                    if (!empty($order_by)) {
                        $oderby = Tbl_meta::id . ' ' . $order_by;
                    } else {
                        $oderby = '';
                    }
                    $vdolist = $this->Mod_general->select(Tbl_meta::tblname, '*', $where_vdo_list, $oderby);
                    /* end get content */
                    /* and new post to blog? */
                    if (!empty($Thumbnail)) {
                        @$BigThumbnail = $this->resize_image($Thumbnail, '0');
                        @$smallThumbnail = $this->resize_image($Thumbnail, '72');
                    } else {
                        $smallThumbnail = '';
                        $BigThumbnail = '';
                    }
                    $countList = count($vdolist);
                    $code_content = '';
                    if (!empty($vdolist)):
                        $i = 0;
                        if (!empty($onyoutube)) {
                            $code_content = $this->get_On_yt($vdolist, $vdotitle, $BigThumbnail);
                        } else {
                            $vid_type = $vdolist[0]->{Tbl_meta::name};
                            switch ($vid_type) {
                                case 'yt':
                                    $code_content = $this->get_yt($vdolist, $vdotitle);
                                    break;
                                case 'yt_single':
                                    $code_content = $this->get_yt_single($vdolist, $vdotitle);
                                    break;
                                case 'iframe':
                                    $code_content = $this->get_iframe($vdolist, $vdotitle, $smallThumbnail);
                                    break;
                                case 'vimeo':
                                    $code_content = $this->get_vimeo($vdolist, $vdotitle, $smallThumbnail);
                                    break;
                                case 'docs.google':
                                    $code_content = $this->get_gdocs($vdolist, $vdotitle, $smallThumbnail);
                                    break;
                                case 'dailymotion':
                                    $code_content = $this->get_dailymotion($vdolist, $vdotitle);
                                    break;
                                case 'fbvid':
                                    $code_content = $this->get_fbvid($vdolist, $vdotitle);
                                    break;

                                default:
                                    $code_content = '';
                                    break;
                            }
                        }
                        $code_content;
                    endif;
                    $breaks = array("\r\n", "\n", "\r");
                    $addOnBody = '<a href="' . $BigThumbnail . '" target="_blank"><img border="0" id="noi" src="' . $BigThumbnail . '" /></a><meta property="og:image" content="' . $BigThumbnail . '"/><link href="' . $BigThumbnail . '" rel="image_src"/><!--more-->';
                    $bodytext = str_replace($breaks, "", $code_content);
                    $bodytext = $addOnBody . $bodytext;
                    if (!empty($posttowebsite)) {
                        $check_unig = $this->Mod_general->like3('vdo_videos', array('uniq_id', 'id'), array('video_title' => $searchName));
                        if (!empty($check_unig)) {
                            $action_site = 1;
                        } else {
                            $action_site = 0;
                        }
                        //$this->post_to_website($title, $where, $label, $type, $edit, $copy, $copyright, $imagepost, $orderby, $search_title)
                        $this->post_to_website($vdotitle, $title_id, $valueTitle_label, '', $action_site, $copy, $copyright, $BigThumbnail, '', $searchName);
                    }
                }
            }

            /* DELETE FROM WEBSITE */
            if ($type == 'del') {
                if (!empty($to_site)) {
                    $this->delete_site_post($searchName_orgin);
                }
            }
            /* END DELETE FROM WEBSITE */

            $where_blog = array(
                Tbl_title::parent => $category,
                Tbl_title::type => 'blogger_id',
                Tbl_title::value => $log_id,
            );
            $query_blogger = $this->Mod_general->select(Tbl_title::tblname, '*', $where_blog);
            /* end get blog to search */
            if (!empty($query_blogger) && empty($blogids)) {
                $this->Mod_general->delete(Tbl_meta::tblname, array(Tbl_meta::type => 'search_blog_post', Tbl_meta::user_id => $log_id));
                foreach ($query_blogger as $value_blogs) {
                    $blog_id = $value_blogs->{Tbl_title::object_id};
                    /* add to check delete or not */
                    $data_sub = array(
                        Tbl_meta::type => 'search_blog_post',
                        Tbl_meta::key => $blog_id,
                        Tbl_meta::name => $searchName,
                        Tbl_meta::user_id => $log_id,
                    );
                    $data_last_id = $this->Mod_general->insert(Tbl_meta::tblname, $data_sub);
                    /* end add to check delete or not */
                }
                echo '<script type="text/javascript">window.location = "' . base_url() . 'post/searchbloggerbost?category=' . $category . '&q=' . $searchName . '&blogid=' . $data_last_id . '&types=' . $type . '&editid=' . $edit_id . '&order=' . $order_by . '&onyoutube=' . $onyoutube . '&posttowebsite=1&copyright=' . $copy_right . '"</script>';
                exit();
            }

            if (!empty($blogids)) {
                /* get permission from blog account */
                $this->load->library('zend');
                $this->zend->load('Zend/Loader');
                Zend_Loader::loadClass('Zend_Gdata');
                Zend_Loader::loadClass('Zend_Gdata_Query');
                Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
                // set credentials for ClientLogin authentication
                $user = $this->session->userdata('username');
                $pass = $this->session->userdata('blogpassword');
                /* end get permission from blog account */
                try {
                    $client = Zend_Gdata_ClientLogin::getHttpClient(
                                    $user, $pass, 'blogger');
                    $service = new Zend_Gdata($client);
                } catch (Exception $e) {
                    die('ERROR:' . $e->getMessage());
                }


                $query_del = $this->Mod_general->select(Tbl_meta::tblname, '*', array(Tbl_meta::id => $blogids, Tbl_meta::name => $searchName));
                if (!empty($query_del)) {
                    $blog_id = $query_del[0]->{Tbl_meta::key};
                    $xmlblog = 'https://www.blogger.com/feeds/' . $blog_id . '/posts/default/?q=' . $searchName . '&max-results=1';
                    $blogid = simplexml_load_file($xmlblog);
                    if (!empty($blogid->entry)) {
                        foreach ($blogid->entry as $value) {
                            $xmlns = $value->children('http://www.w3.org/2005/Atom');
                            $arr = explode('-', $value->id);
                            $pid = $arr[2];
                            if ($type == 'edit') {

                                //$action = $this->blogger_post($query_del[0]->{Tbl_meta::key}, $vdotitle, $bodytext, $valueTitle_label);
                                //$bid, $pid, $title, $content = '', $label = ''

                                $action = $this->blogger_update_post($blog_id, $pid, $vdotitle, $bodytext, $valueTitle_label);
                                /* end and new post to blog? */
                                /* add record blog */
                                $where_bid = array(
                                    Tbl_meta::type => 'by_post_id',
                                    Tbl_meta::object_id => $title_id,
                                    Tbl_meta::user_id => $log_id,
                                    Tbl_meta::value => $blog_id
                                );
                                $bid_list = $this->Mod_general->select(Tbl_meta::tblname, '*', $where_bid);
                                if (!empty($bid_list)) {
                                    $query_blog = $this->Mod_general->update(Tbl_meta::tblname, array(Tbl_meta::key => $pid), array(Tbl_meta::id => $bid_list[0]->{Tbl_meta::id}));
                                } else {
                                    $where_pid = array(
                                        Tbl_meta::type => 'by_post_id',
                                        Tbl_meta::object_id => $title_id,
                                        Tbl_meta::user_id => $log_id,
                                        Tbl_meta::key => $pid
                                    );
                                    $blist = $this->Mod_general->select(Tbl_meta::tblname, '*', $where_pid);
                                    if (empty($blist)) {
                                        $data_post_ids = array(
                                            Tbl_meta::object_id => $title_id,
                                            Tbl_meta::type => 'by_post_id',
                                            Tbl_meta::user_id => $log_id,
                                            Tbl_meta::value => $blog_id,
                                            Tbl_meta::key => $pid
                                        );
                                        $dataPostID = $this->Mod_general->insert(Tbl_meta::tblname, $data_post_ids);
                                    }
                                }
                                /* end add record blog */

                                if ($action) {
                                    $this->Mod_general->delete(Tbl_meta::tblname, array(Tbl_meta::id => $blogids));
                                }

                                //$this->blogger_update_post($bid, $pid, $title, $content, $label);
                            } else if ($type == 'del') {
                                $action = $this->delete_blog_post($service, $query_del[0]->{Tbl_meta::key}, $pid);
                                $this->Mod_general->delete(Tbl_meta::tblname, array(Tbl_meta::id => $blogids));
                            }
                        }
                    } else {
                        /* if edit and not exist in blog post, then add new */
                        if ($type == 'edit') {
                            $action = $this->blogger_post($query_del[0]->{Tbl_meta::key}, $vdotitle, $bodytext, $valueTitle_label);
                            /* end and new post to blog? */

                            /* add record blog */
                            $where_pid = array(
                                Tbl_meta::type => 'by_post_id',
                                Tbl_meta::object_id => $title_id,
                                Tbl_meta::user_id => $log_id,
                                Tbl_meta::key => $action
                            );
                            $blist = $this->Mod_general->select(Tbl_meta::tblname, '*', $where_pid);
                            if (empty($blist)) {
                                $blog_id = $query_del[0]->{Tbl_meta::key};
                                $data_post_id = array(
                                    Tbl_meta::object_id => $title_id,
                                    Tbl_meta::type => 'by_post_id',
                                    Tbl_meta::user_id => $log_id,
                                    Tbl_meta::value => $blog_id,
                                    Tbl_meta::key => $action
                                );
                                $dataPostID = $this->Mod_general->insert(Tbl_meta::tblname, $data_post_id);
                            }
                            /* end add record blog */

                            if ($action) {
                                $this->Mod_general->delete(Tbl_meta::tblname, array(Tbl_meta::id => $blogids));
                            }
                        } else {
                            $this->Mod_general->delete(Tbl_meta::tblname, array(Tbl_meta::id => $blogids));
                        }
                        /* end if edit and not exist in blog post, then add new */
                    }
                    /* check for countinue */
                    $data_sub_select = array(
                        Tbl_meta::type => 'search_blog_post',
                        Tbl_meta::name => $searchName,
                        Tbl_meta::user_id => $log_id,
                    );
                    $query_check_count = $this->Mod_general->select(Tbl_meta::tblname, '*', $data_sub_select);
                    if (!empty($query_check_count)) {
                        echo '<script type="text/javascript">window.location = "' . base_url() . 'post/searchbloggerbost?category=' . $category . '&q=' . $searchName . '&blogid=' . $query_check_count[0]->{Tbl_meta::id} . '&types=' . $type . '&editid=' . $editid . '&order=' . $order_by . '&onyoutube=' . $onyoutube . '"</script>';
                        exit();
                    } else {
                        echo '<script type="text/javascript">window.location = "' . base_url() . 'post/searchbloggerbost?action=finshed"</script>';
                        exit();
                    }
                    /* end check for countinue */
                }
            }
        }
        $url = base_url() . 'post/ajax_custom?p=blogsearch';
        $data['addJsScript'] = array(
            "$(document).ready(function(){
                $(\".editpost\").click(function(){
                    var id = $(\".editpost\").val();
                    if(id == 'edit'){
                        $(\"#idurl\").show();
                    } 
                });
                $(\".delpost\").click(function(){
                    var id = $(\".delpost\").val();
                    if(id == 'del') {
                        $(\"#idurl\").hide();
                    }
                });
                $(\"#idpost\").blur(function(){
                    var id = $(\"#idpost\").val();
                    if(id) {
                        $.ajax
                        ({
                            type: \"POST\",
                            url: \"$url\",
                            data: {id: id},
                            cache: false,
                            datatype: 'json',
                            success: function(data)
                            {
                                var json = $.parseJSON(data);
                                $(\".image\").val(json[0].thumbnail);
                                $(\".titles\").val(json[0].title);
                                $(\".labels\").val(json[0].label);
                            } 
                        });
                    }
                });                
            });"
        );
        $this->load->view('post/searchbloggerbost', $data);
    }

    public function yt() {
        $data['title'] = 'Youtube Video';
        $this->Mod_general->checkUser();
        $log_id = $this->session->userdata('log_id');
        $user = $this->session->userdata('username');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */
        $action = $this->uri->segment(3);
        $id = (!empty($_GET['id']) ? $_GET['id'] : '');
        $order = (!empty($_GET['order']) ? $_GET['order'] : '');
        if (!empty($order)) {
            $oderby = Tbl_meta::id . ' ' . $order;
        } else {
            $oderby = '';
        }
        if (!empty($id)) {
            /* Video list */
            $where_vdo_list = array(
                Tbl_meta::type => 'vdolist',
                Tbl_meta::object_id => $id,
                Tbl_meta::user_id => $log_id,
            );
            $query_vdo_list = $this->Mod_general->select(Tbl_meta::tblname, '*', $where_vdo_list, $oderby);

            $query_get_image = $this->Mod_general->select(Tbl_title::tblname, array(Tbl_title::image), array(Tbl_title::id => $id, Tbl_title::type => 'vdolist'));
            $data['videolist'] = $query_vdo_list;
            /* end Video list */
        }
        $data['addJsScript'] = array(
            "jQuery( document ).ready(function($) {           
            $(\"#addfield\").click(function() {
                var count = $(\".listofsong\").length;
                $(\"#countdiv\").text(\"ចំនួន \" + count + \" បទ\");
                $.ajax
                ({
                    type: \"POST\",
                    url: \"ajax\",
                    data: {page_type: 'addsong', title: $('#txttitle').val()},
                    cache: false,
                    success: function(html)
                    {
                    $(\"#morerow\").append(html);
                    } 
                });


            });  
            $(\".removelist\").live(\"click\", function() {
                $(this).parent().parent().remove();
                var count = $(\".listofsong\").length;
                $(\"#countdiv\").text(\"ចំនួន \" + count + \" បទ\");
            });            
            $('input:radio[name=\"productions\"]').change(
                function(){
                    if ($(this).is(':checked')) {
                        if($(this).val() == 'P -- Hang Meas') {
                            $('input#provol').val(\"RHM Vol \");
                        }
                        if($(this).val() == 'P -- Sunday') {
                            $('input#provol').val(\"P -- Sunday Vol \");
                        }
                        if($(this).val() == 'P -- M Production') {
                            $('input#provol').val(\"P -- M -- Vol \");
                        }
                        if($(this).val() == 'Town Entertainment') {
                            $('input#provol').val(\"Town CD Vol \");
                        }
                        if($(this).val() == 'P -- Bigman') {
                            $('input#provol').val(\"Bigman Vol \");
                        }
                        if($(this).val() == 'P -- Rock Production') {
                            $('input#provol').val(\"Rock CD Vol \");
                        }
                        if($(this).val() == 'Old Songs') {
                            $('input#provol').val(\"Samuth Vol \");
                        }
                    }
                });           
            });
    "
        );

        /* get from */
        if ($this->input->post('youtubeid')) {
            $urlid = $this->input->post('youtubeid');
            $post_edit_id = $this->input->post('idpost');
            $where_contine_del = array(
                Tbl_meta::name => 'add_yt_id',
                Tbl_meta::user_id => $log_id,
            );
            $query_blog = $this->Mod_general->delete(Tbl_meta::tblname, $where_contine_del);
            if (!empty($post_edit_id)) {
                /* delete not use data */
                $where_contine_del = array(
                    Tbl_meta::object_id => $post_edit_id,
                    Tbl_meta::type => 'vdolist',
                    Tbl_meta::user_id => $log_id,
                );
                $query_blog = $this->Mod_general->delete(Tbl_meta::tblname, $where_contine_del);
                /* end delete not use data */
                $post_id = $post_edit_id;
            } else {
                /* insert title db */
                if (!empty($urlid)) {
                    if (preg_match('/ytimg/', $urlid[0]) || preg_match('/vi_webp/', $urlid[0])) {
                        $code1 = $this->imageyoutube($urlid[0]);
                    } else {
                        $code1 = $this->youtubecode($urlid[0]);
                    }
                    $url = "http://gdata.youtube.com/feeds/api/videos/$code1";
                    $doc = new DOMDocument;
                    $doc->load($url);
                    $title = $doc->getElementsByTagName("title")->item(0)->nodeValue;
                    $thumb = 'https://i1.ytimg.com/vi/' . $code1 . '/0.jpg';
                    /* end get title */
                    if (empty($id_post)) {
                        $data_title = array(
                            Tbl_title::title => trim($title),
                            Tbl_title::type => 'vdolist',
                            Tbl_title::object_id => $log_id,
                            Tbl_title::image => $thumb,
                        );
                        $post_id = $this->Mod_general->insert(Tbl_title::tblname, $data_title);
                    }
                    /* end insert title db */
                }
            }
            for ($i = 0; $i < count($urlid); $i++) {
                if (preg_match('/ytimg/', $urlid[$i]) || preg_match('/vi_webp/', $urlid[$i])) {
                    $code = $this->imageyoutube($urlid[$i]);
                } else {
                    $code = $this->youtubecode($urlid[$i]);
                }
                /* add to db */
                $dataLogin = $this->Mod_general->add_meta($code, $i + 1, '', 'yt', $post_id);
                /* end add to db */
            }
            if (!empty($post_edit_id)) {
                redirect(base_url() . 'post/getcode/edit/' . $post_id);
            } else {
                redirect(base_url() . 'post/getcode?id=' . $post_id);
            }
            exit();
        }
        /* end get from */
        $data['singerlist'] = '';
        $this->load->view('post/yt', $data);
    }

    public function youtube() {
        $data['title'] = 'Add by Youtube Videos';
        $log_id = $this->session->userdata('log_id');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */

        /* form* by Socheat Ngann */
        if ($this->input->post('submit')) {
            $xmlurl = $this->input->post('xmlurl');
            $edit_id = $this->input->post('edit_id');
            $yttype = $this->input->post('yttype');
            $imageid = $this->input->post('imageid');
            $max = (($this->input->post('max')) ? $this->input->post('max') : '50');
            $start = (($this->input->post('start')) ? $this->input->post('start') : '1');
            //$ytrul = "http://gdata.youtube.com/feeds/api/videos?q={$xmlurl}&start-index={$start}&max-results={$max}&v=2";
            if ($yttype == 'search') {
                $ytrul = "http://gdata.youtube.com/feeds/base/videos?q={$xmlurl}&start-index={$start}&max-results={$max}&v=2";
            } elseif ($yttype == 'playlist') {
                $ytrul = "http://gdata.youtube.com/feeds/base/playlists/{$xmlurl}?start-index={$start}&max-results={$max}&v=2";
            } elseif ($yttype == 'byuser') {
                $ytrul = "http://gdata.youtube.com/feeds/base/users/{$xmlurl}/uploads?start-index={$start}&max-results={$max}&v=2";
            }

            $getyturl = simplexml_load_file(sprintf($ytrul));
            $data_yl = $this->get_full_yt_playlist($getyturl);
            $ytrul1 = "http://gdata.youtube.com/feeds/base/playlists/{$xmlurl}?start-index=51&max-results={$max}&v=2";
            $getyturl1 = simplexml_load_file(sprintf($ytrul1));
            if (!empty($getyturl1)) {
                $data_yl = $this->get_full_yt_playlist($getyturl1, $data_yl);
            }
            $ytrul2 = "http://gdata.youtube.com/feeds/base/playlists/{$xmlurl}?start-index=101&max-results={$max}&v=2";
            $getyturl2 = simplexml_load_file(sprintf($ytrul2));
            if (!empty($getyturl2)) {
                $data_yl = $this->get_full_yt_playlist($getyturl2, $data_yl);
            }
            $ytrul3 = "http://gdata.youtube.com/feeds/base/playlists/{$xmlurl}?start-index=151&max-results={$max}&v=2";
            $getyturl3 = simplexml_load_file(sprintf($ytrul3));
            if (!empty($getyturl3)) {
                $data_yl = $this->get_full_yt_playlist($getyturl3, $data_yl);
            }

            $i = 0;
            $list_count = count($data_yl);

            if (!empty($edit_id)) {
                $status = 0;
            } else {
                $status = 1;
                /* add new post */
                $data_title = array(
                    Tbl_title::title => ' ',
                    Tbl_title::type => 'vdolist',
                    Tbl_title::object_id => $log_id,
                    Tbl_title::image => @$imageid,
                );
                $edit_id = $this->Mod_general->insert(Tbl_title::tblname, $data_title);
                /* end add new post */
            }
            foreach ($data_yl as $entry) {
                $i++;
                $title = $entry['entry']['title'];
                $codeID = $entry['entry']['vid'];
                $vid_type = 'yt';
                if ($i == 1) {
                    $where_title = array(
                        Tbl_title::type => 'vdolist',
                        Tbl_title::id => $edit_id,
                    );
                    preg_match('/(.*)part/i', $title, $match);
                    if (!empty($match[1])) {
                        $titl = $match[1];
                    } else {
                        $titl = $title;
                    }
                    $data_title = array(
                        Tbl_title::title => trim($titl),
                    );
                    $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_title, $where_title);
                }
                if (!empty($edit_id)) {
                    if ($i == 1) {
                        $vdo_type = 'vdolist';
                        $where_del = array(
                            Tbl_meta::object_id => $edit_id,
                            Tbl_meta::user_id => $log_id,
                            Tbl_meta::type => $vdo_type,
                        );
                        $dataLogin = $this->Mod_general->delete(Tbl_meta::tblname, $where_del);
                    }
                    //$list = ($list_count + 1) - $i;
                    $dataLogin = $this->Mod_general->add_meta($codeID, $i, $title, $vid_type, $edit_id);
                } else {
                    $dataLogin = $this->Mod_general->add_meta($codeID, $i, $title, $vid_type, $edit_id);
                }
            }
            if ($status) {
                redirect(base_url() . 'post/getcode?id=' . $edit_id);
            } else {
                redirect(base_url() . 'post/yt?id=' . $edit_id . '&order=ASC');
            }
        }
        /* end form */
        $this->load->view('post/youtube', $data);
    }

    public function videofile() {
        $data['title'] = 'File Video';
        $this->Mod_general->checkUser();
        $log_id = $this->session->userdata('log_id');
        $user = $this->session->userdata('username');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */
        $action = $this->uri->segment(3);
        $id = (!empty($_GET['id']) ? $_GET['id'] : '');
//        $link = 'https://player.vimeo.com/video/94510473';
//        $link = str_replace('https://player.vimeo.com/video/', 'http://vimeo.com/api/v2/video/', $link) . '.php';
//        $html_returned = unserialize(file_get_contents($link));
//        $thumb_url = $html_returned[0]['thumbnail_medium'];
//        var_dump($html_returned);
//        die;
        if (!empty($id)) {
            /* Video list */
            $where_vdo_list = array(
                Tbl_meta::type => 'vdolist',
                Tbl_meta::object_id => $id,
                Tbl_meta::user_id => $log_id,
            );
            $query_vdo_list = $this->Mod_general->select(Tbl_meta::tblname, '*', $where_vdo_list);

            $query_get_image = $this->Mod_general->select(Tbl_title::tblname, array(Tbl_title::image), array(Tbl_title::id => $id, Tbl_title::type => 'vdolist'));
            $data['videolist'] = $query_vdo_list;
            /* end Video list */
        }
        $data['js'] = array(
            'themes/layout/blueone/plugins/validation/jquery.validate.min.js',
        );
        $data['addJsScript'] = array(
            "jQuery( document ).ready(function($) {
                $.validator.addClassRules('required', {
                required: true
                }); 
		$('#validate').validate();
            $(\"#addfield\").click(function() {
                var count = $(\".listofsong\").length;
                $(\"#countdiv\").text(\"ចំនួន \" + count + \" បទ\");
                $.ajax
                ({
                    type: \"POST\",
                    url: \"ajax\",
                    data: {page_type: 'addsong', title: $('#txttitle').val()},
                    cache: false,
                    success: function(html)
                    {
                    $(\"#morerow\").append(html);
                    } 
                });


            });  
            $(\".removelist\").live(\"click\", function() {
                $(this).parent().parent().remove();
                var count = $(\".listofsong\").length;
                $(\"#countdiv\").text(\"ចំនួន \" + count + \" បទ\");
            });            
            $('input:radio[name=\"productions\"]').change(
                function(){
                    if ($(this).is(':checked')) {
                        if($(this).val() == 'P -- Hang Meas') {
                            $('input#provol').val(\"RHM Vol \");
                        }
                        if($(this).val() == 'P -- Sunday') {
                            $('input#provol').val(\"P -- Sunday Vol \");
                        }
                        if($(this).val() == 'P -- M Production') {
                            $('input#provol').val(\"P -- M -- Vol \");
                        }
                        if($(this).val() == 'Town Entertainment') {
                            $('input#provol').val(\"Town CD Vol \");
                        }
                        if($(this).val() == 'P -- Bigman') {
                            $('input#provol').val(\"Bigman Vol \");
                        }
                        if($(this).val() == 'P -- Rock Production') {
                            $('input#provol').val(\"Rock CD Vol \");
                        }
                        if($(this).val() == 'Old Songs') {
                            $('input#provol').val(\"Samuth Vol \");
                        }
                    }
                });           
            });
    "
        );

        /* get from */
        if ($this->input->post('youtubeid')) {
            $thumb = $this->input->post('imageid');
            $videotype = $this->input->post('videotype');
            $urlid = $this->input->post('youtubeid');
            $id_post = $this->input->post('idpost');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('youtubeid', 'youtubeid', 'required');
            if ($this->form_validation->run() == TRUE) {
                $where_contine_del = array(
                    Tbl_meta::name => 'add_yt_id',
                    Tbl_meta::user_id => $log_id,
                );
                $query_blog = $this->Mod_general->delete(Tbl_meta::tblname, $where_contine_del);
                if (!empty($id_post)) {
                    /* delete not use data */
                    $where_contine_del = array(
                        Tbl_meta::object_id => $id_post,
                        Tbl_meta::type => 'vdolist',
                        Tbl_meta::user_id => $log_id,
                    );
                    $query_blog = $this->Mod_general->delete(Tbl_meta::tblname, $where_contine_del);
                    /* end delete not use data */
                    $post_id = $id_post;
                } else {

                    /* get title */
                    $title = ' ';
                    $data_title = array(
                        Tbl_title::title => trim($title),
                        Tbl_title::type => 'vdolist',
                        Tbl_title::object_id => $log_id,
                        Tbl_title::image => $thumb,
                    );
                    $post_id = $this->Mod_general->insert(Tbl_title::tblname, $data_title);
                    /* end insert title db */
                }
//                switch ($videotype) {
//                    case 'videofile':
//                        $this->get_videofile($urlid, $thumb, $videotype, $post_id);
//                        break;
//
//                    default:
//                        break;
//                }

                $this->get_videofile($urlid, $thumb, $videotype, $post_id);

                if (!empty($id)) {
                    redirect(base_url() . 'post/getcode/edit/' . $post_id);
                } else {
                    redirect(base_url() . 'post/getcode?id=' . $post_id);
                }
                //redirect(base_url() . 'post/getcode?id=' . $post_id);
            }
            exit();
        }
        /* end get from */
        $data['singerlist'] = '';
        $this->load->view('post/videofile', $data);
    }

    function get_videofile($urlid, $thumb, $type, $post_id) {
        $log_id = $this->session->userdata('log_id');
        for ($i = 0; $i < count($urlid); $i++) {
            $vid = $this->get_video_id($urlid[$i], $type);
            if (!empty($vid['vid'])) {
                $v_id = $vid['vid'];
                $vtype = !empty($type) ? $type : $vid['vtype'];
            } else {
                $v_id = $urlid;
                $vtype = $type;
            }
            $dataLogin = $this->Mod_general->add_meta($v_id, $i + 1, '', $vtype, $post_id);
            /* end add to db */
        }
    }

    public function fb() {
        $data['title'] = 'Facebook Video';
        $this->Mod_general->checkUser();
        $log_id = $this->session->userdata('log_id');
        $user = $this->session->userdata('username');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */
        $action = $this->uri->segment(3);
        $id = (!empty($_GET['id']) ? $_GET['id'] : '');




        $fbvideoid = "311938522290189";

        if ($this->input->post('fbid')) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('fbid', 'fbid', 'required');
            if ($this->form_validation->run() == TRUE) {
//                $this->load->library('facebook');
//                $facebook = new Facebook(array(
//                    'appId' => '270357559651042',
//                    'secret' => 'e8799d8af4a01fe87d134449a19ac642',
//                    'cookie' => true,
//                ));
//                // get extended acces token with 60 days validity
//                $facebook->setExtendedAccessToken();
//                $accessToken = $facebook->getAccessToken();
                $urlid = $this->input->post('fbid');
                for ($i = 0; $i < count($urlid); $i++) {
                    if (preg_match('/fbcdn/', $urlid[$i])) {
                        $vid = explode('_', $urlid[$i]);
                        $vid = $vid[2];
                    } else {
                        parse_str(parse_url($urlid[$i], PHP_URL_QUERY), $params);
                        $vid = $params['v'];
                    }
                    //fbcdn
                }
                $post_id = $this->get_fb_video($vid);
                redirect(base_url() . 'post/getcode?id=' . $post_id . '&provider=fb');
            }
        }
        $this->load->view('post/fb', $data);
    }

    public function embedvideo() {
        $data['title'] = 'Facebook Video';
        $this->Mod_general->checkUser();
        $log_id = $this->session->userdata('log_id');
        $user = $this->session->userdata('username');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */
        $action = $this->uri->segment(3);
        $id = (!empty($_GET['id']) ? $_GET['id'] : '');

        /* form */
        if ($this->input->post('vid')) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('vid', 'vid', 'required');
            if ($this->form_validation->run() == TRUE) {
                $vid = $this->input->post('vid');
                $thumb = $this->input->post('imageid');
                $data_title = array(
                    Tbl_title::title => ' ',
                    Tbl_title::type => 'vdolist',
                    Tbl_title::object_id => $log_id,
                    Tbl_title::image => $thumb,
                );
                $post_id = $this->Mod_general->insert(Tbl_title::tblname, $data_title);
                /* end insert title db */
                /* add to db */
                $dataLogin = $this->Mod_general->add_meta($vid, 1, '', 'embed', $post_id);
                redirect(base_url() . 'post/getcode?id=' . $post_id . '&provider=embed');
            }
        }
        /* end form */
        $this->load->view('post/embedvideo', $data);
    }

    public function auto_post_video() {
        $add = (!empty($_GET['add']) ? $_GET['add'] : '');
        $continue = (!empty($_GET['continue']) ? $_GET['continue'] : '');
        $update_post = (!empty($_GET['update']) ? $_GET['update'] : '');
        $bid = (!empty($_GET['bid']) ? $_GET['bid'] : '');
        $data_user = array(
            'user_type' => 1
        );
        $data_user_blog = $this->Mod_general->select('users', array('log_id'), $data_user);
        $log_id = $data_user_blog[0]->log_id;

        if (!empty($add)) {
            $this->load->library('facebook');
            $facebook = new Facebook(array(
                'appId' => '270357559651042',
                'secret' => 'e8799d8af4a01fe87d134449a19ac642',
                'cookie' => true,
            ));
            // get extended acces token with 60 days validity
            $facebook->setExtendedAccessToken();
            $accessToken = $facebook->getAccessToken();


            $this->load->library('html_dom');
            $link = 'http://www.khmer2alls.net/newvideos.html';
            $html = file_get_html($link);
            $i = 0;
            $creat_arr = array();
            foreach ($html->find('#newvideos_results tr') as $e) {
                $i++;
                if ($i < 22) {
                    if ($i == 1) {
                        
                    } else {
                        $v_image = $e->find('td', 0)->innertext;
                        $str_img = <<< HTML
'.$v_image.'
HTML;
                        $html_img = str_get_html($str_img);
                        foreach ($html_img->find('img') as $i_st) {
                            $image = $i_st->src;
                        }

                        /* end image */

                        $v_type = $e->find('td', 1)->plaintext;

                        if (preg_match('/ytimg/', $image) || preg_match('/fbcdn/', $image)) {
                            if (!preg_match('/Drama/', $v_type)) {
                                $title = $e->find('td', 2)->plaintext;
                                $title_st = $title;
                                $titles = explode(" - ", $title);
                                if (!empty($titles[0])) {
                                    $title = $titles[0];
                                } else {
                                    $title = $title;
                                }

                                /* Categories */
                                switch (trim($v_type)) {
                                    case 'Just for Laugh':
                                        $label = 'Just For Laugh, Funny, withads';
                                        break;
                                    case 'Daily Laugh':
                                        $label = 'Just For Laugh, Funny, withads';
                                        break;
                                    case 'Funny Video':
                                        $label = 'Funny, withads';
                                        break;
                                    case 'Funny Song':
                                        $label = 'Videos, Videos Songs,Funny, withads';
                                        break;
                                    case 'Funny Dancing':
                                        $label = 'Videos, Videos Songs,Funny, withads';
                                        break;
                                    case 'Funny Commercial':
                                        $label = 'Videos, Videos Songs,Funny, withads';
                                        break;
                                    case 'Document Film':
                                        $label = 'Movies, khmer movies, Short Movies, Old Doc Films, withads';
                                        break;
                                    case 'Khmer Movie':
                                        $label = 'Movies, khmer movies, Short Movies, withads';
                                        break;
                                    case 'Cambodia Magic':
                                        $label = 'Cambodia Magic, Magic, withads';
                                        break;
                                    /* news */
                                    case 'RFA Radio':
                                        $label = 'News, RFA Videos, Daily News, withads';
                                        break;
                                    case 'RFA Khmer':
                                        $label = 'News, RFA Videos, Daily News, withads';
                                        break;
                                    case 'SBK News':
                                        $label = 'News, SBK News, Daily News, withads';
                                        break;
                                    case 'BBC News':
                                        $label = 'News, BBC TV News, BBC News, Daily News, withads';
                                        break;
                                    case 'VOA Khmer':
                                        $label = 'News, VOA Videos, Daily News, withads';
                                        break;
                                    case 'So Amazing':
                                        $label = 'Amazing, Video clips, withads';
                                        break;
                                    case 'Amazing':
                                        $label = 'Amazing, Video clips, withads';
                                        break;
                                    case 'Amazing Video':
                                        $label = 'Amazing, Video clips, withads';
                                        break;
                                    case 'Amazing Girl':
                                        $label = 'Amazing, Video clips, withads';
                                        break;
                                    case 'Wow':
                                        $label = 'Amazing, Video clips, withads';
                                        break;
                                    case 'Animal Life':
                                        $label = 'Animal, Video clips, withads';
                                        break;
                                    case 'CTN 21':
                                        $label = 'TV Show, CTN Show, CTN 21, withads';
                                        break;
                                    case 'CTN':
                                        if (preg_match('/M Show/', $title)) {
                                            $label = 'TV Show, CTN Show, Morning Show, News, Daily News, withads';
                                        }
                                        break;
                                    case 'Khmer Food Show':
                                        if (preg_match('/Chong Phov Ek/', $title)) {
                                            $label = 'TV Show, CTN Show, Cooking, Chong Phov Ek, withads';
                                        } else {
                                            $label = 'Cooking,withads';
                                        }
                                        break;
                                    case 'Contest':
                                        if (preg_match('/Song Contest/', $title)) {
                                            $label = 'TV Show, CTN Show, Samneang Cheng Chang, withads';
                                        }
                                        if (preg_match('/Samneang Cheng Chang/', $title)) {
                                            $label = 'TV Show, CTN Show, Samneang Cheng Chang, withads';
                                        }
                                        if (preg_match('/Apsara Award/', $title)) {
                                            $label = 'TV APSARA11, TV Show, TV APSARA11 Apsara Award, withads';
                                        }
                                        if (preg_match('/khmer Super Model/', $title)) {
                                            $label = 'TV Show, Fashion Show, Khmer Fashion, withads';
                                        }
                                        break;
                                    case 'Song Contest':
                                        if (preg_match('/Song Contest/', $title)) {
                                            $label = 'TV Show, CTN Show, Samneang Cheng Chang, withads';
                                        }
                                        if (preg_match('/Samneang Cheng Chang/', $title)) {
                                            $label = 'TV Show, CTN Show, Samneang Cheng Chang, withads';
                                        }
                                        if (preg_match('/Apsara Award/', $title)) {
                                            $label = 'TV APSARA11, TV Show, TV APSARA11 Apsara Award, withads';
                                        }
                                        break;
                                    case 'CTN Daily Laugh':
                                        if (preg_match('/Perkmy Replay/', $title)) {
                                            $label = 'Comedy, Khmer Comedy, Paekme, withads';
                                        }
                                        break;
                                    case 'Kids Show':
                                        if (preg_match('/Garden Children Star/', $title)) {
                                            $label = 'TV APSARA11, TV Show, Garden Children Star, withads';
                                        }
                                        break;
                                    case 'Funny Show':
                                        if (preg_match('/Perkmy Replay/', $title)) {
                                            $label = 'Comedy, Khmer Comedy, Paekme,Funny Show, withads';
                                        }
                                        if (preg_match('/Samnerch Tamphum/', $title)) {
                                            $label = 'TV Show, CTN Show, Samnech Tamphumi, Comedy, Khmer Comedy,Funny Show, withads';
                                        }
                                        if (preg_match('/Pteah Lokta/', $title)) {
                                            $label = 'TV Show, CTN Show, CTN Phteah Lokta,Funny Show, withads';
                                        }
                                        if (preg_match('/Penh Jet Ort/', $title)) {
                                            $label = 'MYTV, TV Show, Penh Jet Ort,Funny Show, withads';
                                        }
                                        if (preg_match('/Olala/', $title)) {
                                            $label = 'MYTV, TV Show, MYTV Olala Funny Show,Funny Show, withads';
                                        }
                                        break;
                                    case 'Daily News':
                                        if (preg_match('/CTN/', $title)) {
                                            $label = 'TV Show, CTN Show, News ,Daily News, withads';
                                        }
                                        if (preg_match('/Hang Meas/', $title)) {
                                            $label = 'TV HM, TV Show, TV HM, TV HM Daily News, Daily News, withads';
                                        }
                                        break;
                                    case 'HM News':
                                        if (preg_match('/Hang Meas/', $title_st)) {
                                            $label = 'TV HM, TV Show, TV HM, TV HM Daily News, Daily News, withads';
                                        } else if (preg_match('/Accident/', $title_st)) {
                                            $label = 'TV Show, CTN Show, CTN Morning News, Daily News, News, Security - social, withads';
                                        }
                                        break;
                                    case 'Concert':
                                        if (preg_match('/Sunday Music/', $title_st)) {
                                            $label = 'TV Show, CTN Show, Sunday Music, withads';
                                        }
                                        if (preg_match('/Reatrey Kamsan/', $title_st)) {
                                            $label = 'TV Show, Reatrey Kamsan, CTN Show, CTN Concert, Concert, withads';
                                        }
                                        if (preg_match('/Jumrum Dara/', $title_st)) {
                                            $label = 'MYTV, TV Show, MYTV Jumrum Dara, withads';
                                        }
                                        break;
                                    case 'Khmer Fashion':
                                        $label = 'TV Show, Fashion Show, Khmer Fashion, withads';
                                        break;
                                    case 'Khmer Super Model':
                                        $label = 'TV Show, Fashion Show, Khmer Fashion, withads';
                                        break;
                                    case 'Video News':
                                        $label = 'News, Daily News, withads';
                                        break;
                                    case 'Buddhism':
                                        $label = 'Education, Videos, withads';
                                        break;
                                    case 'Accident Video':
                                        $label = 'Daily News, News, Security - social, withads';
                                        break;
                                    case 'MyTV Daily Laugh':
                                        if (preg_match('/Perkmy Replay/', $title)) {
                                            $label = 'Comedy, Khmer Comedy, Paekme, MyTV Comedy, withads';
                                        } else {
                                            $label = 'Comedy, Khmer Comedy, MyTV Comedy, withads';
                                        }
                                        break;
                                    case 'Training Stage':
                                        $label = 'Boxing, Khmer Boxing,MMA Fight, withads';
                                        break;
                                    case 'MyTV Comedy':
                                        $label = 'Comedy, Khmer Comedy, MyTV Comedy, withads';
                                        break;
                                    case 'Neay Koy Comedy':
                                        $label = 'Comedy, Khmer Comedy, Neay Koy, withads';
                                        break;
                                    case 'Neay Krem Comedy':
                                        $label = 'Comedy, Khmer Comedy, Neay Krem, withads';
                                        break;
                                    case 'Music for Relax':
                                        $label = 'Videos, Videos Songs, withads';
                                        break;
                                    case 'Song for Relax':
                                        $label = 'Videos, Videos Songs, withads';
                                        break;
                                    case 'Khmer Song':
                                        $label = 'Videos, Videos Songs, withads';
                                        break;
                                    case 'Perkmy Joke':
                                        $label = 'Comedy, Khmer Comedy, Paekme, withads';
                                        break;
                                    case 'Game Show':
                                        if (preg_match('/Rungvoin Teveda/', $title)) {
                                            $label = 'MYTV, TV Show, MYTV Rongvean Tevada,Game Show, withads';
                                        } else if (preg_match('/5th Class Student/', $title)) {
                                            $label = 'TV Show, CTN Show, 5th Class Student,Game Show, withads';
                                        } else if (preg_match('/i-Mission/', $title)) {
                                            $label = 'TV Show, Bayon TV, Bayon i-Mission,Game Show, withads';
                                        } else if (preg_match('/15000 Dollars Prize/', $title)) {
                                            $label = 'MYTV, TV Show, 15000 Dollars Prize, Game Show, withads';
                                        } else if (preg_match('/15000 Prize/', $title)) {
                                            $label = 'MYTV, TV Show, 15000 Dollars Prize, Game Show, withads';
                                        } else if (preg_match('/Millionaire Game/', $title)) {
                                            $label = 'TV Show, CTN Show, Millionaire, Game Show, withads';
                                        } else if (preg_match('/1 Minute to Win/', $title)) {
                                            $label = 'TV Show, CTN Show, CTN 1 Minute to Win, Game Show, withads';
                                        } else {
                                            $label = 'TV Show, Game Show, withads';
                                        }
                                        break;
                                    case 'Traditional Music':
                                        if (preg_match('/Mun Sneh Samneang/', $title)) {
                                            $label = 'TV Show, CTN Show, Mun Snae Somneang, withads';
                                        } else {
                                            $label = 'TV Show, Khmer Traditional, withads';
                                        }
                                        break;
                                    case 'Music':
                                        if (preg_match('/A1 Concert/', $title)) {
                                            $label = 'MYTV, TV Show, MYTV A1 Concert,Concert, withads';
                                        } else {
                                            $label = 'withads';
                                        }
                                        break;
                                    case 'Cha Cha Cha':
                                        $label = 'TV Show, Bayon TV, Bayon TV Cha Cha Cha, withads';
                                        break;
                                    case 'Camera Catch':
                                        $label = 'Video clips, withads';
                                        break;
                                    case 'Technology':
                                        $label = 'Technology,News, Video clips, withads';
                                        break;
                                    case 'Chinese Movie':
                                        $label = 'Movies, chinese movies, Short Movies, Short Chinese';
                                        break;
                                    case 'Kun Khmer':
                                        if (preg_match('/CTN Arena/', $title)) {
                                            $label = 'TV Show, CTN Show, CTN Boxing, Boxing, Khmer Boxing, withads';
                                        } else {
                                            $label = 'withads,Boxing,Khmer Boxing';
                                        }
                                        break;

                                    default:
                                        if (preg_match('/Amazing/', $title_st)) {
                                            $label = 'Amazing, Video clips, withads';
                                        } else if (preg_match('/News/', $title_st)) {
                                            $label = 'News, Daily News, withads';
                                        } else if (preg_match('/World Cup/', $v_type)) {
                                            $label = 'Sports, Football, News, Daily News,World Cup, withads';
                                        } else {
                                            $label = 'Videos, withads';
                                        }
                                        break;
                                }
                                /* end Categories */

                                /* check for update */
                                $data_title = array(
                                    Tbl_title::title => trim($title),
                                    Tbl_title::type => 'vdolist',
                                    Tbl_title::object_id => $log_id,
                                );
                                $CheckPost_title = $this->Mod_general->select(Tbl_title::tblname, array(Tbl_title::title, Tbl_title::id), $data_title);
                                $data_title_meta = array(
                                    Tbl_meta::key => trim($title_st),
                                    Tbl_meta::type => 'vdolist',
                                    Tbl_meta::user_id => $log_id,
                                );
                                $CheckPost_title_meta = $this->Mod_general->select(Tbl_meta::tblname, array(Tbl_meta::key, Tbl_meta::status), $data_title_meta);
                                if (!empty($CheckPost_title) && empty($CheckPost_title_meta)) {
                                    $update_vid = $CheckPost_title[0]->{Tbl_title::id};
                                    $where_title = array(
                                        Tbl_title::type => 'vdolist',
                                        Tbl_title::id => $update_vid,
                                    );
                                    $data_title = array(
                                        Tbl_title::status => 3,
                                    );
                                    $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_title, $where_title);

                                    /* update list */
                                    /* for youtube */
                                    $g_value = $image;
                                    if (preg_match('/ytimg/', $g_value)) {
                                        $vdo_type = 'yt';
                                        $video_id = $this->imageyoutube($g_value);
                                        /* for facebook */
                                    } else if (preg_match('/fbcdn/', $g_value)) {
                                        $vdo_type = 'fbvid';
                                        $vid = explode('_', $g_value);
                                        $vid = $vid[2];
                                        $video_id = $this->get_vid_fb($facebook, $vid);
                                    }
                                    //fbcdn
                                    $data_sub = array(
                                        Tbl_meta::object_id => $update_vid,
                                        Tbl_meta::type => 'vdolist',
                                        Tbl_meta::key => trim($title_st),
                                        Tbl_meta::value => $video_id,
                                        Tbl_meta::name => $vdo_type,
                                        Tbl_meta::user_id => $log_id,
                                    );
                                    $dataPostID = $this->Mod_general->insert(Tbl_meta::tblname, $data_sub);
                                    /* end update list */
                                }
//                                else if (!empty($CheckPost_title) && !empty($CheckPost_title_meta)) {
//                                    $update_vid = $CheckPost_title[0]->{Tbl_title::id};
//                                    if ($CheckPost_title_meta[0]->{Tbl_meta::status} == 0) {
//                                        $where_title = array(
//                                            Tbl_title::type => 'vdolist',
//                                            Tbl_title::id => $update_vid,
//                                        );
//                                        $data_title = array(
//                                            Tbl_title::status => 3,
//                                        );
//                                        $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_title, $where_title);
//
//                                        /* update blog status that just update */
//                                        $where_blog = array(
//                                            Tbl_meta::object_id => $update_vid,
//                                            Tbl_meta::type => 'by_post_id',
//                                        );
//                                        $data_title_blog = array(
//                                            Tbl_meta::name => 0
//                                        );
//                                        $query_blog = $this->Mod_general->update(Tbl_meta::tblname, $data_title_blog, $where_blog);
//                                        /* update blog status that just update */
//                                    }
//                                }
                                /* end check for update */


                                $links = $e->find('td', 2)->innertext;
                                $str = <<< HTML
'.$links.'
HTML;
                                $html_str = str_get_html($str);
                                foreach ($html_str->find('a') as $e_st) {
                                    $m_link = $e_st->href;
                                }

                                $creat_arr[] = array(
                                    'brand' => $title,
                                    'title' => $title_st,
                                    'image' => $image,
                                    'cat' => $label,
                                );

                                $data_post_id = array(
                                    Tbl_autopost::name => $title_st,
                                    Tbl_autopost::object_id => "khmer2all",
                                );
                                /* check */
                                $CheckPostID = $this->Mod_general->select(Tbl_autopost::tblname, array(Tbl_autopost::name), $data_post_id);
                                /* end check */

                                /* instert into DB */
                                if (empty($CheckPostID)) {
                                    $dataPostID = $this->Mod_general->insert(Tbl_autopost::tblname, $data_post_id);
                                }
                                /* end instert into DB */
                            }
                        }
                    }
                }
            }


            $newOptions = array();
            $list_arr = array();
            foreach ($creat_arr as $option) {
                $brand = $option['brand'];
                $code = $option['title'];
                $name = $option['image'];
                $cat = $option['cat'];
                $newOptions[$brand]['list'][$code] = $name;
                $newOptions[$brand]['categories'] = $cat;
            }

            /* delete */
            $data_post_del = array(
                Tbl_autopost::name1 => 'autopost_not_in_use',
            );
            //$clean_data = $this->Mod_general->delete(Tbl_autopost::tblname, $data_post_del);
            //$clean_blog_data = $this->Mod_general->delete(Tbl_autopost::tblname, array(Tbl_autopost::name1 => 'autopost_blog'));
            /* end delete */
            foreach ($newOptions as $key => $g_tile) {
                $get_label = (!empty($g_tile['categories']) ? $g_tile['categories'] : '');
                /* add new video */
                foreach ($g_tile['list'] as $g_key => $g_value) {
                    
                }
                $data_title = array(
                    Tbl_title::title => trim($key),
                    Tbl_title::type => 'vdolist',
                    Tbl_title::object_id => $log_id,
                );
                $CheckPost_title = $this->Mod_general->select(Tbl_title::tblname, array(Tbl_title::title, Tbl_title::id), $data_title);
                if (!empty($CheckPost_title)) {
                    $vdo_title_d = $CheckPost_title[0]->{Tbl_title::id};
                } else {
                    $data_title = array(
                        Tbl_title::title => trim($key),
                        Tbl_title::type => 'vdolist',
                        Tbl_title::object_id => $log_id,
                        Tbl_title::image => $g_value,
                        Tbl_title::value => $get_label,
                    );
                    $vdo_title_d = $this->Mod_general->insert(Tbl_title::tblname, $data_title);
                }
                /* end add new video */

                /* add video for TEMPERATERY */
                $data_post_temp = array(
                    Tbl_autopost::name => trim($key),
                    Tbl_autopost::name1 => 'autopost_video',
                    Tbl_autopost::object_id => $vdo_title_d,
                    Tbl_autopost::status => 0,
                    Tbl_autopost::desc => $get_label,
                );
                $CheckPost_temp = $this->Mod_general->select(Tbl_autopost::tblname, array(Tbl_autopost::object_id), array(Tbl_autopost::object_id => $vdo_title_d));
                if (empty($CheckPost_temp)) {
                    $dataPostID = $this->Mod_general->insert(Tbl_autopost::tblname, $data_post_temp);
                    /* blog to post */
                    $data_movies = array(
                        Tbl_title::type => 'config',
                        Tbl_title::title => 'movie_post',
                    );
                    $movie_post = $this->Mod_general->select(Tbl_title::tblname, '*', $data_movies);
                    $where_blog = array(
                        Tbl_title::parent => $movie_post[0]->{Tbl_title::value},
                        Tbl_title::type => 'blogger_id',
                        Tbl_title::value => $log_id,
                    );
                    $query_blogs = $this->Mod_general->select(Tbl_title::tblname, '*', $where_blog);

                    foreach ($query_blogs as $value_blog) {
                        $data_post_id = array(
                            Tbl_meta::object_id => $vdo_title_d,
                            Tbl_meta::type => 'by_post_id',
                            Tbl_meta::user_id => $log_id,
                            Tbl_meta::value => $value_blog->{Tbl_title::object_id},
                            Tbl_meta::name => 0,
                        );
                        $dataPostID = $this->Mod_general->insert(Tbl_meta::tblname, $data_post_id);


//                        $data_post_blog = array(
//                            Tbl_autopost::name1 => 'autopost_blog',
//                            Tbl_autopost::desc => $value_blog->{Tbl_title::object_id},
//                            Tbl_autopost::object_id => $vdo_title_d,
//                            Tbl_autopost::status => 0,
//                        );
//                        $dataPostBlogID = $this->Mod_general->insert(Tbl_autopost::tblname, $data_post_blog);
                    }
                    /* end blog to post */


                    /* add to list */
                    foreach ($g_tile['list'] as $g_key => $g_value) {
                        /* for youtube */
                        if (preg_match('/ytimg/', $g_value)) {
                            $vdo_type = 'yt';
                            $video_id = $this->imageyoutube($g_value);
                            /* for facebook */
                        } else if (preg_match('/fbcdn/', $g_value)) {
                            $vdo_type = 'fbvid';
                            $vid = explode('_', $g_value);
                            $vid = $vid[2];
                            $video_id = $this->get_vid_fb($facebook, $vid);
                        }
                        //fbcdn


                        $data_sub = array(
                            Tbl_meta::object_id => $vdo_title_d,
                            Tbl_meta::type => 'vdolist',
                            Tbl_meta::key => trim($g_key),
                            Tbl_meta::value => $video_id,
                            Tbl_meta::name => $vdo_type,
                            Tbl_meta::user_id => $log_id,
                        );
                        $dataPostID = $this->Mod_general->insert(Tbl_meta::tblname, $data_sub);
                    }
                    /* end add to list */
                }
                /* end add video for TEMPERATERY */
            }

            /* check for update first */
            $get_where_post_edit = array(
                Tbl_title::status => 3,
            );
            $get_b_temp = $this->Mod_general->select(Tbl_title::tblname, '', $get_where_post_edit, '', '', 1);
            if (!empty($get_b_temp)) {
                $id_edit = $get_b_temp[0]->{Tbl_title::id};
                $get_where_blog_edit = array(
                    Tbl_meta::object_id => $id_edit,
                    Tbl_meta::name => 0,
                    Tbl_meta::type => 'by_post_id',
                );
                $get_b_temp_id = $this->Mod_general->select(Tbl_meta::tblname, '', $get_where_blog_edit, '', '', 1);
                if (!empty($get_b_temp_id)) {
                    redirect(base_url() . 'post/auto_post_video?update=' . $get_b_temp[0]->{Tbl_title::id} . '&bid=' . $get_b_temp_id[0]->{Tbl_meta::value});
                    exit();
                }
            }
            /* end check for update first */




            $data_post_temp = array(
                Tbl_autopost::name1 => 'autopost_video',
                Tbl_autopost::status => 0,
            );
            $CheckPost_temp = $this->Mod_general->select(Tbl_autopost::tblname, '', $data_post_temp, '', '', 1);
            if (!empty($CheckPost_temp)) {
                $id_next = $CheckPost_temp[0]->{Tbl_autopost::object_id};
                $data_blog_id = array(
                    Tbl_meta::object_id => $id_next,
                    Tbl_meta::type => 'by_post_id',
                    Tbl_meta::user_id => $log_id,
                    Tbl_meta::name => 0,
                );
                $get_b_temp = $this->Mod_general->select(Tbl_meta::tblname, '', $data_blog_id, '', '', 1);
                if (!empty($get_b_temp)) {
                    echo '<script type="text/javascript">window.location = "' . base_url() . 'post/auto_post_video?continue=' . $id_next . '&bid=' . $get_b_temp[0]->{Tbl_meta::value} . '"</script>';
                    exit();
                }
            }
        }
        /* add */
        /* if have some post to update */
        if (!empty($update_post)) {
            $get_where_update = array(
                Tbl_title::id => $update_post,
                Tbl_title::type => 'vdolist',
            );
            $get_post = $this->Mod_general->select(Tbl_title::tblname, '*', $get_where_update);
            if (!empty($get_post)) {
                /* update the list what status == 1 */
                $where_mta_st = array(
                    Tbl_meta::object_id => $update_post,
                    Tbl_meta::type => 'vdolist',
                );
                $data_status = array(
                    Tbl_meta::status => 1
                );
                $query_blog = $this->Mod_general->update(Tbl_meta::tblname, $data_status, $where_mta_st);
                /* end update the list what status == 1 */



                foreach ($get_post as $value_post) {
                    $post_tile = $value_post->{Tbl_title::title};
                    $imagepost = $value_post->{Tbl_title::image};
                    $label = $value_post->{Tbl_title::value};

                    /* get list */
                    $where_vdo_list = array(
                        Tbl_meta::type => 'vdolist',
                        Tbl_meta::object_id => $value_post->{Tbl_title::id},
                        Tbl_meta::user_id => $log_id,
                    );
                    $order_list = Tbl_meta::id . ' ASC';
                    $query_vdo_list = $this->Mod_general->select(Tbl_meta::tblname, '*', $where_vdo_list, $order_list);
                    if (!empty($query_vdo_list)) {
                        $bodytext = $this->get_vlist($query_vdo_list, $post_tile, $imagepost);
                    }
                    /* end get list */
                    $breaks = array("\r\n", "\n", "\r");
                    $addOnBody = '<img border="0" id="noi" src="' . $imagepost . '" alt="' . $post_tile . '" title="' . $post_tile . '" /><meta property="og:image" content="' . $imagepost . '"/><link href="' . $imagepost . '" rel="image_src"/><!--more-->';
                    $bodytext = str_replace($breaks, "", $bodytext);
                    $content = $addOnBody . $bodytext;
                    $data_blog_post = array(
                        Tbl_meta::type => 'by_post_id',
                        Tbl_meta::user_id => $log_id,
                        Tbl_meta::object_id => $value_post->{Tbl_title::id},
                        Tbl_meta::name => 0,
                        Tbl_meta::value => $bid,
                    );
                    $get_b_pid = $this->Mod_general->select(Tbl_meta::tblname, '', $data_blog_post, '', '', 1);
                    if (!empty($get_b_pid)) {
                        $bid = $get_b_pid[0]->{Tbl_meta::value};
                        $pid = $get_b_pid[0]->{Tbl_meta::key};
                        $post_blog_update = $this->blogger_update_post($bid, $pid, $post_tile, $content, $label);
                        if ($post_blog_update) {
                            /* update blog status that just update */
                            $where_blog = array(
                                Tbl_meta::object_id => $update_post,
                                Tbl_meta::value => $bid
                            );
                            $data_title_blog = array(
                                Tbl_meta::name => 1
                            );
                            $query_blog = $this->Mod_general->update(Tbl_meta::tblname, $data_title_blog, $where_blog);
                            /* update blog status that just update */

                            $data_blog_post = array(
                                Tbl_meta::type => 'by_post_id',
                                Tbl_meta::user_id => $log_id,
                                Tbl_meta::object_id => $value_post->{Tbl_title::id},
                                Tbl_meta::name => 0,
                            );
                            $get_b_temp_id = $this->Mod_general->select(Tbl_meta::tblname, '', $data_blog_post, '', '', 1);
                            if (!empty($get_b_temp_id)) {
                                echo '<script type="text/javascript">window.location = "' . base_url() . 'post/auto_post_video?update=' . $update_post . '&bid=' . $get_b_temp_id[0]->{Tbl_meta::value} . '"</script>';
                            } else {
                                /* update status of post to 0 for not check to update again */
                                $where_title = array(
                                    Tbl_title::id => $update_post,
                                    Tbl_title::type => 'vdolist'
                                );
                                $data_title = array(
                                    Tbl_title::status => 0
                                );
                                $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_title, $where_title);
                                /* end update status of post to 0 for not check to update again */

                                $get_where_post_edit = array(
                                    Tbl_title::status => 3,
                                );
                                $get_b_temp = $this->Mod_general->select(Tbl_title::tblname, '', $get_where_post_edit, '', '', 1);
                                if (!empty($get_b_temp)) {
                                    $id_edit = $get_b_temp[0]->{Tbl_title::id};
                                    $get_where_blog_edit = array(
                                        Tbl_meta::object_id => $id_edit,
                                        Tbl_meta::name => 0,
                                        Tbl_meta::type => 'by_post_id',
                                    );
                                    $get_b_temp_id = $this->Mod_general->select(Tbl_meta::tblname, '', $get_where_blog_edit, '', '', 1);
                                    if (!empty($get_b_temp_id)) {
                                        echo '<script type="text/javascript">window.location = "' . base_url() . 'post/auto_post_video?update=' . $id_edit . '&bid=' . $get_b_temp_id[0]->{Tbl_meta::value} . '"</script>';
                                        exit();
                                    }
                                } else {
                                    $data_post_temp = array(
                                        Tbl_autopost::name1 => 'autopost_video',
                                        Tbl_autopost::status => 0,
                                    );
                                    $CheckPost_temp = $this->Mod_general->select(Tbl_autopost::tblname, '', $data_post_temp, '', '', 1);
                                    if (!empty($CheckPost_temp)) {
                                        $id_next = $CheckPost_temp[0]->{Tbl_autopost::object_id};
                                        $data_blog_id = array(
                                            Tbl_meta::object_id => $id_next,
                                            Tbl_meta::type => 'by_post_id',
                                            Tbl_meta::user_id => $log_id,
                                            Tbl_meta::name => 0,
                                        );
                                        $get_b_temp = $this->Mod_general->select(Tbl_meta::tblname, '', $data_blog_id, '', '', 1);
                                        if (!empty($get_b_temp)) {
                                            echo '<script type="text/javascript">window.location = "' . base_url() . 'post/auto_post_video?continue=' . $id_next . '&bid=' . $get_b_temp[0]->{Tbl_meta::value} . '"</script>';
                                            exit();
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $get_where_post_edit = array(
                            Tbl_title::status => 3,
                        );
                        $get_b_temp = $this->Mod_general->select(Tbl_title::tblname, '', $get_where_post_edit, '', '', 1);
                        if (!empty($get_b_temp)) {
                            $id_edit = $get_b_temp[0]->{Tbl_title::id};
                            $get_where_blog_edit = array(
                                Tbl_meta::object_id => $id_edit,
                                Tbl_meta::name => 0,
                                Tbl_meta::type => 'by_post_id',
                            );
                            $get_b_temp_id = $this->Mod_general->select(Tbl_meta::tblname, '', $get_where_blog_edit, '', '', 1);
                            if (!empty($get_b_temp_id)) {
                                echo '<script type="text/javascript">window.location = "' . base_url() . 'post/auto_post_video?update=' . $id_edit . '&bid=' . $get_b_temp_id[0]->{Tbl_meta::value} . '"</script>';
                                exit();
                            }
                        } else {
                            //continue
                        }
                    }
                    $data_post_temp = array(
                        Tbl_autopost::name1 => 'autopost_video',
                        Tbl_autopost::status => 0,
                    );
                    $CheckPost_temp = $this->Mod_general->select(Tbl_autopost::tblname, '', $data_post_temp, '', '', 1);
                    if (!empty($CheckPost_temp)) {
                        $id_next = $CheckPost_temp[0]->{Tbl_autopost::object_id};
                        $data_blog_id = array(
                            Tbl_meta::object_id => $id_next,
                            Tbl_meta::type => 'by_post_id',
                            Tbl_meta::user_id => $log_id,
                            Tbl_meta::name => 0,
                        );
                        $get_b_temp = $this->Mod_general->select(Tbl_meta::tblname, '', $data_blog_id, '', '', 1);
                        if (!empty($get_b_temp)) {
                            echo '<script type="text/javascript">window.location = "' . base_url() . 'post/auto_post_video?continue=' . $id_next . '&bid=' . $get_b_temp[0]->{Tbl_meta::value} . '"</script>';
                        }
                    }
                }
            }
        }
        /* end if have some post to update */

        /* Posting to blog or web */
        if (!empty($continue)) {
            /* action post to blog or web */
            $get_where_post = array(
                Tbl_title::id => $continue,
                Tbl_title::type => 'vdolist',
            );
            $get_post = $this->Mod_general->select(Tbl_title::tblname, '*', $get_where_post);
            if (!empty($get_post)) {
                foreach ($get_post as $value_post) {
                    /* update the list what status == 1 */
                    $where_mta_st = array(
                        Tbl_meta::object_id => $value_post->{Tbl_title::id},
                        Tbl_meta::type => 'vdolist',
                    );
                    $data_status = array(
                        Tbl_meta::status => 1
                    );
                    $query_blog = $this->Mod_general->update(Tbl_meta::tblname, $data_status, $where_mta_st);
                    /* end update the list what status == 1 */



                    $post_tile = $value_post->{Tbl_title::title};
                    $imagepost = $value_post->{Tbl_title::image};
                    $label = $value_post->{Tbl_title::value};
                    /* get list */
                    $where_vdo_list = array(
                        Tbl_meta::type => 'vdolist',
                        Tbl_meta::object_id => $value_post->{Tbl_title::id},
                        Tbl_meta::user_id => $log_id,
                    );
                    $order_list = Tbl_meta::id . ' DESC';
                    $query_vdo_list = $this->Mod_general->select(Tbl_meta::tblname, '*', $where_vdo_list, $order_list);
                    if (!empty($query_vdo_list)) {
                        $bodytext = $this->get_vlist($query_vdo_list, $post_tile, $imagepost);
                    }
                    /* end get list */
                    $breaks = array("\r\n", "\n", "\r");
                    $addOnBody = '<img border="0" id="noi" src="' . $imagepost . '" alt="' . $post_tile . '" title="' . $post_tile . '" /><meta property="og:image" content="' . $imagepost . '"/><link href="' . $imagepost . '" rel="image_src"/><!--more-->';
                    $bodytext = str_replace($breaks, "", $bodytext);
                    $content = $addOnBody . $bodytext;
                    /* end body text */
                    //$this->post_to_website($title_ogrin, $vdo_id, $label, 'yt', '', $codetype, $copyright_video, $imagepost);
                    $post_blog_id = $this->blogger_post($bid, $post_tile, $content, $label);

                    /* blog post id */
                    $where_blog_update = array(
                        Tbl_meta::object_id => $value_post->{Tbl_title::id},
                        Tbl_meta::value => $bid
                    );
                    $data_update_blog = array(
                        Tbl_meta::key => $post_blog_id,
                        Tbl_meta::name => 1,
                    );
                    $query_blog_update = $this->Mod_general->update(Tbl_meta::tblname, $data_update_blog, $where_blog_update);
                    /* end blog post id */
                }

                /* end action post to blog or web */



                /* if all blogs where post and then update current post to 0 */
                $where_b_post = array(
                    Tbl_meta::type => 'by_post_id',
                    Tbl_meta::object_id => $value_post->{Tbl_title::id},
                    Tbl_meta::user_id => $log_id,
                    Tbl_meta::name => 0,
                );
                $query_vdo_list = $this->Mod_general->select(Tbl_meta::tblname, '*', $where_b_post);

                $data_post_temp_check = array(
                    Tbl_autopost::name1 => 'autopost_video',
                    Tbl_autopost::status => 0,
                    Tbl_autopost::object_id => $value_post->{Tbl_title::id},
                );
                $Post_temp_check = $this->Mod_general->select(Tbl_autopost::tblname, '', $data_post_temp_check);
                if (empty($query_vdo_list) && !empty($Post_temp_check)) {
                    /* udate all blobs in that post to 0 */
                    $where_blogs_tmp = array(
                        Tbl_autopost::object_id => $value_post->{Tbl_title::id},
                        Tbl_autopost::name1 => 'autopost_video'
                    );
                    $data_blogs_emp = array(
                        Tbl_autopost::status => 1
                    );
                    $query_blog_temp = $this->Mod_general->update(Tbl_autopost::tblname, $data_blogs_emp, $where_blogs_tmp);
                    if ($query_blog_temp) {
                        $where_blog_update = array(
                            Tbl_meta::object_id => $value_post->{Tbl_title::id},
                            Tbl_meta::type => 'by_post_id',
                        );
                        $data_update_blog = array(
                            Tbl_meta::name => 0,
                            Tbl_meta::status => 1,
                        );
                        $query_blog_update = $this->Mod_general->update(Tbl_meta::tblname, $data_update_blog, $where_blog_update);
                    }
                    /* end udate all blobs in that post to 0 */
                }
                /* if all blogs where post and then update current post to 0 */

                $data_post_temp = array(
                    Tbl_autopost::name1 => 'autopost_video',
                    Tbl_autopost::status => 0,
                );
                $CheckPost_temp = $this->Mod_general->select(Tbl_autopost::tblname, '', $data_post_temp, '', '', 1);
                if (!empty($CheckPost_temp)) {
                    $id_next = $CheckPost_temp[0]->{Tbl_autopost::object_id};
                    $data_blog_id = array(
                        Tbl_meta::object_id => $id_next,
                        Tbl_meta::type => 'by_post_id',
                        Tbl_meta::user_id => $log_id,
                        Tbl_meta::name => 0,
                    );
                    $get_b_temp = $this->Mod_general->select(Tbl_meta::tblname, '', $data_blog_id, '', '', 1);
                    if (!empty($get_b_temp)) {
                        echo '<script type="text/javascript">window.location = "' . base_url() . 'post/auto_post_video?continue=' . $id_next . '&bid=' . $get_b_temp[0]->{Tbl_meta::value} . '"</script>';
                    }
                }
            }
        }
        /* end Posting to blog or web */
    }

    function get_vlist($query_vdo_list, $post_tile, $imagepost) {
        $i = 0;
        foreach ($query_vdo_list as $vdo) {
            $i++;
            if ($vdo->{Tbl_meta::name} == 'yt' || $vdo->{Tbl_meta::name} == 'yt_single') {
                if ($i == 1) {
                    $bodytext = '<script> $(function() { var list = [';
                }
                $bodytext .= '{"file": "http://www.youtube.com/watch?v=' . $vdo->{Tbl_meta::value} . '", "title": "' . $vdo->{Tbl_meta::key} . '", "description": "' . $post_tile . '", "image": ""},';
            } elseif ($vdo->{Tbl_meta::name} == 'fbvid') {
                $bodytext = '<script type="text/javascript">jwplayer("player4").setup({"flashplayer": "" + flashplay + "", "file": "' . $vdo->{Tbl_meta::value} . '", "image": "' . $imagepost . '", "skin": "" + skinplay + "", "autostart": "" + autostart + "","width": "" + widthplay + "", "height": "" + singleheight + "","logo": "" + logosite + "", "logo.hide": "false", "logo.position": "bottom-right", "logo.out": "1", "link": "" + slink + "", "dock": "true", "plugins": {"like-1": {}}});</script>';
            } elseif ($vdo->{Tbl_meta::name} == 'embed') {
                $bodytext = $vdo->{Tbl_meta::value};
            }
        }
        if ($query_vdo_list[0]->{Tbl_meta::name} == 'yt' || $vdo->{Tbl_meta::name} == 'yt_single') {
            $bodytext .= ']; khmervideo_media_list._selectedPlayAllVideo(list); });</script>';
        }
        foreach ($query_vdo_list as $vdo) {
            if ($vdo->{Tbl_meta::name} == 'yt' || $vdo->{Tbl_meta::name} == 'yt_single') {
                $bodytext .= '<div align="center"><div style="font-size: 16px;font-weight: bold; padding:10px; clear:both; width:90%;overflow:hidden;margin-bottom:2px; text-align:center;border:1px solid #555;"><a href="/p/player.html?http://youtu.be/' . $vdo->{Tbl_meta::value} . '" target="_blank">' . $vdo->{Tbl_meta::key} . '</a></div></div>';
            }
        }
        if (!empty($bodytext)) {
            return $bodytext;
        } else {
            return FALSE;
        }
    }

    function youtubecode($url) {
        preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
        if (!empty($matches[1])) {
            $code = $matches[1];
        } else {
            $code = $url;
        }
        return $code;
    }

    function imageyoutube($url) {
        if (preg_match('/vi_webp/', $url)) {
            preg_match("/vi_webp\/([^&]+)(\/+)/i", $url, $code_link);
        } else {
            preg_match("/vi\/([^&]+)(\/+)/i", $url, $code_link);
        }
        return $code_link[1];
    }

    function get_vid_fb($facebook, $fbvideoid) {
        $fql2 = "SELECT vid,title, embed_html, src, src_hq,created_time,description,length,link,album_id,thumbnail_link,owner FROM video WHERE vid=" . $fbvideoid;
        $param = array(
            'method' => 'fql.query',
            'query' => $fql2,
            'callback' => ''
        );
        $fqlResult2 = $facebook->api($param);
        if (!empty($fqlResult2)) {
            return $fqlResult2[0]['src_hq'];
        } else {
            return null;
        }
    }

    function get_fb_video($fbvideoid) {
        $this->load->library('html_dom');
        $log_id = $this->session->userdata('log_id');
        $url = 'http://www.fbdown.net/down.php';
        $data_url = array('URL' => 'https://www.facebook.com/video.php?v=' . $fbvideoid);

        // use key 'http' even if you send the request to https://...
        $options = array('http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data_url),
            ),);
        $context = stream_context_create($options);
        $html = file_get_html($url, false, $context);
        $image = @$html->find('.img-thumbnail', 0)->src;
        $image = str_replace('https:', 'http:', $image);
        $title = @$html->find('h4', 0)->plaintext;
        $link2 = @$html->find('.well a', 2)->href;
        $link1 = @$html->find('.well a', 1)->href;
        if (preg_match('/.mp4/', $link2)) {
            $vid = $link2;
        } elseif (preg_match('/.mp4/', $link1)) {
            $vid = $link1;
        } else {
            $vid = '';
        }
        $src_file = $fbvideoid;
        if (!empty($vid)) {
            //
            /* insert title db */
            $data_title = array(
                Tbl_title::title => trim($title),
                Tbl_title::type => 'vdolist',
                Tbl_title::object_id => $log_id,
                Tbl_title::image => $image,
            );

            /* end add to db */
        } else {
            $data_title = array(
                Tbl_title::title => '',
                Tbl_title::type => 'vdolist',
                Tbl_title::object_id => $log_id,
                Tbl_title::image => '',
            );
        }
        $post_id = $this->Mod_general->insert(Tbl_title::tblname, $data_title);
        /* end insert title db */
        /* add to db */
        $dataLogin = $this->Mod_general->add_meta($src_file, 1, '', 'fbvid', $post_id);
        //echo $fqlResult2[0]['thumbnail_link'];
        //https://www.facebook.com/video/embed?video_id=
        //var_dump($fqlResult2);
        return $post_id;
    }

    function generateRandomString($length = 50) {
        $characters = '0123456789abcdefghijkmnopqrstuvwxyz';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters [rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    function blogger_post($bid, $title, $content = '', $label = '') {
        /* user */
        $data_user = array(
            Tbl_title::type => 'config',
            Tbl_title::title => 'my_user_username',
        );
        $data_user_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $data_user);
        if (!empty($data_user_blog)) {
            $user = $data_user_blog[0]->{Tbl_title::object_id};
        }

        /* userpass */
        $data_user_pass = array(
            Tbl_title::type => 'config',
            Tbl_title::title => 'my_user_pass',
        );
        $data_user_pass_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $data_user_pass);
        if (!empty($data_user_pass_blog)) {
            $pass = $data_user_pass_blog[0]->{Tbl_title::object_id};
        }

        if (!empty($pass) && !empty($user)) {
            /* get permission from blog account */
            $this->load->library('zend');
            $this->zend->load('Zend/Loader');
            Zend_Loader::loadClass('Zend_Gdata');
            Zend_Loader::loadClass('Zend_Gdata_Query');
            Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
            /* end get permission from blog account */

            try {

                $client = Zend_Gdata_ClientLogin::getHttpClient(
                                $user, $pass, 'blogger');
                $service = new Zend_Gdata($client);

                $uri = 'http://www.blogger.com/feeds/' . $bid . '/posts/default';

                /*                 * ************* START INSERT NEW POST *************** */
                $entry = $service->newEntry();
                $entry->title = $service->newTitle($title);

                $str = $str = str_replace("<br />", "\n", $content);
                $entry->content = $service->newContent($str);
                $entry->content->setType('html');

                /* insert new label */
                $tags = explode(",", $label);
                if (is_array($tags)) {
                    $labels = array();
                    foreach ($tags as $tag) {
                        if (!empty($tag)) {
                            $labels[] = new Zend_Gdata_App_Extension_Category(trim($tag), 'http://www.blogger.com/atom/ns#');
                        }
                    }
                }
                $entry->setCategory($labels);
                $post_blog_id = $this->insertNewPost($entry, $uri, $service);
                return $post_blog_id;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
    }

    public function getcontent() {
        $this->mod_general->checkUser();
        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');
        $this->load->theme('layout');
        $data['title'] = 'Get content from Site';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if ($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url() . $this->uri->segment(1) . '/getcontent');
        }
        $this->breadcrumbs->add('post', base_url() . $this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();
        if ($this->input->post('urlid')) {
            $this->load->library('html_dom');            
            $html = file_get_html($this->input->post('urlid'));
            redirect(base_url() . 'post/getcontent?url=' . urlencode($this->input->post('urlid')));
        }
        $data['urlContent'] = '';
        if(!empty($_GET['url'])) {
            $url = $_GET['url'];
            $this->load->library('html_dom');            
            $html = file_get_html($url);
            foreach($html->find('div[class=sidebar]') as $item) {
                $item->outertext = '';
            }
            foreach($html->find('header') as $item) {
                $item->outertext = '';
            }
            foreach($html->find('div[id=navitions]') as $item) {
                $item->outertext = '';
            }
            $html->save();

            $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $html);
            $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $html);
            $html = preg_replace('/<link\b[^>]*\/>/is', "", $html);
            $html = preg_replace('/return false/', "return true", $html);
            $html = str_replace('col-', "", $html);
            
            $data['urlContent'] = $html;
        }

        $this->load->view('post/getcontent', $data);
    }

    function get_from_url_id($url, $image_id = '') {

        $this->Mod_general->checkUser();
        $log_id = $this->session->userdata('log_id');
        $user = $this->session->userdata('username');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* Sidebar */
        if (!empty($url)) {
            $this->load->library('html_dom');
            $html = file_get_html($url);

            /* khmer-note */
            if (preg_match('/khmer-note.com/', $url)) {
                $copy_from = 'Khmer Note';
                $title = @$html->find('title', 0)->innertext;
                $title_limon = '';
                foreach ($html->find('div[id=content] .entry') as $code)
                    $code = trim($code->innertext);
                $code = explode('</iframe>          </div></div>', $code);
                if (!empty($code[1])) {
                    $code_arr = explode('<!--Ad Injection:bottom-->', $code[1]);
                    $get_content = $code_arr[0];
                    $image = $this->get_the_image($get_content);
                }

                /* ======= abccambodianews ====== */
            } else if (preg_match('/abccambodianews/', $url)) {
                $copy_from = 'Abc Cambodia News';
                $title = @$html->find('title', 0)->innertext;
                $title_limon = @$html->find('h1.title', 0)->innertext;
                //$title_limon = htmlentities($title_limon);

                foreach ($html->find('div.body_wrap .div_left') as $code)
                    $code = trim($code->innertext);
                $code = explode('push({});  			</script>  		</div>', $code);
                if (!empty($code[1])) {
                    $content = preg_replace('/(<(script|style)\b[^>]*>).*?(<\/\2>)/s', "", $code[1]);
                    $content = preg_replace('/(<(ins|ins)\b[^>]*>).*?(<\/\2>)/s', "", $content);
                    $content = str_replace('src="/images', 'src="http://www.abccambodianews.com/images', $content);
                    $content = str_replace('</ins>', '', $content);
                    $get_content = str_replace('(adsbygoogle = window.adsbygoogle || []).push({});', '', $content);
                    $image = $this->get_the_image($get_content);
                }

                /* ======= abccambodianews ====== */
            } elseif (preg_match('/watphnom-news/', $url)) {
                $copy_from = 'Watphnom News';
                $title_limon = '';
                // ##### Get Content #####
                foreach ($html->find('div[id=sing-content]') as $code)
                    $code = explode('<div style="clear:both"></div> </div>', $code);
                $code = explode('<div class="social4i"', $code[1]);
                $get_content = trim($code[0]);
                $image = $this->get_the_image($get_content);
            } elseif (preg_match('/khmerload.com/', $url)) {
                $copy_from = 'Khmerload';
                $title_limon = '';
                // ##### Get Content  #####
                //$title = @$html->find('title', 0)->innertext;
                foreach ($html->find('div[class=news-content]') as $content)
                    $title = trim($content->find('h1', 0)->innertext);

                foreach ($html->find('div[class=news-content]') as $code)
                    $code = trim($code->innertext);
                $code = explode("</span>   					</div>  				</div>  				<div style='clear:both;'></div>  			</div>", $code);
                $get_content = trim(@$code[1]);
                // ##### Get thumbnail #####
                $image = $this->get_the_image($get_content);

                /* ======= watphnom-news ====== */
            } elseif (preg_match('/readkhmer.com/', $url)) {
                $copy_from = 'Read Khmer';
                $title = @$html->find('title', 0)->innertext;
                // ##### Get title #####   
                $title0 = $html->find('h2[class=entry-title]', 0)->innertext;
                $title1 = explode('</h1>', $title0);
                if (!empty($title1)) {
                    $title_limon = $title1[0];
                } else {
                    $title_limon = $title0;
                }
                $title_limon = trim($title_limon);
                //$title_limon = htmlentities($title_limon);
                // ##### Get Content #####  
                $get_content = trim($html->find('#content .entry', 0)->innertext);

                $code = explode('<div class="entry-tags">', $get_content);
                if (!empty($code[0])) {
                    $get_content = $code[0];
                } else {
                    $get_content = $get_content;
                }
                $get_content = '<div class="khmer">' . $get_content . '</div>';
                // ##### Get Thumbnail #####
                $image = $this->get_the_image($get_content);

                /* ======= end readkhmer ====== */
            } elseif (preg_match('/ve-news.com/', $url)) {
                $copy_from = 've-news';
                $title_limon = '';
                // ##### Get title #####
                foreach ($html->find('div[class=stitle]') as $titles)
                    $title = trim($titles->innertext);

                // ##### Get Content #####
                foreach ($html->find('div[class=sdesc]') as $article)
                    $code = explode('<div style="clear:both"></div> </div>', $article);
                $code = explode('<div class="social4i"', $code[1]);
                $get_content = trim($code[0]);

                // ##### Get thumbnail #####
                $image = $this->get_the_image($get_content);

                /* ======= end ve-news ====== */
            } elseif (preg_match('/news.sabay.com.kh/', $url)) {
                $copy_from = 'news.sabay.com.kh';
                $title_limon = '';
                // ##### Get title #####
                foreach ($html->find('h2[class=post-title]') as $titles)
                    $title = $titles->innertext;
                $maintitle = explode('<span', $title);
                if (!empty($maintitle)) {
                    $title = $maintitle[0];
                } else {
                    $title = @$html->find('title', 0)->innertext;
                }
                // ##### Get Content #####
                foreach ($html->find('div[class=post-content]') as $article)
                    $code = trim($article->innertext);
                $code = str_replace(' Sabay ', 'យើងខ្ញុំ', $article);
                $code = str_replace(' sabay ', 'យើងខ្ញុំ', $code);
                $code = str_replace('<em>', "<em style='display:none;'>", $code);
                $code = str_replace('<p><strong>​ប្រែ​សម្រួល', "<p style='display:none;'><strong>​ប្រែ​សម្រួល", $code);
                $code = str_replace('<span style="color: #000000;"><strong>', '<span style="color: #000000; display:none;"><strong>', $code);
                $code = str_replace('<strong>អត្ថបទ', "<strong style='display:none;'>អត្ថបទ", $code);
                $get_content = str_replace('<p><strong>ប្រភព', "<p style='display:none;'><strong>ប្រភព", $code);
                // ##### Get thumbnail #####
                $image = $this->get_the_image($get_content);

                /* ======= end news.sabay.com.kh ====== */
            } elseif (preg_match('/health.com.kh/', $url)) {
                $copy_from = 'health.com.kh';
                $title_limon = '';
                // ##### Get title #####
                foreach ($html->find('span[class=titledetail]') as $titles)
                    $title = $titles->innertext;
                $title = trim($title);
                // ##### Get Content #####
                foreach ($html->find('div[class=con_left]') as $code)
                    $code = trim($code->innertext);
                $div = "<div>";
                $code = explode("displaytext='Facebook Like'></span>", $code);
                //$code = $code[1];
                $code = $div . $code[1];
                $code = str_replace('src=http://www', 'src="http://www', $code);
                $code = str_replace('.jpg  width=', '.jpg"  width=', $code);
                $code = explode('<a href="http://www.facebook.com', $code);
                $get_content = $code[0];
                // ##### Get thumbnail #####
                $image = $this->get_the_image($get_content);

                /* ======= end health.com.kh ====== */
            } elseif (preg_match('/healthcambodia.com/', $url)) {
                $copy_from = 'Health Cambodia';
                $title_limon = '';
                // ##### Get title #####
                foreach ($html->find('div[id=middle]') as $titles)
                    $title = trim($titles->find('h2', 0)->innertext);

                // ##### Get Content #####
                foreach ($html->find('div[class=entry]') as $code)
                    $get_content = trim($code->innertext);

                // ##### Get thumbnail #####
                $image = str_replace("-300x200", '', $get_content);
                $image = $this->get_the_image($image);

                /* ======= end healthcambodia ====== */
            } elseif (preg_match('/neavea.com/', $url)) {
                $copy_from = 'neavea';
                $title_limon = '';
                $title = $html->find('.post-title-detail', 0)->innertext;

                // ##### Get Content  #####
                foreach ($html->find('.post-text-detail') as $code_detail)
                    $get_content = trim($code_detail->innertext);

                // ##### Get thumbnail #####
                $image = $this->get_the_image($get_content);

                /* ======= end neavea ====== */
            } elseif (preg_match('/855live/', $url)) {
                $copy_from = '855live';
                $title_limon = '';
                $title = $html->find('h1.post-title', 0)->plaintext;
                // ##### Get Content  #####
                $content = $html->find('#left-inside .post-wrapper', 0)->innertext;
                $content = explode("<!-- end .post-info -->", $content);
                if (!empty($content[1])) {
                    $content = explode('<ul class="juiz_sps_links_list"><li class="juiz_sps_item juiz_sps_link_facebook">', $content[1]);
                    $get_content = $content[0];
                }
                // ##### Get thumbnail #####
                $image = $this->get_the_image($get_content);

                /* ======= end 855live ====== */
            } elseif (preg_match('/dap-news/', $url)) {
                $copy_from = 'Dap-news';
                $title_limon = '';
                //$title = $html->find('h1.post-title', 0)->plaintext;
                $title = @$html->find('title', 0)->innertext;

                // ##### Get Content  #####
                $get_content = $html->find('#ja-current-content .article-content', 0)->innertext;

                // ##### Get thumbnail #####
                $image = $this->get_the_image($get_content);

                /* ======= end Dap-news ====== */
            } elseif (preg_match('/vodhotnews/', $url)) {
                $copy_from = 'vodhotnews';
                $title_limon = '';
                $title = $html->find('#description h1', 0)->plaintext;
                //$title = @$html->find('title', 0)->innertext;
                // ##### Get Content  #####
                $get_content = $html->find('#description', 0)->innertext;
                $get_content = explode("</h3>", $get_content);
                if (!empty($get_content[1])) {
                    $get_content = explode('<span style="color:#0000FF;"><strong>', $get_content[1]);
                    $get_content = $get_content[0];
                } else {
                    $get_content = '';
                }

                // ##### Get thumbnail #####
                $image = $this->get_the_image($get_content);

                /* ======= end vodhotnews ====== */
            } elseif (preg_match('/rfa.org/', $url)) {
                $copy_from = 'rfa.org/khmer';
                $title_limon = '';
                //$title = $html->find('#description h1', 0)->plaintext;
                $title = @$html->find('title', 0)->innertext;
                // ##### Get Content  #####
                $get_content = $html->find('#storytext', 0)->innertext;
                $get_content = explode("<h3>", $get_content);
                if (!empty($get_content[0])) {
                    $get_content = $get_content[0];
                } else {
                    $get_content = '';
                }


                // ##### Get thumbnail #####
                $image = @$this->get_the_image($get_content);

                $sound = @$html->find('.storyaudio', 0)->innertext;
                /* ======= end rfa.org ====== */
            } elseif (preg_match('/kohsantepheapdaily/', $url)) {
                $copy_from = 'kohsantepheapdaily';
                $title_limon = '';
                //$title = $html->find('#description h1', 0)->plaintext;
                $title = @$html->find('title', 0)->innertext;
                // ##### Get Content  #####
                $get_content = $html->find('#article .body', 0)->innertext;
                $get_content = str_replace('ព័ត៌មានលម្អិតនឹងផ្សាយជូននៅលើទំព័រកាសែតកោះសន្តិភាព', '', $get_content);
                $get_content1 = explode("<script>", $get_content);
                if (!empty($get_content1[0])) {
                    $get_content = $get_content1[0];
                } else {
                    $get_content = $get_content;
                }

                // ##### Get thumbnail #####
                $image = array();
                /* ======= end kohsantepheapdaily ====== */
            }
            if (!empty($sound)) {
                $sound = $sound;
            } else {
                $sound = '';
            }
            $addOn = '<br/>------------<br/><p>Source from: <a href="' . $url . '" rel="nofollow" target="_blank">' . $copy_from . '</a></p>';
            $get_content = $sound . $get_content . $addOn;
            if (!empty($image)) {
                $thumb = $image['url'];
            } else {
                $thumb = $image_id;
            }
            $data = array(
                'content' => trim($get_content),
                'image' => @$thumb,
                'title' => trim($title),
                'limon_title' => trim($title_limon)
            );
            if (!empty($data)) {
                return $data;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    function blogger_update_post($bid, $pid, $title, $content = '', $label = '', $data = array()) {
        $log_id = $this->session->userdata('log_id');
        $user = $this->session->userdata('username');
        $pass = $this->session->userdata('blogpassword');

        /* data array */
        $new_date = !empty($data[0]['new_date']) ? $data[0]['new_date'] : '1';
        $new_link = !empty($data[0]['new_link']) ? $data[0]['new_link'] : '1';

        /* end data array */
        /* user */
        if (!empty($user) && !empty($pass) && $log_id == 1) {
            
        } else {
            $data_user = array(
                Tbl_title::type => 'config',
                Tbl_title::title => 'my_user_username',
            );
            $data_user_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $data_user);
            if (!empty($data_user_blog)) {
                $user = $data_user_blog[0]->{Tbl_title::object_id};
            }

            /* userpass */
            $data_user_pass = array(
                Tbl_title::type => 'config',
                Tbl_title::title => 'my_user_pass',
            );
            $data_user_pass_blog = $this->Mod_general->select(Tbl_title::tblname, '*', $data_user_pass);
            if (!empty($data_user_pass_blog)) {
                $pass = $data_user_pass_blog[0]->{Tbl_title::object_id};
            }
        }
        if (!empty($pass) && !empty($user)) {
            /* get permission from blog account */
            $this->load->library('zend');
            $this->zend->load('Zend/Loader');
            Zend_Loader::loadClass('Zend_Gdata');
            Zend_Loader::loadClass('Zend_Gdata_Query');
            Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
            /* end get permission from blog account */

            try {

                $client = Zend_Gdata_ClientLogin::getHttpClient(
                                $user, $pass, 'blogger');
                $service = new Zend_Gdata($client);

                $uri = 'http://www.blogger.com/feeds/' . $bid . '/posts/default/' . $pid;
                $response = $service->get($uri);
                $entry = new Zend_Gdata_App_Entry($response->getBody());
                $entry->title = $service->newTitle($title);

                $str = stripslashes($content);
                $str = $str = str_replace("<br />", "\n", $str);
                $entry->content = $service->newContent($str);
                $entry->content->setType('html');

                /* insert new label */
                $tags = explode(",", $label);
                if (is_array($tags)) {
                    $labels = array();
                    foreach ($tags as $tag) {
                        if (!empty($tag)) {
                            $labels[] = new Zend_Gdata_App_Extension_Category(trim($tag), 'http://www.blogger.com/atom/ns#');
                        }
                    }
                }
                $entry->setCategory($labels);
                /* update time */
                if ($new_date == 1) {
                    date_default_timezone_set('Asia/Phnom_Penh');
                    $date = date("c");
                    $dated = new Zend_Gdata_App_Extension_Updated($date);
                    $date_pub = new Zend_Gdata_App_Extension_Published($date);
                    $entry->setUpdated($dated);
                    $entry->setPublished($date_pub);
                }
                /* end update time */
                /* update link */
                //if (!empty($new_link)) {
//                    $newlink = new Zend_Gdata_App_Extension_Link();
//                    $newlink->setHref('http://imgblogs.blogspot.com/2014/09/xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.html');
//                    $newlink->setRel('alternate');
//                    $newlink->setType('text/html');
//                    $newlink->setTitle($title);
//                    $new_linkArray[] = $newlink;
//                    $entry->setLink($new_linkArray);
                //}
                /* end update link */
                $update = $service->updateEntry($entry);
                if ($update) {
                    return TRUE;
                }
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
    }

    function get_the_image($args) {
        /* Search the post's content for the <img /> tag and get its URL. */
        preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $args, $matches);

        /* If there is a match for the image, return its URL. */
        if (isset($matches) && @$matches[1][0])
            return array('url' => @$matches[1][0]);
        return false;
    }

    function get_full_yt_playlist($param, $data_yl = array()) {
        foreach ($param->entry as $entry) {
            $url = $entry->link['href'];
            $title = $entry->title;
            preg_match("/v=([^&]+)/i", $url, $code_link);
            $codeID = $code_link[1];
            $vid_type = 'yt';
            $data_yl[]['entry'] = array(
                'title' => (string) $title,
                'vid' => $codeID,
                'type' => $vid_type,
            );
        }
        return $data_yl;
    }

    /* get video type from content */

    function get_yt($param, $title) {
        $list = '<script>$(function(){var list =[{"idGD": "';
        $last_key = end(array_keys($param));
        foreach ($param as $key => $value) {
            if ($key == $last_key) {
                $list .= $value->{Tbl_meta::value};
            } else {
                //$list .= $value->{Tbl_meta::value} . '0!?^0!?A';
                $list .= $value->{Tbl_meta::value} . '?^0!7x0k!iYAt';
            }
        }
        $list .='","title": "' . $title . '","type": "1"}];
loadall(list,"TY");
});</script><style>.rm-rep-frame{height:330px}</style>';
//    foreach ($param as $vdo) {
//        $list .= '<div align="center"><div style="font-size: 16px;font-weight: bold; padding:10px; clear:both; width:90%;overflow:hidden;margin-bottom:2px; text-align:center;border:1px solid #555;">';
//        $list .= '<a href="/p/player.html?http://youtu.be/' . $vdo->{Tbl_meta::value} . '" target="_blank">' . $vdo->{Tbl_meta::key} . '</a>';
//        $list .= '</div></div>';
//    }
        return $list;
    }

    function get_On_yt($param, $title, $thumb = '') {
        $list = '<script>$(function(){var list =[{"idGD": "';
        $last_key = end(array_keys($param));
        foreach ($param as $key => $value) {
            if ($key == $last_key) {
                $list .= $value->{Tbl_meta::value};
            } else {
                $list .= $value->{Tbl_meta::value} . '0!?^0!?A';
            }
        }
        if (!empty($thumb)) {
            $thumb = $thumb;
        } else {
            $thumb = 'http://3.bp.blogspot.com/-c9VaNAeHkGg/UQ4T7EPwlLI/AAAAAAAAAUQ/ns1lvSm5lf8/s100/fancybox_play-button-icon.png';
        }
        $list .='","title": "' . $title . '","type": "1","image": "' . $thumb . '"}];
loadall(list,"onYT");
});</script><style>.rm-rep-frame{height:330px}</style>';
//    foreach ($param as $vdo) {
//        $list .= '<div align="center"><div style="font-size: 16px;font-weight: bold; padding:10px; clear:both; width:90%;overflow:hidden;margin-bottom:2px; text-align:center;border:1px solid #555;">';
//        $list .= '<a href="/p/player.html?http://youtu.be/' . $vdo->{Tbl_meta::value} . '" target="_blank">' . $vdo->{Tbl_meta::key} . '</a>';
//        $list .= '</div></div>';
//    }
        return $list;
    }

    function get_iframe($param, $title, $thumb) {
        $list = '<script>$(function(){var list =[{"idGD": "';
        $last_key = end(array_keys($param));
        foreach ($param as $key => $value) {
            if ($key == $last_key) {
                $list .= $value->{Tbl_meta::value};
            } else {
                $list .= $value->{Tbl_meta::value} . '0!?^0!?A';
            }
        }
        $title_arr = $param[1]->{Tbl_meta::key};
        $title_arr = explode(' - part', $title_arr);
        if (!empty($title)) {
            $titl = $title;
        } else {
            $titl = $title_arr[0];
        }
        if (!empty($thumb)) {
            $thumb = $thumb;
        } else {
            $thumb = 'http://3.bp.blogspot.com/-c9VaNAeHkGg/UQ4T7EPwlLI/AAAAAAAAAUQ/ns1lvSm5lf8/s100/fancybox_play-button-icon.png';
        }
        $list .='","title": "' . $titl . '","type": "1","image": "' . $thumb . '"}];
loadall(list,"iframe");
});</script><style>.rm-rep-frame{height:330px}</style>';
//    foreach ($param as $vdo) {
//        $list .= '<div align="center"><div style="font-size: 16px;font-weight: bold; padding:10px; clear:both; width:90%;overflow:hidden;margin-bottom:2px; text-align:center;border:1px solid #555;">';
//        $list .= '<a href="/p/mega.html?' . $vdo->{Tbl_meta::value} . '" target="_blank">' . $vdo->{Tbl_meta::key} . '</a>';
//        $list .= '</div></div>';
//    }
        return $list;
    }

    function get_vimeo($param, $title, $thumb = 'http://3.bp.blogspot.com/-c9VaNAeHkGg/UQ4T7EPwlLI/AAAAAAAAAUQ/ns1lvSm5lf8/s100/fancybox_play-button-icon.png') {
        $list = '<script>$(function(){var list =[';
        $last_key = end(array_keys($param));
        $i = 0;
        $last_key = end(array_keys($param));
        foreach ($param as $key => $value) {
            $i++;
            if ($key == $last_key) {
                $list .= '{"file": "https://vimeo.com/' . $value->{Tbl_meta::value} . '","title": "' . $title . '","description": "Part ' . $i . '","image": "' . $thumb . '"}];';
            } else {
                $list .= '{"file": "https://vimeo.com/' . $value->{Tbl_meta::value} . '","title": "' . $title . '","description": "Part ' . $i . '","image": "' . $thumb . '"},';
            }
        }
        $list .='loadall(list,"vimeo");
});</script><style>.rm-rep-frame{height:330px}</style>';
//    foreach ($param as $vdo) {
//        $list .= '<div align="center"><div style="font-size: 16px;font-weight: bold; padding:10px; clear:both; width:90%;overflow:hidden;margin-bottom:2px; text-align:center;border:1px solid #555;">';
//        $list .= '<a href="/p/mega.html?http://player.vimeo.com/video/' . $vdo->{Tbl_meta::value} . '" target="_blank">' . $vdo->{Tbl_meta::key} . '</a>';
//        $list .= '</div></div>';
//    }
        return $list;
    }

    function get_gdocs($param, $title, $thumb = '') {
        $list = '<script>$(function(){var list =[{"idGD": "';
        $last_key = end(array_keys($param));
        foreach ($param as $key => $value) {
            if ($key == $last_key) {
                $list .= $this->RandomStr(1) . $value->{Tbl_meta::value} . $this->RandomStr(1);
            } else {
                $list .= $this->RandomStr(1) . $value->{Tbl_meta::value} . $this->RandomStr(1) . '?^0!AB7Ik!Tx';
            }
        }
        $title_arr = @$param[1]->{Tbl_meta::key};
        $title_arr = @explode(' - part', $title_arr);
        if (!empty($title)) {
            $titl = $title;
        } else {
            $titl = $title_arr[0];
        }
        if (!empty($thumb)) {
            $thumb = $thumb;
        } else {
            $thumb = 'http://3.bp.blogspot.com/-c9VaNAeHkGg/UQ4T7EPwlLI/AAAAAAAAAUQ/ns1lvSm5lf8/s100/fancybox_play-button-icon.png';
        }
        $list .='","title": "' . $titl . '","type": "1","image": "' . $thumb . '"}];
loadall(list,"GDoc");
});</script><style>.rm-rep-frame{height:330px}</style>';
        $i = 0;
//    foreach ($param as $vdo) {
//        $i++;
//        if ($i == 1) {
//            $start = '?start=0';
//        } else {
//            $start = '?start=1';
//        }
//        $list .= '<div align="center"><div style="font-size: 16px;font-weight: bold; padding:10px; clear:both; width:90%;overflow:hidden;margin-bottom:2px; text-align:center;border:1px solid #555;">';
//        $list .= '<a href="/p/mega.html?https://docs.google.com/file/d/' . $vdo->{Tbl_meta::value} . '/preview' . $start . '" target="_blank">' . $vdo->{Tbl_meta::key} . '</a>';
//        $list .= '</div></div>';
//    }
        return $list;
    }

    function get_dailymotion($param, $title) {
        $list = '<script>$(function(){var list =[';
        $last_key = end(array_keys($param));
        $i = 0;
        $last_key = end(array_keys($param));
        foreach ($param as $key => $value) {
            $i++;
            if ($key == $last_key) {
                $list .= '{"file": "' . $value->{Tbl_meta::value} . '?autoPlay=0&hideInfos=0","title": "' . $title . '","description": "Part ' . $i . '","image": "http://www.dailymotion.com/thumbnail/video/' . $value->{Tbl_meta::value} . '"}];';
            } else {
                $list .= '{"file": "' . $value->{Tbl_meta::value} . '?autoPlay=0&hideInfos=0","title": "' . $title . '","description": "Part ' . $i . '","image": "http://www.dailymotion.com/thumbnail/video/' . $value->{Tbl_meta::value} . '"},';
            }
        }
        $list .='loadall(list,"dailymotion");
});</script><style>.rm-rep-frame{height:330px}</style>';
//    foreach ($param as $vdo) {
//        $list .= '<div align="center"><div style="font-size: 16px;font-weight: bold; padding:10px; clear:both; width:90%;overflow:hidden;margin-bottom:2px; text-align:center;border:1px solid #555;">';
//        $list .= '<a href="/p/mega.html?http://www.dailymotion.com/embed/video/' . $vdo->{Tbl_meta::value} . '" target="_blank">' . $vdo->{Tbl_meta::key} . '</a>';
//        $list .= '</div></div>';
//    }
        return $list;
    }

    function get_yt_single($param, $title) {
        $list = '<iframe width="100%" height="315" src="' . $param[0]->{Tbl_meta::value} . '" frameborder="0" allowfullscreen></iframe>';
        return $list;
    }

    function get_fbvid($param, $title) {
        $list = '<script type="text/javascript">window.onload = function(){var iframe = document.createElement("iframe"); iframe.frameBorder = 0; iframe.width = "100%"; iframe.height = "315px"; iframe.id = "randomid"; iframe.scrolling = "no"; iframe.setAttribute("src", fbplayer + "' . $param[0]->{Tbl_meta::value} . '"); iframe.setAttribute("allowfullscreen", ""); document.getElementById("fbplayer").appendChild(iframe); }</script>';
        return $list;
    }

    function resize_image($url, $imgsize) {
        if (preg_match('/blogspot/', $url)) {
            //inital value
            $newsize = "s" . $imgsize;
            $newurl = "";
            //Get Segments
            $path = parse_url($url, PHP_URL_PATH);
            $segments = explode('/', rtrim($path, '/'));
            //Get URL Protocol and Domain 
            $parsed_url = parse_url($url);
            $domain = $parsed_url['scheme'] . "://" . $parsed_url['host'];

            $newurl_segments = array(
                $domain . "/",
                $segments[1] . "/",
                $segments[2] . "/",
                $segments[3] . "/",
                $segments[4] . "/",
                $newsize . "/", //change this value
                $segments[6]
            );
            $newurl_segments_count = count($newurl_segments);
            for ($i = 0; $i < $newurl_segments_count; $i++) {
                $newurl = $newurl . $newurl_segments[$i];
            }
            return $newurl;
        } else {
            return $url;
        }
    }

    /* end get video type from content */

    function check_v_type($param) {
        if (preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $param)) {
            $v_type = 'yt';
        } elseif (preg_match('/vimeo/', $param)) {
            $v_type = 'vimeo';
        } elseif (preg_match('/docs.google/', $param)) {
            $v_type = 'docs.google';
        } elseif (preg_match('/dailymotion/', $param)) {
            $v_type = 'dailymotion';
        } else {
            $v_type = '';
        }
        return $v_type;
    }

    function get_video_id($param, $videotype = '') {
        $v_type = $this->check_v_type($param);
        switch ($v_type) {
            case 'yt':
                preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $param, $matches);
                if (!empty($matches[1])) {
                    $content = (!empty($matches[1]) ? $matches[1] : '');
                    if (!empty($videotype)) {
                        $v_id = 'http://www.youtube-nocookie.com/embed/' . $content;
                        $v_type = 'iframe';
                    } else {
                        $v_id = $content;
                    }
                }
                break;
            case 'vimeo':
                preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $param, $matches);
                if (!empty($matches[5])) {
                    if (!empty($videotype)) {
                        if ($videotype != 'vimeoDomain') {
                            $v_id = 'http://player.vimeo.com/video/' . $matches[5];
                            $v_type = 'iframe';
                        } else {
                            $v_id = $matches[5];
                        }
                    } else {
                        $v_id = $matches[5];
                    }
                }
                break;
            case 'docs.google':
                $g_array = explode('/', $param);
                if (!empty($g_array[5])) {
                    $v_ids = $g_array[5];
                } else {
                    $v_ids = $param;
                }
                if (!empty($videotype)) {
                    $v_id = 'https://docs.google.com/file/d/' . $v_ids . '/preview';
                    $v_type = 'iframe';
                } else {
                    $v_id = $v_ids;
                }
                break;
            case 'dailymotion':
                preg_match('#http://www.dailymotion.com/embed/video/([A-Za-z0-9]+)#s', $param, $matches);
                if (!empty($matches[1])) {
                    $v_ids = $matches[1];
                } else {
                    $v_ids = $param;
                }
                if (!empty($videotype)) {
                    $v_id = 'http://www.dailymotion.com/embed/video/' . $v_ids . '?autoPlay=0&hideInfos=0';
                    $v_type = 'iframe';
                } else {
                    $v_id = $v_ids;
                }
                break;

            default:
                $v_id = $param;
                $v_type = 'iframe';
                break;
        }
        $data = array(
            'vid' => $v_id,
            'vtype' => $v_type
        );
        return $data;
    }

    public function sitekmobilemovie($param = '', $title = '', $thumb = '', $post_id = '', $videotype = '') {
        $log_id = $this->session->userdata('log_id');
        if (!empty($post_id)) {
            $edit = 1;
            $vdo_type = 'vdolist';
            $where_del = array(
                Tbl_meta::object_id => $post_id,
                Tbl_meta::user_id => $log_id,
                Tbl_meta::type => $vdo_type,
            );
            $dataLogin = $this->Mod_general->delete(Tbl_meta::tblname, $where_del);
        } else {
            $edit = '';
        }

        $continue = (!empty($_GET['continue']) ? $_GET['continue'] : '');
        $postid = (!empty($_GET['postid']) ? $_GET['postid'] : '');
        $vtype = (!empty($_GET['type']) ? $_GET['type'] : '');
        $edit = (!empty($_GET['edit']) ? $_GET['edit'] : $edit);
        $param = (string) $param;
        if (empty($continue)) {
            $this->load->library('html_dom');
            $log_id = $this->session->userdata('log_id');
            $html = file_get_html($param);
            if (preg_match('/callback=getPost/', $html)) {
                $url = $html->find('#playlistPanel script', 0)->src;
                $imageid = $html->find('link[rel=image_src]', 0)->href;
                $list_title = $html->find('h3.post-title', 0)->innertext;
                if (!empty($title)) {
                    $list_title = $title;
                } else {
                    $list_title = $list_title;
                }
                preg_match('@^(?:http://)?([^/]+)@i', $param, $matches);
                $host = 'http://' . $matches[1];
                if (preg_match('/kmobilemovie/', $url)) {
                    $list_url = $url;
                } else {
                    if (!empty($host)) {
                        $list_url = $host . $url;
                    } else {
                        $list_url = 'http://kmobilemovie.blogspot.com' . $url;
                    }
                }
                $list_url = str_replace('alt=json-in-script', '', $list_url);
                $list_url = str_replace('&callback=getPost', '', $list_url);
                //http://kmobilemovie.blogspot.com/feeds/posts/default/-/Z-Diary%20Of%20Love?alt=rss&start-index=1&max-results=200&callback=getPost
                $html1 = simplexml_load_file($list_url);
                if (!empty($html1)) {
                    foreach ($html1->entry as $value) {
                        $xmlns = $value->children('http://www.w3.org/2005/Atom');
                        $title = $value->title;
                        $link = $value->link;
                        foreach ($link as $value_link) {
                            $link = $value_link->attributes();
                            if ($link['rel'] == 'alternate') {
                                $link = $link['href'];
                            }
                        }
                        if (!empty($link)) {
                            $data_post_id = array(
                                Tbl_meta::type => 'not_in_use',
                                Tbl_meta::user_id => $log_id,
                                Tbl_meta::value => (string) $link,
                            );
                            $dataPostID = $this->Mod_general->insert(Tbl_meta::tblname, $data_post_id);
                        }
                    }

                    /* check title */
                    if (!empty($post_id)) {
                        $where_title = array(
                            Tbl_title::object_id => $log_id,
                            Tbl_title::id => $post_id,
                        );
                        $dataCheckTitle = $this->Mod_general->select(Tbl_title::tblname, Tbl_title::id, $where_title);
                    } else {
                        $where_movie = array(
                            Tbl_title::type => 'vdolist',
                            Tbl_title::object_id => $log_id,
                        );
                        $seach_movie = array(
                            Tbl_title::title => trim($title),
                        );
                        $dataCheckTitle = $this->Mod_general->like(Tbl_title::tblname, '*', $seach_movie, $where_movie);
                    }

                    $thumb = (!empty($thumb) ? $thumb : '');
                    if (!empty($dataCheckTitle)) {
                        $post_id = $dataCheckTitle[0]->{Tbl_title::id};
                        if (!empty($thumb)) {
                            $where_titles = array(
                                Tbl_title::id => $post_id,
                            );
                            $data_titles = array(
                                Tbl_title::image => $thumb,
                            );
                            $query_blog = $this->Mod_general->update(Tbl_title::tblname, $data_titles, $where_titles);
                        }
                    } else {
                        /* end check title */

                        $data_title = array(
                            Tbl_title::title => trim($list_title),
                            Tbl_title::type => 'vdolist',
                            Tbl_title::object_id => $log_id,
                            Tbl_title::image => $imageid,
                        );
                        $vdo_title_d = $this->Mod_general->insert(Tbl_title::tblname, $data_title);
                    }
                    $vdo_title_d = !empty($post_id) ? $post_id : $vdo_title_d;
                    /* delete not in use */
                    $data_not_sel = array(
                        Tbl_meta::type => 'not_in_use',
                        Tbl_meta::user_id => $log_id,
                        Tbl_meta::object_id => $vdo_title_d,
                    );
                    $delete = $this->Mod_general->delete(Tbl_meta::tblname, $data_not_sel);
                    /* end delete not in use */
                    redirect(base_url() . 'post/sitekmobilemovie?continue=' . $dataPostID . '&postid=' . $vdo_title_d . '&edit=' . $edit . '&type=' . $videotype);
                }
            }
        } else {
            $get_data_link = $this->sitekmobilemovie_continue($continue, $postid, $vtype);
            if (!empty($get_data_link)) {
                echo '<script type="text/javascript">window.location = "' . base_url() . 'post/sitekmobilemovie?continue=' . $get_data_link . '&postid=' . $postid . '&edit=' . $edit . '&type=' . $vtype . '"</script>';
            } else {
                if (!empty($edit)) {
                    redirect(base_url() . 'post/getcode/edit/' . $postid);
                    //redirect(base_url() . 'post/getcode?id=' . $postid);
                } else {
                    redirect(base_url() . 'post/getcode?id=' . $postid);
                }
                exit();
            }
        }
        die;
        //return $list_url;
    }

    function sitekmobilemovie_continue($continue, $vdo_title_d, $videotype = '') {
        $this->load->library('html_dom');
        $log_id = $this->session->userdata('log_id');
        try {
            $data_post_sel = array(
                Tbl_meta::id => $continue,
            );
            $order = Tbl_meta::id . ' DESC';
            $get_data_link = $this->Mod_general->select(Tbl_meta::tblname, '', $data_post_sel, $order, '', 1);
            foreach ($get_data_link as $value_link) {
                $getlink = $value_link->{Tbl_meta::value};
                $getID = $value_link->{Tbl_meta::id};
                $html1 = file_get_html($getlink);
                $vid = @$html1->find('#Blog1 .post-body iframe', 0)->src;
                if (!empty($vid)) {
                    $v_type = $this->check_v_type($vid);
                    $data_list = $this->get_video_id($vid, $v_type);
                    if (!empty($data_list['vid'])) {
                        $v_id = $data_list['vid'];
                        $v_type = $data_list['vtype'];
                    }
                } else {
                    $vid_scr = @$html1->find('#Blog1 .post-body script', 0)->innertext;
                    $vid_arr = explode("'", $vid_scr);
                    if (!empty($vid_arr)) {
                        $v_ty = (string) $vid_arr[1];
                        switch ($v_ty) {
                            case 'googledrive':
                                if (!empty($videotype)) {
                                    $v_type = 'iframe';
                                    $vids = (string) $vid_arr[3];
                                    $vid = 'http://docs.google.com/file/d/' . $vids . '/preview';
                                } else {
                                    $v_type = 'docs.google';
                                    $vid = (string) $vid_arr[3];
                                }
                                break;
                            case 'youtube':
                                if (!empty($videotype)) {
                                    $v_type = 'iframe';
                                    $vids = (string) $vid_arr[3];
                                    $vid = 'http://www.youtube-nocookie.com/embed/' . $vids;
                                } else {
                                    $v_type = 'yt';
                                    $vid = (string) $vid_arr[3];
                                }
                                break;
                            case 'dailymotion':
                                if (!empty($videotype)) {
                                    $v_type = 'iframe';
                                    $vids = (string) $vid_arr[3];
                                    $vid = 'http://www.dailymotion.com/embed/video/' . $vids . '?autoPlay=0&hideInfos=0';
                                } else {
                                    $v_type = 'dailymotion';
                                    $vid = (string) $vid_arr[3];
                                }
                                break;
                            case 'vidme':
                                $v_type = 'iframe';
                                $vid = (string) $vid_arr[3];
                                $vid = 'https://vid.me/e/' . $vid;
                                break;
                            case 'vimeo':
                                if (!empty($videotype)) {
                                    $v_type = 'iframe';
                                    $vids = (string) $vid_arr[3];
                                    $vid = 'http://player.vimeo.com/video/' . $vids;
                                } else {
                                    $v_type = 'vimeo';
                                    $vid = (string) $vid_arr[3];
                                }
                                break;
                        }
                    }
                }
                $list_title = $html1->find('h3.post-title', 0)->innertext;
                $dataAddVid = $this->Mod_general->add_meta($vid, '', $list_title, $v_type, $vdo_title_d);
                $delete = $this->Mod_general->delete(Tbl_meta::tblname, array(Tbl_meta::id => $getID));
            }
            $data_post_sel = array(
                Tbl_meta::type => 'not_in_use',
                Tbl_meta::user_id => $log_id,
            );
            $get_data_next = $this->Mod_general->select(Tbl_meta::tblname, '', $data_post_sel, $order, '', 1);
            if (!empty($get_data_next)) {
                foreach ($get_data_next as $value_next) {
                    return $value_next->{Tbl_meta::id};
                }
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function snippingnews() {
        $data['title'] = 'Snipping news';
        $this->Mod_general->checkUser();
        $backto = base_url() . 'post/blogpassword';
        $query_blog = $this->Mod_general->blogcheck(current_url(), $backto);
        $log_id = $this->session->userdata('log_id');

        /* Sidebar */
        $menuPermission = $this->Mod_general->getMenuUser();
        $data['menuPermission'] = $menuPermission;
        /* form */
        if ($this->input->post('submit')) {
            $videotype = '';
            $title = $this->input->post('title');
            $content = $this->input->post('content');
            $url = $this->input->post('url');
            if (!empty($url) || !empty($content)) {
                if (!empty($url)) {
                    $code = $this->get_from_url_id($url);
                    $contents_site = $code['content'];
                    $titles = $code['title'];
                    $contents = preg_replace("/<img[^>]+\>/i", "", $contents_site);
                } else {
                    $titles = $title;
                    $contents = $this->input->post('content');
                }
                echo "<html>" . "\r\n";
                echo "<head>" . "\r\n";
                echo '<meta charset="utf-8">' . "\r\n";
                echo "<link href='http://fonts.googleapis.com/css?family=Battambang' rel='stylesheet' type='text/css'>" . "\r\n";
                echo "<style>
body{font-family: 'Battambang', cursive;}
.mywidth {width:320px; margin:0 auto;padding:0;word-wrap: break-word;line-height:35px;font:normal normal 15px Kh Battambang}
.header {word-wrap: break-word;line-height:35px;font:normal normal 20px Kh Muol;color:#0052FF}
.back{font-size:17px;border-radius:3px;padding:6px 10px;color:#fff;text-align:center;display:inline-block;text-shadow:1px 1px 3px #666;cursor:pointer;border:1px solid rgba(255,255,255,0.3);font-family:GoodWeb-Book,Helvetica Neue,Helvetica,Arial,sans-serif;background:#0000ff;float:right}
</style>";
                echo "</head>" . "\r\n";
                echo "<body>" . "\r\n";
                echo '<a href="'.  base_url().'post/snippingnews" title="money_​format" class="back" align="right">Back</a>';
                echo '<a href="'.  base_url().'post/getcode" title="money_​format" class="back" align="right">Post news</a>';
                echo '<div class="mywidth">' . "\r\n";
                echo "<h2 class='header'>$titles</h2>" . "\r\n";
                echo "<p>" . "\r\n";
                echo $contents;
                echo "</p>\r\n";
                echo "</div>\r\n";
                echo '<a href="'.  base_url().'post/snippingnews" title="money_​format" class="back" align="right">Back</a>';
                echo '<a href="'.  base_url().'post/getcode" title="money_​format" class="back" align="right">Post news</a>';
                echo "</body>\r\n";
                echo "</html>\r\n";
            }
        } else {
            /* end form */
            $data['addJsScript'] = array(
            "$(document).ready(function(){
                $( \"#url\" ).blur(function(){
                    var urls = $(this).val();
                    if(urls) {
                        $(\"#ctitle\").hide();
                        $(\"#bycontent\").hide();
                    } else {
                        $(\"#ctitle\").show();
                        $(\"#bycontent\").show();
                    }
                });

                $( \"#tile\" ).blur(function(){
                    var tilea = $(this).val();
                    if(tilea) {
                        $(\"#curl\").hide();
                        $(\"#contents\").addClass(\"required\");
                    } else {
                        $(\"#curl\").show();
                        $(\"#contents\").removeClass(\"required\");
                    }
                });
                
                $( \"#contents\" ).blur(function(){
                    var con = $(this).val();
                    if(con) {
                        $(\"#curl\").hide();
                        $(\"#tile\").addClass(\"required\");
                    } else {
                        $(\"#curl\").show();
                        $(\"#tile\").removeClass(\"required\");
                    }
                });                
            });
            "
        );
            $this->load->view('post/snippingnews', $data);
        }
    }

    public function imgupload() {
        include 'imgupload.php';
        define('DIR', FCPATH . 'application/libraries/upload/');
        if ($this->input->post('submit')) {
            if (!empty($_FILES['Filedata'])) {
                $resize = $this->input->post('resize');
                $watermark = $this->input->post('watermark');
                $this->load->library('upload');
                \ChipVN\Loader::registerAutoLoad();

                ##################### START CONFIG #######################
                $sitename = 'merlroeung';
                /**
                 * CHMOD folder 777
                 */
                $tempdir = DIR . 'temp/';
                /* logo */
                $logolist = array(
                    1 => 'logo1.png',
                    2 => 'logo2.png',
                    3 => 'logo3.png',
                );
                /* logo1.png */
                $default['logo'] = 'logo1.png';

                /* logo (right bottom, right center, right top, left top, .v.v.) */
                $logoPosition = 'rb';

                /* resize */
                $resizelist = array(
                    0 => 0, // ko resize
                    1 => 100,
                    2 => 150,
                    3 => 320,
                    4 => 640,
                    5 => 800,
                    6 => 1024
                );
                $default['resize'] = 800;
                $watermark = 0;
                $logoid = array();

                ##################### END CONFIG #######################
                $watermark = $watermarkid > 0 ? TRUE : FALSE;
                $logoPath = DIR . 'logo/' . (in_array($logoid, array_keys($logolist)) ? $logolist[$logoid] : $default['logo']);
                $resizeWidth = !empty($resizeid) ? $resizeid : $default['resize'];

                if ($_FILES['Filedata'] AND ! $_FILES['Filedata']['error']) {
                    move_uploaded_file($_FILES['Filedata']['tmp_name'], $imagePath = $tempdir . $sitename . date('dmY') . '.jpg');
                    $isUpload = TRUE;
                } else if ($url = trim($_POST['url'])) {
                    $isUpload = FALSE;
                    \ChipVN\Image::leech($url, $imagePath = $tempdir . $sitename . date('dmY') . '.jpg');
                }

                /* resize */
                if ($resizeWidth > 0) {
                    \ChipVN\Image::resize($imagePath, $resizeWidth, 0);
                }
                /* watermark */
                if ($watermark) {
                    \ChipVN\Image::watermark($imagePath, $logoPath, $logoPosition);
                }

                $service = 'Picasa';
                $uploader = \ChipVN\Image_Uploader::factory($service);
                $uploader->login('socheate@gmail.com', '0689989@Sn');

                if (!$imagePath) {
                    die('Mising an image');
                }
                $url = $uploader->upload($imagePath);

                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }

                if ($isUpload) {
                    echo 'image=' . $url;
                } else {
                    echo $url;
                }
                /* end insert product image */
            }
        }
    }

    function uploadImg($FILES, $tempdir_thumb, $name = '') {
        move_uploaded_file($FILES['tmp_name'], $tempdir_thumb . $name);
    }

    function get_site_content($html, $videotype = '') {
        if (preg_match('/Blog1/', $html)) {
            foreach ($html->find('#Blog1') as $article) {
                $content = $article;
            }
        } else if (preg_match('/page-main/', $html)) {
            foreach ($html->find('#page-main .entry') as $article) {
                $content = $article;
            }
        } else {
            $content = $html;
        }
        $list_id = array();
        if (preg_match('/videogallery-con/', $content)) {
            $i = 0;
            foreach ($content->find('.videogallery-con iframe') as $e) {
                $i++;
                $data_list = $this->get_video_id($e->src, $videotype);
                if (!empty($data_list['vid'])) {
                    $list_id[$i] = array(
                        'list' => $data_list['vid'],
                        'vtype' => $data_list['vtype']
                    );
                }
            }
        } else if (preg_match('/data-vid/', $content)) {
            $i = 0;
            $code = trim(@$content->find('#listVideo li', 0)->innertext);
            $code1 = trim(@$content->find('#vList li', 0)->innertext);
            $code2 = trim(@$content->find('#smart li', 0)->innertext);
            if (!empty($code)) {
                $code_id = @$content->find('#listVideo li');
            } else if ($code1) {
                $code_id = @$content->find('#vList li');
            } else if ($code2) {
                $code_id = @$content->find('#smart li');
            }
            foreach ($code_id as $e) {
                $i++;
                $vid = $e->attr['data-vid'];
                $vid = trim($vid);
                $type = 'docs.google';
                $source_type = '';
                if (empty($source_type)) {
                    if (strlen($vid) >= 6 && strlen($vid) <= 7) {
                        $source_type = 'd';
                    } else if (strlen($vid) == 11) {
                        $source_type = 'y';
                    } else if (strlen($vid) >= 8 && strlen($vid) < 10) {
                        $source_type = 'v';
                    } else if (strlen($vid) == 28) {
                        $source_type = 'g';
                    } else if (strlen($vid) >= 3 && strlen($vid) < 6) {
                        $source_type = 'vid';
                    } else {
                        $source_type = 'f';
                    }
                }

                switch ($source_type) {
                    case 'v':
                        $type = 'vimeo';
                        if (!empty($videotype)) {
                            $v_id = 'http://player.vimeo.com/video/' . $vid;
                        } else {
                            $v_id = $vid;
                        }
                        break;
                    case 'y':
                        $type = 'yt';
                        if (!empty($videotype)) {
                            $v_id = 'http://www.youtube-nocookie.com/embed/' . $vid;
                        } else {
                            $v_id = $vid;
                        }
                        break;
                    case 'g':
                        $type = 'docs.google';
                        if (!empty($videotype)) {
                            $v_id = 'http://docs.google.com/file/d/' . $vid . '/preview';
                        } else {
                            $v_id = $vid;
                        }
                        break;
                    case 'd':
                        $type = 'dailymotion';
                        if (!empty($videotype)) {
                            $v_id = 'http://www.dailymotion.com/embed/video/' . $vid . '?autoPlay=0&hideInfos=0';
                        } else {
                            $v_id = $vid;
                        }
                        break;
                    case 'vid':
                        $type = 'iframe';
                        if (!empty($videotype)) {
                            $v_id = 'https://vid.me/e/' . $vid;
                        } else {
                            $v_id = 'https://vid.me/e/' . $vid;
                        }
                        break;
                    case 'f':
                        $type = 'iframe';
                        $v_id = $vid;
                        break;
                }
                $list_id[$i] = array(
                    'list' => $v_id,
                    'vtype' => $type,
                );
            }
        } else if (preg_match('/player.html/', $content)) {
            $strs = <<<HTML
'.$content.'
HTML;
            $html = str_get_html($strs);
            $i = 0;
            foreach ($html->find('div[align=center] div a[target=_blank]') as $e) {
                $i++;
                $getid = explode('/p/player.html?', $e->href);
                $content = $getid = $this->youtubecode($getid[1]);
                if (!empty($videotype)) {
                    $v_id = 'http://www.youtube-nocookie.com/embed/' . $content;
                } else {
                    $v_id = $content;
                }
                $list_id[$i] = array(
                    'list' => $v_id,
                    'vtype' => 'yt',
                );
            }
        } else if (preg_match('/ytlist/', $content)) {
            preg_match("/ytlist = '([^&]+)/i", $content, $code);
            $content = explode(",';", $code[1]);
            $content = explode(",", $content[0]);
            $count = count($content);


            for ($i = 0; $i < $count; $i++) {
                if (!empty($videotype)) {
                    $v_id = 'http://www.youtube-nocookie.com/embed/' . $content[$i];
                } else {
                    $v_id = $content[$i];
                }
                $list_id[$i] = array(
                    'list' => $v_id,
                    'vtype' => 'yt',
                );
            }
        }

        /* GET CODE BY LIST (YTV_movies) */ else if (preg_match('/YTV_movies/', $content)) {
            $str = <<<HTML
'.$content.'
HTML;

            $html = str_get_html($str);
            $i = 0;
            foreach ($html->find('div[class=YTV_movies] a') as $article) {
                $code = $article->rel;
                if (!empty($code)) {
                    $code = explode("'", $code);
                    $content = $code[3];
                    $title = $article->innertext;
                    if (!empty($videotype)) {
                        $v_id = 'http://www.youtube-nocookie.com/embed/' . $content;
                    } else {
                        $v_id = $content;
                    }
                    $list_id[$i] = array(
                        'list' => $v_id,
                        'vtype' => 'yt',
                    );
                }
                $i++;
            }
        } /* GET CODE BY LIST by (JW Player) */ else if (preg_match('/file":/', $content) || preg_match("/file':/", $content)) {
            $article = $content;
            $code = trim(@$article->find('script', 0)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 0)->innertext);
            }
            $code = trim(@$article->find('script', 1)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 1)->innertext);
            }
            $code = trim(@$article->find('script', 2)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 2)->innertext);
            }
            $code = trim(@$article->find('script', 3)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 3)->innertext);
            }
            $code = trim(@$article->find('script', 4)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 4)->innertext);
            }
            $code = trim(@$article->find('script', 5)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 5)->innertext);
            }
            $code = trim(@$article->find('script', 6)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 6)->innertext);
            }
            $code = trim(@$article->find('script', 7)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 7)->innertext);
            }
            $code = trim(@$article->find('script', 8)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 8)->innertext);
            }
            $code = trim(@$article->find('script', 9)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 9)->innertext);
            }
            $code = trim(@$article->find('script', 10)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 10)->innertext);
            }
            $code = trim(@$article->find('script', 11)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 11)->innertext);
            }
            $code = trim(@$article->find('script', 12)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 12)->innertext);
            }
            $code = trim(@$article->find('script', 13)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 13)->innertext);
            }
            $code = trim(@$article->find('script', 14)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 14)->innertext);
            }
            $code = trim(@$article->find('script', 15)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 15)->innertext);
            }
            $code = trim(@$article->find('script', 16)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 16)->innertext);
            }
            $code = trim(@$article->find('script', 17)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 17)->innertext);
            }
            $code = trim(@$article->find('script', 18)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 18)->innertext);
            }
            $code = trim(@$article->find('script', 19)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 19)->innertext);
            }
            $code = trim(@$article->find('script', 20)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 20)->innertext);
            }
            $code = trim(@$article->find('script', 21)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 21)->innertext);
            }
            $code = trim(@$article->find('script', 22)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 22)->innertext);
            }
            $code = trim(@$article->find('script', 23)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 23)->innertext);
            }
            $code = trim(@$article->find('script', 24)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 24)->innertext);
            }
            $code = trim(@$article->find('script', 25)->innertext);
            if (preg_match('/file":/', $code) || preg_match("/file':/", $code)) {
                $code1 = trim($article->find('script', 25)->innertext);
            }
            $prcot = str_replace('<![CDATA[', '', $code1);
            $prcot = str_replace(']]', '', $prcot);
            $code = explode("[", $prcot);
            $code = explode("]", $code[1]);
            $prcot = str_replace(' ', '', $code[0]);
            $prcot = str_replace('"file":"', '<a href="', $prcot);
            $prcot = str_replace("file':'", '<a href="', $prcot);
            $prcot = str_replace('","', '">link</a>', $prcot);
            $prcot = str_replace("','", '">link</a>', $prcot);
            $str = <<<HTML
'.$prcot.'
HTML;
            $part = 0;
            $list_id = array();
            $html = str_get_html($str);

            foreach ($html->find('a') as $e) {
                $part++;
                $code = $e->href;
                $v_type = $this->check_v_type($code);
                switch ($v_type) {
                    case 'yt':
                        preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $code, $matches);
                        if (!empty($matches[1])) {
                            $content = (!empty($matches[1]) ? $matches[1] : '');
                            if (!empty($videotype)) {
                                $v_id = 'http://www.youtube-nocookie.com/embed/' . $content;
                            } else {
                                $v_id = $content;
                            }
                        }
                        break;
                    case 'vimeo':
                        preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $code, $matches);
                        if (!empty($matches[5])) {
                            if (!empty($videotype)) {
                                $v_id = 'http://player.vimeo.com/video/' . $matches[5];
                            } else {
                                $v_id = $matches[5];
                            }
                        }
                        break;
                    case 'docs.google':
                        $g_array = explode('/', $code);
                        if (!empty($g_array[5])) {
                            $v_id = $g_array[5];
                        } else {
                            $v_id = $code;
                        }
                        break;
                    case 'dailymotion':
                        preg_match('#http://www.dailymotion.com/embed/video/([A-Za-z0-9]+)#s', $code, $matches);
                        if (!empty($matches[1])) {
                            $v_id = $matches[1];
                        } else {
                            $v_id = $files;
                        }
                        break;

                    default:
                        $v_id = '';
                        break;
                }

                $list_id[$part] = array(
                    'list' => $v_id,
                    'vtype' => $v_type,
                );
            }
        } else if (preg_match('/spoiler/', $content)) {
            $article = $content;
            foreach ($html->find('#spoiler .drama-info') as $article)
                ;
            $article = str_replace('Phumi Khmer', '', $article);
            $article = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $article);
            $str = <<<HTML
'.$article.'
HTML;
            $html = str_get_html($str);
            $part = 0;
            foreach ($html->find('a') as $e) {
                $part++;
                $code = $e->href;
                preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $code, $matches);
                if (!empty($matches[1])) {
                    $content = $matches[1];
                    if (!empty($videotype)) {
                        $v_id = 'http://www.youtube-nocookie.com/embed/' . $content;
                    } else {
                        $v_id = $content;
                    }
                    $list_id[$part] = array(
                        'list' => $v_id,
                        'vtype' => 'yt',
                    );
                }
            }
        } else if (preg_match('/idGD/', $content)) {
            $content = $article;
            $code = trim(@$article->find('script', 0)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 0)->innertext);
            }
            $code = trim(@$article->find('script', 1)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 1)->innertext);
            }
            $code = trim(@$article->find('script', 2)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 2)->innertext);
            }
            $code = trim(@$article->find('script', 3)->innertext);

            if (preg_match('/idGD/', $code)) {
                $code1 = trim(@$article->find('script', 3)->innertext);
            }
            $code = trim(@$article->find('script', 4)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 4)->innertext);
            }
            $code = trim(@$article->find('script', 5)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 5)->innertext);
            }
            $code = trim(@$article->find('script', 6)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 6)->innertext);
            }
            $code = trim(@$article->find('script', 7)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 7)->innertext);
            }
            $code = trim(@$article->find('script', 8)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 8)->innertext);
            }
            $code = trim(@$article->find('script', 9)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 9)->innertext);
            }
            $code = trim(@$article->find('script', 10)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 10)->innertext);
            }
            $code = trim(@$article->find('script', 11)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 11)->innertext);
            }
            $code = trim(@$article->find('script', 12)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 12)->innertext);
            }
            $code = trim(@$article->find('script', 13)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 13)->innertext);
            }
            $code = trim(@$article->find('script', 14)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 14)->innertext);
            }
            $code = trim(@$article->find('script', 15)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 15)->innertext);
            }
            $code = trim(@$article->find('script', 16)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 16)->innertext);
            }
            $code = trim(@$article->find('script', 17)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 17)->innertext);
            }
            $code = trim(@$article->find('script', 18)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 18)->innertext);
            }
            $code = trim(@$article->find('script', 19)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 19)->innertext);
            }
            $code = trim(@$article->find('script', 20)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 20)->innertext);
            }
            $code = trim(@$article->find('script', 21)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 21)->innertext);
            }
            $code = trim(@$article->find('script', 22)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 22)->innertext);
            }
            $code = trim(@$article->find('script', 23)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 23)->innertext);
            }
            $code = trim(@$article->find('script', 24)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 24)->innertext);
            }
            $code = trim(@$article->find('script', 25)->innertext);
            if (preg_match('/idGD/', $code)) {
                $code1 = trim($article->find('script', 25)->innertext);
            }
            $code1 = explode('"', $code1);
            if (!empty($code1)) {
                $vtype = '';
                $vlists = $code1[3];
                $vtye = $code1[13];
                if ($vtye == "image") {
                    $vtye = $code1[17];
                } else {
                    $vtye = $code1[13];
                }
                switch ($vtye) {
                    case 'GD':
                        $vtype = 'docs.google';
                        break;
                    case 'DG':
                        $vtype = 'docs.google';
                        break;
                    case 'GDoc':
                        $vtype = 'docs.google';
                        break;
                    case 'YT':
                        $vtype = 'yt';
                        break;
                    case 'TY':
                        $vtype = 'yt';
                        break;
                    case 'youtube':
                        $vtype = 'yt';
                        break;
                    case 'onYT':
                        $vtype = 'yt';
                    case 'iframe':
                        $vtype = 'iframe';
                        break;
                    case 'vimeo':
                        $vtype = 'vimeo';
                        break;
                }
                $check_vd = str_replace("0!7x0k!iYAt", 'xxxxxxxx', $vlists);
                $check_vd = str_replace("0!?^0!?A", 'xxxxxxxx', $check_vd);
                $check_vd = str_replace("?^0!AB7Ik!Tx", 'xxxxxxxx', $check_vd);
                $vlis = explode('xxxxxxxx', $check_vd);
                $i = 0;
                if ($vtye == 'youtube' || $vtye == 'GDoc') {
                    foreach ($vlis as $value) {
                        $i++;
                        $list_id[$i] = array(
                            'list' => substr($value, 1, -1),
                            'vtype' => $vtype,
                        );
                    }
                } else {
                    foreach ($vlis as $value) {
                        $i++;

                        $list_id[$i] = array(
                            'list' => $value,
                            'vtype' => $vtype,
                        );
                    }
                }
            }
        } elseif (preg_match('/vimeowrap/', $content)) {
            $code = str_replace(' ', '', $content);
            $code = explode('urls:[', $code);
            if (!empty($code[1])) {
                $code = $code[1];
                $code = str_replace("'", '', $code);
                $code = explode(',],plugins:', $code);
                $code = explode(",", $code[0]);
                $i = 0;
                foreach ($code as $value_arr) {
                    $i++;
                    preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $value_arr, $matches);
                    if (!empty($matches[5])) {
                        if (!empty($videotype)) {
                            $v_id = 'http://player.vimeo.com/video/' . $matches[5];
                        } else {
                            $v_id = $matches[5];
                        }
                        $list_id[$i] = array(
                            'list' => $v_id,
                            'vtype' => 'vimeo',
                        );
                    }
                }
            }
        } elseif (preg_match('/kmobilemovie/', $content)) {
            
        }
        return @$list_id;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */