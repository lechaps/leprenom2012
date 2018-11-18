<?php
header("Content-Type:text/plain; charset=iso-8859-1");

require("./Include/function.php");
//require("./Include/function.php");
BDD_connexion();
GET_PARAM("REPONSE");
GET_PARAM("REPONSE_INTERVAL");
GET_PARAM("GAME_STATUS");

if (!isset($_GET["methode"]))
	die("ERROR INPUT");

if (isset($_COOKIE["mail"]) && trim($_COOKIE["mail"]!=""))
	$mail=trim($_COOKIE["mail"]);
else
	$mail="";

if (GAME_STATUS=="EN_COURS")
	$methode=trim($_GET["methode"]);
else
	$methode="game_ended";

/*fonction d'affichage du login */
function afficheSaisie($adressemail)   {
	echo '<div id="DIVshake" class="DIV_INCLUDE"><table class="TABLE_INCLUDE" align="center">';
	echo '	<thead>';
	echo '		<tr>';
	echo '			<td	colspan="2" align="center">'.Expression("REPONSE_saisie", "").'</td>';
	echo '		</tr>';
	echo '	</thead>';
	echo '	<tr>';
	echo '		<td align="right">Mail</td>';
	echo '		<td align="left"><input type="text" class="champ" id="Mail_Reponse" name="Mail_Reponse" value="'.$adressemail.'" maxlength="100" onkeypress="getReponseTouch(event);"></td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="right">Réponse(Prénom)</td>';
	echo '		<td align="left"><input type="text" class="champ_prenom" id="Prenom_Reponse" name="Prenom_Reponse" maxlength="100" onkeypress="getReponseTouch(event);"></td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="center" colspan="2"><input class="bouton" type="button" value="'.Expression("BOUTON_valider", "").'" onclick="getReponseCheck()"></td>';
	echo '	</tr>';
	echo '</table></div>';
}

/*fonction d'affichage d'un message de non saisie */
function afficheSaisieBlocage($message)   {
	echo '<div id="DIVshake" class="DIV_INCLUDE"><table align="center">';
	echo '	<tr>';
	echo '		<td align="right">'.$message.'</td>';
	echo '	</tr>';
	echo '</table></div>';
}


/*fonction de vérification du login saisie */
function checkSaisie($no_joueur) {
	$pass	= trim(mb_convert_encoding($_POST["prenom"], "auto", "UTF-8"));
	if (strtolower(Decrypte(REPONSE, CLE))==strtolower($pass))   {
		$requete= "INSERT INTO reponse(no_joueur, lbreponse, datereponse) VALUES (".$no_joueur.",'******', '".date("Y-m-d H:i:s")."')";
		BDD_EXECUTE($requete);
		$requete= "UPDATE parametre set lbparam='ANSWERED' where id_param='GAME_STATUS'";
		BDD_EXECUTE($requete);
		return true;
	}
	else   {
		$requete= "INSERT INTO reponse(no_joueur, lbreponse, datereponse) VALUES (".$no_joueur.",".FormatSQL_chaine($pass).", '".date("Y-m-d H:i:s")."')";
		BDD_EXECUTE($requete);
		return false;		
	}
}

function afficheBonneReponse()   {
	echo '<table align="center"><tr><td align="center">'.Expression("REPONSE_ok", "").'</td></tr></table>';
}

function afficheMessage($message)   {
	echo '<table align="center"><tr><td align="center">'.$message.'</td></tr></table>';
}

function checkIntervaleReponse($nojoueur, $nom, $datedebut)   {
	$datefin	= mktime(date("H"),date("i"), date("s"), date("n"), date("j"), date("Y") );
	$difference	= $datefin-$datedebut;
	if ($difference <= REPONSE_INTERVAL) {
		afficheSaisieBlocage(Expression("REPONSE_interval", $nom));
		return false;
	}else
		return true;
}

function afficheProposition()   {
	$requete="SELECT R.no_reponse, R.lbreponse, R.datereponse, R.no_joueur, J.prenom, J.nom, J.surnom
				FROM reponse R
				LEFT OUTER JOIN joueur J on R.no_joueur=J.no_joueur
				ORDER BY R.datereponse DESC";
	$recordset = BDD_EXECUTE($requete);
	echo '<br>';
	echo '<div class="DIV_INCLUDE">';
	echo '	<table class="TABLE_INCLUDE" align="center">';
	echo '		<thead>';
	echo '			<tr>';
	echo '				<td colspan="2">'.Expression("REPONSE_liste", "").'</td>';
	echo '			</tr>';
	echo '		</thead>';
	while ($record=BDD_RESULT2ARRAY($recordset))   {
		$reponse	= ucwords(strtolower(trim($record[1])));
		$datereponse= trim($record[2]);
		$surnom		= trim($record[6]);
		$prenom		= trim($record[4]);
		$statut		= trim($record[5]);
		if ($surnom=="")
			$appelation = $prenom;
		else
			$appelation = $surnom;
		echo '<tr>';
		echo '	<td><b>'.$reponse.'</b></td>';
		echo '	<td>le '.ArrangeDate($datereponse, "AAAA-MM-JJ HH:mm:ss", "", "JJ/MM/AAAA à HHhmm").' par '.$appelation.'</td>';
		echo '</tr>';
	}
	echo '	</table>';
	echo '</div>';
}

