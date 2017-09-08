var stampW = 44;
var stampH = 44;
var nowX = 1;
var nowY = 1;
var topmargin = 50;
var leftmargin = 50;
var ratio=4;

function copyObj()
{
	var obj = canvas.getActiveObject();
	if(obj == null){
		return;
	}
	obj.clone(function (o) {
	        var vobj = o;
	        if (vobj) {
			vobj.set('top', vobj.top+15);
			vobj.set('left', vobj.left+15);

			canvas.add(vobj);
			canvas.setActiveObject(vobj);
		}
	});
}

function editapply()
{
  obj = canvas.getActiveObject();
  obj.left=parseInt(document.getElementById("editleft").value);
  obj.top=parseInt(document.getElementById("edittop").value);
  obj.angle=parseInt(document.getElementById("editangle").value);
  obj.scaleX=parseFloat(document.getElementById("editscalex").value);
  obj.scaleY=parseFloat(document.getElementById("editscaley").value);
  canvas.renderAll();
}

function canvasEventSet()
{
canvas.selection = false;

canvas.on('selection:cleared', function() {
  btnSwitch(false);
});

canvas.on('object:selected', function(options) {
  btnSwitch(false);
  if(options.target.get('type')=="text"){
	  document.getElementById("edittxt").value=options.target.text;
	  document.getElementById("fontselector2").value=options.target.fontFamily;
	  btnSwitch(true);
  }

  document.getElementById("editleft").value=options.target.left;
  document.getElementById("edittop").value=options.target.top;
  document.getElementById("editangle").value=options.target.angle;
  document.getElementById("editscalex").value=options.target.scaleX;
  document.getElementById("editscaley").value=options.target.scaleY;

  document.getElementById("delbtn").disabled=false;
  document.getElementById("copybtn").disabled=false;
});

//デフォルト設定
btnSwitch(false);

}

function setStampObj(objects, options)
{

	var fillstr = 'black';

	if(nowX+stampW>=stampview.width){
		nowX=1;
		nowY+=stampH;
	}


	var loadedObject = fabric.util.groupSVGElements(objects, options);
	loadedObject.set({
		fill: fillstr,
		left: nowX,
		top: nowY,
		angle: 0,
		padding: 3,
		hasControls: false,
		lockMovementX: true,
		lockMovementY: true,
	});

	//groupの場合はfillは、個別に設定
	if(loadedObject.type=='group'){
		for(var i=0;i<loadedObject._objects.length;i++){
			loadedObject._objects[i].fill = fillstr;
		}
	}

	loadedObject.scale(0.3);
	loadedObject.setCoords();
	stampview.add(loadedObject);

        nowX += stampW;
}

function btnSwitch(editmode)
{
	if(editmode){
		document.getElementById("editbtn").style.display="block";
		document.getElementById("addtextbtn").style.display="none";
	}else{
		document.getElementById("editbtn").style.display="none";
		document.getElementById("addtextbtn").style.display="block";
	}
	document.getElementById("delbtn").disabled="";
	document.getElementById("copybtn").disabled="";
}

function setTextAttr(txtobj,fontstr,fontclr)
{
	txtobj.set({
		lineHeight: 0.90,
		fontSize: 40,
		fontFamily: fontstr ,
		fontWeight: 'bold',
		fill: fontclr,
		left: 20,
		top: 20,
		stroke: 'white' ,
		strokeWidth: 1 ,
		textAlign: 'center' ,
	});
	txtobj.setShadow("0px 0px 4px rgba(0,0,0,1.0)");
	setControler(txtobj);
}

function txtadd()
{
	if(document.getElementById("addtxt").value == ""){
		return;
	}
	var fontstr = document.getElementById("fontselector").value;
	var fontclr = '#'+document.getElementById("coloersel").value;
	var txtobj = new fabric.Text(document.getElementById("addtxt").value);
	setTextAttr(txtobj,fontstr,fontclr);
	canvas.add(txtobj);
	canvas.setActiveObject(txtobj);

	close_modal("addtext");
}

//削除ボタン押した
function delobj()
{
	var obj = canvas.getActiveObject();
	if(obj == null){
		return;
	}
	canvas.remove(obj);
}

function txtedit()
{
	var obj = canvas.getActiveObject();

	if(obj == null){
		return;
	}

	var fontstr = document.getElementById("fontselector2").value;
	var fontclr = '#'+document.getElementById("coloersel2").value
	var text = document.getElementById("edittxt").value;

	obj.fontFamily = fontstr;
	obj.set({
		fill: fontclr,
	})
	obj.text = text;
	canvas.renderAll();
	close_modal("edittext");
}

