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
                            <div class="col-md-4">
                                <label for="imageid">រូបភាពក្របវិដេអូ / Cover</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="imageid" id="imageid" />
                            </div>                         
                        </div>
                        <div class="row" style="margin-bottom:10px;">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" class="form-control required" name="blogid" />
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-info" name="submit" value="Submit">
                                            Get code                           
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