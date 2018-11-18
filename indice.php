<?php
header("Content-Type:text/plain; charset=iso-8859-1");
require("./Include/function.php");
BDD_connexion();
/*Selection du prochain indice à afficher */
$requete = "SELECT no_indice, 	hour(datedebut),minute(datedebut), 	second(datedebut), 	month(datedebut),	day(datedebut),	year(datedebut), 
								hour(datefin), 	minute(datefin), 	second(datefin), 	month(datefin), 	day(datefin), 	year(datefin)
	FROM indice	WHERE datedebut>'".date("Y-m-d H:i:s")."' ORDER BY datedebut LIMIT 1";
$recordset	= BDD_EXECUTE($requete);
if ($recordset=BDD_RESULT2ARRAY($recordset))   {
	$datefin	= mktime($recordset[7], $recordset[8], $recordset[9], $recordset[10], $recordset[11], $recordset[12]);
	$datedebut	= mktime($recordset[1], $recordset[2], $recordset[3], $recordset[4],  $recordset[5],  $recordset[6]);
	$difference	= $datefin-$datedebut;
	$jour		= intval($difference/86400);
	$heure		= intval(($difference-($jour*86400))/3600);
	$minute		= intval(($difference-($jour*86400)-($heure*3600))/60);
	$dure		= $jour." jours ".$heure." heures ".$minute." minutes";
	$day	=$recordset[5];
	$month	=$recordset[4];
	$year	=$recordset[6];
	$hour	=$recordset[1];
	$minute	=$recordset[2];
	$second	=$recordset[3];
	$now	= time();
	$target = mktime($hour, $minute, $second, $month, $day, $year);
	$diffSecs 	= $target - $now;
	$date 		= array();
	$date['secs']	= $diffSecs % 60;
	$date['mins']	= floor($diffSecs/60)%60;
	$date['hours']	= floor($diffSecs/60/60)%24;
	$date['days']	= floor($diffSecs/60/60/24)%7;
	$date['weeks']	= floor($diffSecs/60/60/24/7);
	foreach ($date as $i => $d) {
		$d1 = $d%10;
		$d2 = ($d-$d1) / 10;
		$date[$i] = array((int)$d2,(int)$d1,(int)$d);
	}	
	?>
	<input type="hidden" id="day" 		name="day" 		value="<?php echo $day;?>"	 >
	<input type="hidden" id="month" 	name="month" 	value="<?php echo $month;?>" >
	<input type="hidden" id="year" 		name="year"		value="<?php echo $year;?>"	 >
	<input type="hidden" id="hour" 		name="hour"		value="<?php echo $hour;?>"	 >
	<input type="hidden" id="minute" 	name="minute"	value="<?php echo $minute;?>">
	<input type="hidden" id="second" 	name="second"	value="<?php echo $second;?>">
	<div class="indice_titre">
	<?php echo Expression("INDICE", "<b>".$dure."</b>"); ?>
		<div id="container">
			<div id="countdown_dashboard">
				<div class="dash weeks_dash">
					<span class="dash_title">Semaines</span><div class="digit"><?php echo $date['weeks'][0]?></div><div class="digit"><?php echo $date['weeks'][1]?></div>
				</div>
				<div class="dash days_dash">
					<span class="dash_title">Jours</span><div class="digit"><?php echo $date['days'][0]?></div><div class="digit"><?php echo $date['days'][1]?></div>
				</div>
				<div class="dash hours_dash">
					<span class="dash_title">Heures</span><div class="digit"><?php echo $date['hours'][0]?></div><div class="digit"><?php echo $date['hours'][1]?></div>
				</div>
				<div class="dash minutes_dash">
					<span class="dash_title">Minutes</span><div class="digit"><?php echo $date['mins'][0]?></div><div class="digit"><?php echo $date['mins'][1]?></div>
				</div>
				<div class="dash seconds_dash">
					<span class="dash_title">Secondes</span><div class="digit"><?php echo $date['secs'][0]?></div><div class="digit"><?php echo $date['secs'][1]?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="indice_contenu">&nbsp;</div>
<?php }
echo '<div class="indice_presentation">'.Expression("INDICE_liste", "").'</div>';

/*Selection des indices à afficher */
$requete = "SELECT no_indice, datedebut, datefin, commentaire, libelle
	FROM indice WHERE datedebut<='".date("Y-m-d H:i:s")."' and datefin>='".date("Y-m-d H:i:s")."' ORDER BY datedebut desc";
$recordset	= BDD_EXECUTE($requete);
while ($record=BDD_RESULT2ARRAY($recordset))   {
	echo '<div class="indice_titre">';
	echo '		<b>'.trim($record[3]).'</b> (en ligne du '.arrangedate(trim($record[1]), "AAAA-MM-JJ HH:mm:ss", "", "JJ/MM/AAAA HH:mm");
	echo '		jusqu\'au '.arrangedate(trim($record[2]), "AAAA-MM-JJ HH:mm:ss", "", "JJ/MM/AAAA HH:mm").')</div>';
	echo '<div class="indice_contenu">'.trim($record[4]).'</div>';
}
BDD_close();
?>