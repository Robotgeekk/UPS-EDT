<?php

// Informations de base de données
include_once('../../includes/infos_bdd.php');
include_once("../../classes/class_V_Infos_Etudiant.php");
include_once("../../classes/class_UE.php");


if (isset($_POST['appartient']) && isset($_POST['type'])) {

	$appartient = $_POST['appartient'];
	$type = $_POST['type'];
	
	if ($type == 'etudiant') {	
	
		if (isset($_POST['idEtudiant']) && isset($_POST['idUE'])) {

			$idEtudiant = $_POST['idEtudiant'];
			$idUE = $_POST['idUE'];
			if ((V_Infos_Etudiant::existe_etudiant($idEtudiant)) && (UE::existe_UE($idUE))) { //Test de sécurité
				if ($appartient == 1) { //Ajout du lien dans la table Inscription
					try {
						$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
						$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
						$bdd->query("SET NAMES utf8");
						$req = $bdd->prepare("INSERT INTO Inscription VALUES(?, ?)");
						
						$req->execute(
							Array(					
								$idUE,
								$idEtudiant
							)
						);			
					}
					catch (Exception $e) {
						echo "Erreur : ".$e->getMessage()."<br />";
					}	
				}
				else { //Suppression du lien dans la table Inscription
					try {
						$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
						$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
						$bdd->query("SET NAMES utf8");
						$req = $bdd->prepare("DELETE FROM Inscription WHERE idUE = ? AND idEtudiant=?;");
						$req->execute(
							Array(
								$idUE,
								$idEtudiant
							)
						);
					}
					catch (Exception $e) {
						echo "Erreur : ".$e->getMessage()."<br />";
					}	
				}
			}
			else
				echo 0;
		}
	}
	else if ($type == 'promotion') {
	
		if (isset($_POST['idUE']) && isset($_POST['idPromotion'])) {

			$idUE = $_POST['idUE'];
			
			if (UE::existe_UE($idUE))  { //Test de sécurité
				$idPromotion = $_POST['idPromotion'];
				$liste_etudiants = V_Infos_Etudiant::liste_etudiant($idPromotion);
				
				foreach ($liste_etudiants as $idEtudiant) {
				
					try {
						$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
						$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
						$bdd->query("SET NAMES utf8");
						$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM Inscription WHERE idUE = ? AND idEtudiant=?;");
						$req->execute(
							Array(
								$idUE,
								$idEtudiant
							)
						);
						$ligne = $req->fetch();
						$req->closeCursor();
						
						$nb = $ligne["nb"];
						
						if ($appartient == 1) { //Ajout du lien dans la table Inscription
							if ($nb == 0) { 					
								try {
									$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
									$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
									$bdd->query("SET NAMES utf8");
									$req = $bdd->prepare("INSERT INTO Inscription VALUES(?, ?)");
									
									$req->execute(
										Array(					
											$idUE,
											$idEtudiant
										)
									);			
								}
								catch (Exception $e) {
									echo "Erreur : ".$e->getMessage()."<br />";
								}	
							}					
						}
						else { //Suppression du lien dans la table Inscription
							if ($nb == 1) { 					
								try {
									$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
									$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
									$bdd->query("SET NAMES utf8");
									$req = $bdd->prepare("DELETE FROM Inscription WHERE idUE = ? AND idEtudiant=?;");
									$req->execute(
										Array(
											$idUE,
											$idEtudiant
										)
									);
								}
								catch (Exception $e) {
									echo "Erreur : ".$e->getMessage()."<br />";
								}
							}	
						}
					}
					catch (Exception $e) {
						echo "Erreur : ".$e->getMessage()."<br />";
					}				
				}
			}
			else
				echo 0;
		}
	}
}
?>



