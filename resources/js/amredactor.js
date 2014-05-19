if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.amredactor = {
	init: function() {
        this.buttonAddFirst('html', 'HTML', this.toggle);
    }
};