<?php
header("Content-Type:text/plain; charset=iso-8859-1");
require("./Include/function.php");
BDD_connexion();
GET_PARAM("ADMIN");
GET_PARAM("ADMIN_TENTATIVE");

if (!isset($_GET["methode"]))
	die("ERROR INPUT");

$methode=trim($_GET["methode"]);

/********* Test mire de connexion */
if ((!isset($_SESSION['admin'])) && ($methode!="ADMIN_login_check"))
	$methode="ADMIN_login";

/********* Affichage de la mire d'interdiction (en cas de dépassement du nombre de tentative) */
function afficheADMINInterdiction()   {
	echo '<div id="DIVshake" class="DIV_INCLUDE"><table align="center">';
	echo '	<tr>';
	echo '		<td align="center">Accès interdit définitivement, '.ADMIN_TENTATIVE.' tentatives, c\'est '.(ADMIN_TENTATIVE-1).' de trop!!!!</td>';
	echo '	</tr>';
	echo '</table></div>';
	LOG_ACTION("null", 3, "IP : ".$_SERVER["REMOTE_ADDR"]." Epuisement des tentatives à la partie administration");
}

/********* Affichage du login */
function afficheADMINLogin()   {
	echo '<div id="DIVshake" class="DIV_INCLUDE"><table align="center">';
	echo '	<tr>';
	echo '		<td align="center">'.Expression("ADMIN_accueil", "").'</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="center"><input type="password" class="champ" id="ACCESS_code" name="ACCESS_code" maxlength="20" onkeypress="setAdminTouch(event);">';
	echo '		<input class="bouton" type="button" value="'.Expression("BOUTON_valider", "").'" onclick="getAdminCheck()"></td>';
	echo '</table></div>';
}

/********* Vérification du mot de passe */
function checkADMINLogin() {
	$pass	= trim($_POST["ACCESS_code"]);
	if (!(Decrypte(ADMIN, CLE)==$pass))   {
		LOG_ACTION("null", 3, "IP : ".$_SERVER["REMOTE_ADDR"]." Tentative d'intrusion dans la partie administration");
		return false;
	}
	else
		$_SESSION['admin']=true;
	return true;
}

/********* Affichage du menu d'administration */
function afficheADMINMenu() {
	echo '<div id="DIVshake" class="DIV_INCLUDE"><table class="TABLE_INCLUDE" align="center">';
	echo '<thead><tr><td>Menu d\'administration	</td></tr></thead>';
	echo '	<tr><td><a onclick="getJoueurListe()" id="gestion_joueurs" title="Gestion des joueurs" 	>Gestion des joueurs</a></td></tr>';
	echo '	<tr><td><a onclick="getIndiceListe()"	>Gestion des indices</a></td></tr>';
	echo '	<tr><td><a onclick="getParametre()"		>Gestion des paramètres</a></td></tr>';	
	echo '	<tr><td><a onclick="getLOG()"		>Affichage des LOG</a></td></tr>';	
	echo '</table></div>';
}

/********* Affichage de la liste des joueurs */
function afficheJOUEURliste() {
	$requete="SELECT no_joueur, prenom, nom, surnom, statut FROM joueur ORDER BY prenom, nom";
	$recordset = BDD_EXECUTE($requete);
	echo '<div id="DIVshake" class="DIV_INCLUDE"><table class="TABLE_INCLUDE" align="center">';
	echo '<thead>';
	echo '<tr><td>N°</td><td>Prénom</td><td>Nom</td><td>Surnom</td><td>Statut</td></tr>';
	echo '</thead>';
	while ($record=BDD_RESULT2ARRAY($recordset))   {
		echo '<tr><td>'.trim($record[0]).'</td><td>'.trim($record[1]).'</td><td>'.trim($record[2]).'</td>';
		echo '<td>'.trim($record[3]).'</td><td>'.trim($record[4]).'</td>';
		echo '<td><a class="lien_interne" onclick="setJoueurEdite('.trim($record[0]).')"	>Editer</a></td>';
		echo '<td><a class="lien_interne" onclick="setJoueurSupprime('.trim($record[0]).')"	>Supprimer</a></td>';
		echo '</tr>';
	}
	echo '</table></div>';
}

