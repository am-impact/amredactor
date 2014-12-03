if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.amredactor = function() {
    return {
        init: function() {
            var isAdmin = $('#header-actions .settings').length;
            this.button.addFirst('redo', 'Redo', function() {
                this.execCommand('redo');
            });
            this.button.addFirst('undo', 'Undo', function() {
               this.execCommand('undo');
            });
            if (isAdmin) {
                this.button.addFirst('html', 'HTML', this.toggle);
            }
        }
    }
};