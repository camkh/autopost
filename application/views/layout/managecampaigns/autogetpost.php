<?php if ($this->session->userdata('user_type') != 4) { ?>
    <style type="text/css">
        .morefield .form-group{padding: 0 0 0!important;}
        .input-group > .input-group-btn .btn{height: 32px}
    </style>
<div class="page-header">
    <div class="page-title">
        <h3>
                <?php if (!empty($title)): echo $title; endif; ?>
            </h3>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> <?php if (!empty($title)): echo $title; endif; ?></h4>
            </div>
            <div class="widget-content">
                <form class="form-horizontal row-border" method="post">
                    <div class="input-group"> 
                        <input type="text" name="ytid" class="form-control" placeholder="<?php if (!empty($title)): echo $title; endif; ?> ID"> 
                        <span class="input-group-btn"> 
                            <button class="btn btn-default" type="button">Go!</button> 
                        </span> 
                    </div>
                </form>
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
