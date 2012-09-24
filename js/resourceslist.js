var serviceURL = "http://depottest2.comoj.com/ade/services/";

$('#resourcesListPage').bind('pagebeforeshow', function(event) {
	getResourcesList();
});

function getResourcesList() {
	$('#resourceslist li').remove();
	$.getJSON(serviceURL + 'getresources.php', function(resources) {
		$.each(resources, function(index, resource) {
			$('#resourceslist').append('<li><a href="branch.html?resource='+index+'">'+resource+'</a></li>');
		});
		$('#resourceslist').listview('refresh');
	});
}