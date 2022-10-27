/*
ver.2.23.22

https://docs.jsplumbtoolkit.com/community-2.x/current/index.html
*/
var scroll;
var $grid;
var md;
var $gTop=0;//top general
var $zoom=.5;//zoom inicial
var $pZoom=$zoom;//zoom permanente
var mySVGsToInject;

var focusGrpBol,focusEmpBol=false;//on/off focus
var $lineasP;
var $verTextos=false;//on/off textos
var tmp_id=0;

$(function() {
	//window.jsPDF = window.jspdf.jsPDF;
    md = new MobileDetect(window.navigator.userAgent);	
	// FORMULARIO CONTACTO
    if($('#organigrama').length){
        var $transform=$('#organigrama').css('transform');
        var values = $transform.split('(')[1];
        values = values.split(')')[0];
        values = values.split(',');
        var a = values[0];
        var b = values[1];

        var scale = Math.sqrt(a*a + b*b);
		//parametro Zoom
		$nzoom=getUrlParameter('zoom');
		if($nzoom !== false){
			$zoom=$nzoom/100;
		}else{
			//$zoom=scale;
			$zoom=$pZoom;
			//resetTop();
		}
		$pdfsave=getUrlParameter('save');
		if($pdfsave !== false){
			$('.tools.zoom').hide();
			$('#menu').hide();
			resetTop();
		}
        //

        $('.btn-zoom .list-group-item-action:nth-child(1)').click(function(){
            $zoom+=.1;
            zoomImg();
        });
        $('.btn-zoom .list-group-item-action:nth-child(3)').click(function(){
            $zoom-=.1;
            zoomImg();
        });
        $('.btn-zoom .list-group-item-action:nth-child(4)').click(function(){
            resetVista();
        }); 
		$('.btn-zoom .list-group-item-action:nth-child(5)').click(function(){
			$(this).toggleClass('active');
            focusGroups();
        }); 
		$('.btn-zoom .list-group-item-action:nth-child(6)').click(function(){
			$(this).toggleClass('active');
            mostrarTexto();
        });
		$('#screenshoot').click(function(){
			$(this).prop('disabled', true);
            screenshoot();
        });
        //--
		$(window).bind('mousewheel', function(e){
			if(e.originalEvent.wheelDelta /120 > 0) {
				$zoom+=.1;
            	zoomImg();
			}else{
				$zoom-=.1;
            	zoomImg();
			}
		});
    }
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	  return new bootstrap.Tooltip(tooltipTriggerEl)
	});
	if($('#organigrama').length){
        $('#organigrama').draggable({// agregar dragg
		  start: function( event, ui ) {
			  tmp_id=0;
			  focusGrpBol,focusEmpBol=false;
			  $('#organigrama').removeClass('moveAnm');
			  $('.box').css({
					'opacity': '1',
					'filter': 'blur(0px)',
					'-webkit-filter': 'blur(0px)',
					'-moz-filter': 'blur(0px)',
					'-o-filter': 'blur(0px)',
					'-ms-filter': 'blur(0px)'
			  });
		  }
		});
    }
	tamTotal();
	$( window ).on( "load",function() {
		$('#load').remove();
		/*
		mySVGsToInject = document.querySelectorAll('img.svg');
		SVGInjector(mySVGsToInject);*/      
		loadOk();
		//$grid.masonry();
	});
	$(window).resize(function () {
		jsPlumb.reset();
		realLines_new();
	});
});
function loadOk(){ 
    if($('#organigrama').length){
        //createLines();
		realLines_new();
        zoomImg();
        //centerImg();
    }
    $('#menu .btn-outline-info').click(function(event){
        event.preventDefault();
        var url = $(this).attr('href');
        $.fancybox.open({
            src  : url,
            type : 'iframe',
            opts : {
                afterShow : function( instance, current ) {
                    $('.fancybox-content').css({'max-width':'100%'});
                }
            }
        });
        return false;        
    });
	
}
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? false : decodeURIComponent(results[1].replace(/\+/g, ' '));
};
function mostrarTexto(){
	$verTextos = !$verTextos;
	//createLines();
}
function screenshoot(){
	//$('.tools.zoom').hide();
	/*
	html2canvas($("#pron")[0],{
		allowTaint: true,
		logging: true,
		scale: $zoom
	}).then(
    function (canvas) {
		console.log('Saving...');
		scrBase64 = canvas.toDataURL('image/png');
		scrBase64 = scrBase64.split(',')[1];
		var pdf = new jsPDF();
		pdf.addImage(scrBase64, 'PNG', 0, 0);
		pdf.save('organigrama.pdf');
		$('.tools.zoom').show();
		$('#screenshoot').prop('disabled', false);
    });*/
	$.ajax({
		type: "post",
		url: ajax_var.url_ajax,
		data: "action=save-pdf",
		dataType: 'json',
		success: function(result){
			if(result.url){
				var win = window.open(result.url, '_blank');
				if (win) {
					win.focus();
				} else {
					alert('Permita las ventanas emergentes para este sitio web');
				}
			}else{
				alerta(result.mensaje);
			}
		}
	}).fail( function( jqXHR, textStatus, errorThrown ) {
		console.log(jqXHR.responseText);
		console.log(textStatus);
		console.log(errorThrown);
	}).always( function() {
		//$('.tools.zoom').show();
		$('#screenshoot').prop('disabled', false);
	})	
}
function resetAll(){
	$zoom=$pZoom;
    zoomImg();
	$('.sub-cajas').removeClass('d-none');
}
function centerImg(){
	$('#organigrama').removeClass('moveAnm');
    //$zoom=$pZoom;	
    zoomImg();
}
function resetVista(){
	$blur = 0;
	$opacity = 1;
	focusGrpBol,focusEmpBol=false;
	$verTextos=false;	
	$('.box').css({
			'opacity': $opacity,
			'filter': 'blur('+$blur+'px)',
			'-webkit-filter': 'blur('+$blur+'px)',
			'-moz-filter': 'blur('+$blur+'px)',
			'-o-filter': 'blur('+$blur+'px)',
			'-ms-filter': 'blur('+$blur+'px)'
	});
	
	$zoom=.2;
	zoomImg();
	resetTop();
}
function resetTop(){
	$gTop=0;
	/*
	$('#organigrama').css('top',$gTop);
    $gTop=(($('#organigrama').height()*$zoom)-($('#organigrama').height()/2))/2*/
	
    $('#organigrama').css({
        'left'  : 0,
        'top'   : $gTop
    });
    console.log('center ('+$gTop+')');
}
function zoomImg(){
	if($zoom<=0) $zoom=.1;
    $('#organigrama').css({
		'-webkit-transform' : 'scale(' + $zoom + ')',
		'-moz-transform'    : 'scale(' + $zoom + ')',
		'-ms-transform'     : 'scale(' + $zoom + ')',
		'-o-transform'      : 'scale(' + $zoom + ')',
		'transform'         : 'scale(' + $zoom + ')'
    });
    var porc=Math.round(100*$zoom);
    $('#num-zoom small').html(porc+"%");
	tmp_id=0;
	/*
	$blur = 0;
	$opacity = 1;
	focusGrpBol,focusEmpBol=false;
	$verTextos=false;	
	$('.box').css({
			'opacity': $opacity,
			'filter': 'blur('+$blur+'px)',
			'-webkit-filter': 'blur('+$blur+'px)',
			'-moz-filter': 'blur('+$blur+'px)',
			'-o-filter': 'blur('+$blur+'px)',
			'-ms-filter': 'blur('+$blur+'px)'
	});*/
	/*
	$tTop=-1*( $('#organigrama').offset().top-parseInt( $('body.home').css('padding-top') ) );	
	console.log('$tTop:'+$tTop);*/
}
function buscarEmpresa($id,$tid){
	$blur = 1;
	$opacity = .65;
	console.log('buscarEmpresa:'+$id);
	
	if($('#emp-'+$id).length){
		$('#emp-'+$tid+' > .box').removeClass('animate__animated animate__delay-1s');
		$('#box-child-'+$id).removeClass('animate__animated animate__delay-1s');
		$('.box').css({
				'opacity': $opacity,
				'filter': 'blur('+$blur+'px)',
				'-webkit-filter': 'blur('+$blur+'px)',
				'-moz-filter': 'blur('+$blur+'px)',
				'-o-filter': 'blur('+$blur+'px)',
				'-ms-filter': 'blur('+$blur+'px)'
		});
		
		$('#emp-'+$tid+' > .box').css({
				'opacity': 1,
				'filter': 'blur(0px)',
				'-webkit-filter': 'blur(0px)',
				'-moz-filter': 'blur(0px)',
				'-o-filter': 'blur(0px)',
				'-ms-filter': 'blur(0px)'
		});
		$('#emp-'+$id+' > .box').css({
				'opacity': 1,
				'filter': 'blur(0px)',
				'-webkit-filter': 'blur(0px)',
				'-moz-filter': 'blur(0px)',
				'-o-filter': 'blur(0px)',
				'-ms-filter': 'blur(0px)'
		});
		$('#box-child-'+$id+' > .box').css({
				'opacity': 1,
				'filter': 'blur(0px)',
				'-webkit-filter': 'blur(0px)',
				'-moz-filter': 'blur(0px)',
				'-o-filter': 'blur(0px)',
				'-ms-filter': 'blur(0px)'
		});
		$('#box-child-'+$id).css({
				'opacity': 1,
				'filter': 'blur(0px)',
				'-webkit-filter': 'blur(0px)',
				'-moz-filter': 'blur(0px)',
				'-o-filter': 'blur(0px)',
				'-ms-filter': 'blur(0px)'
		});
		$('#emp-'+$tid+' > .box').addClass('animate__animated animate__pulse animate__delay-1s animate__repeat-2');
		$('#box-child-'+$id).addClass('animate__animated animate__pulse animate__delay-1s animate__repeat-2');
		
		if(tmp_id != $id){//buscar id nuevo
			tmp_id = $id;
			focusEmp($id);			
		}
	}else{
		//tmp_id = $id;
		alerta('Empresa no Encontrada');
	}	
}

