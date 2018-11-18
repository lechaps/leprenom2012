<?php
header("Content-Type:text/plain; charset=iso-8859-1");
require("./Include/function.php");
BDD_connexion();
function afficheSaisieMail()  {
	echo '<div id="DIVshake" class="DIV_INCLUDE"><table align="center">';
	echo '<tr><td align="center">'.(Expression("INSCRIPTION", "")).'</td></tr>';
	echo '<tr><td align="center"><input type="text"	class="champ" id="mail_txt" name="mail_txt" maxlength="100" value="" onkeypress="getMailTouch(event);">';
	echo '						<input type="button" class="bouton" value="'.(Expression("BOUTON_valider", "")).'" onclick="ValidMail()"></td></tr></table></div>';
}

function afficheMail($mail)  {
	echo '<table class="DIV_INCLUDE" align="center" id="table_MAIL">';
	echo '<tr><td align="center">'.$mail.'</td></tr>';
	echo '<tr><td align="center"><a onclick="setMailRevoke()">'.(Expression("MAIL_revocation", "")).'</a></td></tr>';
	echo '</td></tr></table>';
}

/************E N R E G I S T R E M E N T   D U   M A I L*****************/
if (isset($_POST["mail"])) {
	$mail	= trim($_POST["mail"]);
	$requete = "SELECT M.no_joueur, M.id_mail, J.surnom, J.prenom, J.statut  FROM mail M LEFT OUTER JOIN joueur J on J.no_joueur=M.no_joueur 
				WHERE mail =".FormatSQL_chaine(trim(mb_convert_encoding($mail, "auto", 	"UTF-8")));
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
		LOG_ACTION($nojoueur, 1, "IP : ".$_SERVER["REMOTE_ADDR"]." joueur n°".$nojoueur." (".$appelation.")");
		
		switch ($statut) {
			/*Le joueur est connu, il s'active*/
			case "0":
				$requete = "UPDATE joueur SET statut=1, datecreation='".date("d-m-Y H:i:s")."' WHERE no_joueur=".$nojoueur;
				BDD_EXECUTE($requete);
				$message = Expression("MAIL_etat0", $appelation);
				break;
			/*Le joueur est connu, il viens de s'activer, une nouvelle fois*/		
			case "1" : 
				$message = Expression("MAIL_etat1", $appelation);
				break;
			/*Le joueur n'est pas connu, il s'active une nouvelle fois*/
			case "2" : 
				$message = Expression("MAIL_etat2_repeat", $appelation);
				break;
			/*Le joueur est interdit*/
			case "3" : 
				$message = Expression("MAIL_etat3", $appelation);
				break;
		}
	}
	else   {
		$requete = "INSERT into joueur (prenom, statut, datecreation) VALUES (".FormatSQL_chaine(trim(mb_convert_encoding($mail, "auto", "UTF-8"))).", 
					2, '".date("Y-m-d H:i:s")."')";
		BDD_EXECUTE($requete);
		$nojoueur=BDD_LAST_INSERT();
		$requete = "INSERT into mail (no_joueur, mail) VALUES(".$nojoueur.", ".FormatSQL_chaine(trim(mb_convert_encoding($mail, "auto", "UTF-8"))).")";
		BDD_EXECUTE($requete);
		$message = Expression("MAIL_etat2", $mail);
	}
	setcookie('mail' , $mail , COOKIE_EXPIRATION);
	echo '<table align="center"><tr><td align="center">'.$message.'</td></tr></table>';
} else 
/*****A F F I C H A G E   D E   L A   P A G E   D E   P R E S E N T A T I O N******/  {
	if (isset($_GET["paypal"])) {
		if (trim($_GET["paypal"])=="1")
			echo "<b>Merci, cela nous touche beaucoup</b><br>";
		else
			echo "<b>Tant pis ;-)</b><br>";
	}?>
	Bienvenue dans une passionnante chasse au trésor dans laquelle Aude et Romain vous encourage à deviner un prénom...&nbsp;<br>
	<a class="lien_interne" onclick="getReglement();">Le règlement du jeu est consultable ici</a><br>
	<b><p>Une bouteille de vin est à gagner (au choix du gagnant dans la cave de Romain)</p></b><br>
	<b><h1>Le concours final a commencé !!!!</h1></b><br>
	<br><br>
	Si le coeur vous en dit, vous pouvez participez au frais d'hébergement du site...et aux bouteilles que vous pouvez gagner.<br>
	Aucun bénéfice ne sera fait, tout sera remis in Vino Veritas!
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="6KVKE67GKCQGY">
		<input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
		<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
	</form>
	<?php /* l'utilisateur à déjà saisie un mail*/
	if ((isset($_COOKIE["mail"]) && trim($_COOKIE["mail"]!="")) && (!isset($_POST["mail_reset"])  ))   {
		$mail=$_COOKIE["mail"];
		$requete = "SELECT M.no_joueur, M.id_mail, J.surnom, J.prenom, J.statut  FROM mail M LEFT OUTER JOIN joueur J on J.no_joueur=M.no_joueur 
					WHERE mail =".FormatSQL_chaine(trim(mb_convert_encoding($mail, "auto", 	"UTF-8")));
		$recordset	= BDD_EXECUTE($requete);
		if ($record		= BDD_RESULT2ARRAY($recordset)) {
			$nojoueur	= trim($record[0]);
			$surnom		= trim($record[2]);
			$prenom		= trim($record[3]);
			$statut		= trim($record[4]);
			if ($surnom=="")
				$appelation = $prenom;
			else
				$appelation = $surnom;
			LOG_ACTION($nojoueur, 1, "IP : ".$_SERVER["REMOTE_ADDR"]." joueur n°".$nojoueur." (".$appelation.")");
			
			switch ($statut) {
				/*Le joueur est connu*/
				case "0":
				case "1":
					$message=Expression("MAIL_etat01_visite", $appelation);
					break;
				/*Le joueur n'est pas connu, il s'active une nouvelle fois*/
				case "2" : 
					$message=Expression("MAIL_etat2_visite", $appelation);
					break;
				/*Le joueur est interdit*/
				case "3" : 
					$message = Expression("MAIL_etat3", $appelation);
					break;
			}
			afficheMail($message);
		}
		else
			afficheSaisieMail();
	} else   {  /* l'utilisateur n'a pas saisie d'email ou veut le supprimer de son cookie*/
		setcookie('mail');
		afficheSaisieMail();
	}
}
BDD_close();
?>