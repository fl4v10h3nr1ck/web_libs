 <?php

if(!isset($_SESSION))
session_start();

chdir(dirname(__FILE__)); 

chdir('../');

include_once getcwd().'/base/VNVPBase.class.php';

include_once VNVP_PATH_TAB_ABS.'Tabela.class.php';
include_once VNVP_PATH_DIA_ABS.'Dialogo.class.php';


final class Grupos extends VNVPBase{


private $tab;

private $dia;



	function __construct() {
		
		parent::__construct();
		
		$_SESSION['PODE_EDITAR'] = false;
		
		$this->tab= new Tabela();	
		
		$this->dia= new Dialogo();
	}

	


	
	public function dependencias(){
	
		echo "
		
		<script src='".VNVP_PATH_SMP."grupos/grupos.js' type='text/javascript'></script>
	
		<link rel='stylesheet' href='".VNVP_PATH_SMP."grupos/grupos.css' type='text/css' media='all'>";
	
		$this->tab->dependencias();
		
		$this->dia->dependencias();
	}

	
	
	
	
	
	public function conteudo(){

		$this->tab->setPathABSDoObjeto(VNVP_PATH_ABS."grupos/");
		
		$this->tab->setOrderByFixo("###.fk_filial DESC, ###.nome ASC");
	
		$form = "
			<div id='area_opcoes_direita'>
				<table>
					<tr>";
					
		if($this->temPermissao("GEGGRP", ACESSO_EDITAR)){	
			
			$_SESSION['PODE_EDITAR'] = true;
			
			$this->tab->setFuncaoDuploClick("editarGrupo()");
			
			$form .= "	
						<td align='center' class='opcao_vnvp'>
							<button onclick='javascript:novoGrupo()'>
								<img src='".VNVP_PATH_SMP."imgs/novo.png' class='bt_vnvp'>
							</button>
							<br>Novo
						</td>
						<td  align='center' class='opcao_vnvp'>
							<button onclick='javascript:editarGrupo()'>
								<img src='".VNVP_PATH_SMP."imgs/alterar.png' class='bt_vnvp'>
							</button>
							<br>Editar
						</td>";
		}
						
		if($this->temPermissao("GEGGRP", ACESSO_EXCLUIR))
			$form .= "			
						<td  align='center' class='opcao_vnvp'>
							<button onclick='javascript:deletarGrupo()'>
								<img src='".VNVP_PATH_SMP."imgs/excluir.png' class='bt_vnvp'>
							</button>
							<br>Remover
						</td><td  align='center' class='opcao_vnvp'>
							<button onclick='javascript:ativarDesativar()'>
								<img src='".VNVP_PATH_SMP."imgs/ativar.png' class='bt_vnvp'>
							</button>
							<br>Ativ./Desativ.
						</td>";
						
		if($this->temPermissao("ADDPMS"))				
			$form .= "	
						<td  align='center' class='opcao_vnvp'>
							<button onclick='javascript:permissoes()'>
								<img src='".VNVP_PATH_SMP."imgs/permissao.png' class='bt_vnvp'>
							</button>
							<br>Permissões
						</td>";
						
		if($this->temPermissao("VINUSR"))					
			$form .= "	<td  align='center' class='opcao_vnvp'>
							<button onclick='javascript:adicionarUsuario()'>
								<img src='".VNVP_PATH_SMP."imgs/transferir.png' class='bt_vnvp'>
							</button>
							<br>Usuários
						</td>";
		
		$form .= "				
					</tr>
				</table>&nbsp;
			</div>
			<div id='area_opcoes_esquerda' align='right'>
				<table>
					<tr>
						<td align='center' class='opcao'>
							<button onclick='javascript:window.open(\"".PSNL_PATH_SMP."\", \"_SELF\")'>
								<img src='".PSNL_PATH_SMP."imgs/voltar.png' class='bt_opcao'>
							</button>
							<br>Voltar
						</td>
					</tr>
				</table>
			</div>";
		
		$form .= $this->tab->getTabela("BeanGrupo", 'tab_grupos');
		
		$form .= $this->dia->getForm("", "form_edicao_grupos", "Cadastro de Grupos", 70, 300);
		
		$form .= $this->dia->getForm("", "form_edicao_permissoes", "Cadastro de Permissões", 70, 450);
		
		$form .= $this->dia->getForm("", "form_add_usuarios", "Adição de Usuários", 80, 400);
		
		echo $form;
	}
	
	
	
	
	
	
	public function getForm(){
	
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'grupos/BeanGrupo.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
			
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		$grupo = $bd->getPorId(new BeanGrupo(), $comuns->anti_injection($_POST['id_grupo']));

		if(!is_object($grupo))
		$grupo  = new BeanGrupo();
	
		$form = "
				<div id='div_codigo' class='item_form'>
					Código:<span class='campo_obrigatorio'>*</span><br>
					<input type='text' id='codigo' value='".$grupo->codigo."'>
				</div>
				<div id='div_nome' class='item_form'>
					Nome:<span class='campo_obrigatorio'>*</span><br>	
					<input type='text' id='nome' value='".$grupo->nome."'>
				</div>
				<div id='div_descricao' class='item_form'>
					Descrição:<br>
					<input type='text' id='descricao' value='".$grupo->descricao."'>
				</div>
				<div id='div_filial' class='item_form'>
				".$this->getFiliais($bd, $grupo->fk_filial)."
				</div>
				
				<div id='div_admin' class='item_form'>
					Administrador:<br>
					<input id='check_admin_form_grupo' class='switch switch--shadow' type='checkbox' ".($grupo->admin>0?"checked":"").">
					<label for='check_admin_form_grupo'></label>
				</div>
				<div style='clear:both'></div>
				<div align='center'>
					<div class='vnvp_bt' id='bt_salvar_grupo' onclick='javascript:salvarGrupo(".($grupo->id_grupo>0?$grupo->id_grupo:0).")'>
						Salvar Grupo
					</div>	
					<br><br>
				</div>";
	
	
		echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($form).'"}';
	}
	
	
	
	
	
	
	private function getFiliais(&$bd, $id_filial){
	
		include_once VNVP_PATH_ABS."filiais/BeanFilial.class.php";
		
		$form ="	Filial:<span class='campo_obrigatorio'>*</span><br>	
					<select id='filial'>
						<option value='0'> ... </option>";
		
		
		$filiais = $bd->getPorQuery(new BeanFilial(), null, "###.status>0", null);
		
			if(count($filiais)>0){
							
				foreach($filiais as $filial)					
					$form .= "	
						<option value='".$filial->id."' ".($id_filial==$filial->id?"selected":"").">(".$filial->codigo.") ".$filial->nome."</option>";			
							
			}				
		
		$form .= "	</select>
				</div>";		
		
		return $form;
	}
	
	

	
	