/********* Suppression d'un joueur */
function supprimeJOUEUR() {
	$nojoueur=trim($_POST["nojoueur"]);
	$requete="DELETE FROM joueur WHERE no_joueur=".$nojoueur;
	BDD_EXECUTE($requete);
	$requete="DELETE FROM mail WHERE no_joueur=".$nojoueur;
	BDD_EXECUTE($requete);
}

/********* Affichage d'un joueur */
function afficheJOUEUR($nojoueur) {
	$requete="SELECT no_joueur, prenom, nom, surnom, statut FROM joueur WHERE no_joueur=".$nojoueur;
	$recordset = BDD_EXECUTE($requete);
	$recordPlayer=BDD_RESULT2ARRAY($recordset); 
	echo '<div id="DIVshake" class="DIV_INCLUDE"><table align="center">';
	echo '	<tr><td colspan="2"><a class="lien_interne" onclick="getJoueurListe()">Revenir à la gestion des joueurs</a></tr>';
	echo '	<tr><td>Prénom</td>	<td><input type="text" maxlength="50" size="50" name="Prenom"	id="Prenom" value="'.trim($recordPlayer[1]).'"></td></tr>';
	echo '	<tr><td>Nom</td>	<td><input type="text" maxlength="50" size="50" name="Nom"		id="Nom"	value="'.trim($recordPlayer[2]).'"></td></tr>';
	echo '	<tr><td>Surnom</td>	<td><input type="text" maxlength="50" size="50" name="Surnom"	id="Surnom"	value="'.trim($recordPlayer[3]).'"></td></tr>';
	echo '	<tr><td>Statut</td>	<td><select name="Statut" id="Statut">';
	checkJoueurStatutSelect($recordPlayer[4]);
	echo '	</select></td></tr>';
	$requete="SELECT id_mail, mail FROM mail WHERE no_joueur=".$nojoueur;
	$recordset = BDD_EXECUTE($requete);
	while ($recordMail=BDD_RESULT2ARRAY($recordset))   {
		echo '<tr><td>Mail</td><td>'.trim($recordMail[1]).' - <a class="lien_interne" onclick="setMailSupprime('.trim($recordMail[0]).', '.$nojoueur.' )">Supprimer</a></td>';
	}
	echo '	<tr><td>Ajouter ce Mail</td>';
	echo '		<td><input type="text" maxlength="50" size="50" name="mail" id="mail" value="">';
	echo '			<a class="lien_interne" onclick="setMailSauvegarde('.$nojoueur.')">Ajouter</a></td>';
	echo '	<tr><td colspan="2" align="center">';
	echo '		<input type="button" class="bouton" value="Valider" onclick="setJoueurSauvegarde('.trim($recordPlayer[0]).')"><td></tr>';
	echo '</table></div>';	
}

function checkJoueurStatutSelect($valeur) {
	$requete="SELECT replace(id_param, 'PLAYER_STATUT_', ''), lbparam FROM parametre WHERE id_param LIKE 'PLAYER_STATUT_%'";
	$recordset = BDD_EXECUTE($requete);
	while ($record=BDD_RESULT2ARRAY($recordset))   {
		echo '<option value="'.trim($record[0]).'" '.Check_Select($record[0], $valeur, "SELECTED").' >'.trim($record[1]).'</option>';
	}
}

/********* Sauvegarde d'un joueur */
function enregistreJOUEUR() {
	$nojoueur	=trim($_POST["nojoueur"]);
	$nom		=FormatSQL_chaine(trim(mb_convert_encoding($_POST["nom"], "auto", 	"UTF-8")));
	$prenom		=FormatSQL_chaine(trim(mb_convert_encoding($_POST["prenom"], "auto","UTF-8")));
	$surnom		=FormatSQL_chaine(trim(mb_convert_encoding($_POST["surnom"], "auto","UTF-8")));
	$statut		=FormatSQL_nombre(trim($_POST["statut"]));
	$requete="UPDATE joueur SET nom=".$nom.", prenom=".$prenom.", surnom=".$surnom.", statut=".$statut." WHERE no_joueur=".$nojoueur;
	BDD_EXECUTE($requete);
}

/********* Supprime un mail */
function supprimeMAIL($idmail) {
	$requete="DELETE FROM mail WHERE id_mail=".$idmail;
	BDD_EXECUTE($requete);
}

