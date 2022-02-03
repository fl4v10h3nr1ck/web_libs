

jQuery(document).ready(function(){
	
	
	
	trocarSenha();
	
});
		


 	
function login(){
	
	$("#area_bt_logar").html("<img src='"+$("#vnvp_path").val()+"imgs/load.gif'>");
	
	jQuery.post(
		
	$("#vnvp_path").val()+'acao.php',
		{
		nome_da_funcao:"logar", 
		usuario:$("#login_usuario").val(), 
		senha:$("#login_senha").val(), 
		salvar:$("#continuar_logado").is(":checked")?"SIM":"NAO"
		},
		function(retorno){ 
	
		var aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
			
		if(aux.status=='sucesso')
		location.reload();	
			else{
			$("#login_msg_erro").html("Usuário ou senha inválidos.");
			$("#area_bt_logar").html("<input type='submit' style='width:110px' value='Entrar'/>");
			}
		}
	);
}
	
	
	


	
function salvaDadosDeUsuario(param_id){

	$.post(
			
	$("#vnvp_path").val()+'acao.php',
		{nome_da_funcao:"salvaDadosDeUsuario",
		usuario:jQuery("#usuario_user").val(),
		nome:jQuery("#nome_user").val(),
		identificacao:jQuery("#identificacao_user").val(),
				
		tel:jQuery("#tel_user").val(),
		cel:jQuery("#cel_user").val(),
		email:jQuery("#email_user").val(),
			
		logradouro:(jQuery("#logradouro_user").length?jQuery("#logradouro_user").val():""),
		num:(jQuery("#num_user").length?jQuery("#num_user").val():""),
		cidade:(jQuery("#cidade_user").length?jQuery("#cidade_user").val():""),
		bairro:(jQuery("#bairro_user").length?jQuery("#bairro_user").val():""),
		cep:(jQuery("#cep_user").length?jQuery("#cep_user").val():""),
		uf:(jQuery("#uf_user").length?jQuery("#uf_user").val():""),
		complemento:(jQuery("#comp_user").length?jQuery("#comp_user").val():""),
				
		senha:jQuery("#senha_user").val(),
		repete_senha:jQuery("#repete_senha_user").val(),
		id_usuario:param_id,
		grupo_padrao:jQuery("#grupo_padrao").val(),
		captcha:(param_id<=0?grecaptcha.getResponse():"")},

		function(retorno){ 
			
		var aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
			
			if(aux.resultado=='OK'){
				
			alert("Cadastro realizado com sucesso.");
				
			var url = 	window.location.href.split("&idu");
			if(url.length>0)
			window.open(url[0]+'&idu='+aux.id,'_SELF');	
			else
			location.reload();
			}
			else
			alert(aux.erro);
		}
	);
}
	
	
	
		
	
	
function trocarSenha(){
	
	if($("#trocar_senha").length){

		if($("#trocar_senha").is(":checked")){
				
		$("#senha_user").prop("disabled", false);		
		$("#repete_senha_user").prop("disabled", false);			
		}
		else{
			
		$("#senha_user").prop("disabled", true);		
		$("#repete_senha_user").prop("disabled", true);		
				
		$("#senha_user").val("");		
		$("#repete_senha_user").val("");	
		
		}
	}	
}	
	
	
	
	
function validaAntesDeEnviarUpload(){
	
	if($("#id_usuario").val()<=0){
		
	alert("Salve dos dados do usuário primeiro.");
	return false;
	}
		
return true;	
}	
	

	
	

	
function uploadComSucesso(nome_arq){
	
	jQuery.post(
						
	$("#vnvp_path").val()+'acao.php',
		{nome_da_funcao:"salvaImgProfile",
		url_img:nome_arq,
		id_usuario:$("#id_usuario").val()},
		function(retorno){ 
					
		alert("Cadastro realizado com sucesso.");
		location.reload();
		}
	);	
}	
	
	
	
	
	
	
	
	
	
	

	
	
	
	
	
	
	function sair(){
	
		jQuery.post(
		
			path+'login/action.php',
			{nome_da_funcao:"sair"},
			function(retorno){ location.reload();}
		);
	}
	
	
	
	
	




	function salvaUsuario(param_id){
		
	var parans = {
	nome_da_funcao:"salvaUsuario",
	nome:jQuery("#nome").val(),
	email:jQuery("#email").val(),
	tipo:jQuery("#tipo").val(),
	senha:jQuery("#senha").val(),
	senha_outra:jQuery("#senha_outra").val(),
	id:param_id};
	
		jQuery.post(
		
		'action.php',
		parans,
			function(retorno){ 
		
			var aux = jQuery.parseJSON(retorno.substr(retorno.indexOf("{")));
			
				if(aux.resultado=='OK'){
				
				getMsgSucesso("Cadastro realizado com sucesso.", aux.limpa=='SIM'?"limpaCampos()":"");
				
				if(aux.limpa=='SIM')
				limpaCampos();
				}
				else
				getMsgErro(aux.erro);
			}
		);
	}

	
	
	
	
	
	
	function limpaCampos(){
		 
	jQuery("#nome").val("");
	jQuery("#email").val("");
	jQuery("#tipo").val("");
	jQuery("#senha").val("");
	jQuery("#senha_outro").val("");
	}
	
	
	
	
	
	
	
	
	
	
	 
	 
	function desativarUsuario(param_id, param_status){
	
		jQuery.post(
		
		'action.php',
		
		{nome_da_funcao:'desativarUsuario', id:param_id, status:param_status},
		
			function(retorno){
			
			var aux = jQuery.parseJSON(retorno);
			
			if(aux.resultado=='OK')
			location.reload();
				
			}
		);	
	}  
	 
	
	
