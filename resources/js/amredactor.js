if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.amredactor = function() {
    return {
        init: function() {
            var isAdmin = $('#header-actions .settings').length;
            var button = this.button.addFirst('redo', 'Redo');
            this.button.addCallback(button, function() {
                this.buffer.redo();
            });
            var button = this.button.addFirst('undo', 'Undo');
            this.button.addCallback(button, function() {
               this.buffer.undo();
            });
            if (isAdmin) {
                var button = this.button.addFirst('html', 'HTML');
                this.button.addCallback(button, function() {
                    this.code.toggle();
                });
            }
        }
    }
};