/********* Sauvegarde un mail */
function enregistreMAIL() {
	$nojoueur	=trim($_POST["nojoueur"]);
	$mail		=FormatSQL_chaine(trim(mb_convert_encoding($_POST["mail"], "auto", 	"UTF-8")));
	$requete="SELECT count(*) FROM mail WHERE mail=".$mail;
	$recordset = BDD_EXECUTE($requete);
	$recordPlayer=BDD_RESULT2ARRAY($recordset);
	if ($recordPlayer[0]==0)   {
		$requete="INSERT INTO mail (no_joueur, mail) VALUES (".$nojoueur.", ".$mail.")";
		BDD_EXECUTE($requete);
	}
	else
		echo "L'email ".$mail." existe déjà";
}


/********* fonction d'affichage de la liste des joueurs*/
function afficheINDICEliste() {
	$requete="SELECT no_indice, datedebut, datefin, libelle, commentaire FROM indice ORDER BY no_indice";
	$recordset = BDD_EXECUTE($requete);
	echo '<div id="DIVshake" class="DIV_INCLUDE"><table class="TABLE_INCLUDE" align="center">';
	echo '<thead>';
	echo '<tr><td>N°</td><td>DateDebut</td><td>Date Fin</td><td>Commentaire</td>';
	echo '<td><a class="lien_interne" onclick="setIndiceEdite(null)">Ajouter</a></td></tr>';
	echo '</thead>';
	while ($record=BDD_RESULT2ARRAY($recordset))   {
		echo '<tr><td>'.trim($record[0]).'</td><td>'.trim($record[1]).'</td><td>'.trim($record[2]).'</td>';
		echo '<td>'.trim($record[4]).'</td>';
		echo '<td><a class="lien_interne" onclick="setIndiceEdite('.trim($record[0]).')"	>Editer</a></td>';
		echo '<td><a class="lien_interne" onclick="setIndiceSupprime('.trim($record[0]).')"	>Supprimer</a></td>';
		echo '</tr>';
	}
	echo '</table></div>';
}

/********* Affichage d'un indice */
function afficheINDICE($noindice) {
	if ($noindice!="null")  {
		$requete		="SELECT no_indice, datedebut, datefin, libelle, commentaire FROM indice WHERE no_indice=".$noindice;
		$recordset 		=BDD_EXECUTE($requete);
		$recordIndice	=BDD_RESULT2ARRAY($recordset); 
		$datedebut	= ArrangeDate(trim($recordIndice[1]), "AAAA-MM-JJ HH:mm:ss", "", "JJ/MM/AAAA");
		$heuredebut	= ArrangeDate(trim($recordIndice[1]), "AAAA-MM-JJ HH:mm:ss", "", "HH:mm");
		$datefin	= ArrangeDate(trim($recordIndice[2]), "AAAA-MM-JJ HH:mm:ss", "", "JJ/MM/AAAA");
		$heurefin	= ArrangeDate(trim($recordIndice[2]), "AAAA-MM-JJ HH:mm:ss", "", "HH:mm");
		$libelle	= trim($recordIndice[3]);
		$commentaire= trim($recordIndice[4]);
	}
	else   {
		$datedebut	= date("d/m/Y");
		$heuredebut	= date("H:i");
		$datefin	= date("d/m/Y", mktime(date("H"), date("i"), date("s"), date("m"), date("d")+10,  date("Y")));
		$heurefin	= date("H:i");
		$libelle	= "";
		$commentaire= "";
	}
	echo '<div id="DIVshake" class="DIV_INCLUDE"><table align="center">';
	echo '	<tr><td colspan="2"><a onclick="getIndiceListe()">Revenir à la gestion des indices</a></tr>';
	echo '	<tr><td>Début</td><td><input type="text" maxlength="10" size="8" name="Datedebut" id="Datedebut" value="'.$datedebut.'">';
	echo '						<input type="text" maxlength="5" size="5" name="Heuredebut" id="Heuredebut" value="'.$heuredebut.'"></td></tr>';
	echo '	<tr><td>Fin</td><td><input type="text" maxlength="10" size="8" name="Datefin" id="Datefin"	value="'.$datefin.'">';
	echo '						<input type="text" maxlength="5" size="5" name="Heurefin" id="Heurefin" value="'.$heurefin.'"></td></tr>';
	echo '	<tr><td>Libelle</td><td><textarea cols="40" rows="10" name="Libelle" id="Libelle">'.$libelle.'</textarea></td></tr>';
	echo '	<tr><td>Commentaire</td><td><input type="text" maxlength="50" size="50" name="Commentaire" id="Commentaire" value="'.$commentaire.'"></td></tr>';
	echo '	<tr><td colspan="2" align="center">';
	echo '		<input type="button" class="bouton" value="Valider" onclick="setIndiceSauvegarde('.$noindice.')"><td></tr>';
	echo '</table></div>';
}

