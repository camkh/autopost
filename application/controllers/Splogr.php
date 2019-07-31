<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Splogr extends CI_Controller
{
    protected $mod_general;
    public function __construct() {
        parent::__construct();
        $this->load->model('Mod_general');
        $this->load->library('dbtable');
        $this->load->theme('layout');
        $this->mod_general = new Mod_general();
        TIME_ZONE;
        $this->load->library('Breadcrumbs');
    }
    public function index() {
        $data['title'] = 'Autopost';
        $this->load->view('layout/splogr/index', $data);
    }

    public function getnext()
    {
        $log_id = $this->session->userdata ('user_id');
        /*check link*/
        $where_link = array(
            'status' => 0,
            'type' => 'link',
            'uid' => $log_id,
        );
        $nextLink = $this->Mod_general->select ('splogr', 'link', $where_link );
        if(!empty($nextLink[0])) {
            $contentJson = $this->getconents($nextLink[0]->link);
             if(!empty($contentJson)) {
                $error = array('error'=> 0); 
                $setContent = array('content'=> $contentJson); 
                $getJsonArray = array_merge($error,$setContent);
            } else {
                $error = array('error'=> 1); 
                $getJsonArray = array_merge($error,$contentJson);
            }
            echo json_encode($getJsonArray);
        } else {
            $where_cur = array(
                'status' => 0,
                'type' => 'next',
                'uid' => $log_id,
            );
            $curLink = $this->Mod_general->select ('splogr', 'link', $where_cur );
            if(!empty($curLink[0])) {
                $code = $this->get_from_site_id($curLink[0]->link);
                redirect(base_url() . 'splogr?m=no_post');
            } else {
                redirect(base_url() . 'splogr?m=no_post');
            }
        }
        /*End check link*/
        die;
    }
    public function getpost($get='')
    {
        $log_id = $this->session->userdata ('user_id');
        /*check link*/
        $where_link = array(
            'status' => 0,
            'type' => 'link',
            'uid' => $log_id,
        );
        $nextLink = $this->Mod_general->select ('splogr', 'link,sp_post', $where_link );
        if(!empty($nextLink[0])) {
            $link = $nextLink[0]->link;
            if(preg_match('/alibaba.com/', $link)){
                if(strpos($link, "http://") === false) {
                    $link = 'http:'.$link;
                }
                $getJsonArray = $this->fromAlibaba($link,$get);
                $wherelink = array(
                    'uid' => $log_id,
                    'link' => $nextLink[0]->link,
                    'type' => 'link',
                );
                $updateLink = array('status' => 1);
                @$this->Mod_general->update ('splogr', $updateLink, $wherelink);
                if($get == 1) {
                    return $getJsonArray;
                } else {
                    echo json_encode($getJsonArray);
                }
                die;
            }
            $sp_post = json_decode($nextLink[0]->sp_post);
            //$contentJson = $this->getconents($nextLink[0]->link);
            $getContent = array('title'=>$sp_post->title,'content'=>$sp_post->summary . '<br/> from: '.$nextLink[0]->link);
            $contentJson[] = $getContent;
             if(!empty($contentJson)) {
                $error = array('error'=> 0); 
                $setContent = array('content'=> $contentJson); 
                $getJsonArray = array_merge($error,$setContent);
            } else {
                $getJsonArray = $this->get_from_site_id('https://www.alibaba.com/premium/laser_machines/1.html',$get);
                if($get == 1) {
                    return $getJsonArray;
                } else {
                    echo json_encode($getJsonArray);
                }
                die;
            }
            /*update link*/
            if(!empty($getContent)) {
                $wherelink = array(
                    'uid' => $log_id,
                    'link' => $nextLink[0]->link,
                    'type' => 'link',
                );
                $updateLink = array('status' => 1);
                @$this->Mod_general->update ('splogr', $updateLink, $wherelink);
            }
            /*End update link*/
            if($get == 1) {
                return $getJsonArray;
            } else {
                echo json_encode($getJsonArray);
            }            
        } else {
            $where_cur = array(
                'status' => 0,
                'type' => 'next',
                'uid' => $log_id,
            );
            $curLink = $this->Mod_general->select ('splogr', 'link', $where_cur,'rand()','',1 );
            if(!empty($curLink[0])) {
                $code = $this->get_from_site_id($curLink[0]->link);
                //redirect(base_url() . 'splogr/getpost');
                $this->getpost($get);
            } else {
                $error = array('error'=> 1); 
                $getJsonArray = array('error'=> 1,'content'=> 'not config');
                if(!empty($get)) {
                    return $getJsonArray;
                } else {
                    echo json_encode($getJsonArray);
                }
            }
        }
        /*End check link*/
        die;
    }

    function get_from_site_id($site_url = '', $get = '') {
        ini_set('max_execution_time', 0);
        $log_id = $this->session->userdata ('user_id');
        $this->load->library('html_dom');        
        $parse = parse_url($site_url);

        /*check link status*/
        //$lurl = $this->get_fcontent($site_url);
        $html = @file_get_html($site_url);
        if(empty($html)) {
            $this->get_from_site_id('https://www.alibaba.com/premium/laser_machines/1.html',$get);
        }
        switch ($parse['host']) {
            case 'ezinearticles.com':
                foreach($html->find('#page-inner .article') as $e) {
                    $link = @$e->find('.article-title-link',0)->href;
                    $title = @$e->find('.article-title-link',0)->innertext;
                    $summary = @$e->find('.article-summary',0)->innertext;
                    $link = 'http://ezinearticles.com'.$link;
                    $where_u = array(
                        'uid' => $log_id,
                        'link' => $link,
                    );
                    $dataLink = $this->Mod_general->select ('splogr', 'link', $where_u );
                    if(empty($dataLink)) {
                        $LinkA = array(
                            'link'=>$link,
                            'title'=>$title,
                            'summary'=>$summary,
                        );
                        $dataLink = array(
                            'sp_post'=> json_encode($LinkA),
                            'uid' => $log_id,
                            'link' => $link,
                            'type' => 'link',
                            'status' => 0,
                        );  
                        $this->mod_general->insert('splogr', $dataLink);
                    } else {
                        continue;
                    }                    
                }
                $end = @$html->find('#page-inner .pagination .next-off',0)->innertext;
                if(!empty($end)) {
                    $end = true;
                } else {
                    $next = @$html->find('#page-inner .pagination a.next',0)->href;
                    $next = 'http://ezinearticles.com'.$next;
                    // echo '<center>Please wait...<br/>'.urlencode($next).'<br/>'.$next.'</center>';
                    // echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'splogr/getpost?p='.urlencode($next).'";}, 15 );</script>'; 
                    // //$this->get_from_site_id($next);


                    $dataLink = array(
                        'uid' => $log_id,
                        'link' => $next,
                        'type' => 'next',
                        'status' => 0,
                    ); 
                    $this->mod_general->insert('splogr', $dataLink);
                    $where_link = array(
                        'uid' => $log_id,
                        'link' => $site_url,
                        'type' => 'next',
                    );
                    $updateLink = array('status' => 1);
                    @$this->Mod_general->update ('splogr', $updateLink, $where_link);
                }

                break; 
            case 'www.alibaba.com':
                $script = @$html->find('script',25)->innertext;
                $perPages = @explode('"total":', $script);

                $perPage = @explode(',', $perPages[1]);
                $totalP = @$perPage[0];

                $cPageArr = @explode('"current":', $perPages[1]);
                $cPage = @explode(',', $cPageArr[1]);
                $currentPage = @$cPage[0];
                
                $nexts = @explode('/', $site_url);
                $last = @count($nexts) - 1;

                foreach($html->find('.l-main-content .m-gallery-product-item-wrap') as $e) {
                    $link = $e->find('.item-info .title a',0)->href;
                    $where_u = array(
                        'uid' => $log_id,
                        'link' => $link,
                    );
                    $dataLink = $this->Mod_general->select ('splogr', 'link', $where_u );
                    if(empty($dataLink)) {
                        $LinkA = array(
                            'link'=>$link
                        );
                        $dataLink = array(
                            'uid' => $log_id,
                            'link' => $link,
                            'type' => 'link',
                            'status' => 0,
                        );  
                        $this->mod_general->insert('splogr', $dataLink);
                        if(strpos($link, "http://") === false) {
                            $link = 'http:'.$link;
                        }
                        return $this->fromAlibaba($link,$get);
                        break;
                    } else {
                        continue;
                    }
                }
                
                $setNextNew = ((int) $currentPage + 1) . '.html';
                $setNext = str_replace($nexts[$last], $setNextNew, $site_url);
                $dataLink = array(
                    'uid' => $log_id,
                    'link' => $setNext,
                    'type' => 'next',
                    'status' => 0,
                ); 
                $this->mod_general->insert('splogr', $dataLink);
                $where_link = array(
                    'uid' => $log_id,
                    'link' => $site_url,
                    'type' => 'next',
                );
                $updateLink = array('status' => 1);
                @$this->Mod_general->update ('splogr', $updateLink, $where_link);
                break;
            default:
                # code...
                break;
        }            
    }

    public function fromAlibaba($site_url='',$get='')
    {
        ini_set('max_execution_time', 0);
        $log_id = $this->session->userdata ('user_id');
        $this->load->library('html_dom');  
        $html = file_get_html($site_url);      
        $title = $html->find ( 'h1.ma-title', 0 )->innertext;
        $og_image = @$html->find ( 'meta [property=og:image]', 0 )->content;
        $pricewrap = $html->find ( '.ma-price-wrap', 0 )->innertext;
        $pricewrap = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $pricewrap);
        $pricewrap = trim(preg_replace('/\s\s+/', ' ', $pricewrap));
        $dooverview = $html->find ( '.do-overview', 0 )->innertext;
        $dooverview = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $dooverview);
        $dooverview = trim(preg_replace('/\s\s+/', ' ', $dooverview));
        $contentJson = [];
        $getContent = array('title'=>$title,'content'=>$pricewrap . '<br/>'.$dooverview.'<br/> from: '.$site_url);
        $contentJson[] = $getContent;
        $error = array('error'=> 0); 
        $setContent = array('content'=> $contentJson); 
        $getJsonArray = array_merge($error,$setContent);
        error_reporting(0);
        return $getJsonArray;
    }

    public function getconents($site_url='')
    {
        ini_set('max_execution_time', 0);
        $log_id = $this->session->userdata ('user_id');
        $this->load->library('html_dom'); 
        $lurl = $this->get_fcontent($site_url);
        $getContent = [];                
        if($lurl[1] != 0) {    
            /*End check link status*/
            if(!empty($lurl[0])) {
$str = <<< HTML
'.$lurl[0].'
HTML;
                $html = str_get_html($str);
                $title = @$html->find('title',0)->innertext;
                $content = @$html->find('#article-content',0)->innertext;
                $resource = @$html->find('#article-resource',0)->innertext;
                $getContent = array('title'=>$title,'content'=>$content . '<br/>'.$resource);
            }
        } 
        // $html = file_get_html($site_url);
        // $title = @$html->find('title',0)->innertext;
        // $content = @$html->find('#article-content',0)->innertext;
        // $resource = @$html->find('#article-resource',0)->innertext;
        // $getContent = array('title'=>$title,'content'=>$content . '<br/>'.$resource);

        /*update link*/
        if(!empty($getContent)) {
            $where_link = array(
                'uid' => $log_id,
                'link' => $site_url,
                'type' => 'link',
            );
            $updateLink = array('status' => 1);
            @$this->Mod_general->update ('splogr', $updateLink, $where_link);
        }
        /*End update link*/
        $contentJson[] = $getContent;
        return $contentJson;
    }

    public function get_fcontent( $url,  $javascript_loop = 0, $timeout = 5 )
    {
        $url = str_replace( "&amp;", "&", urldecode(trim($url)) );
        $cookie = @tempnam ("/tmp", "CURLCOOKIE");
        $userAgents = $this->getRandomUserAgent();

        $proxies = array(); // Declaring an array to store the proxy list
 
        // Adding list of proxies to the $proxies array
        $proxies[] = '182.53.206.144:42352';  // Some proxies require user, password, IP and port number
        $proxies[] = '182.53.96.159:36893';
        $proxies[] = '81.173.195.38:48522';
        $proxies[] = '46.5.164.115:1080';  // Some proxies only require IP
        $proxies[] = '36.80.193.187:8080';
        $proxies[] = '149.28.189.104:1080'; // Some proxies require IP and port number
        $proxies[] = '123.215.15.132:1080';
        if (isset($proxies)) {  // If the $proxies array contains items, then
            $proxy = $proxies[array_rand($proxies)];    // Select a random proxy from the array and assign to $proxy variable
        }
        $ipAddress = "173.249.35.163";
        $ch = curl_init();
        if (isset($proxy)) {    // If the $proxy variable is set, then
            curl_setopt($ch, CURLOPT_PROXY, $proxy);    // Set CURLOPT_PROXY with proxy in $proxy variable
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["REMOTE_ADDR: $ipAddress", "HTTP_X_FORWARDED_FOR: $ipAddress"]);
        curl_setopt( $ch, CURLOPT_USERAGENT, $userAgents );
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        $content = curl_exec( $ch );
        $response = curl_getinfo( $ch );
        curl_close ( $ch );

        if ($response['http_code'] == 301 || $response['http_code'] == 302) {
            ini_set("user_agent", $userAgents);

            if ( $headers = get_headers($response['url']) ) {
                foreach( $headers as $value ) {
                    if ( substr( strtolower($value), 0, 9 ) == "location:" )
                        return get_url( trim( substr( $value, 9, strlen($value) ) ) );
                }
            }
        }

        if (    ( preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value) ) && $javascript_loop < 5) {
            return get_url( $value[1], $javascript_loop+1 );
        } else {
            return array( $content, $response );
        }
    }

    public function getRandomUserAgent()
    {
         $userAgents=array(
         "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6",
         "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)",
         "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)",
         "Opera/9.20 (Windows NT 6.0; U; en)",
         "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 8.50",
         "Mozilla/4.0 (compatible; MSIE 6.0; MSIE 5.5; Windows NT 5.1) Opera 7.02 [en]",
         "Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; fr; rv:1.7) Gecko/20040624 Firefox/0.9",
         "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.63 Safari/537.31",
         "Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/48 (like Gecko) Safari/48" 
         );
         $random = rand(0,count($userAgents)-1);         
         return $userAgents[$random];
    }
}

/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */
