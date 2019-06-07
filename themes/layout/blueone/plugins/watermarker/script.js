var num = [];
(function(){
  var image = $('#imagewater').attr('src');
  num.push({"mainimage":image,"value":{}});
	function setwatermarker(image,setval) {
		$("#imagewater").watermarker({
			imagePath: image,
			removeIconPath: "images/close-icon.png",
			offsetLeft:30,
			offsetTop: 40,
			onChange: updateCoords,
			onInitialize: updateCoords,
			containerClass: "myContainer container",
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
	function makeid(length) {
	   var result           = '';
	   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	   var charactersLength = characters.length;
	   for ( var i = 0; i < length; i++ ) {
	      result += characters.charAt(Math.floor(Math.random() * charactersLength));
	   }
	   return result;
	}

	

	$(document).on("mousemove",function(event){
		$("#mousex").val(event.pageX);
		$("#mousey").val(event.pageY);
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