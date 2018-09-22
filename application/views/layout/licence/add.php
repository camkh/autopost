<?php if ($this->session->userdata('user_type') != 4):
    $post_id = !empty($_GET['id'])?$_GET['id']:'';
    $userId = $this->session->userdata ( 'user_id' );
    ?>
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
                        <input type="hidden" value="<?php echo @$post_id; ?>" name="postid" id="postID"/>
                        <h4>
                            <i class="icon-reorder">
                            </i>
                            Renew your autopost
                        </h4>                     
                        <div class="toolbar no-padding">
                        </div>
                    </div>
                    <div class="widget-content">                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="link"><span class="pull-right">Payment Method:</span></label>
                                    <div class="col-md-8">
                                        <div class="col-md-12">
                                            <label class="radio-inline">
                                                <input type="radio" value="Wing" name="stransferBy" class="required stransferBy">
                                                <img style="height:40px;" src="https://lh3.googleusercontent.com/-d5utMh87soE/Vjo9K5R9CTI/AAAAAAAAOic/3Dc72b-Im8Y/s100-Ic42/6c409919-daf4-4072-8630-4868cbff7bfe.png"/>
                                            </label> 
                                            <label class="radio-inline">
                                                <input type="radio" value="Emoney" name="stransferBy" class="required stransferBy">
                                                <img style="height:40px;" src="https://lh3.googleusercontent.com/-8ql4LSbpW-Y/Vjo_KadXTqI/AAAAAAAAOio/eTMZiqNuoOU/s40-Ic42/e-money-khmer.png"/>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" value="emoney" name="stransferBy" class="required byPaypal">
                                                <img class="byPaypal" style="height:40px;" src="https://lh3.googleusercontent.com/-eu6RzhlcI1Q/VjpAAZYhuVI/AAAAAAAAOiw/8GgTCghVdWA/s150-Ic42/paypal.gif">                                
                                                <img class="byPaypal" style="height:40px;" src="https://lh3.googleusercontent.com/-Dq5SZf0fe6s/VjpAsNajv6I/AAAAAAAAOi4/cg2cyfLVDyI/s231-Ic42/paypal-button.png">                                
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="onPaypal" style="margin-bottom:10px;display:none">
                            <div class="col-md-12" style="text-align:center;">
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" style="text-align:center;padding:20px;">
                                    <input type="hidden" name="cmd" value="_s-xclick">
                                    <input type="hidden" name="hosted_button_id" value="F59DGCUQ5BEQG">
                                    <table align="center">
                                    <tr><td><input type="hidden" name="on0" value="List price:">List price:</td></tr><tr><td><select name="os0" class="changeBy">
                                        <option data-id="1" value="1 Week">1 Week $3.00 USD</option>
                                        <option data-id="2" value="2 Week">2 Week $5.00 USD</option>
                                        <option data-id="3" value="1 Month">1 Month $7.00 USD</option>
                                        <option data-id="4" value="3 Months">3 Months $18.00 USD</option>
                                        <option data-id="5" value="6 Months">6 Months $30.00 USD</option>
                                        <option data-id="6" value="1 Year">1 Year $50.00 USD</option>
                                    </select> </td></tr>
                                    </table>
                                    <input type="hidden" name="custom" value="<?php echo $userId;?>"/>
                                    <input type="hidden" id="getnumber" name="item_number" value=""/>
                                    <input type="hidden" name="notify_url" value="<?php echo base_url();?>licence/success"/>
                                    <input type="hidden" name="currency_code" value="USD">
                                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                                </form>
                            </div>
                        </div>
                        <form method="post" id="validate" class="form-horizontal row-border">
                            <input type="hidden" value="wing" name="stransferBy" class="stransferOn">
                            <div class="row" style="margin-bottom:10px;">
                                <div class="col-md-12">
                                <div id="khmer-pay" style="display:none">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="title">Choose your plan:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="money" id="moneyper">
                                                <option selected value="1">1 Week/12,000r (3$)</option>
                                                <option selected value="2">2 Week/20,000r (5$)</option>
                                                <option selected value="3">1 Month/28,000r (7$)</option>
                                                <option value="4">3 Months/72,000r (18$)</option>       
                                                <option value="5">6 Months/12,0000r (30$)</option>
                                                <option value="6">1 Year/200,000r (50$)</option>
                                            </select>
                                            <div style="padding:10px 0;font-size:14px;color:red;display:none">Total Price: <span id="getprice" style="font-size:16px;font-weight:bold;">7</span>$</div>
                                        </div>
                                    </div>                                    
                                    <?php
                                    $isEmail = filter_var($user->email, FILTER_VALIDATE_EMAIL);
                                    if (preg_match('/^[0-9]*$/', $user->email) && !$isEmail) {
                                        $phone = $user->email;
                                    }
                                    ?>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="title">លេខកូដផ្ទេរប្រាក់/Money Transfer ID:</label>
                                            <div class="col-md-8">
                                                <input type="text" value="" name="moneyid" id="moneyid" class="required form-control" required/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="name">ឈ្មោះ/Your name:</label>
                                            <div class="col-md-8">
                                                <input type="text" value="<?php echo @$user->u_name; ?>" name="name" id="name" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="phone">ទូរស័ព្ទ/Phone:</label>
                                            <div class="col-md-8">
                                                <input type="text" value="<?php echo @$phone; ?>" name="phone" id="phone" class="form-control" required/>
                                            </div>
                                        </div> 

                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input name="submit" type="submit" value="Public Content" class="btn btn-primary pull-right" />
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        
    </div>


    <div class="row">
      <div class="col-md-12">
        <div class="widget box">
          <div class="widget-header">
            <h4><i class="icon-reorder"></i>ឬអាចវេរលុយតាមរយៈ Payment Method</h4>
          </div>
          <div class="widget-content">
            <div class="row">
                <div class="col-md-3"><h5 style="color:red">សូមមេត្តាវេរប្រាក់ជាមុនៗនិងធ្វើការស្នើរសុំការប្រើប្រាស់<br/>Please transfer money first then request the licence</h5></div>
                <div class="col-md-3">
                    <h4>Wing</h4>
                    <img style="height:60px" src="https://lh3.googleusercontent.com/-d5utMh87soE/Vjo9K5R9CTI/AAAAAAAAOic/3Dc72b-Im8Y/s120-Ic42/6c409919-daf4-4072-8630-4868cbff7bfe.png">
                    <p>send to phone: 0699 777 11</p>
                </div>
                <div class="col-md-3"><h4>ABA Bank</h4>
                <img style="height:60px" src="https://lh3.googleusercontent.com/-t7MH4-HvwuQ/VkhK1ypaENI/AAAAAAAAOqc/Zd58qnT_5z4/h120/0001.jpg">
                <p>លេខគណនី/Account no: 000120814</p>
                </div>
                <div class="col-md-3">
                    <h4>Public Bank</h4>
                    <img style="height:60px" src="http://2.bp.blogspot.com/-yc_EWRVm7eI/VPfQK-YOciI/AAAAAAAASRg/ek7s2a4K4R8/s120/Kerja%2BKosong%2BPublic%2BBank%2BMalaysia.png">
                    <p>លេខគណនី/Account no: 4289 1914 5926 5451</p>
                </div>
            </div>
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
 else:
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
endif;
?>