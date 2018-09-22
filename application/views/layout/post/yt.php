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
                    <a href="javascript:;" id="addfield"  class="btn btn-inverse pull-right" style="margin-left: 5px"><i class="icon-plus"></i></a>
                    <a href="<?php echo base_url() . 'post/getfromb/'; ?>"  class="btn btn-warning pull-right">Blog</a>
                    <a href="<?php echo base_url() . 'post/vdokh/'; ?>"  class="btn btn-info pull-right">vdo4kh</a>                                    
                    <a href="<?php echo base_url() . 'post/khmermove/'; ?>"  class="btn btn-google-plus pull-right">khmermo</a>
                    <a href="<?php echo base_url() . 'post/khmerbe/'; ?>"  class="btn btn-facebook pull-right">khmerbe</a>
                    <h4>
                        <i class="icon-reorder">
                        </i>
                        Managed Table
                    </h4>
                </div>
                <div class="widget-content">
                    <form class="form-horizontal row-border" id="validate-1" action="#" novalidate="novalidate" method="post"> 
                        <div class="form-group listofsong"> 
                            <label class="col-md-3 control-label" for="youtubeid">URL ID <span class="required">*</span></label> 
                            <div class="col-md-8"> <input type="text" name="youtubeid[]" id="youtubeid" class="form-control required"> </div> 
                            <div class="col-md-1"><button class="btn btn-sm pull-right removelist" type="button"><i class="icol-cross"></i></button></div> 
                        </div>
                        <div id="morerow"></div>
                        <div class="form-actions"> <input type="submit" value="Validate Me" class="btn btn-primary pull-right"> </div> </form>
                    <!-- end page -->
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
