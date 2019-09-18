<?php
$log_id = $this->session->userdata ( 'user_id' );
 if ($log_id == 2 || $log_id == 527 || $log_id == 511) { ?>
    <style>
        .radio-inline{}
        .error {color: red}
.in-online{
    float: left;
    width: 100px;
    overflow: hidden;
    height: 80px;
    margin-bottom: 30px;
    background: rgba(255, 255, 255, 0.38);
    margin-left:30px;
    }
    .reblog-post-link,.share-post-link,#NibbleBit-Post-Rating-1,#headerdiv,.postmetadata,#comment{display:none}.narrowcolumn{width:inherit!important;margin:0;padding:10px}.in-online .counte{background: url(http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif) center center no-repeat;background-size: 27px; height:29px;}
.in-online a{color:#000;}
.in-online .counter{
    text-align: center;
    font-size: 30px;
    padding: 3px;
    font-weight: bold;
    color: #fff;
    text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.5);
}
.in-online a span{padding:5px 3px 0 3px;display:block;height:30px;overflow:hidden;font-size: 14px;line-height: 15px;}
@media(max-width: 468px) {
    .in-online{width:29%}
}#images{background-size:100%;background-attachment: fixed;}
.online{
    position: relative;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999;
}
#images {
    position: fixed;
    top: -30px;
    left: -9%;
    right: 0;
    bottom: 0;
    z-index: 1;
    height: 138%;
    width: 120%;
}
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
                        <?php if (!empty($title)): echo $title; endif; ?>
                    </h4>                     
                    <div class="toolbar no-padding">
                    </div>
                </div>
                <div class="widget-content">
                    
                    <div class="online">
<div id="getOnline">
    <div style="clear:both"></div><center><h3>+++++++++ ONLINE +++++++++</h3><div class="form-horizontal row-border" id="setnumber" method="post">';
    <div class="form-group">
                            <div class="col-md-12">
                                <input type="number" id="getnumber" value="30"/>
                                <label>វិនាទី</label>
                                <input type="button" id="summitnumber" value="Save"/>
                            </div>
                        </div>
                    </div></center><div style="clear:both"></div>
                    <div id="setAnother">
                         <div id="histats_counter"></div>