	public function salvarGrupo(){
	
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'grupos/BeanGrupo.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
		$comuns = new Comuns();
		$bd = new BdUtil();
	
		$erros = self::validaGrupo($bd, $comuns);
	
			if(strlen($erros) == 0){

				$grupo = null;
	
				if($_POST['id_grupo']>0)
					$grupo = $bd->getPorId(new BeanGrupo(), $_POST['id_grupo']);
				
				if(!is_object($grupo))
					$grupo = new BeanGrupo();
			

				$grupo->nome  =  		$_POST['nome'];
				$grupo->descricao = 	$_POST['descricao'];
				$grupo->codigo = 		$_POST['codigo'];
				$grupo->admin= 			$_POST['admin'];
				$grupo->fk_filial = 	$_POST['filial'];

				if($grupo->id_grupo<=0){
			
					$grupo->status 		= 1;
					$grupo->data_criacao = date("Y-m-d");
				
					$grupo->id_grupo = $bd->novo($grupo);
			
					if($grupo->id_grupo<=0){
					
						echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
						return;
					}
				}
				else{
			
			
					if(!$bd->altera($grupo)){
			
						echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
						return;
					}
				}
			
		
				echo '{"status":"sucesso"}';	
				return;
			}
				
		
		echo '{"status":"ERRO", "erro":"'.$erros.'"}';
	}
	
	
	
	

	
	public function validaGrupo(&$bd, &$comuns){
	

		$_POST['id_grupo'] = 	$comuns->anti_injection($_POST['id_grupo']);
		$_POST['codigo'] = 		$comuns->anti_injection($_POST['codigo']);
		$_POST['nome'] = 		$comuns->anti_injection($_POST['nome']);
		$_POST['descricao'] = 	$comuns->anti_injection($_POST['descricao']);
		$_POST['admin'] = 		$comuns->anti_injection($_POST['admin']);
		$_POST['filial'] = 		$comuns->anti_injection($_POST['filial']);

	
		if(strlen($_POST['codigo']) == 0)
			return "Informe um código para o grupo.";
	
	
		$reg = $bd->getPrimeiroOuNada(new BeanGrupo(), 
										null, 
											"###.cod='".$_POST['codigo']."'".($_POST['id_grupo']>0?" and ###.id_grupo<>".$_POST['id_grupo']:""),
											null);
	
		if($reg!=null)
			return "O código informado já está sendo usado por outro grupo.";

		if(strlen($_POST['nome']) == 0)
			return "Informe um nome para o grupo.";
		
		if($_POST['filial']<=0)
			return "seleciona a filial a qual o grupo pertence.";
		
		
		return "";
	}


	
	
	
	public function deletarGrupo(){
		
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuarioGrupo.class.php';
		include_once VNVP_PATH_ABS.'grupos/BeanGrupo.class.php';
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		$_POST['id_grupo'] = 	$comuns->anti_injection($_POST['id_grupo']);
		
		// remove as referencias de usuários a esse grupo
		$bd->deletaPorQuery(new BeanUsuarioGrupo, "fk_grupo=".$_POST['id_grupo']);
			
		if($bd->deletaPorId(new BeanGrupo, $_POST['id_grupo']) !== false)
			echo '{"status":"sucesso"}';
		else
			echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
	}
	
	
	
	
	
		
	public function ativarDesativar(){
			
	include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
	include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	include_once VNVP_PATH_ABS.'grupos/BeanGrupo.class.php';
	
	$comuns = new Comuns();
	$bd = new BdUtil();
	
	$_POST['id_grupo'] = 	$comuns->anti_injection($_POST['id_grupo']);
	
	$grupo = $bd->getPorId(new BeanGrupo(), $_POST['id_grupo']);
				
		if(is_object($grupo)){
			
			if($grupo->status>0)
				$grupo->status = 0;
			else
				$grupo->status = 1;
			
			if($bd->altera($grupo)){
			
				echo '{"status":"sucesso"}';
				return;
			}
		}
		
		echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
	}
	

	
	
	

