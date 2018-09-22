<?php if ($this->session->userdata('user_type') != 4) { ?>
    <style>
        .radio-inline{}
        .error {color: red}
    </style>
    <div class="page-header">
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                    <!-- body -->
                    <div class="col-md-4">
                        <form class="form-horizontal row-border" action="" method="post"> 
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="widget box">
                                        <div class="widget-header">
                                            <input name="submit" type="submit" value="Add" class="btn btn-primary pull-right" /><h4><i class="icon-reorder"></i> Add blog</h4>
                                        </div>
                                        <div class="widget-content">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> ប្រភេទប្លុក / Blog type</label>
                                                <div class="col-md-9">
                                                    <select name="blogtype" class="select2" style="width: 100%" required>
                                                        <option value="" selected>Select Type one</option>
                                                        <option value="blogger_id">Blogs Post</option>
                                                        <option value="blog_link">blog random link</option>
                                                    </select>             
                                                </div>                                   
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Blog ID:</label>              
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="blogID" required />
                                                </div>              
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Blog Name:</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="blogTitle" class="form-control"/>
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


                    <div class="col-md-8">
                        <div class="widget box">
                            <div class="widget-header">
                                <h4><i class="icon-reorder"></i> Blog post</h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span> </div>
                                </div>
                            </div>
                            <div class="widget-content">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($bloglist)):
                                            foreach ($bloglist as $key => $value):?>
                                        <tr>
                                            <td><?php echo $key;?></td>
                                            <td><a href="https://www.blogger.com/blogger.g?blogID=<?php echo $value->bid;?>#allposts/src=sidebar" target="_blank"><?php echo $value->bid;?></a></td>
                                            <td style="width: 50%"><a href="https://www.blogger.com/blogger.g?blogID=<?php echo $value->bid;?>#allposts/src=sidebar" target="_blank"><?php echo $value->title;?></a></td>
                                            <td><span class="label label-success"><?php echo $value->status;?></span></td>
                                            <td>
                                                <ul class="table-controls">
                                                    <li><a href="javascript:void(0);" class="bs-tooltip" title="" data-original-title="Edit"><i class="icon-pencil"></i></a> </li>
                                                    <li><a href="<?php echo base_url();?>managecampaigns/setting?del=<?php echo $value->bid;?>&type=blogger_id" class="bs-tooltip" title="" data-original-title="Delete"><i class="icon-trash" style="color: red"></i></a> </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <?php endforeach; endif;?>                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- blog link -->
                        <div class="widget box">
                            <div class="widget-header">
                                <h4><i class="icon-reorder"></i> Blog post</h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span> </div>
                                </div>
                            </div>
                            <div class="widget-content">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($bloglink)):
                                            foreach ($bloglink as $key => $link):?>
                                        <tr>
                                            <td><?php echo $key;?></td>
                                            <td><a href="https://www.blogger.com/blogger.g?blogID=<?php echo $link->bid;?>#allposts/src=sidebar" target="_blank"><?php echo $link->bid;?></a></td>
                                            <td style="width: 50%"><a href="https://www.blogger.com/blogger.g?blogID=<?php echo $link->bid;?>#allposts/src=sidebar" target="_blank"><?php echo $link->title;?></a></td>
                                            <td><span class="label label-success"><?php echo $link->status;?></span></td>
                                            <td>
                                                <ul class="table-controls">
                                                    <li><a href="javascript:void(0);" class="bs-tooltip" title="" data-original-title="Edit"><i class="icon-pencil"></i></a> </li>
                                                    <li><a href="<?php echo base_url();?>managecampaigns/setting?del=<?php echo $link->bid;?>&type=blog_link" class="bs-tooltip" title="" data-original-title="Delete"><i class="icon-trash" style="color: red"></i></a> </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <?php endforeach; endif;?>                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End blog link -->
                    </div>

                                        
                    
                    <!-- End body -->
                </div>

                <!-- Prefix and Subfix -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="widget box">
                            <div class="widget-header">
                                <h4><i class="icon-reorder"></i> Prefix for Random Title</h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span> </div>
                                </div>
                            </div>
                            <div class="widget-content">
                                <form class="form-horizontal row-border" action="" method="post">
                                    <div class="form-group">
                                        <div class="col-md-12 clearfix">
                                            <label>អត្ថបទបន្ថែម ពីមុខ / Prefix</label>
                                        <textarea rows="1" cols="5" rows="3" name="Prefix" class="form-control" placeholder="1234|1234|1234"><?php echo @$prefix_title;?></textarea>
                                        បើចង់ថែម ឬដាក់ថ្មី សូមដាក់ដូចខាងក្រោមៈ<br/>Ex: xxxx|xxxx|xxxx|xxxx
                                        </div>                                
                                    </div>
                                    <div class="form-actions"> 
                                        <input type="submit" value="Save" class="btn btn-primary pull-right"> 
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <!-- End Prefix and Subfix -->

                <!-- Prefix and Subfix -->
                    <div class="col-md-6">
                        <div class="widget box">
                            <div class="widget-header">
                                <h4><i class="icon-reorder"></i> Suffix for Random Title</h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span> </div>
                                </div>
                            </div>
                            <div class="widget-content">
                                <form class="form-horizontal row-border" action="" method="post">
                                    <div class="form-group">
                                        <div class="col-md-12 clearfix">
                                            <label>អត្ថបទបន្ថែម ពីក្រោយ / Suffix</label>
                                            <textarea rows="1" cols="5" name="Suffix" class="form-control" placeholder="1234|1234|1234"><?php echo @$suffix_title;?></textarea>
                                            បើចង់ថែម ឬដាក់ថ្មី សូមដាក់ដូចខាងក្រោមៈ<br/>Ex: xxxx|xxxx|xxxx|xxxx
                                        </div>                                
                                    </div>
                                    <div class="form-actions"> 
                                        <input type="submit" value="Save" class="btn btn-primary pull-right"> 
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Prefix and Subfix -->
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