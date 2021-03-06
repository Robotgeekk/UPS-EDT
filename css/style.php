<?php
	header("Content-type: text/css");
	
	include_once('../includes/infos_bdd.php');
	include_once('../classes/class_Type_Cours.php');
	include_once('../classes/class_Options.php');
	
	include_once('./emploi_du_temps.css');
	include_once('./administration.css');
?>

html, body{
	margin:0;
	padding:0;
	font-family : "Times New Roman";
}

h1, h2, h3, h4, h5, h6, ul, ol, li{
	margin:0;
	padding:0;
}

p, table, ul, input, select{
	font-size:12px;
}

div#pageConnexion{
	margin:10px auto 10px auto;
	padding:5px;
	background-color:#E6DFDC;
	font-family: arial, sans-serif;
	font-size: 0.8em;
	width:665px;
}

div#pageConnexion h1{
	margin-top:10px;
	margin-bottom:10px;
	color:#194B69;
	font-size: 1.5em;
	text-align:center;
	font-weight:normal;
	}

div#pageConnexion form#formulaireConnexion{
	margin: 0 auto 0 auto;
	width:500px;
}

div#pageConnexion form#formulaireConnexion div#imageLogoConnexion{
	text-align:center;
}

div#pageConnexion form#formulaireConnexion div#imageLogoConnexion img{
	max-width:200px;
}

div#pageConnexion form#formulaireConnexion div#indications{
	text-align:center;
}

div#pageConnexion form#formulaireConnexion fieldset{
	color:#194B69;
}

div#pageConnexion form#formulaireConnexion table{
	padding:0;
	margin:0 auto 0 auto;
}

div#pageConnexion div#bas{
	text-align:center;
	}
	
div#pageConnexion div#bas ul{
	list-style-type:none;
	}

table#edt_semaine{
	border-collapse:collapse;
	border: black 1px solid;
	font-size: 8pt;
}

table#edt_semaine tr{
	border-top-style:solid;
	border-bottom-style:solid;
	border-width:1px;
	border-color:black;
}

table#edt_semaine th, table#edt_semaine td{
	height:50px;
	width:150px;
	padding:0;
	margin:0;
	text-align:center;
}

tr.fondBlanc, th.fondBlanc, td.fondBlanc{
	background-color:white;
}

tr.fondGris, th.fondGris, td.fondGris{
	background-color:#cfcfcf;
}

tr.fondGrisFonce, th.fondGrisFonce, td.fondGrisFonce{
	background-color:#b3b3b3;
}

<?php
	foreach (Type_Cours::liste_nom_type_cours() as $nom) {
		echo "table#edt_semaine td.".$nom."{\n";
		echo "\tbackground-color: ".Options::valeur_from_nom("background_color_".$nom."").";\n";
		echo "\tcolor: ".Options::valeur_from_nom("color_".$nom."").";\n";
		echo "\tborder: black 1px solid;\n";
		echo "}\n\n";
	}
	
	foreach (Options::toutes_valeurs_distinct() as $valeur) {
		$class = "bg".substr($valeur, 1);
		echo "table#administration_style_typesCours tr td.".$class."{\n";
		echo "\tbackground-color: ".$valeur.";\n";
		echo "\tborder: black 1px solid;\n";
		echo "\twidth:50px;";
		echo "}\n\n";
	}
?>

table#edt_semaine td.heurePaire{
	background-color:#e9e9e9;
	}
	
table#edt_semaine td.heureImpaire{
	background-color:#f3f3f3;
	}

ul.erreurConnexion{
	list-style-type:none;
	font-weight:bold;
	color:red;
}
