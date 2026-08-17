<?php

remove_action( 'elementor/widgets/wordpress/widget_args', 'dokan_depricated_elementor_store_widgets', 10, 2 );

add_filter('loop_shop_columns', function ($columns){
    if(is_product()){
        $columns = 4;
        $product_single_style = vonaco_get_theme_option('single_product_gallery_layout', 'horizontal');
        if( $product_single_style === 'gallery' || $product_single_style === 'sticky'){
            $columns = 2;
        }
    }
    return $columns;
});
