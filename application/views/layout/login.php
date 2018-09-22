<div class="logo">
    <img src="<?php echo base_url(); ?>themes/layout/blueone/assets/img/logo.png" alt="logo" />
    <strong>
        SABBAY
    </strong>
    .CO
</div>
<div class="box">
    <div class="content">
        <?php
            $attributes = array('class' => 'form-vertical login-form', 'id' => 'login');            
            echo form_open('', $attributes);
        ?> 
            <input type="hidden" name="userlogin" value="userlogin" />
            <h3 class="form-title">
                Sign In to your Account
            </h3>
            <div class="alert fade in alert-danger" style="display: none;">
                <i class="icon-remove close" data-dismiss="alert">
                </i>
                Enter any username and password.
            </div>
            <?php
            /* get message */
            if (validation_errors()) {
                echo '<div class="alert fade in alert-danger" >
                            <i class="icon-remove close" data-dismiss="alert"></i>
                            <strong>' . validation_errors() . '</strong> .
                        </div>';
            }
            ?>
            <div class="form-group">
                <div class="input-icon">
                    <i class="icon-user">
                    </i>
                    <?php
                    $error = (form_error('username') ? 'inputError' : 'username');
                    $username = array(
                        'name' => 'username',
                        'id' => $error,
                        'class' => 'form-control',
                        'placeholder' => 'Username',
                        'autofocus' => 'autofocus',
                        'data-rule-required' => 'true',
                        'data-msg-required' => 'Please enter your username.',
                        'type' => 'text',
                        'value' => set_value('username')
                    );
                    echo form_input($username);
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="input-icon">
                    <i class="icon-lock">
                    </i>
                    <input type="password" name="password" class="form-control" placeholder="Password" data-rule-required="true" data-msg-required="Please enter your password." />
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" name="submit" class="submit btn btn-primary pull-right">
                    Sign In
                    <i class="icon-angle-right">
                    </i>
                </button>
            </div>
        </form>

    </div>

</div>


<script type="text/javascript">
    if (location.host == "envato.stammtec.de" || location.host == "themes.stammtec.de") {
        var _paq = _paq || [];
        _paq.push(["trackPageView"]);
        _paq.push(["enableLinkTracking"]);
        (function() {
            var a = (("https:" == document.location.protocol) ? "https" : "http") + "://analytics.stammtec.de/";
            _paq.push(["setTrackerUrl", a + "piwik.php"]);
            _paq.push(["setSiteId", "17"]);
            var e = document,
                    c = e.createElement("script"),
                    b = e.getElementsByTagName("script")[0];
            c.type = "text/javascript";
            c.defer = true;
            c.async = true;
            c.src = a + "piwik.js";
            b.parentNode.insertBefore(c, b)
        })()
    }
    ;
</script>