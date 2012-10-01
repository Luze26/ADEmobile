<?php
include("simple_html_dom.php");
include("login.php");

//Récupère le "chemin"
$resource=strip_tags($_GET['resource']);
//Récupère la suite de branch
$branchsId=Array();
$branchId=strip_tags($_GET['branchId0']);
$i=0;
while(!empty($branchId)) {
	$branchsId[$i]=$branchId;
	$i++;
	$branchId=strip_tags($_GET['branchId'.$i]);
}


//Récup ou connection à ADE
session_start();

if(!isset($_SESSION['adeId']))
	$_SESSION['adeId']=login();

$session=$_SESSION['adeId'];


$ch = curl_init();
curl_setopt($ch,CURLOPT_COOKIE, "JSESSIONID=$session;");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//Récupération de la liste des ressources
curl_setopt($ch,CURLOPT_URL, "http://ade52-ujf.grenet.fr/ade/standard/gui/tree.jsp?category=$resource");
$output=curl_exec($ch);
if(curl_getinfo($ch, CURLINFO_HTTP_CODE)!=200) {
	$_SESSION['adeId']=login();
	$output=curl_exec($ch);
}
//Récupération des branchs jusqu'à la dernière voulue
for($i=0; $i<sizeof($branchsId); $i++) {
	curl_setopt($ch,CURLOPT_URL, "http://ade52-ujf.grenet.fr/ade/standard/gui/tree.jsp?branchId=$branchsId[$i]");
	$output=curl_exec($ch);
	if(curl_getinfo($ch, CURLINFO_HTTP_CODE)!=200) {
		$_SESSION['adeId']=login();
		$output=curl_exec($ch);
	}
}

curl_close($ch);

$resources = Array();
$html = str_get_html($output);
$liens = $html->find('a');
$nbsp = str_repeat("&nbsp;", $i*3);
foreach($liens as $l) {  
	if(preg_match("/\((.+?), 'true/", $l->href, $match)) {
		if(substr($l->parent()->parent()->innertext, 0, $i*18)==$nbsp)
			$resources[$match[1]]=$l->innertext;
	}	
}

echo json_encode($resources);
?>