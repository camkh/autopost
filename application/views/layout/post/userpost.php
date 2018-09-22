<?php if ($this->session->userdata('user_type') != 4) {
    if(!empty($Thumbnail)) {
        @$BigThumbnail = str_replace("/s230", "/s000", $Thumbnail);
    }else {
        $BigThumbnail = '';
    }
    ?>
    <style>
        .radio-inline{}
        .error {color: red}
    </style>
    <div class="page-header">
    </div>
    <div class="row">
        <form method="post" id="validate" class="form-horizontal row-border">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <input name="submit" type="submit" value="Publish" class="btn btn-primary pull-right" /><h4>
                            <i class="icon-reorder">
                            </i>
                            Add New Post
                        </h4>                     
                        <div class="toolbar no-padding">
                        </div>
                    </div>
                    <div class="widget-content">
                        <div class="row" style="margin-bottom:10px;">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="col-md-4">Title</label>
                                    <div class="col-md-8">
                                        <input type="text" value="<?php echo $vdotitle; ?>" name="title" class="required form-control" />
                                        <input type="hidden" value="<?php echo $vdo_title_id; ?>" name="vdo_title_d"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label class="radio-inline">
                                            <input type="radio" value="jwplayer" name="codetype" class="required" />
                                            <i class="subtopmenu hangmeas">JW player Player</i>
                                        </label>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea onclick="this.focus();
                                                    this.select()" class="form-control" name="jwplayerBody" cols="5" rows="3"><?php
                                                  if (!empty($vdolist)):
                                                      $i = 0;
                                                      foreach ($vdolist as $vdo):
                                                          $i++;
                                                          ?><?php if ($i == 1): ?><img border="0" id="noi" src="<?php echo $Thumbnail; ?>" alt="<?php echo $vdotitle; ?>" title="<?php echo $vdotitle; ?>" /><meta property="og:image" content="<?php echo $BigThumbnail; ?>"/><link href="<?php echo $BigThumbnail; ?>" rel="image_src"/><!--more--><script type="text/javascript">jwplayer("player4").setup({"flashplayer": "" + flashplay + "", "playlist": [<?php endif; ?>{"file": "http://youtu.be/<?php echo $vdo->{Tbl_meta::value}; ?>", "title": "" + sitename + " <?php echo $vdotitle; ?>", "description": "<?php echo $vdotitle; ?>", "image": ""},<?php endforeach; ?>], "skin": "" + skinplay + "", "autostart": "" + autostart + "", "playlist.size": "" + playlistsize + "", "playlist.position": "" + p_playlist + "", "repeat": "list", "width": "" + widthplay + "", "height": "" + heightplay + "", "logo": "" + logosite + "", "logo.hide": "false", "logo.position": "bottom-right", "logo.out": "1", "link": "" + slink + "", "dock": "true", "plugins": {"like-1": {}}});</script><?php foreach ($vdolist as $vdo): ?><div align="center"><div style="font-size: 16px;font-weight: bold; padding:10px; clear:both; width:90%;overflow:hidden;margin-bottom:2px; text-align:center;border:1px solid #555;"><a href="/p/player.html?http://youtu.be/<?php echo $vdo->{Tbl_meta::value}; ?>" target="_blank"><?php echo $vdotitle; ?> Part - <?php echo $vdo->{Tbl_meta::key}; ?></a></div></div><?php
                                                endforeach;
                                            endif;
                                            ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label class="radio-inline">
                                            <input type="radio" value="ytlist" name="codetype" class="required" />
                                            <i class="subtopmenu hangmeas">Player List Youtube</i>
                                        </label>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea onclick="this.focus();
                                                    this.select()" class="limited form-control" name="jwplayerYtlist" cols="5" rows="3"><?php
                                                  if (!empty($vdolist)):
                                                      $i = 0;
                                                      foreach ($vdolist as $vdo):
                                                          $i++;
                                                          ?><?php if ($i == 1): ?><img border="0" id="noi" src="<?php echo $Thumbnail; ?>" alt="<?php echo $vdotitle; ?>" title="<?php echo $vdotitle; ?>" /><meta property="og:image" content="<?php echo $BigThumbnail; ?>"/><link href="<?php echo $BigThumbnail; ?>" rel="image_src"/><!--more--><div class="YTV_movies"><div class="YTV_playback" style="height: 315px;"></div><div class="playlist_container"><ul class="YTV_playlist" style="display:block"><?php endif; ?><li class="video"><a class="video" href="#" rel="{'vidId':'<?php echo $vdo->{Tbl_meta::value}; ?>','<?php
                                                    if ($i == 1) {
                                                        echo 'selected';
                                                    } else {
                                                        echo 'loadIfNext';
                                                    }
                                                    ?>':'1','volume':'20'}"><?php echo $vdotitle; ?> Part - <?php echo  $vdo->{Tbl_meta::key}; ?></a></li><?php endforeach; ?></ul><div class="scrollbar"></div></div><div class="infoPanel"><span class="movie_state">...</span><a class="playlist_repeat" title="toggle repeat" href="#"></a><a class="show_playlist" title="toggle playlist" href="#"></a></div></div><?php foreach ($vdolist as $vdo): ?><div align="center"><div style="font-size: 16px;font-weight: bold; padding:10px; clear:both; width:90%;overflow:hidden;margin-bottom:2px; text-align:center;border:1px solid #555;"><a href="/p/player.html?http://youtu.be/<?php echo $vdo->{Tbl_meta::value}; ?>" target="_blank"><?php echo $vdotitle; ?> Part - <?php echo $vdo->{Tbl_meta::key}; ?></a></div></div><?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             endforeach;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         endif;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label class="radio-inline">
                                            <input type="radio" value="onyoutbue" name="codetype" class="required" />
                                            <i class="subtopmenu hangmeas">លែងបានតែក្នុង Youtube</i>
                                        </label>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea onclick="this.focus();
                                                    this.select()" class="limited form-control" name="onyoutbueBody" cols="5" rows="3"><?php
                                                  if (!empty($vdolist)):
                                                      $i = 0;                                                      
                                                          ?><img border="0" id="noi" src="<?php echo $BigThumbnail; ?>" alt="<?php echo $vdotitle; ?>" title="<?php echo $vdotitle; ?>" /><meta property="og:image" content="<?php echo $BigThumbnail; ?>"/><link href="<?php echo $BigThumbnail; ?>" rel="image_src"/><!--more--><div class="separator" style="clear: both; text-align: center;"><img border="0" src="http://2.bp.blogspot.com/-uQ_Esp6Mezs/UrbPJ7GjjDI/AAAAAAAAETo/ve-nIDDz6a4/s1600/Media-play-ico.png" /></div><?php foreach ($vdolist as $vdo): ?><div align="center"><div style="font-size: 16px;font-weight: bold; padding:10px; clear:both; width:90%;overflow:hidden;margin-bottom:2px; text-align:center;border:1px solid #555;"><a href="http://youtu.be/<?php echo $vdo->{Tbl_meta::value}; ?>" target="_blank"><?php echo $vdotitle; ?> Part - <?php echo $vdo->{Tbl_meta::key}; ?></a></div></div><?php
                                                endforeach;
                                            endif;
                                            ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="radio-inline">
                                            <input type="radio" value="1" name="videotype" class="required" />
                                            <i class="subtopmenu hangmeas">កំពុងបន្ត / Countinue</i>
                                        </label> 
                                        <label class="radio-inline">
                                            <input type="radio" value="0" name="videotype" class="required" />
                                            <i class="subtopmenu hangmeas">រឿងចប់ / Finish</i>
                                        </label>                                
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input id="input22" class="select2-select-02 col-md-12 full-width-fix" multiple="" data-placeholder="Type to add a Tag" type="hidden" name="labeladd" value="<?php if (!empty($current_cat)): echo $current_cat;
                                            endif;
                                            ?>" />
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <select name="label[]" id="input18" class="select2-select-00 col-md-12 full-width-fix" multiple="" size="5">
                                            <optgroup label="Alaskan/Hawaiian Time Zone">
                                                <option value="AK,Khmer,Videos,music" />
                                                Alaska
                                                <option value="HI,1,2,3,4" />
                                                Hawaii
                                            </optgroup>
                                            <optgroup label="Pacific Time Zone">
                                                <option value="CA" />
                                                California
                                                <option value="NV" />
                                                Nevada
                                                <option value="OR" />
                                                Oregon
                                                <option value="WA" />
                                                Washington
                                            </optgroup>
                                            <optgroup label="Mountain Time Zone">
                                                <option value="AZ" />
                                                Arizona
                                                <option value="CO" />
                                                Colorado
                                                <option value="ID" />
                                                Idaho
                                                <option value="MT" />
                                                Montana
                                                <option value="NE" />
                                                Nebraska
                                                <option value="NM" />
                                                New Mexico
                                                <option value="ND" />
                                                North Dakota
                                                <option value="UT" />
                                                Utah
                                                <option value="WY" />
                                                Wyoming
                                            </optgroup>
                                            <optgroup label="Central Time Zone">
                                                <option value="AL" />
                                                Alabama
                                                <option value="AR" />
                                                Arkansas
                                                <option value="IL" />
                                                Illinois
                                                <option value="IA" />
                                                Iowa
                                                <option value="KS" />
                                                Kansas
                                                <option value="KY" />
                                                Kentucky
                                                <option value="LA" />
                                                Louisiana
                                                <option value="MN" />
                                                Minnesota
                                                <option value="MS" />
                                                Mississippi
                                                <option value="MO" />
                                                Missouri
                                                <option value="OK" />
                                                Oklahoma
                                                <option value="SD" />
                                                South Dakota
                                                <option value="TX" />
                                                Texas
                                                <option value="TN" />
                                                Tennessee
                                                <option value="WI" />
                                                Wisconsin
                                            </optgroup>
                                            <optgroup label="Eastern Time Zone">
                                                <option value="CT" />
                                                Connecticut
                                                <option value="DE" />
                                                Delaware
                                                <option value="FL" />
                                                Florida
                                                <option value="GA" />
                                                Georgia
                                                <option value="IN" />
                                                Indiana
                                                <option value="ME" />
                                                Maine
                                                <option value="MD" />
                                                Maryland
                                                <option value="MA" />
                                                Massachusetts
                                                <option value="MI" />
                                                Michigan
                                                <option value="NH" />
                                                New Hampshire
                                                <option value="NJ" />
                                                New Jersey
                                                <option value="NY" />
                                                New York
                                                <option value="NC" />
                                                North Carolina
                                                <option value="OH" />
                                                Ohio
                                                <option value="PA" />
                                                Pennsylvania
                                                <option value="RI" />
                                                Rhode Island
                                                <option value="SC" />
                                                South Carolina
                                                <option value="VT" />
                                                Vermont
                                                <option value="VA" />
                                                Virginia
                                                <option value="WV" />
                                                West Virginia
                                            </optgroup>
                                        </select>
                                    </div>
                                </div> 
    <?php if (empty($editaction)) : ?>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <select name="bloginCat" class="form-control" id="blogCat">
                                                <option value="">Select All Blogs</option>
                                                <?php foreach ($blogcatlist as $value): ?>
                                                    <option value="<?php echo $value->{Tbl_title::id}; ?>"><?php echo $value->{Tbl_title::title}; ?></option>
        <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
    <?php endif; ?>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div id="bloglist">
                                            <?php
                                            if (empty($editaction)) :
                                                foreach ($bloglist as $value) :
                                                    ?>
                                                    <label class="checkbox"><input type="checkbox" value="<?php echo $value->{Tbl_title::object_id}; ?>" name="idblog[]"/><?php echo $value->{Tbl_title::title}; ?></label>
                                                    <?php
                                                endforeach;
                                            else:
                                                /* edit action */
                                                foreach ($bloglist as $value) :
                                                    ?>
                                                    <label class="checkbox"><input type="checkbox" value="<?php echo $value[Tbl_title::object_id]; ?>" name="idblog[]" checked/><?php echo $value[Tbl_title::title]; ?></label>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </div>
                                    </div>
                                </div>
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