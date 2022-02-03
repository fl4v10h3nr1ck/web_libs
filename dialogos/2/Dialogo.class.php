<?php
 
chdir(dirname(__FILE__)); 

include_once getcwd().'/define.php';


final class Dialogo{



	
	
	public function dependencias(){
	
		echo "
		
		<script src='".DIA_PATH_SMP."dialogo.js' type='text/javascript'></script>
		
		<link rel='stylesheet' href='".DIA_PATH_SMP."dialogo.css' type='text/css' media='all'>";
	}

	
	
	
	
	public function getForm($form, $id, $titulo, $largura_porcento, $altura_px){
	
	if($largura_porcento<30)
	$largura_porcento= 20;
	
	if($largura_porcento>=30 && $largura_porcento<40)
	$largura_porcento =30;
	
	if($largura_porcento>=40 && $largura_porcento<50)
	$largura_porcento= 40;
	
	if($largura_porcento>=50 && $largura_porcento<60)
	$largura_porcento= 50;
	
	if($largura_porcento>=60 && $largura_porcento<70)
	$largura_porcento= 60;
	
	if($largura_porcento>=70 && $largura_porcento<80)
	$largura_porcento= 70;
	
	if($largura_porcento>=80 && $largura_porcento<90)
	$largura_porcento= 80;
	
	if($largura_porcento>=90)
	$largura_porcento= 90;
	
	$aux = "<div align='center' class='dialogo_div_geral largura_".$largura_porcento."' style='min-height:".$altura_px."px' id='".$id."'>
			<input type='hidden' value='".$largura_porcento."' id='largura'>	
				<table width='96%' style='background:#EEE;margin-top:5px'>
					<tr>
						<td  width='70%' align='left'>
						<b>".$titulo."</b>
						</td><td width='30%' align='right'>
						<button onclick='javascript:fechar()'><img src='".DIA_PATH_SMP."imgs/fechar.png' style='width:30px;height:30px'></button>
						</td>
					</tr>
				</table>
				<hr with='100%'>
				<div align='left' style='background:#FFF;margin:auto;min-height:".($altura_px-60)."px'>
					<div align='left' style='padding:10px' id='area_conteudo_".$id."'>
					".$form."
					</div>
				</div>
			</div>
			<div class='sombra' id='sombra_".$id."'></div>";
	
	return $aux;
	}

}

?>