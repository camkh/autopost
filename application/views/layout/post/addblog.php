<?php if ($this->session->userdata('user_type') != 3) { ?>
    <style>
        .radio-inline{padding:0;}
    </style>
    <div class="page-header">
    </div>
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal row-border" action="" method="post"> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget box">
                            <div class="widget-header">
                                <input name="submit" type="submit" value="Add" class="btn btn-primary pull-right" /><h4><i class="icon-reorder"></i> Add blog</h4>
                            </div>
                            <div class="widget-content">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Blog Categories:</label>
                                    <div class="col-md-10">
                                        <select name="bloginCat" class="form-control">
                                            <option value="">Select a Categories</option>
                                            <?php foreach ($blogcatlist as $value) { ?><option value="<?php echo $value->{Tbl_title::id}; ?>"><?php echo $value->{Tbl_title::title}; ?></option><?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Blog Name:</label>				
                                    <div class="col-md-10">
                                        <label class="checkbox">
                                            <input type="checkbox" name="allbox" class="uniform" id="checkAll" /> <strong>Select All</strong>
                                        </label>
                                        <?php
                                        $i = 0;
                                        foreach ($feed as $entry):
                                            $i++;
                                            ?>
                                            <label class="checkbox">
                                                <input type="checkbox" class="case uniform" value="<?php
                                                $linkId = $entry->getLink('self')->getHref();
                                                $linkId = $arr = explode('/', $linkId);
                                                echo $linkId[6];
                                                ?>[=]<?php echo $entry->getTitle(); ?>" name="blogID[]" /><?php echo $entry->getTitle(); ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>				
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input name="submit" type="submit" value="Add" class="btn btn-primary pull-right" />
                                    </div>
                                </div>             

                            </div>
                        </div>
                    </div>
                </div>
            </form>
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