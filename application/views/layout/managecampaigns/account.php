<?php if ($this->session->userdata('user_type') != 4) { ?>
    <style>
        .radio-inline{}
        .error {color: red}
    </style>
    <div class="page-header">
    </div>
    <div class="row">

        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4>
                        <i class="icon-reorder">
                        </i>
                        <?php if (!empty($title)): echo $title; endif; ?>
                    </h4>                     
                    <div class="toolbar no-padding">
                    </div>
                </div>
                <div class="widget-content">
                    <!-- login to google -->
                    <?php if (isset($authUrl)){ ?>
                    <header id="sign_in">
                    <h2>Login With Google</h2>
                    </header>
                    <hr/>
                    <center><a href="<?php echo $authUrl; ?>"><img id="google_signin" src="<?php echo base_url(); ?>themes/layout/img/sign-in-with-google.png" width="100%" ></a></center>
                    <?php }else{ ?>
                        <div class="list-group"> 
                            <li class="list-group-item no-padding"> 
                                <a target="_blank" class="user_name" href="<?php echo $userData->link; ?>" /><img class="user_img" src="<?php echo $userData->picture; ?>" width="15%" /></li>
                            </li> 
                            <a href="javascript:void(0);" class="list-group-item"><?php echo '<p class="welcome"><i>Welcome ! </i>' . $userData->name . "</p>"; ?></a> 
                            <?php
                    echo "<p class='profile'><a href=\"javascript:void(0);\" class=\"list-group-item\">Profile :-</a></p>";
                    echo "<a href=\"javascript:void(0);\" class=\"list-group-item\"><b> First Name : </b>" . $userData->given_name . "</a>";
                    echo "<a href=\"javascript:void(0);\" class=\"list-group-item\"><b> Last Name : </b>" . $userData->family_name . "</a>";
                    echo "<a href=\"javascript:void(0);\" class=\"list-group-item\"><b> Gender : </b>" . $userData->gender . "</a>";
                    echo "<a href=\"javascript:void(0);\" class=\"list-group-item\"><b>Email : </b>" . $userData->email . "</a>";
                    echo "<a href=\"#". $userData->id ."\" class=\"list-group-item\"><b>GID : </b>" . $userData->id . "</a>";
                    ?>

                            <a class='logout list-group-item' href='https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=<?php echo base_url(); ?>index.php/user_authentication/logout'>Logout</a> 
                        </div>
                    <ul class="list-group">
                        <li class="list-group-item no-padding"> 
                                <a target="_blank" class="user_name" href="<?php echo $userData->link; ?>" /><img src="<?php echo !empty($this->session->userdata ( 'fb_user_id' )) ? 'https://graph.facebook.com/'.$this->session->userdata ( 'fb_user_id' ).'/picture' : base_url().'themes/layout/blueone/assets/img/logo.png'; ?>" alt="logo" /></li>
                            </li> 
                            <a href="javascript:void(0);" class="list-group-item"><?php echo $this->session->userdata ( 'sid' );?> <?php echo !empty($this->session->userdata ( 'fb_user_name' )) ? $this->session->userdata ( 'fb_user_name' ) : ''; ?></a> 
                            <a href="javascript:void(0);" class="list-group-item"><?php echo $this->session->userdata ( 'user_id' );?></a> 
                    </ul>                   
                    <?php }?>
                    <!-- end login to google -->
                </div>
            </div>
        </div>

    </div>

    </div>
    <script>
        function getattra(e) {
            $("#singerimageFist").val(e);
            $("#imageviewFist").html('<img style="width:100%;height:55px;" src="' + e + '"/>');
        }
    </script>

    <?php

} else {
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
?>