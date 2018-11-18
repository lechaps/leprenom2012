/* Affichage de la page du menu Administration */
function getAdministration()   {
	request("GET", "admin.php?methode=ADMIN_menu" , true, setData);
	
}

/* Gestion du clavier de la mire de connexion (détection de la touche ENTER) */
function setAdminTouch(event)   {
	if (Enter_detector(event))
		getAdminCheck();
}

/* Fonction de validation du login de la partie administration saisi */
function getAdminCheck()   {
	pass=document.getElementById("ACCESS_code").value
	var arguments = "ACCESS_code=" + pass;
	request("POST", "admin.php?methode=ADMIN_login_check", false, setAdmin_callback, arguments ); /*pas d'AJAX asynchrone, car manipulation sur champ*/
}

/* Test la connection en tant qu'admin*/
function setAdmin_callback(res)  {
	if (res=='ACCES_KO')   {
		$('#DIVshake').effect('pulsate', { times:3 }, 500);
		document.getElementById("ACCESS_code").value="";
		document.getElementById("ACCESS_code").focus();
	}
	else
		setData(res); 
}

/*Fonction d'obtention de la liste des joueurs*/
function getJoueurListe()   {
	request("GET", "admin.php?methode=JOUEUR_liste" , true, setData);
}

/* Suppression d'un joueur */
function setJoueurSupprime(Nojoueur)   {
	if (confirm('Suppression du joueur n°'+Nojoueur))   {
		var arguments = "nojoueur=" + Nojoueur;
		request("POST", "admin.php?methode=JOUEUR_supprime", true, setData, arguments );
	}
}

/*Affiche la fiche d'un joueur*/
function setJoueurEdite(Nojoueur)   {
	var arguments = "nojoueur=" + Nojoueur;
	request("POST", "admin.php?methode=JOUEUR_edite", true, setData, arguments );
}

/*Sauvegarde la fiche d'un joueur */
function setJoueurSauvegarde(Nojoueur)   {
	prenom	= document.getElementById("Prenom").value;
	nom		= document.getElementById("Nom").value;
	surnom	= document.getElementById("Surnom").value;
	statut	= document.getElementById("Statut").value;
	var arguments = "nojoueur=" + Nojoueur + "&prenom=" + prenom + "&nom=" + nom + "&surnom=" + surnom + "&statut=" + statut;
	request("POST", "admin.php?methode=JOUEUR_sauvegarde", true, setData, arguments );
}

/*Suppresion d'un mail*/
function setMailSupprime(Idmail, Nojoueur)   {
	if (confirm('Suppression du mail n°'+Idmail))   {
		var arguments = "nojoueur=" + Nojoueur + "&idmail=" + Idmail;
		request("POST", "admin.php?methode=MAIL_supprime", true, setData, arguments );
	}
}

/*Ajoute mail */
function setMailSauvegarde(Nojoueur)   {
	mail	= document.getElementById("mail").value;
	if ((replaceCharacters(mail,' ','')=='') || (!checkEmail(mail)))   {
		alert("Saississez un mail valide !!");
		document.getElementById("mail").focus();
		return false;
	}
	var arguments = "nojoueur=" + Nojoueur + "&mail=" + mail;
	request("POST", "admin.php?methode=MAIL_sauvegarde", true, setData, arguments );
}

/*Fonction d'obtention de la liste des joueurs*/
function getIndiceListe()   {
	request("GET", "admin.php?methode=INDICE_liste" , true, setData);
}

/*Affiche la fiche d'un indice*/
function setIndiceEdite(Nojoueur)   {
	var arguments = "nojoueur=" + Nojoueur;
	request("POST", "admin.php?methode=INDICE_edite", false, setData, arguments ); /*pas d'AJAX asynchrone, car manipulation sur champ*/
	/*champ date*/
	$(function() {
		$( "#Datedebut" ).datepicker({ numberOfMonths: 3, showButtonPanel: true});
		$( "#Datefin" ).datepicker({ numberOfMonths: 3, showButtonPanel: true});
	});
	/*champ heure*/
	$('#Heuredebut').timepicker({hourText: 'Heures', minuteText: 'Minutes', amPmText: ['Matin', 'Aprem'], timeSeparator: ':'});
	$('#Heurefin').timepicker({hourText: 'Heures', minuteText: 'Minutes', amPmText: ['Matin', 'Aprem'], timeSeparator: ':'});
	/*champ commentaire avec editeur HTML*/
	$(document).ready(function() {
        $("#Libelle").cleditor()[0].focus();
	});
}

