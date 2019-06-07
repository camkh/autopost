<?php if ($this->session->userdata('user_type') != 4) { ?>
    <style>
        .radio-inline{}
        .error {color: red}
        #defaultCountdown { width: 340px; height: 100px; font-size: 20pt;margin-bottom: 20px}
        .khmer {font-family: 'Koulen', cursive;font-size: 30px}
        .table tbody tr.trbackground,tr.trbackground {background:#0000ff!important;}
        .trbackground a,.trbackground {color:red;}
        .watermarker-wrapper .watermarker-container .resizer{background-image: url("<?php echo base_url();?>uploads/image/watermark/watermark/resize.png");}
        .icon {width: 25px}
        .icon-choose {height: 50px;cursor: pointer;border: 1px solid white;float: left;padding: 5px;}
        .icon-choose:hover {border: 1px solid red}
        .water-wrap {margin: 10px 5px 5px 10px;border: 1px solid #eee;padding: 3px}
        .tooltip {font-family: 'Battambang';font-size: 14px!important;font-weight: 400!important;}
        .khmer {font-family: 'Battambang';font-size: 14px!important;font-weight: 400!important;}
        .water-wrap {max-height: 350px;overflow-y: auto;}
    </style>
    <link href="https://fonts.googleapis.com/css?family=Battambang" rel="stylesheet">
    <link rel="StyleSheet" href="<?php echo base_url(); ?>themes/layout/blueone/plugins/watermarker/watermarker.css" type="text/css">
    <script src="<?php echo base_url(); ?>themes/layout/blueone/plugins/watermarker/watermarker.js"></script>

    <link rel="StyleSheet" href="<?php echo base_url(); ?>themes/layout/blueone/plugins/image-filter/jquery-ui.css" type="text/css">
    <script src="<?php echo base_url(); ?>themes/layout/blueone/plugins/image-filter/caman.full.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>themes/layout/blueone/plugins/image-filter/jquery-ui.min.js" type="text/javascript"></script>
    <div class="page-header">
    </div>
    <div class="row">
                <div class="col-md-8">
                    <div class="widget box"> <div class="widget-header"> <h4><i class="icon-reorder"></i> Preview</h4> </div> <div class="widget-content"> <div class="imagecontainer"><div class= "image" >
                        <img src="<?php echo base_url();?>uploads/image/watermark/watermark/picture.jpg" id="imagewater">
                    </div></div>
        </div> </div>
                </div>
                <div class="col-md-4">
                    <div class="widget box"> <div class="widget-header"> <h4><i class="icon-reorder"></i> Watermark</h4> </div> <div class="widget-content"> 
                            <div class="info form-horizontal row-border" style="margin: 0 5px;">
                                <div class="form-group">
                                  <label class="radio-inline" style="margin-left: 5px">
                                      <input type="radio" value="text" name="watermarkchooser" class="required" required />
                                      <img class="icon" src="<?php echo base_url();?>uploads/image/watermark/icon/text.png">
                                  </label> 
                                  <label class="radio-inline">
                                      <input type="radio" value="shape" name="watermarkchooser" class="required" required />
                                      <img class="icon" src="<?php echo base_url();?>uploads/image/watermark/icon/shape.png">
                                  </label>
                                  <label class="radio-inline">
                                      <input type="radio" value="sticker" name="watermarkchooser" class="required" required />
                                      <img class="icon" src="<?php echo base_url();?>uploads/image/watermark/icon/heart-smiley-icon.png">
                                  </label>
                                  <div id="choosetext" class="water-wrap" style="display: none;">
                                    <img class="icon-choose bs-tooltip"  data-original-title="មកដល់ហើយ" src="<?php echo base_url();?>uploads/image/watermark/text/comeback.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ឆ្នោតមកក្ដៅៗ" src="<?php echo base_url();?>uploads/image/watermark/text/houy-ded.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ឆ្នោតល្បី" src="<?php echo base_url();?>uploads/image/watermark/text/houy-dung.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="លេខមកក្ដៅៗ" src="<?php echo base_url();?>uploads/image/watermark/text/lek-ded.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="លេខកំពុងល្បី" src="<?php echo base_url();?>uploads/image/watermark/text/lek-kamlang-dang.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ខ្ញុំបង្ហោះឲ្យហើយណា!" src="<?php echo base_url();?>uploads/image/watermark/text/post-now.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="សេវទុកទៅ" src="<?php echo base_url();?>uploads/image/watermark/text/save-it.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="អ្នកណាមានលេខនេះខ្លះ?" src="<?php echo base_url();?>uploads/image/watermark/text/who-have-this.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="អ្នកណាគេកំពុងរង់ចាំ" src="<?php echo base_url();?>uploads/image/watermark/text/who-wait-for.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="មិនបាច់ចាំទេ" src="<?php echo base_url();?>uploads/image/watermark/text/harry-up.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="លេខបែកធ្លាយ" src="<?php echo base_url();?>uploads/image/watermark/text/get-it.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="អ្នកជំពាក់ធនាគារច្រើន ហាមរំលង" src="<?php echo base_url();?>uploads/image/watermark/text/dont-giveup.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ត្រូវមកច្រើនដងហើយ" src="<?php echo base_url();?>uploads/image/watermark/text/all.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="លេខ២ខ្ទង់ត្រង់ៗ" src="<?php echo base_url();?>uploads/image/watermark/text/2-trong.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="លេខ៣ខ្ទង់ត្រង់ៗ" src="<?php echo base_url();?>uploads/image/watermark/text/3-rong.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="កុំអាលថយ" src="<?php echo base_url();?>uploads/image/watermark/text/back.png"/>
                                    <div style="clear: both;"></div>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ឆ្នោតវគ្គនេះ ប៉ុស្តិ៍ឲ្យហើយ" src="<?php echo base_url();?>uploads/image/watermark/text/posted.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="យកទៅ មានហើយ!" src="<?php echo base_url();?>uploads/image/watermark/text/rich.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចមើលទៅ!" src="<?php echo base_url();?>uploads/image/watermark/text/see.png"/>
                                    <div style="clear: both;"></div>
                                  </div>
                                  <div id="chooseshape" class="water-wrap" style="display: none;">
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសបែបជ្រុង" src="<?php echo base_url();?>uploads/image/watermark/shapes/sqare.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសបែបរង្វង់" src="<?php echo base_url();?>uploads/image/watermark/shapes/ellipse.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសរង្វង់បែបដៃ" src="<?php echo base_url();?>uploads/image/watermark/shapes/roundel.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសព្រួញ" src="<?php echo base_url();?>uploads/image/watermark/shapes/blue-point-l.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសព្រួញ" src="<?php echo base_url();?>uploads/image/watermark/shapes/blue-point-r.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសព្រួញ" src="<?php echo base_url();?>uploads/image/watermark/shapes/point-left.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសព្រួញ" src="<?php echo base_url();?>uploads/image/watermark/shapes/point-right.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសបិទអក្សរឬជ្រុង" src="<?php echo base_url();?>uploads/image/watermark/shapes/sqare-white.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="គូសបិទអក្សរឬជ្រុង" src="<?php echo base_url();?>uploads/image/watermark/shapes/blur.png"/>
                                    <div style="clear: both;"></div>
                                  </div>
                                  <div id="choosesticker" class="water-wrap" style="display: none;">
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/emj/pointing-finger-right.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/emj/pointing-finger-left.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/emj/folded-hand.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/emj/Tongue_Out_Emoji_with_Winking_Eye.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/stickers/1.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/stickers/Blushing.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/stickers/heart-smiley-heart.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/stickers/love-eyes.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/stickers/love-eyes-heart.png"/>
                                    <img class="icon-choose bs-tooltip"  data-original-title="ចុចលើវា ដើម្បីយក" src="<?php echo base_url();?>uploads/image/watermark/stickers/so-good.png"/>
                                    <div style="clear: both;"></div>
                                  </div>
                                </div>

                                <div class="form-group"> <label class="col-md-4 control-label">X:</label> <div class="col-md-8"><input type="text" name="regular" class="form-control" id="posx"></div> </div>

                                <div class="form-group"> <label class="col-md-4 control-label">Y:</label> <div class="col-md-8"><input type="text" name="regular" class="form-control" id="posy"></div> </div>

                                <div class="form-group"> <label class="col-md-4 control-label">Width:</label> <div class="col-md-8"><input type="text" name="regular" class="form-control" id="width"></div> </div>

                                <div class="form-group"> <label class="col-md-4 control-label">Height:</label> <div class="col-md-8"><input type="text" name="regular" class="form-control" id="height"></div> </div>

                                <div class="form-group"> <label class="col-md-4 control-label">Mouse X:</label> <div class="col-md-8"><input type="text" name="regular" class="form-control" id="mousex"></div> </div>

                                <div class="form-group"> <label class="col-md-4 control-label">Mouse Y:</label> <div class="col-md-8"><input type="text" name="regular" class="form-control" id="mousey"></div> </div>        
                            </div>     
                </div> </div>


                </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="widget box"> <div class="widget-header"> <h4><i class="icon-reorder"></i> Image filter</h4> </div> <div class="widget-content align-center"> 
                            <div class="info form-horizontal row-border" style="margin: 0 5px;">
                                    <div id="blur" type="range">Blur</div> 
                                  <div id="grayscale" type="range">Grayscale</div> 
                                  <div id="brightness" type="range">brightness</div>
                                  <div id="contrast" type="range">contrast</div>
                                  <div id="rotate" type="range">huerotate</div>
                                  <div id="invert" type="range">invert</div>
                                  <div id="opacity" type="range">opacity</div>
                                  <div id="saturate" type="range">saturate</div>
                                  <div id="sepia" type="range">sepia</div>
                                  <div style="clear: both;"></div> 
                                  <div class="form-group"> <div class="col-md-12"><textarea class="form-control" id="datavalu"></textarea></div></div>     
                            </div>     
                </div> </div>
        </div>
    </div>
    <script>
        /*watermarker*/
        var num = [];
        (function(){
          var image = $('#imagewater').attr('src');
          num.push({"mainimage":image,"value":{}});
            
            

            

            $(document).on("mousemove",function(event){
                $("#mousex").val(event.pageX);
                $("#mousey").val(event.pageY);
            });

            $(document).on("click","input[name=watermarkchooser]", function(){
                var value = $(this).val();
                switch (value) {
                  case 'text':
                    $("#chooseshape").slideUp();
                    $("#choosesticker").slideUp();
                    $("#choosetext").slideDown();
                    break;
                  case 'shape':
                    $("#choosesticker").hide();
                    $("#choosetext").hide();
                    $("#chooseshape").slideDown();
                    break;
                  case 'sticker':
                    $("#chooseshape").hide();
                    $("#choosetext").hide();
                    $("#choosesticker").slideDown();
                    break; 
                }
            });

            $(document).on("click",".icon-choose", function(){
                var value = $(this).attr('src');
                getwatermark(value);
            });


            $('select[name=watermark]').change(function () {
                var value = $(this).val();
                var setnum = makeid(10);
                if(value!='') {
                    num.push({"watermark":setnum, "value":{"image":value,"x1":0,"y1":0,"w":0,"h":0}});
                    setwatermarker(value,setnum);
                }
            });
        })();
        /*End watermarker*/

        function getattra(e) {
            $("#singerimageFist").val(e);
            $("#imageviewFist").html('<img style="width:100%;height:55px;" src="' + e + '"/>');
        }

function setwatermarker(image,setval) {
  $("#imagewater").watermarker({
      imagePath: image,
      removeIconPath: "<?php echo base_url();?>uploads/image/watermark/watermark/close-icon.png",
      offsetLeft:30,
      offsetTop: 40,
      onChange: updateCoords,
      onInitialize: updateCoords,
      containerClass: "myContainer imagecontainer",
      watermarkImageClass: "myImage superImage",      
      watermarkerClass: "js-watermark-1 js-watermark",
      watermarkerDataId: setval,
      data: {id: setval, "class": "superclass", pepe: "pepe"},        
      onRemove: function(){
          for (var i = num.length - 1; i >= 0; --i) {
              if (num[i].watermark == setval) {
                  num.splice(i,1);
              }
          }
          if(typeof console !== "undefined" && typeof console.log !== "undefined"){
              console.log("Removing...");
          }
      },
      onDestroy: function(){
          if(typeof console !== "undefined" && typeof console.log !== "undefined"){
              console.log("Destroying...");   
          }
      }
  });
}
function updateCoords (coords){
  $("#posx").val(coords.x);
  $("#posy").val(coords.y);
  $("#width").val(coords.width);
  $("#height").val(coords.height);
  $("#opacity").val(coords.opacity);  
  for (var i = 0; i < num.length; i++) {
      if (num[i].watermark == coords.id) {
          num[i].value = {"image":coords.imagePath,"x1":coords.x,"y1":coords.y,"w":coords.width,"h":coords.height};
      }
  }

  // var image = $('#image').attr('src');
  //var obj={"watermark":{"image":coords.imagePath,"x1":coords.x,"y1":coords.y,"w":coords.width,"h":coords.height}}
  var pretty = JSON.stringify(num, undefined, 2);
  $('#datavalu').val(pretty);
  console.log(num);
}
function getwatermark(value) {
  var setnum = makeid(10);
  if(value!='') {
      num.push({"watermark":setnum, "value":{"image":value,"x1":0,"y1":0,"w":0,"h":0}});
      setwatermarker(value,setnum);
  }
}
function makeid(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}
/*filter*/
function blur() {
  var blur = $("#blur").slider("value");
  var grayscale = $("#grayscale").slider("value");
  var brightness = $("#brightness").slider("value");
  var contrast = $("#contrast").slider("value");
  var rotate = $("#rotate").slider("value");
  var invert = $("#invert").slider("value");
  var opacity = $("#opacity").slider("value");
  var saturate = $("#saturate").slider("value");
  var sepia = $("#sepia").slider("value");
  $("#imagewater").css("-webkit-filter", "blur(" + blur + "px)" + "brightness(" + brightness + "%)" + "grayscale(" + grayscale + "%)" + "hue-rotate(" + rotate + "deg)" + "contrast(" + contrast + "%)" + "invert(" + invert + "%)" + "opacity(" + opacity + "%)" + "saturate(" + saturate + ")" + "sepia(" + sepia + "%)");
  $("#imagewater").css("filter", "blur(" + blur + "px)" + "brightness(" + brightness + "%)" + "grayscale(" + grayscale + "%)" + "hue-rotate(" + rotate + "deg)" + "contrast(" + contrast + "%)" + "invert(" + invert + "%)" + "opacity(" + opacity + "%)" + "saturate(" + saturate + ")" + "sepia(" + sepia + "%)");
console.log(111);
  for (var i = 0; i < num.length; i++) {
      if (num[i].mainimage) {
          num[i].value = {"blur":blur,"grayscale":grayscale,"brightness":brightness,"contrast":contrast,"rotate":rotate,"invert":invert,"opacity":opacity,"saturate":saturate,"sepia":sepia};
      }
  }
  var pretty = JSON.stringify(num, undefined, 2);
  $('#datavalu').val(pretty);
}
//***********SLIDERS*************//

$(function() {
  $("#blur").slider({
    orientation: "horizontal",
    min: 0,
    max: 25,
    value: 0,
    slide: blur,
    change: blur
  });
  $("#grayscale").slider({
    orientation: "horizontal",
    min: 0,
    max: 100,
    value: 0,
    slide: blur,
    change: blur
  });
  $("#brightness").slider({
    orientation: "horizontal",
    min: 100,
    max: 1000,
    value: 0,
    slide: blur,
    change: blur
  });

  $("#contrast").slider({
    orientation: "horizontal",
    min: 0,
    max: 1000,
    value: 100,
    slide: blur,
    change: blur
  });
  $("#rotate").slider({
    orientation: "horizontal",
    min: -180,
    max: 180,
    value: 0,
    slide: blur,
    change: blur
  });

  $("#saturate").slider({
    orientation: "horizontal",
    min: 0,
    max: 100,
    value: 1,
    slide: blur,
    change: blur
  });

  $("#sepia").slider({
    orientation: "horizontal",
    min: 0,
    max: 100,
    value: 0,
    slide: blur,
    change: blur
  });

  $("#opacity").slider({
    orientation: "horizontal",
    min: 0,
    max: 100,
    value: 100,
    slide: blur,
    change: blur
  });

  $("#invert").slider({
    orientation: "horizontal",
    min: 0,
    max: 100,
    value: 0,
    slide: blur,
    change: blur
  });
});
    </script>
<style>
        #image,#myCanvas{
  float:left;
}

#blur,#grayscale,#brightness,#contrast,#rotate,#invert,#opacity,#saturate,#sepia{
    width: 300px;
    margin: 15px;
   float:left;
 }
 
 div[type=range] {
  -webkit-appearance: none;
  width: 100%;
  margin: 2px 0;
}

div[type=range] {
  width: 100%;
  height: 1px;
  cursor: pointer;
  box-shadow: 1px 1px 0.7px #000000, 0px 0px 1px #0d0d0d;
  background: rgba(191, 102, 192, 0.35);
  border-radius: 9.2px;
  border: 0.2px solid #010101;
}
.ui-slider .ui-slider-handle {
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
  border: 1px solid #000000;
  height: 5px;
  width: 22px;
  border-radius: 12px;
  background: rgba(255, 42, 109, 0.75);
  cursor: pointer;
  -webkit-appearance: none;
  margin-top: 1.8px;
}

div[type=range] {
  width: 100%;
  height: 1px;
  cursor: pointer;
  box-shadow: 1px 1px 0.7px #000000, 0px 0px 1px #0d0d0d;
  background: rgba(191, 102, 192, 0.35);
  border-radius: 9.2px;
  border: 0.2px solid #010101;
}

div[type=range] {
  width: 100%;
  height: 1px;
  cursor: pointer;
  background: transparent;
  border-color: transparent;
  color: transparent;
}
div[type=range] {
  background: rgba(164, 68, 165, 0.35);
  border: 0.2px solid #010101;
  border-radius: 18.4px;
  box-shadow: 1px 1px 0.7px #000000, 0px 0px 1px #0d0d0d;
}
div[type=range] {
  background: rgba(191, 102, 192, 0.35);
  border: 0.2px solid #010101;
  border-radius: 18.4px;
  box-shadow: 1px 1px 0.7px #000000, 0px 0px 1px #0d0d0d;
}
div[type=range] {
  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
  border: 1px solid #000000;
  height: 5px;
  width: 22px;
  border-radius: 12px;
  cursor: pointer;
  height: 1px;
}

</style>
    <?php

} else {
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
?>