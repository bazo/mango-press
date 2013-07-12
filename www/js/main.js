requirejs.config({
	baseUrl: '/js/',
	paths: {
		jquery: '/vendor/jquery/jquery',
		bootstrap: 'bootstrap.min',
		"bootstrap.markdown": '/vendor/bootstrap-markdown/js/bootstrap-markdown',
		"bootstrap.tagmanager": 'bootstrap-tagmanager'
	},
	shim: {
		bootstrap: {
			deps: ["jquery"]
		},
		"bootstrap.tagmanager": {
			deps: ["jquery", "bootstrap"]
		},
		"bootstrap.markdown": {
			deps: ["jquery", "bootstrap"]
		}
	}
});

var dependencies = [
	"jquery",
	"bootstrap",
	"netteForms",
	"bootstrap.markdown",
	"bootstrap.tagmanager"
]

require(dependencies, function(jquery, bootstrap, netteForms, bootstrapMarkdown, tagmanager) {
	main();
}
);

function main() {
	$.each(q, function(index, f) {
		$(f);
	});

}
;