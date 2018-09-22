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
                        Add New Post
                    </h4>                     
                    <div class="toolbar no-padding">
                    </div>
                </div>
                <div class="widget-content">
                    <form method="post" id="validate" class="form-horizontal row-border">
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="imageid">Title</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="title" id="title" />
                                    </div>                         
                                </div>                         
                            </div>                         
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="imageid">Image</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="imageid" id="imageid" />
                                    </div>                         
                                </div>                         
                            </div>                         
                        </div>
                        <div class="form-group">
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="imageid">Type</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select name="videotype" name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" class="select2 full-width-fix">
                                            <option value="" selected="selected">
                                                Video Type...
                                            </option>
                                            <option value="videofile">
                                                Video file
                                            </option>
                                            <option value="vimeo">
                                                Vimeo
                                            </option>
                                            <option value="docs.google">
                                                Google docs
                                            </option>
                                            <option value="dailymotion">
                                                Dailymotion
                                            </option>
                                            <option value="iframe">
                                                Iframe
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
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