function dllink()
{
        document.getElementById("loading").style.display = "block";
        document.getElementById("dllink" ).style.display = "none";

	open_modal("downloadview");
	setTimeout("dllink2()",500);
}
function dllink2()
{
	var dstobj = document.getElementById("dstcanvas")
	//出力canvasの初期化
	dstcanvas.clear();

	//切り抜き線の描画
	var rect = new fabric.Rect({
		left: leftmargin,
		top: topmargin,
		width: canvas.getWidth()*ratio,
		height: canvas.getHeight()*ratio,
		fill: 'white',
		stroke: 'black',
		strokeWidth: 1,
	});
	dstcanvas.add(rect);

	//切り抜き線の描画
	var rect = new fabric.Rect({
		left: leftmargin*2+canvas.getWidth()*ratio,
		top: topmargin,
		width: canvas.getWidth()*ratio,
		height: canvas.getHeight()*ratio,
		fill: 'white',
		stroke: 'black',
		strokeWidth: 1,
	});
	dstcanvas.add(rect);

	for(var i = 0;i<canvas.size();i++){
		canvas.item(i).clone(function (o) {
		        var vobj = o;
		        if (vobj) {
				vobj.set('scaleX', vobj.scaleX*ratio );
				vobj.set('scaleY', vobj.scaleY*ratio );
				vobj.set('top', vobj.top*ratio + topmargin);
				vobj.set('left', vobj.left*ratio + leftmargin);

				dstcanvas.add(vobj);
			}
		});

		canvas.item(i).clone(function (o) {
		        var vobj = o;
		        if (vobj) {
				vobj.set('scaleX', vobj.scaleX*ratio );
				vobj.set('scaleY', vobj.scaleY*ratio );
				vobj.set('top', vobj.top*ratio + topmargin);
				vobj.set('left', vobj.left*ratio + canvas.getWidth()*ratio + leftmargin*2);

				dstcanvas.add(vobj);
			}
		});

	}

	//強制描画
	dstcanvas.renderAll();

	//画像ファイル化
	var text = dstobj.toDataURL();

	document.getElementById("dllink").innerText = "ここをクリックして保存";
	document.getElementById("dllink").href = text;

        document.getElementById("loading").style.display = "none";
        document.getElementById("dllink" ).style.display = "block";
				document.getElementById("start-save").style.display = "block";
				document.getElementById("png-uri").setAttribute("data-uri", text);

}

function open_modal(windowid)
{
	$("body").append('<div id="modal-bg"></div>');
	$("#"+windowid).show();
	modalResize();

	$("#modal-bg,#modal-main").show();
	$("#modal-bg").click(function(){close_modal(windowid)});
	$(window).resize(modalResize);
}

function close_modal(windowid)
{
	$("#modal-main,#modal-bg").hide();
	$("#"+windowid).hide();
	$('#modal-bg').remove();
}

function modalResize(){

	var w = $(window).width();
	var h = $(window).height();

	var cw = $("#modal-main").outerWidth();
	var ch = $("#modal-main").outerHeight();

	//取得した値をcssに追加する
	$("#modal-main").css({
		"left": ((w - cw)/2) + "px",
		"top": ((h - ch)/2) + "px"
	});
}

function clonestamp()
{
	var stamp = stampview.getActiveObject();
	if(stamp == null){
		return ;
	}

	stamp.clone(function (o) {
		var vobj = o;
		if (vobj) {
			//vobj.set('scaleX', vobj.scaleX*2 );
			//vobj.set('scaleY', vobj.scaleY*2 );
			vobj.set('top', 15);
			vobj.set('left', 15);

			canvas.add(vobj);
			canvas.setActiveObject(vobj);
		}
	});

/*
	var svgstr = stamp.toSVG();
	if(svgstr.slice(0,3) != "<g "){
		svgstr = '<g >' + svgstr + '</g>';
	}
	fabric.loadSVGFromString(svgstr , function(objects, options) {
		var svgGroups = fabric.util.groupSVGElements(objects, options);
		svgGroups.set({
			left: 10,
			top: 10,
			fill: 'red',
		});
		setControler(svgGroups);
		svgGroups.scale(1);
		svgGroups.setCoords();
		canvas.add(svgGroups).renderAll();
	});
*/
	close_modal("addstamp");
}


function setControler(obj)
{
	obj.set({
		cornerSize: 25,
	});
}

function startSave()
{
	var uri = document.getElementById("png-uri").getAttribute("data-uri");
	Penlight.sendDataURI(uri);
}
