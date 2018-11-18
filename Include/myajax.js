//********************************************************************************************************
// La seule fonction à utiliser ici est request, le traitement doit être fait dans la fonction de callback
//********************************************************************************************************
var xmlhttp = null;

//Crée la variable nécessaire aux requêtes
function getXMLHttp()   {
	if (window.XMLHttpRequest)
		xmlhttp = new XMLHttpRequest();
	else
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	return (xmlhttp);
}
			
//Vérifie que la requête a terminé et a retournée une valeur
function checkRequest()   {
	if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		return (true);
	return (false);
}
			
//Fonction de requête. callback = fonction de traitement de la réponse (xmlhttp.responseText en paramètre), args = arguments facultatifs de la fonction callback
function request(methode, url, async, callback, args, last)
{
	//Si une requete en cours, on quitte
	if (xmlhttp && xmlhttp.readyState != 0)
		return ;
	
	//on récupère l'obj nécessaire aux requêtes
	xmlhttp = getXMLHttp();
	
	if (async)
		callback('WAIT'); /* réponse d'un message d'attente */
	
	//Définition de la fonction de callback avec passage des args si présents
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
	//Envoi de la requête
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