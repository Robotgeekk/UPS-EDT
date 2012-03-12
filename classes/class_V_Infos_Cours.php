<?php
	class V_Infos_Cours{
		
		public static $nomTable = "V_Infos_Cours";
		
		public static $attributs = Array(
			"nomUE",
			"prenomIntervenant",
			"nomIntervenant",
			"nomTypeCours",
			"tsDebut",
			"tsFin",
			"nomSalle",
			"nomBatiment"
		);
		
		public function getId(){return $this->id;}
		public function getNomUE(){return $this->nomUE;}
		public function getNomSalle(){return $this->nomSalle;}
		public function getNomTypeCours(){return $this->nomTypeCours;}
		public function getNomBatiment(){return $this->nomBatiment;}
		
		public function V_Infos_Cours($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".V_Infos_Cours::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(V_Infos_Cours::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function liste_cours($idPromotion){
			$listeId = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".V_Infos_Cours::$nomTable." WHERE idPromotion=? ORDER BY tsDebut");
				$req->execute(
					Array($idPromotion)
				);
				while($ligne = $req->fetch()){
					array_push($listeId, $ligne['id']);
				}
				$req->closeCursor();
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeId;
		}
		
		public function getNbreCours($idPromotion) { 
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".V_Infos_Cours::$nomTable." WHERE idPromotion = ?");
				$req->execute(
					Array($idPromotion)
				);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne["nb"];
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function getHeureDebut(){
			$explode = explode(" ",$this->tsDebut);
			$heureDebut = $explode[1];
			$explode = explode(":",$heureDebut);
			$heureDebut = "$explode[0]:$explode[1]";
			return $heureDebut;
		}
		
		public function getHeureFin(){
			$explode = explode(" ",$this->tsFin);
			$heureFin = $explode[1];
			$explode = explode(":",$heureFin);
			$heureFin = "$explode[0]:$explode[1]";
			return $heureFin;
		}
		
		public function commence_a_heure($heure){
			$explode = explode(" ",$this->tsDebut);
			$heureDebut = $explode[1];
			return ($heure == $heureDebut);
		}
		
		public function nbQuartsHeure(){
			$time = (strtotime($this->tsFin) - strtotime($this->tsDebut)) / 900;
			return $time;
		}
		
		public function toString(){
			$string = "";
			foreach(V_Infos_Cours::$attributs as $att){
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
	}
