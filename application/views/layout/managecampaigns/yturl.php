<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/bootstrap-wysihtml5/wysihtml5.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/blockui/jquery.blockUI.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/jquery.form.js"></script>

<script src="<?php echo base_url(); ?>themes/layout/blueone/plugins/crop-upload/js/jquery.Jcrop.js"></script>
<script src="<?php echo base_url(); ?>themes/layout/blueone/plugins/crop-upload/js/script.js"></script>
<link href="<?php echo base_url(); ?>themes/layout/blueone/plugins/crop-upload/css/main.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>themes/layout/blueone/plugins/crop-upload/css/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" /> 

<!-- watermaker -->
<link rel="StyleSheet" href="<?php echo base_url(); ?>themes/layout/blueone/plugins/watermarker/watermarker.css" type="text/css">
<script src="<?php echo base_url(); ?>themes/layout/blueone/plugins/watermarker/watermarker.js"></script>
<script src="<?php echo base_url(); ?>themes/layout/blueone/plugins/watermarker/script.js"></script>

<!-- End watermaker -->
<link href="https://fonts.googleapis.com/css?family=Battambang" rel="stylesheet">
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
        .wysihtml5-action-active{color: #1e88e5!important}
        .btn.wysihtml5-action-active {background-color:#fff!important;}
        .btn.disabled {color: #D1D1D1!important;}
        .wysihtml5-toolbar {margin-top: 3px!important}
        ul.wysihtml5-toolbar > li{margin:0px 5px -1px 0px!important}
        .khmer {font-family: 'Battambang';font-size: 14px!important;font-weight: 400!important;}
        .counts{color:#ff0000;}

        /*upload file*/
        .filecontainer{
    margin: 0;
}

#postimacros label{
    display: block;
    max-width: 200px;
    margin: 0 auto 15px;
    text-align: center;
    word-wrap: break-word;
    color: #1a4756;
}
#postimacros .addmorefield label{text-align: left;margin: -5px 0;}
#postimacros .hidden, #uploadImg:not(.hidden) + label{
    display: none;
}

#postimacros .file{
    display: none;
    margin: 0 auto;
}

#postimacros .upload{
    margin-left: 5px;
    width: 150px;
    display: block;
    padding: 2px 5px;
    border: 0;
    font-size: 15px;
    letter-spacing: 0.05em;
    cursor: pointer;
    background: #216e69;
    color: #fff;
    outline: none;
    transition: .3s ease-in-out;
    &:hover, &:focus{
        background: #1AA39A;
    }
    &:active{
        background: #13D4C8;
        transition: .1s ease-in-out;
    }

}

#postimacros img{
    display: block;
    margin:0;
}
        /*End upload file*/