	public function getFormDePermissoes(){
	
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'grupos/BeanGrupo.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
		
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		$_POST['id_grupo'] = $comuns->anti_injection($_POST['id_grupo']);
		
		$grupo = $bd->getPorId(new BeanGrupo(), $_POST['id_grupo']);

		if(!is_object($grupo)){
			
			echo '{"status":"ERRO", "erro":"Grupo não encontrado."}';
			return;
		}
		
		if($grupo->admin>0){
			
			$form = "<div align='center' style='width:100%;padding-top:100px'>
						&laquo; Grupos de administradores já possuem todas as permissões habilitadas por padrão &raquo;
					</div>";
			
			echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($form).'"}';
			return;
		}
		
		
		include_once VNVP_PATH_ABS.'grupos/BeanGrupoAcesso.class.php';
		include_once VNVP_PATH_ABS.'grupos/BeanAcesso.class.php';
		
			
		$ids_permissoes = array();
		$valores_permissoes = array();
		
		$permissoes_grupo = $bd->getPorQuery(new BeanGrupoAcesso(), 
					null, "###.fk_grupo=".$_POST['id_grupo'], null);

					
		if(count($permissoes_grupo)>0){
			
			foreach($permissoes_grupo as $permissao){
				
				$ids_permissoes[] 		= $permissao->fk_acesso;
				$valores_permissoes[] 	= $permissao->valor;	
			}
		}
		
		$acessos = $bd->getPorQuery(new BeanAcesso(), null, null, "###.ordem ASC, ###.nome ASC");

		$form .= "
				<div id='area_acessos'>
					<table border= '1' id='tab_acessos'>
						<tr>
							<th width='20%' align='center' class='acesso_ocultar'>CÓDIGO</th>
							<th width='50%' align='center'>ACESSO</th>
							<th width='30%' align='center'>PERMISSÃO</th>
						</tr>";
		
		if(count($acessos)>0){
		
		$ordem_atual = 0;
		
			foreach($acessos as $acesso){
				
				if($ordem_atual != $acesso->ordem){
					
					$ordem_atual = 	$acesso->ordem;
					
					$titulo = "";

					switch($ordem_atual){
						
						case 1:
							$titulo = "Gestão de Conta Pessoal";
							break;
							case 2:
							$titulo = "Gestão geral de Usuários";
							break;
								case 3:
								$titulo = "Gestão de Entidades Administrativas";
								break;
									case 4:
									$titulo = "Gestão de Grupos de Usuários";
									break;
										case 5:
										$titulo = "HelpDesk";
										break;
											case 6:
											$titulo = "Arquivos e Documentos";
											break;
												case 7:
												$titulo = "CNC";
												break;
													default:
													$titulo = "Outros";
													break;
					}
					
					
					$form .= "	
						<tr>
							<td align='center' class='acesso_ocultar' colspan='3' align='center'><b>".$titulo."</b></td>
						</tr>";	
				}
				
				$form .= "	
						<tr>
							<td align='center' class='acesso_ocultar'>".strtoupper($acesso->codigo)."</td>
							<td align='left'>".$acesso->nome."</td>
							<td align='center'>";
							
				if(strcmp($acesso->tipo, ACESSO_SIM_NAO)==0){
				
					$cv = array_search($acesso->id, $ids_permissoes);
				
					$checkado = false;
					
					if($cv!==false && 
						strlen($valores_permissoes[$cv])>0 && 
							strcmp($valores_permissoes[$cv], ACESSO_SIM)==0)
					$checkado = true;		
						
					$form .= "
								<input  ".($checkado?"checked":"")." id='acesso_".$acesso->id."' class='switch switch--shadow permissao_item vsn' type='checkbox'>
								<label for='acesso_".$acesso->id."'></label>";		
				}			
				else{
					
					$cv = array_search($acesso->id, $ids_permissoes);
				
					$ver = false;
					$editar = false;
					$excluir = false;
					
					if($cv!==false && 
						strlen($valores_permissoes[$cv])>0){

						if(strcmp($valores_permissoes[$cv], ACESSO_VER)==0)
						$ver = true;		
						
						if(strcmp($valores_permissoes[$cv], ACESSO_EDITAR)==0)
						$editar = true;	
					
						if(strcmp($valores_permissoes[$cv], ACESSO_EXCLUIR)==0)
						$excluir = true;	
					}
					
						
					$form .= "	<table width='100%'>
									<tr>
										<td width='33%' align='center'>
										Ver:
										<input  ".($ver || $editar || $excluir?"checked":"")." id='ver_".$acesso->id."' class='permissao_item ver' type='checkbox' onclick='javascript:marcaPermissao(".$acesso->id.", 0)'>
										</td>
										<td width='34%' align='center'>
										Editar:
										<input  ".($editar || $excluir?"checked":"")." id='editar_".$acesso->id."' class='permissao_item edt' type='checkbox' onclick='javascript:marcaPermissao(".$acesso->id.", 1)'>
										</td>
										<td width='33%' align='center'>
										Excluir:
										<input  ".($excluir?"checked":"")." id='excluir_".$acesso->id."' class='permissao_item exr' type='checkbox' onclick='javascript:marcaPermissao(".$acesso->id.", 2)'>
										</td>
									</tr>
								</table>";	
				}

				$form .= "	</td>
						</tr>";
			}
		}
	
		$form .= "	</table>
				</div>
				<div align='center'>
					<div class='vnvp_bt' id='bt_salvar_permissoes' onclick='javascript:salvarPermissoes(".($grupo->id_grupo>0?$grupo->id_grupo:0).")'>
						Salvar Permissões
					</div>	
					<br>
				</div>";
	
		echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($form).'"}';
	}
	
	
	

	
	