/********* Sauvegarde d'un indice */
function enregistreINDICE() {
	$noindice	=trim($_POST["noindice"]);
	$datedebut	=trim(mb_convert_encoding(trim($_POST["datedebut"]), "auto", "UTF-8"));
	$datefin	=trim(mb_convert_encoding(trim($_POST["datefin"]), "auto", "UTF-8"));
	$datedebut	=FormatSQL_chaine(ArrangeDate($datedebut, "JJ/MM/AAAA HH:mm", "", "AAAA-MM-JJ HH:mm:ss"));
	$datefin	=FormatSQL_chaine(ArrangeDate($datefin, "JJ/MM/AAAA HH:mm", "", "AAAA-MM-JJ HH:mm:ss"));
	$libelle	=FormatSQL_chaine(trim(mb_convert_encoding(trim($_POST["libelle"])	, "auto","UTF-8")));
	$commentaire=FormatSQL_chaine(trim(mb_convert_encoding(trim($_POST["commentaire"]), "auto","UTF-8")));
	if ($noindice=="null") 
		$requete="INSERT INTO indice(datedebut, datefin, libelle, commentaire) VALUES (".$datedebut.",".$datefin.",".$libelle.",".$commentaire.")";
	else
		$requete="UPDATE indice set datedebut=".$datedebut.", datefin=".$datefin.", libelle=".$libelle.", commentaire=".$commentaire."
					WHERE no_indice=".$noindice;
	BDD_EXECUTE($requete);
}

/********* Supprime un mail */
function supprimeINDICE($noindice) {
	$requete="DELETE FROM indice WHERE no_indice=".$noindice;
	BDD_EXECUTE($requete);
}

/********* Affichage des parametres */
/*fonction d'affichage de la liste des joueurs*/
function affichePARAMliste() {
	$requete="SELECT id_param, lbparam, descparam FROM parametre";
	$recordset = BDD_EXECUTE($requete);
	echo '<div id="DIVshake" class="DIV_INCLUDE"><table align="center">';
	while ($record=BDD_RESULT2ARRAY($recordset))   {
		$id	=trim($record[0]);
		$val=trim($record[1]);
		$cmt=trim($record[2]);
		if ($id=="ADMIN" || $id=="REPONSE")
			$val=Decrypte($val, CLE);
		echo '<tr><td><a id="A_'.$id.'"  title="'.$cmt.'">'.$id.'</a></td><td><input id="'.$id.'" type="text" size="50" value="'.$val.'"</td>';
		echo '</tr>';
	}
	echo '	<tr><td colspan="2" align="center">';
	echo '		<input type="button" class="bouton" value="Valider" onclick="setParametreSauvegarde()"><td></tr>';
	echo '</table></div>';
}

