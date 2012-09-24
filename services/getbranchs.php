<?php
include("simple_html_dom.php");
include("login.php");

$resource=strip_tags($_GET['resource']);
$branchsId=Array();
$branchId=strip_tags($_GET['branchId']);
$i=0;
while(!empty($branchId)) {
	$branchsId[$i]=$branchId;
	$i++;
	$branchId=strip_tags($_GET['branchId'+$i]);
}

session_id(3010);
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
for($i=0; $i<sizeof($branchsId); $i++) {
	curl_setopt($ch,CURLOPT_URL, "http://ade52-ujf.grenet.fr/ade/standard/gui/tree.jsp?branchId=$branchsId[$i]");
	$output=curl_exec($ch);
	if(curl_getinfo($ch, CURLINFO_HTTP_CODE)!=200) {
		$_SESSION['adeId']=login();
		$output=curl_exec($ch);
	}
}

 echo $output;
curl_close($ch);
$resources = Array();
$html = str_get_html($output);
$liens = $html->find('a');
foreach($liens as $l) {
	if(preg_match("/\((.+?), 'true/", $l->href, $match))
		$resources[$match[1]]=$l->innertext;
}

echo json_encode($resources);
?>