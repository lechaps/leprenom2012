<?php
require('./Include/Expression.php');
define("CLE","Motdepasse" );
define("COOKIE_EXPIRATION", time()+60*60*24*365); /* duré d'expiration du cookie : => 365 jours*/
session_start();
/****************************************************************************************/
/*Connexion BDD*/
function BDD_connexion() {
	require('BDD_connexion.php');
	$connexion = mysql_connect($host_connexion, $login_connexion, $pass_connexion) or die("<br><h1>Impossible de se connecter au serveur My_SQL</h1>".mysql_error());
	mysql_select_db($Base_connexion) or die("<br><h1>Impossible de se connecter à la base de données</h1>".mysql_error());
	return $connexion;
}

/****************************************************************************************/
/*Exécution requête*/
function BDD_EXECUTE($SQL) {
	$result = mysql_query($SQL) or die ("REQUETE : ".$SQL." ERREUR : ".mysql_error());
	return $result;
}

/****************************************************************************************/
/*Recupére le dernier ID d'une table*/
function BDD_LAST_INSERT() {
	//return mysql_insert_id();
	$RS		= BDD_EXECUTE("SELECT LAST_INSERT_ID()");
	$NumEnreg=BDD_RESULT2ARRAY($RS);
	return $NumEnreg[0];
}
/****************************************************************************************/
/*Se déplace dans un recorset*/
/* $start=-1  ==> se place à l'enregistrement suivant, sinon se place à $start*/
function BDD_MOVE($RS, $start=1)   {
	if (mysql_num_rows($RS)!=0)
		return mysql_data_seek($RS,$start);
	else
		return false;
}

/****************************************************************************************/
/*Transforme un recodet en tableau*/
function BDD_RESULT2ARRAY($RS)   {
	$result = mysql_fetch_array($RS);
	return $result;
}

/****************************************************************************************/
/*Nb de champ d'un recordset*/
function BDD_NBCHAMP($RS)   {
	return mysql_num_fields($RS);
}

/****************************************************************************************/
/*Libellé champ d'un recordset*/
function BDD_LBCHAMP($RS, $index)   {
	return mysql_field_name($RS, $index);
}

/****************************************************************************************/
/*Nb de ligne d'un recordset*/
function BDD_NBLIGNE($RS)   {
	return mysql_num_rows($RS);
}

/****************************************************************************************/
/*Fermeture BDD*/
function BDD_CLOSE()   {
	mysql_close();
}

/****************************************************************************************/
/*Test d'égalité*/
function Check_Select($Argument, $valeur, $retour)   {
	if ($Argument == $valeur)
		return $retour;
}

/****************************************************************************************/
/*test de Chaine  : Renvoi "null" si chaine vide*/
function FormatSQL_chaine($chaine)   {
	if (get_magic_quotes_gpc())
		$chaine = stripslashes(trim($chaine));
	$chaine = mysql_real_escape_string($chaine);
	if ($chaine == "")
		return "null";
	else
		return "'".$chaine."'";
}

/****************************************************************************************/
/*test de date  : Renvoi 0 si chaine vide*/
function FormatSQL_dateheure($date, $heure)   {
	$date = str_replace( "'", "''", trim($date));
	$heure = str_replace( "'", "''", trim($heure));
	if ($date == "" || $heure == "")
		return "null";
	return "'".ArrangeDate($date, "JJ/MM/AAAA", "", "AAAA-MM-JJ")." ".$heure."'";
 
}

/****************************************************************************************/
/*test de Chaine  : Renvoi 0 si chaine vide*/
function FormatSQL_nombre($nombre)   {
	if ($nombre == "" || !is_numeric($nombre))
		return "null";
	else
		return doubleval($nombre);
}

/****************************************************************************************/
/*test de Chaine  : Renvoi 1 si varaible connue*/
function FormatSQL_bit($bit)   {
	if (isset($bit))
		return 1;
	else
		return 0;
}

