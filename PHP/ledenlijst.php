<h1>Ledenlijst</h1><?php$query = "SELECT waarde FROM instellingen WHERE gebruiker = ".$_SESSION['gebruiker']." AND instelling = 'ledenlijstweergave'";$resultaat = mysql_query($query) or die (mysql_error());$aantal = mysql_num_rows($resultaat);if($aantal == 0) {	$query1 = "INSERT INTO instellingen (gebruiker, instelling, waarde, gewijzigd) VALUES (".$_SESSION['gebruiker'].",'ledenlijstweergave','".$_GET['weergave']."',NOW())";	mysql_query($query1) or die (mysql_error());}else {		$query2 = "UPDATE instellingen SET waarde = '".$_GET['weergave']."', gewijzigd = NOW() WHERE gebruiker = '".$_SESSION['gebruiker']."'";	mysql_query($query2) or die (mysql_error());}//lijst- of fotoweergaveif($_GET['weergave'] == "lijst" || empty($_GET['weergave'])) {	echo "<p><b>Lijstweergave</b> | <a href=\"index.php?pagina=ledenlijst&amp;weergave=foto\">Fotoweergave</a></p>";}else {	echo "<p><a href=\"index.php?pagina=ledenlijst&amp;weergave=lijst\">Lijstweergave</a> | <b>Fotoweergave</b></p>";}if(rechten(6)) {	echo "<table>";	echo "<tr>";	echo "<td><img src=\"afbeeldingen/plus_klein.png\" /></td>";	echo "<td><a href=\"index.php?pagina=lidtoevoegen\">Lid toevoegen</a></td>";	echo "</tr>";	echo "</table>";}include("includes/db_connect.php");$query = "SELECT id, voornaam, tussenvoegsel, achternaam, geslacht FROM gebruikers ORDER BY achternaam";$resultaat = mysql_query($query) or die (mysql_error());//als $_GET['weergave'] == lijstif($_GET['weergave'] == "lijst" || empty($_GET['weergave'])) {	echo "<ul>";	while($obj = mysql_fetch_assoc($resultaat)) {		if($obj['id'] == $_SESSION['gebruiker']) {			if(empty($obj['tussenvoegsel'])) {				echo "<li><b><a href=\"index.php?pagina=gebruiker&amp;id=".$obj['id']."\">".$obj['achternaam'].", ".$obj['voornaam']."</a></b></li>\n";			}			else {				echo "<li><b><a href=\"index.php?pagina=gebruiker&amp;id=".$obj['id']."\">".$obj['achternaam'].", ".$obj['voornaam']." ".$obj['tussenvoegsel']." </a></b></li>\n";			}		}		else {			if(empty($obj['tussenvoegsel'])) {				echo "<li><a href=\"index.php?pagina=gebruiker&amp;id=".$obj['id']."\">".$obj['achternaam'].", ".$obj['voornaam']." </a></li>\n";			}			else {				echo "<li><a href=\"index.php?pagina=gebruiker&amp;id=".$obj['id']."\">".$obj['achternaam'].", ".$obj['voornaam']." ".$obj['tussenvoegsel']."</a></li>\n";			}		}	}	echo "</ul>";}elseif($_GET['weergave'] == "foto") {		// hulpvariabelen	$aantal_kolommen = 4;	$huidige_kolom = 0; // als deze variabele de waarde 0 heeft dan is er een rij afgelopen	// bouw de tabel en doorloop de items		echo "<table width=\"100%\">";		while($obj = mysql_fetch_assoc($resultaat)) { 		// waren we klaar met een rij ? 		if($huidige_kolom == 0) {    		// open een nieuwe rij   			echo "<tr>\n"; // hier is \n toegevoegd om de leesbaarheid van de HTML-source te verhogen 		}		echo "<td align=\"center\">";  		// druk het item af - hier zou je dus nog tabellen kunnen nesten - in dit voorbeeld houden we het simpel 		if(file_exists("afbeeldingen/pasfotos/".$obj['id'].".png")) {			echo "<a href=\"index.php?pagina=gebruiker&amp;id=".$obj['id']."\"><img class=\"pasfoto\" src=\"afbeeldingen/pasfotos/".$obj['id'].".png\" /></a>";		}		else {				if($obj['geslacht'] == "m") {				echo "<img src=\"afbeeldingen/man.png\" />";			}			else {				echo "<img src=\"afbeeldingen/vrouw.png\" />";			}		}				echo "<div class=\"naampasfoto\">";		if(empty($obj['tussenvoegsel'])) {			echo "<a href=\"index.php?pagina=gebruiker&amp;id=".$obj['id']."\">".$obj['achternaam'].", ".$obj['voornaam']." </a>";		}		else {			echo "<a href=\"index.php?pagina=gebruiker&amp;id=".$obj['id']."\">".$obj['achternaam'].", ".$obj['voornaam']." ".$obj['tussenvoegsel']."</a>";		}					echo "</div></td>";		  		// we zijn een kolom verder, verhoog $huidige_kolom 		$huidige_kolom++; 		// waren we klaar met een rij ? 		if($huidige_kolom == $aantal_kolommen) {  			// sluit de rij af en reset $huidige_kolom    		echo "</tr>\n";    		$huidige_kolom = 0;		}	}		// fix voor de laatste rij - was een rij volledig gevuld ?	if($huidige_kolom != 0) { 		// rij was nog niet vol(ledig) - vul de resterende cellen op met "stuffing" 		for($i = $huidige_kolom; $i < $aantal_kolommen; $i++) {    		// in plaats van &nbsp; kun je de cel ook met iets anders vullen natuurlijk   			echo "<td>&nbsp;</td>\n";  		} 		// sluit tenslotte de rij alsnog af  		echo "</tr>\n";	}		echo "</table>";}?><p><a href="ledentekst.php"><img src="afbeeldingen/pdf.png" align="left" style="margin-right: 20px; border: 0px;" /></a>Klik <a href="ledentekst.php">hier</a> om de ledenlijst als pdf-bestand te downloaden.</p>