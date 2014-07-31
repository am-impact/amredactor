if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.amredactor = {
	init: function() {
        var isAdmin = $('#header-actions .settings').length;
        this.buttonAddFirst('redo', 'Redo', function() {
            this.execCommand('redo');
        });
        this.buttonAddFirst('undo', 'Undo', function() {
           this.execCommand('undo');
        });
        if (isAdmin) {
            this.buttonAddFirst('html', 'HTML', this.toggle);
        }
    }
};