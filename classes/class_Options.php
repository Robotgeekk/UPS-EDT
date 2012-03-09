<?php
	class Options{
		
		public static $nomTable = "Options";
		
		public static $attributs = Array(
			"id",
			"nom",
			"valeur"
		);
		
		public function getId(){ return $this->id; }
		public function getNom(){ return $this->nom; }
		public function getValeur(){ return $this->valeur; }
		
		public function Options($nom){
			try{
				$pdo_Options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_Options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Options::$nomTable." WHERE nom=?");
				$req->execute(
					Array($nom)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Options::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function generer_array_Options_style(){
			$Options_style = Array();
			foreach(Type_Cours::liste_nom_type_cours() as $nom){
				$Options_style["Arriere Plan $nom"] = "background_color_$nom";
				$Options_style["Couleur Texte $nom"] = "color_$nom";
			}
			return $Options_style;
		}
		
		public static function existe_Options($nom){
			try{
				$pdo_Options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_Options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM ".Options::$nomTable." WHERE nom=?");
				$req->execute(
					Array($nom)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['nb'] == 1;
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function valeur_from_nom($nom){
			try{
				$pdo_Options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_Options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Options::$nomTable." WHERE nom=?");
				$req->execute(
					Array($nom)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['valeur'];
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function ajouter_Options($nom, $valeur){
			try{
				$pdo_Options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_Options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Options::$nomTable." VALUES(?, ?, ?)");
				
				$req->execute(
					Array(
						"",
						$nom, 
						$valeur
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_Options($nom, $valeur){
			try{
				$pdo_Options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_Options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Options::$nomTable." SET valeur=? WHERE nom=?;");
				$req->execute(
					Array(
						$valeur,
						$nom
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function formulaire_modification_Options_style_typeCours($nombreTabulations = 0){
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			
			echo "$tab<h1>Gestion d'affichage</h1>\n";
			echo "$tab<h2>Gestion des couleurs de type de cours</h2>\n";
			echo "$tab<form id=\"administration_stype_typeCours\" method=\"post\">\n";
			echo "$tab\t<table>\n";
			foreach(Options::generer_array_Options_style() as $label => $nom){
				$valeur = Options::valeur_from_nom($nom);
				echo "$tab\t\t<tr>\n";
				echo "$tab\t\t\t<td>$label</td>\n";
				echo "$tab\t\t\t<td><input type=\"color\" name=\"$nom\" value=\"$valeur\"/></td>\n";
				echo "$tab\t\t</tr>\n";
			}
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td></td>\n";
			echo "$tab\t\t\t<td><input type=\"submit\" name=\"valider_formulaire_administration_stype_typeCours\" value=\"Valider\"/></td>\n";
			echo "$tab\t\t</tr>\n";
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";
		}
		
		public static function prise_en_compte_formulaire(){
			$name_formulaire = "valider_formulaire_administration_stype_typeCours";
			if(isset($_POST[$name_formulaire])){
				foreach(Options::generer_array_Options_style() as $nom){
					if(PregMatch::est_couleur_avec_diez($_POST[$nom])){
						if(Options::existe_Options($nom)){
							Options::modifier_Options($nom, strtoupper($_POST[$nom]));
						}
						else{
							Options::ajouter_Options($nom, strtoupper($_POST[$nom]));
						}
					}
					$location = "index.php?page=styleTypeCours";
					if(isset($_GET['idPromotion'])){
						$location .= "&idPromotion={$_GET['idPromotion']}";
					}
					header("Location: $location");
				}
			}
		}
	}
