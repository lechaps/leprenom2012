function getReponse()   {
	request("GET", "reponse.php?methode=saisie" , true, setData);
}

/* Fonction d'affichage de la page d'administration */
function getReponseTouch(event)   {
	if (Enter_detector(event))
		getReponseCheck();
}

/*Fonction de validation du login de la partie administration saisi*/
function getReponseCheck()   {
	mail	= document.getElementById("Mail_Reponse").value;
	prenom	= document.getElementById("Prenom_Reponse").value;
	if ((replaceCharacters(mail,' ','')=='') || (!checkEmail(mail)))   {
		//alert("Saississez un mail valide !!");
		$('#DIVshake').effect('pulsate', { times:3 }, 500);
		return false;
	}
	Nb = Math.random();
	if (Nb>=0.5)
		$('#DIVshake').effect('explode', 500);
	else
		$('#DIVshake').effect('puff', 500);
	setTimeout("setForm()", 750);
}

/*envoi le formulaire de saisie*/
function setForm()   {
	var arguments = "prenom=" + prenom + "&mail=" + mail;
	request("POST", "reponse.php?methode=checksaisie", true, Setreponse_callback, arguments ); /*pas d'AJAX asynchrone, car manipulation sur champ*/
}

/* Test la connection en tant qu'admin*/
function Setreponse_callback(res)  {
	setData(res); 
}
