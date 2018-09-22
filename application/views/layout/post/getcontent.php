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
                    	<?php if(!empty($_GET['url'])):?>
                    	<div class="row has-success" style="margin-bottom:10px;">
                            <div class="col-md-12">
                                <?php echo @$urlContent;?>
                            </div>                        
                        </div>
                    	<?php else:?>
                        <div class="row" style="margin-bottom:10px;">
                            <div class="col-md-12" style="font-family:khmer OS;font-size:16px; text-align:center;color:green">
                            គ្រប់វេបសាយដែលមិនអាច select ហើយនិង coppy ប្រើកម្មវិធីនេះអាចជួយបាន<br/>
                                All sites that are unable to select and coppy.<br/>
                                we can use this tool to enable select and coppy.
                            </div>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" class="form-control required" name="urlid" placeholder="URL here ex: http://www.patjaa.com/115654-2/" />
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-info" name="submit" value="Submit">
                                            Get content                       
                                        </button>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <?php endif;?>
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