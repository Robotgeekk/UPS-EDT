<?php
	class Mail{
		
		// A TESTER
		
		public static function array_email_to_string($array){
			$nombreEmails = sizeof($array);
			$string = "";
			for($i = 0 ; $i < $nombreEmails ; $i++){
				$string .= $array[$i];
				if($i != ($nombreEmails -1)){
					$string .= ", ";
				}
			}
			return $string;
		}
		
		public static function envoyer_email($sujet, $contenu, $expediteur, $destinataires, $cc_destinataires, $bcc_destinataires){
			$to = Mail::array_email_to_string($destinataires);
			$cc = Mail::array_email_to_string($cc_destinataires);
			$bcc = Mail::array_email_to_string($bcc_destinataires);
			
			$subject = $sujet;
			$headers = "From: $expediteur\r\n".
					   "Cc: $cc\r\n".
					   "Bcc: $bcc\r\n".
					   "Reply-To: $expediteur\r\n".
					   "X-Mailer: PHP/".phpversion()."\r\n".
					   "Content-Type: text/plain; charset=\"UTF-8\"\r\n"; 
			$message = $contenu;
			echo "to :\n$to<br />";
			echo "subject :$sujet<br />";
			echo "headers :$headers<br />";
			echo "message :$message<br />";
			
			return mail($to, $subject, $message, $headers);
		}
		
		public static function envoyer_creation_utilisateur($Utilisateur, $mot_de_passe){
			switch($Utilisateur->getType()){
				case "Etudiant":
					$Destinataire = new Etudiant($Utilisateur->getIdCorrespondant());
					break;
				case "Intervenant":
					$Destinataire = new Intervenant($Utilisateur->getIdCorrespondant());
					break;
			}
			
			$sujet = "UPS-EDT - Connexion";
			$destinataires = Array($Destinataire->getEmail());
			$cc_destinataires = Array();
			$bcc_destinataires = Array();
			$message = "Bonjour, \r\n\r\n".
					   "Votre compte UPS-EDT à été créé / modifié\r\n".
					   "Votre login : {$Destinataire->getLogin()}\r\n".
					   "Votre mot de passe : $mot_de_passe\r\n" ;
			return envoyer_email($sujet, $message, $destinataires, $cc_destinataires, $bcc_destinataires);			
		}
		
		public static function envoyer_modification_motDePasse_utilisateur($Utilisateur, $mot_de_passe){
			switch($Utilisateur->getType()){
				case "Etudiant":
					$Destinataire = new Etudiant($Utilisateur->getIdCorrespondant());
					break;
				case "Intervenant":
					$Destinataire = new Intervenant($Utilisateur->getIdCorrespondant());
					break;
			}
			
			$sujet = "Modification mot de passe Utilisateur UPS-EDT";
			$destinataires = Array($Destinataire->getEmail());
			$cc_destinataires = Array();
			$bcc_destinataires = Array();
			$message = "Bonjour, \r\n\r\n".
					   "Votre mot de passe UPS-EDT à été modifié : \r\n".
					   "Votre login : {$Destinataire->getLogin()}\r\n".
					   "Votre mot de passe : $mot_de_passe\r\n";
			return envoyer_email($sujet, $message, $destinataires, $cc_destinataires, $bcc_destinataires);			
		}
	}