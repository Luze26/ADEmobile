var serviceURL = "http://depottest2.comoj.com/ade/services/";

$('#branchsListPage').live('pageshow', function(event) {
	/=([^&]+)/.exec(window.location.hash);
	getBranchsList();
});

function getBranchsList() {
	var resource=RegExp.$1;
	var branchsUrl = "";
	var $i = 2;
	while(RegExp["$" + $i]) {
		branchsUrl += "&branchId"+($i-2)+"="+RegExp["$" + $i];
		$i++;
	}
	
	$('#branchslist li').remove();
	$.getJSON(serviceURL + 'getbranchs.php?resource=' + resource + branchsUrl, function(branchs) {
		$.each(branchs, function(index, branch) {
			$('#branchslist').append('<li><a href="branch.html?resource'+resource + branchsUrl + '&branchId'+($i-2)+'='+index+'">'+branch+'</a></li>');
		});
		$('#branchslist').listview('refresh');
	});
}