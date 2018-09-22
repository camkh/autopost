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
                            Add New Post
                        </h4>                     
                        <div class="toolbar no-padding">
                        </div>
                    </div>
                    <div class="widget-content">
                        <div class="row" style="margin-bottom:10px;">
                            <div class="col-md-12" align="center">
                                <div class="input-group">
                                    <a href="<?php echo base_url().'post/getfromb/'.$id;?>"  class="btn btn-warning">by Blog</a>
                                    <a href="<?php echo base_url().'post/vdokh/'.$id;?>"  class="btn btn-info">vdo4kh</a>                                    
                                    <a href="<?php echo base_url().'post/khmermove/'.$id;?>"  class="btn btn-google-plus">by khmermove</a>
                                    <a href="<?php echo base_url().'post/khmerbe/'.$id;?>"  class="btn btn-facebook">by khmerbe</a>
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