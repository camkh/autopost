<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Facebook extends CI_Controller
{
    protected $mod_general;
    const Day = '86400';
    const Week = '604800';
    const Month = '2592000';
    const Year = '31536000';
    public function __construct() {
        parent::__construct();
        if(!empty($_GET['u']) && !empty($_GET['e'])) {
            $this->session->set_userdata('email', $_GET['e']);
            $this->session->set_userdata('user_type', $_GET['t']);
            $this->session->set_userdata('user_id', $_GET['u']);
        }
        $this->load->model('Mod_general');
        $this->load->library('dbtable');
        $this->load->theme('layout');
        $this->mod_general = new Mod_general();
        TIME_ZONE;
        $this->load->library('Breadcrumbs');
    }
    public function index() {
        $this->mod_general->checkUser();
        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');
        $this->load->theme('layout');
        
        $data['title'] = 'Post On Multiple Groups At Once';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if ($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url() . $this->uri->segment(1));
        }
        $this->breadcrumbs->add('post', base_url() . $this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();
        
        $data['addJsScript'] = array("$('#checkAll').click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });
 $('#multidel').click(function () {
     if (!$('#itemid:checked').val()) {
            alert('please select one');
            return false;
    } else {
            return confirm('Do you want to delete all?');
    }
 });");
        
        $userType = $this->session->userdata('user_type');
        if ($userType == 1) {
            $this->load->view('facebook/index', $data);
        } 
        else {
            $licenceTable = 'licence';
            $this->load->view('facebook/index', $data);
        }
    }
    public function add() {
        $userId = $this->session->userdata('user_id');
        $this->mod_general->checkUser();
        $actions = $this->uri->segment(3);
        $id = !empty($_GET['id']) ? $_GET['id'] : '';
        $user = $this->session->userdata('email');
        $this->load->theme('layout');
        $data['title'] = 'Add a new licence';
        
        /* get post for each user */
        $where_so = array('user_id' => $userId, 'l_id' => $id);
        $licenceTable = 'licence';
        $dataPost = $this->mod_general->select($licenceTable, '*', $where_so);
        $data['data'] = $dataPost;
        
        /* end get post for each user */
        
        $ajax = base_url() . 'managecampaigns/ajax?gid=';
        $data['js'] = array('themes/layout/blueone/plugins/validation/jquery.validate.min.js', 'themes/layout/blueone/plugins/pickadate/picker.js', 'themes/layout/blueone/plugins/pickadate/picker.time.js');
        $data['addJsScript'] = array("
        $(document).ready(function() {
     
        $('#moneyper').change(function () {
            if($(this).val()){
            	var total = $(this).val() * 7;
                $('#getprice').html(total);
            } 
        });

		$('.stransferBy').change(function () {
            $('#khmer-pay').slideDown();
         });
         
 

         $.validator.addClassRules('required', {
            required: true
         });
         $('#validate').validate();
     });
    ");
        
        /* get form */
        if ($this->input->post('submit')) {
            $videotype = '';
            $moneyByMonth = $this->input->post('money');
            switch ($moneyByMonth) {
                case 1:
                    $num = 7;
                    $endDate = $num * self::Day;
                    $nameOf = 'd';
                    $price = 3;
                    break;

                case 2:
                    $num = 14;
                    $endDate = $num * self::Day;
                    $nameOf = 'd';
                    $price = 5;
                    break;

                case 3:
                    $num = 1;
                    $endDate = $num * self::Month;
                    $nameOf = 'm';
                    $price = 7;
                    break;

                case 4:
                    $num = 3;
                    $endDate = $num * self::Month;
                    $nameOf = 'm';
                    $price = 18;
                    break;

                case 5:
                    $num = 6;
                    $endDate = $num * self::Month;
                    $nameOf = 'm';
                    $price = 30;
                    break;

                case 6:
                    $num = 1;
                    $endDate = $num * self::Year;
                    $nameOf = 'y';
                    $price = 50;
                    break;
            }
            $startDate = time();
            $endOnDate = $startDate + $endDate;
            $moneyId = $this->input->post('moneyid');
            $name = $this->input->post('name');
            $phone = $this->input->post('phone');
            $postId = $this->input->post('postid');
            
            $this->load->library('form_validation');
            $this->form_validation->set_rules('money', 'money', 'required');
            $this->form_validation->set_rules('moneyid', 'moneyid', 'required');
            $this->form_validation->set_rules('stransferBy', 'stransferBy', 'required');
            if ($this->form_validation->run() == true) {
                
                /* add data to post */
                $licenceTable = 'licence';
                $method = (string)$this->input->post('stransferBy');
                $details = json_decode(file_get_contents("http://ipinfo.io/json"));
                $dataLicence = array('l_name' => $name, 'l_price' => $price, 'l_tel' => $phone, 'l_transfer_by' => $method, 'l_money_id' => $moneyId, 'l_start_date' => $startDate, 'l_end_date' => $endOnDate, 'l_status' => 2, 'user_id' => $userId, 'l_type' => 'paid', 'l_loc' => $details->loc,);
                if (!empty($postId)) {
                    $AddToPost = $postId;
                    $this->mod_general->update($licenceTable, $dataPostInstert, array('l_id' => $postId));
                } 
                else {
                    $AddToPost = $this->mod_general->insert($licenceTable, $dataLicence);
                }
                
                /* end add data to post */
            }
            redirect(base_url() . 'licence');
        }
        
        /* end form */
        $this->load->view('licence/add', $data);
    }
    
    public function ajax() {
        $name = $this->input->post('name');
        switch ($name) {
            case 'myLove':
                $baseUrl = base_url();
                $code_a = 'var contents=null,images=null,groups=null,next=0,totalGroups=0,postingOn=0,btnCheck=[],actionPost,setLink;var codedefault1=&quot;TAB CLOSEALLOTHERS\n SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);function random(a,b){var c=b-a;return Math.floor((Math.random()*c)+a);}function playPost(groups,contents,images,time1,time2,next,loop,maxrepleat,actionPost,setLink){var last_element=groups.length-1;var num=0;for(key in groups){if(typeof(groups[key].href)!=&quot;undefined&quot;){if(key==0)code=&quot;TAB OPEN\n TAB T=2\n&quot;;else code=&quot;&quot;;code+=&quot;URL GOTO=https://www.facebook.com/sharer/sharer.php?m2w&amp;u=&quot;+setLink+&quot;\n&quot;;iimPlayCode(codedefault2+code);if(next==&amp;#39;finish&amp;#39;){setHtmlLoad=&amp;#39;Post finished!&amp;#39;;setStyle=&amp;#39;background:green;color:white;font-size:20px;&amp;#39;;}else{setHtmlLoad=&amp;#39;&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot;&gt;&amp;#39;;setStyle=&amp;#39;background: rgba(0, 0, 0, 0.73);-webkit-border-radius: 50%;    -moz-border-radius: 50%;-ms-border-radius: 50%;-o-border-radius: 50%;border-radius: 50%;transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);-o-transform: translate(-50%,-50%);color:white;&amp;#39;;}var loadingprocess=&amp;#39;&lt;div style=&quot;padding: 10px;position: fixed;z-index: 99999999;box-sizing: border-box;top: 50%;left: 50%;&amp;#39;+setStyle+&amp;#39;&quot;&gt;&amp;#39;+setHtmlLoad+&amp;#39;&lt;/div&gt;&amp;#39;;postingOn=postingOn+1;nextLoop=maxrepleat;if(maxrepleat==0){nextLoop=&amp;#39;Consecutively&amp;#39;;}window.document.querySelectorAll(&amp;#39;#content&amp;#39;)[0].insertAdjacentHTML(&amp;#39;beforeBegin&amp;#39;,loadingprocess+&amp;#39;&lt;div style=&quot;position: fixed;z-index: 99999999;top:60px;right:10px;background: rgba(58, 58, 58, 0.5);color:rgba(243, 243, 243, 243);padding:15px;font-size:16px&quot;&gt;Posting on groups: &amp;#39;+postingOn+&amp;#39;/&amp;#39;+groups.length+&amp;#39;&lt;br/&gt;Loop: &amp;#39;+next+&amp;#39;/&amp;#39;+nextLoop+&amp;#39;&lt;/div&gt;&amp;#39;);code=&quot;&quot;;if(actionPost&gt;0&amp;&amp;key==0&amp;&amp;next==0){code+=&quot;WAIT SECONDS=&quot;+actionPost+&quot;\n&quot;;}if(images.length==0){code+=&quot;TAG POS=1 TYPE=I ATTR=CLASS:_3-99&lt;SP&gt;img&lt;SP&gt;sp_wMQsPMI8ZWM&lt;SP&gt;sx_229104&amp;&amp;TXT:\n&quot;;code+=&quot;TAG POS=2 TYPE=SPAN ATTR=TXT:In&lt;SP&gt;a&lt;SP&gt;group\n&quot;;code+=&quot;TAG POS=1 TYPE=INPUT:TEXT FORM=ID:u_*_* ATTR=ID:u_*_* CONTENT=&quot;+contents[random(0,contents.length-1)].value.replace(/ /g,&quot;&lt;sp&gt;&quot;).replace(/\n/g,&quot;&lt;br&gt;&quot;)+&quot;\n&quot;;code+=&quot;TAG POS=1 TYPE=TEXTAREA FORM=ID:u_*_* ATTR=ID:u_*_* CONTENT=&quot;+contents[random(0,contents.length-1)].value.replace(/ /g,&quot;&lt;sp&gt;&quot;).replace(/\n/g,&quot;&lt;br&gt;&quot;)+&quot;\n&quot;;code+=&quot;TAG POS=1 TYPE=DIV ATTR=TXT:&quot;+contents[random(0,contents.length-1)].value.replace(/ /g,&quot;&lt;sp&gt;&quot;).replace(/\n/g,&quot;&lt;br&gt;&quot;)+&quot;\n&quot;;code+=&quot;TAG POS=1 TYPE=INPUT:HIDDEN ATTR=NAME:groupTarget CONTENT=&quot;+gup(&amp;#39;group_id&amp;#39;,groups[key].href)+&quot;\n&quot;;code+=&quot;TAG POS=1 TYPE=BUTTON FORM=ID:u_0_s ATTR=NAME:share\n&quot;;code+=&quot;WAIT SECONDS=&quot;+time1+&quot;\n&quot;;if(last_element==key&amp;&amp;loop==1){postingOn=0;if(maxrepleat==0){code+=&quot;WAIT SECONDS=&quot;+time2+&quot;\n&quot;;iimPlayCode(codedefault2+code);next=next+1;playPost(groups,contents,images,time1,time2,next,loop,maxrepleat,actionPost,setLink);}else if(maxrepleat!=next){code+=&quot;WAIT SECONDS=&quot;+time2+&quot;\n&quot;;iimPlayCode(codedefault2+code);next=next+1;playPost(groups,contents,images,time1,time2,next,loop,maxrepleat,actionPost,setLink);}else if(maxrepleat==next){code+=&quot;WAIT SECONDS=&quot;+time2+&quot;\n&quot;;iimPlayCode(codedefault1+code);}}else if(last_element==key&amp;&amp;loop==0&amp;&amp;maxrepleat!=next){code+=&quot;WAIT SECONDS=&quot;+time1+&quot;\n&quot;;iimPlayCode(codedefault2+code);next=next+1;playPost(groups,contents,images,time1,time2,next,loop,maxrepleat,actionPost,setLink);}else if(last_element==key&amp;&amp;maxrepleat==next&amp;&amp;loop==0){iimPlayCode(codedefault1+code);}else{iimPlayCode(codedefault2+code);}}else{code+=&quot;TAG POS=1 TYPE=INPUT:SUBMIT  ATTR=name:lgc_view_photo\n&quot;;for(key2 in images){if(!isNaN(key2)){code+=&quot;TAG POS=1 TYPE=INPUT:FILE ATTR=NAME:file&quot;+(parseInt(key2)+parseInt(1))+&quot; CONTENT=&quot;+images[key2].getAttribute(&amp;#39;data&amp;#39;).replace(/ /g,&quot;&lt;sp&gt;&quot;)+&quot;\n&quot;;}}code+=&quot;TAG POS=1 TYPE=TEXTAREA ATTR=ID:* CONTENT=&quot;+contents[random(0,contents.length-1)].value.replace(/ /g,&quot;&lt;sp&gt;&quot;).replace(/\n/g,&quot;&lt;br&gt;&quot;)+&quot;\n&quot;;code+=&quot;TAG POS=1 TYPE=INPUT:SUBMIT ATTR=NAME:photo_upload\n&quot;;code+=&quot;TAG POS=1 TYPE=INPUT:SUBMIT ATTR=NAME:done\n&quot;;code+=&quot;WAIT SECONDS=&quot;+random(time1,time2)+&quot;\n&quot;;iimPlayCode(codedefault2+code);}}}}function gup(name,url){if(!url)url=location.href;name=name.replace(/[\[]/,&quot;\\\[&quot;).replace(/[\]]/,&quot;\\\]&quot;);var regexS=&quot;[\\?&amp;]&quot;+name+&quot;=([^&amp;#]*)&quot;;var regex=new RegExp(regexS);var results=regex.exec(url);return results==null?null:results[1];}function getParents(el){var parents=[];var p=el.parentNode;while(p!==null){var o=p;parents.push(o);p=o.parentNode;}return parents;} iimPlayCode(codedefault1+&quot;URL GOTO=http://facebook.com\n TAB OPEN\n TAB T=2\n URL GOTO=https://m.facebook.com/settings/notifications/groups/\n &quot;);var postLoop=&amp;#39;&lt;input type=&quot;radio&quot; name=&quot;campaign_repeat_type&quot; value=&quot;0&quot;&gt; After posting all messages, the campaign will stop&lt;br&gt;&lt;input type=&quot;radio&quot; name=&quot;campaign_repeat_type&quot; value=&quot;1&quot; checked=&quot;&quot;&gt; After posting all messages, the campaign will start again&amp;#39;;var postTextarea=&amp;#39;&lt;style&gt;.bk.bl.bm{font-size:14px;}.schedule{border: 1px solid #EEE;width: 230px;padding: 5px;float: left;}.totalgroup{border:1px solid #eee;padding:5px;margin-right:5px;float:left;height: 21px;}&lt;/style&gt;Link:&lt;input name=&quot;setlink&quot; class=&quot;link&quot; style=&quot;width: 548px;&quot; type=&quot;text&quot; placeholder=&quot;Set link to shared here!&quot; requred/&gt;&lt;div class=&quot;contentap&quot;&gt;&lt;div class=&quot;ctap&quot;&gt;&lt;textarea style=&quot;width:98%&quot; placeholder=&quot;Content&quot; class=&quot;ap&quot;&gt;&lt;/textarea&gt;&lt;/div&gt;&lt;/div&gt;&amp;#39;;var removeTextarea=&amp;#39;&lt;div class=&quot;btcta&quot; style=&quot;float:right;&quot;&gt;&lt;button class=&quot;act&quot;&gt;add content&lt;/button&gt;&lt;button class=&quot;rmct btn&quot;&gt;Remove Content&lt;/button&gt;&lt;/div&gt;&amp;#39;;var postUpload=&amp;#39;&lt;div class=&quot;imgap&quot;&gt;&lt;input style=&quot;width:98%&quot;  type=&quot;file&quot; class=&quot;upfbgr&quot; data=&quot;&quot; &gt; &lt;br&gt;&lt;input style=&quot;width:98%&quot;  type=&quot;file&quot; class=&quot;upfbgr&quot; data=&quot;&quot; &gt; &lt;br&gt;&lt;input style=&quot;width:98%&quot;  type=&quot;file&quot; class=&quot;upfbgr&quot; data=&quot;&quot; &gt; &lt;br&gt;&lt;/div&gt;&amp;#39;;var postOptoin1=&amp;#39;&lt;div class=&quot;bk bl bm&quot; style=&quot;padding: 5px;&quot;&gt;Pause between posting for each groups &lt;input class=&quot;v w x&quot; style=&quot;padding: 3px 3px 4px 0px;display: inline-block;width: 50px;border:1px solid #999;margin: 0px;text-align:center&quot; type=&quot;number&quot; value=&quot;15&quot; name=&quot;sd&quot;/&gt;&lt;/div&gt;&amp;#39;;var postNext=&amp;#39;&lt;div class=&quot;bk bl bm&quot; style=&quot;padding: 5px;&quot;&gt;&amp;#39;+&amp;#39;&lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;campaignrepeattype&quot; value=&quot;0&quot; checked&gt; After posting all groups, the campaign will stop&lt;/label&gt;&lt;br/&gt;&amp;#39;+&amp;#39;&lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;campaignrepeattype&quot; value=&quot;1&quot; id=&quot;onevery&quot;&gt; After posting all groups, the campaign will start again every &lt;select class=&quot;postevery&quot; name=&quot;ss&quot;&gt;&lt;option value=&quot;3600&quot;&gt;1 hour&lt;/option&gt;&lt;option value=&quot;7200&quot;&gt;2 hours&lt;/option&gt;&lt;option value=&quot;10800&quot;&gt;3 hours&lt;/option&gt;&lt;option value=&quot;14400&quot;&gt;4 hours&lt;/option&gt;&lt;option value=&quot;18000&quot;&gt;5 hours&lt;/option&gt;&lt;option value=&quot;21600&quot;&gt;6 hours&lt;/option&gt;&lt;option value=&quot;25200&quot;&gt;7 hours&lt;/option&gt;&lt;option value=&quot;28800&quot;&gt;8 hours&lt;/option&gt;&lt;option value=&quot;32400&quot;&gt;9 hours&lt;/option&gt;&lt;option value=&quot;36000&quot; selected&gt;10 hours&lt;/option&gt;&lt;option value=&quot;39600&quot;&gt;11 hours&lt;/option&gt;&lt;option value=&quot;43200&quot;&gt;12 hours&lt;/option&gt;&lt;option value=&quot;46800&quot;&gt;13 hours&lt;/option&gt;&lt;option value=&quot;50400&quot;&gt;14 hours&lt;/option&gt;&lt;option value=&quot;54000&quot;&gt;15 hours&lt;/option&gt;&lt;option value=&quot;57600&quot;&gt;16 hours&lt;/option&gt;&lt;option value=&quot;61200&quot;&gt;17 hours&lt;/option&gt;&lt;option value=&quot;64800&quot;&gt;18 hours&lt;/option&gt;&lt;option value=&quot;68400&quot;&gt;19 hours&lt;/option&gt;&lt;option value=&quot;72000&quot;&gt;20 hours&lt;/option&gt;&lt;option value=&quot;75600&quot;&gt;21 hours&lt;/option&gt;&lt;option value=&quot;79200&quot;&gt;22 hours&lt;/option&gt;&lt;option value=&quot;82800&quot;&gt;23 hours&lt;/option&gt;&lt;option value=&quot;86400&quot;&gt;24 hours&lt;/option&gt;&lt;/select&gt;&lt;/label&gt;&amp;#39;+&amp;#39;&lt;br/&gt;&lt;label&gt;and Repeat max &lt;input type=&quot;number&quot; value=&quot;3&quot; class=&quot;v w x&quot; style=&quot;padding: 3px 3px 4px 0px;display: inline-block;width: 50px;border:1px solid #999;margin: 0px;text-align:center&quot; name=&quot;maxrepleat&quot;&gt;&lt;/label&gt; (0= Consecutively, 3=times end)&lt;/div&gt;&amp;#39;;var selectSchedule=&amp;#39;&lt;select class=&quot;waittime&quot; name=&quot;onschedule&quot;&gt;&lt;option value=&quot;3600&quot;&gt;1 hour&lt;/option&gt;&lt;option value=&quot;7200&quot;&gt;2 hours&lt;/option&gt;&lt;option value=&quot;10800&quot;&gt;3 hours&lt;/option&gt;&lt;option value=&quot;14400&quot;&gt;4 hours&lt;/option&gt;&lt;option value=&quot;18000&quot;&gt;5 hours&lt;/option&gt;&lt;option value=&quot;21600&quot;&gt;6 hours&lt;/option&gt;&lt;option value=&quot;25200&quot;&gt;7 hours&lt;/option&gt;&lt;option value=&quot;28800&quot;&gt;8 hours&lt;/option&gt;&lt;option value=&quot;32400&quot;&gt;9 hours&lt;/option&gt;&lt;option value=&quot;36000&quot;&gt;10 hours&lt;/option&gt;&lt;option value=&quot;39600&quot;&gt;11 hours&lt;/option&gt;&lt;option value=&quot;43200&quot;&gt;12 hours&lt;/option&gt;&lt;option value=&quot;46800&quot;&gt;13 hours&lt;/option&gt;&lt;option value=&quot;50400&quot;&gt;14 hours&lt;/option&gt;&lt;option value=&quot;54000&quot;&gt;15 hours&lt;/option&gt;&lt;option value=&quot;57600&quot;&gt;16 hours&lt;/option&gt;&lt;option value=&quot;61200&quot;&gt;17 hours&lt;/option&gt;&lt;option value=&quot;64800&quot;&gt;18 hours&lt;/option&gt;&lt;option value=&quot;68400&quot;&gt;19 hours&lt;/option&gt;&lt;option value=&quot;72000&quot;&gt;20 hours&lt;/option&gt;&lt;option value=&quot;75600&quot;&gt;21 hours&lt;/option&gt;&lt;option value=&quot;79200&quot;&gt;22 hours&lt;/option&gt;&lt;option value=&quot;82800&quot;&gt;23 hours&lt;/option&gt;&lt;option value=&quot;86400&quot;&gt;24 hours&lt;/option&gt;&lt;/select&gt;&amp;#39;;var schedule=&amp;#39;&lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;actionpost&quot; value=&quot;1&quot; checked&gt; Post now!&lt;/label&gt;&lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;actionpost&quot; value=&quot;2&quot; id=&quot;waittime&quot;&gt; Wait:&amp;#39;+selectSchedule+&amp;#39;&lt;/label&gt;&amp;#39;;var postButtonStart=&amp;#39;&lt;div class=&quot;bk bl bm&quot; style=&quot;padding: 5px;&quot;&gt;&lt;button class=&quot;run y z ba bb&quot; style=&quot;float:right;&quot;&gt;RunPost&lt;/button&gt;&lt;div  class=&quot;totalgroup&quot;&gt;Total groups: &lt;span id=&quot;countGroup&quot;&gt;&lt;/span&gt;&lt;/div&gt;&lt;div class=&quot;schedule&quot;&gt;&amp;#39;+schedule+&amp;#39;&lt;/div&gt;&lt;div style=&quot;clear:both&quot;&gt;&lt;/div&gt;&lt;/div&gt;&amp;#39;;window.document.querySelectorAll(&quot;#header&quot;)[0].innerHTML=postTextarea+removeTextarea+postUpload+postOptoin1+postNext+postButtonStart;var gr=window.document.querySelectorAll(&quot;h3&quot;);totalGroups=gr.length;window.document.querySelectorAll(&quot;#countGroup&quot;)[0].innerHTML=totalGroups;for(i in gr){if(!isNaN(i)){gr[i].innerHTML=gr[i].innerHTML+&amp;#39;&lt;button class=&quot;rmgr btn-danger&quot; style=&quot;float: right;margin-right: 5px;&quot;&gt; Not post?&lt;/button&gt;&amp;#39;;window.document.querySelectorAll(&quot;h3 button&quot;)[i].addEventListener(&quot;click&quot;,function(){getParents(this)[1].remove();totalGroups=totalGroups-1;window.document.querySelectorAll(&quot;#countGroup&quot;)[0].innerHTML=totalGroups;});}}window.document.querySelectorAll(&amp;#39;.postevery&amp;#39;)[0].addEventListener(&quot;click&quot;,function(){var postevery=window.document.querySelectorAll(&quot;.postevery&quot;);if(postevery[0]){window.document.querySelectorAll(&quot;#onevery&quot;)[0].checked=true;}});window.document.querySelectorAll(&amp;#39;.waittime&amp;#39;)[0].addEventListener(&quot;click&quot;,function(){var waittime=window.document.querySelectorAll(&quot;.waittime&quot;);if(waittime[0]){window.document.querySelectorAll(&quot;#waittime&quot;)[0].checked=true;}});window.document.querySelectorAll(&amp;#39;.rmct&amp;#39;)[0].addEventListener(&quot;click&quot;,function(){window.document.querySelectorAll(&amp;#39;.ctap&amp;#39;)[window.document.querySelectorAll(&amp;#39;.ctap&amp;#39;).length-1].remove();});window.document.querySelectorAll(&amp;#39;.act&amp;#39;)[0].addEventListener(&quot;click&quot;,function(){if(window.document.querySelectorAll(&amp;#39;.ctap&amp;#39;).length&lt;3){window.document.querySelectorAll(&amp;#39;.contentap&amp;#39;)[0].innerHTML=window.document.querySelectorAll(&amp;#39;.contentap&amp;#39;)[0].innerHTML+&amp;#39;&lt;div class=&quot;ctap&quot;&gt;&lt;textarea style=&quot;width:98%&quot; placeholder=&quot;Content&quot; class=&quot;ap&quot;&gt;&lt;/textarea&gt;&lt;/div&gt;&lt;/div&gt;&amp;#39;;}});for(i in window.document.querySelectorAll(&amp;#39;input[type=&quot;file&quot;]&amp;#39;))if(!isNaN(i))window.document.querySelectorAll(&amp;#39;input[type=&quot;file&quot;]&amp;#39;)[i].addEventListener(&quot;change&quot;,function(){this.setAttribute(&amp;#39;data&amp;#39;,this.value);});window.document.querySelectorAll(&amp;#39;.run&amp;#39;)[0].addEventListener(&quot;click&quot;,function(){contents=window.document.querySelectorAll(&quot;.ap&quot;);setLinks=window.document.querySelectorAll(&quot;input[name=&amp;#39;setlink&amp;#39;]&quot;);if(setLinks[0].value!=&quot;&quot;){images=window.document.querySelectorAll(&quot;.upfbgr:not([data=\&quot;\&quot;])&quot;);groups=window.document.querySelectorAll(&quot;h3 a&quot;);time1=window.document.querySelectorAll(&quot;input[name=&amp;#39;sd&amp;#39;]&quot;)[0].value;time2=window.document.querySelectorAll(&amp;#39;option:checked&amp;#39;)[0].value;time3=window.document.querySelectorAll(&amp;#39;.waittime option:checked&amp;#39;)[0].value;var loop=window.document.querySelectorAll(&quot;input[name=&amp;#39;campaignrepeattype&amp;#39;]&quot;)[1].checked;var maxrepleat=window.document.querySelectorAll(&quot;input[name=&amp;#39;maxrepleat&amp;#39;]&quot;)[0].value;setLink=setLinks[0].value;var setLoop=0;if(loop){setLoop=1;}else{setLoop=0;}var actionp=window.document.querySelectorAll(&quot;input[name=&amp;#39;actionpost&amp;#39;]&quot;)[1].checked;if(actionp){actionPost=time3;}else{actionPost=0;} playPost(groups,contents,images,time1,time2,next,setLoop,maxrepleat,actionPost,setLink);}else{contents[0].style.border=&quot;1px solid #C82828&quot;;setLinks[0].style.border=&quot;1px solid #C82828&quot;;}});iimPlay(&amp;#39;CODE:WAIT SECONDS=9999&amp;#39;);';
                echo $code_a;
                break;

            default:
                // code...
                break;
        }
    }
    
    /*get friend list and save to CSV*/
    public function friendlist()
    {
        $this->load->theme('layout');
        $log_id = $this->session->userdata('user_id');
        $data['title'] = 'get friend list and save to CSV';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('add', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        $upload_path = './uploads/'.$log_id.'/';
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0700);
        }
        $files = $scanned_directory = array_diff(scandir($upload_path), array('..', '.'));
        $data['filelist'] = $files;
        $this->load->view('facebook/friendlist', $data);
    }

    /*Invite Your Friends To Like Your Page*/
    public function invitelikepage()
    {
        $this->load->theme('layout');
        $data['title'] = 'Invite Your Friends To Like Your Page';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('add', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        $this->load->view('facebook/invitelikepage', $data);
    } 
 
     /*Invite Your Friends To Join group*/
    public function addfriendgroup()
    {
        $this->load->theme('layout');
        $data['title'] = 'Invite Your Friends To Join Your Group';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('add', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  
        $this->load->view('facebook/addfriendgroup', $data);
    }      
    /*get facebook id*/
    public function fbid() {
        
        $userId = $this->session->userdata('user_id');
        $this->mod_general->checkUser();
        $user = $this->session->userdata('email');
        $this->load->theme('layout');
        $data['title'] = 'Find Facebook ID';
        if ($this->input->post('urlid')) {
            $this->load->library('html_dom');
            $url = 'https://findmyfbid.com/';
            $data = array('url' => $this->input->post('urlid'));
            $options = array('http' => array('header' => "User-Agent:MyAgent/1.0\r\n", 'method' => 'POST', 'content' => http_build_query($data),),);
            $context = stream_context_create($options);
            $html = file_get_html($url, false, $context);
            $html = str_replace('{"id":', '', $html);
            $html = str_replace('}', '', $html);
            redirect(base_url() . 'Facebook/fbid?id=' . $html);
        }
        $this->load->view('facebook/fbid', $data);
    }

    /*create facebook account*/
    public function create()
    {
        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');
        $data['title'] = 'Create new account';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('add', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();  

        /*update */
        if(!empty($_GET['password'])) {
            @$this->mod_general->update('faecbook', array('f_status'=>1, 'user_id'=>$log_id),array('f_phone'=>$_GET['password']));
        }
        /*end update */
        /*add new*/
        if ($this->input->post('urlid')) {
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
            $this->mod_general->delete('faecbook', array('f_status'=>3,'user_id'=>$log_id));

            


            $lanuage = 'khmer';
            $fName = $this->getName($lanuage);
            $lName = $this->getName($lanuage);
            $year = $this->getName(null, 'year');
            $phone = $this->input->post('urlid');
            $dataCheck = $this->mod_general->select('faecbook','*',array('f_status'=>1,'f_phone'=>$phone,'user_id'=>$log_id));
            if(!empty($dataCheck)) {
                redirect(base_url().'Facebook/create?m=already');
            }
            $response = new stdClass ();
            $response->phone = $phone;
            $response->fName = $fName;
            $response->lName = $lName;
            $response->year = $year;
            $arrayData = array(
                'f_name'=>$fName,
                'f_lname'=>$lName,
                'f_phone'=>$phone,
                'f_pass'=>$phone,
                'user_id'=>$log_id,
                'f_date'=> '01-01-' . $year,
            );
            $this->mod_general->insert('faecbook', $arrayData);
            $data['dataAdd'] = $response;
        }
        //$data['results'] = $this->mod_general->select('faecbook','*',array('f_status'=>1,'user_id'=>$log_id));

        $fTable = 'faecbook';
        $this->load->library ( 'pagination' );
        $per_page = (! empty ( $_GET ['result'] )) ? $_GET ['result'] : 20;
        $config ['base_url'] = base_url () . 'Facebook/create';
        $count_blog = $this->mod_general->select ( $fTable, '*',array('f_status'=>1,'user_id'=>$log_id));
        $config ['total_rows'] = count ( $count_blog );
        $config ['per_page'] = $per_page;
        $config = $this->mod_general->paginations($config);
        $page = ($this->uri->segment ( 3 )) ? $this->uri->segment ( 3 ) : 0;
        
        $query_blog = $this->mod_general->select($fTable, '*', array('f_status'=>1,'user_id'=>$log_id), "id DESC", '', $config['per_page'], $page); 
        $config ["uri_segment"] = 3;
        $this->pagination->initialize ( $config );
        $data ["total_rows"] = count ( $count_blog );
        $data ["results"] = $query_blog;
        /* end get pagination */

        $this->load->view('facebook/create', $data);
    }
    function getProtectedValue($obj, $name) {
        $array = (array)$obj;
        $prefix = chr(0) . '*' . chr(0);
        return $array[$prefix . $name];
    }

    public function getName($type = 'khmer', $year = null)
    {
        if(empty($year)) {
            switch ($type) {
                case 'khmer':
                    $ran = array("Narinmouy", " Narinpee", "Sommouy", "Sepee", "Sombey", "Namboun", "Nirakbram", "Narithsom","Sovanntun", "Appov paitai", "Nongkik", "Sophann", "Buttda", "Kroutith", "Chitphakdee", "Noottee", "Phoovanneat", "Nakovong", "Anupharp","Sothee","Rady","Saray","Noun Srey","Vannary","Chamroeun","Socheat","Nun Bory","Reachasey","Sumry","Sunny","Reasmey","Bopha","Kunthea","Kunthy","Khunary","Sav suoy","thaivy","Sophek","Boprek","Rathivat","Sakda","Sakana","Loso","Piv","Kic","Nich","Hainad","Songprawati","Kris","Montri","Chennoi","Kantawong","Thapthim","Prempree","Anada","Leeyao","Chayond","Kaewmanee","Nuananong","Ponhpaiboon","Thawanya","Noppachorn","Kasin","Sudham","Chaiyo","Chaiprasit","Ubol Tanasugarn","Piya","Khuntilanont","Nat","Yoonim","Naruesorn","Pongsanam","Parin","Chirathivat","Chai Son","Suntornnitikul","Chuachai","Ratanarak","Chai Son","Mookjai","Nat Kawrungruang","Korrakoj","Sukbunsung","Chuachan","Kadesadayurat","Vanida","Tongproh","Wipa Puntasrima","Ratchanichon","Supasawat","Yaowaman","Ekaluck","Patsaporn","Puarborn","Kwang","Pichit","Toptim","Prasongsanti","Thong Thao","Suparat","Kimnai","Nitaya");
                    break;
                case 'thai':
                    $ran = array("Sovanntun", "Appov paitai", "Nongkik", "Sophann", "Buttda", "Kroutith", "Chitphakdee", "Noottee", "Phoovanneat", "Nakovong", "Anupharp");
                    break;
                default:
                    $ran = array();
                    break;
            }
        } else {
            $ran = array(1980,1981,1982,1983,1984,1985,1986,1987,1988,1989,1990,1991,1992,1993,1994,1995,1996);
        }
        return $ran[array_rand($ran, 1)];

    }
    public function gennum()
    {
        $log_id = $userId = $this->session->userdata('user_id');
        $this->mod_general->checkUser();
        $user = $this->session->userdata('email');
        $data['title'] = 'Phone number generator';
        if ($this->input->post('phone')) {
            for ($i=0; $i < $this->input->post('max'); $i++) { 
                $phone = $this->input->post('phone');
                $ccode = $this->input->post('ccode');
                if (substr($phone, 0, 1) == '0') {
                     $phone = substr($phone, 1);
                } 
                if($i<10) {
                    $nphone = substr($phone, 0, -1);
                } else if($i<100) {
                    $nphone = substr($phone, 0, -2);
                } else if($i<1000) {
                    $nphone = substr($phone, 0, -3);
                }
                $pass = '0' . $nphone . $i;
                $number = $ccode. $nphone . $i;
                /*add nunmber*/
                $dataCheck = $this->mod_general->select('faecbook','*',array('f_phone'=>$number,'user_id'=>$log_id));
                if(empty($dataCheck)) {
                    $data_insert = array(
                        'f_name' => $number,
                        'f_phone' => $number,
                        'f_pass' => $pass,
                        'f_lname' => $ccode,
                        'user_id' => $log_id,
                        'f_date' => 'getNum',
                        'f_status' => 9,
                    );
                    $csvData = $this->mod_general->insert('faecbook', $data_insert);
                }
                /*End add nunmber*/
            }
            redirect(base_url().'Facebook/checknum');
            exit();

        }
        $this->load->view('facebook/gennum', $data);
    }


    public function getfriendlist()
    {
        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');
        $data['title'] = 'Get friend list';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('list', base_url().$this->uri->segment(1).'/fblist');
        $this->breadcrumbs->add('add', base_url().$this->uri->segment(2));
        $data['breadcrumb'] = $this->breadcrumbs->output();
        $upload_path = './uploads/'.$log_id.'/';
        if ($this->input->post('submit')) {
            if($this->input->post('clean')) {
                $this->mod_general->delete('faecbook', array('f_status'=>9,'user_id'=>$log_id,'f_date'=>'getNum'));
            }
            if (!empty($_FILES['userfile'])) {
                $this->load->helper('path');
                $directory = FCPATH;
                $base_path = set_realpath($directory);
                
                if (!file_exists($upload_path)) {
                    mkdir($upload_path, 0700);
                }
                if ($_FILES['userfile'] AND ! $_FILES['userfile']['error']) {
                    $tmpName = $_FILES['userfile']['tmp_name']; 
                    $newName = $upload_path . $_FILES['userfile']['name'];
                    if(!is_uploaded_file($tmpName) || !move_uploaded_file($tmpName, $newName)) {
                        $data['error'] = array('error' => $this->upload->display_errors());
                    } else {
                        $data['addJsScript'] = array(
                'var success = generate(\'success\');',
                'setTimeout(function () {
            $.noty.setText(export successfully!\');
        }, 1000);',
                'setTimeout(function () {
                        $.noty.closeAll();
                    }, 4000);'
            );
                        redirect('Facebook/getfriendlist?file='.$_FILES['userfile']['name'], 'location');
                        exit();
                    }
                }
                
                
            }
        }
        if(!empty($_GET['file'])) {
            if (preg_match('/.html/', $_GET['file'])) {
                $json = $this->readJson(str_replace('.html', '', $_GET['file']));
            } else {
                $json = $this->readJson($_GET['file']);
            }
            $data['results'] = $json;
        }
        $this->load->view('facebook/getfriendlist', $data);
    }
    public function readJson($value='')
    {
        $log_id = $this->session->userdata('user_id');
        $upload_path = './uploads/'.$log_id.'/';
        $newName = $upload_path . $value;
        $json = file_get_contents($newName);
        $json = explode('[{',$json);
        $json = explode('}]',$json[1]);
        $json = '[{'.$json[0].'}]';
        return json_decode($json);
    }
    public function postaction()
    {
        $data['title'] = 'your action';
        $this->load->view('facebook/postaction', $data);
    }

    public function cp1252_to_utf8($string)
    {
        error_reporting(0);
        if (!preg_match("/[\200-\237]/", $string)
         && !preg_match("/[\241-\377]/", $string)
        ) {
            return $string;
        }

        // decode three byte unicode characters
        $string = preg_replace("/([\340-\357])([\200-\277])([\200-\277])/e",
            "'&#'.((ord('\\1')-224)*4096 + (ord('\\2')-128)*64 + (ord('\\3')-128)).';'",
            $string
        );

        // decode two byte unicode characters
        $string = preg_replace("/([\300-\337])([\200-\277])/e",
            "'&#'.((ord('\\1')-192)*64+(ord('\\2')-128)).';'",
            $string
        );
        return $string;
    }

    public function tgroups() {
        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');
        $data['title'] = 'Facebook';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('add', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();
        
        $this->load->view('facebook/tgroups', $data);
    }

    public function addgroup() {
        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');
        $sid = $this->session->userdata ( 'sid' );
        $data['title'] = 'Facebook';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('add', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();

        if(empty($this->session->userdata ( 'fb_user_id' ))) {
            redirect(base_url() . 'managecampaigns');
            exit();
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
            $('#checkAll').click(function () {
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
 $('#addNewList').mouseover(function () {
    $('#addlist').attr('checked', 'checked');
    a();
 });
  $('#onexistlist').mouseover(function () {
    $('#exlist').attr('checked', 'checked');
    a();
 });
    var a =  function(){
        if(!$('#addlist').is(':checked'))
        {
           $('#categorylist').slideUp();
        } else {
            $('#categorylist').slideDown();
        }
    };
 });
 "
        );

        $gData = array();
        if (!empty($_FILES['userfile']) && empty($this->input->post('getfile'))) {
            $this->load->helper('path');
            $directory = FCPATH;
            $base_path = set_realpath($directory);
            $config['upload_path'] = './uploads/'; 
            $config['allowed_types'] = 'htm|html';
            //$config['allowed_types'] = 'gif|jpg|png'; 
            $config['max_size'] = '100048';
            
            if ($_FILES['userfile'] AND ! $_FILES['userfile']['error']) {
                //$file_path = $base_path . '/uploads/groups/' . $_GET['file'];
                $tmpName = $_FILES['userfile']['tmp_name']; 
                $path = $_FILES['userfile']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $newName = $base_path . '/uploads/groups/' . date('dmY') . '.'.$ext;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $newName);
                $isUpload = TRUE;
                redirect(base_url() . 'facebook/addgroup?file=' . date('dmY') . '.'.$ext);
            } 
            // $this->load->library('upload', $config);
            
            // if ( ! $this->upload->do_upload())
            // {
            //     $data['error'] = array('error' => $this->upload->display_errors());
            //     //$this->load->view('addgroup', $data);
            // } else {
            //     $dataupload = $this->upload->data();
            //     $file_name = $dataupload['file_name'];
            //     redirect(base_url() . 'facebook/addgroup?file=' . $file_name);
            // }
        }

        /*get id group*/
        $getCatelist = $this->mod_general->select('group_category', '*');
        $socailNetwork = $checkFbId = $this->mod_general->select(Tbl_user::tblUser,'*');        
        if (!empty($_GET['file'])) {
            $ext = pathinfo($_GET['file'], PATHINFO_EXTENSION);
            ini_set('memory_limit', '-1');
            $this->load->helper('path');
            $directory = FCPATH;
            $base_path = set_realpath($directory);           
            $this->load->library('html_dom'); 
            $file_path = $base_path . 'uploads/groups/' . $_GET['file'];

            /*get from json file*/            
            if($ext == 'json') {
                $html = file_get_html($file_path);
                $json = json_decode($html);
                foreach ($json as $key => $gdata) {
                    $gData[] = array(
                        'title' => $gdata->gname,
                        'gid' => $gdata->gid,
                        'members' => null
                    );
                }
                /*get from json file*/
            } else {
                /*get from HTML file*/                
                $html = file_get_html($file_path);   
                if(preg_match('/pagelet_timeline_medley_groups/', $html)) {
                    /*get only public group*/
                    foreach ($html->find('#pagelet_timeline_medley_groups ul li .mtm') as $e) {
                        $getId = @$e->find('a', 0)->{'data-hovercard'};
                        if(!empty($getId)) {
                            $getTitle = $e->find('a', 0)->innertext;
                            $interMember = @$e->find('div', 1)->innertext;
                                $getMemeber = str_replace(' members', '', $e->find('div', 1)->innertext);
                            parse_str( parse_url( $getId, PHP_URL_QUERY ), $my_array_of_vars );
                            $gData[] = array(
                                'title' => $getTitle,
                                'gid' => $my_array_of_vars['id'],
                                'members' => $getMemeber
                            );
                        } else {
                            continue;
                        }
                    }                    
                } else if(preg_match('/XHTML Mobile 1.0/', $html)) {
                    foreach ($html->find('#objects_container table tr') as $e) {
                        $getT = $e->find('td', 1);
                        $getTitle = $getT->find('a', 0)->innertext;
                        $getId = $getT->find('a', 0)->href;
                        parse_str( parse_url( $getId, PHP_URL_QUERY ), $my_array_of_vars );
                        if(!empty($getId)) {
                            $gData[] = array(
                                'title' => $getTitle,
                                'gid' => $my_array_of_vars['group_id'],
                                'members' => ''
                            );
                        } else {
                            continue;
                        }
                    }                    
                } else {
                    /*get all groups*/
                    foreach ($html->find('#bookmarksSeeAllEntSection ul li a[data-testid=*]') as $e) {
                        $tAr = $e->{'data-gt'};
                        $tAr = str_replace('&quot;bmid&quot;:&quot;', '<li>', $tAr);
                        $tAr = str_replace('&quot;,&quot;count&quot;', '</li>', $tAr);
$str = <<<HTML
{$tAr}
HTML;
                        $htmls = str_get_html($str);
                        $gData[] = array(
                            'title' => $e->title,
                            'gid' => $htmls->find('li', 0)->innertext,
                            'members' => null
                        );
                    }
                }
                /*End get from HTML file*/ 
                //@unlink($base_path . 'uploads/groups/' . $_GET['file']);                
            } 
                     
        }
        $upload_path = './uploads/groups/';
        $file_name = 'group_list_'.$this->session->userdata('fb_user_id').'.json';
        $filesJson = '';
        if (file_exists($upload_path.$file_name)) {
            $filesJson = base_url().'uploads/groups/'.$file_name;
        }

        /*get group list */
        $getGList = $this->mod_general->select('group_list','*',array('l_user_id'=>$log_id,'l_sid'=>$sid));
        /*ednd get group list */ 
        $data['filesJson'] = $filesJson;
        $data['dataGroupList'] = $getGList;
        $data['getCatelist'] = $getCatelist;
        $data['socailNetwork'] = $socailNetwork;

        /*add data list to DB*/
        if ($this->input->post('itemid')) {
            $id = $this->input->post('itemid');   
            $itemidall = $this->input->post('itemidall');                    
                $this->load->library('form_validation');
                $this->form_validation->set_rules('Typelist', 'Typelist', 'required');                
                $existGroups = array();
                $addGroups = array();
                $arrGroups = array();
                if ($this->form_validation->run() == TRUE) {
                    /*save all ID groups to CSV file*/                    
                    if(!empty($itemidall)) {
                        $this->load->helper('path');
                        $directory = FCPATH;
                        $base_path = set_realpath($directory);
                        $file_path = $base_path . '/uploads/groups/';
                        $file_name = 'group_list_'.$this->session->userdata('fb_user_id').'.json';
                        $list = array();
                        foreach ($itemidall as $key => $gall) {
                            $ArrData = explode('||', $gall);
                            $gID = $ArrData[0];
                            $gTitle = $ArrData[1];
                            $list[] = array(
                                'gid' => $gID,
                                'gname' => $gTitle,
                                'members' => 0
                            );
                        }
                        $f = fopen($file_path.$file_name, 'w');
                        $fwrite = fwrite($f, json_encode($list));
                        fclose($f);
                    }
                    /*End save all ID groups to CSV file*/
                    if(!empty($id)) {
                        if ($this->input->post('Typelist') =='add') {
                            /*add to group list*/
                            $data_groupList = array(
                                'lname' => $this->input->post('addlist'),
                                'l_user_id' => $log_id,
                                'l_category' => (int) $this->input->post('categorylist'),
                                'l_count' => count($id),
                                'l_sid' => $sid,
                            );
                            $GroupListID = $this->mod_general->insert('group_list', $data_groupList);
                            /*end add to group list*/
                        } else {
                            /*update to group list*/
                            $checkGList = $this->mod_general->select('group_list','*',array('l_id'=>$this->input->post('onexistlist'),'l_sid' => $sid));
                            if(!empty($checkGList[0])) {
                                $GroupListID = $checkGList[0]->l_id;
                                $update = $GroupListID;
                                $getCountGroup = $checkGList[0]->l_count;
                                /*get count from user group*/
                            }
                            /*end update to group list*/
                        }                        
                        foreach ($id as $key => $gvalue) {
                            /*add to group*/
                            $ArrayData = explode('||', $gvalue);
                            $groupID = $ArrayData[0];
                            $groupTitle = $ArrayData[1];
                            $groupMember = $ArrayData[2];
                            $checkID = $this->mod_general->select('socail_network_group','*',array('sg_page_id'=>$groupID,'s_id' => $sid));
                            if(!empty($checkID[0])) {
                                $fgId = $checkID[0]->sg_id;
                                array_push($existGroups, $fgId);
                            } else {
                                @iconv_set_encoding("internal_encoding", "TIS-620");
                                @iconv_set_encoding("output_encoding", "UTF-8");
                                @ob_start("ob_iconv_handler");
                                $data_group = array(
                                    'sg_name' => $this->mod_general->remove_emoji($groupTitle),
                                    'sg_page_id' => $groupID,
                                    'sg_member' => $groupMember,
                                    's_id' => $sid,
                                    'sg_type' => 'groups',
                                    'sg_status' => 1,
                                );
                                @ob_end_flush();
                                $fgId = $this->mod_general->insert('socail_network_group', $data_group);
                                array_push($addGroups, $fgId);
                            }


                            /*add to user group*/
                            $checkUserGroup = $this->mod_general->select('group_user','*',array('gu_idgroups'=>$fgId,'gu_user_id' => $log_id,'gu_grouplist_id' => $GroupListID));
                            if(empty($checkUserGroup)) {
                                $data_user_group = array(
                                    'gu_idgroups' => $fgId,
                                    'gu_user_id' => $log_id,
                                    'gu_grouplist_id' => $GroupListID,
                                    'sid' => $sid,
                                );
                                $userGroupID = $this->mod_general->insert('group_user', $data_user_group);
                            }
                            /*End add to user group*/
                            array_push($arrGroups, intval($fgId));
                            /*end add to group*/
                        }
                        
                        if(!empty($update)) {
                            $totalGroup = $getCountGroup + count($addGroups);
                            $data_groupList = array(
                                'l_count' => $totalGroup,
                            );
                            $whereGroupList = array('l_id'=>$update);
                            $dataUpdateGList = $this->mod_general->update('group_list', $data_groupList, $whereGroupList);
                        }
                    }
                }
        
            redirect(base_url() . 'Facebook/group?all=' . count($arrGroups) . '&added=' . count($addGroups) . '&ex=' . count($existGroups));
        /*end add data list to DB*/
        }
        $data['gList'] = $gData;
        /*END get id group*/

        
        $this->load->view('facebook/addgroup', $data);
    }
    public function group() {
        $fb_id = $this->session->userdata ( 'fb_user_id' );
        $log_id = $this->session->userdata('user_id');
        $sid = $this->session->userdata ( 'sid' );
        $user = $this->session->userdata('email');
        $data['title'] = 'Facebook group';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('add', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();

                $data ['addJsScript'] = array (
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

        if(empty($this->session->userdata ( 'fb_user_id' ))) {
            redirect(base_url() . 'managecampaigns');
            exit();
        }

        if(!empty($this->input->get('cat'))) {
            if($this->input->get('cat') == 'none') {
                $this->session->unset_userdata('cat');
            } else {
                $this->session->set_userdata('cat', $this->input->get('cat'));
            }
        }


        $data['grouplist'] = $this->mod_general->select('group_list','*',array('l_user_id'=>$log_id,'l_sid'=>$sid));

        /*Delete*/
        if ($this->input->post('delete')) {
            if(!empty($this->input->post('itemid'))) {
                $gid = $this->input->post('itemid');
                foreach ($gid as $key => $value) {
                    $this->Mod_general->delete('group_user', array('gu_idgroups'=>$value, 'gu_user_id'=>$fb_id));
                    $this->Mod_general->delete('socail_network_group', array('sg_id'=>$value, 's_id'=>$sid));
                }
            }
        }
        /*End Delete*/

        $tableName = 'socail_network_group';
        if(!empty($this->session->userdata ( 'cat' ))) {
            $grUser = $this->mod_general->select('group_user', '*', array('gu_grouplist_id'=>$this->session->userdata ( 'cat' )));
            $whG = array();
            if(!empty($grUser)) {
                foreach ($grUser as $gvalue) {
                    $whG[] = $gvalue->gu_idgroups; 
                }
            }
            if(!empty($whG)) {
                $whereList['where_in'] = array('sg_id'=> $whG);
            } else {
                $this->session->unset_userdata('cat');
                redirect(base_url() . 'Facebook/group');
            }
            
            //$whereList = array('sg_id in '=> '('.implode(',', $whG).')');
        } else {
            $whereList = array('s_id'=>$sid);
        }

        
        $this->load->library('pagination');
        $per_page = (!empty($_GET['result'])) ? $_GET['result'] : 50;
        $config['base_url'] = base_url() . 'Facebook/group/';
        $count = $this->mod_general->select($tableName, '*', $whereList);
        $config['total_rows'] = count($count);
        $config['per_page'] = $per_page;
        $config = $this->mod_general->paginations($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $queryList = $this->mod_general->select($tableName, '*', $whereList, "sg_id DESC", '', $config['per_page'], $page);
        $data["total_rows"] = count($count);
        $data["links"] = $this->pagination->create_links();
        $data['list'] = $queryList;
        $this->load->view('facebook/group', $data);
    }

    public function trgroups()
    {
        $fb_id = $this->session->userdata ( 'fb_user_id' );
        $log_id = $this->session->userdata('user_id');
        $sid = $this->session->userdata ( 'sid' );
        $user = $this->session->userdata('email');
        $data['title'] = 'Facebook group Tranfer';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('add', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();

        $this->load->view('facebook/trgroups', $data);
    }

    public function overview() {
        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');

        $groupListId = $this->uri->segment(3);
        if(empty($groupListId)) {
            redirect('Facebook/group', 'location');
            die;
        }
        $tableName = 'group_list';
        $groupData = $this->mod_general->select($tableName, '*', array('l_id'=>$groupListId,'l_user_id'=>$log_id));

        $data['title'] = $groupData[0]->lname . ' - Group list';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1).'/group'); 
        }
        $this->breadcrumbs->add('Overview', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();



        
        $whereList = array('l_user_id'=>$log_id);
        $this->load->library('pagination');
        $per_page = (!empty($_GET['result'])) ? $_GET['result'] : 10;
        $config['base_url'] = base_url() . 'Facebook/group/';
        $count = $this->mod_general->select($tableName, '*', $whereList);
        $config['total_rows'] = count($count);
        $config['per_page'] = $per_page;
        $config = $this->mod_general->paginations($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $queryList = $this->mod_general->select($tableName, '*', $whereList, "l_id DESC", '', $config['per_page'], $page);
        $data["total_rows"] = count($count);
        $data["links"] = $this->pagination->create_links();
        $data['list'] = $queryList;
        $this->load->view('facebook/overview', $data);
    }

    public function csv($id)
    {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        //$query = $this->mod_general->select('group_user', '*');
        $this->load->database('default', true);
        $query = $this->db->query("SELECT g.`gfg_id` FROM `group_user` AS gl
INNER JOIN `group` AS g
ON gl.`gu_idgroups` = g.`gid`
WHERE gl.`gu_grouplist_id` = {$id}");
        $delimiter = '';
        $newline = "\r\n";
        $enclosure ='';
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline,$enclosure);
        force_download('CSV_group_id_('.$id.').csv', $data);
    }

    public function delete() {
        $log_id = $this->session->userdata('user_id');
        $actions = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        $sid = $this->session->userdata ( 'sid' );
        switch ($actions) {
            case "grouplist":
                $table = 'group_list';
                $group_list = $this->mod_general->select('group_user', '*', array('gu_grouplist_id'=>$id,'gu_user_id'=>$log_id));
                if(!empty($group_list)) {
                    foreach ($group_list as $valueGroup) {
                        $groupUserList = $this->mod_general->select('group_user', '*', array('gu_idgroups'=>$valueGroup->gu_idgroups));
                        if(count($groupUserList)==1) {
                            $this->mod_general->delete('group', array('gid'=>$groupUserList[0]->gu_idgroups));
                        }
                        $this->mod_general->delete('group_user', array('gu_id'=>$valueGroup->gu_id));
                    }
                    $this->mod_general->delete($table, array('l_id'=>$id));
                }
                //redirect('Facebook/group', 'location');
                break;
            case 'groupid':
                if(!empty($this->input->get('cat'))) {
                    $this->mod_general->delete('group_user', array('gu_grouplist_id'=>$this->input->get('cat'),'gu_user_id'=>$log_id,'gu_idgroups'=>$id));
                    redirect('Facebook/group', 'location');
                } else {
                    $this->mod_general->delete('socail_network_group', array('s_id'=>$sid,'sg_id'=>$id));
                    redirect('Facebook/group', 'location');
                }
                //$this->mod_general->delete('socail_network_group', array('s_id'=>$sid,'sg_id'=>$id));
                
                break;
        }
    }
    public function removegroup() {
        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');
        $data['title'] = 'Remove facebook groups by ID';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('add', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();
        
        if ($this->input->post('groupID')) {
           $groupID = $this->input->post('groupID'); 
           $prevent_readd = $this->input->post('prevent_readd'); 
           $arrID = explode(',', $groupID);
           if(!empty($arrID)) {
                $data['group'] = $arrID;
                $data['prevent_readd'] = $prevent_readd;
           }
        }
        $this->load->view('facebook/removegroup', $data);
    }

    public function fbjson()
    {
        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');
        $data['title'] = 'check Facebook status';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('list', base_url().$this->uri->segment(1).'/fblist');
        $this->breadcrumbs->add('add', base_url().$this->uri->segment(2));
        $data['breadcrumb'] = $this->breadcrumbs->output();

        if ($this->input->post('submit')) {
            if($this->input->post('clean')) {
                $this->mod_general->delete('faecbook', array('f_status'=>9,'user_id'=>$log_id,'f_date'=>'getNum'));
            }
            if(!empty($this->input->post('userfile'))) {
                $jsonTxt = $this->input->post('userfile');
                $obj = json_decode($jsonTxt);
                foreach ($obj as $item) {
                    $data_insert = array(
                        'f_name' => $item->name,
                        'f_phone' => trim(str_replace(' ', '', $item->phone)),
                        'f_pass' => $item->profile_link,
                        'f_lname' => $this->input->post('friend'),
                        'user_id' => $log_id,
                        'f_date' => 'getNum',
                        'f_status' => 9,
                    );
                    $csvData = $this->mod_general->insert('faecbook', $data_insert);
                }
                redirect('Facebook/checknum', 'location');
                exit();
            }
        }

        $this->load->view('facebook/fbjson', $data);
    }
    /*get cehck facebook Exist*/
    public function fbstatus() {
        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');
        $data['title'] = 'check Facebook status';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('list', base_url().$this->uri->segment(1).'/fblist');
        $this->breadcrumbs->add('add', base_url().$this->uri->segment(2));
        $data['breadcrumb'] = $this->breadcrumbs->output();
        if ($this->input->post('submit')) {
            if($this->input->post('clean')) {
                $this->mod_general->delete('faecbook', array('f_status'=>9,'user_id'=>$log_id,'f_date'=>'getNum'));
            }
            $count=0;
            $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
            while($csv_line = fgetcsv($fp,1024))
            {
                $count++;
                if($count == 1 || $count == 2)
                {
                    continue;
                }//keep this if condition if you want to remove the first row
                for($i = 0, $j = count($csv_line); $i < $j; $i++)
                {
                    $insert_csv = array();
                    $insert_csv['number'] = trim(str_replace(' ', '', $csv_line[0]));//remove if you want to have primary key,
                    $insert_csv['id'] = trim($csv_line[1]);
                    $insert_csv['user'] = trim($csv_line[2]);
                    $insert_csv['url'] = trim($csv_line[3]);

                    /*add to DB*/
                    if($i==3) {
                        $checkNum = $this->mod_general->select('faecbook', '*', array('f_phone'=>$insert_csv['number'],'user_id' => $log_id));
                        if(empty($checkNum[0])) {
                            $data_insert = array(
                                'f_name' => $insert_csv['user'],
                                'f_phone' => $insert_csv['number'],
                                'f_pass' => $insert_csv['id'],
                                'f_lname' => $this->input->post('friend'),
                                'user_id' => $log_id,
                                'f_date' => 'getNum',
                                'f_status' => 9,
                            );
                            $csvData = $this->mod_general->insert('faecbook', $data_insert);
                        } else {
                            $data_update = array(
                                'f_name' => $insert_csv['user'],
                                'f_phone' => $insert_csv['number'],
                                'f_pass' => $insert_csv['id'],
                                'f_lname' => $this->input->post('friend')
                            );
                            $csvData = $this->mod_general->update('faecbook', $data_update, array('id'=>$checkNum[0]->id,'user_id' => $log_id));
                        }
                    }
                    /*end add to DB*/

                }
                $i++;
                //var_dump($insert_csv);
                
            }
            fclose($fp) or die("can't close file");
            redirect('Facebook/checknum', 'location');
            exit();
        }

        $this->load->view('facebook/fbstatus', $data);
    }

    public function checknum()
    {

        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');

        $data['title'] = 'Facebook list not check';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('fb list', base_url().$this->uri->segment(1).'/fblist');
        $this->breadcrumbs->add('Uncheck', base_url().$this->uri->segment(2));
        $data['breadcrumb'] = $this->breadcrumbs->output();

        $checkNum = $this->mod_general->select('faecbook', '*', array('f_date'=>'getNum','user_id' => $log_id,'f_status' => 9),$order = 0, $group = 0, $limit = 10);
        if(!empty($checkNum)) {
            $data['result'] = $checkNum;
            if(!empty($_GET['next'])){
                $data['bodyLoad'] = 'runCode();';
            }
            //
        }

        $checkCount = $this->mod_general->select('faecbook', '*', array('f_date'=>'getNum','user_id' => $log_id,'f_status' => 9));
        if(!empty($checkCount)) {
            $data['count'] = $checkCount;
        }

        if(!empty($_GET['status'])) {
            if($_GET['status'] == 'no') {
                $message = 'var success = generate(\'error\');';
            } else {
                $message = 'var success = generate(\'success\');';
            }
            $data['addJsScript'] = array(
                    $message
            );
        }
        
        $this->load->view('facebook/checknum', $data);
    }

    public function publicphone()
    {

        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');

        $data['title'] = 'Facebook list not check';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('fb list', base_url().$this->uri->segment(1).'/fblist');
        $this->breadcrumbs->add('Uncheck', base_url().$this->uri->segment(2));
        $data['breadcrumb'] = $this->breadcrumbs->output();

        $checkNum = $this->mod_general->select('faecbook', '*', array('f_date'=>'getNum','user_id' => $log_id,'f_status' => 9),$order = 0, $group = 0, $limit = 10);
        if(!empty($checkNum)) {
            $data['result'] = $checkNum;
            if(!empty($_GET['next'])){
                $data['bodyLoad'] = 'runCode();';
            }
            //
        }

        $checkCount = $this->mod_general->select('faecbook', '*', array('f_date'=>'getNum','user_id' => $log_id,'f_status' => 9));
        if(!empty($checkCount)) {
            $data['count'] = $checkCount;
        }

        if(!empty($_GET['status'])) {
            if($_GET['status'] == 'no') {
                $message = 'var success = generate(\'error\');';
            } else {
                $message = 'var success = generate(\'success\');';
            }
            $data['addJsScript'] = array(
                    $message
            );
        }
        
        $this->load->view('facebook/publicphone', $data);
    }

    public function fblist()
    {

        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');

        $data['title'] = 'Facebook List';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('fb list', base_url().$this->uri->segment(1).'/fblist');
        $data['breadcrumb'] = $this->breadcrumbs->output();

        // $checkNumCount = $this->mod_general->select('faecbook', '*', array('f_date'=>'getNum','user_id' => $log_id,'f_status' => 8));
        // if(!empty($checkNumCount)) {
        //     $data['result'] = $checkNumCount;
        // }


        $fTable = 'faecbook';
        $this->load->library ( 'pagination' );
        $per_page = (! empty ( $_GET ['result'] )) ? $_GET ['result'] : 20;
        $config ['base_url'] = base_url () . 'Facebook/fblist';
        $count_blog = $this->mod_general->select ( $fTable, '*',array('f_date'=>'getNum','user_id' => $log_id, 'f_status = 8 OR f_status ='=>4));
        $config ['total_rows'] = count ( $count_blog );
        $config ['per_page'] = $per_page;
        $config = $this->mod_general->paginations($config);
        $page = ($this->uri->segment ( 3 )) ? $this->uri->segment ( 3 ) : 0;
        
        $query_blog = $this->mod_general->select($fTable, '*', array('f_date'=>'getNum','user_id' => $log_id,'f_status = 8 OR f_status ='=>4), "id DESC", '', $config['per_page'], $page); 
        $config ["uri_segment"] = 3;
        $this->pagination->initialize ( $config );
        $data ["total_rows"] = count ( $count_blog );
        $data ["result"] = $query_blog;
        /* end get pagination */


        if(!empty($_GET['status'])) {
            if($_GET['status'] == 'no') {
                $message = 'var success = generate(\'error\');';
            } else {
                $message = 'var success = generate(\'success\');';
            }
            $data['addJsScript'] = array(
                    $message
            );
        }

        /*Delete*/
        if(!empty($this->input->get('del'))) {
            
        }
        /*End Delete*/

        /*Status*/
        $this->load->helper('url');
        if(!empty($this->input->get('id'))) {
            switch ($this->input->get('type')) {
                case 'del':
                    $this->Mod_general->delete ( $fTable, 
                        array (
                            'id' => $this->input->get('id'),
                            'user_id' => $log_id,
                        )
                    );
                    redirect(base_url(uri_string()) . '?m=del_success');
                    break;
                case 'status':
                    $dataSatus = array(
                        'f_status'      => $this->input->get('status')
                    );
                    $whereSatus = array(
                        'user_id'     => $log_id,
                        'id' => $this->input->get('id'),
                    );
                    $this->Mod_general->update($fTable, $dataSatus,$whereSatus);
                    redirect(base_url(uri_string()) . '?m=updated');
                break;
                default:
                    # code...
                    break;
            }
        }
        /*End Status*/
        
        $this->load->view('facebook/fblist', $data);
    }

    public function fbnumset()
    {
        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');
        if(!empty($_GET['status'])) {
            if($_GET['status'] == 'no') {
                $action = $this->mod_general->delete('faecbook', array('id'=>$_GET['id'],'user_id' => $log_id));
            } else if($_GET['status'] == 1) {
                $dataPostInstert = array('f_status' => 8);
                $action = $this->mod_general->update('faecbook', $dataPostInstert, array('id'=>$_GET['id'],'user_id' => $log_id));
            }
            $tatal = $_GET['total'] + 1;
            redirect('Facebook/checknum?next=1&total='.$tatal.'&', 'location');
            exit();
        }
        die;
    }

    public function addnumberphone()
    {
        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');
        if ($this->input->post('submit')) {
            if($this->input->post('clean')) {
                $this->mod_general->delete('faecbook', array('f_status'=>9,'user_id'=>$log_id,'f_date'=>'getNum'));
            }
        }
        if(!empty($_GET['num'])) {
            $phone = trim(str_replace(' ', '', $_GET['num']));
            $dataCheck = $this->mod_general->select('faecbook','*',array('f_phone'=>$phone,'user_id'=>$log_id));
                if(!empty($dataCheck)) {
                    $data_insert = array(
                        'f_name' => @$_GET['name'],
                        'f_phone' => $phone,
                        'f_pass' => str_replace(' ', '', $_GET['u']),
                        'f_lname' => $_GET['date'],
                        'user_id' => $log_id,
                        'f_date' => 'getNum',
                        'f_status' => 9,
                    );
                    $csvData = $this->mod_general->insert('faecbook', $data_insert);
                }
            //$tatal = $_GET['total'] + 1;
            //redirect('Facebook/publicphone?next=1&total='.$tatal.'&', 'location');
            exit();
        }
        die;
    }
    public function uploadMedia($file_path='')
       {
        $imgName = $file_path;
        $client_id = '51d22a7e4b628e4';

        $filetype = mime_content_type($file_path);
        /*resize image*/
        $maxDim = 1200;
        $file_name = $imgName;
        list($width, $height, $type, $attr) = getimagesize( $file_name );
        if ( $width < $maxDim || $height < $maxDim ) {
            $target_filename = $file_name;
            $ratio = $width/$height;
            if( $ratio > 1) {
                $new_width = $maxDim;
                $new_height = $maxDim/$ratio;
            } else {
                $new_width = $maxDim*$ratio;
                $new_height = $maxDim;
            }

            $src = imagecreatefromstring( file_get_contents( $file_name ) );
            $dst = imagecreatetruecolor( $new_width, 680 );
            imagecopyresampled( $dst, $src, 0, 0, 0, 100, $new_width, $new_height, $width, $height );
            imagedestroy( $src );
            imagejpeg( $dst, $target_filename ); // adjust format as needed
            imagedestroy( $dst );
        }
        /*end resize image*/
        /*upload to imgur.com*/
        $image = file_get_contents($imgName);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( "Authorization: Client-ID $client_id" ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, array( 'image' => base64_encode($image) ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($ch);
        curl_close($ch);
        $reply = json_decode($reply);
        if(!empty($reply->data->link)) {
            return $reply->data->link;
        } else {
            return false;
        }
        /*End upload*/
    }

    function do_upload($config) {
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());
            return $error;
        } else {
            $data = array('upload_data' => $this->upload->data());
            return $data;
        }
    }

    public function requestgroups()
    {
        $log_id = $this->session->userdata('user_id');
        $data['title'] = 'Request group by ID';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('add', base_url().$this->uri->segment(1));
        $data['breadcrumb'] = $this->breadcrumbs->output();

        if(!empty($this->input->post('gids'))) {
            $data['gids'] = explode(',', $this->input->post('gids'));
        }  

        $this->load->view('facebook/requestgroups', $data);

    }
    public function randomLink($link='',$image='')
    {
        /*upload photo first*/
        $imgur = false;
        if(!empty($image)) {
            $file_title = basename( $image);
            $fileName = FCPATH . 'uploads/image/'.$file_title;
            copy($image, $fileName);
            $image = $this->uploadMedia($fileName);
            @unlink($fileName);
            $imgur = true;
        }
        /*End upload photo first*/

        $log_id = $this->session->userdata('user_id');
        /*show blog link*/
        $where_link = array(
            'c_name'      => 'blog_link',
            'c_key'     => $log_id,
        );
        $data['bloglink'] = false;
        $query_blog_link = $this->Mod_general->select('au_config', '*', $where_link);
        if (!empty($query_blog_link[0])) {
            $data = json_decode($query_blog_link[0]->c_value);
            $big = array();
            foreach ($data as $key => $blog) {
                $big[] = $blog->bid;
            }
            $blogRand = $big[mt_rand(0, count($big) - 1)];

            //http://thl01lot.blogspot.com/search/label/rSGqB2v.jpg?id=2QgCZZ3&1=1
            if(preg_match("/bit.ly/", $link) && !empty($imgur)) {
                $bitlyId = str_replace('http://bit.ly/', '', $link);
                $str = time();
                $str = md5($str);
                $uniq_id = substr($str, 0, 9);
                $link = $blogRand . 'search/label/' . basename($image) . '?id=' . $bitlyId . '&l=' . $uniq_id;
            }
            
        }
        return $link;
        /*End show blog link*/
    }
    public function shareation($value=1)
    {
        /*nerver expire*/
        if(!empty($this->input->get('uid'))) {
            $this->session->set_userdata('user_id', $this->input->get('uid'));
        }
        if(!empty($this->input->get('suid'))) {
            $this->session->set_userdata('sid', $this->input->get('suid'));
        }

        $log_id = !empty($this->input->get('uid')) ? $this->input->get('uid') : $this->session->userdata('user_id');
        $user = $this->session->userdata('email');
        $sid = !empty($this->input->get('suid')) ? $this->input->get('suid') : $this->session->userdata ( 'sid' );
        $pid = $this->input->get( 'id' );

        $user = $this->session->userdata('email');        
        $action = $this->input->get('post');
        $wait = $this->input->get('wait');

        $randomLink = $this->session->userdata ( 'randomLink' );
        $shareId = $this->input->get('shareid');

        if(empty($sid)) {
            redirect(base_url() . 'managecampaigns?back='.urlencode(base_url() . 'Facebook/share?post=nexpost'));
        }

        $UserAgent = @$this->input->get('agent');
        $this->session->set_userdata('randomLink', $randomLink);
        /*End nerver expire*/
        $this->session->set_userdata('blogpassword', 1);
        /*if random Link*/
        if(empty($randomLink)) {
            $whereRan = array(
                'c_name'      => 'randomLink',
                'c_key'     => $log_id,
                'c_value'     => 1,
            );
            $query_ran = $this->Mod_general->select('au_config', '*', $whereRan);
            /* check before insert */
            if (!empty($query_ran[0])) {
                $this->session->set_userdata('randomLink', 1);
            } else {
                $this->session->set_userdata('randomLink', 2);
            } 
        }
        /*End if random Link*/
        switch ($action) {
            case 'getpost':
                $sid = $this->session->userdata ( 'sid' );
                $fbUserId = $this->session->userdata('fb_user_id');
                $licence = $this->session->userdata('licence');

                /*get Post to post*/
                $pid = @$this->input->get('pid');
                $date = new DateTime("now");
                $curr_date = $date->format('Y-m-d h:i:s');
                $sid = $this->session->userdata ( 'sid' );


                if(!empty($pid)) {
                    $where_Pshare = array (
                        'p_id' => $pid,
                        'u_id' => $sid,
                        'p_status' => 1,
                    );
                } else {
                    $where_Pshare = array (
                        'u_id' => $sid,
                        'p_status' => 1,
                    );
                    // $whereShare = array (
                    //     'uid' => $log_id,
                    //     'sh_status' => 0,
                    //     'sh_type' => 'imacros',
                    //     'social_id'=> $sid
                    // );
                }
                $PID = $pid = $topost = '';
                $pData = array();
                $dataPost = $this->Mod_general->select ('post','*', $where_Pshare);
                // if(!empty($dataPost[0])) {
                //     foreach ($dataPost as $ppost) {
                //         if($ppost->p_post_to == 0) {
                //             $PID = $pid = $ppost->p_id;
                //             $pData = $ppost;
                //             break;
                //         }
                //         if($ppost->p_post_to != 0) {
                //             $topost = $ppost->p_id;
                //             $pData = $ppost;
                //             break;
                //         }
                //     }
                // }
                if(!empty($wait)) {
                    $value = 'nexpost';
                }
                if(!empty($dataPost[0])) {
                    $PID = $pid = $dataPost[0]->p_id;
                    $pConent = json_decode($dataPost[0]->p_conent);
                    $pOption = json_decode($dataPost[0]->p_schedule);
                    if($pOption->short_link==1 && !(preg_match('/bit.ly/', $pConent->link) || preg_match('/tiny.cc/', $pConent->link)) && !preg_match('/youtu/', $pConent->link)  && $dataPost[0]->p_post_to ==0){
                        //http://localhost/autopost/managecampaigns/autopost?bitly=1&pid=2
                        if(empty($this->input->get('noshort'))) {
                            redirect(base_url() . 'managecampaigns/autopost?bitly='.urlencode($pConent->link).'&pid='.$pid);
                        }
                    }
                    $whereShare = array (
                        'uid' => $log_id,
                        'sh_status' => 0,
                        'sh_type' => 'imacros',
                        'p_id' => $PID,
                        'social_id'=> $sid
                    );
                    $dataShare = $this->Mod_general->select (
                        'share',
                        '*', 
                        $whereShare,
                        $order = 'c_date', 
                        $group = 0, 
                        $limit = 1 
                    );

                    $tmp_path = './uploads/'.$log_id.'/'. $fbUserId . '_tmp_action.json';
                    if (file_exists($tmp_path)) {
                        $string = file_get_contents($tmp_path);
                        $json_a = json_decode($string);
                    }
                    
                    $userAction = $this->Mod_general->userrole('uid');
                    if($userAction) {
                        /*get group*/
                        $wGList = array (
                            'lname' => 'post_progress',
                            'l_user_id' => $log_id,
                            'l_sid' => $sid,
                        );
                        $geGList = $this->Mod_general->select ( 'group_list', '*', $wGList );
                        if(!empty($geGList[0])) {
                            $wGroupType = array (
                                'gu_grouplist_id' => $geGList[0]->l_id,
                                'gu_user_id' => $log_id,
                                'gu_status' => 1
                            ); 
                            $sh_type = 'imacros'; 
                            $ShContent = array();
                        }
                    }

                    if(empty($dataShare[0])) {
                        /*if empty groups*/
                        if(empty($wGroupType)) {
                            $fbUserId = $this->session->userdata('fb_user_id');
                            $tmp_path = './uploads/'.$log_id.'/'. $fbUserId . '_tmp_action.json';
                            $string = file_get_contents($tmp_path);
                            $json_a = json_decode($string);
                            $sh_type = $json_a->ptype;
                            /*get group*/
                           $wGroupType = array (
                                    'gu_grouplist_id' => $json_a->account_group_type,
                                    'gu_user_id' => $log_id,
                                    'gu_status' => 1
                            );
                        }
                        $tablejoin = array('socail_network_group'=>'socail_network_group.sg_id=group_user.gu_idgroups');
                        $itemGroups = $this->Mod_general->join('group_user', $tablejoin, $fields = '*', $wGroupType);
                       /*End get group*/
                       /* add data to group of post */
                        if(!empty($itemGroups)) {
                            echo '<meta http-equiv="refresh" content="5"/>';
                            if(@$json_a->share_schedule == 1) {
                                $date = DateTime::createFromFormat('m-d-Y H:i:s',$startDate . ' ' . $startTime);
                                $cPost = $date->format('Y-m-d H:i:s');
                            } else {
                                $cPost = date('Y-m-d H:i:s');
                            }
                            $ShContent = array (
                                'userAgent' => @$json_a->userAgent,                            
                            );                    
                            foreach($itemGroups as $key => $groups) { 
                                if(!empty($groups)) {      
                                    $dataGoupInstert = array(
                                        'p_id' => $PID,
                                        'sg_page_id' => $groups->sg_id,
                                        'social_id' => @$sid,
                                        'sh_social_type' => 'Facebook',
                                        'sh_type' => @$sh_type,
                                        'c_date' => $cPost,
                                        'uid' => $log_id,                                    
                                        'sh_option' => @json_encode($ShContent),                                    
                                    );
                                    $AddToGroup = $this->Mod_general->insert(Tbl_share::TblName, $dataGoupInstert);
                                }
                            } 
                        }
                        /* end add data to group of post */
                        /*End if empty groups*/
                    }
                    if(!empty($dataShare[0])) {
                        $pid = $dataShare[0]->p_id;
                        $postId = $dataShare[0]->p_id;
                        $shareid = $dataShare[0]->sh_id;
                        $shOption = json_decode($dataShare[0]->sh_option);
                        $this->session->set_userdata('pid', $dataShare[0]->p_id);

                        $postAto = $this->Mod_general->getActionPost();
                        if(!empty($postAto)) {
                            $imgUrl = @$pConent->picture;
                            if (preg_match("/http/", $imgUrl) && preg_match('/ytimg.com/', $imgUrl)) {
                                echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$PID.'&bid='.$json_a->blogid.'&action=postblog&blink='.$json_a->blogLink.'&autopost=1";},0 );</script>';
                                        exit();
                            }


                            if (date('H') <= 23 && date('H') > 4 && date('H') !='00') {
                                if(preg_match('/youtu/', $pConent->link) || $dataPost[0]->p_post_to ==1 || ($dataPost[0]->p_post_to == 1 && $pOption->main_post_style =='tnews')) {
                                    $waits = !empty($this->input->get('waits')) ? ($this->input->get('waits') * 1000) : 30;
                                    if(empty($wait)) {
                                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$PID.'&bid='.$json_a->blogid.'&action=postblog&blink='.$json_a->blogLink.'&autopost=1";}, '.$waits.' );</script>';
                                        exit();
                                    } 
                                    if(!empty($wait)) {
                                        redirect(base_url() . 'Facebook/share?post='.$value.'&id=' . $pid.'&agent=' . $shOption->userAgent.'&shareid='.$shareid);
                                        // $waiting = $pOption->wait_post;
                                        // $styleA = $waiting * (60 * 10);
                                        // $waiting = $waiting * 60;
                                        // echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$PID.'&bid='.$json_a->blogid.'&action=postblog&blink='.$json_a->blogLink.'&autopost=1";}, '.$styleA.' );</script>';
                                    }
                                } else {
                                    redirect(base_url() . 'Facebook/share?post='.$value.'&id=' . $pid.'&agent=' . $shOption->userAgent.'&shareid='.$shareid);
                                }
                                // if(!preg_match('/youtu/', $pConent->link)) {
                                //     redirect(base_url() . 'Facebook/share?post='.$value.'&id=' . $pid.'&agent=' . $shOption->userAgent.'&shareid='.$shareid);
                                // } else {
                                //     echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$PID.'&bid='.$json_a->blogid.'&action=postblog&blink='.$json_a->blogLink.'&autopost=1";}, 30 );</script>';
                                //     exit();
                                // }
                            } else {
                                echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/waiting";}, 30 );</script>';
                            }
                        } else {
                            if(!preg_match('/youtu/', $pConent->link) && $dataPost[0]->p_post_to ==0) {
                                redirect(base_url() . 'Facebook/share?post='.$value.'&id=' . $pid.'&agent=' . $shOption->userAgent.'&shareid='.$shareid);
                                exit();
                            } else {
                                echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$PID.'&bid='.$json_a->blogid.'&action=postblog&blink='.$json_a->blogLink.'&autopost=1";}, 30 );</script>';
                                exit();
                            }
                            // if($dataPost[0]->p_post_to == 0) {
                            //     redirect(base_url() . 'Facebook/share?post='.$value.'&id=' . $pid.'&agent=' . $shOption->userAgent.'&shareid='.$shareid);
                            //  } else if($dataPost[0]->p_post_to == 1) {
                            //     echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$PID.'&bid='.$json_a->blogid.'&action=postblog&blink='.$json_a->blogLink.'&autopost=1";}, 30 );</script>';
                            //     exit();
                            // }
                        }
                    }
                } else {
                    // if(!empty($topost)) {
                    //     $pConent = json_decode($pData->p_conent);
                    //     $shOption = json_decode($pData->sh_option);
                    //     redirect(base_url() . 'Facebook/share?post='.$value.'&id=' . $pid.'&agent=' . $shOption->userAgent.'&shareid='.$shareid);
                    //     echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$topost.'&bid='.$json_a->blogid.'&action=postblog&blink='.$json_a->blogLink.'&autopost=1";}, 30 );</script>';
                    //     exit(); 
                    // }
                   


                    // if(empty($pid)) {
                    //     $where_Pshare = array (
                    //         'u_id' => $sid,
                    //         'p_post_to' => 1,
                    //     );
                    //     $dataPost = $this->Mod_general->select (
                    //         'post',
                    //         '*', 
                    //         $where_Pshare
                    //     );
                    //     if(!empty($dataPost[0])) {
                    //         $pid = $dataPost[0]->p_id;
                    //     }
                    // }
                    // if(!empty($pid)) {
                    //     echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$pid.'&bid='.$json_a->blogid.'&action=postblog&blink='.$json_a->blogLink.'&autopost=1";}, 30 );</script>';
                    //     exit();
                    // }
                }
                if(empty($dataPost[0])) {
                    redirect(base_url() . 'managecampaigns?m=runout_post');
                }
                break;
             case 'wait':
                echo '<meta http-equiv="refresh" content="60">';
                $pid = (int) $this->input->get('id');
                $sids = (int) $this->input->get('sid');
                $suid = (int) $this->input->get('suid');
                $uid = (int) $this->input->get('uid');                

                /*update share group id */
                $dataShare = array(
                    'sh_status' => 1
                );
                $whereShere = array(
                    'uid' => (int) $log_id,
                    'p_id'=> $pid,
                    'sh_id' => $shareId,
                );
                $dataid = $this->Mod_general->update('share', $dataShare, $whereShere);
                /*End update share group id */

                /*share_history*/
                $whereCheck = array (
                    'uid' => $log_id,
                    'p_id' => $pid,
                    'social_id' => $sid,
                    'sh_id' => $shareId,
                );
                $ShareCheck = $this->Mod_general->select (
                    'share',
                    '*', 
                    $whereCheck
                );
                $count_shared = 0;
                if(!empty($ShareCheck[0])) {
                    $whereHi = array(
                        'sid' => $sid,
                        'uid' => $log_id,
                        'sg_id' => $ShareCheck[0]->sg_page_id,
                        'shp_posted_id' => $pid,
                        'shhare_id' => $shareId,
                    );
                    $sharedCheck = $this->Mod_general->select('share_history', '*', $whereHi);
                    if(empty($sharedCheck[0])) {
                        $ShareCheckHis = array(
                            'shp_date' => date('Y-m-d H:i:s'),
                            'sg_id' => $ShareCheck[0]->sg_page_id,
                            'shp_posted_id' => $ShareCheck[0]->p_id,
                            'shhare_id' => $ShareCheck[0]->sh_id,
                            'sid' => $ShareCheck[0]->social_id,
                            'uid' => $log_id,
                        );
                        $GroupListID = $this->mod_general->insert('share_history', $ShareCheckHis);
                    }
                    /*count shred*/
                    $whereHis = array(
                        'sid' => $ShareCheck[0]->social_id,
                        'uid' => $log_id,
                        'shp_posted_id' => $pid,
                    );
                    $shared = $this->Mod_general->select('share_history', 'shp_id', $whereHis);
                    $count_shared = count($shared);
                }
                /*End count shred*/
                /*End share_history*/

                /*get next post*/
                if($randomLink == 1) {
                    $orderBy = 'rand()';
                    $where_Pshare = array (
                        'u_id' => $sid,
                        'p_status' => 1,
                        'p_post_to' => 0,
                    );
                } else {
                    $where_Pshare = array (
                        'p_id' => $pid,
                        'p_status' => 1,
                        'p_post_to' => 0,
                    );
                    // $where_so = array (
                    //     'uid' => $log_id,
                    //     'sh_status' => 0,
                    //     'sh_type' => 'imacros',
                    //     'p_id' => $pid,
                    //     'social_id'=> $sid
                    // );
                    $orderBy = 'p_date desc';
                }
                $dataPost = $this->Mod_general->select (
                    'post',
                    '*', 
                    $where_Pshare,
                    $order = $orderBy, 
                    $group = 0, 
                    $limit = 1 
                );
                if(!empty($dataPost[0])) {
                    /* check before insert */
                    $where_so = array (
                        'uid' => $log_id,
                        'sh_status' => 0,
                        'sh_type' => 'imacros',
                        'p_id' => $dataPost[0]->p_id,
                        'social_id'=> $sid
                    );
                    $orderBy = 'c_date desc';
                    $dataShare = $this->Mod_general->select (
                        'share',
                        '*', 
                        $where_so
                    );

                    /*check post and share count*/               
                    $where_shCo = array (
                        'uid' => $log_id,
                        'social_id'=> $sid,
                        'p_id'=> $dataPost[0]->p_id,
                    );
                    $dataShCheck = $this->Mod_general->select (
                        'share',
                        '*',
                        $where_shCo
                    );
                    if(count($dataShCheck) == $count_shared) {
                        $this->Mod_general->delete ( 'post', array (
                            'p_id' => $dataPost[0]->p_id,
                            'user_id' => $log_id,
                        ));
                        $this->Mod_general->delete ( 'share', array (
                            'p_id' => $dataPost[0]->p_id,
                            'uid' => $log_id,
                        ));
                        $this->Mod_general->delete ( 'share_history', array (
                            'shp_posted_id' => $dataPost[0]->p_id,
                            'uid' => $log_id,
                        ));
                        /*go to check post*/
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'Facebook/share?post=checkpost";}, 2000 );</script>';
                        exit();
                    }
                    /*End check post and share count*/ 

                    if(!empty($dataShare[0])) {
                        $pid = $dataShare[0]->p_id;
                        $post_id = $dataShare[0]->p_id;
                        $shareid = $dataShare[0]->sh_id;
                        $this->session->set_userdata('post_id', $post_id);
                        echo '<center>Please wait...</center>';
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'Facebook/share?post=gwait&id='.$pid.'&uid='.$uid.'&suid='.$suid.'&agent='.$UserAgent.'&shareid='.$shareid.'";}, 2000 );</script>';
                        exit();
                        //redirect(base_url() . 'Facebook/share?post=gwait&id=' . $pid);
                    }
                    /*End get next post*/
                } else {
                    /*get next post*/
                    $where_Pshare = array (
                        'u_id' => $sid,
                        'p_status' => 1,
                        'p_post_to' => 0,
                    );
                    $dataPost = $this->Mod_general->select (
                        'post',
                        '*', 
                        $where_Pshare
                    );
                    if(!empty($dataPost[0])) {
                        $whereNext = array (
                            'uid' => $log_id,
                            'sh_status' => 0,
                            'sh_type' => 'imacros',
                            'p_id' => $dataPost[0]->p_id,
                            'social_id'=> $sid
                        );
                        $nextShare = $this->Mod_general->select (
                            'share',
                            '*', 
                            $whereNext
                        );                    
                        if(!empty($nextShare[0])) {
                            $pid = $nextShare[0]->p_id;
                            $shareid = $nextShare[0]->sh_id;
                            $this->session->set_userdata('post_id', $pid);
                            echo '<center>Please wait...</center>';
                        echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'Facebook/share?post=nexpost&id='.$pid.'&uid='.$uid.'&suid='.$suid.'&agent='.$UserAgent.'&shareid='.$shareid.'";}, 2000 );</script>';
                            //redirect(base_url() . 'Facebook/share?post=nexpost&id=' . $pid);
                        exit();
                        }
                    }
                    /*end get next post*/
                }                
                /*End check next post */
                /*count shred*/
                // $whereHis = array(
                //     'sid' => $sid,
                //     'uid' => $log_id
                // );
                // $shared = $this->Mod_general->select('share_history', 'shp_id', $whereHis);
                // if(count($shared) >= 16) {
                //     $this->Mod_general->delete ('share_history', 
                //     array (
                //         'sid' => $sid,
                //         'uid' => $log_id
                //     ));
                // }
                /*End count shred*/                               
                break;
            default:
                # code...
                break;
        }
        //redirect(base_url() . 'managecampaigns?m=runout_post');
        
    }
    public function share()
    {
        $log_id = !empty($this->input->get('uid')) ? $this->input->get('uid') : $this->session->userdata('user_id');
        $user = $this->session->userdata('email');
        $action = $this->input->get('post');
        $sid = !empty($this->input->get('suid')) ? $this->input->get('suid') : $this->session->userdata ( 'sid' );
        $pid = $this->input->get( 'id' );
        $postId = $this->input->get( 'pid' );

        if(!empty($this->input->get('uid'))) {
            $this->session->set_userdata('user_id', $this->input->get('uid'));
        }
        if(!empty($this->input->get('suid'))) {
            $this->session->set_userdata('sid', $this->input->get('suid'));
        }
        $userAgent = 0;
        if(!empty($this->input->get('agent'))) {
           $userAgent = $this->input->get('agent');
        }
        if(empty($this->session->userdata ( 'sid' ))) {
            redirect(base_url() . 'managecampaigns?back='.urlencode(base_url() . 'Facebook/share?post=nexpost&agent='.$userAgent));
        }
        $this->session->set_userdata('sid', $sid);
        $data['licence'] = $this->session->userdata('licence');


        $data['title'] = 'Share to Facebook';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        $this->breadcrumbs->add('Post list', base_url().'managecampaigns');
        $this->breadcrumbs->add('Share post', base_url().$this->uri->segment(2));
        $data['breadcrumb'] = $this->breadcrumbs->output();

        $sharePost = new ArrayObject();
        if(!empty($action) && $action != 'checkpost') {
            /*get Post to post*/
            $date = new DateTime("now");
            $curr_date = $date->format('Y-m-d h:i:s');
            $where_so = array (
                    'p_id' => $pid,
                    'sh_status'=>0

            );
            //'DATE(c_date)' => $curr_date
            
            //'DATE(c_date) <=' =>  $curr_date
            $data['share'] = $this->Mod_general->select(
                'share',
                '*', 
                $where_so);              
            $sharePost->pcount = 0; 
            if(!empty($data['share'][0])) {
                $sharePost->pcount = 1;
                $sharePost->pid = $pid = $data['share'][0]->p_id;
                $sharePost->sid = $data['share'][0]->sg_page_id;
                $sharePost->share_id = $data['share'][0]->sh_id;
                $sharePost->social_id = $data['share'][0]->social_id;
                $where_so = array (
                    'p_id' => $pid,
                );
                $data['post'] = $dataPost = $this->Mod_general->select ('post','*', $where_so, $order = 0, $group = 0, $limit = 1 );
                /*get option from post*/
                if(!empty($data['post'][0])) {
                    $pConent = json_decode($data['post'][0]->p_conent);                
                    $pSchedule = json_decode($data['post'][0]->p_schedule);

                    /*check before share*/
                    $fbuids = $this->session->userdata('fb_user_id');
                    $tmp_path = './uploads/'.$log_id.'/'. $fbuids . '_tmp_action.json';
                    $string = @file_get_contents($tmp_path);
                    $data['json_a'] = $json_a = @json_decode($string);

                    /*show blog linkA*/
                    /*End show blog link*/
                    if(preg_match('/youtube.com/', $pConent->link) || preg_match('/youtu.be/', $pConent->link)) {
                        //echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/yturl?pid='.$dataPost[0]->p_id.'&bid='.$json_a->blogid.'&action=postblog&blink='.$json_a->blogLink.'&autopost=1";}, 30 );</script>';
                        //exit();
                    }
                    /*end check before share*/
                    $sharePost->conent = $pConent;
                    $sharePost->option = $pSchedule;
                    $sharePost->pTitle = $data['post'][0]->p_name;
                    $sharePost->p_post_to = $data['post'][0]->p_post_to;

                    /*time waiting*/
                    /*Check image before post*/
                    $sharePost->notcheckimage = @$pSchedule->check_image;
                    /*Check image before post*/

                    

                    /*Post schedule*/
                    if($pSchedule->share_schedule == 1) {
                        $date = DateTime::createFromFormat('m-d-Y H:i:s',$pSchedule->start_date . ' ' . $pSchedule->start_time);
                        $cPost = $date->format('Y-m-d H:i:s');
                        $start  = date_create($cPost);
                        $end    = date_create(date('Y-m-d H:i:s')); // Current time and date
                        $diff   = date_diff( $start, $end );
                        $sharePost->diff = $diff;                
                        $sharePost->timeStart = $cPost;                
                        $sharePost->waiting = $this->startEndTime($diff);                
                    }
                }
                /*get group id*/
                $whereGruops = array(
                    'sg_id'=>$data['share'][0]->sg_page_id
                );
                $gids = $this->Mod_general->select ('socail_network_group','sg_page_id', $whereGruops);
                if(!empty($gids[0])) {
                    $sharePost->group_id = $gids[0]->sg_page_id;
                } 
                /*End get group id*/
                /*End get option from post*/
                $subRand = $preRand = '';


                /*Show data Prefix*/
                if(!empty($pSchedule->prefix_checked)) {
                    if(!empty($pSchedule->prefix_title)) {
                        $prefixArr = explode('|', $pSchedule->prefix_title);
                        $preRand = $prefixArr[mt_rand(0, count($prefixArr) - 1)];
                    } else {       
                        $where_pre = array(
                            'c_name'      => 'prefix_title',
                            'c_key'     => $log_id,
                        );
                        $prefix_title = $this->Mod_general->select('au_config', '*', $where_pre);
                        if(!empty($prefix_title[0])) {
                            $prefix = json_decode($prefix_title[0]->c_value);
                            $prefixArr = explode('|', $prefix);
                            $preRand = $prefixArr[mt_rand(0, count($prefixArr) - 1)];
                        }
                    }
                }
                /*End Show data Prefix*/

                /*Show data Prefix*/
                if(!empty($pSchedule->suffix_checked)) {
                    if(!empty($pSchedule->suffix_title)) {
                        $subFixArr = explode('|', $pSchedule->suffix_title);
                        $subRand = $subFixArr[mt_rand(0, count($subFixArr) - 1)];
                    } else {
                        $whereSuf = array(
                            'c_name'      => 'suffix_title',
                            'c_key'     => $log_id,
                        );
                        $suffix_title = $this->Mod_general->select('au_config', '*', $whereSuf);
                        if(!empty($suffix_title[0])) {
                            $subfix = json_decode($suffix_title[0]->c_value);
                            $subFixArr = explode('|', $subfix);
                            $subRand = $subFixArr[mt_rand(0, count($subFixArr) - 1)];
                        }
                    }
                }
                /*End Show data Prefix*/
                if(!empty($preRand)) {
                    $sharePost->title = $preRand . '<br/>' . $sharePost->pTitle . '<br/>' . $subRand;
                } else {
                    $sharePost->title = $sharePost->pTitle . !empty($subRand)? '<br/>' . $subRand :'';
                }
                /*count share posts*/
                $whereCount = array (
                    'p_id' => $pid,

                );
                $shareCountPost = $this->Mod_general->select ('share','*', $whereCount);
                $sharePost->totalGroups = count($shareCountPost);
                /*End count share posts*/

                $str = time();
                $str = md5($str);
                $uniq_id = substr($str, 0, 9);
                $link = $pConent->link . '?s=' . $uniq_id. '&g='.$sharePost->group_id.'&fb='.$this->session->userdata ( 'fb_user_id' ).'&m=1';
                
                //$pLink = $sharePost->link . '&g='.$sharePost->group_id.'&fb='.$this->session->userdata ( 'fb_user_id' ).'&m=1';
                //bitly shorter
                //$link = $this->shorturl($link,$pSchedule->short_link);

                /*random link and image*/
                $sharePost->shorturl = $pSchedule->short_link;
                if($pSchedule->random_link == 1) {                    
                    $sharePost->link = $this->randomLink($link,$pConent->picture);
                } else {
                    $sharePost->link = $link;
                }
                /*End random link*/

                /*create csv*/
                // $file_name = $pid . '_post.csv';
                // $upload_path = FCPATH . "uploads/post/";
                // if (!file_exists($upload_path)) {
                //     mkdir($upload_path, 0700, true);
                // }  
                // $handle = fopen($upload_path.$file_name, 'w');
                // fputs($handle ,(chr(0xEF).chr(0xBB).chr(0xBF)));
                // fputcsv($handle, array($sharePost->title, $sharePost->link,$sharePost->group_id,$pid));
                // fclose($handle);
                /*End create csv*/
            }
            if(empty($sharePost->link)) {
                // $this->Mod_general->delete (
                //     'post', 
                //     array (
                //         'p_id' => $pid,
                //         'user_id' => $log_id
                //     )
                // );
                redirect(base_url() . 'facebook/shareation?post=getpost');
            }

            /*if random Link*/
            $sharePost->randomLink = false;
            $randomLink = $this->session->userdata ( 'randomLink' );
            /* check before insert */
            if ($randomLink == 1) {
                $sharePost->randomLink = $randomLink;
            }

            // $whereHi = array(
            //     'sid' => $sharePost->social_id,
            //     'uid' => $log_id,
            //     'sg_id' => $sharePost->sid,
            // );
            // $sharedCheck = $this->Mod_general->select('share_history', 'shp_id', $whereHi);
            // if(empty($sharedCheck[0])) {
            //     $dataShareHis = array(
            //         'shp_date' => date('Y-m-d H:i:s'),
            //         'sg_id' => $sharePost->sid,
            //         'shp_posted_id' => $pid,
            //         'shhare_id' => $sharePost->share_id,
            //         'sid' => $sharePost->social_id,
            //         'uid' => $log_id
            //     );
            //     $GroupListID = $this->mod_general->insert('share_history', $dataShareHis);
            // }
            /*count shred*/
            $whereHis = array(
                'sid' => $sharePost->social_id,
                'shp_posted_id' => $sharePost->pid,
                'uid' => $log_id
            );
            $shared = $this->Mod_general->select('share_history', 'shp_id', $whereHis);
            $sharePost->count_shared = count($shared);
            /*End count shred*/

            /*End if random Link*/

            /*select post list from user*/
            $where_so = array (
                'user_id' => $log_id,
                'u_id' => $sharePost->social_id,
            );
            $sharePost->posts_list = $this->Mod_general->select ( 'post', '*', $where_so );

            if(!preg_match('/youtu/', $sharePost->link) && $sharePost->p_post_to ==0) {
                
            }  else {
                $fbUserId = $this->session->userdata('fb_user_id');
                $tmp_path = './uploads/'.$log_id.'/'. $fbUserId . '_tmp_action.json';
                $string = file_get_contents($tmp_path);
                $json_a = json_decode($string);
                $sharePost->json_a = $json_a;
            }

            /*End select post list from user*/
            $data['sharePost'] = $sharePost;
            /*End get Post to post*/
        } else {
        }  
        $this->load->view('facebook/share', $data);
    }

    public function shorturl($link='',$type=0)
    {
        if(!preg_match('/bit.ly/', $link)) {
            if($type == 1) {
                $log_id = $this->session->userdata ( 'user_id' );
                $whereBit = array(
                    'c_name'      => 'bitlyaccount',
                    'c_key'     => $log_id,
                );
                $bitlyAc = $this->Mod_general->select('au_config', '*', $whereBit);
                if(!empty($bitlyAc[0])) {
                    $bitly = json_decode($bitlyAc[0]->c_value);
                    $link = $this->get_bitly_short_url ( $link, $bitly->username, $bitly->api );
                } else {
                    return $link;
                } 
                
            } 
        }
        return $link;
    }

    public function startEndTime($diff)
    {
        $year = $month = $day = $hour = $minute = $second = 0;
        if ($diff->invert == 1) {
            if ($diff->y > 0) {
                $year = $diff->y * 311040000;       
            } 
            if ($diff->m > 0) {
                $month = $diff->m * 25920000;
            }
            if ($diff->d > 0) {
                $day = $diff->d * 864000;
            }
            if ($diff->h > 0) {
                $hour = $diff->h * 36000;
            }
            if ($diff->i > 0) {
                $minute = $diff->i * 600;
            }
            if ($diff->s > 0) {
                $second = $diff->s * 10;
            }
        }
        return $year + $month + $day + $hour + $minute + $second;
    }

    public function unfriend()
    {
        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');

        $data['title'] = 'Unfriend at Once';
        $this->breadcrumbs->add('<i class="icon-home"></i> Home', base_url());
        if($this->uri->segment(1)) {
            $this->breadcrumbs->add($data['title'], base_url(). $this->uri->segment(1)); 
        }
        $this->breadcrumbs->add('fb list', base_url().$this->uri->segment(1).'/fblist');
        $this->breadcrumbs->add('Uncheck', base_url().$this->uri->segment(2));
        $data['breadcrumb'] = $this->breadcrumbs->output();
        $this->load->view('facebook/unfriend', $data);
    }

    /* returns the shortened url */
    function get_bitly_short_url($url, $login, $appkey, $format = 'txt') {
        $connectURL = 'http://api.bit.ly/v3/shorten?login=' . $login . '&apiKey=' . $appkey . '&uri=' . urlencode ( $url ) . '&format=' . $format;
        return $this->curl_get_result ( $connectURL );
    }
    
    /* returns expanded url */
    function get_bitly_long_url($url, $login, $appkey, $format = 'txt') {
        $connectURL = 'http://api.bit.ly/v3/expand?login=' . $login . '&apiKey=' . $appkey . '&shortUrl=' . urlencode ( $url ) . '&format=' . $format;
        return $this->curl_get_result ( $connectURL );
    }
    
    /* returns a result form url */
    function curl_get_result($url) {
        $ch = curl_init ();
        $timeout = 5;
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        $data = curl_exec ( $ch );
        curl_close ( $ch );
        return $data;
    }
}

/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */
