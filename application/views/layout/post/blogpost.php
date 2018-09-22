<?php if ($this->session->userdata('user_type') == 1) {
    ?>
    <div class="page-header">
        <div class="page-title">
            <h3>
                <?php
                if (!empty($title)):
                    echo $title;
                endif;
                ?>
            </h3>
        </div>
        <ul class="page-stats">
            <li>
                <a class="btn btn-lg btn-primary" href="<?php echo base_url(); ?>post/addblog">Add blogs</a>
            </li>        
        </ul>
    </div>
    <div class="row">
        <form method="post" class="form-horizontal row-border"> 
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4>
                            <i class="icon-reorder">
                            </i>
                            Managed Table
                        </h4>
                        <div class="toolbar no-padding">
                            <div class="btn-group">
                                <span class="btn btn-xs widget-collapse">
                                    <i class="icon-angle-down">
                                    </i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content">
                        <?php
                        if (!empty($music_blog)):
                            foreach ($music_blog as $conf):
                                $getid = $conf->{Tbl_title::value};
                                ?>

                                <div class="form-group">
                                    <label class="col-md-4 control-label">Music blogs:</label>				
                                    <div class="col-md-8">
                                        <select name="muiscblog" class="select2 full-width-fix required">
                                            <?php foreach ($blogcatlist as $value) { ?><option value="<?php echo $value->{Tbl_title::id}; ?>" <?php echo (($getid == $value->{Tbl_title::id}) ? 'selected' : ''); ?>><?php echo $value->{Tbl_title::title}; ?></option><?php } ?>
                                        </select>
                                    </div>				
                                </div>
                                <?php
                            endforeach;
                        endif;
                        
                        if (!empty($video_blog)):
                            foreach ($video_blog as $vdo):
                                $vdoid = $vdo->{Tbl_title::value};
                                ?>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Video Blogs:</label>				
                                    <div class="col-md-8">
                                        <select name="videoblog" class="select2 full-width-fix required">
                                            <?php foreach ($blogcatlist as $value) { ?><option value="<?php echo $value->{Tbl_title::id}; ?>" <?php echo (($vdoid == $value->{Tbl_title::id}) ? 'selected' : ''); ?>><?php echo $value->{Tbl_title::title}; ?></option><?php } ?>
                                        </select>
                                    </div>				
                                </div>
                                <?php
                            endforeach;
                        endif;
                        ?>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input name="submit" type="submit" value="Add" class="btn btn-primary pull-right" />
                            </div>
                        </div> 
                        <!-- end page -->

                    </div>
                </div>
            </div>
        </form>
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
