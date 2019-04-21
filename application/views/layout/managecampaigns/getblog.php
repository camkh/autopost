<?php if ($this->session->userdata('user_type') != 4) { ?>
    <style>
        .radio-inline{}
        .error {color: red}
    </style>
    <link href="https://fonts.googleapis.com/css?family=Hanuman" rel="stylesheet">
    <style>.khmer{font-size:20px;padding:40px;font-family: Hanuman, serif!important;} .alertkh {font-size: 30px;color: #ff0000;text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.5);}</style>
    <div class="page-header">
    </div>
    <div class="row">

        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4>
                        <i class="icon-reorder">
                        </i>
                        Get blog list
                    </h4>                     
                    <div class="toolbar no-padding">
                    </div>
                </div>
                <div class="widget-content">
                    <form method="post" id="validate" class="form-horizontal row-border">
                        <div class="form-group" id="iMacrosGroupsWrap">
                            <div class="col-md-11">
                                <textarea class="form-control" name="iMacrosid" cols="5" rows="3" placeholder="Groups ID: 123,456,789" id="iMacrosid"></textarea>
                            </div>
                            <div class="col-md-1"><input type="button" class="uniform" name="allbox" id="getImacrosID" value="Get ID" /></div>
                        </div>
                        <div style="height:400px;overflow: auto;">
                            <table class="table table-striped table-condensed table-hover table-checkable datatable">
                                <thead>
                                    <tr>
                                        <th style="width:10px"><input type="checkbox" class="uniform" name="allbox"
                                            id="checkAll" /></th>
                                        <th style="width:135px">ID</th>
                                        <th>Title</th>
                                    </tr>
                                </thead>
                                <tbody id="group_from_imacros">                                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <div class="alert alert-warning fade in khmer alertkh"> 
                                <i class="icon-remove close" data-dismiss="alert"></i> 
                                <strong>សូមប្រយ័ត្ន!</strong> មើលឈ្មោះប្លុក ឲ្យបានច្បាស់លាស់ <br/>តិចច្រឡំចំប្លុក ដែលមានឃោសនា អាចធ្វើឲ្យអ្នកបាត់ចំណូលបាន. 
                            </div>
                        </div>
                        <div class="form-actions">
                            Total/<span class="khmer">សរុប</span>: <span id="btotal">0</span><span class="khmer">ប្លុក</span>
                            <input type="submit" name="submit" value="Add" class="btn btn-primary pull-right"/>
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
        $(document).ready(function() {
            $("#getImacrosID").click(function() {
                var gemil = "<?php echo $this->session->userdata ('gemail');?>";
                var bIDs = $('#iMacrosid').val();
                var obj = JSON.parse(bIDs);
                var dataUser = "";
                if(gemil != obj[0].gemail) {
                    $("#gemila").html(gemil);
                    $("#gemilb").html(obj[0].gemail);
                    $('#cropModal').modal('show');
                    return false;
                } 
                if(gemil == obj[0].gemail) {
                    for (i = 0; i < obj.length; i++) { 
                        dataUser += '<tr>';
                        dataUser += '<td style="width:10px" class="checkbox-column">';
                        dataUser += '<input type="checkbox" id="itemid" name="bid[]" class="uniform" value="' + obj[i].bid + '" /></td>';
                        dataUser += '<td style="width:135px"><a href="https://www.blogger.com/blogger.g?blogID=' + obj[i].bid + '" target="_blank">' + obj[i].bid + '</a></td>';
                        dataUser += '<td><a href="https://www.blogger.com/blogger.g?blogID=' + obj[i].bid + '" target="_blank">' + obj[i].btitle + '</a></td>';
                        dataUser += '</tr>';
                    }
                    $("#group_from_imacros").html(dataUser);
                    $("#iMacrosGroupsWrap").fadeOut();
                }
            });

            $('#checkAll').click(function () {
                 $('input:checkbox').not(this).prop('checked', this.checked);
             });
             $('#multidel').click(function () {
                 if (!$('#itemid:checked').val()) {
                        alert('please select one');
                        return false;
                } else {
                        return confirm('Do you want to delete all?');
                }
             });
             var $checkboxes = $('input:checkbox');
           //  $checkboxes.change(function(){
            $(document).on('change', 'input:checkbox', function() {
                var countCheckedCheckboxes = $('input:checkbox').filter(':checked').length;
                $('#btotal').html(countCheckedCheckboxes);
            });
        });
    </script>
<div class="modal fade khmer" id="cropModal" role="dialog" aria-labelledby="cropModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="cropModalLabel"><span class="khmer">អ្នកប្រើខុសអ៊ីមែលហើយ</span></h4>
      </div>
      <div class="modal-body bbody">
            <center><p class="khmer" style="color: red;font-size: 18px !important;">not this account /អ្នកប្រើអ៊ីមើលនេះ  <span id="gemila" style="color: blue!important"></span> ក្នុងកម្មវិធីអូតូនេះ <br/>ដល់ទៅយកប្លុកអ្នកប្រើអ៊ីមែលនេះ  <span id="gemilb" style="color: blue!important"></span> ទៅវិញ</p>
</center>
      </div>
      <div class="modal-footer"><button onclick="myStopFunction()" data-dismiss="modal" class="btn btn-default" type="button"><span class="khmer">បិទផ្ទាំង</span></button></div>
    </div>
  </div>
</div>
    <?php

} else {
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
?>