
///versao 1.0



	function mascara(param_campo, param_funcao){
	
	if(param_campo == null)
	return;
	
	v_obj=param_campo;
	v_fun=param_funcao;
	v_call=null;
	setTimeout("execmascara()",1);

	}

	
	
	
	function mascaraCallBack(param_campo, param_funcao, callback){
	
	if(param_campo == null)
	return;
	
	v_obj=param_campo;
	v_fun=param_funcao;
	v_call=callback;
	setTimeout("execmascara()",1);

	}
	
	
	
	

	function execmascara(){

	v_obj.value=v_fun(v_obj.value);
	
	if(v_call!=null)
		v_call();
	}


	
	
	function formatarSomenteNum(valor){
    
	valor=valor.replace(/\D/g,"");                  //Remove tudo o que não é dígito
                                              
    return valor;
	}



	
	function formatarData(valor){

	valor=valor.replace(/\D/g,"");                  //Remove tudo o que não é dígito
	
	//limita o campo a 8 digitos (sem contar as barras invertidas)
	if( valor.length > 8)
	valor=valor.substring(0, 8);   
	
    valor=valor.replace(/(\d{2})(\d)/,"$1/$2")       //Coloca um / entre o segundo e o terceiro dígitos
    valor=valor.replace(/(\d{2})(\d)/,"$1/$2")       //Coloca um / entre o quarto e o quinto dígitos
        
	return valor;
	}




	function formatarCPF( valor){
	
	valor=valor.replace(/\D/g,"");                  //Remove tudo o que não é dígito
	
	//limita o campo a 11 digitos (sem contar os pontos e traço)
	if( valor.length > 11)
	valor=valor.substring(0, 11);   
	
	valor=valor.replace(/(\d{3})(\d)/,"$1.$2");       //Coloca um ponto entre o terceiro e o quarto dígitos
    valor=valor.replace(/(\d{3})(\d)/,"$1.$2");      //Coloca um ponto entre o terceiro e o quarto dígitos, de novo (para o segundo bloco de números)
    valor=valor.replace(/(\d{3})(\d)/,"$1-$2"); //Coloca um hífen entre o terceiro e o quarto dígitos
    
	return valor;
	}
	
	
	
	
	
	function formatarCNPJ(valor){
	
	valor=valor.replace(/\D/g,"");                  //Remove tudo o que não é dígito
	
	if( valor.length > 15)
	valor=valor.substring(0, 15);   
	

	if(valor.length == 14)
	valor=valor.replace(/(\d{2})(\d)/,"$1.$2");       
	else if(valor.length == 15)
	valor=valor.replace(/(\d{3})(\d)/,"$1.$2");       
	
	
	
	valor=valor.replace(/(\d{3})(\d)/,"$1.$2");    
	valor=valor.replace(/(\d{3})(\d)/,"$1/$2");    
	valor=valor.replace(/(\d{4})(\d)/,"$1-$2");    
		
	return valor;
	}
	
	
	
	
	
	function formatarCEP(valor){
	
	valor=valor.replace(/\D/g,"");                  //Remove tudo o que não é dígito
	
	//limita o campo a 8 digitos (sem contar o traço)
	if( valor.length > 8)
	valor=valor.substring(0, 8);   
	
	valor=valor.replace(/(\d{5})(\d)/,"$1-$2");       //Coloca um ponto entre o terceiro e o quarto dígitos

	return valor;
	}
	
	
	
	
	
	function formatarDataMesAno(valor){

	valor=valor.replace(/\D/g,"");                  //Remove tudo o que não é dígito
	
	//limita o campo a 8 digitos (sem contar as barras invertidas)
	if( valor.length > 6)
	valor=valor.substring(0, 6);   
	
    valor=valor.replace(/(\d{2})(\d)/,"$1/$2")       //Coloca um / entre o segundo e o terceiro dígitos
    
	return valor;
	}


