
	jQuery(document).ready(function(){
		
		trocarSenha();
	});





	function novoUsuario(){
		
		mostrarForm(0, 1);
	}
	
	
	
	
	function editarUsuario(){
		
	var id = getIdSelecionado("tab_usuarios");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		mostrarForm(id, 1);
	}
	
	
	
	
	
	
	function clonar(){
		
	var id = getIdSelecionado("tab_usuarios");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		mostrarForm(id, 4);
	}
	
	
	
	

	
	
	function mostrarForm(id, tipo){

		var funcao = "";
		var formulario = "";
		
		switch(tipo){
			
			case 1:
			funcao = "getForm";
			formulario = "form_edicao_usuarios";
			break;
				case 2:
				funcao = "getFormDeFiliais";
				formulario = "form_add_filiais";
				break;
					case 3:
					funcao = "getFormDeCargos";
					formulario = "form_add_cargos";
					break;
						case 4:
						funcao = "getFormClonar";
						formulario = "form_clonar";
						break;
		}
	
	
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:funcao,
				path:"/usuarios/",
				classe:"Usuarios",
				id_usuario:id
			},
			function(retorno){ 
				
				console.log(retorno);
				
				var erro = false;
				var aux;
					try { 
						aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
					}
					catch (e) { 
						erro = true; 
					} 
						
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else{
					
					mostrarDialogo(formulario, aux.msg);
					
					if(tipo == 1)
						trocarSenha();
				}					
			}
		);		
	}
	
	
	
	
	
	
	function salvarUsuario(id){

		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"salvarUsuario",
				path:"/usuarios/",
				classe:"Usuarios",
				id_usuario:id,
				usuario:$('#usuario').val(),
				nome:$('#nome').val(),
				email:$('#email').val(),
				tel:$('#tel').val(),
				cel:$('#cel').val(),
				senha:$('#senha').val(),
				repete_senha:$(repete_senha).val(),
				filial:$('#filial').val()
				
			},
			function(retorno){ 
				
				var erro = false;
				var aux;
					try { 
						aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
					}
					catch (e) { 
						erro = true; 
					} 
						
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else{
					
					alert("Operação realizada com sucesso.");
			
					carregaPagina("op=USR");	
				}			
			}
		);		
	}
	
	
	
	
	function ativarDesativar(){
		
		var id = getIdSelecionado("tab_usuarios");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		

		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"ativarDesativar",
				path:"/usuarios/",
				classe:"Usuarios",
				id_usuario:id
			},
			function(retorno){ 
				
				var erro = false;
				var aux;
					try { 
						aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
					}
					catch (e) { 
						erro = true; 
					} 
						
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else{
					
					alert("Operação realizada com sucesso.");
			
					carregaPagina("op=USR");	
				}			
			}
		);	
	}
	
	
	
	
	
	function trocarSenha(){
		
		if($("#trocar_senha").length){
			if($("#trocar_senha").is(":checked")){
				
				$("#senha").prop("disabled", false);
				$("#repete_senha").prop("disabled", false);
			}
			else{
				
				$("#senha").prop("disabled", "disabled");
				$("#repete_senha").prop("disabled", "disabled");
				
				$("#senha").val("");
				$("#repete_senha").val("");
			}
		}			
	}
	
	
	
	
