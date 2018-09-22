<?php
if (!empty($tvlist)):
    foreach ($tvlist as $value):
        $tv_image = $value->{Tbl_title::image};
        $tv_title = $value->{Tbl_title::title};
    endforeach;
    ?>
    <style>.post_thumb{width:73px;height: 73px;}</style>
    <div class="slider_container">
        <div class="flexslider">
            <script src='http://w.sharethis.com/button/buttons.js' type='text/javascript'></script>
            <div class="left23 left_content"><?php include 'tv/player.php'; ?></div>
            <div class="left13 sidebar">
                <style>
                    .respondsive-adleft { width: 300px; height: 250px; }
                    @media(min-width: 781px) { .respondsive-adleft { width: 468px; height: 60px; }}
                    @media(min-width: 950px) { .respondsive-adleft { width: 300px; height: 250px; }}
                    @media only screen and (min-width:469px) and (max-width:768px) {.respondsive-adleft { width: 300px; height: 250px; } }
                    @media(max-width: 468px) { .respondsive-adleft { width: 320px; height: 50px;  }}            
                </style>
                <script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- Respondsive Ad -->
                <ins class="adsbygoogle respondsive-adleft"
                     style="display:inline-block"
                     data-ad-client="ca-pub-7674908109535265"
                     data-color-border = "000000"
                     data-color-bg = "000000"
                     data-color-link = "ff0066"
                     data-color-text = "ffffff"
                     data-color-url = "ff0066"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
                <div  style="margin-top: 10px;"></div>
                <span class='st_plusone_vcount' displayText='Google +1'></span>
                <span class='st_fblike_vcount' displayText='Facebook Like'/></span>
                <span class='st_facebook_vcount' displayText='Facebook'></span>
                <span class='st_twitter_vcount' displayText='Tweet'></span>
                <span class='st_sharethis_large' displayText='ShareThis'></span>
            </div>
            <div class="clear"></div>
        </div>

        <?php include('themes/layout/power-gym-wordpress/ads-728.php'); ?>
    </div>


    <!-- Elastislide Carousel -->
    <div id="carousel" class="es-carousel-wrapper">
        <div class="es-carousel">

            <ul>
                <?php
                if (!empty($related_tv)):
                    foreach ($related_tv as $value):
                        ?>
                        <li>
                            <div class="sport_icon">
                                <?php
                                $id = $value->{Tbl_title::url};
                                $title = $value->{Tbl_title::title};
                                $image = '<img src="' . $value->{Tbl_title::image} . '" alt="" class="" />';
                                $id = $value->{Tbl_title::url};
                                echo anchor($id, $image, 'title="' . $title . '"');
                                ?>
                            </div>
                            <h2><?php echo anchor($id, $title, 'title="' . $title . '"'); ?></h2>
                            <p><?php echo $value->{Tbl_title::desc}; ?></p>                  
                        </li>
                        <?php
                    endforeach;
                endif;
                ?>
            </ul>
        </div>
    </div>
    <!-- End Elastislide Carousel -->


    <div class="content">
        <div class="home_widgets">    
            <div class="left13"> 
                <?php include('themes/layout/power-gym-wordpress/sidebar-left.php'); ?>
            </div>

            <div class="left13"> 
                <div class="widget-categories">
                    <h2>TV Detail: (<?php echo @$site_count; ?>)</h2> 
                    <div class="post_small">
                        <img src="<?php echo $tv_image; ?>" alt="" class="post_thumb" />
                        <h3><a href="javascript:;" rel="bookmark" title="<?php echo $tv_title; ?>"><?php echo $tv_title; ?></a></h3>
                        <p><?php echo $site_desc; ?></p>
                    </div>
                    <p>Dear visitors, We try to find all TV to post on our website, because we have many visitors over the world request us to post other TV on this site. Well, we would like to thank to visitors who support us for taking their times to write the comments to us to tell about problem of this website. We want to say that, please feel free to tell us if you want to request any TV. You can post it in the comment.</p>
                </div>
                <div class="widget-categories">
                    <h2>Popular TV</h2> 
                    <?php
                    if (!empty($toptv)):
                        foreach ($toptv as $value):
                            ?>
                            <div class="post_small">
                                <?php
                                $id = $value->{Tbl_title::url};
                                $title = $value->{Tbl_title::title};
                                $image = '<img src="' . $value->{Tbl_title::image} . '" alt="" class="post_thumb" />';
                                $id = $value->{Tbl_title::url};
                                echo anchor($id, $image, 'title="' . $title . '"');
                                ?>
                                <h3><?php echo anchor($id, $title, 'title="' . $title . '"'); ?></h3>
                                <p><?php echo $value->{Tbl_title::desc}; ?></p>
                                <div class="clear"></div>
                            </div>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>


            <div class="left13"> 
                <?php include('themes/layout/power-gym-wordpress/sidebar-right.php'); ?>
            </div>
        </div>
        <div class="clear"></div>
        <div class="slider_container">
            <div id="fb-root" style="width:300px;"></div>
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id))
                            return;
                        js = d.createElement(s);
                        js.id = id;
                        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=252730688210724&version=v2.0";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>
            <div class="fb-comments" data-href="<?php $site_url; ?>" data-numposts="5" data-colorscheme="light" width="500" align="center"></div>
        </div>
        <div class="clear"></div>
        <div style="width:100%;height:1px;overflow:hidden">
            <h2>Forex - USD/CAD touches session highs after weak Canadian data.</h2>
            <p>The U.S. dollar edged up to session highs against the Canadian dollar on Wednesday after data showed that the number of new building permits issued in Canada fell unexpectedly in March, but gains were checked ahead of testimony by Federal Reserve Chair Janet Yellen later in the session.<br>
                Forex - USD/CAD touches session highs after weak Canadian dataU.S. dollar touches session highs vs. Canadian dollar after weak Canadian housing data<br>
                USD/CAD edged up to 1.0902 from 1.0890 ahead of the data. The pair was likely to find support at 1.0857 and resistance at 1.0950.<br>
                The Canadian dollar dipped after Statistics Canada reported that the number of new building permits issued in March tumbled 3%, confounding expectations for a gain of 4.3%.<br>
                February’s figure was revised to a drop of 11.3% from a previously reported decline of 11.6%.<br>
                The greenback remained under pressure ahead of testimony to Congress by U.S. central bank head Janet Yellen later Wednesday. Ms. Yellen was widely expected to reiterate interest rates will remain on hold for longer, in spite of last month’s stronger-than-forecast U.S. nonfarm payrolls report.<br>
                Concerns over the crisis in Ukraine also weighed on market sentiment, as conflict between the government and pro-Russian separatists in the east and south of the country continued to escalate, fuelling fears over a civil war.<br>
                Elsewhere, the loonie, as the Canadian dollar is also known, was almost unchanged against the euro, with EUR/CAD trading at 1.5178.</p>
        </div>
    </div>
    <?php
