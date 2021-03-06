<?php
include("simple_html_dom.php");
include("login.php");

session_start();

//Si client pas connecté à ADE on se connecte
if(!isset($_SESSION['adeId']))
	$_SESSION['adeId']=login();

$session=$_SESSION['adeId'];

$ch = curl_init();
curl_setopt($ch,CURLOPT_COOKIE, "JSESSIONID=$session;");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//Récupération de la liste des ressources
curl_setopt($ch,CURLOPT_URL, "http://ade52-ujf.grenet.fr/ade/standard/gui/tree.jsp?projectId=6");
$output=curl_exec($ch);
//Si on se fait rediriger, plus connecté on reconnecte et renvoi la requête
if(curl_getinfo($ch, CURLINFO_HTTP_CODE)!=200) {
	$_SESSION['adeId']=login();
	$output=curl_exec($ch);
}
curl_close($ch);

//Construction de la liste des resources "lien":"nom"
$resources = Array();
$html = str_get_html($output);
$liens = $html->find('a');
foreach($liens as $l) {
	if(preg_match("/javascript:checkCategory\('(.+?)'\)/", $l->href, $match))
		$resources[$match[1]]=$l->innertext;
}

//Affichage sous format JSON
echo json_encode($resources);
?>