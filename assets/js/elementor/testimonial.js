(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/vonaco-testimonials.default', ($scope) => {
            let $carousel = $('.vonaco-carousel', $scope);
            let $currentItem = $('.current-item', $scope);
            if ($carousel.length > 0) {
                let data = $carousel.data('settings');
                $carousel.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
                    var i = (currentSlide ? currentSlide : 0) + 1;
                    $currentItem.text(i + '/' + slick.slideCount);
                });
                $carousel.slick(
                    {
                        dots: data.navigation == 'both' || data.navigation == 'dots' ? true : false,
                        arrows: data.navigation == 'both' || data.navigation == 'arrows' ? true : false,
                        infinite: data.loop,
                        speed: 300,
                        slidesToShow: parseInt(data.items),
                        autoplay: data.autoplay,
                        autoplaySpeed: data.autoplaySpeed,
                        slidesToScroll: 1,
                        lazyLoad: 'ondemand',
                        rtl: data.rtl,
                        responsive: [
                            {
                                breakpoint: parseInt(data.breakpoint_laptop),
                                settings: {
                                    slidesToShow: parseInt(data.items_laptop),
                                }
                            },
                            {
                                breakpoint: parseInt(data.breakpoint_tablet_extra),
                                settings: {
                                    slidesToShow: parseInt(data.items_tablet_extra),
                                }
                            },
                            {
                                breakpoint: parseInt(data.breakpoint_tablet),
                                settings: {
                                    slidesToShow: parseInt(data.items_tablet),
                                }
                            },
                            {
                                breakpoint: parseInt(data.breakpoint_mobile_extra),
                                settings: {
                                    slidesToShow: parseInt(data.items_mobile_extra),
                                }
                            },
                            {
                                breakpoint: parseInt(data.breakpoint_mobile),
                                settings: {
                                    slidesToShow: parseInt(data.items_mobile),
                                }
                            }
                        ]
                    }
                );
            }
        });
    });

})(jQuery);
