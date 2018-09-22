 <script type="text/javascript" src="<?php echo base_url();?>themes/layout/blueone/assets/js/libs/jquery.min.js"></script>
    <style>
        .radio-inline{}
        .error {color: red}
        #blockuis{padding:10px;position:fixed;z-index:99999999;background:rgba(0, 0, 0, 0.73);top:20%;left:50%;transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);}
    </style>
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
                            <?php if(empty($_GET['file'])):?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-2">Suorce code</label>
                                    <div class="col-md-10">
                                        <p>The easiest way to add the groups you have joined is to follow these simple directions.</p>
                                        <ul>
                                            <li><p><b>All groups</b>:<br/></p>
                                            <p>only <b>Public groups</b>:<br/>
                                            Go to <a href="https://a.biteight.xyz/redir/r.php?url=https://www.facebook.com/" rel="noreferrer" target="_blank">https://www.facebook.com/</a>  then click on your name in the upper right part of the screen. <br/><img alt="find FB profile name" src="http://img.constantcontact.com/faq/kb/FB_ProfileName.png"><br/>
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

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input name="submit" type="submit" value="Submit" class="btn btn-primary pull-right" />
                                    </div>
                                </div> 
                            </div>
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
                                                        <option value="<?php echo $sList->u_provider_uid;?>" <?php echo ($this->session->userdata('fb_user_id') == $sList->u_provider_uid) ? 'selected' : '';?>><?php echo $sList->u_name;?></option>
                                                    <?php
                                                        endforeach;
                                                     endif;?>
                                                </select> 
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
                                                    value="<?php echo $value['gid']; ?>||<?php echo $value['title'];?>||<?php echo $value['members'];?>" /></td>
                                                <td style="width:135px"><?php echo $value['gid']; ?></td>
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

                    </div>
                </div>
            </div>
        </form>
    </div> 