/* Fonction d'affichage de la page de présentation */
function getPresentation()   {
	request("GET", "presentation.php" , true, setData);	
}

/* Fonction d'affichage du don paypal */
function getPaiementOK()   {
	request("GET", "presentation.php?paypal=1" , true, setData);	
}
/* Fonction d'affichage du don paypal */
function getPaiementKO()   {
	request("GET", "presentation.php?paypal=0" , true, setData);	
}

/* Fonction d'affichage du règlement */
function getReglement()   {
	request("GET", "reglement.php" , true, setData);	
}

/*Fonction de validation du mail saisi*/
function ValidMail()   {
	mail=document.getElementById("mail_txt").value;
	if ((replaceCharacters(mail,' ','')=='') || (!checkEmail(mail)))   {
		//alert("Saississez un mail valide !!");
		$('#DIVshake').effect('pulsate', { times:3 }, 500);
		document.getElementById("mail_txt").focus();
		return false;
	}
	var arguments = "mail=" + mail;
	request("POST", "presentation.php", true, setPresentationData, arguments);
}

function setPresentationData(res)   {
	if (res=="WAIT")   {
		document.getElementById("DIVshake").innerHTML='<table align="center"><tr><td><img src="Image/Attente.gif"></td></tr></table>';
		return false
	}
	document.getElementById("DIVshake").innerHTML = res;
	$("#DIVshake").effect("highlight", {}, 3000);
}

function getMailTouch(event)   {
	if (Enter_detector(event))
		ValidMail();
}

/* Revocation du cookie en cours */
function setMailRevoke()   {
	var arguments = "mail_reset=OK";
	request("POST", "presentation.php" , true, setData, arguments);	
}