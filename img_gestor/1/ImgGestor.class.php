<?php

chdir(dirname(__FILE__)); 

include_once getcwd().'/define.php';




final class ImgGestor{


private $permitir_png;
private $permitir_gif;
	
	
	
	public function ImgGestor(){
		
	$this->permitirPng();
	
	$this->permitirGif();
	}
	

	
	
	public function dependencias(){
		
		
	echo "
	
	<script src='".IMGGESTOR_BASE_PACK_SPS."img_gestor.js' type='text/javascript'></script>
	
	<script src='".IMGGESTOR_LOCAL_SMP_JCROP."js/jquery.Jcrop.min.js'></script>
	
	<link rel='stylesheet' href='".IMGGESTOR_LOCAL_SMP_JCROP."css/jquery.Jcrop.css' type='text/css'/>
	
	<script src='".IMGGESTOR_LOCAL_SMP_JFORM."jquery.form.min.js'></script>";
	}
	
	
	
	
	
	
	public function permitirPng($permitir = true){
	
	$this->permitir_png = $permitir;
	}
	
	
	
	
	
	public function permitirGif($permitir = true){
	
	$this->permitir_gif = $permitir;
	}
	
	
	
	
	
	
	
	public function getFormDeUpload($id, 
									$titulo, 
									$path_img_smp, 
									$path_img_abs, 
									$nome_img, 
									$tamanho_max, 
									$largura, 
									$altura,
									$radio_w_por_h,
									$altura_max_exibicao,
									$funcao_js_pre_envio,
									$funcao_js_envio_sucesso){
		
	$img = strlen($nome_img)==0?IMGGESTOR_BASE_PACK_SPS."imgs/fundo_preview.jpg":$path_img_smp.$nome_img;	
		
	$form = "
		<div class='area_geral_de_upload'>
		<b>".$titulo."</b><br><br>
			<div id='area_pre_visao_".$id."' align='center'>
			<img src='".$img."' class='img_preview' style='max-height:".$altura_max_exibicao."px'>					
			</div>
			<form id='form_envia_img_".$id."' method='post' enctype='multipart/form-data' action='".IMGGESTOR_BASE_PACK_SPS."acao.php?id=".$id."'>
									
			<input type='hidden' id='nome_arq_antigo_".$id."' name='nome_arq_antigo_".$id."' value='".$nome_img."'>
			<input type='file' id='nova_imagem_".$id."' name='nova_imagem_".$id."' onChange=\"javascript:setPrevisao('".$id."', ".$radio_w_por_h.", ".$altura_max_exibicao.")\">
			
			<input type='hidden' id='png_".$id."' name='png_".$id."' value='".($this->permitir_png?1:0)."'/>
			<input type='hidden' id='gif_".$id."' name='gif_".$id."' value='".($this->permitir_gif?1:0)."'/>

			
			<input type='hidden' id='x_".$id."' name='x_".$id."'/>
			<input type='hidden' id='y_".$id."' name='y_".$id."'/>
			<input type='hidden' id='w_".$id."' name='w_".$id."'/>
			<input type='hidden' id='h_".$id."' name='h_".$id."'/>
			
			<input type='hidden' id='tam_max_".$id."' name='tam_max_".$id."' value='".$tamanho_max."'/>
			<input type='hidden' id='al_".$id."' name='al_".$id."' value='".$altura."'/>
			<input type='hidden' id='lar_".$id."' name='lar_".$id."' value='".$largura."'/>
				
			<input type='hidden' id='path_smp_".$id."' name='path_smp_".$id."' value='".$path_img_smp."'/>
			<input type='hidden' id='path_abs_".$id."' name='path_abs_".$id."' value='".$path_img_abs."'/>
			
			<input type='hidden' id='al_max_exib_".$id."' name='al_max_exib_".$id."' value='".$altura_max_exibicao."'/>
	
				
			</form>
			<span style='font-size:9px'>
			Dimensões mínimas: ".$largura."x".$altura." Pixels<br>
			Tamanho máximo: ".$tamanho_max."KB.<br>
			Extensões permitidas: jpeg | jpg".($this->permitir_png?" | png":"").($this->permitir_gif?" | gif":"").".<br>
			Clique e Arraste o mouse sobre a imagem carregada para definir a área de corte.<br>
			</span>
			<br><br>
				<div class='bt_verde' onclick=\"javascript:if(".$funcao_js_pre_envio."()){EnviarImgDeUpload('".$id."', '".$funcao_js_envio_sucesso."')}\" style='width:150px'>Salvar Foto</div>
			<br>
		</div>";
	
	return $form;	
	}
	
	
	
	
	
	
	
