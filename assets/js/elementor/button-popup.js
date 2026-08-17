(function ($) {
    "use strict";

    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/vonaco-button-popup.default', ( $scope ) => {
            if ($scope.find('.vonaco-button-popup a.elementor-button').length > 0) {
                $scope.find('.vonaco-button-popup a.elementor-button').magnificPopup({
                    type: 'inline',
                    removalDelay: 500,
                    closeBtnInside: true,
                    callbacks: {
                        beforeOpen: function() {
                            this.st.mainClass = this.st.el.attr('data-effect');
                        }
                    },
                    midClick: true
                });
            }
        } );
    });

})(jQuery);