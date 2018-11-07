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
                        <form method="post" id="validate" class="form-horizontal row-border" action="<?php echo base_url();?>Facebook/tgroups">
                            <div class="form-group">
                                <div class="col-md-2">
                                    <input type="text" name="u" class="form-control required" placeholder="friend ID" required/>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="g" cols="5" rows="3" placeholder="Groups ID: 123,456,789"></textarea>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="t" class="form-control required" placeholder="set delay Time" value="5" required/>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-info pull-right" name="submit" value="Submit">
                                    Stranfer now
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