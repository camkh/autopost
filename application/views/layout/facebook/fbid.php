<?php if ($this->session->userdata('user_type') != 4) { ?>
    <style>
        .radio-inline{}
        .error {color: red}
    </style>
    <div class="page-header">
    </div>
    <div class="row">
        <form method="post" id="validate">
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
                    	<?php if(!empty($_GET['id'])):?>
                    	<div class="row has-success" style="margin-bottom:10px;">
                            <div class="col-md-4">
                                <label for="imageid">ID:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" onclick="this.focus(); this.select()" class="form-control" value="<?php echo $_GET['id'];?>" />
                            </div>                         
                        </div>
                    	<?php endif;?>
                        <div class="row" style="margin-bottom:10px;">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" class="form-control required" name="urlid" />
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-info" name="url" value="Submit">
                                            Find numeric ID                        
                                        </button>
                                    </div>
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