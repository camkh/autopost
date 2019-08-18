<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Getcontent extends CI_Controller
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
    function getConentFromSite($url,$oldurl='')
    {
        $log_id = $this->session->userdata ( 'user_id' );
        $this->load->library ( 'html_dom' );
        $html = file_get_html ( $url );
        $obj = new stdClass();
        $obj->description = @$html->find ( 'meta[property=og:description]', 0 )->content;
        $obj->title = $html->find ( 'title', 0 )->innertext;
        $og_image = @$html->find ( 'meta [property=og:image]', 0 )->content;
        $image_src = @$html->find ( 'link [rel=image_src]', 0 )->href;
        if (! empty ( $image_src )) {
            $thumb = $image_src;
        } elseif (! empty ( $html->find ( 'meta [property=og:image]', 0 )->content )) {
            $thumb = $html->find ( 'meta [property=og:image]', 0 )->content;
        } else {
            $thumb = '';
        }
        $obj->thumb = $thumb;
        $parse = parse_url($url);
        //echo $parse['host'];

        switch ($parse['host']) {
            case 'www.siamnews.com':
                foreach($html->find('.line_view') as $item) {
                    $item->outertext = '';
                }
                /*get label*/
                $label = [];
                $last = count($html->find('.breadcrumb li')) - 1;
                foreach($html->find('.breadcrumb li') as $index => $labels) {
                    if($index != $last && $index !=0) {
                        $label[] = $labels->plaintext;
                    } 
                }
                 $obj->label = implode(',', $label);
                /*End get label*/
               
                //$html->save();
                $content = @$html->find ( '#article-post .data_detail', 0 )->innertext;
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $file_title = basename($image);
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($image, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.siamvariety.com':
                /*get label*/
                $label = [];
                $last = count($html->find('.breadcrumb li')) - 1;
                foreach($html->find('.breadcrumb li') as $index => $labels) {
                    if($index != $last && $index !=0) {
                        $label[] = $labels->plaintext;
                    } 
                }
                 $obj->label = implode(',', $label);
                /*End get label*/

                $content = @$html->find ( '#article-post .data_detail', 0 )->innertext;

                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $file_title = basename($image);
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($image, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.viralsfeedpro.com':
                foreach($html->find('.line_view') as $item) {
                    $item->outertext = '';
                }
                /*get label*/
                $label = [];
                $last = count($html->find('.breadcrumb li')) - 1;
                foreach($html->find('.breadcrumb li') as $index => $labels) {
                    if($index != $last && $index !=0) {
                        $label[] = $labels->plaintext;
                    } 
                }
                 $obj->label = implode(',', $label);
                /*End get label*/
               
                //$html->save();
                $content = @$html->find ( '#article-post .data_detail', 0 )->innertext;
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $file_title = basename($image);
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($image, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.siamweek.com':
                /*get label*/
                $label = [];
                $last = count($html->find('.breadcrumb li')) - 1;
                foreach($html->find('.breadcrumb li') as $index => $labels) {
                    if($index != $last && $index !=0) {
                        $label[] = $labels->plaintext;
                    } 
                }
                 $obj->label = implode(',', $label);
                /*End get label*/

                $content = @$html->find ( '#article-post .data_detail', 0 )->innertext;

                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $file_title = basename($image);
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($image, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.siamtopic.com':
                /*get label*/
                $label = [];
                $last = count($html->find('.breadcrumb li')) - 1;
                foreach($html->find('.breadcrumb li') as $index => $labels) {
                    if($index != $last && $index !=0) {
                        $label[] = $labels->plaintext;
                    } 
                }
                $obj->label = implode(',', $label);
                /*End get label*/
                $content = @$html->find ( '#article-post .data_detail', 0 )->innertext;

                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);

                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $file_title = basename($image);
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($image, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case '108resources.com':
                /*get label*/
                $label = [];
                $label = $html->find('span[typeof=v:Breadcrumb]',1)->plaintext;
                if($label == 'ดวงและความเชื่อ') {
                    $obj->label = 'ศรัทธา - ความเชื่อ';
                } else if($label == 'สูตรอาหาร') {
                    $obj->label = 'อาหารการกิน';
                } else if($label == 'อาหารและสุขภาพ') {
                    $obj->label = 'อาหารการกิน,สุขภาพ';
                } else {
                    $obj->label = $label;
                }
                /*End get label*/
                $content = @$html->find ( '.bdaia-post-content', 0 )->innertext;
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);

                $regex = '/src="([^"]*)"/';
                // we want all matches
                preg_match_all( $regex, $content, $matches );
                // reversing the matches array
                $matches = array_reverse($matches);
                if(!empty($matches[0])) {
                    foreach ($matches[0] as $image) {
                        $file_title = basename($image);
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($image, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'lahbey.com':
                /*get label*/
                $label = [];
                $last = count($html->find('.td-crumb-container span')) - 1;
                foreach($html->find('.td-crumb-container span') as $index => $labels) {
                    if($index != $last && $index !=0) {
                        $label[] = $labels->plaintext;
                    } 
                }
                $obj->label = implode(',', $label);
                /*End get label*/
                $content = @$html->find ( '.td-ss-main-content .td-post-content', 0 )->innertext;
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                // $regex = '~<img.*?src=["\']+(.*?)["\']+~';
                // preg_match_all( $regex, $content, $matches );
                // var_dump($matches[1]);
                // die;
                // $ImgSrc = array_pop($matches);

                // // reversing the matches array
                // if(!empty($ImgSrc)) {
                //     foreach ($ImgSrc as $image) {
                //         $imagedd = strtok($image, "?");
                //         $file_title = basename($imagedd);
                //         $fileName = FCPATH . 'uploads/image/'.$file_title;
                //         @copy($image, $fileName);   
                //         $images = $this->mod_general->uploadtoImgur($fileName);
                //         if(empty($images)) {
                //             $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                //             $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                //             if($images) {
                //                 @unlink($fileName);
                //             }
                //         } else {
                //             $gimage = @$images; 
                //             @unlink($fileName);
                //         }
                //         if(!empty($gimage)) {
                //             $content = str_replace($image,$gimage,$content);
                //         }
                //     }
                // }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'deejaiplus.com':
                /*get label*/
                $label = $html->find('.cat-links a',0)->plaintext;
                $obj->label = $label;
                /*End get label*/
                foreach($html->find('.sharedaddy') as $item) {
                    $item->outertext = '';
                }
                foreach($html->find('.robots-nocontent') as $item) {
                    $item->outertext = '';
                }
                $html->save();
                $content = @$html->find ( '#main .entry-content', 0 )->innertext;
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-sizes=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-src=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-recalc-dims=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-large-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-medium-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-meta=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-description class=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-orig-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-permalink=".*?"/i', "$1", $content );

                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                // echo $content;
                // die;
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'deemindplus.com':
                /*get label*/
                $label = $html->find('.cat-links a',0)->plaintext;
                $obj->label = $label;
                /*End get label*/

                foreach($html->find('.sharedaddy') as $item) {
                    $item->outertext = '';
                }
                foreach($html->find('.robots-nocontent') as $item) {
                    $item->outertext = '';
                }
                $html->save();
                $content = @$html->find ( '#main .entry-content', 0 )->innertext;
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-sizes=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-src=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-recalc-dims=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-large-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-medium-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-meta=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-description class=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-orig-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-permalink=".*?"/i', "$1", $content );
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'sabidee.com':
                /*get label*/
                $label = $html->find('.cat-links a',0)->plaintext;
                $obj->label = $label;
                /*End get label*/

                // foreach($html->find('.sharedaddy') as $item) {
                //     $item->outertext = '';
                // }
                // foreach($html->find('.featured-image') as $item) {
                //     $item->outertext = '';
                // }
                // $html->save();
                $content = @$html->find ( '#content .entry-content', 0 )->innertext;
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-sizes=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-src=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-recalc-dims=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-large-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-medium-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-meta=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-description class=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-orig-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-permalink=".*?"/i', "$1", $content );
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'deesoulmuch.com':
                /*get label*/
                $label = $html->find('.cat-links a',0)->plaintext;
                $obj->label = $label;
                /*End get label*/

                // foreach($html->find('.sharedaddy') as $item) {
                //     $item->outertext = '';
                // }
                // foreach($html->find('.featured-image') as $item) {
                //     $item->outertext = '';
                // }
                // $html->save();
                $content = @$html->find ( '#main .entry-content', 0 )->innertext;
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-sizes=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-src=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-recalc-dims=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-large-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-medium-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-meta=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-description class=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-orig-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-permalink=".*?"/i', "$1", $content );
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'deejunglife.com':
                /*get label*/
                $label = $html->find('.cat-links a',0)->plaintext;
                $obj->label = $label;
                /*End get label*/
                foreach($html->find('.sharedaddy') as $item) {
                    $item->outertext = '';
                }
                foreach($html->find('.shared-counts-wrap') as $item) {
                    $item->outertext = '';
                }
                foreach($html->find('.robots-nocontent') as $item) {
                    $item->outertext = '';
                }
                $html->save();
                $content = @$html->find ( '#main .entry-content', 0 )->innertext;
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-sizes=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-src=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-recalc-dims=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-large-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-medium-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-meta=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-description class=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-orig-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-permalink=".*?"/i', "$1", $content );

                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.tnews.co.th':
                /*get label*/
                $label = [];
                $last = count($html->find('.breadcrumb li')) - 1;
                foreach($html->find('.breadcrumb li') as $index => $labels) {
                    if($index != $last && $index !=0) {
                        $label[] = $labels->plaintext;
                    } 
                }
                $obj->label = implode(',', $label);
                /*End get label*/

                $content = @$html->find ( '.content-main .h4-content-lh', 0 )->innertext;
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $htmlContent = str_get_html($content);
                foreach($htmlContent->find('.row') as $item) {
                    $item->outertext = '';
                }
                foreach($htmlContent->find('.text-left') as $item) {
                    $item->outertext = '';
                }
                foreach($htmlContent->find('blockquote') as $item) {
                    $item->outertext = '';
                }
                foreach($htmlContent->find('#hastag-txt') as $item) {
                    $item->outertext = '';
                }
                foreach($htmlContent->find('.news-word') as $item) {
                    $item->outertext = '';
                }
                foreach($htmlContent->find('.hastag-topic') as $item) {
                    $item->outertext = '';
                }
                $htmlContent->save();
                $htmlContent = str_replace('data-cfsrc','src',$htmlContent);
                $htmlContent = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $htmlContent);

                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $htmlContent = str_replace($image,$gimage,$htmlContent);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $htmlContent;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.tpolitic.com':
                /*get label*/
                $label = [];
                $last = count($html->find('.breadcrumb li')) - 1;
                foreach($html->find('.breadcrumb li') as $index => $labels) {
                    if($index != $last && $index !=0) {
                        $label[] = $labels->plaintext;
                    } 
                }
                $obj->label = implode(',', $label);
                /*End get label*/
                foreach($html->find('.text-left') as $item) {
                    $item->outertext = '';
                }
                foreach($html->find('.text-center') as $item) {
                    $item->outertext = '';
                }
                foreach($html->find('.tag') as $item) {
                    $item->outertext = '';
                }
                foreach($html->find('.ads-1') as $item) {
                    $item->outertext = '';
                }
                $html->save();
                $content = @$html->find ( '.container .col-md-8', 0 )->innertext;
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-sizes=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-src=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-recalc-dims=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-large-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-medium-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-meta=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-description class=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-orig-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-permalink=".*?"/i', "$1", $content );
                $pattern = "/<p[^>]*>&nbsp;<\\/p[^>]*>/"; 
                $content = preg_replace($pattern, '', $content);
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.77jowo.com':
                /*get label*/
                $label = [];
                $last = count($html->find('.breadcrumb li')) - 1;
                foreach($html->find('.breadcrumb li') as $index => $labels) {
                    if($index != $last && $index !=0) {
                        $label[] = $labels->plaintext;
                    } 
                }
                $obj->label = implode(',', $label);
                /*End get label*/

                $contents = @$html->find ( '#content-detail', 0 );
                $content = '';
                foreach($contents->find("div[class^='detail-']") as $item) {
                    $content .= $item;
                }
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);

                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.one31.net':
                /*get label*/
                $obj->label = 'ข่าว';
                $contents = @$html->find ( '.box-newsdetail .newsdetail-txt', 0 );
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $contents);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);

                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'xn--y3ccoob3d.com':
                /*get label*/
                //$label = $html->find('.cat-links a',0)->plaintext;
                $obj->label = 'Lott';
                $obj->title = str_replace('หมีหวย.com', '', $obj->title);
                /*End get label*/

                // foreach($html->find('.sharedaddy') as $item) {
                //     $item->outertext = '';
                // }
                // foreach($html->find('.robots-nocontent') as $item) {
                //     $item->outertext = '';
                // }
                // $html->save();
                $content = @$html->find ( '.hentry .entry-content', 0 )->innertext;
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-sizes=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-src=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-recalc-dims=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-large-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-medium-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-meta=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-description class=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-orig-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-permalink=".*?"/i', "$1", $content );
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.007lotto.net':
                /*get label*/
                //$label = $html->find('.cat-links a',0)->plaintext;
                $obj->label = 'Lott';
                //$obj->title = str_replace('หมีหวย.com', '', $obj->title);
                /*End get label*/

                foreach($html->find('#share-this') as $item) {
                    $item->outertext = '';
                }
                foreach($html->find('.addthis_sharing_toolbox') as $item) {
                    $item->outertext = '';
                }
                $html->save();
                $content = @$html->find ( '#main .entry-content', 0 )->innertext;
                $content = preg_replace('/<center\b[^>]*>(.*?)<\/center>/is', "", $content);
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-sizes=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-src=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-recalc-dims=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-large-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-medium-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-meta=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-description class=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-orig-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-permalink=".*?"/i', "$1", $content );
                $content = str_replace('--Advertisement--', '', $content);
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.mumkhao.com':
                /*get label*/
                $label = [];
                $last = count($html->find('.breadcrumb li')) - 1;
                foreach($html->find('.breadcrumb li') as $index => $labels) {
                    if($index != $last && $index !=0) {
                        $label[] = $labels->plaintext;
                    } 
                }
                $obj->label = implode(',', $label);
                /*End get label*/

                $contents = @$html->find ( '#main .data_detail', 0 );
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $contents);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);

                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'tnews.teenee.com':
                /*get label*/
                $label = [];
                $last = count($html->find('.post-header .post-author a')) - 1;
                foreach($html->find('.post-header .post-author a') as $index => $labels) {
                    if($index != $last && $index !=0) {
                        $setNewLabel = trim($labels->plaintext);
                        if($setNewLabel == 'ข่าวเด่นประเด็นร้อน') {
                            $label[] = 'ข่าว';
                        } else {
                            $label[] = $setNewLabel;
                        }
                    } 
                }
                $obj->label = implode(',', $label);
                /*End get label*/

                $content = '';
                foreach($html->find ('#main div[itemprop=articleBody]') as $item) {
                    $content .= $item->innertext;
                }
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);

                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);

                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");

                        if(!preg_match('/^(http)/', $imagedd)){
                            $imagedd = 'http://tnews.teenee.com/crime/'.$imagedd;
                        }
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'variety.teenee.com':
                /*get label*/
                $label = [];
                $last = count($html->find('.post-header .post-author a')) - 1;
                foreach($html->find('.post-header .post-author a') as $index => $labels) {
                    if($index != $last && $index !=0) {
                        $setNewLabel = trim($labels->plaintext);
                        if($setNewLabel == 'ข่าวเด่นประเด็นร้อน') {
                            $label[] = 'ข่าว';
                        } else {
                            $label[] = $setNewLabel;
                        }
                    } 
                }
                $obj->label = implode(',', $label);
                /*End get label*/

                $content = '';
                foreach($html->find ('#main div[itemprop=articleBody]') as $item) {
                    $content .= $item->innertext;
                }
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);

                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        if(!preg_match('/^(http)/', $imagedd)){
                            $imagedd = 'http://variety.teenee.com/foodforbrain/'.$imagedd;
                        }
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'entertain.teenee.com':
                /*get label*/
                $label = [];
                $last = count($html->find('.post-header .post-author a')) - 1;
                foreach($html->find('.post-header .post-author a') as $index => $labels) {
                    if($index != $last && $index !=0) {
                        $setNewLabel = trim($labels->plaintext);
                        if($setNewLabel == 'ข่าวเด่นประเด็นร้อน') {
                            $label[] = 'ข่าว';
                        } else {
                            $label[] = $setNewLabel;
                        }
                    } 
                }
                $obj->label = implode(',', $label);
                /*End get label*/

                $content = '';
                foreach($html->find ('#main div[itemprop=articleBody]') as $item) {
                    $content .= $item->innertext;
                }
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        if(!preg_match('/^(http)/', $imagedd)){
                            $imagedd = 'http://entertain.teenee.com/thaistar/'.$imagedd;
                        }
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'socialnews.teenee.com':
                /*get label*/
                $label = [];
                $last = count($html->find('.post-header .post-author a')) - 1;
                foreach($html->find('.post-header .post-author a') as $index => $labels) {
                    if($index != $last && $index !=0) {
                        $setNewLabel = trim($labels->plaintext);
                        if($setNewLabel == 'ข่าวเด่นประเด็นร้อน') {
                            $label[] = 'ข่าว';
                        } else {
                            $label[] = $setNewLabel;
                        }
                    } 
                }
                $obj->label = implode(',', $label);
                /*End get label*/

                $content = '';
                foreach($html->find ('#main div[itemprop=articleBody]') as $item) {
                    $content .= $item->innertext;
                }
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);

                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        if(!preg_match('/^(http)/', $imagedd)){
                            $imagedd = 'http://socialnews.teenee.com/penkhao/'.$imagedd;
                        }
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'horo.teenee.com':
                /*get label*/
                $label = [];
                $last = count($html->find('.post-header .post-author a')) - 1;
                foreach($html->find('.post-header .post-author a') as $index => $labels) {
                    if($index != $last && $index !=0) {
                        $setNewLabel = trim($labels->plaintext);
                        if($setNewLabel == 'ดูดวง') {
                            $label[] = 'ดูดวง';
                            $label[] = 'ไลฟ์สไตล์';
                        } else {
                            $label[] = $setNewLabel;
                        }
                    } 
                }
                $obj->label = implode(',', $label);
                /*End get label*/


                $content = '';
                foreach($html->find ('#main div[itemprop=articleBody]') as $item) {
                    $content .= $item->innertext;
                }
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);


                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        if(!preg_match('/^(http)/', $imagedd)){
                            $imagedd = 'http://horo.teenee.com/seer/'.$imagedd;
                        }
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'board.postjung.com':
                /*get label*/
                $obj->label = 'ข่าว';
                $label = $html->find('.mainbox .spboard a',0)->plaintext;
                if($label == 'ข่าววันนี้') {
                    $obj->label = 'ข่าว';
                }
                
                /*End get label*/

                $content = '';
                foreach($html->find ('#maincontent') as $item) {
                    $content .= $item->innertext;
                }
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);

                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);

                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");

                        if(!preg_match('/^(http)/', $imagedd)){
                            $imagedd = 'http://tnews.teenee.com/crime/'.$imagedd;
                        }
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'khreality.com':
                /*get label*/
                $label = $html->find('#main-navigation .current-post-parent a',0)->plaintext;
                if($label == 'ข่าวทั่วไป') {
                    $obj->label = 'ข่าว';
                } else if($label == 'ข่าวต่างประเทศ') {
                    $obj->label = 'ข่าว,ข่าวต่างประเทศ';
                } else if($label == 'ข่าวบันเทิง') {
                    $obj->label = 'ข่าว,ข่าวบันเทิง';
                } else if($label == 'สุขภาพ') {
                    $obj->label = 'ไลฟ์สไตล์,สุขภาพ';
                } else {
                    $obj->label = $label;
                }
                
                /*End get label*/
                $contents = @$html->find ( '.single-container .entry-content', 0 );
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $contents);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.sanook.com':
                /*get label*/
                $label = $html->find('nav.nav li.active a span',0)->plaintext;
                if($label == 'กีฬา') {
                    $obj->label = 'ข่าว,ข่าวกีฬา';
                } else if($label == 'ข่าวบันเทิง') {
                    $obj->label = 'ข่าว,ข่าวบันเทิง';
                } else if($label == 'ไอที') {
                    $obj->label = 'ข่าว,ข่าวไอที';
                } else if($label == 'รถยนต์') {
                    $obj->label = 'ข่าว,ข่าวยานยนต์';
                } else if($label == 'ดูดวง') {
                    $obj->label = 'ไลฟ์สไตล์,ดูดวง';
                } else if($label == 'สุขภาพ') {
                    $obj->label = 'ไลฟ์สไตล์,สุขภาพ';
                } else {
                    $obj->label = $label;
                }
                
                /*End get label*/
                foreach($html->find('.EntryShare') as $item) {
                    $item->outertext = '';
                }
                foreach($html->find('.infoRight') as $item) {
                    $item->outertext = '';
                }
                foreach($html->find('.infoLeft') as $item) {
                    $item->outertext = '';
                }
                foreach($html->find('.EntryHeading') as $item) {
                    $item->outertext = '';
                }
                foreach($html->find('.partner') as $item) {
                    $item->outertext = '';
                }
                foreach($html->find('.pagination') as $item) {
                    $item->outertext = '';
                }
                foreach($html->find('.tags .SectionItem') as $item) {
                    $item->outertext = '';
                }
                $contents = @$html->find ( '.container .EntryBody', 0 );
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $contents);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                
                
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.khaosod.co.th':
                /*get label*/
                $label = $html->find('.entry-crumbs span',1)->plaintext;
                if($label == 'กีฬา') {
                    $obj->label = 'ข่าว,ข่าวกีฬา';
                } else if($label == 'บันเทิง') {
                    $obj->label = 'ข่าว,ข่าวบันเทิง';
                } else if($label == 'ไอที') {
                    $obj->label = 'ข่าว,ข่าวไอที';
                } else if($label == 'ยานยนต์') {
                    $obj->label = 'ข่าว,ข่าวยานยนต์';
                } else if($label == 'ดวง') {
                    $obj->label = 'ไลฟ์สไตล์,ดูดวง';
                } else if($label == 'สุขภาพ') {
                    $obj->label = 'ไลฟ์สไตล์,สุขภาพ';
                } else if($label == 'การเมือง') {
                    $obj->label = 'ข่าว,ข่าวการเมือง';
                } else if($label == 'เศรษฐกิจ') {
                    $obj->label = 'ข่าว,เศรษฐกิจ';
                } else if($label == 'ทุกทิศทั่วไทย') {
                    $obj->label = 'ข่าว,ทุกทิศทั่วไทย';
                } else if($label == 'ตรวจหวยกับข่าวสด') {
                    $obj->label = 'ไลฟ์สไตล์,เสี่ยงดวง - หวย';
                } else {
                    $obj->label = $label;
                }
                
                /*End get label*/

                $contents = @$html->find ( '.ud_post_wrap .td-post-content', 0 );
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $contents);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'ball76.com':
                $obj->label = '';
                $iframeCheck = @$html->find ( '.su-youtube iframe', 0 );
                $obj->title = $html->find ( 'title', 0 )->innertext;
                if(!empty($iframeCheck)) {
                    $iframe = @$iframeCheck->src;
                    // $html1 = file_get_html ( $iframeCheck->src );
                    // $obj->title = $html1->find ( 'title', 0 )->innertext;
                    $vid = $this->Mod_general->get_video_id($iframe);
                    $obj->vid = $vid['vid'];
                    $obj->thumb = 'https://i.ytimg.com/vi/'.$obj->vid.'/hqdefault.jpg';
                }
                $obj->conent = '';
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'casa982.com':
                $obj->label = '';
                $obj->title = @$html->find ( 'meta[name=description]', 0 )->content; 
                $contents = @$html->find ( '#main .entry-content', 0 );
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $contents);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.kiddee.welovemyking.com':
                foreach($html->find('.seed-social') as $item) {
                    $item->outertext = '';
                }
                $html->save();
                $label = $html->find('.tt_cat_single a',0)->plaintext;
                $obj->label = $label;
                $obj->title = @$html->find ( 'title', 0 )->plaintext; 
                $contents = @$html->find ( '.thaitheme_read', 0 );
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $contents);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'siamama.com':
                // foreach($html->find('.seed-social') as $item) {
                //     $item->outertext = '';
                // }
                // $html->save();
                $obj->thumb = @$html->find ( '.np-article-thumb img', 0 )->src; 
                /*get label*/
                $label = [];
                foreach($html->find('.post-cats-list a') as $index => $labels) {
                    $setNewLabel = trim($labels->plaintext);
                    if($setNewLabel == 'ดูดวงรายวัน') {
                        $label[] = 'ดูดวง';
                        $label[] = 'ไลฟ์สไตล์';
                    } else if($setNewLabel == 'สาระน่ารู้') {
                        $label[] = 'เรื่องน่ารู้';
                    } else {
                        $label[] = $setNewLabel;
                    } 
                }
                $obj->label = implode(',', $label);
                /*End get label*/

                $obj->title = @$html->find ( 'title', 0 )->plaintext; 
                $contents = @$html->find ( '#main .entry-content', 0 );
                $content = preg_replace('/<center\b[^>]*>(.*?)<\/center>/is', "", $contents);
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-sizes=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-src=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-recalc-dims=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-large-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-medium-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-meta=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-description class=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-orig-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-permalink=".*?"/i', "$1", $content );
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.seesuttho.com':
                foreach($html->find('.code-block') as $item) {
                    $item->outertext = '';
                }
                foreach($html->find('.wp-post-author-wrap') as $item) {
                    $item->outertext = '';
                }
                $html->save();
                $obj->thumb = @$html->find ( '.image-link img.attachment-hitmag-featured', 0 )->src; 
                /*get label*/
                $label = [];
                foreach($html->find('.post-cats-list a') as $index => $labels) {
                    $setNewLabel = trim($labels->plaintext);
                    if($setNewLabel == 'ดูดวงรายวัน') {
                        $label[] = 'ดูดวง';
                        $label[] = 'ไลฟ์สไตล์';
                    } else if($setNewLabel == 'สาระน่ารู้') {
                        $label[] = 'เรื่องน่ารู้';
                    } else {
                        $label[] = $setNewLabel;
                    } 
                }
                $obj->label = implode(',', $label);
                /*End get label*/

                $obj->title = @$html->find ( 'title', 0 )->plaintext; 
                $contents = @$html->find ( '#main .entry-content', 0 );
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $contents);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);

                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->conent = $content;
                $obj->fromsite = '';
                $obj->site = 'site';
                return $obj;
                break;
            case 'guruthainews.com':
                /*get label*/
                $label = $html->find('.post .entry-crumbs a',1)->plaintext;
                if($label == 'หวย') {
                    $obj->label = 'ไลฟ์สไตล์,เสี่ยงดวง - หวย';
                } else if($label == 'บันเทิง') {
                    $obj->label = 'ข่าว,ข่าวบันเทิง';
                } else {
                    $obj->label = $label;
                }
                //$obj->title = str_replace('หมีหวย.com', '', $obj->title);
                /*End get label*/

                // foreach($html->find('#share-this') as $item) {
                //     $item->outertext = '';
                // }
                foreach($html->find('.td-post-featured-image') as $item) {
                    $item->outertext = '';
                }
                $html->save();
                $content = @$html->find ( '.post .td-post-content', 0 )->innertext;
                $content = preg_replace('/<center\b[^>]*>(.*?)<\/center>/is', "", $content);
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-sizes=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-src=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-recalc-dims=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-large-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-medium-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-meta=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-description class=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-orig-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-permalink=".*?"/i', "$1", $content );
                $content = str_replace('--Advertisement--', '', $content);
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.amarintv.com':
                /*get label*/
                $label = trim($html->find('.entry-header .nav-wrapper a',1)->plaintext);
                if($label == 'หวย') {
                    $obj->label = 'ไลฟ์สไตล์,เสี่ยงดวง - หวย';
                } else if($label == 'ENTERTAINMENT UPDATE') {
                    $obj->label = 'ข่าว,ข่าวบันเทิง';
                } else if($label == 'NEWS UPDATE') {
                    $obj->label = 'ข่าว,ข่าวด่วน';
                } else if($label == 'NEWS UPDATE') {
                    $obj->label = 'ข่าว,ข่าวด่วน';
                } else if($label == 'LIFESTYLE UPDATE') {
                    $obj->label = 'ไลฟ์สไตล์';
                } else {
                    $obj->label = $label;
                }
                //$obj->title = str_replace('หมีหวย.com', '', $obj->title);
                /*End get label*/

                // foreach($html->find('#share-this') as $item) {
                //     $item->outertext = '';
                // }
                // foreach($html->find('.td-post-featured-image') as $item) {
                //     $item->outertext = '';
                // }
                // $html->save();
                $content = @$html->find ( '#main #content-inner', 0 )->innertext;
                $content = preg_replace('/<center\b[^>]*>(.*?)<\/center>/is', "", $content);
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-sizes=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-src=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-recalc-dims=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-large-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-medium-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-meta=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-description class=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-orig-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-permalink=".*?"/i', "$1", $content );
                $content = str_replace('--Advertisement--', '', $content);
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.facedara.com':
                /*get label*/
                // $label = trim($html->find('.entry-header .nav-wrapper a',1)->plaintext);
                // if($label == 'หวย') {
                //     $obj->label = 'ไลฟ์สไตล์,เสี่ยงดวง - หวย';
                // } else if($label == 'ENTERTAINMENT UPDATE') {
                //     $obj->label = 'ข่าว,ข่าวบันเทิง';
                // } else if($label == 'NEWS UPDATE') {
                //     $obj->label = 'ข่าว,ข่าวด่วน';
                // } else if($label == 'NEWS UPDATE') {
                //     $obj->label = 'ข่าว,ข่าวด่วน';
                // } else if($label == 'LIFESTYLE UPDATE') {
                //     $obj->label = 'ไลฟ์สไตล์';
                // } else {
                //     $obj->label = $label;
                // }
                $obj->label = 'ข่าว';
                //$obj->title = str_replace('หมีหวย.com', '', $obj->title);
                /*End get label*/

                // foreach($html->find('#share-this') as $item) {
                //     $item->outertext = '';
                // }
                // foreach($html->find('.td-post-featured-image') as $item) {
                //     $item->outertext = '';
                // }
                // $html->save();
                $obj->thumb = @$html->find ( '.post_thumb img', 0 )->src;
                $content = @$html->find ( '.post-detail .the_content', 0 )->innertext;
                $content = preg_replace('/<center\b[^>]*>(.*?)<\/center>/is', "", $content);
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-sizes=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-src=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-recalc-dims=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-large-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-medium-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-meta=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-description class=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-orig-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-permalink=".*?"/i', "$1", $content );
                $content = str_replace('--Advertisement--', '', $content);
                // $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                // preg_match_all( $regex, $content, $matches );
                // $ImgSrc = array_pop($matches);
                // reversing the matches array
                // if(!empty($ImgSrc)) {
                //     foreach ($ImgSrc as $image) {
                //         $imagedd = strtok($image, "?");
                //         $file_title = basename($imagedd);
                //         $fileName = FCPATH . 'uploads/image/'.$file_title;
                //         @copy($imagedd, $fileName);   
                //         $images = $this->mod_general->uploadtoImgur($fileName);
                //         if(empty($images)) {
                //             $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                //             $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                //             if($images) {
                //                 @unlink($fileName);
                //             }
                //         } else {
                //             $gimage = @$images; 
                //             @unlink($fileName);
                //         }
                //         if(!empty($gimage)) {
                //             $content = str_replace($image,$gimage,$content);
                //         }
                //     }
                // }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.amarintv.com':
                /*get label*/
                $label = trim($html->find('.entry-header .nav-wrapper a',1)->plaintext);
                if($label == 'หวย') {
                    $obj->label = 'ไลฟ์สไตล์,เสี่ยงดวง - หวย';
                } else if($label == 'ENTERTAINMENT UPDATE') {
                    $obj->label = 'ข่าว,ข่าวบันเทิง';
                } else if($label == 'NEWS UPDATE') {
                    $obj->label = 'ข่าว,ข่าวด่วน';
                } else if($label == 'NEWS UPDATE') {
                    $obj->label = 'ข่าว,ข่าวด่วน';
                } else if($label == 'LIFESTYLE UPDATE') {
                    $obj->label = 'ไลฟ์สไตล์';
                } else {
                    $obj->label = $label;
                }
                //$obj->title = str_replace('หมีหวย.com', '', $obj->title);
                /*End get label*/

                // foreach($html->find('#share-this') as $item) {
                //     $item->outertext = '';
                // }
                // foreach($html->find('.td-post-featured-image') as $item) {
                //     $item->outertext = '';
                // }
                // $html->save();
                $content = @$html->find ( '#main #content-inner', 0 )->innertext;
                $content = preg_replace('/<center\b[^>]*>(.*?)<\/center>/is', "", $content);
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-sizes=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-src=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-recalc-dims=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-large-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-medium-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-meta=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-description class=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-orig-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-permalink=".*?"/i', "$1", $content );
                $content = str_replace('--Advertisement--', '', $content);
                $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                preg_match_all( $regex, $content, $matches );
                $ImgSrc = array_pop($matches);
                // reversing the matches array
                if(!empty($ImgSrc)) {
                    foreach ($ImgSrc as $image) {
                        $imagedd = strtok($image, "?");
                        $file_title = basename($imagedd);
                        $fileName = FCPATH . 'uploads/image/'.$file_title;
                        @copy($imagedd, $fileName);   
                        $images = $this->mod_general->uploadtoImgur($fileName);
                        if(empty($images)) {
                            $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                            $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                            if($images) {
                                @unlink($fileName);
                            }
                        } else {
                            $gimage = @$images; 
                            @unlink($fileName);
                        }
                        if(!empty($gimage)) {
                            $content = str_replace($image,$gimage,$content);
                        }
                    }
                }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'ploynaphas.com':
                /*get label*/
                $label = trim($html->find('.bdaia-crumb-container span a',1)->plaintext);
                if($label == 'หวย') {
                    $obj->label = 'ไลฟ์สไตล์,เสี่ยงดวง - หวย';
                } else if($label == 'ENTERTAINMENT UPDATE') {
                    $obj->label = 'ข่าว,ข่าวบันเทิง';
                } else if($label == 'NEWS UPDATE') {
                    $obj->label = 'ข่าว,ข่าวด่วน';
                } else if($label == 'NEWS UPDATE') {
                    $obj->label = 'ข่าว,ข่าวด่วน';
                } else if($label == 'LIFESTYLE UPDATE') {
                    $obj->label = 'ไลฟ์สไตล์';
                } else {
                    $obj->label = $label;
                }
                //$obj->title = str_replace('หมีหวย.com', '', $obj->title);
                /*End get label*/

                // foreach($html->find('#share-this') as $item) {
                //     $item->outertext = '';
                // }
                // foreach($html->find('.td-post-featured-image') as $item) {
                //     $item->outertext = '';
                // }
                // $html->save();
                $content = @$html->find ( '#content #wtr-content', 0 )->innertext;
                $content = preg_replace('/<center\b[^>]*>(.*?)<\/center>/is', "", $content);
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-sizes=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-src=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-recalc-dims=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-large-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-medium-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-meta=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-description class=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-orig-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-permalink=".*?"/i', "$1", $content );
                $content = str_replace('--Advertisement--', '', $content);
                // $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                // preg_match_all( $regex, $content, $matches );
                // $ImgSrc = array_pop($matches);
                // // reversing the matches array
                // if(!empty($ImgSrc)) {
                //     foreach ($ImgSrc as $image) {
                //         $imagedd = strtok($image, "?");
                //         $file_title = basename($imagedd);
                //         $fileName = FCPATH . 'uploads/image/'.$file_title;
                //         @copy($imagedd, $fileName);   
                //         $images = $this->mod_general->uploadtoImgur($fileName);
                //         if(empty($images)) {
                //             $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                //             $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                //             if($images) {
                //                 @unlink($fileName);
                //             }
                //         } else {
                //             $gimage = @$images; 
                //             @unlink($fileName);
                //         }
                //         if(!empty($gimage)) {
                //             $content = str_replace($image,$gimage,$content);
                //         }
                //     }
                // }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            case 'www.tdaily.us':
                /*get label*/
                // $label = trim($html->find('.bdaia-crumb-container span a',1)->plaintext);
                // if($label == 'หวย') {
                //     $obj->label = 'ไลฟ์สไตล์,เสี่ยงดวง - หวย';
                // } else if($label == 'ENTERTAINMENT UPDATE') {
                //     $obj->label = 'ข่าว,ข่าวบันเทิง';
                // } else if($label == 'NEWS UPDATE') {
                //     $obj->label = 'ข่าว,ข่าวด่วน';
                // } else if($label == 'NEWS UPDATE') {
                //     $obj->label = 'ข่าว,ข่าวด่วน';
                // } else if($label == 'LIFESTYLE UPDATE') {
                //     $obj->label = 'ไลฟ์สไตล์';
                // } else {
                //     $obj->label = $label;
                // }
                //$obj->title = str_replace('หมีหวย.com', '', $obj->title);
                /*End get label*/

                // foreach($html->find('#share-this') as $item) {
                //     $item->outertext = '';
                // }
                // foreach($html->find('.td-post-featured-image') as $item) {
                //     $item->outertext = '';
                // }
                // $html->save();
                $obj->label = 'ข่าว';
                $content = @$html->find ( '#main .entry-content', 0 )->innertext;
                $content = preg_replace('/<center\b[^>]*>(.*?)<\/center>/is', "", $content);
                $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-srcset=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-sizes=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-lazy-src=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-recalc-dims=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-large-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-medium-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-meta=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-image-description class=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-orig-file=".*?"/i', "$1", $content );
                $content = preg_replace( '/(<[^>]+) data-permalink=".*?"/i', "$1", $content );
                $content = str_replace('--Advertisement--', '', $content);
                // $regex = '/< *img[^>]*src *= *["\']?([^"\']*)/';
                // preg_match_all( $regex, $content, $matches );
                // $ImgSrc = array_pop($matches);
                // // reversing the matches array
                // if(!empty($ImgSrc)) {
                //     foreach ($ImgSrc as $image) {
                //         $imagedd = strtok($image, "?");
                //         $file_title = basename($imagedd);
                //         $fileName = FCPATH . 'uploads/image/'.$file_title;
                //         @copy($imagedd, $fileName);   
                //         $images = $this->mod_general->uploadtoImgur($fileName);
                //         if(empty($images)) {
                //             $apiKey = '76e9b194c1bdc616d4f8bb6cf295ce51';
                //             $images = $this->Mod_general->uploadToImgbb($fileName, $apiKey);
                //             if($images) {
                //                 @unlink($fileName);
                //             }
                //         } else {
                //             $gimage = @$images; 
                //             @unlink($fileName);
                //         }
                //         if(!empty($gimage)) {
                //             $content = str_replace($image,$gimage,$content);
                //         }
                //     }
                // }
                $obj->vid = '';
                $obj->conent = $content;
                $obj->fromsite = $parse['host'];
                $obj->site = 'site';
                return $obj;
                break;
            default:
                if (preg_match ( '/blogspot/', $url ) && empty($oldurl)) {
                    $obj->label = '';
                    foreach($html->find('#share-this') as $item) {
                        $item->outertext = '';
                    }
                    foreach($html->find('.td-post-featured-image') as $item) {
                        $item->outertext = '';
                    }
                    foreach($html->find('.article-share-cont') as $item) {
                        $item->outertext = '';
                    }
                    foreach($html->find('form') as $item) {
                        $item->outertext = '';
                    }
                    $html->save();
                    $content = @$html->find ( '#Blog1 .entry-content', 0 );
                    $content = preg_replace('/<center\b[^>]*>(.*?)<\/center>/is', "", $content);
                    $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
                    $content = preg_replace('/<ins\b[^>]*>(.*?)<\/ins>/is', '<div class="setAds"></div>', $content);
                    $content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
                    $content = preg_replace( '/(<[^>]+) srcset=".*?"/i', "$1", $content );
                    $content = preg_replace( '/(<[^>]+) data-lazy-srcset=".*?"/i', "$1", $content );
                    $content = preg_replace( '/(<[^>]+) data-lazy-sizes=".*?"/i', "$1", $content );
                    $content = preg_replace( '/(<[^>]+) data-lazy-src=".*?"/i', "$1", $content );
                    $content = preg_replace( '/(<[^>]+) data-recalc-dims=".*?"/i', "$1", $content );
                    $content = preg_replace( '/(<[^>]+) data-large-file=".*?"/i', "$1", $content );
                    $content = preg_replace( '/(<[^>]+) data-medium-file=".*?"/i', "$1", $content );
                    $content = preg_replace( '/(<[^>]+) data-image-meta=".*?"/i', "$1", $content );
                    $content = preg_replace( '/(<[^>]+) data-image-description class=".*?"/i', "$1", $content );
                    $content = preg_replace( '/(<[^>]+) data-orig-file=".*?"/i', "$1", $content );
                    $content = preg_replace( '/(<[^>]+) data-permalink=".*?"/i', "$1", $content );
                    $content = str_replace('--Advertisement--', '', $content);
                    $obj->conent = $content;
                    $obj->fromsite = $parse['host'];
                    $obj->site = 'site';
                    return $obj;
                    break;
                } else {
                    $obj->label = '';
                    $dataA = @$html->find ( '#main .post', 0 );
                    $checked = @$html->find ( '#Blog1 .youtube_link', 0 );
                    $iframeCheck = @$html->find ( '#Blog1 iframe', 0 );
                    $iframeCheckH2 = @$html->find ( '#Blog1 h2', 0 );
                    $regex = '~var customcode = ({(.*?)(?=};)};)~';
                    preg_match_all($regex, $dataA, $matches);
                    if(!empty($matches[0][0])) {
                        $json = $matches[0][0];
                        $jsonArr = explode('"', $json);
                        $html1 = file_get_html ( 'https://www.youtube.com/watch?v='.$jsonArr[11] );
                        $obj->title = @$html1->find ( 'meta[property=og:title]', 0 )->content; 
                        //$title = $html1->find ( 'tit.youtube_linkle', 0 )->innertext;
                        $obj->vid = $jsonArr[11];
                    } else if (!empty($checked)) {
                        $iframe = @$checked->href;
                        $html1 = file_get_html ( $iframe );
                        $obj->title = @$html1->find ( 'meta[property=og:title]', 0 )->content; 
                        //$title = $html1->find ( 'tit.youtube_linkle', 0 )->innertext;
                        $obj->vid = $iframe;
                    } else if(!empty($iframeCheck)) {
                        $iframe = @$html->find ( '#Blog1 iframe', 0 )->src;
                        $html1 = file_get_html ( $iframe );
                        $obj->title = $html1->find ( 'title', 0 )->innertext;
                        $obj->vid = $iframe;
                    } else {
                        $obj->title = @$html->find ( 'meta[property=og:title]', 0 )->content; 
                    }
                    $obj->conent = '';
                    $obj->fromsite = '';
                    $obj->site = 'old';
                    return $obj;
                    break;
                }
        }
    }
}

/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */
