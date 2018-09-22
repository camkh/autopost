<?php
    if (empty($_GET['step'])) {
        ?>
        <style>
            .radio-inline{padding:0;}
        </style>
        <div class="page-header">
        </div>
        <div class="row">
            <form method="post">
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
                                <div class="form-group">                            
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="inside_url" class="radio">
                                                    <input type="radio" id="inside_url" name="DESC" value="2" />
                                                    inside url
                                                </label>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="DESC1" class="radio">
                                                    <input type="radio" name="DESC" id="DESC1" value="1" checked="checked" />
                                                    by image</label>	
                                            </div>
                                            <div class="col-md-2">
                                                <label for="dailymotino" class="radio">
                                                    <input type="checkbox" name="DESC" id="dailymotino" value="3" />
                                                    Dailymotino</label>	
                                            </div>
                                            <div class="col-md-2">
                                                <label for="orderby" class="radio">									
                                                    <input type="checkbox" name="orderby" id="orderby" value="1" />	
                                                    order 3-2-1</label>	
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <input name="blogid" class="form-control"/>
                                            </div>                                        
                                            <div class="col-md-1">
                                                <select name="mid" class="form-control">
                                                    <?php for ($i = 1; $i < 31; $i++) { ?>
                                                        <option value='<?php echo $i ?>'>
                                                            <?php echo $i ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="submit" name="submit" class="btn btn-primary pull-right" value="Submit"/>
                                            </div>
                                        </div>

                                    </div>
                                </div> 
                            </div>

                        </div>
                    </div>
                </div>
        </div>
        </form>
        </div>
        <script>
            function getattra(e) {
                $("#singerimageFist").val(e);
                $("#imageviewFist").html('<img style="width:100%;height:55px;" src="' + e + '"/>');
            }
        </script>

        <?php
    }
?>