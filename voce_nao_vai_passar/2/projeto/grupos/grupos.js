


	function novoGrupo(){
		
		mostrarForm(0, 0);
	}
	
	
	
	
	
	
	function editarGrupo(){
		
	var id = getIdSelecionado("tab_grupos");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		mostrarForm(id, 0);
	}
	
	

	
	
	
	function mostrarForm(id, tipo){

		var funcao = "";
		var formulario = "";
		
		switch(tipo){
			
			case 0:
			funcao = "getForm";
			formulario = "form_edicao_grupos";
			break;
				case 1:
				funcao = "getFormDePermissoes";
				formulario = "form_edicao_permissoes";
				break;
					case 2:
					funcao = "getFormDeUsuarios";
					formulario = "form_add_usuarios";
					break;
		}
		
	
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:funcao,
				path:"/grupos/",
				classe:"Grupos",
				id_grupo:id
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
				
				}		
			}
		);		
	}
	
	
	
	
	
	function salvarGrupo(id){

		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"salvarGrupo",
				path:"/grupos/",
				classe:"Grupos",
				id_grupo:id,
				codigo:$('#codigo').val(),
				nome:$('#nome').val(),
				descricao:$('#descricao').val(),
				filial:$('#filial').val(),
				admin:$('#check_admin_form_grupo').is(":checked")?1:0
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
			
					carregaPagina("op=GRP");	
				}			
			}
		);		
	}
	
	
	
	
	
	function deletarGrupo(){
	
		var id = getIdSelecionado("tab_grupos");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		if(!confirm("Tem certeza que deseja excluir este grupo (operação irreversível)?"))
			return;
		
		
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"deletarGrupo",
				path:"/grupos/",
				classe:"Grupos",
				id_grupo:id
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
			
					carregaPagina("op=GRP");	
				}			
			}
		);	
	}
	
	
	
	
	
	function ativarDesativar(){
		
		var id = getIdSelecionado("tab_grupos");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		

		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"ativarDesativar",
				path:"/grupos/",
				classe:"Grupos",
				id_grupo:id
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
			
					carregaPagina("op=GRP");	
				}			
			}
		);	
	}
	
	
	
	
	
	function permissoes(){
		
		var id = getIdSelecionado("tab_grupos");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		mostrarForm(id, 1);
	}
	
	
	
	
	function salvarPermissoes(id){
		
		var dados = "";
	
		$(".permissao_item").each(function(){
			
			if($(this).is(":checked")){
			
				var id = $(this).attr('id').replace(/\D+/g, '');	
				
				dados += id+"@";
				
				if($(this).hasClass("vsn"))
					dados += "4";
				
				else if($(this).hasClass("ver"))
					dados += "1";
				
				else if($(this).hasClass("edt"))
					dados += "2";
				
				else if($(this).hasClass("exr"))
					dados += "3";
			
					
			dados += "#";
			}	
		});
		
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"salvarPermissoes",
				path:"/grupos/",
				classe:"Grupos",
				id_grupo:id,
				permissoes:dados
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
			
					carregaPagina("op=GRP");	
				}			
			}
		);		
	}
	
	
	
	
	
	
	function marcaPermissao(id, tipo){
		
		if(tipo==0){
		
			$("#editar_"+id).prop('checked', false);
			$("#excluir_"+id).prop('checked', false);
		}
		else if(tipo==1){
		
			if($("#editar_"+id).is(":checked")){
				
				$("#ver_"+id).prop('checked', true);
				$("#excluir_"+id).prop('checked', false);	
			}
			else{
				
				$("#excluir_"+id).prop('checked', false);
			}
		}
		else if(tipo==2){
		
			if($("#excluir_"+id).is(":checked")){
				
				console.log("excluir");
				
				$("#ver_"+id).prop('checked', true);
				$("#editar_"+id).prop('checked', true);	
			}
		}
	}
	
	
	
	
	
	function adicionarUsuario(){
		
		var id = getIdSelecionado("tab_grupos");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		mostrarForm(id, 2);
	}
	
	
	
	
	
	function selecionaListaDeUsuario(id){
		
		$(".linha_para_add").css("background", "#FFF");
			
		$(".linha_para_add").removeClass("selec_lista_usuario_p_add");

		$("#linha_para_add_"+id).addClass("selec_lista_usuario_p_add");
			
		$("#linha_para_add_"+id).css("background", "#32baff");
	}
	
	

	
	
	function getIdSelecionadoListaDeUSuario(){
	
		if($(".selec_lista_usuario_p_add").length){
	
			var linha = $(".selec_lista_usuario_p_add")[0];
	
			var aux  =	$(linha).attr('id').split("_");
			
			if(aux.length>0)
				return aux[aux.length-1]; 
			
			return 0;
		}
		
	return 0;
	}
	
	
	
	
	
	function transferirUsuario(id_grupo){
		
		var id = getIdSelecionadoListaDeUSuario();
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"salvarAdicaoDeUsuario",
				path:"/grupos/",
				classe:"Grupos",
				id_grupo:id_grupo,
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
					
					var formularios  = aux.msg.split("##_##");
					
					if(formularios.length ==2){
						$("#area_para_add").html(formularios[0]);
						$("#area_ja_add").html(formularios[1]);
					}
				}			
			}
		);		
	}
	
	
	
	
	
	function removerUsuarioDeGrupo(id_grupo, id_usuario){
		
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"removerUsuarioDeGrupo",
				path:"/grupos/",
				classe:"Grupos",
				id_grupo:id_grupo,
				id_usuario:id_usuario
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
					
					var formularios  = aux.msg.split("##_##");
					
					if(formularios.length ==2){
						$("#area_para_add").html(formularios[0]);
						$("#area_ja_add").html(formularios[1]);
					}
				}			
			}
		);		
		
		
	}
	
	
	