<!-- Histats.com  START  (aync)-->
<script type="text/javascript">var _Hasync= _Hasync|| [];
_Hasync.push(['Histats.start', '1,4312325,4,109,150,20,00011111']);
_Hasync.push(['Histats.fasi', '1']);
_Hasync.push(['Histats.track_hits', '']);
(function() {
var hs = document.createElement('script'); hs.type = 'text/javascript'; hs.async = true;
hs.src = ('//s10.histats.com/js15_as.js');
(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(hs);
})();</script>
<noscript><a href="/" target="_blank"><img  src="//sstatic1.histats.com/0.gif?4312325&101" alt="web counter" border="0"></a></noscript>
<!-- Histats.com  END  -->
                    </div>
                    <div id="setEachOnline"></div>
</div>
<div style="clear:both;padding:10px;margin-bottom:20px"></div>
</div>
<div style="clear:both;padding:10px;margin-bottom:20px"></div>
<div class="result"></div>
<div id="images"></div>
                </div>
            </div>
        </div>

    </div>

    </div>
<script>
var myImages = new Array("https://preykabbas.files.wordpress.com/2012/08/dsc_0983.jpg?w=1270", "http://2.bp.blogspot.com/-XhOJjTBD5U0/TnJNoWyndXI/AAAAAAAABWA/9C-PcQm5RT0/s1600/hd+wallpaper+TheWallpaperDB.blogspot+%25284%2529.jpg", "https://preykabbas.files.wordpress.com/2011/04/e19e95e19f92e19e9be19ebce19e9ce19e96e19eb8e19e80e19f84e19f87e19e80e19ebbe19e84-e19e91e19f85e19e80e19f92e19e9ae19ebbe19e84e19e80e19f863.jpg", "http://1.bp.blogspot.com/_90zJnGPR5Uk/TSpyYqj3CDI/AAAAAAAAAGA/zbI0IE65M8s/s1600/sun-rays-coming-out-of-the-clouds-in-a-blue-sky-wallpaper.jpg", "http://4.bp.blogspot.com/_w1aB3ZpYpYg/TOekj_S78oI/AAAAAAAAABU/aoDCgzYrTOQ/s1600/Rain_Cloud_9374_1024_768.jpg","https://2.bp.blogspot.com/-l6WKVIb-ADQ/TfCu2qBP0AI/AAAAAAAAOd8/Pux1zDKFbWo/s1600/www.ipakway.blogspot.com+%252823%2529.png","https://s-media-cache-ak0.pinimg.com/736x/26/8c/19/268c191dafe3d6be830ae2b0ffea60bf.jpg");


var otherOnline = [
{"name":"SN","online":"280gjz04q9"},
{"name":"SN1","online":"nb21iphzm4"},
{"name":"TK","online":"kdw1lefeye"},
{"name":"SR","online":"yi2j8mup2e"},
{"name":"SR1","online":"hav0r66nuak"},
{"name":"KV","online":"nlgy5qkgyp"},
{"name":"DV","online":"vftoa1pqkv"},
{"name":"BB1","online":"f9c95vmq0z"},
{"name":"BS","online":"djspuafipc"},
{"name":"SrL","online":"f9ep0rok78"},
{"name":"R","online":"cg5tms2uhr"},
{"name":"KHun","online":"egofkf08xp"},
{"name":"00","online":"awadirz9j2"},
{"name":"01","online":"x8ywnliba3"},
{"name":"02","online":"em9y9nqjd3"},
{"name":"03","online":"f1prv1hspg"},
{"name":"04","online":"6siyjsyhtu"},
{"name":"05","online":"f9ep0rok78"},
{"name":"06","online":"g1ly4mjyq8"},
{"name":"07","online":"e3nbr2hkxb"},
{"name":"BB","online":"55vfmrfk3z"},
{"name":"08","online":"awadirz9j2"},
{"name":"09","online":"v8j41rgrv2"},
{"name":"10","online":"2b3my4zgjg"},
{"name":"11","online":"7uqwbq7jgl"},
{"name":"12","online":"wxxe0ijehp"},
{"name":"13","online":"et29bympcs"},
{"name":"14","online":"b4b17qs3gw"},
{"name":"15","online":"s68lh5v5ne"},
{"name":"16","online":"1omb95880q"},
{"name":"17","online":"uz46982ien"},
{"name":"18","online":"zvlonkdtyz"},
{"name":"19","online":"xmjtx48ybs"},
{"name":"News 1","online":"186p1xd648"},
{"name":"News 2","online":"1pmflfi2ac"},
{"name":"Sandy","online":"wseq7gnntu"},
];
var items = ["FF5647","FF3CAE","EA49FF","5D5BFF","62C0FF","00E08A","00E309","FF8537","FF000F","FD0044","E9A100","7CBA00","40C200","FE3EFF","C78FFF","72D8FF","007F23","00C61A","157200","3A6F00","647A00","D7D900","E37A00","DC3A00","DD0017","DD0067","C2008B","740057","940052","A1001C","9C1400","9D4A00","845D00","5E6400"];
var setnumbers = document.getElementById("getnumber").value * 1000;
$(document).ready(function(){
$( "body" ).mousemove(function( event ) {
    $('.online').css('opacity:', 100);
    $("#summitnumber").click(function () {
        $('#getnumber').val($('#getnumber').val());
        setnumbers = document.getElementById("getnumber").value * 1000;
    });
});

setOnline();
//$('#content').remove();
setInterval(function(){
    console.log(setnumbers);
    setOnline();
}, setnumbers);
    

     var random = myImages[Math.floor(Math.random() * myImages.length)];
        random = 'url(' + random + ')';
        $('#images').css('background-image', random);

        setInterval(function() {
            SetImage();
        }, 30000);
});

function randomColor(arg) {
        return arg[Math.floor(Math.random() * arg.length)];
}

function setOnline(data) {
//randomColor(items)
    d = new Date();    
    online = '';
    $.each( otherOnline, function( i, item ) {
        var colorset = getRandomColor();
        online += '<div class="in-online" style="border:1px solid '+colorset+'"><a href="http://whos.amung.us/stats/'+item.online+'" target="_blank"><div class="counter" id="other'+i+'" style="background-color:'+colorset+'"><div style="background:#000;display:block"><img style="height:20px;" src="http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif" height="20"/></div></div><span>'+item.name+'</span></a></div>';
        $.get( "<?php echo base_url();?>managecampaigns/ajax?id=" + item.online + "&p=online", function( data ) {
          $('#other' + i).html(data);
        });
    });
    
    $("#setEachOnline").html(online);
}
function getonline (id) {
    $.get( "http://hetkar.com/ajaxcheck?id=count&online=" + id, function( data ) {
      $( ".result" ).html( data );
      alert( "Load was performed." );
    });
}
function SetImage() {
        var random = myImages[Math.floor(Math.random() * myImages.length)];

        random = 'url(' + random + ')';
        $('#images').fadeOut(2000);

        setTimeout(function () {
            $('#images').css('background-image', random);
            $('#images').fadeIn(2000);
        }, 2000);
    }
function getRandomColor() {
    var letters = '0123456789ABCDE'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 15)];
    }
    return color;
}
</script>

    <?php

} else {
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
?>