function focusGroups(){
	focusGrpBol = !focusGrpBol;
	//console.log(focusGrpBol);
	if(focusGrpBol){
		$blur = 1;
		$opacity = .65;
	}else{
		$blur = 0;
		$opacity = 1;
	}
	$('.box.s-child').hover(function(element) {
		
		//--
		caja = element.target;		
		$('.box').css({
				'opacity': $opacity,
				'filter': 'blur('+$blur+'px)',
				'-webkit-filter': 'blur('+$blur+'px)',
				'-moz-filter': 'blur('+$blur+'px)',
				'-o-filter': 'blur('+$blur+'px)',
				'-ms-filter': 'blur('+$blur+'px)'
		});
		$(caja).css({
				'opacity': '1',
				'filter': 'blur(0px)',
				'-webkit-filter': 'blur(0px)',
				'-moz-filter': 'blur(0px)',
				'-o-filter': 'blur(0px)',
				'-ms-filter': 'blur(0px)'
		});
		$(caja).parent('.caja').find('.box').css({
				'opacity': '1',
				'filter': 'blur(0px)',
				'-webkit-filter': 'blur(0px)',
				'-moz-filter': 'blur(0px)',
				'-o-filter': 'blur(0px)',
				'-ms-filter': 'blur(0px)'
		});
	}, function() {
		$('.box').css({
				'opacity': '1',
				'filter': 'blur(0px)',
				'-webkit-filter': 'blur(0px)',
				'-moz-filter': 'blur(0px)',
				'-o-filter': 'blur(0px)',
				'-ms-filter': 'blur(0px)'
		});
	});
}

