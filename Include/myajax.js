//********************************************************************************************************
// La seule fonction � utiliser ici est request, le traitement doit �tre fait dans la fonction de callback
//********************************************************************************************************
var xmlhttp = null;

//Cr�e la variable n�cessaire aux requ�tes
function getXMLHttp()   {
	if (window.XMLHttpRequest)
		xmlhttp = new XMLHttpRequest();
	else
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	return (xmlhttp);
}
			
//V�rifie que la requ�te a termin� et a retourn�e une valeur
function checkRequest()   {
	if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		return (true);
	return (false);
}
			
//Fonction de requ�te. callback = fonction de traitement de la r�ponse (xmlhttp.responseText en param�tre), args = arguments facultatifs de la fonction callback
function request(methode, url, async, callback, args, last)
{
	//Si une requete en cours, on quitte
	if (xmlhttp && xmlhttp.readyState != 0)
		return ;
	
	//on r�cup�re l'obj n�cessaire aux requ�tes
	xmlhttp = getXMLHttp();
	
	if (async)
		callback('WAIT'); /* r�ponse d'un message d'attente */
	
	//D�finition de la fonction de callback avec passage des args si pr�sents
	xmlhttp.onreadystatechange=function() {
		if (checkRequest(xmlhttp))   {
			if (!args || methode == "POST") 
				callback(xmlhttp.responseText);
			else
				callback(xmlhttp.responseText, args);
			xmlhttp = null;
			if (last)
				last();
		}
	}
	//Envoi de la requ�te
	xmlhttp.open(methode, url, async);
	
	if (methode == "POST")   {
		try   {
			xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		}
		catch(error)    {
			alert(error);
		}
	}
	
	if (methode == "GET" || !args) // Si methode == get pas d'argument a transmettre
		xmlhttp.send(null);
	else
		xmlhttp.send(args); // Si c'est post on transmet les arguments POST
}