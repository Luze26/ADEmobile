<?php 
function login() {
	//Page de login
	$chl = curl_init(); 
	curl_setopt($chl, CURLOPT_URL, "http://ade52-ujf.grenet.fr/ade/standard/index.jsp");
	curl_setopt($chl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($chl, CURLOPT_HEADER, 1);
	$output = curl_exec($chl); 
	//Récupération de l'id de session
	preg_match('/^Set-Cookie: (.*?);/m', $output, $m);
	$id=strstr($m[1], "=");
	$arr = Array();
	for($i=0;$i<strlen($id);$i++){
	$arr[$i] = substr($id,$i,1);
	}
	array_shift($arr);
	$session= implode($arr);

	//Submit le formulaire
	$url = 'http://ade52-ujf.grenet.fr/ade/gui/interface.jsp';
	$fields = array(
		'login' => urlencode("voirIMA"),
		'password' => urlencode("ima")
		);
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');
	curl_setopt($chl,CURLOPT_COOKIE, "JSESSIONID=$session;");
	curl_setopt($chl,CURLOPT_URL, $url);
	curl_setopt($chl,CURLOPT_POST, count($fields));
	curl_setopt($chl,CURLOPT_POSTFIELDS, $fields_string);
	$result = curl_exec($chl);
	curl_close($chl);

	$chl = curl_init();
	curl_setopt($chl,CURLOPT_COOKIE, "JSESSIONID=$session;");
	curl_setopt($chl,CURLOPT_URL, "http://ade52-ujf.grenet.fr/ade/standard/redirectProjects.jsp");
	curl_setopt($chl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($chl, CURLOPT_HEADER, 1);
	curl_exec($chl);
	curl_close($chl);

	return $session;
}
?>