function createLines(){
	/*
	$('#svgContainer').unbind().removeData();
    $('#svgContainer svg').remove();*/

    var $tZoom=$zoom;
    $zoom=1;
    zoomImg();
	
	//realLines_new();
    
    //volver zoom
    $zoom=$tZoom;
    zoomImg();
}
function tamTotal(){
	var tWidth=0;
	$.each( $('#organigrama > .cajas > .caja-madre'), function( key, value ) {
		tWidth+=$(value).outerWidth( true );
	});
	var lLeft=-1*( (tWidth/2)+$('#organigrama').width()/2 );
	$('#organigrama .bg-all').width(tWidth);
	//$('#organigrama .bg-all').css('left',lLeft);
}
var common;
function realLines_new(){
	jsPlumb.ready(function() {
		common = {
			elementsDraggable:false,
			allowLoopback:false,
			connector:[ "Flowchart", { stub: 0, gap: 8, cornerRadius: 2, alwaysRespectStubs: false } ],
			maxConnections:-1
		};		
		jsPlumb.setSuspendDrawing(true);
		
		$.each( $('div[data-boxpadre]:not(.d-none)'), function( key, value ) {
			var $radio=5;
			var $id=$(value).attr('id');
			var $idPadre=$(value).data('boxpadre');
			var $spc=$(value).data('special');
			var $unq=$(value).data('unica');
			var $color=$(value).data('colorlinea');
			//var $orientacion=[ ["Continuous",{ faces:[ "bottom", "left" ] }], ["Top"] ];
			var $orientacion=["Left", "Left"];
			var $endpoints=[
					["Dot", {radius:$radio}], 
					["Image",{src:ajax_var.url_theme+"/img/flecha.svg", cssClass:"img-horz"} ]
				];

			if( $spc ){// en el admin dice "columna especial"
				$orientacion=[ "Right", "Left" ];
				$endpoints=[
					["Dot", {radius:$radio, fill:$color}], 
					["Image",{src:ajax_var.url_theme+"/img/flecha.svg", cssClass:"img-horz"} ]
				];
			}
			if( $unq ){// admin "Columan unica"
				$orientacion=[ ["Continuous",{ faces:[ "left" ] }], ["Top"] ];
				$endpoints=[
					["Dot", {radius:$radio, fill:$color}], 
					["Image",{src:ajax_var.url_theme+"/img/flecha.svg"} ]
				];
			}
			if( $(value).data('level')==1 ){
				$orientacion=["Bottom", "Top"];
				$endpoints=[
					["Dot", {radius:$radio}], 
					["Image",{src:ajax_var.url_theme+"/img/flecha.svg"} ]
				];
			}
			if( $(value).data('abajo') ){
				$orientacion=["Bottom", "Top"];
				$endpoints=[
					["Dot", {radius:$radio}], 
					["Image",{src:ajax_var.url_theme+"/img/flecha.svg"} ]
				];
			}
			if(!$color) $color='#000000';

			jsPlumb.connect({
				source:"box-child-"+ $idPadre,
				target:$id,
				anchors:$orientacion,
				endpoints:$endpoints,
				deleteEndpointsOnDetach:true,
				detachable:false,
				paintStyle: { strokeWidth:2, stroke:$color }
			},common);
			//$conections[$id]=jsConn;
		});	

		jsPlumb.setSuspendDrawing(false, true);
		
	});
}
function realLines(){	
	//lineas 
    var $paths=[];  
    $.each( $('div[data-boxpadre]:not(.d-none)'), function( key, value ) {
        if( !$(value).parents('.sub-cajas').hasClass('d-none') ){

            $id=$(value).attr('id');
            $idPadre=$(value).data('boxpadre');
            $color=$(value).data('colorlinea');
            //$lvl=$(value).data('level');
			$spc=$(value).data('special');
			$texto="";
			if($verTextos){
				$texto=$(value).data('texto');
			}
            $orientacion="horizontal";

            //$mt=$(this).parents('.caja.sub-c3').find('.box.box-shadow-n38').outerHeight()+($(this).parents('.caja.sub-c3').find('.box.box-shadow-n38').height()/2);
			
			//mover cajas hijo arriba
            //$(this).parents('.caja.sub-c3').find('.sub-cajas').css({'margin-top':-1*$mt});
            
            if(!$color) $color='#000000';

            if( $spc ) $orientacion="vertical";

            $paths.push({ 
				start:"#box-child-"+ $idPadre, 
				end:"#" + $id, 
				text:$texto, 
				strokeWidth: 2, 
				stroke: $color, 
				class:"path-box-"+$idPadre, 
				orientation: $orientacion 
			});
        }

    });

    //Lineas
    $lineasP=$("#svgContainer").HTMLSVGconnect({
        paths: $paths
    });
}
function minimizeChart(element,id,center){ 
    $(element).find('.fa-solid').toggleClass('d-none');
    //$('#scaja-'+id).toggleClass("d-none");
	$('#scaja-'+id).find('.box').toggleClass("d-none");
    if(typeof center !== 'undefined'){
        centerImg();
    }
	/*
	if($(element).find('.fa-solid.fa-circle-minus').hasClass('d-none')){
		jsPlumb.deleteConnectionsForElement('box-child-'+id);		
	}else{
		jsPlumb.reset();
		realLines_new();
	}*/
	jsPlumb.reset();
	realLines_new();
	//--	
}
function focusEmp(id){
	/*
	$tzoom=$zoom;
	$zoom=1;	
    zoomImg();*/
	$('#organigrama').removeClass('moveAnm');
	$('#organigrama').css({top:0,left:0});
	
	
	var nTop  = $('#emp-'+id).offset().top;
	var nLeft = (-1*($('#emp-'+id).offset().left+$('#emp-'+id+' > .box').width()))+$(window).height();
	var tTop  = (parseInt( $('body.home').css('padding-top') )-nTop)+($('#emp-'+id+' > .box').height()+$('#menu').height());
	console.log('{top:'+tTop+',left:'+nLeft+'}');	
	setTimeout(function(){
		$('#organigrama').addClass('moveAnm');
		$('#organigrama').css({top:tTop,left:nLeft});
	},600);
	/*
	$zoom=$tzoom;
    zoomImg();*/
	
	//buscarEmpresa(id,0);
}

/*



*/
function animateCSS(element, animationName, callback) {
    const node = $(element);
    node.addClass('animated '+animationName);

    function handleAnimationEnd() {
        node.removeClass('animated '+animationName);

        if (typeof callback === 'function') callback()
    }

    node.one('webkitAnimationEnd oanimationend msAnimationEnd animationend', handleAnimationEnd);
    return node;
}
function alerta($msj){
    console.log($msj);
    $.fancybox.open($msj);
}