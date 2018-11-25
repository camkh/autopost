<?php if ($this->session->userdata('user_type') != 4):
    if(!empty($data)):
        foreach ($data as $value):
            $dataConents = $value->{Tbl_posts::conent};
            $json = json_decode($dataConents, true);
            $Thumbnail = @$json['picture'];
            $postTitle = @$json['name'];
            $postLink = @$json['link'];
            $description = @$json['description'];
        endforeach;
    endif;
    $post_id = !empty($_GET['id'])?$_GET['id']:'';
    ?>
    <div style="display:none;text-align:center;font-size:20px;color:white" id="blockuis">
        <div id="loaderimg" class=""><img align="middle" valign="middle" src="http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif"></div>
        Please wait...
    </div>    
    <style>
        .radio-inline{}
        .error {color: red}
        .morefield {padding:5px 0 !important;}
        .morefield .form-group{padding: 0 0 0!important;}
        .morefield .input-group > .input-group-btn .btn,.ytid .btn{height: 32px}
        .removediv + .tooltip > .tooltip-inner {background-color: #f00;}
        .removediv + .tooltip > .tooltip-arrow { border-bottom-color:#f00;}
        .help-bloc{color:red;}
        #blockuis{padding:15%;position:fixed;z-index:99999999;background:rgba(0, 0, 0, 0.88) none repeat scroll 0% 0%;top:0;left: 0;right: 0;bottom: 0;}
        .fixed {position: fixed; right: 40px; width: 90%;bottom: 0;background: #fff}
    </style>
    <div class="page-header">
    </div>
    <div class="row">
        <form method="post" id="validate" class="form-horizontal row-border">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <input name="submit" type="submit" value="Publish" class="btn btn-primary pull-right" /><h4>
                            <i class="icon-reorder">
                            </i>
                            Add New Post
                        </h4>                     
                        <div class="toolbar no-padding">
                        </div>
                    </div>
                    <div class="widget-content">
                        <div class="row" style="margin-bottom:10px;">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div class="col-md-9">
                                        <input type="text" name="ytid" class="form-control" placeholder="Get from Youtube Channel ID">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group ytid">               
                                            <select name="ytmax" class="select2" style="width: 60px">
                                                    <option value="5" >5</option>
                                                    <option value="10" selected>10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="60">60</option>
                                            </select> 
                                            <span class="input-group-btn"> 
                                                <button class="btn btn-default" id="getyt" type="button">Get!</button> 
                                            </span> 
                                        </div>
                                    </div>
                                </div>
                                <!-- post by iMacros -->
                                <div class="form-group">
                                    <div class="col-md-6">តំណ / URL</div>
                                    <div class="col-md-5">ចំណងជើង / Title</div>                                    
                                  <div class="col-md-1">
                                    <span id="addfield" class="btn btn-sm  pull-right bs-tooltip <?php echo ($post_id) ? 'disabled':'';?>" data-original-title="Add more..."><i class="icon-plus"></i></span>
                                  </div>
                                </div>
                                <div class="optionBox"  id="postimacros">
                                  <div class="form-group morefield">
                                    <div class="col-md-12">
                                      <div class="form-group"> 
                                        <div class="col-md-4">
                                            <input type="text" id="link_1" value="<?php echo @$postLink; ?>" class="form-control post-option" name="link[]" placeholder="URL" onchange="getLink(this);" /> 
                                            <input type="hidden" value="<?php echo @$post_id; ?>" name="postid" id="postID"/>
                                        </div>
                                        <div class="col-md-3">
                                            <img id="show_link_1" src="https://i.ytimg.com/vi/0000/0.jpg" style="width:120px"/>
                                            <input type="hidden" id="image_link_1" value="<?php echo @$Thumbnail; ?>" class="form-control post-option" name="thumb[]" placeholder="Image url" /> 
                                        </div>
                                        <div class="col-md-5">
                                            <div class="input-group">                                                 
                                                <input type="text" value="<?php echo @$postTitle; ?>" class="form-control post-option" name="title[]" placeholder="Title" id="title_link_1" />
                                                <input type="hidden" id="name_link_1" value="" class="form-control post-option" name="name[]" />
                                                <span class="input-group-btn"> 
                                                    // <button class="btn btn-default removediv bs-tooltip" data-original-title="Remove this" type="button" <?php echo ($post_id) ? 'disabled':'';?>>
                                                        <i class="icon-remove text-danger"></i>
                                                    </button> 
                                                </span> 
                                            </div>
                                            <textarea name="conents[]" id="description_link_1" class="form-control post-option" style="height: 58px"></textarea>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <!-- End post by iMacros -->

                                <!-- post in api -->
                                <div class="row" id="postapi" style="display: none;">
                                    <div class="col-md-3">
                                        <input type="text" value="<?php echo @$postLink; ?>" name="linkapi" id="link" class="form-control required" placeholder="Link" required/>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" value="<?php echo @$postTitle; ?>" name="titleapi" id="title" class="required form-control" placeholder="Title" />
                                        
                                    </div>
                                    <div class="col-md-3">
                                        <textarea class="limited form-control" name="messageapi" rows="1" id="message"  placeholder="Message"><?php echo @$description;?></textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group"> 
                                            <input type="text" value="<?php echo @$postTitle; ?>" name="captionapi" id="caption" class="form-control" placeholder="Caption"/>
                                            <span class="input-group-btn"> 
                                                <button class="btn btn-default removediv" type="button">
                                                    <i class="icon-remove text-danger"></i>
                                                </button> 
                                            </span> 
                                        </div>
                                    </div>
                                </div> 
                                <!-- End post in api -->
                            </div>

                            <div class="col-md-4">
                                <div class="widget box">
                                    <div class="widget-content">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label class="control-label">
                                                    Select Blog post to:
                                                </label>
                                                <select name="blogpost" id="blogpost" class="required select2 full-width-fix" required>
                                                    <option value="">Select Blog post</option>
                                                    <?php foreach ($bloglist as $blog): ?>
                                                    <option value="<?php echo $blog->bid;?>"><?php echo $blog->bid;?> || <?php echo $blog->title;?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div> 
                                        </div>


                                        <label class="control-label">
                                            Social Account to Post:
                                        </label>
                                        <select name="accoung" class="required select2 full-width-fix" id="Groups" required>
                                            <option value="">Select Account</option>
                                            <?php foreach ($account as $vAccount): ?>
                                            <option value="<?php echo $vAccount->u_id; ?>|<?php echo $vAccount->u_type; ?>" <?php
                                            if($this->session->userdata('fb_user_id') == $vAccount->u_provider_uid):
                                                $fbId = $vAccount->u_id;
                                                echo 'selected';endif;?>><?php echo $vAccount->u_name; ?> [@ <?php echo $vAccount->u_type; ?> ]</option>
                                            <?php endforeach;?>
                                        </select>
                                        <label id="showgroum" for="imagepost" generated="true" class="error help-block" style="display: none;">please select one.</label>

                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label class="control-label">
                                                    Groups Type:
                                                </label>
                                                <select name="groups" id="togroup" class="required select2 full-width-fix" required>
                                                    <option value="">Groups Type</option>
                                                    <?php foreach ($groups_type as $gtype): ?>
                                                    <option value="<?php echo $gtype->l_id; ?>"><?php echo $gtype->lname; ?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div> 
                                        </div>

                                        <div class="form-group" id="groupWrapLoading" style="display: none; text-align: center; font-size: 130%;color:red;">Loading...</div>
                                        <div class="form-group" id="groupWrap" style="display: none;">
                                            <div class="col-md-12">
                                                    <label class="checkbox"> 
                                                    <input type="checkbox" value="" id="checkAll"/> 
                                                    <b>Check/Uncheck:</b>
                                                    </label>
                                                    <div style=" background-color: #E5E5E5;height: 1px; margin: 9px 0;overflow: hidden;"></div>
                                                    <div id="getAllGroups" style="max-height: 250px;overflow-y: auto; margin-right:10px"></div>
                                                    <button type="button" value="add" id="addGroups" class="btn btn-warning pull-right" style="margin-right:10px;margin-top:10px;display: none;">Add groups</button>
                                            </div>
                                        </div>
                                            </div>
                                        </div>


                                        <div class="widget box">
                                    <div class="widget-header">
                                        <h4><i class="icon-reorder"></i> កំណត់នៃការស៊ែរ៍ / Share Option:</h4>
                                    </div>
                                    <div class="widget-content">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">ប្រើប្លុកលីងគ៍<br/>with Blog link?:</label>
                                            <div class="col-md-8">
                                                <label class="radio-inline">
                                                    <input type="radio" value="1" name="bloglink" class="required" required />
                                                    <i class="subtopmenu hangmeas">Yes</i>
                                                </label> 
                                                <label class="radio-inline">
                                                    <input type="radio" value="0" name="bloglink" class="required" required checked />
                                                    <i class="subtopmenu hangmeas">No</i>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" value="2" name="bloglink" class="required" required />
                                                    <i class="subtopmenu hangmeas">None content</i>
                                                </label>    
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><img style="display: inline-block;width: 20px" border="0" src="https://lh3.googleusercontent.com/_WcqkU_bdb6J2kqbx2AKzMpJ0yyHaYZbCC5r2kXy9v8SzouzrNuRoHRpz227m2LeWcGMbeFfoxo2qGxMCIXmT2zDvRdcyGEu47-HahrTL8wrsFgNMgMVBMdqZOaLFzVZl6Mp72DF0tFw0FSmmcupvl-hf_KP9taHLFMrdDd2149ksooaiv-MIg0WC7f7XGkLoCTeOYrBm8y549yZ4d0b0pnNasO-CawKCCykBXJM5Gs_eiVR7xlbzhjr7RwPgETWHxosgBY4wCF6gQQLHVFhgbnmAVymwr27HW1aL_r_v6PFhHHYrMcSUFgywv2uh1hK7MDFWnchwH0hZBLm_v6VtBoYdbzSCcVeLwklkFl2NCxQdJgZh_-08Sh42UTKWpfeZdQlptIMHO7nw02A80BjHXZD1xMfkSUpo5VgW1n4DOeYo-vLbUG4bglGE0wJBrTCo6-GHqeW0qeSEtlHwWWuTKK6h1PT_hZt2L1SfI9kk_oaO-J8a26JyjMVQ9BDtftVRpYKdXby7ZDnM9mNbhD2JqhpGi_W8Y5694o5ZQO5H3KZiA2-PdS7uIgmdPdehYe3u8FC0CG7UAUBdVoU-5Mt7uEZg3D2PekaBtPJgZfqZI-oYIo4JWvmhlZwKTtYw1Z-PxP05VxPnzLgV8dJKTjhom4YsEAhZv1UunRtIFBgiDHEIw=s64-no" width="320" height="320" data-original-width="16" data-original-height="16" /> use User Agent:</label>
                                            <div class="col-md-8">
                                                <label class="radio-inline">
                                                    <input type="radio" value="1" name="useragent" class="required" required />
                                                    <i class="subtopmenu hangmeas">Yes</i>
                                                </label> 
                                                <label class="radio-inline">
                                                    <input type="radio" value="0" name="useragent" class="required" required checked />
                                                    <i class="subtopmenu hangmeas">No</i>
                                                </label>    
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><img style="display: inline-block;width: 20px" border="0" src="https://1.bp.blogspot.com/-JdCz7PtuHDQ/W-k3U3fmFrI/AAAAAAAAZ_w/Cw_UCq_WbCEFrrQAEOr6V6jEHDoMXmx9gCLcBGAs/s320/bitly-1-432498.png" width="320" height="320" data-original-width="16" data-original-height="16" /> បំព្រួញលីងគ៍<br/>Bitly Short URL?:</label>
                                            <div class="col-md-8">
                                                <label class="radio-inline">
                                                    <input type="radio" value="1" name="shortlink" class="required" required />
                                                    <i class="subtopmenu hangmeas">Yes</i>
                                                </label> 
                                                <label class="radio-inline">
                                                    <input type="radio" value="0" name="shortlink" class="required" required/>
                                                    <i class="subtopmenu hangmeas">No</i>
                                                </label>    
                                            </div>
                                        </div>

                                        <div class="form-group BitlySelect" style="display: none;">
                                            <label class="col-md-4 control-label">ផុសឆ្លាស់លីងគ៍<br/>Random link?:</label>
                                            <div class="col-md-8">
                                                <label class="radio-inline">
                                                    <input type="radio" value="1" name="randomlink" class="required" />
                                                    <i class="subtopmenu hangmeas">Yes</i>
                                                </label> 
                                                <label class="radio-inline">
                                                    <input type="radio" value="0" name="randomlink" class="required" checked="checked" />
                                                    <i class="subtopmenu hangmeas">No</i>
                                                </label>    
                                            </div>
                                        </div>

                                        <div class="form-group shareType" style="display: none;">
                                            <label class="col-md-4 control-label">លក្ខណៈស៊ែរ៍<br/>Share type:</label>
                                            <div class="col-md-8">
                                                <label class="radio-inline">
                                                    <input type="radio" value="image" name="sharetype" class="required" checked="checked" />
                                                    <i class="subtopmenu hangmeas">ស៊ែរ៍បែបរូបភាព</i>
                                                </label> 
                                                <label class="radio-inline">
                                                    <input type="radio" value="link" name="sharetype" class="required" disabled />
                                                    <i class="subtopmenu hangmeas">ស៊ែរ៍បែប Link</i>
                                                </label>    
                                            </div>
                                        </div>                                        

                                    </div>
                                </div>


                                        <div class="widget box">
                                    <div class="widget-header">
                                        <h4><i class="icon-reorder"></i> កំណត់នៃការប៉ុស្តិ៍ / Post Option:</h4>
                                    </div>
                                    <div class="widget-content">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label>អត្ថបទបន្ថែម ពីមុខ / Prefix</label>
                                                <textarea rows="1" cols="5" name="Prefix" class="form-control" placeholder="1234|1234|1234"></textarea>
                                                បើចង់ថែម ឬដាក់ថ្មី សូមដាក់ដូចខាងក្រោមៈ<br/>Ex: xxxx|xxxx|xxxx|xxxx
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label>អត្ថបទបន្ថែម ពីក្រោយ / Suffix</label>
                                                <textarea rows="1" cols="5" name="addtxt" class="form-control" placeholder="1234|1234|1234"></textarea>
                                                បើចង់ថែម ឬដាក់ថ្មី សូមដាក់ដូចខាងក្រោមៈ<br/>Ex: xxxx|xxxx|xxxx|xxxx

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Post type: </label>
                                            <div class="col-md-8">
                                                <label class="radio-inline">
                                                    <input type="radio" value="imacros" name="ptype" checked="checked" />
                                                    <i class="subtopmenu hangmeas">iMacros</i>
                                                </label> 
                                                <label class="radio-inline">
                                                    <input type="radio" value="api" name="ptype" />
                                                    <i class="subtopmenu hangmeas">API</i>
                                                </label>                                
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Status: </label>
                                            <div class="col-md-8">
                                                <label class="radio-inline">
                                                    <input type="radio" value="1" name="postType" checked="checked" />
                                                    <i class="subtopmenu hangmeas">Publish</i>
                                                </label> 
                                                <label class="radio-inline">
                                                    <input type="radio" value="2" name="postType" />
                                                    <i class="subtopmenu hangmeas">Draff</i>
                                                </label>                                 
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label">End: </label>
                                            <div class="col-md-8">
                                                <label class="radio-inline">
                                                    <input type="radio" value="1" name="looptype" class="required" />
                                                    <i class="subtopmenu hangmeas">Loop</i>
                                                </label> 
                                                <label class="radio-inline">
                                                    <input type="radio" value="0" name="looptype" class="required" checked/>
                                                    <i class="subtopmenu hangmeas">Once</i>
                                                </label>                                
                                            </div>
                                        </div>

                                    </div>
                                </div>   



                                <div class="widget box">
                                    <div class="widget-content">
                                        <label class="control-label">
                                            Post action:
                                        </label>
                                        <div class="col-md-12">
                                            <label class="radio-inline">
                                                <input type="radio" value="0" name="paction" checked="checked" />
                                                <i class="subtopmenu hangmeas">Post now:</i>
                                            </label> 
                                            <label class="radio-inline">
                                                <input type="radio" value="1" name="paction" />
                                                <i class="subtopmenu hangmeas">Schedule:</i>
                                            </label>
                                            <div id="postSchedule" style="display: none;"> 
                                                <div style="clear:both"></div>
                                                    <input type="text" value="<?php echo date("m-d-Y");?>" name="startDate" class="form-control " id="datepicker" size="10" placeholder="Start date"  style="float:left;margin-right:5px;height:25px; width:85px"/>
                                                    <input type="text" value="<?php echo date("h:i:s");?>" name="startTime" class="form-control " id="timepicker" size="10" placeholder="start time"  style="float:left;margin-right:5px;height:25px; width:85px"/>
                                                    <span style="float: left;margin-right: 5px;">to </span>  
                                                    <input type="text" name="endDate" class="form-control " id="datepickerEnd" size="10" placeholder="End date"  style="float:left;margin-right:5px;height:25px; width:85px"/>
                                                    <input type="text" name="endTime" class="form-control " id="timepickerEnd" size="10" placeholder="end time"  style="float:left;margin-right:5px;height:25px; width:85px"/>
                                                <div style="clear:both"></div>
                                                <label class="control-label">
                                                    Repeat:
                                                </label>
                                                <label class="radio"> 
                                                    <input type="radio" name="loop" value="m" id="everyMimute"/> 
                                                    <span style="float: left;margin-right: 5px;">Repeat every: </span> 
                                                    <input name="minuteNum" class="form-control input-width-mini" type="text" style="float:left;margin-right:5px;height:25px" value="120"/> minutes
                                                </label>
                                                <div style="clear:both"></div>
                                                <label class="radio"> 
                                                    <input type="radio" name="loop" value="h" id="everyHour"/> 
                                                    <span style="float: left;margin-right: 5px;">Repeat every: </span> 
                                                    <input name="hourNum" class="form-control input-width-mini" type="text" style="float:left;margin-right:5px;height:25px" value="1"/> hour
                                                </label>
                                                <div style="clear:both"></div>
                                                <label class="radio"> 
                                                    <input type="radio" name="loop" value="d" id="everyDay" checked="checked"/> 
                                                    <span style="float: left;margin-right: 5px;">Repeat every: </span> 
                                                    <input name="dayNum" class="form-control input-width-mini" type="text" style="float:left;margin-right:5px;height:25px" value="1"/> day
                                                </label>
                                                <div style="clear:both"></div>
                                                <label class="control-label">
                                                    Repeat on:
                                                </label>
                                                <div class="col-md-12">
                                                    <label class="checkbox-inline"> 
                                                        <input type="checkbox" class="uniform" value="Sun" name="loopDay[]"/>
                                                        S 
                                                    </label> 
                                                    <label class="checkbox-inline"> 
                                                        <input type="checkbox" class="uniform" value="Mon" name="loopDay[]"/>
                                                        M 
                                                    </label>
                                                    <label class="checkbox-inline"> 
                                                        <input type="checkbox" class="uniform" value="Tue" name="loopDay[]"/>
                                                        T 
                                                    </label>
                                                    <label class="checkbox-inline"> 
                                                        <input type="checkbox" class="uniform" value="Wed" name="loopDay[]"/>
                                                        W 
                                                    </label>
                                                    <label class="checkbox-inline"> 
                                                        <input type="checkbox" class="uniform" value="Thu" name="loopDay[]"/>
                                                        T
                                                    </label>
                                                    <label class="checkbox-inline"> 
                                                        <input type="checkbox" class="uniform" value="Fri" name="loopDay[]"/>
                                                        F
                                                    </label>
                                                    <label class="checkbox-inline"> 
                                                        <input type="checkbox" class="uniform" value="Sat" name="loopDay[]"/>
                                                        S
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="clear:both"></div>
                                    </div>
                                </div>


                                <div class="widget box">
                                    <div class="widget-header">
                                        <h4><i class="icon-reorder"></i> Post delay:</h4>
                                    </div>
                                    <div class="widget-content">
                                        <div class="form-group">
                                             <label class="col-md-4 control-label">ក្នុង១ក្រុមត្រូវរង់ចាំ<br/>each group waiting: </label>
                                            <div class="col-md-8">
                                                <label class="radio"> 
                                                    <input 
                                                        class="form-control input-width-mini" 
                                                        type="number" 
                                                        style="float:left;margin-right:5px;" 
                                                        value="180"
                                                        name="pause"
                                                   /> វិនាទី/seconds [recommended value: 60 seconds]
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label">ក្នុង១ប៉ុស្តិ៍ត្រូវរង់ចាំ<br/>Next Post waiting: </label>
                                            <div class="col-md-8">
                                                <label class="radio"> 
                                                    <select name="ppause" class="select2" style="width: 60px">
                                                            <option value="5" >5</option>
                                                            <option value="10" selected>10</option>
                                                            <option value="15">15</option>
                                                            <option value="20">20</option>
                                                            <option value="25">25</option>
                                                            <option value="30">30</option>
                                                            <option value="35">35</option>
                                                            <option value="40">40</option>
                                                            <option value="45">45</option>
                                                            <option value="50">50</option>
                                                            <option value="55">55</option>
                                                            <option value="60">60</option>
                                                    </select> នាទី/Minutes  [recommended value: 10 Minutes]
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label">ផុសបែបឆ្លាស់<br/>Random Post?:</label>
                                            <div class="col-md-8">
                                                <label class="radio-inline">
                                                    <input type="radio" value="1" name="random" class="required" />
                                                    <i class="subtopmenu hangmeas">Yes</i>
                                                </label> 
                                                <label class="radio-inline">
                                                    <input type="radio" value="0" name="random" class="required" checked="checked" />
                                                    <i class="subtopmenu hangmeas">No</i>
                                                </label>    
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                
                                    
                            </div>                                
                        </div>

                        <div class="form-group fixed">
                            <div class="col-md-12">
                                <input name="submit" type="submit" value="Public Content" class="btn btn-primary pull-right" />
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function () {
            $('input[name=paction]').click(function () {
                if($(this).val() == 0) {
                    $('#postSchedule').slideUp();
                }
                if($(this).val() == 1) {
                    $('#postSchedule').slideDown();
                }
            });

            $('input[name=ptype]').click(function () {
                if($(this).val() == 'imacros') {
                    $('#postimacros').slideDown();
                    $('#postapi').slideUp();
                }
                if($(this).val() == 'api') {                    
                    $('#postimacros').slideUp();
                    $('#postapi').slideDown();
                }
            });

            /*BitLy option*/
            $('input[name=shortlink]').click(function () {
                if($(this).val() == 1) {
                    $('.BitlySelect').slideDown();
                }
                if($(this).val() == 0) {                    
                    $('.BitlySelect').slideUp();
                }
            });
            $('input[name=randomlink]').click(function () {
                if($(this).val() == 1) {
                    $('.shareType').slideDown();
                }
                if($(this).val() == 0) {                    
                    $('.shareType').slideUp();
                }
            });
            /*End BitLy option*/

            /*Youtube channel*/
            $("#getyt").click(function() {
                $("#blockuis").show();
                var ytid = $("input[name=ytid]").val();
                var ytmax = $("select[name=ytmax]").val();
                $.ajax
                ({
                    type: "get",
                    url: "<?php echo base_url ();?>managecampaigns/ajax?gid="+ytid+"&p=ytid&max="+ytmax,
                    dataType: 'json',
                    success: function(data)
                    {
                        $('#postimacros').html('');
                         $.each(data, function(index, element) {
                            $("#blockuis").hide();
                            var dataYt = '<div class="form-group morefield"><div class="col-md-12"><div class="form-group"><div class="col-md-4"><input type="text" value="'+element.vid+'" class="form-control post-option" name="link[]" placeholder="Youtube URL or ID" id="link_'+element.vid+'" onchange="getLink(this);" /><p><span class="help-bloc">'+element.viewCount+'</span> Views</p><p><span>published:</span> <span class="help-bloc">'+element.publishedAt+' </span></p></div><div class="col-md-3"><img src="https://i.ytimg.com/vi/'+element.vid+'/hqdefault.jpg" style="width:120px"/><input type="hidden" id="img_'+element.vid+'" value="'+element.picture+'" class="form-control post-option" name="thumb[]" placeholder="Image url" /></div><div class="col-md-5"><div class="input-group"><input type="text" value="'+element.title+'" class="form-control post-option" name="title[]" placeholder="Title" id="title_'+element.title+'" /><span class="input-group-btn"><button class="btn btn-default removediv bs-tooltip" data-original-title="Remove this" type="button"><i class="icon-remove text-danger"></i></button></span></div></div></div></div></div>';
                            $('#postimacros').append(dataYt);
                        });
                        $('.bs-tooltip').tooltip();
                    },
                    timeout: 6000
                });
            });
            /*End Youtube channel*/

            /*add field*/
             $("#addfield").click(function() {
                var code = makeid();
                var link = 'link_' + code;
                var title = 'title_link_' + code;
                var name = 'name_link_' + code;
                var description = 'description_link_' + code;
                var image = 'image_link_' + code;
                var image_show = 'show_link_' + code;
              $('.morefield:last').after('<div class="form-group morefield"><div class="col-md-12"><div class="form-group"><div class="col-md-4"><input type="text" value="" class="form-control post-option" name="link[]" placeholder="Youtube URL or ID" id="'+link+'" onchange="getLink(this);" /></div><div class="col-md-3"><img id="'+image_show+'" src="https://i.ytimg.com/vi/0000/0.jpg" style="width:120px"/><input type="hidden" id="'+image+'" value="" class="form-control post-option" name="thumb[]" placeholder="Image url" /></div><div class="col-md-5"><div class="input-group"><input type="text" value="" class="form-control post-option" name="title[]" placeholder="Title" id="'+title+'" /><input type="hidden" id="'+name+'" value="" class="form-control post-option" name="name[]" /><span class="input-group-btn"><button class="btn btn-default removediv bs-tooltip" data-original-title="Remove this" type="button"><i class="icon-remove text-danger"></i></button></span></div><textarea name="conents[]" id="'+description+'" class="form-control post-option" style="height: 58px"></textarea></div></div></div></div>');
                $('.bs-tooltip').tooltip();
                //var count = $(".listofsong").length;
                //$("#countdiv").text("ចំនួន " + count + " បទ");
            })           
            /*End add field*/

            /*remove field*/
             $('.optionBox').on('click','.removediv',function() {
              $(this).parent().parent().parent().parent().parent().parent().remove();
            });
            /*End remove field*/
        });
        function makeid() {
          var text = "";
          var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

          for (var i = 0; i < 5; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

          return text;
        }
        function getLink(e) {
            $("#blockuis").show();
            var id = $(e).attr('id');
            if(e!='') {
                var jqxhr = $.ajax( "<?php echo base_url();?>managecampaigns/get_from_url?url=" + $(e).val())
                  .done(function(data) {
                    if ( data ) {
                        $("#blockuis").hide();
                        var obj = JSON.parse(data);
                      $('#title_' + id).val(obj.name);
                      $('#name_' + id).val(obj.name);
                      $('#description_' + id).val(obj.description);
                      $('#image_' + id).val(obj.picture);
                      $('#show_' + id).attr("src",obj.picture);
                    }
                  })
                  .fail(function() {
                    alert( "error" );
                  })
                  .always(function() {
                    //alert( "complete" );
                  });
            }
        }
        function getattra(e) {
            $("#singerimageFist").val(e);
            $("#imageviewFist").html('<img style="width:100%;height:55px;" src="' + e + '"/>');
        }
    </script>

    <?php
 else:
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
endif;
?>