	public function salvarPermissoes(){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'grupos/BeanGrupoAcesso.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
		$comuns = new Comuns();
		$bd = new BdUtil();
	
		$_POST['id_grupo'] = 	$comuns->anti_injection($_POST['id_grupo']);
		
		// remove as referencias de permissoes a esse grupo
		$bd->deletaPorQuery(new BeanGrupoAcesso, "fk_grupo=".$_POST['id_grupo']);
		
		$permissoes = explode("#", $_POST['permissoes']);
		
		if(count($permissoes)>0){

			$ids = array();
			$valores = array();
			
			foreach($permissoes as $permissao){
			
				if(strlen($permissao)>0){
			
					$args = explode("@", $permissao);
					
					if(count($args)==2 &&
							strlen($args[0])>0 &&
								strlen($args[1])>0){
			
						$cv = array_search($args[0], $ids);
			
						if($cv===false){
			
							$ids[] = $args[0];
							$valores[] = $args[1];
						}
						else{
								
							if(intval($args[1])>intval($valores[$cv]))
								$valores[$cv] = $args[1];
						}
					}
				}
			}
			

			foreach($ids as $i=>$id){

				$valor = "";
					
				if($valores[$i] == 4)
					$valor = ACESSO_SIM;
				elseif($valores[$i] == 1)
					$valor = ACESSO_VER;
				elseif($valores[$i] == 2)
					$valor = ACESSO_EDITAR;
				elseif($valores[$i] == 3)
					$valor = ACESSO_EXCLUIR;
				
				if(strlen($valor)>0){
			
					$grupoAcesso  =  new BeanGrupoAcesso();
					$grupoAcesso->fk_acesso = 		$id;
					$grupoAcesso->fk_grupo = 		$_POST['id_grupo'];
					$grupoAcesso->valor= 			$valor;
					
					$grupoAcesso->id = $bd->novo($grupoAcesso);
			
					if($grupoAcesso->id<=0){
					
						echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
						return;
					}
				}
			}
		}
				
		
		echo '{"status":"sucesso"}';
	}

	
	
	
	
