<?php
	/** 
	 * Classe Appartient_Cours_GroupeCours - Interface entre les groupes de cours et les cours
	 */ 
	class Appartient_Cours_GroupeCours {
		
		public static $nomTable = "Appartient_Cours_GroupeCours";
		
		public static $attributs = Array(
			"idCours",
			"idGroupeCours"
		);
		
		/**
		 * Constructeur de la classe Appartient_Cours_GroupeCours
		 * Récupère les informations de Appartient_Cours_GroupeCours dans la base de données depuis l'id
		 */
		public function Appartient_Cours_GroupeCours() {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Appartient_Cours_GroupeCours::$nomTable);
				$req->execute();
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Appartient_Cours_GroupeCours::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction utilisée pour la gestion de l'association entre les cours et les groupes de cours
		 */
		public function liste_appartenance_cours_groupeCours() {
			$idPromotion = $_GET['idPromotion'];
			
			//liste des groupes de cours de la promotion
			$listeGroupeCours = Groupe_Cours::listeGroupeCours($idPromotion);
			$nbreGroupeCours = Groupe_Cours::getNbreGroupeCours($idPromotion);
			
			//liste des futur cours de la promotion
			$listeCours = V_Infos_Cours::liste_cours_futur($idPromotion);
			$nbCours = V_Infos_Cours::getNbreCoursFutur($idPromotion);
			$tab = "";
			
			if (($nbCours == 0) || ($nbreGroupeCours == 0)) {
				echo $tab."<h2>Aucun groupe de cours et/ou aucun cours n'a été créé pour cette promotion</h2>\n";
			}
			else {
				echo $tab."<table name=\"tabGestionGroupeCours\" class=\"table_liste_administration\">\n";
				echo $tab."\t\t<tr class=\"fondGrisFonce\">\n";
				echo $tab."\t\t\t<th class=\"fondBlanc\" colspan='2' rowspan='2' style=\"border-top-color:white;border-left-color:white;border-top-style: solid;\"></th>\n";
				echo $tab."\t\t\t<th colspan='{$nbreGroupeCours}'>Nom des groupes de cours</th>\n";
				echo $tab."\t\t</tr>\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				foreach ($listeGroupeCours as $idGroupeCours) {
					$_GroupeCours = new Groupe_Cours($idGroupeCours);
					echo $tab."\t\t<td>".$_GroupeCours->getNom()."</td>\n";
				}
				echo $tab."\t</tr>\n";
				
				echo $tab."\t<tr>\n";
				echo $tab."\t<td class=\"fondGrisFonce\" rowspan='{$nbCours}'>Cours</td>\n";
				$cpt = 0;
				foreach ($listeCours as $idCours) {
					if ($cpt == 0) {
						$couleurFond="fondBlanc";
					} else {
						$couleurFond="fondGris";
					}
					$cpt++; $cpt %= 2;
					
					$_Cours = new V_Infos_Cours($idCours);
					echo $tab."\t\t<td class=\"fondGrisFonce\">".$_Cours->getNomUE()." (".$_Cours->getNomTypeCours()." / ".$_Cours->getNomBatiment()."-".$_Cours->getNomSalle().")</td>\n";
					
					foreach ($listeGroupeCours as $idGroupeCours) {
						$_GroupeCours = new Groupe_Cours($idGroupeCours);

						$nom_case = "case_GroupeCours_".$idGroupeCours;
						if (Appartient_Cours_GroupeCours::appartenance_cours_groupeCours($idCours, $idGroupeCours))
							$checked = "checked = \"checked\"";
						else
							$checked = "";
								
						echo $tab."\t\t<td class=\"".$couleurFond."\"><input type=\"checkbox\" name= \"{$nom_case}\" value=\"{$nom_case}\" onclick=\"appartenance_cours_groupeCours({$idCours},{$idGroupeCours},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
						
					}
					echo $tab."\t</tr>\n";
				}
				
				echo $tab."\t<tr>\n";
				echo $tab."\t<th class=\"fondGrisFonce\" colspan='2'>Tous les cours</th>\n";
				foreach ($listeGroupeCours as $idGroupeCours) {
					$_GroupeCours = new Groupe_Cours($idGroupeCours);

					$nom_case = "case_promotion_".$idGroupeCours;
					if ($nbCours == Appartient_Cours_GroupeCours::appartenance_promotion_groupe_Cours($idGroupeCours))
						$checked = "checked = \"checked\"";
					else
						$checked = "";		
						
					echo $tab."\t\t<td class=\"fondGrisFonce\"><input type=\"checkbox\" name= \"{$nom_case}\" value=\"{$nom_case}\" onclick=\"appartenance_promotion_groupeCours({$idPromotion},{$idGroupeCours},{$nbCours},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
					
				}
				echo $tab."\t</tr>\n";
				echo $tab."</table>\n";
			}
		}
		
		/**
		 * Test de l'existence du lien entre le cours et le groupe de cours
		 * @param $idCours : int idCours
		 * @param $idGroupeCours : int idGroupeCours
		 * @return appartenance : 1 si le lien existe, 0 sinon
		 */
		public function appartenance_cours_groupeCours($idCours, $idGroupeCours) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM ".Appartient_Cours_GroupeCours::$nomTable." WHERE idCours=? AND idGroupeCours=?");
				$req->execute(
					array(
						$idCours,
						$idGroupeCours
					)
				);
				$ligne = $req->fetch();
				
				$appartenance = $ligne["nb"];
				$req->closeCursor();
				
				return $appartenance;
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Test de l'existence du lien entre le groupe de cours et tous les cours de la promotion
		 * @param $idGroupeCours : int idGroupeCours
		 * @return appartenance : nombre de lien correspondant au groupe de cours
		 */
		public function appartenance_promotion_groupe_Cours($idGroupeCours) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM ".Appartient_Cours_GroupeCours::$nomTable." WHERE idGroupeCours=?");
				$req->execute(
					array(
						$idGroupeCours
					)
				);
				$ligne = $req->fetch();
				
				$appartenance = $ligne["nb"];
				$req->closeCursor();
				
				return $appartenance;
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
	}
