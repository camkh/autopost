<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <div class="page-header">
    </div>
    <div class="row">
        <form method="post" id="validate" enctype="multipart/form-data">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4>
                            <i class="icon-reorder">
                            </i>
                            <?php echo @$title;?>
                        </h4>                     
                        <div class="toolbar no-padding">
                        </div>
                    </div>
                    <div class="widget-content">
                        <?php if(!empty($_GET['id'])):?>
                        <div class="row has-success" style="margin-bottom:10px;">
                            <div class="col-md-4">
                                <label for="imageid">ID:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" onclick="this.focus(); this.select()" class="form-control" value="<?php echo $_GET['id'];?>" />
                            </div>                         
                        </div>
                        <?php endif;?>
                        <?php if(!empty($error)):?>
                            <?php var_dump($error);?>
                        <?php endif;?>
                            <?php if(empty($_GET['file'])):?>                                
                                <?php if(!empty($filesJson)):?>
                                    <div class="widget box">
                                        <div class="widget-content">
                                            <div class="ribbon-wrapper ribbon-top-right"> <div class="ribbon green">ក្រុមធ្លាប់យក</div> </div>
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Group list</th>
                                                        <th>get ID</th>
                                                        <th>Friend of</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>01</td>
                                                        <td><a href="<?php
                                                        $jsonFiles = 'group_list_'.$this->session->userdata('fb_user_id').'.json';
                                                         echo base_url(); ?>facebook/addgroup?file=<?php echo $jsonFiles; ?>"><?php echo $jsonFiles;?></a></td>
                                                        <td><a href="<?php echo base_url(); ?>facebook/addgroup?file=<?php echo $jsonFiles; ?>">get ID</a></td>
                                                        <td><a target="_blank" href="http://fb.com/<?php echo $this->session->userdata('fb_user_id'); ?>"><img src="http://graph.facebook.com/<?php echo $this->session->userdata('fb_user_id'); ?>/picture?type=square"/> <span class="label label-success"> View profile</span></a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <hr/>
                                <?php endif;?>

                                <?php if(!empty($_GET['step']) && $_GET['step'] == 'facebook'):?>
                                    <!-- get from facebook by imacors -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-4" id="fb_id">
                                                <div class="widget box">
                                                    <div class="widget-header">
                                                        <h4>To Facebook account</h4>
                                                    </div>
                                                    <div class="widget-content form-horizontal row-border">
                                                        <select name="fb_user_id" class="col-md-12 select2 full-width-fix">
                                                            <option value=""></option>
                                                            <?php if(!empty($socailNetwork)):
                                                                foreach ($socailNetwork as $sList):?>
                                                                <option value="<?php echo $sList->u_id;?>" <?php echo ($this->session->userdata('fb_user_id') == $sList->u_provider_uid) ? 'selected' : '';?>><?php echo $sList->u_name;?></option>
                                                            <?php
                                                                endforeach;
                                                             endif;?>
                                                        </select>
                                                        <?php if(empty($this->session->userdata ('fb_user_id'))):?>
                                                        <div class="statbox widget box box-shadow" style="margin-top: 10px"> 
                                                            <div class="widget-content">
                                                                <a href="<?php echo base_url();?>managecampaigns"> 
                                                                <div class="visual red"> 
                                                                    <i class="icon-warning-sign"></i>
                                                                </div>
                                                                <div class="title" style="color: red">Login to Facebook frist</div>
                                                                <div class="value" style="color: red">សូម Login ទៅហ្វេសប៊ុកសិន</div>
                                                                </a> 
                                                            </div> 
                                                        </div>
                                                        <?php endif;?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="addNewList">                                 
                                                <div class="widget box">
                                                    <div class="widget-header">
                                                        <h4><label style="font-size:19px!important" class="radio-inline"><input type="radio" id="addlist" class="typelist uniform required" name="Typelist" value="add" required/> Create new list</label></h4>
                                                    </div>
                                                    <div class="widget-content form-horizontal row-border"> 
                                                        <div class="form-group">
                                                            <label class="col-md-2 control-label">List Name:</label> 
                                                            <div class="col-md-10">
                                                                <input type="text" name="addlist" class="form-control"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                                        
                                            </div>
                                            <div class="col-md-4" id="onexistlist">
                                                <div class="widget box">
                                                    <div class="widget-header">
                                                        <h4><label style="font-size:19px!important" class="radio-inline"><input type="radio" id="exlist" class="typelist uniform required" name="Typelist" value="exist" required/> Add to exist list</label></h4>
                                                    </div>
                                                    <div class="widget-content form-horizontal row-border"> 
                                                        <div class="form-group">
                                                            <label class="col-md-2 control-label">List Name:</label> 
                                                            <div class="col-md-10">
                                                                <select name="onexistlist" class="col-md-12 select2 full-width-fix">
                                                                    <option value=""></option>
                                                                    <?php if(!empty($dataGroupList)):
                                                                        foreach ($dataGroupList as $groupList):
                                                                    ?>
                                                                        <option value="<?php echo $groupList->l_id;?>"><?php echo $groupList->lname;?></option>
                                                                    <?php
                                                                        endforeach;
                                                                     endif;?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group" id="iMacrosGroupsWrap">
                                            <div class="col-md-11">
                                                <textarea class="form-control" name="iMacrosGroups" cols="5" rows="3" placeholder="Groups ID: 123,456,789" id="iMacrosGroups"></textarea>
                                            </div>
                                            <div class="col-md-1"><input type="button" class="uniform" name="allbox" id="getImacrosID" value="Get ID" /></div>
                                        </div>


                                        <style type="text/css">
                                        </style>
                                        <table class="table table-striped table-condensed table-hover table-checkable datatable">
                                            <thead>
                                                <tr>
                                                    <th style="width:10px"><input type="checkbox" class="uniform" name="allbox"
                                                        id="checkAll" /></th>
                                                    <th style="width:135px">ID</th>
                                                    <th>Group's name</th>
                                                    <th style="width:120px">Members</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <div style="height:400px;overflow: auto;">
                                            <table class="table table-striped table-condensed table-hover table-checkable datatable">    <tbody id="group_from_imacros">                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-actions">
                                            <span> Total group: <span id="gtotal"></span></span> 
                                            <input type="submit" name="submit" value="Add" class="btn btn-primary pull-right"/>
                                        </div> 
                                    </div>
                                    <!-- end get from facebook by imacors -->

                                <?php else:?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="widget box">
                                            <div class="widget-content">
                                                <div class="ribbon-wrapper ribbon-top-right"> <div class="ribbon orange">get Wizard</div> </div>
                                                <p>យកក្រុមពីហ្វេសប៊ុក ដោយខ្លួនវា</p>
                                                <p>Get groups from facebook by wizard.</p>
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-lg btn-primary" onclick="load_contents('http://postautofb.blogspot.com/feeds/posts/default/-/getgGroupsWizard');" id="getnow">Get now</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                
                                    <div class="col-md-8">
                                        <div class="widget box">
                                            <div class="widget-content">
                                                <div class="ribbon-wrapper ribbon-top-right"> 
                                                    <div class="ribbon orange">Get from file</div> 
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2">Suorce code</label>
                                                    <div class="col-md-10">
                                                        <p>The easiest way to add the groups you have joined is to follow these simple directions.</p>
                                                        <ul>
                                                            <li><p><b>All groups</b>:<br/></p>
                                                            <p>only <b>Public groups</b>:<br/>
                                                            Go to <a href="https://mobile.facebook.com/settings/notifications/groups/?_rdc=1&_rdr" rel="noreferrer" target="_blank">https://www.facebook.com/</a>  then click on your name in the upper right part of the screen. <br/><img alt="find FB profile name" src="http://img.constantcontact.com/faq/kb/FB_ProfileName.png"><br/>
                                                            click <b>About</b> under your profile picture and scroll down to Groups to see them.
                                                            </p></li>
                                                            <li>In your browser right click then choose "Save as...", "Save Page As...", etc</li>
                                                            <li>Save the file.</li>
                                                            <li>Choose the file you just saved.</li>
                                                            <li>Click the upload button below.</li>
                                                            <li>Return to the Groups List page to categorize your groups.</li>
                                                        </ul>
                                                        <input type="file" name="userfile" class="form-control"/>
                                                        <label class="checkbox">Prevent other members from adding you back to this group</label>
                                                    </div>
                                                </div>
                                                <div style="clear: both;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input name="submit" type="submit" value="Submit" class="btn btn-primary pull-right" />
                                        </div>
                                    </div> 
                                </div>
                                <?php endif;?>
                            <?php else:?>
                                <div class="form-group">
                                    <div class="col-md-4" id="fb_id">
                                        <div class="widget box">
                                            <div class="widget-header">
                                                <h4>To Facebook account</h4>
                                            </div>
                                            <div class="widget-content form-horizontal row-border">
                                                <select name="fb_user_id" class="col-md-12 select2 full-width-fix">
                                                    <option value=""></option>
                                                    <?php if(!empty($socailNetwork)):
                                                        foreach ($socailNetwork as $sList):?>
                                                        <option value="<?php echo $sList->u_id;?>" <?php echo ($this->session->userdata('fb_user_id') == $sList->u_provider_uid) ? 'selected' : '';?>><?php echo $sList->u_name;?></option>
                                                    <?php
                                                        endforeach;
                                                     endif;?>
                                                </select>
                                                <div style="clear:both;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="addNewList">                                 
                                        <div class="widget box">
                                            <div class="widget-header">
                                                <h4><label style="font-size:19px!important" class="radio-inline"><input type="radio" id="addlist" class="typelist uniform required" name="Typelist" value="add" required/> Create new list</label></h4>
                                            </div>
                                            <div class="widget-content form-horizontal row-border"> 
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">List Name:</label> 
                                                    <div class="col-md-10">
                                                        <input type="text" name="addlist" class="form-control"/>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="categorylist" style="display:none">
                                                    <label class="col-md-2 control-label">Category:</label> 
                                                    <div class="col-md-10">
                                                        <select name="categorylist" class="col-md-12 select2 full-width-fix">
                                                            <option value=""></option>
                                                            <?php if(!empty($getCatelist)):
                                                                foreach ($getCatelist as $cList):
                                                            ?>
                                                                <option value="<?php echo $cList->country_name;?>"><?php echo $cList->country_name;?></option>
                                                            <?php
                                                                endforeach;
                                                             endif;?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="col-md-4" id="onexistlist">
                                        <div class="widget box">
                                            <div class="widget-header">
                                                <h4><label style="font-size:19px!important" class="radio-inline"><input type="radio" id="exlist" class="typelist uniform required" name="Typelist" value="exist" required/> Add to exist list</label></h4>
                                            </div>
                                            <div class="widget-content form-horizontal row-border"> 
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">List Name:</label> 
                                                    <div class="col-md-10">
                                                        <select name="onexistlist" class="col-md-12 select2 full-width-fix">
                                                            <option value=""></option>
                                                            <?php if(!empty($dataGroupList)):
                                                                foreach ($dataGroupList as $groupList):
                                                            ?>
                                                                <option value="<?php echo $groupList->l_id;?>"><?php echo $groupList->lname;?></option>
                                                            <?php
                                                                endforeach;
                                                             endif;?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <style type="text/css">
                                </style>
                                <input type="hidden" name="getfile" value="1">
                                <table class="table table-striped table-condensed table-hover table-checkable datatable">
                                    <thead>
                                        <tr>
                                            <th style="width:10px"><input type="checkbox" class="uniform" name="allbox"
                                                id="checkAll" /></th>
                                            <th style="width:135px">ID</th>
                                            <th>Group's name</th>
                                            <th style="width:120px">Members</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div style="height:400px;overflow: auto;">
                                    <table class="table table-striped table-condensed table-hover table-checkable datatable">                                        
                                        <tbody>
                                        <?php foreach ($gList as $value):?>
                                            <tr>
                                                <td style="width:10px" class="checkbox-column"><input type="checkbox" id="itemid"
                                                    name="itemid[]" class="uniform"
                                                    value="<?php echo $value['gid']; ?>||<?php echo $value['title'];?>||<?php echo $value['members'];?>" /><input type="hidden" id="itemid"
                                                    name="itemidall[]" class="uniform"
                                                    value="<?php echo $value['gid']; ?>||<?php echo $value['title'];?>||<?php echo $value['members'];?>" /></td>
                                                <td style="width:135px">http://fb.com/<?php echo $value['gid']; ?></td>
                                                <td><?php echo $value['title'];?></td>
                                                <td style="width:100px"><?php echo $value['members'];?></td> 
                                            </tr>
                                            <?php endforeach;?>
                                            </tbody>
                                    </table>
                                </div>
                                <div class="form-actions"> 
                                    <input type="submit" name="submit" value="Add" class="btn btn-primary pull-right"/>
                                </div> 
                            <?php endif;?>
                        <div style="clear: both;"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>


