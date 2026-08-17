(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/vonaco-tabs.default', ($scope) => {

            let $tabs = $scope.find('.elementor-tabs-wrapper');
            let $contents = $scope.find('.elementor-tabs-content-wrapper');
            $contents.find('.elementor-tab-content').hide();
            // Active tab
            $contents.find('.elementor-active').show();

            $tabs.find('.elementor-tab-title').on('click', function () {
                $tabs.find('.elementor-tab-title').removeClass('elementor-active');
                $contents.find('.elementor-tab-content').removeClass('elementor-active').hide();
                $(this).addClass('elementor-active');
                let id = $(this).attr('aria-controls');
                $contents.find('#' + id).addClass('elementor-active').show();
            });

        });
    });


})(jQuery);