/****************************************************************************************/
/* traite une date en fonction d'un format et d'une de fin en modifiant la séparation*/
function ArrangeDate($input, $formatinput, $end, $formatouput)   {
	if (str_replace(" ", "" ,$input)=="")
		return "";
	if ($end!="")   {
		$posEnd		= strpos($input, $end);
		$int_date 	= substr($input, 0, $posEnd);
	}
	else {
		$int_date	= $input;
	}

	switch ($formatinput)
	{
		case "AAAAMMJJ":
			$year	= substr($int_date, 0, 4);
			$month	= substr($int_date, 4, 2);
			$day	= substr($int_date, 6, 2);
			break;
		case "AAAA-MM-JJ":
			list ($year, $month, $day) = explode ('-', $int_date);
			break;
		case "JJ/MM/AAAA":
			list ($day, $month, $year) = explode ('/', $int_date);
			break;
		case "MM/JJ/AAAA":
			list ($month, $day, $year) = explode ('/', $int_date);
			break;
		case "AAAAMMJJHHmmss":	
			$year	= substr($int_date, 0, 4);
			$month	= substr($int_date, 4, 2);
			$day	= substr($int_date, 6, 2);
			$hour	= substr($int_date, 8, 2);
			$minute	= substr($int_date, 10, 2);
			$second	= substr($int_date, 12, 2);
			break;
		case "AAAA-MM-JJ HH:mm:ss":
			list($date, $time)			= explode(" ", $int_date);			
			list($year, $month, $day) 	= explode ("-", $date);
			list($hour, $minute, $second)= explode (":", $time);	
			break;
		case "AAAA-MM-JJ HH:mm":
			list($date, $time)			= explode(" ", $int_date);			
			list($year, $month, $day) 	= explode ("-", $date);
			list($hour, $minute)		= explode (":", $time);	
			$second = "00";
			break;
		case "JJ/MM/AAAA HH:mm":
			list($date, $time)			= explode(" ", $int_date);			
			list($day, $month, $year ) 	= explode ("/", $date);
			list($hour, $minute)		= explode (":", $time);	
			$second = "00";
			break;
		default:
			$output = "le format Entrée n'est pas pris en charge par le système";
			break;
	}
	
	switch ($formatouput) {
		case "JJ/MM/AAAA":
			$output	= $day."/".$month."/".$year;
			break;
		case "AAAAMMJJ":
			$output	= $year.$month.$day;
			break;
		case "MM/JJ/AAAA":
			$output	= $month."/".$day."/".$year;
			break;
		case "AAAA-MM-JJ":
			$output	= $year."-".$month."-".$day;
			break;
		case "JJ/MM/AAAA HH:mm":
			$output	= $day."/".$month."/".$year." ".$hour.":".$minute;
			break;
		case "JJ/MM/AAAA à HHhmm":
			$output	= $day."/".$month."/".$year." à ".$hour."h".$minute;
			break;
		case "JJ/MM/AAAA HH:mm:ss":
			$output	= $day."/".$month."/".$year." ".$hour.":".$minute.":".$second;
			break;
		case "AAAA-MM-JJ HH:mm:ss":
			$output	= $year."-".$month."-".$day." ".$hour.":".$minute.":".$second;
			break;
		case "AAAA-MM-JJ HH:mm":
			$output	= $year."-".$month."-".$day." ".$hour.":".$minute;
			break;
		case "HH:mm:ss":
			$output	= $hour.":".$minute.":".$second;
			break;
		case "HH:mm":
			$output	= $hour.":".$minute;
			break;
		default:
			$output ="le format Sortie n'est pas pris en charge par le système";
			break;
	}
	return $output;
}

/****************************************************************************/
 function minute_vers_heure($minute, $format){
     return sprintf($format, floor($minute/60), $minute%60);
  }

/****************************************************************************************/
/* retourne les paramètres non clé*/
function GET_PARAM($idparam)   {
	$SQL="SELECT lbparam, descparam FROM parametre WHERE id_param='".$idparam."'";
	$recordset	= BDD_EXECUTE($SQL);
	$result		= BDD_RESULT2ARRAY($recordset);
	define($idparam,$result[0] );
}

/****************************************************************************************/
/* retourne les paramètres non clé*/
function GETLB_PARAM($idparam)   {
	$SQL="SELECT lbparam FROM parametre WHERE id_param='".$idparam."'";
	$recordset	= BDD_EXECUTE($SQL);
	$result		= BDD_RESULT2ARRAY($recordset);
	define($idparam,$result[0] );
}

/****************************************************************************************/
/* génération de clé de cryptage*/
function GenerationCle($Texte,$CleDEncryptage)    {
	$CleDEncryptage = md5($CleDEncryptage);
	$Compteur=0;
	$VariableTemp = "";
	for ($Ctr=0;$Ctr<strlen($Texte);$Ctr++)   {
		if ($Compteur==strlen($CleDEncryptage))
			$Compteur=0;
		$VariableTemp.= substr($Texte,$Ctr,1) ^ substr($CleDEncryptage,$Compteur,1);
		$Compteur++;
    }
	return $VariableTemp;
}

/****************************************************************************************/
/* Cryptage*/
function Crypte($Texte,$Cle)   {
	srand((double)microtime()*1000000);
	$CleDEncryptage = md5(rand(0,32000) );
	$Compteur=0;
	$VariableTemp = "";
	for ($Ctr=0;$Ctr<strlen($Texte);$Ctr++)   {
		if ($Compteur==strlen($CleDEncryptage))
			$Compteur=0;
		$VariableTemp.= substr($CleDEncryptage,$Compteur,1).(substr($Texte,$Ctr,1) ^ substr($CleDEncryptage,$Compteur,1) );
		$Compteur++;
    }
	return base64_encode(GenerationCle($VariableTemp,$Cle) );
}

/****************************************************************************************/
/* Décryptage - non utilisé*/
function Decrypte($Texte,$Cle)   {
	$Texte = GenerationCle(base64_decode($Texte),$Cle);
	$VariableTemp = "";
	for ($Ctr=0;$Ctr<strlen($Texte);$Ctr++)   {
		$md5 = substr($Texte,$Ctr,1);
		$Ctr++;
		$VariableTemp.= (substr($Texte,$Ctr,1) ^ $md5);
    }
	return $VariableTemp;
} 

/****************************************************************************************/
/* enregistre un log*/
function LOG_ACTION($user, $priorite, $commentaire)   {
	$user		= FormatSQL_nombre($user);
	$priorite	= FormatSQL_nombre($priorite);
	$commentaire= FormatSQL_chaine($commentaire);
	$dateaction	= "'".date("Y-m-d H:i:s")."'";
	$SQL="INSERT INTO log (no_joueur, priorite, action, dateaction) values ($user, $priorite, $commentaire, $dateaction )";
	BDD_EXECUTE($SQL);	
}

/****************************************************************************************/
/* retourne une phrase choisi dans le dictionnaire*/
function Expression($type, $nom)   {
	global $texte;
	$nbElement	= count($texte[$type]);
	$tirage 	= rand(1, $nbElement);
	return str_replace("#NOM", $nom, $texte[$type][$tirage-1]);
}
?>