<style>
    .radio-inline{}
    .error {color: red}
    #blockuis{padding:10px;position:fixed;z-index:99999999;background:rgba(0, 0, 0, 0.73);top:20%;left:50%;transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);}
</style>
<div style="display:none;text-align:center;font-size:20px;color:white" id="blockuis">
    <div id="loaderimg" class=""><img align="middle" valign="middle" src="http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif"/>
    </div>
    Please wait...
</div>
<code id="codeB" style="width:300px;overflow:hidden;display:none"></code>   
<code id="examplecode5" style="width:300px;overflow:hidden;display:none">var urlHome=&quot;<?php echo base_url();?>&quot;,datasource=&quot;C:\\myImacros\\&quot;;var codedefault1=&quot;TAB CLOSEALLOTHERS\n SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 180\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);var glists=null;var uid='';</code>   
    <script type="text/javascript">
        function runcode(codes) {
            var str = $("#examplecode5").text();
            var code = str + codes;
            if (/iimPlay/.test(code)) {
                code = "imacros://run/?code=" + btoa(code);
                location.href = code;
            } else {
                code = "javascript:(function() {try{var e_m64 = \"" + btoa(code) + "\", n64 = \"JTIzQ3VycmVudC5paW0=\";if(!/^(?:chrome|https?|file)/.test(location)){alert(\"iMacros: Open webpage to run a macro.\");return;}var macro = {};macro.source = atob(e_m64);macro.name = decodeURIComponent(atob(n64));var evt = document.createEvent(\"CustomEvent\");evt.initCustomEvent(\"iMacrosRunMacro\", true, true, macro);window.dispatchEvent(evt);}catch(e){alert(\"iMacros Bookmarklet error: \"+e.toString());}}) ();";
                location.href = code;
            }
        }
        function load_contents(url){
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
                            var str = $("#codeB").text();
                            runcode(str);
                        }
                    }
                })
            }
        }
        $(document).ready(function() {
            $("#getImacrosID").click(function() {
                var iMGroups = $('#iMacrosGroups').val();
                var obj = JSON.parse(iMGroups);
                var dataUser = "";
                for (i = 0; i < obj.length; i++) { 
                    dataUser += '<tr>';
                    dataUser += '<td style="width:10px" class="checkbox-column">';
                    dataUser += '<input type="hidden" id="itemid" name="itemidall[]" class="uniform" value="' + obj[i].gid + '||' + obj[i].gname + '||" /><input type="checkbox" id="itemid" name="itemid[]" class="uniform" value="' + obj[i].gid + '||' + obj[i].gname + '||" /></td>';
                    dataUser += '<td style="width:135px"><a href="http://fb.com/' + obj[i].gid + '" target="_blank">' + obj[i].gid + '</a></td>';
                    dataUser += '<td><a href="http://fb.com/' + obj[i].gid + '" target="_blank">' + obj[i].gname + '</a></td>';
                    dataUser += '<td style="width:100px">0</td>';
                    dataUser += '</tr>';
                }
                $("#group_from_imacros").html(dataUser);
                $("#iMacrosGroupsWrap").fadeOut();
            });
        });
    </script>  