switch ($methode) {
	/******************************PAGE DE LOGIN**********************************/
    case "saisie":
		if ($mail!="")   {
			$requete = "SELECT M.no_joueur, M.id_mail, J.surnom, J.prenom, J.statut, 
					hour(datereponse), minute(datereponse), second(datereponse), month(datereponse), day(datereponse), year(datereponse)
					FROM mail M 
					LEFT OUTER JOIN joueur J on J.no_joueur=M.no_joueur 
					LEFT OUTER JOIN reponse R on R.no_joueur=M.no_joueur
					WHERE mail =".FormatSQL_chaine($mail);
			$recordset	= BDD_EXECUTE($requete);
			/*Si le joueur est connu de nos service*/
			if ($record	= BDD_RESULT2ARRAY($recordset)) {
				$nojoueur	= trim($record[0]);
				$surnom		= trim($record[2]);
				$prenom		= trim($record[3]);
				$statut		= trim($record[4]);
				if ($surnom=="")
					$appelation = $prenom;
				else
					$appelation = $surnom;
				$datereponse	= mktime($record[5], $record[6], $record[7], $record[8],  $record[9],  $record[10]);
				
				switch ($statut) {
					case "0":
					case "1" : 
						if (checkIntervaleReponse($nojoueur, $appelation ,$datereponse))  {
							afficheSaisie($mail);
						}
						break;						
					case "2" : 
						afficheSaisieBlocage(Expression("REPONSE_etat2", $appelation));
						break;
					case "3" : 
						afficheSaisieBlocage(Expression("REPONSE_etat3", $appelation));
						break;
				}
			}
			else
				afficheSaisie($mail);
		}
		else
			afficheSaisie($mail);
		afficheProposition();
		break;
    /******************************VERIF LOGIN**********************************/
	case "checksaisie":
		$mail	= trim(mb_convert_encoding($_POST["mail"], "auto", "UTF-8"));
		$requete = "SELECT M.no_joueur, M.id_mail, J.surnom, J.prenom, J.statut, 
					hour(datereponse), minute(datereponse), second(datereponse), month(datereponse), day(datereponse), year(datereponse)
					FROM mail M 
					LEFT OUTER JOIN joueur J on J.no_joueur=M.no_joueur 
					LEFT OUTER JOIN reponse R on R.no_joueur=M.no_joueur
					WHERE mail =".FormatSQL_chaine(trim(mb_convert_encoding($mail, "auto", 	"UTF-8")))." ORDER BY datereponse desc
					LIMIT 1";
		$recordset	= BDD_EXECUTE($requete);
		/*Si le joueur est connu de nos service*/
		if ($record		= BDD_RESULT2ARRAY($recordset)) {
			$nojoueur	= trim($record[0]);
			$surnom		= trim($record[2]);
			$prenom		= trim($record[3]);
			$statut		= trim($record[4]);
			if ($surnom=="")
				$appelation = $prenom;
			else
				$appelation = $surnom;
			$datereponse	= mktime($record[5], $record[6], $record[7], $record[8],  $record[9],  $record[10]);
			switch ($statut) {
				/*Le joueur est connu, il s'active*/
				case "0":
					$requete = "UPDATE joueur SET statut=1, datecreation='".date("d-m-Y H:i:s")."' WHERE no_joueur=".$nojoueur;
					BDD_EXECUTE($requete);
					if (checkIntervaleReponse($nojoueur, $appelation, $datereponse))   {
						if (!checkSaisie($nojoueur))
							afficheMessage(Expression("REPONSE_etat0_verif", $appelation).Expression("REPONSE_ko", $appelation));
						else
							afficheBonneReponse();
					}
					break;
				/*Le joueur est connu, il viens de s'activer, une nouvelle fois*/		
				case "1" : 
					if (checkIntervaleReponse($nojoueur, $appelation, $datereponse))   {
						if (!checkSaisie($nojoueur))
							afficheMessage(Expression("REPONSE_etat1_verif", $appelation)."<br>".Expression("REPONSE_ko", $appelation));
						else
							afficheBonneReponse();
						break;
					}
					break;
				/*Le joueur n'est pas connu, il s'active une nouvelle fois*/
				case "2" : 
					afficheMessage(Expression("MAIL_etat2", $appelation).Expression("REPONSE_wait", $appelation));
					break;
				/*Le joueur est interdit*/
				case "3" : 
					afficheMessage(Expression("MAIL_etat3", $appelation).Expression("REPONSE_wait", $appelation));
					break;
			}
		}
		else   {
			$requete = "INSERT into joueur (prenom, statut, datecreation) VALUES (".FormatSQL_chaine(trim(mb_convert_encoding($mail, "auto", "UTF-8"))).", 
						2, '".date("d-m-Y H:i:s")."')";
			BDD_EXECUTE($requete);
			$nojoueur=BDD_LAST_INSERT();
			$requete = "INSERT into mail (no_joueur, mail) VALUES(".$nojoueur.", ".FormatSQL_chaine(trim(mb_convert_encoding($mail, "auto", "UTF-8"))).")";
			BDD_EXECUTE($requete);
			afficheMessage(Expression("MAIL_etat2", $mail));
		}
		setcookie('mail' , $mail , COOKIE_EXPIRATION);
		break;
	/******************************PRENOM DEJA TROUVE**********************************/
	case "game_ended" : 
		afficheSaisieBlocage(Expression("GAME_ENDED", ""));
		afficheProposition();
		break;
    default:
        break;
}
BDD_close();
?>
