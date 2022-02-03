 <?php

if(!isset($_SESSION))
session_start();

chdir(dirname(__FILE__)); 

chdir('../');

include_once getcwd().'/base/VNVPBase.class.php';

include_once VNVP_PATH_TAB_ABS.'Tabela.class.php';
include_once VNVP_PATH_DIA_ABS.'Dialogo.class.php';


final class Filiais extends VNVPBase{


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
		
		<script src='".VNVP_PATH_SMP."filiais/filiais.js' type='text/javascript'></script>
	
		<link rel='stylesheet' href='".VNVP_PATH_SMP."filiais/filiais.css' type='text/css' media='all'>";
	
		$this->tab->dependencias();
		
		$this->dia->dependencias();
	}

	
	
	
	
	
	public function conteudo(){

		$this->tab->setPathABSDoObjeto(VNVP_PATH_ABS."filiais/");
	
		$this->tab->setOrderByFixo("###.nome ASC");
	
		$form = "
			<div id='area_opcoes_direita'>
				<table>
					<tr>";
	
		if($this->temPermissao("GESFIL", ACESSO_EDITAR)){	
			
			$_SESSION['PODE_EDITAR'] = true;
			
			$this->tab->setFuncaoDuploClick("editarFilial()");
			
			$form .= "	<td align='center' class='opcao_vnvp'>
							<button onclick='javascript:novaFilial()'>
								<img src='".VNVP_PATH_SMP."imgs/novo.png' class='bt_vnvp'>
							</button>
							<br>Novo
						</td>
						<td  align='center' class='opcao_vnvp'>
							<button onclick='javascript:editarFilial()'>
								<img src='".VNVP_PATH_SMP."imgs/alterar.png' class='bt_vnvp'>
							</button>
							<br>Editar
						</td>";
		}				
						
						
		if($this->temPermissao("GESFIL", ACESSO_EDITAR))				
			$form .= "	<td  align='center' class='opcao_vnvp'>
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
		
		$form .= $this->tab->getTabela("BeanFilial", 'tab_filiais');
		
		$form .= $this->dia->getForm("", "form_edicao_filiais", "Cadastro de Filial", 70, 300);
		
		echo $form;
	}
	
	
	
	
	
	
	public function getForm(){
	
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'filiais/BeanFilial.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
			
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		$filial = $bd->getPorId(new BeanFilial(), $comuns->anti_injection($_POST['id_filial']));

		if(!is_object($filial))
		$filial  = new BeanFilial();
	
		$form = "
				<div id='div_codigo' class='item_form'>
					Código:<span class='campo_obrigatorio'>*</span><br>
					<input type='text' id='codigo' value='".$filial->codigo."'>
				</div>
				<div id='div_nome' class='item_form'>
					Nome:<span class='campo_obrigatorio'>*</span><br>	
					<input type='text' id='nome' value='".$filial->nome."'>
				</div>
				<div id='div_endereco' class='item_form'>
					Endereço:<br>
					<input type='text' id='endereco' value='".$filial->endereco."'>
				</div>
				<div id='div_tel_1' class='item_form'>
					Fone: <span class='indicadores_ajuda'>(xx) xxxxx-xxxx</span><br>
					<input type='text' id='tel_1' value='".$filial->tel_1."' maxlength='15' onchange='javascript:mascara(this, formatarTEL);'>
				</div>
				<div id='div_tel_2' class='item_form'>
					Fone: <span class='indicadores_ajuda'>(xx) xxxxx-xxxx</span><br>
					<input type='text' id='tel_2' value='".$filial->tel_2."' maxlength='15' onchange='javascript:mascara(this, formatarTEL);'>
				</div>
				<div id='div_email' class='item_form'>
					E-mail:<br>
					<input type='text' id='email' value='".$filial->email."'>
				</div>
				<div id='div_site' class='item_form'>
					Site (inclua o http://):<br>
					<input type='text' id='site' value='".$filial->site."'>
				</div>
				<div style='clear:both'></div>
				<div align='center'>
					<div class='vnvp_bt' id='bt_salvar_filial' onclick='javascript:salvarFilial(".($filial->id>0?$filial->id:0).")'>
						Salvar Filial
					</div>	
					<br><br>
				</div>";
	
	
		echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($form).'"}';
	}
	
	
	
	
	
	
	public function salvarFilial(){
	
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'filiais/BeanFilial.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
		$comuns = new Comuns();
		$bd = new BdUtil();
	
		$erros = self::validaFilial($bd, $comuns);
	
			if(strlen($erros) == 0){

				$filial = null;
	
				if($_POST['id_filial']>0)
					$filial = $bd->getPorId(new BeanFilial(), $_POST['id_filial']);
				
				if(!is_object($filial))
					$filial = new BeanFilial();
			

				$filial->nome  =  		$_POST['nome'];
				$filial->endereco = 	$_POST['endereco'];
				$filial->codigo = 		$_POST['codigo'];
				$filial->tel_1= 		$_POST['tel_1'];
				$filial->tel_2= 		$_POST['tel_2'];
				$filial->email= 		$_POST['email'];
				$filial->site= 			$_POST['site'];

				if($filial->id<=0){
			
					$filial->status 		= 1;
				
					$filial->id = $bd->novo($filial);
			
					if($filial->id<=0){
					
						echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
						return;
					}
				}
				else{
			
			
					if(!$bd->altera($filial)){
			
						echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
						return;
					}
				}
			
		
				echo '{"status":"sucesso"}';	
				return;
			}
				
		
		echo '{"status":"ERRO", "erro":"'.$erros.'"}';
	}
	
	
	
	

	
	public function validaFilial(&$bd, &$comuns){
	
		$_POST['id_filial'] = 	$comuns->anti_injection($_POST['id_filial']);
		$_POST['codigo'] = 		$comuns->anti_injection($_POST['codigo']);
		$_POST['nome'] = 		$comuns->anti_injection($_POST['nome']);
		$_POST['endereco'] = 	$comuns->anti_injection($_POST['endereco']);
		$_POST['tel_1'] = 		$comuns->anti_injection($_POST['tel_1']);
		$_POST['tel_2'] = 		$comuns->anti_injection($_POST['tel_2']);
		$_POST['email'] = 		$comuns->anti_injection($_POST['email']);
		$_POST['site'] = 		$comuns->anti_injection($_POST['site']);
	
		if(strlen($_POST['codigo']) == 0)
			return "Informe um código para a filial.";
	
	
		$reg = $bd->getPrimeiroOuNada(new BeanFilial(), 
										null, 
											"###.codigo='".$_POST['codigo']."'".($_POST['id_filial']>0?" and ###.id_filial<>".$_POST['id_filial']:""),
											null);
	
		if($reg!=null)
			return "O código informado já está sendo usado por outra filial.";

		if(strlen($_POST['nome']) == 0)
			return "Informe um nome para a filial.";
		
		if(strlen($_POST['tel_1']) > 0){	
			if(!$comuns->validaTEL($_POST['tel_1']))
				return "Informe um fone 1 válido.";
		}
		
		if(strlen($_POST['tel_2']) > 0){	
			if(!$comuns->validaTEL($_POST['tel_2']))
				return "Informe um fone 2 válido.";
		}
		
		if(strlen($_POST['email']) > 0){	
			if(!$comuns->validaEmail($_POST['email']))
				return "Informe um endereço de E-mail válido.";
		}
		
		return "";
	}


	
	
		
	public function ativarDesativar(){
			
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'filiais/BeanFilial.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
		$comuns = new Comuns();
		$bd = new BdUtil();
	
		$_POST['id_filial'] = 	$comuns->anti_injection($_POST['id_filial']);
	
		$filial = $bd->getPorId(new BeanFilial(), $_POST['id_filial']);
				
		if(is_object($filial)){
			
			if($filial->status>0)
				$filial->status = 0;
			else
				$filial->status = 1;
			
			if($bd->altera($filial)){
			
				echo '{"status":"sucesso"}';
				return;
			}
		}
		
		echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
	}
	

	
	
}

?>