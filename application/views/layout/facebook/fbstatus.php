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
                            <?php echo @$title;?>
                        </h4>                     
                        <div class="toolbar no-padding">
                        </div>
                    </div>
                    <div class="widget-content">
                        <form method="post" id="validate" enctype="multipart/form-data" class="form-horizontal row-border">
                            <div class="form-group">
                                <label class="col-md-2 control-label">File CSV: </label>
                                <div class="col-md-5">
                                    <input type="file" class="required" name="userfile" accept=".csv" required/>
                                </div>
                                <div class="col-md-5">
                                    <a href="<?php echo base_url() . 'Facebook/fbjson'; ?>"  class="btn btn-xs btn-info">Json Type</a>
                                </div>                          
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">
                                    Friend of:
                                </label>
                                <div class="col-md-10">
                                    <input type="text" name="friend" class="form-control required" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Inline Checkbox: </label>
                                <div class="col-md-10">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="uniform" value="1" name="clean">Clean all uncheck 
                                    </label>
                                </div>                           
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-info pull-right" name="submit" value="Submit">
                                    Upload                       
                                </button>
                            </div>
                        </form>
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