/*	
	function adicionarFiliais(){
		
		var id = getIdSelecionado("tab_usuarios");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		mostrarForm(id, 2);
	}
	
	
	
	
	
	function selecionaListaDeFiliais(id){
		
		$(".linha_para_add").css("background", "#FFF");
			
		$(".linha_para_add").removeClass("selec_lista_filial_p_add");

		$("#linha_para_add_"+id).addClass("selec_lista_filial_p_add");
			
		$("#linha_para_add_"+id).css("background", "#32baff");
	}
	
	

	
	
	function getIdSelecionadoListaDeFiliais(){
	
		if($(".selec_lista_filial_p_add").length){
	
			var linha = $(".selec_lista_filial_p_add")[0];
	
			var aux  =	$(linha).attr('id').split("_");
			
			if(aux.length>0)
				return aux[aux.length-1]; 
			
			return 0;
		}
		
	return 0;
	}
	
	
	
	
	
	function transferirFilial(id_usuario){
		
		var id = getIdSelecionadoListaDeFiliais();
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"salvarAdicaoDeFilial",
				path:"/usuarios/",
				classe:"Usuarios",
				id_usuario:id_usuario,
				id_filial:id
			},
			function(retorno){ 
				
				var erro = false;
				var aux;
					try { 
						aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
					}
					catch (e) { 
						erro = true; 
					} 
						
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else{
					
					if(aux.msg.length ==2){
						$("#area_para_add").html(aux.msg[0]);
						$("#area_ja_add").html(aux.msg[1]);
					}
				}			
			}
		);		
	}
	
	
	
	
	
	function removerFilialDeUsuario(id_usuario, id_filial){
		
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"removerFilialDeUsuario",
				path:"/usuarios/",
				classe:"Usuarios",
				id_usuario:id_usuario,
				id_filial:id_filial
			},
			function(retorno){ 
				
				var erro = false;
				var aux;
					try { 
						aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
					}
					catch (e) { 
						erro = true; 
					} 
						
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else{
					
					if(aux.msg.length ==2){
						$("#area_para_add").html(aux.msg[0]);
						$("#area_ja_add").html(aux.msg[1]);
					}
				}			
			}
		);		
		
		
	}
	

*/	
	
	
	function mudaFilialPadrao(id_cargo, id_usuario){
			
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"mudaFilialPadrao",
				path:"/usuarios/",
				classe:"Usuarios",
				id_usuario:id_usuario,
				id_cargo:id_cargo
			},
			function(retorno){ 
				
				console.log(retorno);
				
				var erro = false;
				var aux;
					try { 
						aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
					}
					catch (e) { 
						erro = true; 
					} 
						
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else
					$("#div_area_cargos_adds").html(aux.msg);	
									
			}
		);		
		
		
	}

	
	
	
	
	
	function adicionarCargos(){
		
		var id = getIdSelecionado("tab_usuarios");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		mostrarForm(id, 3);
	}
	
	
	
	

	

	function mudaFilialDeCargo(){
		
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"mudaFilialDeCargo",
				path:"/usuarios/",
				classe:"Usuarios",
				filial:$('#filial_cargo').val()
			},
			function(retorno){ 
				
				console.log(retorno);
				
				var erro = false;
				var aux;
					try { 
						aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
					}
					catch (e) { 
						erro = true; 
					} 
						
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else
					$("#div_departamentos_cargo").html(aux.msg);			
			}
		);	
	}
	
	
	
	
	
	
	function mudaDepartamentoDeCargo(){
		
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"mudaDepartamentoDeCargo",
				path:"/usuarios/",
				classe:"Usuarios",
				departamento:$('#departamento_cargo').val()
			},
			function(retorno){ 
				
				console.log(retorno);
				
				var erro = false;
				var aux;
					try { 
						aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
					}
					catch (e) { 
						erro = true; 
					} 
						
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else
					$("#div_cargos_cargo").html(aux.msg);			
			}
		);	
	}
	
	
	
	
	
	
	
	function addNovoCargo(id_usuario){

		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"addNovoCargo",
				path:"/usuarios/",
				classe:"Usuarios",
				id_usuario:id_usuario,
				id_cargo:$('#cargo_cargo').val()
				
			},
			function(retorno){ 
				
				var erro = false;
				var aux;
					try { 
						aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
					}
					catch (e) { 
						erro = true; 
					} 
						
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else{
					
					mostrarForm(id_usuario, 3);
					/*
					$("#div_area_cargos_adds").html(aux.form);
					
					$("#filial_cargo").val(0);
					$("#div_departamentos_cargo").html("Departamentos:<select id='departamento_cargo' onChange='javascript:mudaDepartamentoDeCargo()'><option value='0'> ... </option></select>");
					$("#div_cargos_cargo").html("Cargos:<select id='cargo_cargo'><option value='0'> ... </option></select>");*/
				}		
			}
		);		
	}
	
	
	
	
	
	
	
	
	function removerCargo(id_usuario, id_cargo){
		
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"removerCargo",
				path:"/usuarios/",
				classe:"Usuarios",
				id_usuario:id_usuario,
				id_cargo:id_cargo
			},
			function(retorno){ 
				
				console.log(retorno);
				
				var erro = false;
				var aux;
					try { 
						aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
					}
					catch (e) { 
						erro = true; 
					} 
						
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else
					mostrarForm(id_usuario, 3);				
			}
		);		
		
		
	}
	
	
	
	
	
	
	function resetarSenha(senhapadrao){
			
		var id = getIdSelecionado("tab_usuarios");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"resetarSenha",
				path:"/usuarios/",
				classe:"Usuarios",
				id_usuario:id
			},
			function(retorno){ 
				
				console.log(retorno);
				
				var erro = false;
				var aux;
					try { 
						aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
					}
					catch (e) { 
						erro = true; 
					} 
						
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else{
					
					alert("Operação realizada com sucesso. Nova senha padrão \""+senhapadrao+"\".");
			
					carregaPagina("op=USR");	
				}				
			}
		);		
		
		
	}
	
	
	
	
	
	function clonarUsuario(id_usuario){
		
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"clonarUsuario",
				path:"/usuarios/",
				classe:"Usuarios",
				id_de:id_usuario,
				id_para:$('#usuario_para').val()	
			},
			function(retorno){ 
				
				console.log(retorno);
				
				var erro = false;
				var aux;
					try { 
						aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
					}
					catch (e) { 
						erro = true; 
					} 
						
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else{
					
					alert("Operação realizada com sucesso.");
			
					carregaPagina("op=USR");	
				}			
			}
		);		
	}
	