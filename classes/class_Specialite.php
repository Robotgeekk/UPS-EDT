<?php
	/** 
	 * Classe Specialite - Permet de gérer les Specialites
	 */ 
	class Specialite{
		
		public static $nomTable = "Specialite";
		
		public static $attributs = Array(
			"nom",
			"intitule"
		);
		
		/**
		 * Getter de l'id de la spécialité
		 * @return int : id de la spécialité
		 */
		public function getId() { return $this->id; }
		
		/**
		 * Getter du nom de la spécialité
		 * @return string : nom de la spécialité
		 */
		public function getNom() { return $this->nom; }
		
		/**
		 * Getter de l'intitule de la spécialité
		 * @return string : intitule de la spécialité
		 */
		public function getIntitule() { return $this->intitule; }
		
		/**
		 * Constructeur de la classe Specialite
		 * Récupère les informations de Specialite dans la base de données depuis l'id
		 * @param $id : int id du Specialite
		 */
		public function Specialite($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Specialite::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Specialite::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction testant l'existence d'une spécialité
		 * @param id : int id de la spécialité
		 */
		public static function existe_specialite($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Specialite::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
				);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['nb'] == 1;
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Ajouter une spécialité dans la base de données
		 * @param $nom : string nom de la spécialité
		 * @param $intitule : string intitulé de la spécialité
		 * @param $idPromotion : int idPromotion de la spécialité
		 */
		public static function ajouter_specialite($nom, $intitule, $idPromotion) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Specialite::$nomTable." VALUES(?, ?, ?, ?)");
				
				$req->execute(
					Array(
						"",
						$idPromotion,
						$nom, 
						$intitule
					)
				);			
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Modifier une spécialité dans la base de données
		 * @param $idSpecialite : int id de la spécialité a modifié
		 * @param $nom : string nom de la spécialité
		 * @param $intitule : string intitulé de la spécialité
		 * @param $idPromotion : int idPromotion de la spécialité
		 */
		public static function modifier_specialite($idSpecialite, $nom, $intitule, $idPromotion) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Specialite::$nomTable." SET nom=?, intitule=?, idPromotion=? WHERE id=?;");
				$req->execute(
					Array(
						$nom, 
						$intitule, 
						$idPromotion,
						$idSpecialite
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Supprime une spécialité dans la base de données
		 * @param $idSpecialite int : id de la spécialité a supprimé
		 */
		public static function supprimer_specialite($idSpecialite) {
		
			//MAJ de la table "Etudiant" on met idSpecialite à 0 pour l'idSpecialite correspondant
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Etudiant::$nomTable." SET idSpecialite = 0 WHERE idSpecialite=?;");
				$req->execute(
					Array(
						$idSpecialite
					)
				);
				
				//Suppression de la spécialité
				try {
					$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("DELETE FROM ".Specialite::$nomTable." WHERE id=?;");
					$req->execute(
						Array(
							$idSpecialite
						)
					);
				}
				catch (Exception $e) {
					echo "Erreur : ".$e->getMessage()."<br />";
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction utilisée pour l'affichage du formulaire utilisé pour l'ajout d'une spécialité
		 * @param $idPromotion : int id de la promotion sélectionnée
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public function formulaireAjoutSpecialite($idPromotion, $nombresTabulations = 0) {
			$tab = ""; while ($nombresTabulation = 0) { $tab .= "\t"; $nombresTabulations--; }
			
			// Gestion du formulaire suivant si on ajoute ou on modifie une spécialité
			if (isset($_GET['modifier_specialite'])) { 
				$titre = "Modifier une spécialité";
				$Specialite = new Specialite($_GET['modifier_specialite']);
				$nomModif = "value=\"{$Specialite->getNom()}\"";
				$intituleModif = "value=\"{$Specialite->getIntitule()}\"";
				$valueSubmit = "Modifier la spécialité"; 
				$nameSubmit = "validerModificationSpecialite";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_specialite']}\" />";
				$lienAnnulation = "index.php?page=ajoutSpecialite";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion=".$_GET['idPromotion'];
				}
			}
			else {
				$titre = "Ajouter une spécialité";
				$nomModif = (isset($_POST['nom'])) ? "value=\"".$_POST['nom']."\"" : "value=\"\"";
				$intituleModif = (isset($_POST['intitule'])) ? "value=\"".$_POST['intitule']."\"" : "value=\"\"";
				$valueSubmit = "Ajouter la spécialité"; 
				$nameSubmit = "validerAjoutSpecialite";
				$hidden = "";
			}
		
			echo $tab."<h2>".$titre."</h2>\n";
			echo $tab."<form method=\"post\">\n";
			echo $tab."\t<table>\n";
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Nom</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"nom\" type=\"text\" required {$nomModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Intitulé</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"intitule\" type=\"text\" required {$intituleModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td></td>\n";
			echo $tab."\t\t\t<td>".$hidden."<input type=\"submit\" name=\"".$nameSubmit."\" value=\"".$valueSubmit."\"></td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t</table>\n";
			echo $tab."</form>\n";	

			if (isset($lienAnnulation)) {
				echo $tab."<p><a href=\"".$lienAnnulation."\">Annuler modification</a></p>";
			}				
		}	
		
		/**
		 * Fonction permettant de prendre en compte les informations validées dans le formulaire pour la MAJ de la base de données
		 */
		public static function priseEnCompteFormulaire() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_POST['validerAjoutSpecialite']) || isset($_POST['validerModificationSpecialite'])) {
				// Vérification des champs				
				$nom = htmlentities($_POST['nom'], ENT_QUOTES, 'UTF-8');
				$nomCorrect = PregMatch::est_nom($nom);
				$intitule = htmlentities($_POST['intitule'], ENT_QUOTES, 'UTF-8');
				$intituleCorrect = PregMatch::est_intitule($intitule);
				
				$validationAjout = false;
				if (isset($_POST['validerAjoutSpecialite'])) {
					// Ajout d'une nouvelle spécialité
					if ($nomCorrect && $intituleCorrect) {
						Specialite::ajouter_specialite($nom, $intitule, $_GET['idPromotion']);
						array_push($messagesNotifications, "La spécialité a bien été ajouté");
						$validationAjout = true;
					}
				}
				else {
					// Modification d'une nouvelle spécialité
					$id = htmlentities($_POST['id']); 
					$idCorrect = Specialite::existe_specialite($id);
					if ($idCorrect && $nomCorrect && $intituleCorrect) {
						Specialite::modifier_specialite($_GET['modifier_specialite'], $nom, $intitule, $_GET['idPromotion']);
						array_push($messagesNotifications, "La spécialité a bien été modifié");
						$validationAjout = true;
					}				
				}
				
				// Traitement des erreurs
				if (!$validationAjout) {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
					if (isset($idCorrect) && !$idCorrect) {
						array_push($messagesErreurs, "L'id de la spécialité n'est pas correct, contacter un administrateur");
					}
					if (!$nomCorrect) {
						array_push($messagesErreurs, "Le nom n'est pas correct");
					}
					if (!$intituleCorrect) {
						array_push($messagesErreurs, "L'intitulé n'est pas correct");
					}
				}
			}			
		}
		
		/**
		 * Fonction permettant de prendre en compte la validation d'une demande de suppression d'une spécialité, on test s'il est bien enregistré dans la base de donnée
		 */
		public static function priseEnCompteSuppression() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_GET['supprimer_specialite'])) {	
				if (Specialite::existe_specialite($_GET['supprimer_specialite'])) {
					// La spécialité existe
					Specialite::supprimer_specialite($_GET['supprimer_specialite']);
					array_push($messagesNotifications, "La spécialité à bien été supprimé");
				}
				else {
					// La spécialité n'existe pas
					array_push($messagesErreurs, "La spécialité n'existe pas");
				}
			}
		}
		
		/**
		* Fonction principale permettant l'affichage du formulaire d'ajout ou de modification d'une spécialité ainsi que l'affichage des spécialités de la promotion enregistrées dans la base de données
		* @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		*/
		public static function pageAdministration($nombreTabulations = 0) {			
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			Specialite::formulaireAjoutSpecialite($_GET['idPromotion'], $nombreTabulations + 1);
			echo $tab."<h2>Liste des spécialités</h2>\n";
			Specialite::liste_specialite_to_table($_GET['idPromotion'], $nombreTabulations + 1);
		}
		
		/**
		 * Liste des spécialités de la promotion sélectionnée
		 * @param $idPromotion : int id de la promotion sélectionnée
		 * @return List<Specialite> : informations des spécialités de la promotion sélectionnée
		 */
		public static function liste_specialite($idPromotion) {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Specialite::$nomTable." WHERE idPromotion = ? ORDER BY nom");
				$req->execute(
					Array($idPromotion)
				);
				while ($ligne = $req->fetch()) {
					array_push($listeId, $ligne['id']);
				}
				$req->closeCursor();
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeId;
		}
		
		/**
		 * Fonction utilisée pour l'affichage de la liste des spécialités créés 
		 * @param $idPromotion : int id de la promotion sélectionnée
		 * @param $administration boolean : possibilité de modification et suppression si egal à 1
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public static function liste_specialite_to_table($idPromotion, $administration, $nombreTabulations = 0) {
			//Liste des spécialités de la promotion
			$liste_specialite = Specialite::liste_specialite($idPromotion);
			$nbSpecialite = sizeof($liste_specialite);
			
			$tab = ""; while ($nombreTabulations > 0) { $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbSpecialite == 0) {
				echo $tab."<b>Aucune spécialité n'est enregistré pour cette promotion</b>\n";
			}
			else {
			
				echo $tab."<table class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				echo $tab."\t\t<th>Nom</th>\n";
				echo $tab."\t\t<th>Intitulé</th>\n";
				
				if ($administration) {
					echo $tab."\t\t<th>Actions</th>\n";
				}
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				// Gestion de l'affichage des informations des spécialités
				foreach ($liste_specialite as $idSpecialite) {
					$Specialite = new Specialite($idSpecialite);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo $tab."\t<tr class=\"".$couleurFond."\">\n";
					foreach (Specialite::$attributs as $att) {
						echo $tab."\t\t<td>".$Specialite->$att."</td>\n";
					}
					
					// Création des liens pour la modification et la suppression des spécialités et gestion de l'URL 
					if ($administration) {
						$pageModification = "./index.php?page=ajoutSpecialite&amp;modifier_specialite=".$idSpecialite;
						$pageSuppression = "./index.php?page=ajoutSpecialite&amp;supprimer_specialite=".$idSpecialite;
						
						if (isset($_GET['idPromotion'])) {
							$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
							$pageSuppression .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						echo $tab."\t\t<td>";
						echo "<a href=\"".$pageModification."\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"".$pageSuppression."\" onclick=\"return confirm('Supprimer la specialite ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
						echo "</td>\n";
					}
					echo $tab."\t</tr>\n";
				}
				
				echo $tab."</table>\n";
			}
		}
	}
