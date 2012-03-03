<?php
	// Informations de base de données
	include_once('../includes/infos_bdd.php');
	
	$repertoire = opendir("../classes/");
	while($fichier = readdir($repertoire)){
		if($fichier != '..' && $fichier != '.'){
			include_once("../classes/$fichier");
		}
	}
	
	if(isset($_GET['idPromotion'])){
		$promotion_choisie = true;
		$idPromotion = $_GET['idPromotion'];
		if($idPromotion == 0){ // AJOUTER TEST SI EXISTANT
			header('Location: ./index.php');
		}
	}
	else{
		$promotion_choisie = false;
	}
	
	Option::test_validation_formulaire_administration();
?>
<!DOCTYPE html>
	<head>
		<title>Administration</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="../css/style.php" />
		<script type="text/javascript" src="../js/prototype.js?v=<?php echo filemtime("../js/prototype.js");?>"></script>	
		<script type="text/javascript" src="../js/gestionPromotion.js?v=<?php echo filemtime("../js/gestionPromotion.js");?>"></script>
		<script type="text/javascript" src="../js/ajoutCours.js?v=<?php echo filemtime("../js/ajoutCours.js");?>"></script>
		<script type="text/javascript" src="../js/ajoutIntervenant.js?v=<?php echo filemtime("../js/ajoutIntervenant.js");?>"></script>
		<script type="text/javascript" src="../js/ajoutSalle.js?v=<?php echo filemtime("../js/ajoutSalle.js");?>"></script>	
		<script type="text/javascript" src="../js/gestionUtilisateurs.js?v=<?php echo filemtime("../js/gestionUtilisateurs.js");?>"></script>
		<script type="text/javascript" src="../js/gestionPublication.js?v=<?php echo filemtime("../js/gestionPublication.js");?>"></script>
		<script type="text/javascript" src="../js/inscriptionUE.js?v=<?php echo filemtime("../js/inscriptionUE.js");?>"></script>
	</head>
	<body>
		<div id="page_administration">
			<div id="page_administration_haut">
				<div id="page_administration_titre">
					<h1><a href="./index.php<?php if($promotion_choisie){ echo "?idPromotion=$idPromotion"; } ?>">Administration</a></h1>
				</div>
				<div id="barre_selection_promotion">
					<table>
						<tr>
							<td>Selection d'une promotion</td>
							<td>
		<?php 
			if($promotion_choisie){
				echo Promotion::liste_promotion_for_select($idPromotion); 
			}
			else{
				echo Promotion::liste_promotion_for_select(); 
			}
		?>
							</td>
							<td><a href="?page=ajoutPromotion" >Ajout d'une promotion</a></td>
						</tr>
					</table>
				</div>
			</div>
			<div id="page_administration_milieu">
<?php
	if($promotion_choisie){
		$promotion = $_GET['idPromotion'];
		include_once('./nav.php'); 
?>
				<section>
					<ul>
					<li>TOTAL EN COURS 80%</li>
					<li>
						Reste à faire : [6 à 9h] Fin Possible le 11 Mars (developpement) puis 18 Mars (tests + style)<br />
						- Ajouter la gestion de couleur à un type de cours [2h]<br />
						- Gestion des groupes d'étudiants [2 à 4h]<br />
						- Gestion des groupes de cours [2h à 3h]<br />				
						- TESTS [?]<br />
						- Modifier le CSS<br />
					</li>					
				</ul>
<?php
		if(isset($_GET['page'])){
			include_once("./pages/{$_GET['page']}.php");
		}
	}
	else if(isset($_GET['page']) && $_GET['page'] == "ajoutPromotion"){
		include_once("./pages/{$_GET['page']}.php");
	}
	else{
?>
					<p>Merci de choisir une promotion</p>
<?php
	}
?>
				</section>
			</div>
			<div id="page_administration_bas">
				<p>Manuel Administration</p>
			</div>
		</div>
	</body>
</html>
	