/*Sauvegarde la fiche d'un indice */
function setIndiceSauvegarde(Noindice)   {
	datedebut	= document.getElementById("Datedebut").value+' '+document.getElementById("Heuredebut").value;
	datefin		= document.getElementById("Datefin").value+' '+document.getElementById("Heurefin").value;
	libelle		= document.getElementById("Libelle").value;
	commentaire	= document.getElementById("Commentaire").value;
	var arguments = "noindice=" + Noindice + "&datedebut=" + datedebut + "&datefin=" + datefin + "&libelle=" + libelle + "&commentaire=" + commentaire;
	request("POST", "admin.php?methode=INDICE_sauvegarde", true, setData, arguments );
}

/*Suppresion d'un indice*/
function setIndiceSupprime(Noindice)   {
	if (confirm("Suppression de l'indice n°"+Noindice))   {
		var arguments = "noindice=" + Noindice;
		request("POST", "admin.php?methode=INDICE_supprime", true, setData, arguments );
	}
}

/* Affichage des paramètres */
function getParametre()   {
	request("GET", "admin.php?methode=PARAM_liste" , false, setData);
	$(function() {
		$('#A_TITLE_PAGE').tipsy({fade: true, gravity: 's'}); 
		$('#A_CHEMIN_STYLE').tipsy({fade: true, gravity: 's'}); 
		$('#1_STYLE_JQUERY').tipsy({fade: true, gravity: 's'}); 
		$('#A_VERSION').tipsy({fade: true, gravity: 's'}); 
		$('#A_ADMIN').tipsy({fade: true, gravity: 's'}); 
		$('#A_PLAYER_STATUT_0').tipsy({fade: true, gravity: 's'}); 
		$('#A_PLAYER_STATUT_1').tipsy({fade: true, gravity: 's'}); 
		$('#A_PLAYER_STATUT_2').tipsy({fade: true, gravity: 's'}); 
		$('#A_PLAYER_STATUT_3').tipsy({fade: true, gravity: 's'}); 
		$('#A_REPONSE').tipsy({fade: true, gravity: 's'}); 
		$('#A_ADMIN_TENTATIVE').tipsy({fade: true, gravity: 's'}); 
		$('#A_REPONSE_INTERVAL').tipsy({fade: true, gravity: 's'}); 
		$('#GAME_STATUS').tipsy({fade: true, gravity: 's'}); 
	});
}

/*Sauvegarde les paramètres*/
function setParametreSauvegarde(Noindice)   {
	TITLE_PAGE		=document.getElementById("TITLE_PAGE").value;
	CHEMIN_STYLE	=document.getElementById("CHEMIN_STYLE").value;
	STYLE_JQUERY	=document.getElementById("STYLE_JQUERY").value;
	VERSION			=document.getElementById("VERSION").value;
	ADMIN			=document.getElementById("ADMIN").value;
	PLAYER_STATUT_0	=document.getElementById("PLAYER_STATUT_0").value;
	PLAYER_STATUT_1	=document.getElementById("PLAYER_STATUT_1").value;
	PLAYER_STATUT_2	=document.getElementById("PLAYER_STATUT_2").value;
	PLAYER_STATUT_3	=document.getElementById("PLAYER_STATUT_3").value;
	REPONSE			=document.getElementById("REPONSE").value;
	ADMIN_TENTATIVE	=document.getElementById("ADMIN_TENTATIVE").value;
	REPONSE_INTERVAL=document.getElementById("REPONSE_INTERVAL").value;
	GAME_STATUS		=document.getElementById("GAME_STATUS").value;
	var arguments = "TITLE_PAGE=" + TITLE_PAGE + "&CHEMIN_STYLE=" + CHEMIN_STYLE + "&STYLE_JQUERY=" + STYLE_JQUERY +  "&VERSION=" + VERSION;
	arguments+="&ADMIN=" + ADMIN + "&PLAYER_STATUT_0=" + PLAYER_STATUT_0 + "&PLAYER_STATUT_1=" + PLAYER_STATUT_1 +  "&REPONSE=" + REPONSE;
	arguments+="&PLAYER_STATUT_2=" + PLAYER_STATUT_2 + "&PLAYER_STATUT_3=" + PLAYER_STATUT_3 + "&ADMIN_TENTATIVE=" + ADMIN_TENTATIVE;
	arguments+="&REPONSE_INTERVAL=" +REPONSE_INTERVAL+ "&GAME_STATUS=" + GAME_STATUS;
	request("POST", "admin.php?methode=PARAM_sauvegarde", true, setData, arguments );
}

/* Affichage des paramètres */
function getLOG()   {
	request("GET", "admin.php?methode=LOG_liste" , true, setData);
}

/*Suppresion d'un log*/
function setLOGSupprime(NoLOG)   {
	if (confirm("Suppression du LOG n°"+NoLOG))   {
		var arguments = "nolog=" + NoLOG;
		request("POST", "admin.php?methode=LOG_supprime", true, setData, arguments );
	}
}

/*Purge des logs*/
function setPurgeLOG()   {
	if (confirm("Purge des LOG (hors ceux de priorités 3)"))   {
		request("GET", "admin.php?methode=LOG_purge" , true, setData);
	}
}