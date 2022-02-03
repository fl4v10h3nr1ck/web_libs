


	
function setPrevisao(id, ratio, altura){
    
var nome = jQuery("#nova_imagem_"+id)[0].files[0].name;
	
	if(nome.length==0){
	
	alert("Um erro ocorreu ao carregar o arquivo.");
	document.getElementById("form_envia_img_"+id).reset();
	return;
	}

nome = nome.split(".");	
nome = nome.length>0?nome[nome.length-1]:"";	
	
	if(jQuery("#png_"+id).val()==0 && (nome=="png" || nome =="PNG")){
		
	alert("Extensão de arquivo não aceita (.PNG).");
	document.getElementById("form_envia_img_"+id).reset();
	return;	
	}
	
	
	if(jQuery("#gif_"+id).val()==0 && (nome=="gif" || nome =="GIF")){
		
	alert("Extensão de arquivo não aceita (.GIF).");
	document.getElementById("form_envia_img_"+id).reset();
	return;	
	}
	
	
	if (typeof (FileReader) != "undefined") {
 
    var image_holder = jQuery("#area_pre_visao_"+id);
    image_holder.empty();
 
    var reader = new FileReader();
		
		reader.onload = function (e) {
        
		jQuery("<img />", 
			{"src": e.target.result,
            "class": "img_preview",
			"height": altura+"px",
			}).appendTo(image_holder);
		
			$('.img_preview').Jcrop({
				aspectRatio: ratio,
				onSelect: function(c){
					
				$('#x_'+id).val(c.x);
				$('#y_'+id).val(c.y);
				$('#w_'+id).val(c.w);
				$('#h_'+id).val(c.h);	
				}
			});
		}
    image_holder.show();
    reader.readAsDataURL(jQuery("#nova_imagem_"+id)[0].files[0]);
	}
}
	
	
		
	
	

	   

function EnviarImgDeUpload(id, func_sucesso){
	
	jQuery('#form_envia_img_'+id).ajaxForm(
			
		function(retorno){ 
	
		var aux = jQuery.parseJSON(retorno);
			
		if(aux.status=='SUCESSO')
		eval(func_sucesso)(aux.nome);
		else
		alert(aux.erro);
		}
			
	).submit();
}
	
		   
	
	
	
	

