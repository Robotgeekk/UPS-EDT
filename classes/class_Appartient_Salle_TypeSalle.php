<?php
	class Appartient_Salle_TypeSalle {
		
		public static $nomTable = "Appartient_Salle_TypeSalle";
		
		public static $attributs = Array(
			"idSalle",
			"idTypeSalle"
		);
		
		public function Appartient_Salle_TypeSalle() {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Appartient_Salle_TypeSalle::$nomTable);
				$req->execute();
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Appartient_Salle_TypeSalle::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function toString() {
			$string = "";
			foreach (Appartient_Salle_TypeSalle::$attributs as $att) {
				$string .= $att.":".$this->$att." ";
			}
			return $string;
		}
	}
