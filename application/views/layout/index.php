<div class="page-header">
    <?php
    $blogpassword = $this->session->userdata('blogpassword');
    ?>
    <div class="page-title">
        <h3><?php
        if ($this->session->userdata('user_type') != 3) {
            if ($blogpassword) {
                echo 'អ្នកអាចធ្វើការជាមួយប្លុក​របស់អ្នកបានហើយ';
            } else {
                ?>
                សូមស្វាគមន៍ចំពោះការមកដល់របស់អ្នក<br/>
                វាយលេខកូដសម្ងាប់សម្រាប់ប្លុករបស់អ្នក
            <?php }
        } else { echo 'សូមស្វាគមន៍ចំពោះការមកដល់របស់អ្នក';}?>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                
                <h4>
                    <i class="icon-reorder">
                    </i>
                    <?php
                    if ($this->session->userdata('user_type') != 3) {
                        if (!$blogpassword) {
                            echo 'Login to your blog';
                        } else {
                            echo 'You can post to your blog rightnow';
                        }
                    }
                    ?>
                </h4>
            </div>
            <div class="widget-content">
                <?php if(!empty($blogerror)):?><div class="error" style="color:red;"><?php echo $blogerror;?><br/>សូមវាយលេខសម្ងាត់ម្ដងទៀត</div><?php endif;?>
<?php if (!$blogpassword && $this->session->userdata('user_type') != 3) { ?>
                    <form method="post">
                        <div class="input-group">
                            <?php if(!empty($_GET['backto'])) { ?><input type="password" class="form-control" name="backto" value="<?php echo $_GET['backto'];?>" /><?php } ?>
                            <input type="password" class="form-control" name="blogpass" />
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-info" name="submit">
                                    Login                           
                                </button>
                            </div>
                        </div>
                    </form><?php } else { ?>
                    Welcome to my app
                    <?php if ($this->session->userdata('user_type') == 3):
                        $user_action = @$_GET['user'];
                    if(empty($blogpassword) && empty($user_action)):
                        ?>
                <a href="<?php echo base_url() . 'home?user=publisher'; ?>"  class="btn btn-success">I'm a publisher</a>
                    <?php 
                    else:
                        echo '<span style="color:green">Thanks, You can post rightnow!</span>';
                    endif;endif;?>
<?php } ?>
            </div>
        </div>
    </div>
</div>