	public function getFormDeUsuarios(){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuario.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
		$bd = new BdUtil();
		$comuns = new Comuns();
	
		$form = "
			<div class='area_lista_usuarios'>
				<div style='margin:10px 0px 10px 10px'>
					<b>Usuários disponíveis:</b>
				</div>
				<div id='area_para_add'>
				".$this->getParaAdicionar($bd)."
				</div>
			</div>
			<div id='area_transferir_usuarios' align='center'>
				<div class='vnvp_bt' id='bt_transferir_usuario' onclick='javascript:transferirUsuario(".$_POST['id_grupo'].")'>
					&raquo ADD &raquo;
				</div>	
			</div>
			<div class='area_lista_usuarios'>
				<div style='margin:10px 0px 10px 10px'>
					<b>Usuários do Grupo:</b>
				</div>
				<div id='area_ja_add'>
				".$this->getjaAdicionados($bd)."
				</div>
			</div>
			<div style='clear:both'></div>";
		
		echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($form).'"}';
	}
	
	
	
	
	
	
	private function getParaAdicionar(&$bd){
		
		$para_add = $bd->getPorQuery(new BeanUsuario(), 
											null, 
											"###.id_usuario NOT IN (select fk_usuario from usuarios_x_grupos where fk_grupo=".$_POST['id_grupo'].")", 
											null);
		
		$form = "
				<table class='tab_lista_usuarios' cellspacing=0 id='tab_para_add'>
					<tr>
						<th align='center'>
						NOME
						</th>
					</tr>";
		
		if(count($para_add)>0){
			
			foreach($para_add as $usuario)
				$form .= "
					<tr class='linha_para_add' id='linha_para_add_".$usuario->id."' onclick='javascript:selecionaListaDeUsuario(".$usuario->id.")'> 
						<td align='left'>
						(".$usuario->usuario.") ".$usuario->nome_completo."
						</td>
					</tr>";	
		}		
						
		$form .= "
			</table>";
			
		return $form;
	}
	
	
	
	
	
