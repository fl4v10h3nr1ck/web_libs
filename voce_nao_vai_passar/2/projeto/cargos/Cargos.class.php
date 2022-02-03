 <?php

if(!isset($_SESSION))
session_start();

chdir(dirname(__FILE__)); 

chdir('../');

include_once getcwd().'/base/VNVPBase.class.php';

include_once VNVP_PATH_TAB_ABS.'Tabela.class.php';
include_once VNVP_PATH_DIA_ABS.'Dialogo.class.php';


final class Cargos extends VNVPBase{


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
		
		<script src='".VNVP_PATH_SMP."cargos/cargos.js' type='text/javascript'></script>
	
		<link rel='stylesheet' href='".VNVP_PATH_SMP."cargos/cargos.css' type='text/css' media='all'>";
	
		$this->tab->dependencias();
		
		$this->dia->dependencias();
	}

	
	
	
	
	
	public function conteudo(){

		$this->tab->setPathABSDoObjeto(VNVP_PATH_ABS."cargos/");
		
		$this->tab->setOrderByFixo("###.fk_departamento DESC, dpt.fk_filial DESC, ###.nome ASC");
	
		$form = "
			<div id='area_opcoes_direita'>
				<table>
					<tr>";
					
		if($this->temPermissao("GESCAR", ACESSO_EDITAR)){	
			
			$_SESSION['PODE_EDITAR'] = true;
			
			$this->tab->setFuncaoDuploClick("editarCargo()");

			$form .= "
						<td align='center' class='opcao_vnvp'>
							<button onclick='javascript:novoCargo()'>
								<img src='".VNVP_PATH_SMP."imgs/novo.png' class='bt_vnvp'>
							</button>
							<br>Novo
						</td><td  align='center' class='opcao_vnvp'>
							<button onclick='javascript:editarCargo()'>
								<img src='".VNVP_PATH_SMP."imgs/alterar.png' class='bt_vnvp'>
							</button>
							<br>Editar
						</td>";
		}				
						
		if($this->temPermissao("GESCAR", ACESSO_EXCLUIR))
			$form .= "							
						<td  align='center' class='opcao_vnvp'>
							<button onclick='javascript:deletarCargo()'>
								<img src='".VNVP_PATH_SMP."imgs/excluir.png' class='bt_vnvp'>
							</button>
							<br>Remover
						</td><td  align='center' class='opcao_vnvp'>
							<button onclick='javascript:ativarDesativar()'>
								<img src='".VNVP_PATH_SMP."imgs/ativar.png' class='bt_vnvp'>
							</button>
							<br>Ativ./Desativ.
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
		
		$form .= $this->tab->getTabela("BeanCargo", 'tab_cargos');
		
		$form .= $this->dia->getForm("", "form_edicao_cargos", "Cadastro de Cargo", 70, 250);
		
		echo $form;
	}
	
	
	
	
	
	
	public function getForm(){
	
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'cargos/BeanCargo.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
		include_once VNVP_PATH_ABS."departamentos/BeanDepartamento.class.php";
			
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		$cargo = $bd->getPorId(new BeanCargo(), $comuns->anti_injection($_POST['id_cargo']));

		if(!is_object($cargo))
		$cargo  = new BeanCargo();
	
		$id_filial = 0;
		if($cargo->fk_departamento>0){
				
			$departamento = $bd->getPorId(new BeanDepartamento(), $cargo->fk_departamento);
		
			if(is_object($departamento))
				$id_filial = $departamento->fk_filial;
				
		}
		
		$form = "
				<div id='div_codigo' class='item_form'>
					Código:<span class='campo_obrigatorio'>*</span><br>
					<input type='text' id='codigo' value='".$cargo->codigo."'>
				</div>
				<div id='div_nome' class='item_form'>
					Nome:<span class='campo_obrigatorio'>*</span><br>	
					<input type='text' id='nome' value='".$cargo->nome."'>
				</div>
				<div id='div_filial' class='item_form'>
					Filial:<span class='campo_obrigatorio'>*</span><br>	
					".$this->getFiliais($cargo->fk_departamento, $bd, $id_filial)."
				</div>
				<div id='div_departamento' class='item_form'>";
				

		$form .= $this->getDepartamentos($cargo->fk_departamento, $bd, $id_filial);
		
				
		$form .= "
				</div>
				<div style='clear:both'></div>
				<div align='center'>
					<div class='vnvp_bt' id='bt_salvar_cargo' onclick='javascript:salvarCargo(".($cargo->id>0?$cargo->id:0).")'>
						Salvar Cargo
					</div>	
					<br><br>
				</div>";
	
	
		echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($form).'"}';
	}
	
	
	
	
		
	
	private function getFiliais($id_departamento, &$bd, $id_filial){
	
		include_once VNVP_PATH_ABS."filiais/BeanFilial.class.php";
		
		$form ="	<select id='filial' onChange='javascript:mudaDepartamento()'>
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
	
	
	
	
	
	
	
	
	private function getDepartamentos($id_departamento, &$bd, $id_filial){
	
		include_once VNVP_PATH_ABS."departamentos/BeanDepartamento.class.php";
		
		$form ="	Departamento:<span class='campo_obrigatorio'>*</span><br>	
					<select id='departamento'>
						<option value='0'> ... </option>";
		
		$departamentos = $bd->getPorQuery(new BeanDepartamento(), null, "###.status>0 and ###.fk_filial=".$id_filial, null);
		
			if(count($departamentos)>0){
							
				foreach($departamentos as $departamento)					
					$form .= "	
						<option value='".$departamento->id."' ".($id_departamento==$departamento->id?"selected":"").">(".$departamento->codigo.") ".$departamento->nome."</option>";			
							
			}				
		
		$form .= "	</select>";		
		
		return $form;
	}
	
	
	
	
	
	
	public function mudaDepartamento(){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
		
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($this->getDepartamentos(0, $bd, $_POST['filial'])).'"}';
	}
	
	



	
	public function salvarCargo(){
	
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'cargos/BeanCargo.class.php';
		$bd = new BdUtil();
	
		$erros = self::validaCargo($bd);
	
			if(strlen($erros) == 0){

				$cargo = null;
	
				if($_POST['id_cargo']>0)
					$cargo = $bd->getPorId(new BeanCargo(), $_POST['id_cargo']);
				
				if(!is_object($cargo))
					$cargo = new BeanCargo();
			

				$cargo->nome  =  				$_POST['nome'];
				$cargo->codigo = 				$_POST['codigo'];
				$cargo->fk_departamento = 		$_POST['departamento'];

				if($cargo->id<=0){
			
					$cargo->status 		= 1;
					
					$cargo->id = $bd->novo($cargo);
			
					if($cargo->id<=0){
					
						echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
						return;
					}
				}
				else{
			
			
					if(!$bd->altera($cargo)){
			
						echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
						return;
					}
				}
			
		
				echo '{"status":"sucesso"}';	
				return;
			}
				
		
		echo '{"status":"ERRO", "erro":"'.$erros.'"}';
	}
	
	
	
	

	
	public function validaCargo(&$bd){
	
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
		$comuns = new Comuns();
	
		$_POST['id_cargo'] = 			$comuns->anti_injection($_POST['id_cargo']);
		$_POST['codigo'] = 				$comuns->anti_injection($_POST['codigo']);
		$_POST['nome'] = 				$comuns->anti_injection($_POST['nome']);
		$_POST['departamento'] = 		$comuns->anti_injection($_POST['departamento']);
	
		if(strlen($_POST['codigo']) == 0)
			return "Informe um código para o cargo.";
	
	
		$reg = $bd->getPrimeiroOuNada(new BeanCargo(), 
										null, 
											"###.codigo='".$_POST['codigo']."'".($_POST['id_cargo']>0?" and ###.id_cargo<>".$_POST['id_cargo']:""),
											null);
	
		if($reg!=null)
			return "O código informado já está sendo usado por outro cargo.";

		if(strlen($_POST['nome']) == 0)
			return "Informe um nome para o cargo.";
		
		if($_POST['departamento']<=0)
			return "Selecione um departamento.";
		
		
		
		return "";
	}


	
	
	
			
	public function ativarDesativar(){
			
	include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
	include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	include_once VNVP_PATH_ABS.'cargos/BeanCargo.class.php';
	
	$comuns = new Comuns();
	$bd = new BdUtil();
	
	$_POST['id_cargo'] = 	$comuns->anti_injection($_POST['id_cargo']);
	
	$cargo = $bd->getPorId(new BeanCargo(), $_POST['id_cargo']);
				
		if(is_object($cargo)){
			
			if($cargo->status>0)
				$cargo->status = 0;
			else
				$cargo->status = 1;
			
			if($bd->altera($cargo)){
			
				echo '{"status":"sucesso"}';
				return;
			}
		}
		
		echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
	}
	

	

	
	
	public function deletarCargo(){
		
		include_once VNVP_PATH_ABS.'cargos/BeanCargo.class.php';
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		$_POST['id_cargo'] = 	$comuns->anti_injection($_POST['id_cargo']);
		
		// remove as referencias de usuários a esse cargo
		$bd->executaQuery("update usuarios set fk_cargo=NULL where fk_cargo=".$_POST['id_cargo']);
			
		if($bd->deletaPorId(new BeanCargo, $_POST['id_cargo']) !== false)
			echo '{"status":"sucesso"}';
		else
			echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
	}
	
	


	
}

?>