/********* Sauvegarde des paramètres */
function enregistrePARAM() {
	$TITLE_PAGE		=mb_convert_encoding(trim($_POST["TITLE_PAGE"])		, "auto", "UTF-8");
	$CHEMIN_STYLE	=mb_convert_encoding(trim($_POST["CHEMIN_STYLE"])	, "auto", "UTF-8");
	$STYLE_JQUERY	=mb_convert_encoding(trim($_POST["STYLE_JQUERY"])	, "auto", "UTF-8");
	$VERSION		=mb_convert_encoding(trim($_POST["VERSION"])	, "auto", "UTF-8");
	$ADMIN			=Crypte(mb_convert_encoding(trim($_POST["ADMIN"])	, "auto", "UTF-8"), CLE);
	$PLAYER_STATUT_0=mb_convert_encoding(trim($_POST["PLAYER_STATUT_0"])	, "auto", "UTF-8");
	$PLAYER_STATUT_1=mb_convert_encoding(trim($_POST["PLAYER_STATUT_1"])	, "auto", "UTF-8");
	$PLAYER_STATUT_2=mb_convert_encoding(trim($_POST["PLAYER_STATUT_2"])	, "auto", "UTF-8");
	$PLAYER_STATUT_3=mb_convert_encoding(trim($_POST["PLAYER_STATUT_3"])	, "auto", "UTF-8");
	$REPONSE		=Crypte(mb_convert_encoding(trim($_POST["REPONSE"])	, "auto", "UTF-8"), CLE);
	$ADMIN_TENTATIVE=mb_convert_encoding(trim($_POST["ADMIN_TENTATIVE"])	, "auto", "UTF-8");
	$REPONSE_INTERVAL=mb_convert_encoding(trim($_POST["REPONSE_INTERVAL"])	, "auto", "UTF-8");
	$GAME_STATUS	=mb_convert_encoding(trim($_POST["GAME_STATUS"])	, "auto", "UTF-8");
	$requete	="UPDATE parametre set lbparam='".$TITLE_PAGE."' 		where id_param='TITLE_PAGE'";
	BDD_EXECUTE($requete);
	$requete	="UPDATE parametre set lbparam='".$CHEMIN_STYLE."' 		where id_param='CHEMIN_STYLE'";
	BDD_EXECUTE($requete);
	$requete	="UPDATE parametre set lbparam='".$STYLE_JQUERY."'		where id_param='STYLE_JQUERY'";
	BDD_EXECUTE($requete);
	$requete	="UPDATE parametre set lbparam='".$VERSION."'			where id_param='VERSION'";
	BDD_EXECUTE($requete);
	$requete	="UPDATE parametre set lbparam='".$ADMIN."'				where id_param='ADMIN'";
	BDD_EXECUTE($requete);
	$requete	="UPDATE parametre set lbparam='".$PLAYER_STATUT_0."'	where id_param='PLAYER_STATUT_0'";
	BDD_EXECUTE($requete);
	$requete	="UPDATE parametre set lbparam='".$PLAYER_STATUT_1."'	where id_param='PLAYER_STATUT_1'";
	BDD_EXECUTE($requete);
	$requete	="UPDATE parametre set lbparam='".$PLAYER_STATUT_2."'	where id_param='PLAYER_STATUT_2'";
	BDD_EXECUTE($requete);
	$requete	="UPDATE parametre set lbparam='".$PLAYER_STATUT_3."'	where id_param='PLAYER_STATUT_3'";
	BDD_EXECUTE($requete);
	$requete	="UPDATE parametre set lbparam='".$REPONSE."'			where id_param='REPONSE'";
	BDD_EXECUTE($requete);
	$requete	="UPDATE parametre set lbparam='".$ADMIN_TENTATIVE."'	where id_param='ADMIN_TENTATIVE'";
	BDD_EXECUTE($requete);
	$requete	="UPDATE parametre set lbparam='".$REPONSE_INTERVAL."'	where id_param='REPONSE_INTERVAL'";
	BDD_EXECUTE($requete);
	$requete	="UPDATE parametre set lbparam='".$GAME_STATUS."'	where id_param='GAME_STATUS'";
	BDD_EXECUTE($requete);
}

/********* Affichage de la liste des logs */
function afficheLOGliste() {
	$requete="SELECT no_action, no_joueur, priorite, action, dateaction FROM log ORDER BY dateaction desc";
	$recordset = BDD_EXECUTE($requete);
	echo '<div id="DIVshake" class="DIV_INCLUDE"><table class="TABLE_INCLUDE" align="center">';
	echo '<thead>';
	echo '<tr><td>N°</td><td>Joueur</td><td>Priorité</td><td>Action</td><td>Date d\'action</td>';
	echo '<td><a class="lien_interne" onclick="setPurgeLOG()">Purge</a></td></tr>';
	echo '</thead>';
	while ($record=BDD_RESULT2ARRAY($recordset))   {
		echo '<tr><td>'.trim($record[0]).'</td><td>'.trim($record[1]).'</td><td>'.trim($record[2]).'</td>';
		echo '<td>'.trim($record[3]).'</td><td>'.trim($record[4]).'</td>';
		echo '<td><a class="lien_interne" onclick="setLOGSupprime('.trim($record[0]).')"	>Supprime</a></td>';
		echo '</tr>';
	}
	echo '</table></div>';
}

/********* Supprime un LOG */
function supprimeLOG($no) {
	$requete="DELETE FROM log WHERE no_action=".$no;
	BDD_EXECUTE($requete);
}

/********* Purge les LOG (priorité inférieur à 3) */
function purgeLOG() {
	$requete="DELETE FROM log WHERE priorite<3";
	BDD_EXECUTE($requete);
}