	public function salvarImg(){

	
	$id = array_key_exists("id", $_GET)?$_GET['id']:"";
	
		if(strlen($id)==0){
		echo '{"status":"ERRO", "erro":"Dados insuficientes para upload."}';
		return;
		}
	
		if(!array_key_exists("nova_imagem_".$id,  $_FILES)){
			
		echo '{"status":"ERRO", "erro":"Selecione um arquivo para upload."}';
		return;
		}	
    
	$tamanho_max = array_key_exists("tam_max_".$id, $_POST)?$_POST["tam_max_".$id]:0;
	
	$tamanho = round($_FILES["nova_imagem_".$id]['size'] / 1024);
            
        if($tamanho >= $tamanho_max){
		
		echo '{"status":"ERRO", "erro":"O arquivo deve ser de no máximo '.$tamanho_max.'KB."}';
		return;
		} 
	
	
	$permitidos = array(".jpg",".jpeg",".gif",".png");
	
	$ext = strtolower(strrchr($_FILES["nova_imagem_".$id]['name'],"."));
	
		if(!in_array($ext, $permitidos)){
		
		echo '{"status":"ERRO", "erro":"Tipo de arquivo não permitido."}';
		return;
		} 

 
		if(strlen($_POST['w_'.$id])==0 || $_POST['w_'.$id]==0 || 
					strlen($_POST['h_'.$id])==0 || $_POST['h_'.$id]==0){
		
		echo '{"status":"ERRO", "erro":"Área de recorte não definida."}';
		return;
		} 
 
		
	$targ_w = array_key_exists("lar_".$id, $_POST)?$_POST["lar_".$id]:0;
	$targ_h = array_key_exists("al_".$id, $_POST)?$_POST["al_".$id]:0;	
		
		
	$img = getimagesize($_FILES["nova_imagem_".$id]['tmp_name']);
		
		if($img[0]<$targ_w){
		
		echo '{"status":"ERRO", "erro":"Largura da imagem menor que a permitida."}';
		return;
		} 		
			
		if($img[1]<$targ_h){
		
		echo '{"status":"ERRO", "erro":"Altura da imagem menor que a permitida."}';
		return;
		}
			
	
	$fator = $_POST['al_max_exib_'.$id]/$img[1];
	
	$path = array_key_exists("path_abs_".$id, $_POST)?$_POST["path_abs_".$id]:"";
	$novo_nome = "gu".date("dmYHi").str_pad(rand(1, 1000000), 8, "0",  STR_PAD_LEFT).$ext;
	
   
	if(!move_uploaded_file($_FILES["nova_imagem_".$id]['tmp_name'], $path.$novo_nome))
	echo '{"status":"ERRO", "erro":"Um erro ocorreu ao enviar o arquivo para o servidor."}';


	if(is_file($path.$_POST['nome_arq_antigo_'.$id]))
	unlink($path.$_POST['nome_arq_antigo_'.$id]); 	
 	

	//$aux = $_POST['x_'.$id] - 
	
   
	$_POST['x_'.$id]= $_POST['x_'.$id]/$fator;
	$_POST['y_'.$id]= $_POST['y_'.$id]/$fator;
	
	//echo $_POST['w_'.$id]." ".$_POST['h_'.$id]." ";
	
	
	$_POST['w_'.$id]= intval($_POST['w_'.$id]/$fator);
	$_POST['h_'.$id]= intval($_POST['h_'.$id]/$fator);

 
	//echo $_POST['w_'.$id]." ".$_POST['h_'.$id];
	
	$img_r = null;
 
	if(strrpos($ext, "jpg")!==false || strrpos($ext, "jpeg")!==false)
    $img_r = imageCreateFromJpeg($path.$novo_nome);
	elseif(strrpos($ext, "gif")!==false)
	$img_r = imageCreateFromGif($path.$novo_nome);
	elseif(strrpos($ext, "png")!==false)
	$img_r = imageCreateFromPng($path.$novo_nome);

    $dst_r = ImageCreateTrueColor( $targ_w, $targ_h);
 
    imagecopyresampled(	$dst_r, $img_r, 
						0, 0, 
						$_POST['x_'.$id], $_POST['y_'.$id], 
						$targ_w, $targ_h, 
						$_POST['w_'.$id], $_POST['h_'.$id]);
   
	$retorno = false;
   
   
	if(strrpos($ext, "jpg")!==false || strrpos($ext, "jpeg")!==false)
    $retorno = imagejpeg($dst_r, $path.$novo_nome, 100);
	elseif(strrpos($ext, "gif")!==false)
	$retorno = imagegif($dst_r, $path.$novo_nome);
	elseif(strrpos($ext, "png")!==false)
	$retorno = imagepng($dst_r, $path.$novo_nome, 0);
   
	if($retorno)
	echo '{"status":"SUCESSO", "nome":"'.$novo_nome.'"}';
	else
	echo '{"status":"ERRO", "erro":"Um erro ocorreu ao enviar o arquivo para o servidor."}';
	}
		

		
	
	
}

?>


