<?php
if ($this->session->userdata('user_type') == 1) {
    if (!empty($editUser)) {
        foreach ($editUser as $value) {
            $name = $value->name;
            $email = $value->email;
            $username = $value->username;
            $log_id = $value->log_id;
            $user_status = $value->user_status;
            $user_type = $value->user_type;
        }
    } else {
        $name = '';
        $email = '';
        $username = '';
        $log_id = '';
        $user_status = '';
        $user_type = '';
    }
    ?>
    <div class="page-header">
        <div class="page-title">
            <h3>
                <?php
                if (!empty($title)) {
                    echo $title;
                };
                ?>
            </h3>
        </div>
        <ul class="page-stats">
            <li>
                <a class="btn btn-lg btn-primary" href="<?php echo base_url(); ?>user">Back</a>
            </li>        
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4>
                        <i class="icon-reorder">
                        </i>
                        Add user
                    </h4>
                </div>
                <div class="widget-content">
                    <form class="form-horizontal row-border" id="validate-4" action="" method="post"> 
                        <div class="form-group">                        
                            <div class="col-md-12 clearfix">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="fname" id="fname" value="<?php echo $name;?>" class="form-control required" minlength="3"/>
                                        <label for="fname" class="control-label">Name</label> 
                                    </div>                                    

                                    <div class="col-md-1">
                                        <select name="usertype" class="select2 full-width-fix required">
                                            <option />
                                            <option value="3" <?php echo (($user_type==3)?' selected="selected"':'');?>/>
                                            Subcriper
                                            <option value="2" <?php echo (($user_type==2)?' selected="selected"':'');?> />
                                            Editor
                                            <option value="1" <?php echo (($user_type==1)?' selected="selected"':'');?>  />
                                            Admin
                                        </select>
                                        <label for="lname" class="control-label">User type</label> 
                                        <label for="chosen1" generated="true" class="has-error help-block" style="display:none;">
                                        </label>
                                    </div>
                                    <?php if(!empty($editAction)){?>
                                    <input type="hidden" name="action" value="<?php echo $editAction;?>"/>
                                    <input type="hidden" name="id" value="<?php echo $userID;?>"/>
                                    <div class="col-md-1">
                                        <select name="userstatus" class="select2 full-width-fix required">
                                            <option />
                                            <option value="1" <?php echo (($user_status==1)?' selected="selected"':'');?>/>
                                            Active
                                            <option value="0" <?php echo (($user_status==0)?' selected="selected"':'');?> />
                                            Inactive                                           
                                        </select>
                                        <label for="lname" class="control-label">Status</label> 
                                        <label for="chosen1" generated="true" class="has-error help-block" style="display:none;">
                                        </label>
                                    </div><?php }?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">    
                                <div class="form-group">
                                    <label class="col-md-3 control-label">
                                        Email
                                        <span class="required">
                                            *
                                        </span>
                                    </label>
                                    <div class="col-md-9">
                                        <div>
                                            <input type="email" name="email" value="<?php echo $username;?>" class="form-control required" minlength="5"/>
                                        </div>
                                        <label for="spinner-validation" generated="true" class="has-error help-block" style="display:none;">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6"> 
                                <div class="form-group">
                                    <label class="col-md-3 control-label">
                                        Password
                                        <?php if(empty($editAction)){?><span class="required">
                                            *
                                        </span><?php }?>
                                    </label>
                                    <div class="col-md-9">
                                        <div>
                                            <input type="password" name="password" value="" class="form-control <?php if(empty($editAction)){?>required<?php }?>" <?php if(empty($editAction)){?>minlength="5"<?php }?> />
                                        </div>
                                        <label for="spinner-validation" generated="true" class="has-error help-block" style="display:none;">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type="submit" value="Validate Me" class="btn btn-primary pull-right" />
                            <button type="button" class="btn btn-success btn-notification" data-layout="top" data-type="success" data-text="You successfully read this danger alert message.">Success</button>
                            <button data-modal="true" data-text="Do you want to continue?" data-type="confirm" data-layout="top" data-action="user" class="btn btn-inverse btn-notification">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
?>
<script type="text/javascript">
    function generate(type) {
        var n = noty({
            text: type,
            type: type,
            dismissQueue: false,
            layout: 'top',
            theme: 'defaultTheme'
        });
        console.log(type + ' - ' + n.options.id);
        return n;
    }

    function generateAll() {
        generate('alert');
        generate('information');
        generate('error');
        generate('warning');
        generate('notification');
        generate('success');
    }

</script>