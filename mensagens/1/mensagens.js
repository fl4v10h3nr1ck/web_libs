
var path ="/" ;

var ICON_ERRO_PATH 	= path+"imgs/erro_msg.png";
var ICON_OK_PATH 	= path+"imgs/ok_msg.png";
var ICON_DIA_PATH 	= path+"imgs/dialogo.png";
var TEMPORIZADOR 	= true;


var func_callback_MSG = "";

var func_callback_DIA_1 = "";

var func_callback_DIA_2 = "";




	jQuery(document).ready(function(){
	
	if(jQuery("#div_area_sombra_msg").length){
			jQuery("#div_area_sombra_msg").click(
				function(e){
				
				if(jQuery(e.target).attr('id')=="div_area_sombra_msg")
				
				if(jQuery("#tipo_de_msg_lib").val()=="MSG")
				fecharMensagem();
				else
				fecharDialogo(0);	
				}
			);
		}		
	});
		


	

	
	function fecharMensagem(){

	fecharGeral(func_callback_MSG);
	}
	
	
	
	
	
	
	function fecharMensagemComEnter(){
	
	if ((window.event ? window.keyCode : window.which) == 13)
	fecharMensagem();
	}
	
	
	
	
	
	
	
	function fecharDialogo(opcao){
		
	fecharGeral(opcao==0?"":(opcao==1?func_callback_DIA_1:func_callback_DIA_2));
	}
	
	
	
	
	
	function fecharGeral( callback){
		
		jQuery("#div_area_sombra_msg").fadeOut("slow", 
			function(){
							
			jQuery("#div_area_sombra_msg").html("");
			
			func_callback_MSG = "";
			
			func_callback_DIA_1 = "";

			func_callback_DIA_2 = "";
			
			
			if(callback.length>0)
			eval(callback);	
			}
		);	
	}
	
	
	
	
		
	function getMsgErro(msg){
		
		
	getMsg(msg, 0, "");	
	}	
	
	
	
	
	
	function getMsgErro(msg, func){
		
		
	getMsg(msg, 0, func);	
	}
	
	
	
	
	
	function getMsgSucesso(msg){
			
	getMsg(msg, 1, "");	
	}	
	
	

	
	
	
	function getMsgSucesso(msg, func){
			
	getMsg(msg, 1, func);	
	}	
	
	

	
	
	
	
	function getComprimento(msg){
		
	var ttl_w  =jQuery(window).outerWidth();
	
	if(ttl_w<800)
	x = 94;
	else if(ttl_w>=800 && ttl_w<1000)
	x = 80;
		else{
		var x = 40;
		
		if(msg.length>100)
		var x = 50;
		}
	
	return Math.round((ttl_w*x)/100);
	}
	
	
	
	
	
	
	
	function getMarginTop(){
		
	var ttl_h  =jQuery(window).outerHeight();
	
	var h_m = 180;
	
	if(ttl_h>800)
	h_m = 230;

	return h_m;
	}
	
	
	
	
	
	
	
	function getMarginLeft(comprimento){
	
	return Math.round((jQuery(window).outerWidth() -comprimento)/2);
	}
	
	
	
	
	
	
	
	function getMsg(msg, tipo, func){
	
	if(func==null)
	func = "";

	func = func.replace(/'/g, "\"");
	
	func_callback_MSG = func;
	
	var comprimento = getComprimento(msg);
	
	topo = "<div style='wdith:100%;height:25px;background:";
	
	if(tipo==0)
	topo += "red";
	else
	topo += "green";


	topo += ";color:#FFF' align='left'><input id='tipo_de_msg_lib' type='hidden' value='MSG'></div>";
	

	var div = 
	"<div style='width:"+comprimento+"px;min-height:100px;margin:"+getMarginTop()+"px 0px 0px "+getMarginLeft(comprimento)+"px;background:#FFF;border-radius: 3px;box-shadow: 3px 3px 3px #888888;'>"+
	topo+
	"	<table style='padding-top:15px;width:100%'>"+
	"		<tr>"+
	"			<td width='15%' align='center'>"; 
	
	if(tipo==0)
	div +="<img src='"+ICON_ERRO_PATH+"' style='height:30px'>";
	else
	div +="<img src='"+ICON_OK_PATH+"' style='height:30px'>";
	
	div +="		</td>"+
	"			<td  width='85%' align='left'>"+ 
				msg+
				"</td>"+
			"</tr>"+
			"<tr>"+
				"<td colspan='2' align='center'>"+
				"<button id='bt_fechar_msg' onclick='javascript:fecharMensagem();' style='width:150px;margin:15px 0px 10px 0px' onKeyPress='javascript:fecharMensagemComEnter();'>Fechar (5 segundos)</button>"+
				"</td>"+
			"</tr>"+
			
			
		"</table>"+
	"</div>";
	

	jQuery("#div_area_sombra_msg").html(div);
	

		jQuery("#div_area_sombra_msg").fadeIn("fast", 
			function(){
		
			document.getElementById('bt_fechar_msg').focus();
		
				if(TEMPORIZADOR){
		
		
					setTimeout(function(){
						
					jQuery("#bt_fechar_msg").html("Fechar (4 segundos)");
						setTimeout(function(){
							
						jQuery("#bt_fechar_msg").html("Fechar (3 segundos)");
						
							setTimeout(function(){
								
							jQuery("#bt_fechar_msg").html("Fechar (2 segundos)");
							
								setTimeout(function(){
									
								jQuery("#bt_fechar_msg").html("Fechar (1 segundos)");
								
									setTimeout(function(){
										
									fecharMensagem();
									
									},1000);
								}, 1000);
							}, 1000);
						}, 1000);	
					}, 1000);
				}
			}
		);	
	}	
		
		
	
	


	function getDialogo(msg, func_bt_1, func_bt_2, rot_bt_1, rot_bt_2){
	
	if(func_bt_1==null)
	func_bt_1 = "";

	if(func_bt_2==null)
	func_bt_2 = "";

	func_bt_1 = func_bt_1.replace(/'/g, "\"");
	func_bt_2 = func_bt_2.replace(/'/g, "\"");
	
	func_callback_DIA_1 = func_bt_1;
	func_callback_DIA_2 = func_bt_2;
	
	
	var comprimento = getComprimento(msg);
	
	topo = "<div style='wdith:100%;height:25px;background:blue;color:#FFF' align='left'></div>";
	

	var div = 
	"<div style='width:"+comprimento+"px;min-height:100px;margin:"+getMarginTop()+"px 0px 0px "+getMarginLeft(comprimento)+"px;background:#FFF;border-radius: 3px;box-shadow: 3px 3px 3px #888888;'>"+
	"	<div style='wdith:100%;height:25px;background:blue;color:#FFF' align='left'><input id='tipo_de_msg_lib' type='hidden' value='DIA'></div>"+
	"	<table style='padding-top:15px;width:100%'>"+
	"		<tr>"+
	"			<td width='15%' align='center'>"+
	"			<img src='"+ICON_DIA_PATH+"' style='height:30px'>"+
	"			</td>"+
	"			<td  width='85%' align='left'>"+ 
				msg+
				"</td>"+
			"</tr>"+
			"<tr>"+
				"<td colspan='2' align='center'>"+
				"<button id='bt_dia_1' style='width:150px;margin:15px 0px 10px 0px' onclick='javascript:fecharDialogo(1);'>"+(rot_bt_1==null || rot_bt_1.length==0?"SIM":rot_bt_1)+"</button>&nbsp;&nbsp;&nbsp;&nbsp;"+
				"<button id='bt_dia_2' style='width:150px;margin:15px 0px 10px 0px' onclick='javascript:fecharDialogo(2);'>"+(rot_bt_2==null || rot_bt_2.length==0?"NÃO":rot_bt_2)+"</button>"+
				"</td>"+
			"</tr>"+	
		"</table>"+
	"</div>";
	

	jQuery("#div_area_sombra_msg").html(div);
	
	jQuery("#div_area_sombra_msg").fadeIn("fast");		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	