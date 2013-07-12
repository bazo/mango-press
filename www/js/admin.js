requirejs.config({
	//By default load any module IDs from js/lib
	baseUrl: '/js/',
	paths: {
		vendor: '/vendor'
	}
});

var dependencies = [
	"vendor/jquery/jquery", 
		"bootstrap.min", 
		"netteForms", 
		"moment.min", 
		"livestamp.min", 
		"nl2br", 
		"underscore.string", 
		"vendor/bootstrap-markdown/js/bootstrap-markdown",
		"bootstrap-tagmanager"
]

require(dependencies, function(jquery, bootstrap, netteForms, moment, livestamp, nl2br, _) {
		main();
	}
);

function main() {
	

}
;