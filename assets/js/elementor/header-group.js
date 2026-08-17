(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/vonaco-header-group.default', ($scope) => {

            let $button_side = $scope.find('.site-header-button .button-content');

            let $button_closs = $('.button-side-heading .close-button-side');
            $('.button-side-overlay').on('click', function (e) {
                e.preventDefault();
                $(this).siblings('.header-button-canvas').removeClass('active');
            });
            $button_closs.on('click', function (e) {
                e.preventDefault();
                $button_closs.closest('.header-button-canvas').removeClass('active');
            });

            // Setup
            $button_side.on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                let $button_active = $(this).data('target');
                $($button_active).toggleClass('active');
            });
        });
    });

})(jQuery);


