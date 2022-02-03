 <?php

if(!isset($_SESSION))
session_start();

chdir(dirname(__FILE__)); 

chdir('../');

include_once getcwd().'/base/VNVPBase.class.php';

include_once VNVP_PATH_TAB_ABS.'Tabela.class.php';
include_once VNVP_PATH_DIA_ABS.'Dialogo.class.php';


final class Departamentos extends VNVPBase{


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
		
		<script src='".VNVP_PATH_SMP."departamentos/departamentos.js' type='text/javascript'></script>
	
		<link rel='stylesheet' href='".VNVP_PATH_SMP."departamentos/departamentos.css' type='text/css' media='all'>";
	
		$this->tab->dependencias();
		
		$this->dia->dependencias();
	}

	
	
	
	
	
	public function conteudo(){

		$this->tab->setPathABSDoObjeto(VNVP_PATH_ABS."departamentos/");

		$this->tab->setOrderByFixo("###.fk_filial DESC, ###.nome ASC");
	
		$form = "
			<div id='area_opcoes_direita'>
				<table>
					<tr>";
					
		if($this->temPermissao("GESDEP", ACESSO_EDITAR)){	
			
			$_SESSION['PODE_EDITAR'] = true;
			
			$this->tab->setFuncaoDuploClick("editarDepartamento()");			
			
			$form .= "		
						<td align='center' class='opcao_vnvp'>
							<button onclick='javascript:novoDepartamento()'>
								<img src='".VNVP_PATH_SMP."imgs/novo.png' class='bt_vnvp'>
							</button>
							<br>Novo
						</td><td  align='center' class='opcao_vnvp'>
							<button onclick='javascript:editarDepartamento()'>
								<img src='".VNVP_PATH_SMP."imgs/alterar.png' class='bt_vnvp'>
							</button>
							<br>Editar
						</td>";
		}
		
		if($this->temPermissao("GESDEP", ACESSO_EXCLUIR))
		$form .= "				
						<td  align='center' class='opcao_vnvp'>
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
		
		$form .= $this->tab->getTabela("BeanDepartamento", 'tab_departamentos');
		
		$form .= $this->dia->getForm("", "form_edicao_departamento", "Cadastro de Departamento", 70, 250);
		
		echo $form;
	}
	
	
	
	
	
	
	public function getForm(){
	
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'departamentos/BeanDepartamento.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
			
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		$departamento = $bd->getPorId(new BeanDepartamento(), $comuns->anti_injection($_POST['id_departamento']));

		if(!is_object($departamento))
		$departamento  = new BeanDepartamento();
	
		$form = "
				<div id='div_codigo' class='item_form'>
					Código:<span class='campo_obrigatorio'>*</span><br>
					<input type='text' id='codigo' value='".$departamento->codigo."'>
				</div>
				<div id='div_nome' class='item_form'>
					Nome:<span class='campo_obrigatorio'>*</span><br>	
					<input type='text' id='nome' value='".$departamento->nome."'>
				</div>";
				
				
		$form .= $this->getFiliais($departamento->fk_filial, $bd);
		
				
		$form .= "	
				<div style='clear:both'></div>
				<div align='center'>
					<div class='vnvp_bt' id='bt_salvar_departamento' onclick='javascript:salvarDepartamento(".($departamento->id>0?$departamento->id:0).")'>
						Salvar Departamento
					</div>	
					<br><br>
				</div>";
	
	
		echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($form).'"}';
	}
	
	
	
	
	
	
	private function getFiliais($id_filial, &$bd){
	
		include_once VNVP_PATH_ABS."filiais/BeanFilial.class.php";
		
		$form ="<div id='div_filial' class='item_form'>
					Filial:
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
	
	
	
	

	
	public function salvarDepartamento(){
	
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'departamentos/BeanDepartamento.class.php';
		$bd = new BdUtil();
	
		$erros = self::validaDepartamento($bd);
	
			if(strlen($erros) == 0){

				$departamento = null;
	
				if($_POST['id_departamento']>0)
					$departamento = $bd->getPorId(new BeanDepartamento(), $_POST['id_departamento']);
				
				if(!is_object($departamento))
					$departamento = new BeanDepartamento();
			

				$departamento->nome  =  		$_POST['nome'];
				$departamento->codigo = 		$_POST['codigo'];
				$departamento->fk_filial = 		$_POST['filial'];

				if($departamento->id<=0){
			
					$departamento->status 		= 1;
					
					$departamento->id = $bd->novo($departamento);
			
					if($departamento->id<=0){
					
						echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
						return;
					}
				}
				else{
			
			
					if(!$bd->altera($departamento)){
			
						echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
						return;
					}
				}
			
		
				echo '{"status":"sucesso"}';	
				return;
			}
				
		
		echo '{"status":"ERRO", "erro":"'.$erros.'"}';
	}
	
	
	
	

	
	public function validaDepartamento(&$bd){
	
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
		$comuns = new Comuns();
	
		$_POST['id_departamento'] = 	$comuns->anti_injection($_POST['id_departamento']);
		$_POST['codigo'] = 				$comuns->anti_injection($_POST['codigo']);
		$_POST['nome'] = 				$comuns->anti_injection($_POST['nome']);
		$_POST['filial'] = 				$comuns->anti_injection($_POST['filial']);
	
		if(strlen($_POST['codigo']) == 0)
			return "Informe um código para o departamento.";
	
	
		$reg = $bd->getPrimeiroOuNada(new BeanDepartamento(), 
										null, 
											"###.codigo='".$_POST['codigo']."'".($_POST['id_departamento']>0?" and ###.id_departamento<>".$_POST['id_departamento']:""),
											null);
	
		if($reg!=null)
			return "O código informado já está sendo usado por outro departamento.";

		if(strlen($_POST['nome']) == 0)
			return "Informe um nome para o departamento.";
		
		if($_POST['filial']<=0)
			return "Selecione uma filial.";
		
		
		
		return "";
	}


	
	
	
			
	public function ativarDesativar(){
			
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
		include_once VNVP_PATH_ABS.'departamentos/BeanDepartamento.class.php';
		
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		$_POST['id_departamento'] = 	$comuns->anti_injection($_POST['id_departamento']);
		
		$departamento = $bd->getPorId(new BeanDepartamento(), $_POST['id_departamento']);
				
		if(is_object($departamento)){
			
			if($departamento->status>0)
				$departamento->status = 0;
			else
				$departamento->status = 1;
			
			if($bd->altera($departamento)){
			
				echo '{"status":"sucesso"}';
				return;
			}
		}
		
		echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
	}
	

	
	
}

?>