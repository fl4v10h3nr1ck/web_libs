


function formataNumeroEmMoeda(valor){
		
var val = valor+"";

var i = val.indexOf(".");

var aux = val;


if(i<0)	
aux+="00";
else if(i==(val.length-2))	
aux+="0";
else if(i<(val.length-2))	
aux=val.substring(0, i)+"."+val.substring(i+1, i+3);	


return aux;
}




function getNumeroDeMoeda(valor){

var bruto = valor.length>0?valor:"0";

return parseFloat(bruto.replace(".", "").replace(",", "."));
}
