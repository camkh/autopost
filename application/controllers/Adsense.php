<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class adinsert extends CI_Controller {


    var $content;
    var $paragraphs;    
    var $ad_pos1=2;
    var $ad_pos2;
    var $ad_pos3;
    var $ad= '<div class="setAdsSection"></div>';

public function __construct($content) {

    if(!$content)
        return $content;

    $this->set_content($content);

    $this->paragrapherize(2);
    $this->paragraph_numbers();
    $this->get_first_pos();
    $this->paragrapherize();
    $this->paragraph_numbers();
    $this->find_images();
    $this->find_ad_pos(); 
    $this->insert_ads();

}


public function echo_content(){
    return $this->content;
}

private function insert_ads() {

    if($this->ad_pos2 && $this->ad_pos2 != 'end'):
        $posb= $this->ad_pos2;
        $this->content=substr_replace($this->content,$this->ad,$posb ,0);
    else:
        $this->content.= $this->ad;    
    endif;

    //comment the below line to remove last image insertion 
    $this->content.= $this->ad;
}

private function get_first_pos() {

    $i=0;

    foreach($this->paragraphs as $key=>$data):
        if($i==0):

            $length= $data['end']-$data['start'];
            $string= substr($this->content, $data['start'],$length);    
            $newkey= $key+1;
            $lengthb= @$this->paragraphs[$newkey]['end']- @$this->paragraphs[$newkey]['start'];
            $stringb= substr($this->content, @$this->paragraphs[$newkey]['start'],$lengthb);

            $wcount= count(explode(' ', $string));

            if( preg_match('/(<img[^>]+>)/i', $string, $image) ):
                    $newstring=preg_replace('/(<img[^>]+>)/i', '', $string);

                        if($wcount>10):
                            $newstring.=$this->ad;
                            $this->ad_pos1=1;       
                            $this->content=str_replace($string,$newstring,$this->content);
                        endif;
            else:
                        if($wcount>10) :
                            $newstring=$string.$this->ad;
                            //echo $newstring;
                            $this->ad_pos1=1;
                            //$this->content=str_replace($string,$newstring,$this->content);
                            $this->content= preg_replace('~'.$string.'~', $newstring, $this->content, 1);
                        endif;
            endif;

            if( preg_match('/(<img[^>]+>)/i', $stringb, $imageb) ):
                        $newstringb=preg_replace('/(<img[^>]+>)/i', '', $stringb);  
                        if($wcount<10) :
                        $newstringb.=$this->ad;
                        $this->ad_pos1=2;
                        $this->content=str_replace($stringb,$newstringb,$this->content);
                        endif;
            else:
                        if($wcount<10) :
                            $newstringb=$stringb.$this->ad;
                            $this->ad_pos1=2;
                            $this->content=str_replace($stringb,$newstringb,$this->content);
                        endif;
            endif;

        else:
            break;
        endif;
        $i++;       
    endforeach;
}


private function find_ad_pos() {

    $remainder_images= $this->paragraph_count;
    if($remainder_images < $this->ad_pos1 + 3):
        $this->ad_pos2='end';
    else:   

        foreach($this->paragraphs as $key=>$data):
            $p[]=$key;
        endforeach;

        unset($p[0]);
        unset($p[1]);

        $startpos= $this->ad_pos1 + 2;
        $possible_ad_positions= $remainder_images - $startpos;
    //figure out half way
        if($remainder_images < 3): //use end pos
            $pos1= $startpos;
            $pos1=$this->getclosestkey($pos1, $p);
        else: // dont use end pos
            $pos1=  ($remainder_images/2)-1;
            $pos1= $this->getclosestkey($pos1, $p);
        endif;
        $this->ad_pos2= @$this->paragraphs[$pos1]['end'];
    endif;
}


private function getclosestkey($key, $keys) {
    $close= 0;
    foreach($keys as $item): //4>4
        if($close == null || $key - $close > $item - $key ) :
          $close = $item;
        endif;
    endforeach;
    return $close;
}



private function find_images() {

    foreach($this->paragraphs as $item=>$key):
        $length= @$key['end']- @$key['start'];
        $string= substr($this->content, $key['start'],$length);
        if(strpos($string,'src')!==false && $item !=0 && $item !=1):
            //unset the number, find start in paragraphs array + 1 after
            unset($this->paragraphs[$item]);
            $nextitem= $item+1;
            $previtem= $item-1;
            unset($this->paragraphs[$nextitem]);
            unset($this->paragraphs[$previtem]);
        endif;          
    endforeach;

}





private function paragraph_numbers() {

    $i=1;
    foreach($this->paragraphs as $item):
        $i++;
    endforeach; 
    $this->paragraph_count=$i;
}

private function paragrapherize($limit=0) {

    $current_pos=0;
    $i=0;

    while( strpos($this->content, '<p', $current_pos) !== false ):

    if($limit && $i==$limit)
        break;

    if($i==105) {
        break;
    }
        if($i!=0) {
            $current_pos++; 
        }


        $paragraph[$i]['start']=strpos($this->content, '<p', $current_pos);//1

    //looking for the next time a /p follows a /p so is less than the next position of p

    $nextp= strpos($this->content, '<p', $paragraph[$i]['start']+1); //14 failing on next???
    $nextendp= strpos($this->content, '</p>', $current_pos);//22

    if($nextp>$nextendp)://NO
        $paragraph[$i]['end']=$nextendp;
        if( ($nextendp - $paragraph[$i]['start']) < 80 ):
            unset($paragraph[$i]);
        endif;

        $current_pos= $nextendp;
        $i++;   
    else:   

    $startipos = $nextendp;

        $b=0;                                           
        do {
            if($b==100){
               break;
            }

            $nextp= strpos($this->content, '<p', $startipos); //230
            $nextendp= strpos($this->content, '</p>', $startipos+1);//224


            if($nextp>$nextendp) {

                $paragraph[$i]['end']=$nextendp;
                $current_pos= $nextendp;

                $i++;
            } else {
                $startipos = $nextendp+1;
            }
            $b++;

        } while ($nextp < $nextendp );
    endif;
        endwhile;
        $this->paragraphs= $paragraph;
    }

    public function set_content($content) {
        $this->content= $content;
    }

}