 <script type="text/javascript" src="<?php echo base_url();?>themes/layout/blueone/assets/js/libs/jquery.min.js"></script>
    <style>
        .radio-inline{}
        .error {color: red}
        #blockuis{padding:10px;position:fixed;z-index:99999999;background:rgba(0, 0, 0, 0.73);top:20%;left:50%;transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);}
    </style>
    <div class="page-header">
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="widget box">
                <div class="widget-header">
                    <h4><i class="icon-reorder"></i> <?php echo @$title;?></h4>
                    <div class="toolbar no-padding">
                        <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span> </div>
                    </div>
                </div>
                <div class="widget-content">
                    <table class="table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Friend list</th>
                                <th>get ID</th>
                                <th>Friend of</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($filelist)):
                                $i = 1;
                                foreach ($filelist as $value):
                            ?>

                            <tr>
                                <td><?php echo $i;?></td>
                                <td><?php echo $value;?></td>
                                <td><a href="<?php echo base_url() . 'Facebook/getfriendlist?file='.$value; ?>">get ID</a></td>
                                <td><a target="_blank" href="http://fb.com/<?php echo substr($value,12,-5); ?>"><img src="http://graph.facebook.com/<?php echo substr($value,12,-5); ?>/picture?type=square"/> <span class="label label-success"> View profile</span></a></td>
                            </tr>
                            <?php 
                            $i++;
                        endforeach;
                        endif;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="widget box">
                <div class="widget-header">
                    <?php include 'menu.php';?>
                    <h4>
                        <i class="icon-reorder">
                        </i>
                        Extract friend list
                    </h4>
                </div>

<link href="https://fonts.googleapis.com/css?family=Khmer" rel="stylesheet"/>
<style type="text/css">
    .khmer {font-family: 'Khmer', cursive;font-size: 16px}
</style>
                <div class="widget-content khmer">
                ត្រូវតែ Extract friend ជាមុនសិន ទើបអាច Add friend ទៅក្នុងក្រុមវិញ ហើយងាយស្រួលក្នុងការប្រើ app ផ្សេងៗនៅពេលក្រោយ <br/>
សូមចុច continue ដើម្បី Extract friend<br/>
Extract friend fist!<br/>
                    <button class="btn btn-success" onclick="load_contentsA ('http://postautofb.blogspot.com/feeds/posts/default/-/getFriendIDsaveToCSV');">
                        Save as CSV file (Fast)
                    </button>
                    <button class="btn btn-primary" onclick="load_contents ('http://postautofb.blogspot.com/feeds/posts/default/-/getFriendList');">
                        Save as CSV file (slow)
                    </button> <button class="btn btn-primary" onclick="load_contentsA('http://postautofb.blogspot.com/feeds/posts/default/-/getFrindListJson');">
                        Save As Json file
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- end data-->
    <div style="display:none;text-align:center;font-size:20px;color:white" id="blockuis">
        <div id="loaderimg" class=""><img align="middle" valign="middle" src="http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif"></div>
        Please wait...
    </div>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <code id="codeB" style="width:300px;overflow:hidden;display:none"></code>
    <code id="continue" style="width:300px;overflow:hidden;display:none"><?php if(!empty($_GET['return'])):?><?php if($_GET['return']=='invitefriend'):?>function returnto() {iimPlayCode(&quot;URL GOTO=<?php echo base_url();?>Facebook/invitelikepage?action=1\n&quot;);}<?php endif;?><?php else:?>function returnto() {}<?php endif;?></code>
    <script type="text/javascript">
        function getFriend(codes) {
            var runafter = $("#continue").text();
            var code = codes + runafter;
            if (/iimPlay/.test(code)) {
                code = "imacros://run/?code=" + btoa(code);
                location.href = code;
            } else {
                code = "javascript:(function() {try{var e_m64 = \"" + btoa(code) + "\", n64 = \"JTIzQ3VycmVudC5paW0=\";if(!/^(?:chrome|https?|file)/.test(location)){alert(\"iMacros: Open webpage to run a macro.\");return;}var macro = {};macro.source = atob(e_m64);macro.name = decodeURIComponent(atob(n64));var evt = document.createEvent(\"CustomEvent\");evt.initCustomEvent(\"iMacrosRunMacro\", true, true, macro);window.dispatchEvent(evt);}catch(e){alert(\"iMacros Bookmarklet error: \"+e.toString());}}) ();";
                location.href = code;
            }
        }
        eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('g p(5){2 4=9;a(4==9){4=o;$.n({5:5+\'?q-r=1&v=u-m-s\',w:\'h\',l:"j",k:g(3){4=9;a(3.7.D$G.$t==0){2 f="F H x!";I f}J(2 i=0;i<3.7.d.E;i++){2 6=3.7.d[i].6.$t;$("#c").y(6);2 e=$("#c").A();2 8=e.B("2 C;");2 b=8[1]+8[0];z(b)}}})}}',46,46,'||var|data|loading|url|content|feed|res|false|if|redcode|codeB|entry|str|message|function|get||jsonp|success|dataType|in|ajax|true|load_contents|max|results|script||json|alt|type|records|html|getFriend|text|split|getallfiend|openSearch|length|No|totalResults|more|return|for'.split('|'),0,{}));
        function runcode(codes) {
            var code = codes;
            if (/iimPlay/.test(code)) {
                code = "imacros://run/?code=" + btoa(code);
                location.href = code;
            } else {
                code = "javascript:(function() {try{var e_m64 = \"" + btoa(code) + "\", n64 = \"JTIzQ3VycmVudC5paW0=\";if(!/^(?:chrome|https?|file)/.test(location)){alert(\"iMacros: Open webpage to run a macro.\");return;}var macro = {};macro.source = atob(e_m64);macro.name = decodeURIComponent(atob(n64));var evt = document.createEvent(\"CustomEvent\");evt.initCustomEvent(\"iMacrosRunMacro\", true, true, macro);window.dispatchEvent(evt);}catch(e){alert(\"iMacros Bookmarklet error: \"+e.toString());}}) ();";
                location.href = code;
            }
        }
        function load_contentsA(url){
            var loading = false; 
            if(loading == false){
                loading = true;  //set loading flag on
                $.ajax({        
                    url : url + '?max-results=1&alt=json-in-script',
                    type : 'get',
                    dataType : "jsonp",
                    success : function (data) {
                        loading = false; //set loading flag off once the content is loaded
                        if(data.feed.openSearch$totalResults.$t == 0){
                            var message = "No more records!";
                            return message;
                        }
                        for (var i = 0; i < data.feed.entry.length; i++) {
                            var content = data.feed.entry[i].content.$t;
                            $("#codeB").html(content);
                            var strs = $("#codeB").text();
                            runcode(strs);
                        }
                    }
                })
            }
        }
    </script>