else:
    $url = base_url();
    switch ($tv_url) {
        case '2013/04/live-tv3-online-channel-khmer-live-tv':
            header('Location: ' . $url . '2013/04/live-tv3-online-channel-khmer-live-tv-kh.html');
            exit();
            break;
        case '2013/04/live-apsara-tv-online-tv-channel-11':
            header('Location: ' . $url . '2013/04/live-apsara-tv-online-tv-channel-11-kh.html');
            exit();
            break;
        case '2013/04/live-tv5-online-5-channel-khmer-live-tv':
            header('Location: ' . $url . '2013/04/live-tv5-online-5-channel-khmer-live-tv-kh.html');
            exit();
            break;
        case '2011/08/watch-tv-9-live-tv-from-cambodia':
            header('Location: ' . $url . '2011/08/watch-tv-9-live-tv-from-cambodia-kh.html');
            exit();
            break;
        case '2013/04/live-sea-tv-online-channel-khmer-live':
            header('Location: ' . $url . '2013/04/live-sea-tv-online-channel-khmer-live-kh.html');
            exit();
            break;
        case '2011/08/test_07':
            header('Location: ' . $url . '2011/08/voa-voice-of-america-khmer.html');
            exit();
            break;
        case '2011/08/tvk-channel-khmer-live-tv-online':
            header('Location: ' . $url . '2011/08/tvk-channel-khmer-live-tv-online-kh.html');
            exit();
            break;
    }
    ?>
    <div class="content">
        <div class="home_widgets" align="center" style="padding:30px;">
            <META http-equiv="refresh" content="10;URL=<?php echo base_url(); ?>"/>
            <h1>404 error - Document not found</h1>
            <h2><a href="<?php echo base_url(); ?>">Click here to proceed to the main page</a></h2>
            <h2><a href="<?php echo base_url(); ?>">wwiTV.co</a></h2>
        </div>
    </div>
<?php endif; ?>
<?php include('themes/layout/power-gym-wordpress/footer.php'); ?>

<div class="clear"></div>