/****************************************************************************************************************************************/
/****************************************************************************************************************************************/
/************************************** A I G U I L L A G E *****************************************************************************/
/****************************************************************************************************************************************/
/****************************************************************************************************************************************/


switch ($methode) {
	/******************************PAGE DE LOGIN**********************************/
    case "ADMIN_login":
		if (isset($_SESSION['admin_tentative']))   {
			$_SESSION['admin_tentative']+=1;
			if ($_SESSION['admin_tentative']>=ADMIN_TENTATIVE)   {
				afficheADMINInterdiction();
			}
			else
				afficheADMINLogin();
		}
		else   {
			afficheADMINLogin();}
		break;
    /******************************VERIF LOGIN**********************************/
	case "ADMIN_login_check":
		if (checkADMINLogin())
			afficheADMINMenu();
		else
			if (isset($_SESSION['admin_tentative']))   {
				$_SESSION['admin_tentative']+=1;
				if ($_SESSION['admin_tentative']>=ADMIN_TENTATIVE)   {
					afficheADMINInterdiction();
				}
				else
					echo "ACCES_KO";
			}
			else   {
				$_SESSION['admin_tentative']=1;
				echo "ACCES_KO";
			}
		break;

	/**************************MENU ADMINISTRATION*******************************/
	case "ADMIN_menu":
		afficheADMINMenu();
		break;
	/**************************GESTION DES JOUEURS*******************************/
	case "JOUEUR_liste":
		afficheJOUEURliste();
		break;
	/**************************SUPPRESSION D'UN JOUEUR*******************************/
	case "JOUEUR_supprime":
		supprimeJOUEUR();
		afficheJOUEURliste();
		break;
	/**************************FICHE JOUEURS*******************************/
	case "JOUEUR_edite":
		$nojoueur=trim($_POST["nojoueur"]);
		afficheJOUEUR($nojoueur);
		break;
	/**************************SAUVEGARDE D'UN JOUEUR*******************************/
	case "JOUEUR_sauvegarde":
		enregistreJOUEUR();
		afficheJOUEURliste();
		break;
	/**************************SUPPRESSION D'UN MAIL*******************************/
	case "MAIL_supprime":
		$nojoueur	= trim($_POST["nojoueur"]);
		$idmail		= trim($_POST["idmail"]);
		supprimeMAIL($idmail);
		afficheJOUEUR($nojoueur);
		break;
	/**************************AJOUTE UN MAIL*******************************/
	case "MAIL_sauvegarde":
		enregistreMAIL();
		$nojoueur=trim($_POST["nojoueur"]);
		afficheJOUEUR($nojoueur);
		break;
	/**************************GESTION DES INDICES*******************************/
	case "INDICE_liste":
		afficheINDICEliste();
		break;
	/**************************FICHE INDICE*******************************/
	case "INDICE_edite":
		$noindice=trim($_POST["nojoueur"]);
		afficheINDICE($noindice);
		break;
	/**************************SAUVEGARDE D'UN INDICE*******************************/
	case "INDICE_sauvegarde":
		enregistreINDICE();
		afficheINDICEliste();
		break;
	/**************************SUPPRESSION D'UN INDICE*******************************/
	case "INDICE_supprime":
		$noindice	= trim($_POST["noindice"]);
		supprimeINDICE($noindice);
		afficheINDICEliste();
		break;
	/**************************GESTION DES PARAMETRES*******************************/
	case "PARAM_liste":
		affichePARAMliste();
		break;
	/**************************SAUVEGARDE DES PARAMETRES*******************************/
	case "PARAM_sauvegarde":
		enregistrePARAM();
		afficheADMINMenu();
		break;
	/**************************GESTION DES LOG*******************************/	
	case "LOG_liste":
		afficheLOGliste();
		break;
	/**************************SUPPRESSION D'UN LOG*******************************/
	case "LOG_supprime":
		$nolog	= trim($_POST["nolog"]);
		supprimeLOG($nolog);
		afficheLOGliste();
		break;
	/**************************PURGE DES LOGS*******************************/
	case "LOG_purge":
		purgeLOG();
		afficheLOGliste();
		break;
    default:
		echo "WARNING!!! MEN,  YOU WANT TO DO SOMETHING WRONG, I DON'T UNDERSTAND : ".$methode;
        break;
}
BDD_close();
?>
