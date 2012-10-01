var serviceURL = "services/";

$('#branchsListPage').live('pageshow', function(event) {
	getBranchsList();
});

function getBranchsList() {
	//Récupération des paramètres
	/=([^&]+)/.exec(window.location.hash);
	var resource = RegExp.$1;		//La ressource
	var branchsUrl = RegExp.rightContext;		//Les branchs
	var match_branchs = branchsUrl.match(/=/g);	//Récup des branchs
	var nb_branchs = 0;	//Nombre de branchs
	if(match_branchs!=null)
		nb_branchs=match_branchs.length;

	$('#branchslist li').remove();
	$.getJSON(serviceURL + 'getbranchs.php?resource=' + resource + branchsUrl, function(branchs) {
		$.each(branchs, function(index, branch) {
			$('#branchslist').append('<li><a href="branch.html?resource='+resource + branchsUrl + '&branchId'+ nb_branchs +'='+index+'">'+branch+'</a></li>');
		});
		$('#branchslist').listview('refresh');
	});
}