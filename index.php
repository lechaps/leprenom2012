<?php
require("./Include/function.php");
BDD_connexion();
GET_PARAM("TITLE_PAGE");
GET_PARAM("STYLE_JQUERY");
GET_PARAM("VERSION");
session_destroy();
LOG_ACTION("null", 1, "IP : ".$_SERVER["REMOTE_ADDR"]." Connexion");
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
<title><?php echo TITLE_PAGE?></title>
<link rel="stylesheet" href="Style/feuille.css"							type="text/css" media="all"/> 
<link rel="stylesheet" href="./Include/Tipsy/tipsy.css" 				type="text/css" />
<link rel="stylesheet" href="./Include/CLEeditor/jquery.cleditor.css" 	type="text/css" />
<link rel="stylesheet" href="./Include/JQueryUI/css/<?php echo STYLE_JQUERY?>" 	type="text/css" />
<link rel="stylesheet" href="./Include/TimePicker/jquery.ui.timepicker.css" 	type="text/css" />
<link rel="stylesheet" href="./Include/CompteARebour/horloge.css"			type="text/css" />
<script src="./Include/jquery-1.6.4.min.js"		type="text/javascript"></script>
<script src="./Include/Tipsy/jquery.tipsy.js"	type="text/javascript" ></script>
<script src="./Include/Smokescreen/smokescreen.js"					type="text/javascript"></script>
<script src="./Include/JQueryUI/jquery-ui-1.8.16.js"				type="text/javascript" ></script>
<script src="./Include/TimePicker/jquery.ui.timepicker.js"			type="text/javascript" ></script>
<script src="./Include/CLEeditor/jquery.cleditor.min.js"			type="text/javascript" ></script>
<script src="./Include/CompteARebour/jquery.lwtCountdown-1.0.js"	type="text/javascript" ></script>
<script src="./Include/jquery.ui.datepicker-fr.js"				type="text/javascript" ></script>
<script src="./Include/function.js"	type="text/javascript" ></script>
<script src="./Include/myajax.js"	type="text/javascript" ></script>
<script src="./presentation.js"		type="text/javascript" ></script>
<script src="./indice.js"			type="text/javascript" ></script>
<script src="./reponse.js"			type="text/javascript" ></script>
<script src="./admin.js"			type="text/javascript" ></script>

<script language="Javascript">
/*********************************************************************/
/* AJAX - Affichage des données*/
function setData(res)   {
	if (res=="WAIT")   {
		document.getElementById("DIV_Data").innerHTML='<img src="Image/Attente.gif">';
		return false
	}
	
	if (document.getElementById("DIV_Data"))   {
		document.getElementById("DIV_Data").innerHTML = res;
		$("#DIV_Data").fadeOut(0, function(){$(this).fadeIn(2000);}); /* Effet d'apparition*/
	}
	
	if (document.getElementById("mail_txt"))   {
		$('#mail_txt').smokescreen({smoke:'Pour jouer, saississez ici votre adresse mail...'});
	}

	if (document.getElementById("Prenom_Reponse"))
		document.getElementById("Prenom_Reponse").focus();
		
	if (document.getElementById("ACCESS_code"))	
		document.getElementById("ACCESS_code").focus();
}

/*********************************************************************/
/* Effet d'aide sur les liens*/
$(function() {
    $('#lien_presentation').tipsy({fade: true, gravity: 's'}); 
	$('#lien_indice').tipsy({fade: true, gravity: 's'}); 
	$('#lien_reponse').tipsy({fade: true, gravity: 's'}); 
	$('#lien_news').tipsy({fade: true, gravity: 's'}); 
});
</script>
</head>
<body>
<table align="center" class="TABLE_MAIN" border="0">
	<tr><td rowspan="3" colspan="2" class="Logo"><img src="Image/Logo.png"></td><td class="Logo_Interligne"></td></tr>
	<tr>
		<td class="Menu_Barre"><a id="lien_presentation" 	onclick="getPresentation();" 	title="<?php echo Expression("LIEN_title_presentation", "")?>"	><?php echo Expression("LIEN_presentation", "")?>	</a></td>
		<td class="Menu_Barre"><a id="lien_indice" 			onclick="getIndice();"			title="<?php echo Expression("LIEN_title_indice", "")?>"		><?php echo Expression("LIEN_indice", "")?>			</a></td>
		<td class="Menu_Barre"><a id="lien_reponse" 		onclick="getReponse();"			title="<?php echo Expression("LIEN_title_reponse", "")?>"		><?php echo Expression("LIEN_reponse", "")?>		</a></td>
		<td class="Menu_Barre"><a id="lien_news"			onclick="getAdministration();"	title="<?php echo Expression("LIEN_title_administration", "")?>"><?php echo Expression("LIEN_administration", "")?>	</a></td>
	</tr>
	<tr><td colspan="5" class="Menu_interligne"></td></tr>	
	<tr>
		<td class="CadreGaucheIE"></td><td class="CadreGauche"></td><td colspan="5" class="Cadredroite"><div id="DIV_Data"></div></td>
	</tr>
	<tr>
		<td colspan="6" class="filet"><div><?php echo Expression("SITE_commentaire", "") ?> - <?php echo VERSION?></div></td>
	</tr>
</table>
<script language="Javascript">
<?php
if (!isset($_GET["paypal"]))
	echo "getPresentation();";
else   {
	if (trim($_GET["paypal"])=="1")
		echo "getPaiementOK();";
	else
		echo "getPaiementKO();";
}
?>	
</script>
</body>
</html>
<?php
BDD_close()
?>