if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.amredactor = function() {
    return {
        /**
         * Undo, redo, source
         */
        addDefaultButtons: function() {
            var self = this,
                buttonUndo = this.button.addBefore('format', 'undo', 'Undo'),
                buttonRedo = this.button.addBefore('format', 'redo', 'Redo');

            this.button.addCallback( buttonRedo, this.buffer.redo );
            this.button.addCallback( buttonUndo, this.buffer.undo );

            if (
                (typeof window.amredactorShowHtmlButton === 'object' && !Object.keys(window.amredactorShowHtmlButton).length) ||
                window.amredactorShowHtmlButton == 'n'
            ) {
                this.button.remove('html');
            }
        },

        /**
         * Add button to clear html
         */
        addClearButton: function() {
            var button = this.button.addBefore('bold', 'clear', 'Remove all styles');

            this.button.addCallback(button, function() {

                if( confirm('Are you sure that you want to remove all styles?') ) {

                   // If there is text selected format only this part of the text
                   if ( this.selection.getHtml().length > 0 ) {
                        var selectedHtml = this.selection.getHtml();
                        this.insert.text( this.clean.getPlainText(selectedHtml) ) ;
                    } else {
                        /**
                         * Add spaces at end of paragraphs, so content won't be glued to each other
                         */
                        var paragraphs = this.$editor[0].querySelectorAll('p');

                        // Loop through the paragraphs
                        for( var p = 0, plen = paragraphs.length; p < plen; p++ ) {
                            // Get the plain html from the paragraph
                            var paragraphText = this.clean.stripTags( paragraphs[p].innerHTML );

                            // When the last character of the paragraph isn't a space and when it isn't the last paragraph
                            // then add a space
                            if( paragraphText[ paragraphText.length - 1 ] !== ' ' && p < plen - 1 ) {
                                paragraphs[p].innerHTML = paragraphs[p].innerHTML += ' ';
                            }
                        }

                        /**
                        * this.content cannot be used because it doesn't update when typing
                        * So get the html with innerHTML
                        */
                        var newContent = this.clean.stripTags( this.$editor[0].innerHTML );

                        // Delete all newlines
                        newContent = newContent.replace( /\r?\n|\r/g, ' ' );

                        this.insert.set( newContent );
                    }
                }
            });
        },

        /**
         * Add styles dropdown
         */
        addStylesButton: function( classes ) {
            var self = this,
                dropdown = {},
                button = this.button.addBefore('bold', 'customstyles', 'Styles');

            jQuery.each(classes, function(ind, item) {
                dropdown[item.class] = { title: item.label, func: function() { self.amredactor.setStyle(item.class); }};
            });

            dropdown.remove = { title: 'Wis opmaak', func: self.amredactor.removeStyles };

            this.button.addDropdown(button, dropdown);
        },

        /**
         * Remove formatted styles
         */
        removeStyles: function() {
            this.inline.removeFormat();
        },

        /**
         * Add styles
         */
        setStyle: function(className) {
            this.inline.format('span', 'class', className);
        },

        /**
         * Init
         */
        init: function() {

            var self = this;

            self.amredactor.addDefaultButtons();
            self.amredactor.addClearButton();

            /**
             * 'amredactorClasses' is set from the config of the plugin
             */
            if( Object.keys(window.amredactorClasses).length > 0 ) {
                self.amredactor.addStylesButton( window.amredactorClasses );
            }
        }
    }
};