/*watermark*/
.water-wrap {max-height: 350px;overflow-y: auto;}
/*End watermark*/
 /*updad with crop*/   
 .modal-dialog {
    z-index: 1050;
    width: auto!important;
    padding: 10px;
    margin-right: auto;
    margin-left: auto;
}
.ui-widget-overlay {
  opacity: 0.80;
  filter: alpha(opacity=70);
}
.jc-dialog {
  padding-top: 1em;
}
.ui-dialog p tt {
  color: yellow;
}
.jcrop-light .jcrop-selection {
  -moz-box-shadow: 0px 0px 15px #999;
  /* Firefox */

  -webkit-box-shadow: 0px 0px 15px #999;
  /* Safari, Chrome */

  box-shadow: 0px 0px 15px #999;
  /* CSS3 */

}
.jcrop-dark .jcrop-selection {
  -moz-box-shadow: 0px 0px 15px #000;
  /* Firefox */

  -webkit-box-shadow: 0px 0px 15px #000;
  /* Safari, Chrome */

  box-shadow: 0px 0px 15px #000;
  /* CSS3 */

}
.jcrop-fancy .jcrop-handle.ord-e {
  -webkit-border-top-left-radius: 0px;
  -webkit-border-bottom-left-radius: 0px;
}
.jcrop-fancy .jcrop-handle.ord-w {
  -webkit-border-top-right-radius: 0px;
  -webkit-border-bottom-right-radius: 0px;
}
.jcrop-fancy .jcrop-handle.ord-nw {
  -webkit-border-bottom-right-radius: 0px;
}
.jcrop-fancy .jcrop-handle.ord-ne {
  -webkit-border-bottom-left-radius: 0px;
}
.jcrop-fancy .jcrop-handle.ord-sw {
  -webkit-border-top-right-radius: 0px;
}
.jcrop-fancy .jcrop-handle.ord-se {
  -webkit-border-top-left-radius: 0px;
}
.jcrop-fancy .jcrop-handle.ord-s {
  -webkit-border-top-left-radius: 0px;
  -webkit-border-top-right-radius: 0px;
}
.jcrop-fancy .jcrop-handle.ord-n {
  -webkit-border-bottom-left-radius: 0px;
  -webkit-border-bottom-right-radius: 0px;
}
.description {
  margin: 16px 0;
}
.jcrop-droptarget canvas {
  background-color: #f0f0f0;
}  
.tooltip {font-family: 'Battambang';font-size: 14px!important;font-weight: 400!important;} 
 /*End updad with crop*/       
        .removediv{
            top: 0px;
            right: -30px;
            width: 32px;
        }
        .widgets-refresh {
            top: 37px;
            right: -61px;
            width: 32px;
        }
    .imgwrap{position: relative;}
    .imgwrap .btn{position: absolute;top:0;right: 0}

    /*watermark*/
    .watermarker-wrapper .watermarker-container .resizer{background-image: url("<?php echo base_url();?>uploads/image/watermark/watermark/resize.png");}
    .icon {width: 25px}
    .icon-choose {height: 50px;cursor: pointer;border: 1px solid white;float: left;padding: 5px;}
    .icon-choose:hover {border: 1px solid red}
    .water-wrap {margin: 10px 5px 5px 10px;border: 1px solid #eee;padding: 3px}
    fieldset {padding: 10px;border: 1px solid #ddd;}
    fieldset legend{font-size: 100%;border:none;margin-bottom: 0px;font-weight: bold;width: inherit;}
    /*End watermark*/
    </style>

    <link rel="StyleSheet" href="<?php echo base_url(); ?>themes/layout/blueone/plugins/image-filter/jquery-ui.css" type="text/css">
    <script src="<?php echo base_url(); ?>themes/layout/blueone/plugins/image-filter/caman.full.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>themes/layout/blueone/plugins/image-filter/jquery-ui.min.js" type="text/javascript"></script>
    <div class="page-header">
    </div>
    <div class="row">
        <form method="post" id="validate" class="form-horizontal row-border" enctype="multipart/form-data">
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
                                <div class="widget box">
                                    <div class="widget-content"> 
                                        <div class="row-border">
                                            <div class="form-group">
                                                <div class="col-md-9">
                                                    <input type="text" name="ytid" class="form-control" placeholder="Get from Youtube Channel ID">
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group ytid">               
                                                        <select name="ytmax" class="select2" style="width: 60px">
                                                                <option value="5" >5</option>
                                                                <option value="10">10</option>
                                                                <option value="15" selected>15</option>
                                                                <option value="20">20</option>
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
                                        </div>
                                    </div> 
                                </div>

                                <div id="postimacros"​ class="morefield">
                                    <div class="widget box optionBox" id="post_1" data-postid="1">
                                        <div class="widget-header">                                             
                                            <h4><i class="icon-reorder"></i> Post <span class="counts">1</span></h4>
                                            <div class="toolbar no-padding"> 
                                                <div class="btn-group">
                                                    <span class="btn btn-xs btn-inverse widgets-refresh" onclick="getcontent('1');"><i class="icon-refresh"></i></span> 
                                                    <button class="btn btn-xs removediv bs-tooltip" data-original-title="Remove this" type="button" onclick="removediv('1');" <?php echo ($post_id) ? 'disabled':'';?>>
                                                                            <i class="icon-remove text-danger"></i>
                                                                        </button>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="widget-content"> 
                                            <div class="row-border">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                      <div class="form-group"> 
                                                        <div class="col-md-12">
                                                            <table style="width: 100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="form-group" style="margin-bottom: 3px">
                                                                                <div class="col-md-12"> 
                                                                                    <div class="input-group"> 
                                                                                        <span class="input-group-addon"><i style="color: red" class="icon-youtube-sign"></i></span> <input type="text" id="link_1" value="<?php echo @$postLink; ?>" class="form-control post-option" name="link[]" placeholder="URL" onchange="getLink(this);" /> 
                                                                                    </div>
                                                                                
                                                                                <input type="hidden" value="<?php echo @$post_id; ?>" name="postid" id="postID"/>
                                                                                <input type="hidden" value="" name="vid[]" id="vid_link_1"/>
                                                                                </div>
                                                                            </div>                                                   
                                                                            <div class="form-group" style="border: none;margin-bottom: 3px">
                                                                                <label class="col-md-3 khmer">ចំណងជើងប្លុក</label>
                                                                                <div class="col-md-9">
                                                                                    <input type="text" value="<?php echo @$postTitle; ?>" class="form-control post-option" name="title[]" placeholder="Title" id="title_link_1" />
                                                                                </div>   
                                                                            </div>
                                                                            <div class="form-group" style="border: none">
                                                                                <label class="col-md-3 khmer">ចំណងជើងស៊ែរ៍</label>
                                                                                <div class="col-md-9">
                                                                                    <input type="text" id="name_link_1" value="" class="form-control post-option" name="name[]" />
                                                                                </div>   
                                                                            </div>
                                                                        </td>
                                                                        <td style="width: 150px;" data-id="1">
                                                                            <div class="imgwrap">
                                                                                <img id="show_link_1" src="https://i.ytimg.com/vi/0000/0.jpg" style="width:150px;margin-left: 5px;height:85px;border: 1px solid #CCC;"/>
                                                                                <a href="javascript:;" class="btn btn-xs btn-inverse" title="Edit Image" onclick="getEitImage(this);"><i class="glyphicon glyphicon-pencil"></i></a>
                                                                            </div>
                                                                            <button type="button" class="btn btn-xs btn-primary pull-right" style="margin-left: 5px;width: 150px" onclick="getImage(this);">
                                                                             <span class="khmer">ដូររូបភាព</span>
                                                                            </button>
                                                                            <!-- <div class="filecontainer" id="img_1" data-id='1'>
                                                                                <div class="input">
                                                                                    <input name="upload[]" type="file"  class="file" onchange="inputFile(this);" />
                                                                                    <input value="ប្ដូរូបភាព" class="upload khmer" type="button" onclick="getImage(this);">
                                                                                </div>
                                                                            </div> -->
                                                                            <input type="hidden" id="image_link_1" value="<?php echo @$Thumbnail; ?>" class="form-control post-option" name="thumb[]" placeholder="Image url" /> 
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            
                                                        </div>
                                                      </div>
                                                      <div class="form-group" style="border: none">
                                                        <div class="col-md-12">
                                                          <textarea name="conents[]" id="description_link_1" class="form-control post-option wysiwygs" style="height: 58px"></textarea>
                                                      </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                            </div>
                                            <div class="addmorefield" style="">
                                                    <div class="form-group" style="border: none">
                                                        <div class="col-md-3 khmer">
                                                            <fieldset>
                                                                <legend>យកទិន្នន័យពី:</legend>
                                                                <label class="radio-inline" style="display: inline-block;margin-top: -3px">
                                                                    <input value="1" name="fromoldlink[]" type="checkbox">
                                                                    <i class="subtopmenu hangmeas khmer">មិនផុសប្លុកដើម</i>
                                                                </label>
                                                            </fieldset>
                                                            <fieldset>
                                                                <legend>Post to Blog link:</legend>
                                                                <label class="radio-inline" style="display: inline-block;">
                                                                    <input value="1" name="setbloglink[]" type="checkbox">
                                                                    <i class="subtopmenu hangmeas khmer">ប្រើប្លុកលីងគ៍?</i>
                                                                </label>
                                                            </fieldset>
                                                        </div>
                                                       
                                                        <div class="col-md-3">
                                                            <fieldset>
                                                                <legend>Post Type:</legend>
                                                                <label class="radio">
                                                                    <input type="radio" value="1" name="smpoststyle1" class="uniform smpoststyle" />
                                                                    <i class="subtopmenu hangmeas">Youtube</i>
                                                                </label> 
                                                                <label class="radio">
                                                                    <input type="radio" value="2" name="smpoststyle1" class="uniform smpoststyle" />
                                                                    <i class="subtopmenu hangmeas">With Player</i>
                                                                </label>
                                                                <label class="radio">
                                                                    <input type="radio" value="link" name="smpoststyle1" class="uniform smpoststyle" />
                                                                    <i class="subtopmenu hangmeas">Link</i>
                                                                </label>  
                                                                <label class="radio khmer">
                                                                    <input type="radio" value="tnews" name="smpoststyle1" class="uniform smpoststyle" />
                                                                    <i class="subtopmenu hangmeas">ព័ត៌មាន / News</i>
                                                                </label> 
                                                            </fieldset>
                                                        </div>
                                                         <div class="col-md-6">
                                                            <fieldset>
                                                                <legend>ថែមអត្ថបទពីក្រោយ:</legend>
                                                                    <label class="khmer" style="max-width: 100%;width: 100%">
                                                                    <textarea style="max-width: 100%;width: 100%;height: 34px" rows="1" cols="5" name="saddtxt[]" class="form-control setPrefix" placeholder="1234|1234|1234"></textarea></label>
                                                            </fieldset>
                                                            <fieldset>
                                                                <legend>ប្រភេទ / Label:</legend>
                                                                    <label class="khmer" style="max-width: 100%;width: 100%">
                                                                    <input style="max-width: 100%;width: 100%;" name="label[]" class="form-control set_balel" placeholder="News, Thai Lottery" type="text"></label>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>

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

                                <div class="form-group">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-5"></div>                                    
                                  <div class="col-md-1">
                                    <span id="addfield1" class="addfield btn btn-success  pull-right bs-tooltip <?php echo ($post_id) ? 'disabled':'';?>" data-original-title="Add more..."><i class="icon-plus"></i></span>
                                  </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="widget box">
                                    <div class="widget-content">
                                        <div class="form-group chekimg">
                                            <div class="col-md-8">
                                                <label class="radio-inline">
                                                    <input type="checkbox" value="1" name="foldlink" />
                                                    <i class="subtopmenu hangmeas khmer">From lld link / យកពីប្លុកចាស់?</i>
                                                </label>   
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <span class="label label-primary khmer" style="font-size: 16px"><label><input type="checkbox" value="" id="getDataAll"/> យកតាមជម្រើសចាស់  </label></span>
                                            </div>
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
                                        <h4><i class="icon-reorder"></i> <span class="khmer">កំណត់នៃការស៊ែរ៍ </span>/ Share Option:</h4>
                                    </div>
                                    <div class="widget-content">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label khmer">ម៉ូដក្នុងប៉ុស្ដិ៍ប្លុកដើម<br/>Content type?:</label>
                                            <div class="col-md-8">
                                                <label class="radio-inline">
                                                    <input type="radio" value="1" name="mpoststyle" class="required" required checked />
                                                    <i class="subtopmenu hangmeas">Youtube</i>
                                                </label> 
                                                <label class="radio-inline">
                                                    <input type="radio" value="2" name="mpoststyle" class="required" required />
                                                    <i class="subtopmenu hangmeas">With Player</i>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" value="link" name="mpoststyle" class="required" required />
                                                    <i class="subtopmenu hangmeas">Link</i>
                                                </label>  
                                                <label class="radio-inline khmer">
                                                    <input type="radio" value="tnews" name="mpoststyle" class="required" required />
                                                    <i class="subtopmenu hangmeas">ព័ត៌មាន / News</i>
                                                </label>  
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label khmer">ប្រើប្លុកលីងគ៍<br/>with Blog link?:</label>
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
                                                    <i class="subtopmenu hangmeas">With Player</i>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" value="link" name="bloglink" class="required" required />
                                                    <i class="subtopmenu hangmeas">Link</i>
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
                                            <label class="col-md-4 control-label khmer"><img style="display: inline-block;width: 20px" border="0" src="https://1.bp.blogspot.com/-JdCz7PtuHDQ/W-k3U3fmFrI/AAAAAAAAZ_w/Cw_UCq_WbCEFrrQAEOr6V6jEHDoMXmx9gCLcBGAs/s320/bitly-1-432498.png" width="320" height="320" data-original-width="16" data-original-height="16" /> បំព្រួញលីងគ៍<br/>Bitly Short URL?:</label>
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
                                            <label class="col-md-4 control-label khmer">ផុសឆ្លាស់លីងគ៍<br/>Random link?:</label>
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
                                            <label class="col-md-4 control-label khmer">លក្ខណៈស៊ែរ៍<br/>Share type:</label>
                                            <div class="col-md-8">
                                                <label class="radio-inline">
                                                    <input type="radio" value="image" name="sharetype" class="required" checked="checked" />
                                                    <i class="subtopmenu hangmeas khmer">ស៊ែរ៍បែបរូបភាព</i>
                                                </label> 
                                                <label class="radio-inline">
                                                    <input type="radio" value="link" name="sharetype" class="required" disabled />
                                                    <i class="subtopmenu hangmeas khmer">ស៊ែរ៍បែប Link</i>
                                                </label>    
                                            </div>
                                        </div>

                                        <div class="form-group chekimg">
                                            <div class="col-md-12">
                                                <label class="radio-inline">
                                                    <i class="icon-sun" style="color:red"></i>
                                                    <i class="subtopmenu hangmeas khmer">Brightness level / កែពន្លឺនៃរូបភាព</i>
                                                </label>   
                                            </div>
                                            <div class="col-md-12">
                                                <label class="radio"> 
                                                    <input type="checkbox" name="filter_brightness" style="float:left" value="1" checked /> 
                                                    <span style="float: left;margin-right: 5px;margin-left: 5px"> Randomly: </span> 
                                                    <input name="brandom" class="form-control input-width-mini" type="text" style="float:left;margin-right:5px;height:25px" value="10,100" readonly/> level
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group chekimg">
                                            <div class="col-md-12">
                                                <label class="radio-inline">
                                                    <i class="icon-adjust" style="color:blue"></i> 
                                                    <i class="subtopmenu hangmeas khmer">Contrast level / កែភាពច្បាស់នៃរូបភាព</i>
                                                </label>
                                                <label class="radio"> 
                                                    <input type="checkbox" name="filter_contrast" style="float:left" value="1" checked /> 
                                                    <span style="float: left;margin-right: 5px;margin-left: 5px"> Randomly: </span> 
                                                    <input name="crandom" class="form-control input-width-mini" type="text" style="float:left;margin-right:5px;height:25px" value="10,100" readonly /> level
                                                </label>

                                            </div>
                                        </div>
                                        <div class="form-group chekimg">
                                            <div class="col-md-12">
                                                <label class="radio-inline">
                                                    <i class="icon-repeat"></i> 
                                                    <input type="checkbox" value="1" name="img_rotate" checked />
                                                    <i class="subtopmenu hangmeas khmer">Image Rotate / បង្វិលរូបភាព</i>
                                                </label>   
                                            </div>
                                        </div>

                                        <div class="form-group chekimg">
                                            <div class="col-md-12">
                                                <label class="radio-inline">
                                                    <input type="checkbox" value="1" name="cimg" <?php if(!empty($data)){ echo ($pSchedule['check_image']=='1' ? 'checked' : '');}?>/>
                                                    <i class="subtopmenu hangmeas khmer">Not check imge / មិនឆែករូបភាពមុនប៉ុស្តិ៍?</i>
                                                </label>   
                                            </div>
                                        </div>
                                        <div class="form-group btnplayer">
                                            <div class="col-md-12">
                                                <label class="radio-inline">
                                                    <input type="checkbox" value="1" name="btnplayer" <?php if(!empty($data)){ echo ($pSchedule['btnplayer']=='play-button-overlay.png' ? 'checked' : '');}?>/>
                                                    <i class="subtopmenu hangmeas khmer">Button Player / មានប៉ូតុង?</i>
                                                </label>   
                                            </div>
                                        </div>
                                        <div class="form-group btnplayer">
                                            <div class="col-md-12">
                                                <label class="radio-inline">
                                                    <input type="checkbox" value="1" name="playerstyle" />
                                                    <i class="subtopmenu hangmeas khmer">Player style/ មានក្បាលចាក់?</i>
                                                </label>   
                                            </div>
                                        </div>
                                        <div class="form-group imagecolor">
                                            <div class="col-md-12">
                                                <label class="radio-inline">
                                                    <input type="checkbox" value="1" name="imgcolor" <?php if(!empty($data)){ echo ($pSchedule['btnplayer']=='play-button-overlay.png' ? 'checked' : '');}?>/>
                                                    <i class="subtopmenu hangmeas khmer">Random Image color / ដាក់បន្ថែមពណ៌?</i>
                                                </label>   
                                            </div>
                                        </div>

                                        <div class="form-group likeshare">
                                            <div class="col-md-12">
                                                <label class="radio-inline">
                                                    <input type="checkbox" value="1" name="txtlike" checked />
                                                    <i class="subtopmenu hangmeas khmer">Share & Like text / អត្ថបទចុច Share & Like?</i>
                                                </label>   
                                            </div>
                                        </div>
                                        <div class="form-group txtadd">
                                            <div class="col-md-8">
                                                <label class="radio-inline">
                                                    <input type="checkbox" value="1" name="txtadd" checked />
                                                    <i class="subtopmenu hangmeas khmer">Addition text / ថែមអត្ថបទ?</i>
                                                </label>   
                                            </div>
                                        </div>                                       

                                    </div>
                                </div>


                                        <div class="widget box">
                                    <div class="widget-header">
                                        <h4><i class="icon-reorder"></i> <span class="khmer">កំណត់នៃការប៉ុស្តិ៍ </span>/ Post Option:</h4>
                                    </div>
                                    <div class="widget-content">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label class="khmer">អត្ថបទបន្ថែម ពីមុខ / Prefix <input style="inline-block" type="checkbox" value="imacros" name="pprefix" checked /></label>
                                                <textarea rows="1" cols="5" name="Prefix" class="form-control" placeholder="1234|1234|1234"></textarea>
                                                <span class="khmer">បើចង់ថែម ឬដាក់ថ្មី សូមដាក់ដូចខាងក្រោមៈ</span><br/>Ex: xxxx|xxxx|xxxx|xxxx
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label class="khmer">អត្ថបទបន្ថែម ពីក្រោយ / Suffix <input style="inline-block" type="checkbox" value="imacros" name="psuffix" checked /></label>
                                                <textarea rows="1" cols="5" name="addtxt" class="form-control" placeholder="1234|1234|1234"></textarea>
                                               <span class="khmer"> បើចង់ថែម ឬដាក់ថ្មី សូមដាក់ដូចខាងក្រោមៈ</span><br/>Ex: xxxx|xxxx|xxxx|xxxx

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
                                                <label class="radio khmer"> 
                                                    <input 
                                                        class="form-control input-width-mini" 
                                                        type="number" 
                                                        style="float:left;margin-right:5px;" 
                                                        value="30"
                                                        name="pause"
                                                   /> វិនាទី/seconds [recommended value: 60 seconds]
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label khmer">ក្នុង១ប៉ុស្តិ៍ត្រូវរង់ចាំ<br/>Next Post waiting: </label>
                                            <div class="col-md-8">
                                                <label class="radio khmer"> 
                                                    <select name="ppause" class="select2" style="width: 60px" id="ppause">
                                                        <option value="1" >1</option>
                                                        <option value="2" >2</option>
                                                        <option value="3" >3</option>
                                                        <option value="4" >4</option>
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
                                            <label class="col-md-4 control-label khmer">ផុសបែបឆ្លាស់<br/>Random Post?:</label>
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
                            <div class="col-md-1">
                                <span id="addfield1" class="addfield btn btn-success bs-tooltip <?php echo ($post_id) ? 'disabled':'';?>" data-original-title="Add more..."><i class="icon-plus"></i></span>
                            </div>
                            <div class="col-md-5">
                            </div>
                            <div class="col-md-4">
                                <label class="radio-inline">
                                    <input value="1" name="post_by_manaul" type="checkbox">
                                    <i class="subtopmenu hangmeas khmer">Post and get code / ប៉ុស្តិ៍យកកូដផុសដៃ?</i>
                                </label>
                                <label class="radio-inline">
                                    <input value="1" name="post_all" type="checkbox">
                                    <i class="subtopmenu hangmeas khmer">Publish all Post / ប៉ុស្តិ៍ទាំងអស់?</i>
                                </label>
                            </div>
                            <div class="col-md-2">
                                <input name="submit" type="submit" value="Public Content" class="btn btn-primary pull-right" />
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>  
        var num = [];
        var Apps = function () {
            return {
                init: function () {
                    v();
                    t();
                    u();
                    f();
                    d();
                    h();
                    e();
                    i();
                    k();
                    j();
                    a();
                    p()
                },
                getLayoutColorCode: function (x) {
                    if (m[x]) {
                        return m[x]
                    } else {
                        return ""
                    }
                },
                blockUI: function (x, y) {
                    var x = $(x);
                    x.block({
                        message: '<img src="<?php echo base_url(); ?>themes/layout/blueone/assets/img/ajax-loading.gif" alt="">',
                        centerY: y != undefined ? y : true,
                        css: {
                            top: "10%",
                            border: "none",
                            padding: "2px",
                            backgroundColor: "none"
                        },
                        overlayCSS: {
                            backgroundColor: "#000",
                            opacity: 0.05,
                            cursor: "wait"
                        }
                    })
                },
                unblockUI: function (x) {
                    $(x).unblock({
                        onUnblock: function () {
                            $(x).removeAttr("style")
                        }
                    })
                }
            }
        }();   

        $(document).ready(function () {
            var access_token_time = "<?php echo date("F j, Y G:i:s",$access_token_time);?>";
            var token_time = new Date(access_token_time).getTime();
            var myVar = setInterval(function() { 
                var timeNow = new Date().getTime();
                if(timeNow > token_time) { 
                    //$('#cropModal').modal('show');
                }    
                //closeOnLoad("<?php echo base_url();?>managecampaigns");
            }, 500);
            $(".wysiwygs").wysihtml5({
                "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
                "emphasis": true, //Italics, bold, etc. Default true
                "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true                
                "link": true, //Button to insert a link. Default true
                "image": true, //Button to insert an image. Default true,
                "color": false, //Button to change color of font  
                "blockquote": false,
                "html": true, //Button which allows you to edit the generated HTML. Default false
            });

            $('#getDataAll').click(function () {
                if(this.checked) {
                    $.ajax({
                        url: '<?php echo base_url();?>uploads/<?php echo $this->session->userdata ('user_id');?>/<?php echo $this->session->userdata('fb_user_id');?>_tmp_action.json?<?php echo strtotime("now");?>',
                        dataType: 'json',
                        async: false,
                        success: function(json) {
                            if(json) {
                                if(json.blogid != '') {
                                    /*set blog id*/
                                   $("#blogpost").val(json.blogid); 
                                   var blogpostxt = $( "#blogpost option:selected" ).text();
                                   $("#s2id_blogpost").find(".select2-chosen").html(blogpostxt);
                                }
                                if(json.account_group_type != '') {
                                    $("#togroup").val(json.account_group_type); 
                                    var togrouptxt = $( "#togroup option:selected" ).text();
                                   $("#s2id_togroup").find(".select2-chosen").html(togrouptxt);
                                    /*set group id*/
                                   $.ajax
                                    ({
                                        type: "get",
                                        url: "<?php echo base_url ();?>managecampaigns/ajax?gid="+json.account_group_type+'&p=getgrouptype',
                                        cache: false,
                                        success: function(html)
                                        {
                                            $('#groupWrapLoading').hide();
                                            $("#getAllGroups").html(html);
                                            $("#groupWrap").show();
                                            $("#checkAll").prop( "checked", true );
                                        }
                                    });
                                   /*end set group id*/
                               }

                               /*blog link type*/
                               $("input[name=bloglink][value="+json.blogLink+"]").prop('checked', true);
                               $("input[name=mpoststyle][value="+json.main_post_style+"]").prop('checked', true);
                               /*userAgent*/
                               $("input[name=useragent][value="+json.useragent+"]").prop('checked', true);
                               $("input[name=shortlink][value="+json.short_link+"]").prop('checked', true);

                               $("input[name=filter_brightness]").prop('checked', json.filter_brightness);
                               $("input[name=filter_contrast]").prop('checked', json.filter_contrast);
                               $("input[name=img_rotate]").prop('checked', json.img_rotate);

                               $("input[name=cimg]").prop('checked', json.checkImage);
                               $("input[name=btnplayer]").prop('checked', json.btnplayer);
                               $("input[name=playerstyle]").prop('checked', json.playerstyle);
                               $("input[name=imgcolor]").prop('checked', json.imgcolor);
                               $("input[name=txtadd]").prop('checked', json.txtadd);

                               $("textarea[name=Prefix]").val(json.prefix_title);
                               $("textarea[name=addtxt]").val(json.suffix_title);

                               $("input[name=ptype][value="+json.ptype+"]").prop('checked', true);
                               /*wait_group*/
                               $("input[name=pause]").val(json.wait_group);
                               $("#ppause").val(json.wait_post); 
                                var ppausetxt = $( "#ppause option:selected" ).text();
                                $("#s2id_ppause").find(".select2-chosen").html(ppausetxt);  

                                $("input[name=random][value="+json.randomGroup+"]").prop('checked', true);
                            }
                        }
                    });
                }
            });

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
             $(".addfield").click(function() {
                var code = makeid();
                var link = 'link_' + code;
                var title = 'title_link_' + code;
                var name = 'name_link_' + code;
                var description = 'description_link_' + code;
                var image = 'image_link_' + code;
                var vid = 'vid_link_' + code;
                var image_show = 'show_link_' + code;
                var n = $( ".optionBox" ).length;
              $('.morefield').append('<div class="widget box optionBox" id="post_'+code+'"  data-postid="'+code+'"><div class="widget-header"><h4><i class="icon-reorder"></i> Post <span class="counts">'+n+'</span></h4><div class="toolbar no-padding"><div class="btn-group"><span class="btn btn-xs btn-inverse widgets-refresh" onclick="getcontent(&#39;'+code+'&#39;);"><i class="icon-refresh"></i></span><button class="btn btn-xs removediv bs-tooltip" data-original-title="Remove this" type="button" id="'+code+'"  onclick="removediv(&#39;'+code+'&#39;);"><i class="icon-remove text-danger"></i></button></div></div></div><div class="widget-content"><div class="row-border"><div class="form-group"><div class="col-md-12"><div class="form-group"><div class="col-md-12"><table style="width: 100%;"><tbody><tr><td><div class="form-group" style="margin-bottom: 3px"><div class="col-md-12"><div class="input-group"><span class="input-group-addon"><i style="color: red" class="icon-youtube-sign"></i></span> <input type="text" value="" class="form-control post-option" name="link[]" placeholder="Youtube URL or ID" id="'+link+'" onchange="getLink(this);" /></div></div></div><div class="form-group" style="border: none;margin-bottom: 3px"><label class="col-md-3 khmer">ចំណងជើងប្លុក</label><div class="col-md-9"><input type="text" value="" class="form-control post-option" name="title[]" placeholder="Title" id="'+title+'" /></div></div><div class="form-group" style="border: none"><label class="col-md-3 khmer">ចំណងជើងស៊ែរ៍</label><div class="col-md-9"><input type="text" id="'+name+'" value="" class="form-control post-option" name="name[]" /></div></div></td><td style="width: 150px;"><div class="imgwrap"><img id="'+image_show+'" src="https://i.ytimg.com/vi/0000/0.jpg" style="width:150px;margin-left: 5px;height:102px;border: 1px solid #CCC;"/><a href="javascript:;" class="btn btn-xs btn-inverse" title="Edit Image" onclick="getEitImage(this);"><i class="glyphicon glyphicon-pencil"></i></a></div><button type="button" class="btn btn-xs btn-primary pull-right" style="margin-left: 5px;width: 150px" onclick="getImage(this);"><span class="khmer">ដូររូបភាព</span></button><input type="hidden" id="'+image+'" value="" class="form-control post-option" name="thumb[]" placeholder="Image url" /><input type="hidden" id="'+vid+'" value="" class="form-control post-option" name="vid[]" /></td></tr></tbody></table></div></div><div class="form-group" style="border: none"><div class="col-md-12"><textarea name="conents[]" id="'+description+'" class="form-control post-option wysiwygs" style="height: 58px"></textarea></div></div></div></div></div></div></div>');
                $('.bs-tooltip').tooltip();
                getEditor(code);
                updateCount(code);
                window.location.hash = 'post_'+code;
                var postid = $("#post_"+code);
                if($(postid).length){
                    $( "#addmorefields .addmorefield" ).clone().appendTo( "#post_" +code + " .row-border" );
                    // $( "#post_" +code + " .row-border .smpoststyle" ).each(function(i) {
                    //   $(this).attr('name', $(this).attr('name') + i);
                    // });
                    $("#post_" +code + " .row-border .smpoststyle").attr('name', $("#post_" +code + " .row-border .smpoststyle").attr('name') + $( ".optionBox" ).length);
                }
                //var count = $(".listofsong").length;
                //$("#countdiv").text("ចំនួន " + count + " បទ");
            });        
            /*End add field*/

            $(".widget .toolbar .widgets-refresh").click(function () {
                // var a = $(this).parents(".optionBox");
                // var ids = $(this).parents(".widget").attr('id');                
                // Apps.blockUI(a);
                // if(ids!='') {
                //     var id = ids.split("post_");
                //     id = id[1];
                //     var jqxhr = $.ajax( "http://localhost/wordpress/mynews/wp-content/plugins/splogr/scrap.php?action=1&max=1")
                //       .done(function(data) {
                //         if ( data ) {                            
                //             var obj = JSON.parse(data);                            
                //             if(!obj.error) {
                //                $('#title_link_' + id).val(obj.content[0].title);
                //                $('#description_link_' + id).data("wysihtml5").editor.setValue(obj.content[0].content);
                //                 window.setTimeout(function () {
                //                     Apps.unblockUI(a);
                //                     noty({
                //                         text: "<strong>Success!</strong>",
                //                         type: "success",
                //                         timeout: 1000
                //                     })
                //                 }, 1000)
                //             }
                //         }
                //         if ( !data ) {
                //             window.setTimeout(function () {
                //                 Apps.unblockUI(a);
                //                 noty({
                //                     text: "<strong>No data</strong>",
                //                     type: "error",
                //                     timeout: 1000
                //                 })
                //             }, 1000)
                //         }
                //       })
                //       .fail(function() {
                //         alert( "error" );
                //       })
                //       .always(function() {
                //         //alert( "complete" );
                //       }); 
                // }
            });

            /*upload file*/
            var options = { 
                beforeSubmit:  showRequest,  // pre-submit callback 
                success:       showResponse  // post-submit callback 
                // other available options: 
                //url:       url         // override for form's 'action' attribute 
                //type:      type        // 'get' or 'post', override for form's 'method' attribute 
                //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
                //clearForm: true        // clear all form fields after successful submit 
                //resetForm: true        // reset the form after successful submit 
         
                // $.ajax options can be used here too, for example: 
                //timeout:   3000 
            }; 
         
            // bind to the form's submit event 
            $("body").on("submit", "#upload_form", function(e){
                e.preventDefault();
                var form = $(e.target);
                var id = $(e.target).data('formid');
                var data = new FormData(form[0]);
                if($("#image_file").length != 0) {
                    jQuery.each(jQuery('#image_file')[0].files, function(i, file) {
                        data.append('image_file', file);
                    });
                }
                $("#blockui").show();
                $.ajax({
                    url: form.attr("action"),
                    type: "POST",
                    data: data,
                    dataType: 'JSON',
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    beforeSend: function (xhr) {
                        xhr.overrideMimeType("text/plain; charset=x-user-defined");
                    }
                }).done(function (data) {
                    $("#blockui").hide();
                    if(!data.error) {
                        if(data.image) {
                            var setbse = '<?php echo base_url();?>uploads/image/';
                            $("#uploadFile #image_file").remove();
                            $("#editimage").remove();
                            $("#image_link_"+id).val(setbse+data.image);
                            $("#show_link_"+id).attr("src",setbse+data.image);
                            $("#imagewater").attr("src",setbse+data.image);
                            $( "#imageeffect" ).clone().appendTo( ".step3" );
                            $(".step2").hide();
                            var image = $('#imagewater').attr('src');
                            num.push({"mainimage":image,"value":{}});
                            var pretty = JSON.stringify(num, undefined, 2);
                            $('#datavalu').val(pretty);
                            getslider();
                            $('.bs-tooltip').tooltip();
                        }
                        if(data.upload) {
                            $("#image_link_"+id).val(data.upload);
                            $("#show_link_"+id).attr("src",data.upload);
                            $('#cropModal').modal('hide');
                        }
                    }
                });
                // $(e.target).ajaxSubmit(options); 
                // return false; 
                // $.post( form.attr("action"), form.serialize(), function(res){
                //     console.log(res);
                // });
            });
            /*end upload file*/
        });

        function getImage(setbtn) {
            //$('#upload_form')[0].reset();
            var id = $(setbtn).closest(".optionBox").data('postid');
            setmodule(id);
        }
        function getEitImage(setbtn) {
            num = [];
            $("#uploadFile #image_file").remove();
            var id = $(setbtn).closest(".optionBox").data('postid');
            var img = $("#show_link_"+id).attr("src");
            //var img = '<?php echo base_url();?>uploads/image/acd9ad73a5c77ca7d566191e10e0dc98.jpg';
            setmodule(id);
            ImageSelectHandler(img);
            //setmodule(id);
        }
        function setmodule(id) {
            var formhtml = '<div class="tabbable tabbable-custom tabs-below" id="tabbables"> <div class="tab-content"> <div class="tab-pane active" id="tab_2_1"><!--form--><form id="upload_form" method="post" data-formid="'+id+'" enctype="multipart/form-data" action="<?php echo base_url();?>managecampaigns/upload" class="form-horizontal row-border" > <input type="hidden" id="x1" name="x1"/> <input type="hidden" id="y1" name="y1"/> <input type="hidden" id="x2" name="x2"/> <input type="hidden" id="y2" name="y2"/> <div class="row" id="uploadFile"> <div class="col-md-12"> <fieldset> <div class="form-group"> <input type="file" name="image_file" id="image_file" onchange="fileSelectHandler()" data-style="fileinput" accept="image/*"/> <div class="error"></div></div></fieldset> </div><div class="step2"> <h2>Step2: Please select a crop region</h2> <img id="preview"/> <div class="info"> <label>File size</label> <input type="text" id="filesize" name="filesize" class="form-control input-width-small" style="display: inline-block;"/> <label>Type</label> <input type="text" id="filetype" name="filetype" class="form-control input-width-small" style="display: inline-block;"/> <label>Image dimension</label> <input type="text" id="filedim" name="filedim" class="form-control input-width-small" style="display: inline-block;"/> <label>W</label> <input type="text" id="w" name="w" class="form-control input-width-small" style="display: inline-block;"/> <label>H</label> <input type="text" id="h" name="h" class="form-control input-width-small" style="display: inline-block;"/> </div><div class="form-group fixed"> <div class="col-md-12"> <input type="submit" value="Upload" class="btn btn-primary pull-right" name="upload"/> </div></div></div><div class="step3"></div></div><div class="row" id="wrap-loading"> <div class="col-md-12"> <div id="loading"></div></div></div></form><!--end form--></div><div class="tab-pane" id="tab_2_2"><div class="form-horizontal row-border"><div class="form-group" style="margin:0"> <label class="col-md-2 control-label">ដាក់លីងគ៍/URL:</label> <div class="col-md-10"><div class="input-group"> <input type="text" name="fromlink" id="fromlink" class="form-control"> <span class="input-group-btn"> <button class="btn btn-default" type="button" id="getfromlink" style="height:32px">Get!</button> </span> </div></div> </div></div></div></div><ul class="nav nav-tabs"> <li class="active"><a href="#tab_2_1" data-toggle="tab">ពីកុំព្យូទ័រ/Upload</a></li><li><a href="#tab_2_2" data-toggle="tab">ពីវេបសាយ / URL</a></li></ul> </div>';
            $('#formgenerate').html(formhtml);
            $('#cropModal').modal('show');
        }
        function makeid() {
          var text = "";
          var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

          for (var i = 0; i < 5; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

          return text;
        }

        function removediv(id){
             $("#post_" + id).remove();
             updateCount(id);
        }

        function getcontent(id) {
            var a = $("#post_"+id);             
                Apps.blockUI(a);
                if(id!='') {
                    var jqxhr = $.ajax( "<?php echo base_url();?>splogr/getpost")
                      .done(function(data) {
                        if ( data ) {                            
                            var obj = JSON.parse(data);                            
                            if(!obj.error) {
                               $('#title_link_' + id).val(obj.content[0].title);
                               $('#description_link_' + id).data("wysihtml5").editor.setValue(obj.content[0].content);
                                window.setTimeout(function () {
                                    Apps.unblockUI(a);
                                    noty({
                                        text: "<strong>Success!</strong>",
                                        type: "success",
                                        timeout: 1000
                                    })
                                }, 1000)
                            }
                            if(obj.error) {
                                window.setTimeout(function () {
                                    Apps.unblockUI(a);
                                    noty({
                                        text: "<strong>No data</strong>",
                                        type: "error",
                                        timeout: 1000
                                    })
                                }, 1000)
                            }
                        }
                        if ( !data ) {
                            window.setTimeout(function () {
                                Apps.unblockUI(a);
                                noty({
                                    text: "<strong>No data</strong>",
                                    type: "error",
                                    timeout: 1000
                                })
                            }, 1000)
                        }
                      })
                      .fail(function() {
                        //alert( "error" );
                            window.setTimeout(function () {
                                Apps.unblockUI(a);
                                noty({
                                    text: "<strong>Error contact to admin</strong>",
                                    type: "error",
                                    timeout: 1000
                                })
                            }, 1000)
                      })
                      .always(function() {
                        //alert( "complete" );
                      }); 
                }

            // var a = $(this).parents(".optionBox");
                // var ids = $(this).parents(".widget").attr('id');                
                // Apps.blockUI(a);
                // if(ids!='') {
                //     var id = ids.split("post_");
                //     id = id[1];
                //     var jqxhr = $.ajax( "http://localhost/wordpress/mynews/wp-content/plugins/splogr/scrap.php?action=1&max=1")
                //       .done(function(data) {
                //         if ( data ) {                            
                //             var obj = JSON.parse(data);                            
                //             if(!obj.error) {
                //                $('#title_link_' + id).val(obj.content[0].title);
                //                $('#description_link_' + id).data("wysihtml5").editor.setValue(obj.content[0].content);
                //                 window.setTimeout(function () {
                //                     Apps.unblockUI(a);
                //                     noty({
                //                         text: "<strong>Success!</strong>",
                //                         type: "success",
                //                         timeout: 1000
                //                     })
                //                 }, 1000)
                //             }
                //         }
                //         if ( !data ) {
                //             window.setTimeout(function () {
                //                 Apps.unblockUI(a);
                //                 noty({
                //                     text: "<strong>No data</strong>",
                //                     type: "error",
                //                     timeout: 1000
                //                 })
                //             }, 1000)
                //         }
                //       })
                //       .fail(function() {
                //         alert( "error" );
                //       })
                //       .always(function() {
                //         //alert( "complete" );
                //       }); 
                // }
        }
        function getLink(e) {
            var id = $(e).attr('id'),oldlink ='';
            var sid = $(e).closest(".optionBox").data('postid'); 
            Apps.blockUI($("#post_"+sid));
            if($("input[name=foldlink]").is(":checked")) {
                oldlink = $("input[name=foldlink]").val();
            }            
            if(e!='') {
                var jqxhr = $.ajax( "<?php echo base_url();?>managecampaigns/get_from_url?url=" + encodeURI($(e).val()) + "&old=" + oldlink)
                  .done(function(data) {
                    if ( data ) {
                        var obj = JSON.parse(data);
                      $('#title_' + id).val(obj.name);
                      $('#name_' + id).val(obj.name);
                      $('#vid_' + id).val(obj.vid);
                      $('#description_' + id).val(obj.description);
                      $('#image_' + id).val(obj.picture);
                      $('#show_' + id).attr("src",obj.picture);
                      if(obj.from != 'site') {
                        getcontent(id.replace("link_", ""));
                      }
                      if(obj.from == 'site') {
                        $('#post_' + sid + ' .set_balel').val(obj.label);
                        $('#post_' + sid + ' .setPrefix').val('#ชอบก็ไลค์ #ใช่ก็แชร์');
                        $('#post_' + sid + ' .smpoststyle[value=tnews]').prop('checked', true);
                      }
                      if(obj.from == 'site') {
                        console.log('site');
                        $('#description_link_' + sid).data("wysihtml5").editor.setValue(obj.content);
                        window.setTimeout(function () {
                            Apps.unblockUI($("#post_"+sid));
                            noty({
                                text: "<strong>Success!</strong>",
                                type: "success",
                                timeout: 1000
                            })
                        }, 1000)
                      }
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

        function updateCount(id) {
            $(".optionBox .counts").each(function (index, value) {
                index = (index + 1);
                var id = $(this).parent().parent().parent().attr('id'); 
               $("#" + id + ' .counts').html(index);
               $("#" +id + " .row-border .smpoststyle").attr('name', 'smpoststyle' + index);
            });
            
        }

        function updateIputField(index,id) {
            $("#post_" +id + " .row-border .smpoststyle").attr('name', $("#post_" +id + " .row-border .smpoststyle").attr('name') + index);
        }

        function getEditor (id) {  
            $("#description_link_" + id).wysihtml5('deepExtend', {
                "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
                "emphasis": true, //Italics, bold, etc. Default true
                "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true                
                "link": true, //Button to insert a link. Default true
                "image": true, //Button to insert an image. Default true,
                "color": false, //Button to change color of font  
                "blockquote": false,
                "html": true, //Button which allows you to edit the generated HTML. Default false
            });
        }
        function myStopFunction() {
            alert(11);
          clearInterval(myVar);
        }

        // pre-submit callback 
function showRequest(formData, jqForm, options) { 
    $('#uploadFile').hide();
    $('#wrap-loading').show();
    $("#loading").html('<center><img src="<?php echo base_url(); ?>assets/img/upload_progress.gif" alt="Uploading...."/></center>');
    return true; 
} 
 
// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  { 
    var obj = JSON.parse(responseText);
    if (!obj.error) {
        // $("#image-url").val(obj.image);
        // $("#image-preview").html('<img src="' + obj.image + '" alt="Success...."/>').show();
        // $("#photoCrop").attr("src",obj.image);
        // $('#cropModal').modal('hide');
        // $("#imagepost").val(obj.image);
        // $('#uploadFile').show();
        // $('#wrap-loading').hide();
    }
}
    </script>
<!-- crop Modal -->
<div class="modal fade khmer" id="loginModal" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="loginModalLabel">Please Login again</h4>
      </div>
      <div class="modal-body">
            <center><p class="khmer" style="color: red;font-size: 18px !important;">សូមមេត្តាចុចប៉ូតុង login ខាងក្រោម ចូលម្ដងទៀត មុននឹងធ្វើការប៉ុស្ដិ៍<br/>
ព្រោះផុតកំណត់ម៉ោងនៃការប្រើគណនីរបស់ google ហើយ</p>
<a href="<?php echo base_url();?>managecampaigns/account" target="_blank"><img src="<?php echo base_url();?>themes/layout/img/google.png"/></a><p class="khmer">ដើម្បីកុំឲ្យបាត់ទិន្នន័យដែលអ្នកបានបញ្ចូលហើយ<br/>
សូមចុច login រួចហើយ សឹមចុចបិទ</p></center>
      </div>
      <div class="modal-footer"><button onclick="myStopFunction()" data-dismiss="modal" class="btn btn-default" type="button">Close</button></div>
    </div>
  </div>
</div>

<!-- crop Modal -->
<div class="modal fade khmer" id="cropModal" role="dialog" aria-labelledby="cropModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="cropModalLabel">Upload</h4>
      </div>
      <div class="modal-body bbody" id="formgenerate">
      </div>
    </div>
  </div>
</div> 
<div style="display: none;">
<div id="imageeffect">
    <div class="row">
                <div class="col-md-8">
                    <div class="widget box"> <div class="widget-header"> <h4><i class="icon-reorder"></i> Preview</h4> </div> <div class="widget-content"> <div class="imagecontainer"><div class= "image" >
                        <img src="<?php echo base_url();?>uploads/image/watermark/watermark/picture.jpg" id="imagewater" style="width: 400px;"/>
                    </div></div>
        </div> </div>

                <!-- box 2 -->
                <div class="widget box"> <div class="widget-header"> <h4><i class="icon-reorder"></i> Image filter</h4> </div> <div class="widget-content align-center"> 
                            <div class="info form-horizontal row-border" style="margin: 0 5px;">
                                    <div id="blur" type="range">Blur</div> 
                                  <div id="grayscale" type="range">Grayscale</div> 
                                  <div id="brightness" type="range">brightness</div>
                                  <div id="contrast" type="range">contrast</div>
                                  <div id="rotate" type="range">huerotate</div>
                                  <div id="invert" type="range">invert</div>
                                  <div id="opacity" type="range">opacity</div>
                                  <div id="saturate" type="range">saturate</div>
                                  <div id="sepia" type="range">sepia</div>
                                  <div style="clear: both;"></div> 
                                  <div class="form-group" style="height: 0;opacity: 0;"> <div class="col-md-12"><textarea class="form-control" id="datavalu" name="dataImageEffect" readonly></textarea></div></div>    
                            </div>     
                </div> </div>
                <!-- End box 2 -->
                </div>
                <div class="col-md-4">
                    <div class="widget box"> <div class="widget-header"> <h4><i class="icon-reorder"></i> Watermark</h4> </div> <div class="widget-content"> 
                            <div class="info form-horizontal row-border" style="margin: 0 5px;">
                                <div class="form-group">
                                  <label class="radio-inline" style="margin-left: 5px">
                                      <input type="radio" value="text" name="watermarkchooser" class="required" />
                                      <img class="icon" src="<?php echo base_url();?>uploads/image/watermark/icon/text.png">
                                  </label> 
                                  <label class="radio-inline">
                                      <input type="radio" value="shape" name="watermarkchooser" class="required" />
                                      <img class="icon" src="<?php echo base_url();?>uploads/image/watermark/icon/shape.png">
                                  </label>
                                  <label class="radio-inline">
                                      <input type="radio" value="sticker" name="watermarkchooser" class="required" />
                                      <img class="icon" src="<?php echo base_url();?>uploads/image/watermark/icon/heart-smiley-icon.png">
                                  </label>
                                  <div id="choosetext" class="water-wrap" style="display: none;">
                                    <img class="icon-choose bs-tooltip"  data-original-title="មកដល់ហើយ" src="<?php echo base_url();?>uploads/image/watermark/text/comeback.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ឆ្នោតមកក្ដៅៗ" src="<?php echo base_url();?>uploads/image/watermark/text/houy-ded.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ឆ្នោតល្បី" src="<?php echo base_url();?>uploads/image/watermark/text/houy-dung.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="លេខមកក្ដៅៗ" src="<?php echo base_url();?>uploads/image/watermark/text/lek-ded.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="លេខកំពុងល្បី" src="<?php echo base_url();?>uploads/image/watermark/text/lek-kamlang-dang.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ខ្ញុំបង្ហោះឲ្យហើយណា!" src="<?php echo base_url();?>uploads/image/watermark/text/post-now.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="សេវទុកទៅ" src="<?php echo base_url();?>uploads/image/watermark/text/save-it.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="អ្នកណាមានលេខនេះខ្លះ?" src="<?php echo base_url();?>uploads/image/watermark/text/who-have-this.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="អ្នកណាគេកំពុងរង់ចាំ" src="<?php echo base_url();?>uploads/image/watermark/text/who-wait-for.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="មិនបាច់ចាំទេ" src="<?php echo base_url();?>uploads/image/watermark/text/harry-up.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="លេខបែកធ្លាយ" src="<?php echo base_url();?>uploads/image/watermark/text/get-it.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="អ្នកជំពាក់ធនាគារច្រើន ហាមរំលង" src="<?php echo base_url();?>uploads/image/watermark/text/dont-giveup.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ត្រូវមកច្រើនដងហើយ" src="<?php echo base_url();?>uploads/image/watermark/text/all.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="លេខ២ខ្ទង់ត្រង់ៗ" src="<?php echo base_url();?>uploads/image/watermark/text/2-trong.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="លេខ៣ខ្ទង់ត្រង់ៗ" src="<?php echo base_url();?>uploads/image/watermark/text/3-rong.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="កុំអាលថយ" src="<?php echo base_url();?>uploads/image/watermark/text/back.png"/>
                                    <div style="clear: both;"></div>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ឆ្នោតវគ្គនេះ ប៉ុស្តិ៍ឲ្យហើយ" src="<?php echo base_url();?>uploads/image/watermark/text/posted.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="យកទៅ មានហើយ!" src="<?php echo base_url();?>uploads/image/watermark/text/rich.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចមើលទៅ!" src="<?php echo base_url();?>uploads/image/watermark/text/see.png"/>
                                    <div style="clear: both;"></div>
                                  </div>
                                  <div id="chooseshape" class="water-wrap" style="display: none;">
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសបែបជ្រុង" src="<?php echo base_url();?>uploads/image/watermark/shapes/sqare.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសបែបរង្វង់" src="<?php echo base_url();?>uploads/image/watermark/shapes/ellipse.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសរង្វង់បែបដៃ" src="<?php echo base_url();?>uploads/image/watermark/shapes/roundel.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសព្រួញ" src="<?php echo base_url();?>uploads/image/watermark/shapes/blue-point-l.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសព្រួញ" src="<?php echo base_url();?>uploads/image/watermark/shapes/blue-point-r.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសព្រួញ" src="<?php echo base_url();?>uploads/image/watermark/shapes/point-left.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសព្រួញ" src="<?php echo base_url();?>uploads/image/watermark/shapes/point-right.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសបិទអក្សរឬជ្រុង" src="<?php echo base_url();?>uploads/image/watermark/shapes/sqare-white.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសបិទអក្សរឬជ្រុង" src="<?php echo base_url();?>uploads/image/watermark/shapes/blur.png"/>
                                    <div style="clear: both;"></div>
                                  </div>
                                  <div id="choosesticker" class="water-wrap" style="display: none;">
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/emj/pointing-finger-right.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/emj/pointing-finger-left.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/emj/folded-hand.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/emj/Tongue_Out_Emoji_with_Winking_Eye.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/stickers/1.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/stickers/Blushing.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/stickers/heart-smiley-heart.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/stickers/love-eyes.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/stickers/love-eyes-heart.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/stickers/so-good.png"/>
                                    <img class="icon-choose" src="<?php echo base_url();?>uploads/image/watermark/emj/like.png"/>
                                    <img class="icon-choose" src="<?php echo base_url();?>uploads/image/watermark/emj/like-left.png"/>
                                    <img class="icon-choose" src="<?php echo base_url();?>uploads/image/watermark/emj/like-hand-right.png"/>
                                    <img class="icon-choose" src="<?php echo base_url();?>uploads/image/watermark/emj/like-hand-left.png"/>
                                    <div style="clear: both;"></div>
                                  </div>
                                </div>

                                <div class="form-group"> <label class="col-md-4 control-label">X:</label> <div class="col-md-8"><input type="text" name="regular" class="form-control" id="posx"></div> </div>

                                <div class="form-group"> <label class="col-md-4 control-label">Y:</label> <div class="col-md-8"><input type="text" name="regular" class="form-control" id="posy"></div> </div>

                                <div class="form-group"> <label class="col-md-4 control-label">Width:</label> <div class="col-md-8"><input type="text" name="regular" class="form-control" id="width"></div> </div>

                                <div class="form-group"> <label class="col-md-4 control-label">Height:</label> <div class="col-md-8"><input type="text" name="regular" class="form-control" id="height"></div> </div>

                                <div class="form-group"> <label class="col-md-4 control-label">Mouse X:</label> <div class="col-md-8"><input type="text" name="regular" class="form-control" id="mousex"></div> </div>

                                <div class="form-group"> <label class="col-md-4 control-label">Mouse Y:</label> <div class="col-md-8"><input type="text" name="regular" class="form-control" id="mousey"></div> </div>        
                            </div>     
                </div> </div>


                </div>
                <div style="clear: both;"></div>
                <div class="form-actions"> <input name="step3" value="Ok" class="btn btn-primary pull-right" type="submit"></div>
    </div>
</div>
</div>
<!-- image watermarker and effect -->

<!-- add more field -->
<div id="addmorefields" style="display: none;">
    <div class="addmorefield" style="">
        <div class="form-group" style="border: none">
            <div class="col-md-3 khmer">
                <fieldset>
                    <legend>យកទិន្នន័យពី:</legend>
                    <label class="radio-inline" style="display: inline-block;margin-top: -3px">
                        <input value="1" name="fromoldlink[]" type="checkbox">
                        <i class="subtopmenu hangmeas khmer">មិនផុសប្លុកដើម</i>
                    </label>
                </fieldset>
                <fieldset>
                    <legend>Post to Blog link:</legend>
                    <label class="radio-inline" style="display: inline-block;">
                        <input value="1" name="setbloglink[]" type="checkbox">
                        <i class="subtopmenu hangmeas khmer">ប្រើប្លុកលីងគ៍?</i>
                    </label>
                </fieldset>
            </div>
           
            <div class="col-md-3">
                <fieldset>
                    <legend>Post Type:</legend>
                    <label class="radio">
                        <input type="radio" value="1" name="smpoststyle" class="uniform smpoststyle"  />
                        <i class="subtopmenu hangmeas">Youtube</i>
                    </label> 
                    <label class="radio">
                        <input type="radio" value="2" name="smpoststyle" class="uniform smpoststyle" />
                        <i class="subtopmenu hangmeas">With Player</i>
                    </label>
                    <label class="radio">
                        <input type="radio" value="link" name="smpoststyle" class="uniform smpoststyle"  />
                        <i class="subtopmenu hangmeas">Link</i>
                    </label>  
                    <label class="radio khmer">
                        <input type="radio" value="tnews" name="smpoststyle" class="uniform smpoststyle"  />
                        <i class="subtopmenu hangmeas">ព័ត៌មាន / News</i>
                    </label> 
                </fieldset>
            </div>
             <div class="col-md-6">
                <fieldset>
                    <legend>ថែមអត្ថបទពីក្រោយ:</legend>
                        <label class="khmer" style="max-width: 100%;width: 100%">
                        <textarea style="max-width: 100%;width: 100%;height: 34px" rows="1" cols="5" name="saddtxt[]" class="form-control setPrefix" placeholder="1234|1234|1234"></textarea></label>
                </fieldset>
                <fieldset>
                    <legend>ប្រភេទ / Label:</legend>
                        <label class="khmer" style="max-width: 100%;width: 100%">
                        <input type="text" style="max-width: 100%;width: 100%;" name="label[]" class="form-control set_balel" placeholder="News, Thai Lottery"></label>
                </fieldset>
            </div>
        </div>
</div>
</div>
<!-- End add more field -->
<script>
        /*watermarker*/
        
        (function(){
          
            $(document).on("click","#getfromlink", function(){
                var id = $("#upload_form").data('formid');
                var img = $("#fromlink").val();
                if(img) {
                    $("#show_link_"+id).attr("src",img);
                    $("#image_link_"+id).val(img);
                    $('#cropModal').modal('hide');
                }
            });
            // $(document).on("mousemove",function(event){
            //     $("#mousex").val(event.pageX);
            //     $("#mousey").val(event.pageY);
            // });
            $(document).on("click","input[name=watermarkchooser]", function(){
                var value = $(this).val();
                switch (value) {
                  case 'text':
                    $("#chooseshape").slideUp();
                    $("#choosesticker").slideUp();
                    $("#choosetext").slideDown();
                    break;
                  case 'shape':
                    $("#choosesticker").hide();
                    $("#choosetext").hide();
                    $("#chooseshape").slideDown();
                    break;
                  case 'sticker':
                    $("#chooseshape").hide();
                    $("#choosetext").hide();
                    $("#choosesticker").slideDown();
                    break; 
                }
            });

            $(document).on("click",".icon-choose", function(){
                var value = $(this).attr('src');
                getwatermark(value);
            });


            $('select[name=watermark]').change(function () {
                var value = $(this).val();
                var setnum = make_id(10);
                if(value!='') {
                    num.push({"watermark":setnum, "value":{"image":value,"x1":0,"y1":0,"w":0,"h":0}});
                    setwatermarker(value,setnum);
                }
            });
        })();
        
function getattra(e) {
    $("#singerimageFist").val(e);
    $("#imageviewFist").html('<img style="width:100%;height:55px;" src="' + e + '"/>');
}
function getwatermark(value) {
  var setnum = make_id(10);
  if(value!='') {
      num.push({"watermark":setnum, "value":{"image":value,"x1":0,"y1":0,"w":0,"h":0}});
      setwatermarker(value,setnum);
  }
}
function setwatermarker(image,setval) {
    $("#imagewater").watermarker({
        imagePath: image,
        removeIconPath: "<?php echo base_url();?>uploads/image/watermark/watermark/close-icon.png",
        offsetLeft:30,
        offsetTop: 40,
        onChange: updateCoords,
        onInitialize: updateCoords,
        containerClass: "myContainer imagecontainer",
        watermarkImageClass: "myImage superImage",      
        watermarkerClass: "js-watermark-1 js-watermark",
        watermarkerDataId: setval,
        data: {id: setval, "class": "superclass", pepe: "pepe"},        
        onRemove: function(){
            for (var i = num.length - 1; i >= 0; --i) {
                if (num[i].watermark == setval) {
                    num.splice(i,1);
                }
            }
            if(typeof console !== "undefined" && typeof console.log !== "undefined"){
                console.log("Removing...");
            }
        },
        onDestroy: function(){
            if(typeof console !== "undefined" && typeof console.log !== "undefined"){
                console.log("Destroying...");   
            }
        }
    });
}
function updateCoords (coords){
    $("#posx").val(coords.x);
    $("#posy").val(coords.y);
    $("#width").val(coords.width);
    $("#height").val(coords.height);
    $("#opacity").val(coords.opacity);  
    for (var i = 0; i < num.length; i++) {
        if (num[i].watermark == coords.id) {
            num[i].value = {"image":coords.imagePath,"x1":coords.x,"y1":coords.y,"w":coords.width,"h":coords.height};
        }
    }

    // var image = $('#image').attr('src');
    //var obj={"watermark":{"image":coords.imagePath,"x1":coords.x,"y1":coords.y,"w":coords.width,"h":coords.height}}
    var pretty = JSON.stringify(num, undefined, 2);
    $('#datavalu').val(pretty);
}
function make_id(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}
/*End watermarker*/
/*filter*/
function blur() {
  var blur = $("#blur").slider("value");
  var grayscale = $("#grayscale").slider("value");
  var brightness = $("#brightness").slider("value");
  var contrast = $("#contrast").slider("value");
  //var rotate = $("#rotate").slider("value");
  var invert = $("#invert").slider("value");
  //var opacity = $("#opacity").slider("value");
  //var saturate = $("#saturate").slider("value");
  //var sepia = $("#sepia").slider("value");
  $("#imagewater").css("-webkit-filter", "blur(" + blur + "px)" + "brightness(" + brightness + "%)" + "grayscale(" + grayscale + "%)" + "contrast(" + contrast + "%)" + "invert(" + invert + "%)");
  $("#imagewater").css("filter", "blur(" + blur + "px)" + "brightness(" + brightness + "%)" + "grayscale(" + grayscale + "%)" + "contrast(" + contrast + "%)" + "invert(" + invert + "%)");
  for (var i = 0; i < num.length; i++) {
      if (num[i].mainimage) {
          num[i].value = {"blur":blur,"grayscale":grayscale,"brightness":brightness,"contrast":contrast,"huerotate":rotate,"invert":invert,"opacity":opacity,"saturate":saturate,"sepia":sepia};
      }
  }
  var pretty = JSON.stringify(num, undefined, 2);
  $('#datavalu').val(pretty);
}
//***********SLIDERS*************//

function getslider() {
  $("#blur").slider({
    orientation: "horizontal",
    min: 0,
    max: 25,
    value: 0,
    slide: blur,
    change: blur
  });
  $("#grayscale").slider({
    orientation: "horizontal",
    min: 0,
    max: 100,
    value: 0,
    slide: blur,
    change: blur
  });
  $("#brightness").slider({
    orientation: "horizontal",
    min: 100,
    max: 1000,
    value: 100,
    slide: blur,
    change: blur
  });

  $("#contrast").slider({
    orientation: "horizontal",
    min: 0,
    max: 1000,
    value: 100,
    slide: blur,
    change: blur
  });
  // $("#rotate").slider({
  //   orientation: "horizontal",
  //   min: -180,
  //   max: 180,
  //   value: 0,
  //   slide: blur,
  //   change: blur
  // });

  // $("#saturate").slider({
  //   orientation: "horizontal",
  //   min: 0,
  //   max: 100,
  //   value: 1,
  //   slide: blur,
  //   change: blur
  // });

  // $("#sepia").slider({
  //   orientation: "horizontal",
  //   min: 0,
  //   max: 100,
  //   value: 0,
  //   slide: blur,
  //   change: blur
  // });

  // $("#opacity").slider({
  //   orientation: "horizontal",
  //   min: 0,
  //   max: 100,
  //   value: 100,
  //   slide: blur,
  //   change: blur
  // });

  $("#invert").slider({
    orientation: "horizontal",
    min: 0,
    max: 100,
    value: 0,
    slide: blur,
    change: blur
  });
}
    </script>
<style>
        #image,#myCanvas{
  float:left;
}

#blur,#grayscale,#brightness,#contrast,#rotate,#invert,#opacity,#saturate,#sepia{
    width: 300px;
    margin: 15px;
   float:left;
   font-size: 11px;
 }
 
 div[type=range] {
  -webkit-appearance: none;
  width: 100%;
  margin: 2px 0;
}

div[type=range] {
  width: 100%;
  height: 1px;
  cursor: pointer;
  box-shadow: 1px 1px 0.7px #000000, 0px 0px 1px #0d0d0d;
  background: rgba(191, 102, 192, 0.35);
  border-radius: 9.2px;
  border: 0.2px solid #010101;
}
.ui-slider .ui-slider-handle {
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
  border: 1px solid #000000;
  height: 5px;
  width: 22px;
  border-radius: 12px;
  background: rgba(255, 42, 109, 0.75);
  cursor: pointer;
  -webkit-appearance: none;
  margin-top: 1.8px;
}

div[type=range] {
  width: 100%;
  height: 1px;
  cursor: pointer;
  box-shadow: 1px 1px 0.7px #000000, 0px 0px 1px #0d0d0d;
  background: rgba(191, 102, 192, 0.35);
  border-radius: 9.2px;
  border: 0.2px solid #010101;
}

div[type=range] {
  background: rgba(164, 68, 165, 0.35);
  border: 0.2px solid #010101;
  border-radius: 18.4px;
  box-shadow: 1px 1px 0.7px #000000, 0px 0px 1px #0d0d0d;
}
div[type=range] {
  background: rgba(191, 102, 192, 0.35);
  border: 0.2px solid #010101;
  border-radius: 18.4px;
  box-shadow: 1px 1px 0.7px #000000, 0px 0px 1px #0d0d0d;
}
div[type=range] {
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
  border: 1px solid #000000;
  height: 5px;
  width: 22px;
  border-radius: 12px;
  cursor: pointer;
  height: 1px;
}

</style>
<!-- End image watermarker and effect -->
    <?php
 else:
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
endif;
?>