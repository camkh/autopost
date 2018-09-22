<?php if ($this->session->userdata('user_type') != 4) { ?>
    <div class="page-header">
        <div class="page-title">
            <h3>
                <?php if (!empty($title)): echo $title;
                endif; ?>
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
                        Managed Table
                    </h4>
                </div>
                <div class="widget-content">
                    <div class="row">
                        <div style="text-align: center;">
                            <a href="<?php echo base_url(); ?>hauth/login/Facebook.html" title="Login to your Facebook account"><img src="<?php echo base_url(); ?>img/social/face.png"/></a>
                            <a href="<?php echo base_url(); ?>hauth/login/Google.html" title="Login to your Google plus account"><img src="<?php echo base_url(); ?>img/social/g+.png"/></a>
                          	<a href="<?php echo base_url(); ?>hauth/login/Twitter.html" title="Login to your Twitter account"><img src="<?php echo base_url(); ?>img/social/twit.png"/></a>
                          	<a href="<?php echo base_url(); ?>hauth/login/LinkedIn.html" title="Login to your LinkedIn account"><img src="<?php echo base_url(); ?>img/social/in.png"/></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function Confirms(text, layout, id, type) {
            var n = noty({
                text: text,
                type: type,
                dismissQueue: true,
                layout: layout,
                theme: 'defaultTheme',
                modal: true,
                buttons: [
                    {addClass: 'btn btn-primary', text: 'Ok', onClick: function($noty) {
                            $noty.close();
                            window.location = "<?php echo base_url(); ?>user/delete/" + id;
                        }
                    },
                    {addClass: 'btn btn-danger', text: 'Cancel', onClick: function($noty) {
                            $noty.close();
                        }
                    }
                ]
            });
            console.log('html: ' + n.options.id);
        }
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
    <?php
} else {
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
?>