/*	
	function formatarTEL(valor){
	
	valor=valor.replace(/\D/g,"");                  //Remove tudo o que não é dígito
	
	//limita o campo a 9 digitos (sem contar o traço)
	if( valor.length > 9)
	valor=valor.substring(0, 9);   
	
	if(valor.length == 8)
	valor=valor.replace(/(\d{4})(\d)/,"$1-$2");       
	else if(valor.length == 9)
	valor=valor.replace(/(\d{5})(\d)/,"$1-$2");       
	
	return valor;
	}
	
*/	
	

	
	function formatarMonetario(valor){
	
	valor=valor.replace(/\D/g,"");                  //Remove tudo o que não é dígito
	
	if(valor.length ==0)
	return 0,00;
	
	// passa para inteiro e depois para string novamente (retirar zeros no inicio da string)
	valor = parseInt(valor)+"";	
	
	if(valor.length ==1)
	return "0,0"+valor;
	
	if(valor.length ==2)
	return "0,"+valor;
	
	var valor = valor+'';
	valor = valor.replace(/([0-9]{2})$/g, ",$1");
	if( valor.length > 6 )
	valor = valor.replace(/([0-9]{3}).([0-9]{2}$)/g, ".$1,$2");
 
	
	return valor;
	}
	
	
	

	function formatarPorcentagem( valor){
	
	valor=valor.replace(/\D/g,"");                  //Remove tudo o que não é dígito

	if(valor.length== 0)
	valor = '0';
	
	if(valor.length== 4)
	valor = valor.substring(0, 3);
	
	return valor+'%';
	}

	
	
	
	
	function formatarCPFCNPJ( valor){
	
	valor=valor.replace(/\D/g,"");                  //Remove tudo o que não é dígito
	
	if( valor.length > 11)
	return formatarCNPJ(valor); 
	
	return formatarCPF(valor); 
	}
	
	
	

	
	function formatarTEL( valor){
	
	valor=valor.replace(/\D/g,"");                  //Remove tudo o que não é dígito
	
	if( valor.length > 11)
	valor=valor.substring(0, 11);   
	
		if(valor.length >=3){
		valor=valor.replace(/(\d{0})(\d)/,"($1$2"); 
		valor=valor.replace(/(\d{2})(\d)/,"$1)$2"); 
		}
	
	
	if(valor.length == 12)
	valor=valor.replace(/(\d{4})(\d)/,"$1-$2");       
	if(valor.length == 13)
	valor=valor.replace(/(\d{5})(\d)/,"$1-$2");       
	
	if(valor.length>5)
		valor=valor.substring(0, 4)+" "+valor.substring(4);
	
	return valor;
	}
	
	
	
	
	
	function formatarDouble(valor){
	
		if(valor==null || valor.length==0)
		return "0";
	
		var aux="";
	
		var virgula = false;
		var virgula_pos = 0;
		for(var i =valor.length-1; i>=0; i--){
			
			if(valor[i]=='0' || valor[i]=='1' || valor[i]=='2' || 
				valor[i]=='3' || valor[i]=='4' || valor[i]=='5' || 
					valor[i]=='6' || valor[i]=='7' || valor[i]=='8' || 
						valor[i]=='9' || valor[i]==',' || valor[i]=='-'){
				
				if(valor[i]==','){
					
					if(!virgula){
						
						virgula_pos = i;
						virgula = true;
					}
					else
						continue;
				}
				
				aux=valor[i]+aux;
			}
				
		}
	
		if(virgula_pos==0)
			aux ="0"+aux;
	
		if(aux.length>0 && aux[aux.length-1]==',')
			aux +="0";
		
	return aux;
	}
	
	
	
	
	
	function formatarHorario( valor){
	
		valor=valor.replace(/\D/g,"");                  //Remove tudo o que não é dígito
	
		if( valor.length > 4)
			valor=valor.substring(0, 4);   
	
		valor=valor.replace(/(\d{2})(\d)/,"$1:$2");       
	
		return valor;
	}
	
	
	
	
	
	function formatarQuatroCasasDecimais(valor){
	
	valor=valor.replace(/\D/g,"");                  //Remove tudo o que não é dígito
	
	if(valor.length ==0)
	return "0,0000";
	
	// passa para inteiro e depois para string novamente (retirar zeros no inicio da string)
	valor = parseInt(valor)+"";	
	
	if(valor.length ==1)
	return "0,000"+valor;
	
	if(valor.length ==2)
	return "0,00"+valor;
	
	if(valor.length ==3)
	return "0,0"+valor;
	
	if(valor.length ==4)
	return "0,"+valor;
	
	var parte1 = valor.substring(0, valor.length-4);
	var parte2 = valor.substring(valor.length-4);
	
	parte1 = parseFloat(parte1).toLocaleString('pt-BR')+"";

	return parte1+","+parte2;
	}
	
	
	
	
	
	function formatarTresCasasDecimais(valor){
	
	valor=valor.replace(/\D/g,"");                  //Remove tudo o que não é dígito
	
	if(valor.length ==0)
	return "0,000";
	
	// passa para inteiro e depois para string novamente (retirar zeros no inicio da string)
	valor = parseInt(valor)+"";	
	
	if(valor.length ==1)
	return "0,00"+valor;
	
	if(valor.length ==2)
	return "0,0"+valor;
	
	if(valor.length ==3)
	return "0,"+valor;
	
	var parte1 = valor.substring(0, valor.length-3);
	var parte2 = valor.substring(valor.length-3);
	
	parte1 = parseFloat(parte1).toLocaleString('pt-BR')+"";

	return parte1+","+parte2;
	}
	
	
	
	
	
	function formatarUmaCasaDecimal(valor){
	
	valor=valor.replace(/\D/g,"");                  //Remove tudo o que não é dígito
	
	if(valor.length ==0)
	return "0,0";
	
	// passa para inteiro e depois para string novamente (retirar zeros no inicio da string)
	valor = parseInt(valor)+"";	
	
	if(valor.length ==1)
	return "0,"+valor;
	
	var parte1 = valor.substring(0, valor.length-1);
	var parte2 = valor.substring(valor.length-1);
	
	parte1 = parseFloat(parte1).toLocaleString('pt-BR')+"";

	return parte1+","+parte2;
	}
	
	
	
	
/*	
	function formatarMonetario(valor){
	
	valor=valor.replace(/\D/g,"");                  //Remove tudo o que não é dígito
	
	if(valor.length ==0)
	return 0,00;
	
	// passa para inteiro e depois para string novamente (retirar zeros no inicio da string)
	valor = parseInt(valor)+"";	
	
	if(valor.length ==1)
	return "0,0"+valor;
	
	if(valor.length ==2)
	return "0,"+valor;
	
	var valor = valor+'';
	valor = valor.replace(/([0-9]{2})$/g, ",$1");
	if( valor.length > 6 )
	valor = valor.replace(/([0-9]{3}).([0-9]{2}$)/g, ".$1,$2");
 
	
	return valor;
	}
*/	
	
	
	
	
	
	
	