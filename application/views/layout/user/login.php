<div class="form_content">
    <h3 class="form_subtitle">Contact form</h3>
    <div id="Note"></div>
    <div class="form">
        <form class="cmxform" id="CommentForm" method="post" action="">
            <div class="form_row">
                <label for="ContactName" class="overlabel">Name</label>
                <input id="ContactName" name="ContactName" class="form_input required">
            </div>
            <div class="form_row">
                <label for="ContactEmail" class="overlabel">E-Mail</label>
                <input id="ContactEmail" name="ContactEmail" class="form_input required email">
            </div>
            <div class="form_row">
                <label for="ContactComment" class="overlabel">Message</label>
                <textarea id="ContactComment" name="ContactComment" class="form_textarea required" rows="10" cols="4"></textarea>
            </div>
            <div class="form_row">
                <input type="submit" name="submit" class="form_submit" id="submit" value="Send">
                <input class="" type="hidden" name="to" value="bbbooogggs@gmail.com">
                <input class="" type="hidden" name="customlabel" value="">
                <input class="" type="hidden" name="subject" value="Contact subject title">
                <div id="loader" style="display:none;"><img src="http://famousthemes.com/power-gym-wordpress/wp-content/themes/power-gym-wordpress/images/loader.gif" alt="Loading..." id="LoadingGraphic"></div>
            </div>
        </form>
    </div>
</div>