	private function getjaAdicionados(&$bd){
		
		
		$ja_add = $bd->getPorQuery(new BeanUsuario(), 
											null, 
											"###.id_usuario IN (select fk_usuario from usuarios_x_grupos where fk_grupo=".$_POST['id_grupo'].")", 
											null);

		$form = "
				<table class='tab_lista_usuarios' cellspacing=0  id='tab_ja_add'>
					<tr>
						<th align='center'>
						NOME
						</th>
					</tr>";
		
		if(count($ja_add)>0){

			foreach($ja_add as $usuario)
				$form .= "	
					<tr id='linha_ja_add_".$usuario->id."'>
						<td align='left'>
							<table width='100%'>
								<tr>
									<td align='left' width='85%' style='border:none'>
									(".$usuario->usuario.") ".$usuario->nome_completo."
									</td>
									<td align='center' width='15%' style='border:none'>
										<div class='vnvp_bt vnvp_bt_vermelho bt_remover_usuario' onclick='javascript:removerUsuarioDeGrupo(".$_POST['id_grupo'].", ".$usuario->id.")'>
										X
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>";	
		}	
		
		$form .= "
			</table>";
		
		return $form;
	}
	
	
	
	
	
	public function salvarAdicaoDeUsuario(){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuario.class.php';
		include_once VNVP_PATH_ABS.'grupos/BeanGrupo.class.php';
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuarioGrupo.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
	
		$bd = new BdUtil();
		$comuns = new Comuns();
		
		$para_add = $bd->getPorId(new BeanUsuario(), $comuns->anti_injection($_POST['id_usuario']));
	
		$grupo = $bd->getPorId(new BeanGrupo(), $comuns->anti_injection($_POST['id_grupo']));
		
		$msg ="";
		
		if(is_object($para_add) &&
			is_object($grupo)){
			
			$add = new BeanUsuarioGrupo;
				
			$add->fk_usuario = $para_add->id;
			$add->fk_grupo = $grupo->id_grupo;
			
			$add->id = $bd->novo($add);
			
			if($add->id<=0){
					
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}
			
			$msg =	$this->getParaAdicionar($bd)."##_##".$this->getjaAdicionados($bd);
				
		}
		else{
			
			echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
			return;	
		}
		
		echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($msg).'"}';
	}
	
	
	
	
	
	public function removerUsuarioDeGrupo(){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuario.class.php';
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuarioGrupo.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
	
		$bd = new BdUtil();
		$comuns = new Comuns();
		
		$para_remover = $bd->getPorId(new BeanUsuario(), $comuns->anti_injection($_POST['id_usuario']));
	
		if(is_object($para_remover)){
		
			if(!$bd->deletaPorQuery(new BeanUsuarioGrupo(), 
							"fk_grupo=".$comuns->anti_injection($_POST['id_grupo']).
								" and fk_usuario=".$para_remover->id)){
				
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}

			echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($this->getParaAdicionar($bd)."##_##".$this->getjaAdicionados($bd)).'"}';			
			return;
		}
		
		echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
	}


	
}

?>