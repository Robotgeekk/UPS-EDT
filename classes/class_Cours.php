<?php
	class Cours{
		
		public static $nomTable = "Cours";
		
		public static $attributs = Array(
			"id",
			"idUE",
			"idSalle",
			"idIntervenant",
			"idTypeCours",
			"tsDebut",
			"tsFin"
		);
		
		public function getId(){ return $this->id; }
		public function getIdUE(){ return $this->idUE; }
		public function getIdSalle(){ return $this->idSalle; }
		public function getIdIntervenant(){ return $this->idIntervenant; }
		public function getIdTypeCours(){ return $this->idTypeCours; }
		public function getTsDebut(){ return $this->tsDebut; }
		public function getTsFin(){ return $this->tsFin; }
		
		public function Cours($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Cours::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Cours::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function ajouter_cours($idUE, $idSalle, $idIntervenant, $type, $tsDebut, $tsFin, $recursivite){
		
			for ($i=0; $i<=$recursivite; $i++) {
				try{
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("INSERT INTO ".Cours::$nomTable." VALUES(?, ?, ?, ?, ?, ?, ?)");
					$req->execute(
						Array(
							"",
							$idUE,
							$idSalle,
							$idIntervenant,
							$type,
							$tsDebut,
							$tsFin
						)
					);
				}
				catch(Exception $e){
					echo "Erreur : ".$e->getMessage()."<br />";
				}
				
				$tsDebut = Cours::datePlusUneSemaine($tsDebut);
				$tsFin = Cours::datePlusUneSemaine($tsFin);
			}			
		}
		
		public static function datePlusUneSemaine($tsDate){
			$tsDate_explode = explode(' ',$tsDate);
			$tsDate_jma = $tsDate_explode[0];
			$tsDate_hms = $tsDate_explode[1];
			$tsDate_jma_explode = explode('-',$tsDate_jma);
			$timestamp = mktime(0, 0, 0, $tsDate_jma_explode[1],$tsDate_jma_explode[2],$tsDate_jma_explode[0]);
			$timestamp_plus_une_semaine = $timestamp + (3600 * 24 * 7); //On ajoute une semaine
			$date_jma = date('Y-m-d',$timestamp_plus_une_semaine);
			$date = $date_jma." ".$tsDate_hms;
			return $date;
		}
		
		public static function modifier_cours($idCours, $idUE, $idSalle, $idIntervenant, $idTypeCours, $tsDebut, $tsFin){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Cours::$nomTable." SET idUE=?, idSalle=?, idIntervenant=?, idTypeCours=?, tsDebut=?, tsFin=? WHERE id=?;");
				$req->execute(
					Array(
						$idUE,
						$idSalle,
						$idIntervenant,
						$idTypeCours,
						$tsDebut,
						$tsFin,
						$idCours
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_cours($idCours){
			//Suppression des apparitions du cours dans la table "Appartient_Cours_GroupeCours"
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Appartient_Cours_GroupeCours::$nomTable." WHERE idCours=?;");
				$req->execute(
					Array(
						$idCours
					)
				);			
			
				//Suppression du cours
				try{
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("DELETE FROM ".Cours::$nomTable." WHERE id=?;");
					$req->execute(
						Array(
							$idCours
						)
					);
				}
				catch(Exception $e){
					echo "Erreur : ".$e->getMessage()."<br />";
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_salle($idCours, $idSalle){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Cours::$nomTable." SET idSalle=? WHERE id=?;");
				$req->execute(
					Array($idSalle, $idCours)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_salle_tout_cours($idSalleSrc, $idSalleDst){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Cours::$nomTable." SET idSalle=? WHERE idSalle=?;");
				$req->execute(
					Array($idSalleDst, $idSalleSrc)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function liste_cours_to_table($idPromotion, $administration, $nombreTabulations = 0){
			$liste_cours = V_Infos_Cours::liste_cours_futur($idPromotion);
			$nbCours = sizeof($liste_cours);
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbCours == 0) {
				echo "$tab<b>Aucun cours à venir n'est enregistré pour cette promotion</b>\n";
			}
			else {
			
				echo "$tab<table class=\"table_liste_administration\">\n";
				
				echo "$tab\t<tr class=\"fondGrisFonce\">\n";
				
				echo "$tab\t\t<th>UE</th>\n";
				echo "$tab\t\t<th>Intervenant</th>\n";
				echo "$tab\t\t<th>Type</th>\n";
				echo "$tab\t\t<th>Date</th>\n";
				echo "$tab\t\t<th>Salle</th>\n";
				
				if($administration){
					echo "$tab\t\t<th>Actions</th>\n";
				}
				echo "$tab\t</tr>\n";
				
				$cpt = 0;
				foreach($liste_cours as $idCours){
					$Cours = new V_Infos_Cours($idCours);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo "$tab\t<tr class=\"$couleurFond\">\n";
					$cptBoucle=0;
					$valTemp="";
					$valTemp2="";
					foreach(V_Infos_Cours::$attributs as $att){
						if ( ($cptBoucle == 1) || ($cptBoucle == 4) || ($cptBoucle == 6) )
							$valTemp = $Cours->$att;
						else if ( ($cptBoucle == 2) || ($cptBoucle == 7) ) {
							$val = $Cours->$att." ".$valTemp;
							$valTemp="";
							echo "$tab\t\t<td>".$val."</td>\n";
						}
						else if ($cptBoucle == 5) {
							$valTemp2 = $Cours->$att;
							$val = "De ".$valTemp." à ".$valTemp2;
							echo "$tab\t\t<td>";
							Cours::dateCours($valTemp, $valTemp2);
							echo "</td>\n";
						}
						else {
							echo "$tab\t\t<td>".$Cours->$att."</td>\n";
						}
						$cptBoucle++;
					}
					if($administration){
						$pageModification = "./index.php?page=ajoutCours&modifier_cours=$idCours";
						$pageSuppression = "./index.php?page=ajoutCours&supprimer_cours=$idCours";
						if(isset($_GET['idPromotion'])){
							$pageModification .= "&amp;idPromotion={$_GET['idPromotion']}";
							$pageSuppression .= "&amp;idPromotion={$_GET['idPromotion']}";
						}
						
						echo "$tab\t\t<td>";
						echo "<a href=\"$pageModification\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"$pageSuppression\" onclick=\"return confirm('Supprimer le cours ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
						echo "</td>\n";
					}
					echo "$tab\t</tr>\n";
				}
				
				echo "$tab</table>\n";
			}
		}
	
		public function dateCours($dateDebut, $dateFin) {
			$chaineDateDebut = explode(' ',$dateDebut);
			$chaineJMADebut = explode('-',$chaineDateDebut[0]);
			$chaineHMSDebut = explode(':',$chaineDateDebut[1]);

			$chaineDateFin = explode(' ',$dateFin);
			$chaineJMAFin = explode('-',$chaineDateFin[0]);
			$chaineHMSFin = explode(':',$chaineDateFin[1]);
			
			if ($chaineJMADebut[2] == $chaineJMAFin[2]) {
				echo "Le ";
				echo Cours::getDate($chaineJMADebut[2],$chaineJMADebut[1],$chaineJMADebut[0]);
				echo " de {$chaineHMSDebut[0]}h{$chaineHMSDebut[1]}";
				echo " à {$chaineHMSFin[0]}h{$chaineHMSFin[1]}";
			}
			else {
				echo "Du ";
				echo Cours::getDate($chaineJMADebut[2],$chaineJMADebut[1],$chaineJMADebut[0]);
				echo " {$chaineHMSDebut[0]}h{$chaineHMSDebut[1]} au ";
				echo Cours::getDate($chaineJMAFin[2],$chaineJMAFin[1],$chaineJMAFin[0]);
				echo " {$chaineHMSFin[0]}h{$chaineHMSFin[1]}";
			}
		}
		
		public function getDate($jour, $mois, $annee) {
			if ($jour == 1)  
				$numero_jour = '1er';
			else if ($jour < 10)
				$numero_jour = $jour[1];
			else 
				$numero_jour = $jour;
				
			$nom_mois = "";
			switch ($mois) {
				case 1 : 
					$nom_mois = 'Janvier';
					break;
				case 2 : 
					$nom_mois = 'Fevrier';
					break;
				case 3 : 
					$nom_mois = 'Mars';
					break;
				case 4 : 
					$nom_mois = 'Avril';
					break;
				case 5 : 
					$nom_mois = 'Mai';
					break;
				case 6 : 
					$nom_mois = 'Juin';
					break;
				case 7 : 
					$nom_mois = 'Juillet';
					break;
				case 8 : 
					$nom_mois = 'Août';
					break;
				case 9 : 
					$nom_mois = 'Septembre';
					break;
				case 10 : 
					$nom_mois = 'Octobre';
					break;
				case 11 : 
					$nom_mois = 'Novembre';
					break;
				case 12 : 
					$nom_mois = 'Décembre';
					break;
			}
			
			echo "{$numero_jour} {$nom_mois} {$annee}";
		}		
		
		// Formulaire
		public function formulaireAjoutCours($idPromotion, $nombresTabulations = 0){
			$tab = ""; while($nombresTabulation = 0){ $tab .= "\t"; $nombresTabulations--; }
			$liste_UE_promotion = UE::liste_UE_promotion($idPromotion);
			$liste_intervenant = Intervenant::liste_intervenant();
			$liste_type_cours = Type_Cours::liste_id_type_cours();
			
			if(isset($_GET['modifier_cours'])){ 
				$titre = "Modifier un cours";
				$Cours = new Cours($_GET['modifier_cours']);
				$idUEModif = $Cours->getIdUE();
				$idSalleModif = $Cours->getIdSalle();
				$idIntervenantModif = $Cours->getIdIntervenant();
				$idTypeCoursModif = $Cours->getIdTypeCours();
				$tsDebutModif = $Cours->getTsDebut();
				$tsFinModif = $Cours->getTsFin();
				$valueSubmit = "Modifier le cours"; 
				$nameSubmit = "validerModificationCours";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_cours']}\" />";
				$lienAnnulation = "index.php?page=ajoutCours";
				if(isset($_GET['idPromotion'])){
					$lienAnnulation .= "&amp;idPromotion={$_GET['idPromotion']}";
				}
			}
			else{
				$titre = "Ajouter un cours";
				$idTypeCoursModif = 1;
				$idSalleModif = 0;
				$valueSubmit = "Ajouter le cours"; 
				$nameSubmit = "validerAjoutCours";
				$hidden = "";
			}
			
			echo "$tab<h2>$titre</h2>\n";
			echo "$tab<form method=\"post\">\n";
			echo "$tab\t<table>\n";
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label for=\"UE\">UE</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"UE\" id=\"UE\">\n";
			foreach($liste_UE_promotion as $idUE){
				$UE = new UE($idUE);
				$nomUE = $UE->getNom();
				if(isset($idUEModif) && ($idUEModif == $idUE)){ $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"$idUE\" $selected>$nomUE</option>\n";
			}
			echo "$tab\t\t\t\t</select>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label for=\"type\">Type</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"typeCours\" id=\"typeCours\" onChange=\"update_select_typeSalle({$idSalleModif})\">\n";
			foreach($liste_type_cours as $idTypeCours){
				$Type_Cours = new Type_Cours($idTypeCours);
				$nomTypeCours = $Type_Cours->getNom();
				if($idTypeCoursModif == $idTypeCours){ $selected = "selected=\"selected\""; } else{ $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"$idTypeCours\"$selected>$nomTypeCours</option>\n";
			}
			echo "$tab\t\t\t\t</select>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label for=\"intervenant\">Intervenant</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"intervenant\" id=\"intervenant\">\n";
			
			if(isset($idIntervenantModif) && ($idIntervenantModif == 0)){ $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"0\" $selected>----- Inconnu -----</option>\n";
			foreach($liste_intervenant as $idIntervenant){
				if ($idIntervenant != 0) {
					$Intervenant = new Intervenant($idIntervenant);
					$nomIntervenant = $Intervenant->getNom(); $prenomIntervenant = $Intervenant->getPrenom();
					if(isset($idIntervenantModif) && ($idIntervenantModif == $idIntervenant)){ $selected = "selected=\"selected\" "; } else { $selected = ""; }
					echo "$tab\t\t\t\t\t<option value=\"$idIntervenant\" $selected>$nomIntervenant $prenomIntervenant.</option>\n";
				}
			}
			echo "$tab\t\t\t\t</select>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			if(isset($tsDebutModif)){
				$explode = explode(" ", $tsDebutModif);
				$valueDateDebut = "value=\"{$explode[0]}\" ";
				$explodeHeure = explode(":", $explode[1]);
				$valueHeureDebut = $explodeHeure[0];
				$valueMinuteDebut = $explodeHeure[1];
			}
			else{
				$valueDateDebut = "";
				$valueHeureDebut = "";
				$valueMinuteDebut = "";
			}
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td>Date Debut</td>\n";
			echo "$tab\t\t\t<td><input onchange=\"changeDateDebut(this.value)\" name=\"dateDebut\" type=\"date\" required $valueDateDebut/> aaaa-mm-jj</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td>Heure Debut</td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"heureDebut\" onchange=\"changeHeureDebut(this.value)\">\n";			
			for ($cpt=0;$cpt<=23;$cpt++) {
				if ($cpt == $valueHeureDebut)
					$selected = " selected";
				else if ( ($cpt == 7) && ($valueHeureDebut == "") )
					$selected = " selected";
				else
					$selected = "";
					
				if ($cpt < 10)
					echo "$tab\t\t\t\t\t<option value=\"0{$cpt}\" {$selected}>0{$cpt}</option>\n";
				else
					echo "$tab\t\t\t\t\t<option value=\"{$cpt}\" {$selected}>{$cpt}</option>\n";				
			}
			echo "$tab\t\t\t\t\t</select>\n";
			echo "$tab\t\t\t\t<select name=\"minuteDebut\" onchange=\"changeMinuteDebut(this.value)\">\n";		
			$tab_minute = array(0,15,30,45);
			$first=false;
			for ($cpt=0;$cpt<4;$cpt++) {
				if($tab_minute[$cpt] == $valueMinuteDebut) {
					$selected = " selected";
				}
				else if ( ($cpt == 3) && ($valueMinuteDebut == "") )
					$selected = " selected";
				else
					$selected = "";
					
				if ($cpt == 0)
					echo "$tab\t\t\t\t\t<option value=\"0{$tab_minute[$cpt]}\" {$selected}>0{$tab_minute[$cpt]}</option>\n";
				else
					echo "$tab\t\t\t\t\t<option value=\"{$tab_minute[$cpt]}\" {$selected}>{$tab_minute[$cpt]}</option>\n";						
			}
			echo "$tab\t\t\t\t\t</select>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			if(isset($tsFinModif)){
				$explode = explode(" ", $tsFinModif);
				$valueDateFin = "value=\"{$explode[0]}\" ";
				$explodeHeure = explode(":", $explode[1]);
				$valueHeureFin = $explodeHeure[0];
				$valueMinuteFin = $explodeHeure[1];
			}
			else{
				$valueDateFin = "";
				$valueHeureFin = "";
				$valueMinuteFin = "";
			}
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td>Date Fin</td>\n";
			echo "$tab\t\t\t<td><input id=\"dateFin\" name=\"dateFin\" type=\"date\" required $valueDateFin/> aaaa-mm-jj</td>\n";
			echo "$tab\t\t</tr>\n";
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td>Heure Fin</td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"heureFin\">\n";			
			for ($cpt=0;$cpt<=23;$cpt++) {
				if ($cpt == $valueHeureFin)
					$selected = " selected";
				else if ( ($cpt == 9) && ($valueHeureFin == "") )
					$selected = " selected";
				else
					$selected = "";
					
				if ($cpt < 10)
					echo "$tab\t\t\t\t\t<option value=\"0{$cpt}\" {$selected}>0{$cpt}</option>\n";
				else
					echo "$tab\t\t\t\t\t<option value=\"{$cpt}\" {$selected}>{$cpt}</option>\n";				
			}
			echo "$tab\t\t\t\t\t</select>\n";
			echo "$tab\t\t\t\t<select name=\"minuteFin\">\n";		
			$tab_minute = array(0,15,30,45);
			$first=false;
			for ($cpt=0;$cpt<4;$cpt++) {
				if($tab_minute[$cpt] == $valueMinuteFin) {
					$selected = " selected";
				}
				else if ( ($cpt == 3) && ($valueMinuteFin == "") )
					$selected = " selected";
				else
					$selected = "";
					
				if ($cpt == 0)
					echo "$tab\t\t\t\t\t<option value=\"0{$tab_minute[$cpt]}\" {$selected}>0{$tab_minute[$cpt]}</option>\n";
				else
					echo "$tab\t\t\t\t\t<option value=\"{$tab_minute[$cpt]}\" {$selected}>{$tab_minute[$cpt]}</option>\n";						
			}
			echo "$tab\t\t\t\t\t</select>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label for=\"salle\">Salle</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"salle\" id=\"salle\">\n";
			
			Cours::liste_salle_suivant_typeCours($idSalleModif, $idTypeCoursModif);
			
			echo "$tab\t\t\t\t</select>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			if(! isset($_GET['modifier_cours'])){ 
				echo "$tab\t\t<tr>\n";
				echo "$tab\t\t\t<td><label for=\"recursivite\">Récursivité</label></td>\n";
				echo "$tab\t\t\t<td>\n";
				echo "$tab\t\t\t\t<select name=\"recursivite\" id=\"recursivite\">\n";
				
				echo "$tab\t\t\t\t\t<option value=\"0\" $selected>----- Aucune -----</option>\n";
				for($i=1; $i<=10; $i++){
					echo "$tab\t\t\t\t\t<option value=\"$i\">$i</option>\n";					
				}
				echo "$tab\t\t\t\t</select> (en semaines)\n";
				echo "$tab\t\t\t</td>\n";
				echo "$tab\t\t</tr>\n";
			}			
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td></td>\n";
			echo "$tab\t\t\t<td>$hidden<input type=\"submit\" name=\"$nameSubmit\" value=\"{$valueSubmit}\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";
			
			if(isset($lienAnnulation)){echo "$tab<p><a href=\"$lienAnnulation\">Annuler modification</a></p>";}	
		}
		
		public static function liste_salle_suivant_typeCours($idSalleModif, $idTypeCours) {
			$tab = "";
			$liste_salle = V_Liste_Salles::liste_salles_appartenant_typeCours($idTypeCours);
			
			if(isset($idSalleModif) && ($idSalleModif == 0)){ $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"0\" $selected>----- Inconnu -----</option>\n";
			foreach($liste_salle as $idSalle){
				$Salle = new V_Liste_Salles($idSalle);
				$nomBatiment = $Salle->getNomBatiment();
				$nomSalle = $Salle->getNomSalle();
				if(isset($idSalleModif) && ($idSalleModif == $idSalle)){ $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"$idSalle\" $selected>$nomBatiment $nomSalle</option>\n";
			}
		}
		
		public static function prise_en_compte_formulaire(){
			global $messages_notifications, $messages_erreurs;
			if(isset($_POST['validerAjoutCours'])){
				$idUE = $_POST['UE'];
				$idUE_correct = true;
				$idSalle = $_POST['salle'];
				$idSalle_correct = true;
				$idIntervenant = $_POST['intervenant'];
				$idIntervenant_correct = true;
				$typeCours = $_POST['typeCours'];
				$typeCours_correct = true;
				$dateDebut = $_POST['dateDebut'];
				$dateDebut_correct = true;
				$heureDebut = $_POST['heureDebut'];
				$heureDebut_correct = true;
				$minuteDebut = $_POST['minuteDebut'];
				$minuteDebut_correct = true;
				$dateFin = $_POST['dateFin'];
				$dateFin_correct = true;
				$heureFin = $_POST['heureFin'];
				$heureFin_correct = true;
				$minuteFin = $_POST['minuteFin'];
				$minuteFin_correct = true;
				$recursivite = $_POST['recursivite'];
				$recursivite_correct = true;
				if($idUE_correct && $idSalle_correct && $idIntervenant_correct && $typeCours_correct && $dateDebut_correct && $heureDebut_correct && $minuteDebut_correct && $dateFin_correct && $heureFin_correct && $minuteFin_correct && $recursivite_correct){	
					Cours::ajouter_cours($idUE, $idSalle, $idIntervenant, $typeCours, "$dateDebut $heureDebut:$minuteDebut:00", "$dateFin $heureFin:$minuteFin:00", $recursivite);				
					array_push($messages_notifications, "Le cours a bien été ajouté");
				}
				else{
					array_push($messages_erreurs, "La saisie n'est pas correcte");
				}
			}
			else if(isset($_POST['validerModificationCours'])){	
				$id = $_POST['id']; 
				$id_correct = V_Infos_Cours::existe_cours($id);			
				$idUE = $_POST['UE'];
				$idUE_correct = true;
				$idSalle = $_POST['salle'];
				$idSalle_correct = true;
				$idIntervenant = $_POST['intervenant'];
				$idIntervenant_correct = true;
				$typeCours = $_POST['typeCours'];
				$typeCours_correct = true;
				$dateDebut = $_POST['dateDebut'];
				$dateDebut_correct = true;
				$heureDebut = $_POST['heureDebut'];
				$heureDebut_correct = true;
				$minuteDebut = $_POST['minuteDebut'];
				$minuteDebut_correct = true;
				$dateFin = $_POST['dateFin'];
				$dateFin_correct = true;
				$heureFin = $_POST['heureFin'];
				$heureFin_correct = true;
				$minuteFin = $_POST['minuteFin'];
				$minuteFin_correct = true;
				if($id_correct && $idUE_correct && $idSalle_correct && $idIntervenant_correct && $typeCours_correct && $dateDebut_correct && $heureDebut_correct && $minuteDebut_correct && $dateFin_correct && $heureFin_correct && $minuteFin_correct){
					Cours::modifier_cours($_GET['modifier_cours'], $idUE, $idSalle, $idIntervenant, $typeCours, "$dateDebut $heureDebut:$minuteDebut:00", "$dateFin $heureFin:$minuteFin:00");
					array_push($messages_notifications, "Le cours a bien été modifié");
				}
				else{
					array_push($messages_erreurs, "La saisie n'est pas correcte");
				}
			}
		}
		
		public static function prise_en_compte_suppression(){
			global $messages_notifications, $messages_erreurs;
			if(isset($_GET['supprimer_cours'])){	
				if(V_Infos_Cours::existe_cours($_GET['supprimer_cours'])){
					// Le cours existe
					Cours::supprimer_cours($_GET['supprimer_cours']);
					array_push($messages_notifications, "Le cours à bien été supprimé");
				}
				else{
					// Le cours n'existe pas
					array_push($messages_erreurs, "Le cours n'existe pas");
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			Cours::formulaireAjoutCours($_GET['idPromotion'], $nombreTabulations + 1);
			echo "$tab<h2>Liste des cours à venir</h2>\n";
			Cours::liste_cours_to_table($_GET['idPromotion'], true, $nombreTabulations + 1);
		}		
		
		public function toUl(){
			$string = "<ul>\n";
			foreach(Cours::$attributs as $att){
				$string .= "<li>$att : ".$this->$att."</li>\n";
			}
			return "$string</ul>\n";
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Cours::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Cours::$nomTable);
		}
	}
