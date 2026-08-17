<?php

defined( 'ABSPATH' ) || exit();

/**
 * Hook to delete post elementor related with this menu
 */
add_action( "before_delete_post", "vonaco_megamenu_on_delete_menu_item", 9 );
function vonaco_megamenu_on_delete_menu_item( $post_id ) {
	if( is_nav_menu_item($post_id) ){
		$related_id = vonaco_megamenu_get_post_related_menu( $post_id );
		if( $related_id ){
			wp_delete_post( $related_id, true );
		}
	}
}

add_filter( 'elementor/editor/footer', 'vonaco_megamenu_add_back_button_inspector' );
function vonaco_megamenu_add_back_button_inspector() {
	if ( ! isset( $_GET['vonaco-menu-editable'] ) || ! $_GET['vonaco-menu-editable'] ) {
		return;
	}
	?>
    <script type="text/template" id="tmpl-elementor-panel-footer-content">
        <div id="elementor-panel-footer-back-to-admin" class="elementor-panel-footer-tool elementor-leave-open tooltip-target" data-tooltip="<?php esc_attr_e( 'Back', 'vonaco' ); ?>">
            <i class="eicon-close" aria-hidden="true"></i>
        </div>
        <div id="elementor-panel-footer-settings" class="elementor-panel-footer-tool elementor-leave-open tooltip-target" data-tooltip="<?php esc_attr_e( 'Settings', 'vonaco' ); ?>">
            <i class="eicon-cog" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?php echo esc_html__( 'Page Settings', 'vonaco' ); ?></span>
        </div>
        <div id="elementor-panel-footer-navigator" class="elementor-panel-footer-tool tooltip-target" data-tooltip="<?php esc_attr_e( 'Navigator', 'vonaco' ); ?>">
            <i class="eicon-navigator" aria-hidden="true"></i>
            <span class="elementor-screen-only">
                    <?php echo esc_html__( 'Navigator', 'vonaco' ); ?>
                </span>
        </div>
        <div id="elementor-panel-footer-responsive" class="elementor-panel-footer-tool">
            <i class="eicon-device-desktop tooltip-target" aria-hidden="true" data-tooltip="<?php esc_attr_e( 'Responsive Mode', 'vonaco' ); ?>"></i>
            <span class="elementor-screen-only">
					<?php echo esc_html__( 'Responsive Mode', 'vonaco' ); ?>
				</span>
        </div>
        <div id="elementor-panel-footer-history" class="elementor-panel-footer-tool elementor-leave-open tooltip-target" data-tooltip="<?php esc_attr_e( 'History', 'vonaco' ); ?>">
            <i class="eicon-history" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?php echo esc_html__( 'History', 'vonaco' ); ?></span>
        </div>
        <div id="elementor-panel-footer-saver-publish" class="elementor-panel-footer-tool">
            <button id="elementor-panel-saver-button-publish" class="elementor-button elementor-button-success elementor-saver-disabled">
					<span class="elementor-state-icon">
						<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
					</span>
                <span id="elementor-panel-saver-button-publish-label">
						<?php echo esc_html__( 'Publish', 'vonaco' ); ?>
					</span>
            </button>
        </div>
        <div id="elementor-panel-saver-save-options" class="elementor-panel-footer-tool" >
            <button id="elementor-panel-saver-button-save-options" class="elementor-button elementor-button-success tooltip-target elementor-disabled" data-tooltip="<?php esc_attr_e( 'Save Options', 'vonaco' ); ?>">
                <i class="eicon-caret-up" aria-hidden="true"></i>
                <span class="elementor-screen-only"><?php echo esc_html__( 'Save Options', 'vonaco' ); ?></span>
            </button>
        </div>
    </script>

	<?php
}

add_action( 'wp_ajax_vonaco_load_menu_data', 'vonaco_megamenu_load_menu_data' );
function vonaco_megamenu_load_menu_data() {
	$nonce = ! empty( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
	$menu_id = ! empty( $_POST['menu_id'] ) ? absint( $_POST['menu_id'] ) : false;
	if ( ! wp_verify_nonce( $nonce, 'vonaco-menu-data-nonce' ) || ! $menu_id ) {
		wp_send_json( array(
				'message' => esc_html__( 'Access denied', 'vonaco' )
			) );
	}

	$data =  vonaco_megamenu_get_item_data( $menu_id );

	$data = $data ? $data : array();
	if( isset($_POST['istop']) && absint($_POST['istop']) == 1  ){
		if ( class_exists( 'Elementor\Plugin' ) ) {
			if( isset($data['enabled']) && $data['enabled'] ){
				$related_id = vonaco_megamenu_get_post_related_menu( $menu_id );
				if ( ! $related_id  ) {
					vonaco_megamenu_create_related_post( $menu_id );
					$related_id = vonaco_megamenu_get_post_related_menu( $menu_id );
				}

				if ( $related_id && isset($_REQUEST['menu_id']) && is_admin() ) {
					$url = Elementor\Plugin::instance()->documents->get( $related_id )->get_edit_url();
					$data['edit_submenu_url'] = add_query_arg( array( 'vonaco-menu-editable' => 1 ), $url );
				}
			} else {
				$url = admin_url();
				$data['edit_submenu_url'] = add_query_arg( array( 'vonaco-menu-createable' => 1, 'menu_id' => $menu_id ), $url );
			}
		}
	}

	$results = apply_filters( 'vonaco_menu_settings_data', array(
			'status' => true,
			'data' => $data
	) );

	wp_send_json( $results );

}

add_action( 'wp_ajax_vonaco_update_menu_item_data', 'vonaco_megamenu_update_menu_item_data' );
function vonaco_megamenu_update_menu_item_data() {
	$nonce = ! empty( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
	if ( ! wp_verify_nonce( $nonce, 'vonaco-update-menu-item' ) ) {
		wp_send_json( array(
				'message' => esc_html__( 'Access denied', 'vonaco' )
			) );
	}

	$settings = ! empty( $_POST['vonaco-menu-item'] ) ? ($_POST['vonaco-menu-item']) : array();
	$menu_id = ! empty( $_POST['menu_id'] ) ? absint( $_POST['menu_id'] ) : false;

	do_action( 'vonaco_before_update_menu_settings', $settings );


	vonaco_megamenu_update_item_data( $menu_id, $settings );

	do_action( 'vonaco_menu_settings_updated', $settings );
	wp_send_json( array( 'status' => true ) );
}

add_action( 'admin_footer', 'vonaco_megamenu_underscore_template' );
function vonaco_megamenu_underscore_template() {
	global $pagenow;
	if ( $pagenow === 'nav-menus.php' ) { ?>
		<script type="text/html" id="tpl-vonaco-menu-item-modal">
			<div id="vonaco-modal" class="vonaco-modal">
                <div class="vonaco-modal-overlay"></div>
				<div id="vonaco-modal-body" class="<%= data.edit_submenu === true ? 'edit-menu-active' : ( data.is_loading ? 'loading' : '' ) %>">
					<% if ( data.edit_submenu !== true && data.is_loading !== true ) { %>
						<form id="menu-edit-form">
					<% } %>
						<div class="vonaco-modal-content">
							<% if ( data.edit_submenu === true ) { %>
								<iframe src="<%= data.edit_submenu_url %>" />
							<% } else if ( data.is_loading === true ) { %>
								<i class="fa fa-spin fa-spinner"></i>
							<% } else { %>

							<div class="form-group toggle-select-setting">
								<label for="icon"><?php esc_html_e( 'Icon', 'vonaco' ) ?></label>
								<select id="icon" name="vonaco-menu-item[icon]" class="form-control icon-picker vonaco-input-switcher vonaco-input-switcher-true" data-target=".icon-custom">
									<option value=""<%= data.icon == '' ? ' selected' : '' %>><?php echo esc_html__( 'No Use', 'vonaco' ) ?></option>
									<option value="1"<%= data.icon == 1 ? ' selected' : '' %>><?php echo esc_html__( 'Custom Class', 'vonaco' ) ?></option>
									<?php foreach ( vonaco_megamenu_get_fontawesome_icons() as $value ) : ?>
										<option value="<?php echo 'vonaco-icon-'.esc_attr( $value ) ?>"<%= data.icon == '<?php echo 'vonaco-icon-'.esc_attr( $value ) ?>' ? ' selected' : '' %>><?php echo esc_attr( $value ) ?></option>
									<?php endforeach ?>
								</select>
							</div>
							<div class="form-group icon-custom toggle-select-setting elementor-hidden">
								<label for="icon-custom"><?php esc_html_e( 'Icon Class Name', 'vonaco' ) ?></label>
								<input type="text" name="vonaco-menu-item[icon-custom]" class="input" id="icon-custom"/>
							</div>
							<div class="form-group">
								<label for="icon_color"><?php esc_html_e( 'Icon Color', 'vonaco' ) ?></label>
								<input class="color-picker" name="vonaco-menu-item[icon_color]" value="<%= data.icon_color %>" id="icon_color" />
							</div>

								<div class="form-group submenu-setting toggle-select-setting">
									<label><?php esc_html_e( 'Mega Submenu Enabled', 'vonaco' ) ?></label>
									<select name="vonaco-menu-item[enabled]" class="vonaco-input-switcher vonaco-input-switcher-true" data-target=".submenu-width-setting">
										<option value="1" <%= data.enabled == 1? 'selected':'' %>> <?php esc_html_e( 'Yes', 'vonaco' ) ?></opttion>
										<option value="0" <%= data.enabled == 0? 'selected':'' %>><?php esc_html_e( 'No', 'vonaco' ) ?></opttion>
									</select>
									<button id="edit-megamenu" class="button button-primary button-large">
										<?php esc_html_e( 'Edit Megamenu Submenu', 'vonaco' ) ?>
									</button>
								</div>

								<div class="form-group submenu-width-setting toggle-select-setting elementor-hidden">
									<label><?php esc_html_e( 'Sub Megamenu Width', 'vonaco' ) ?></label>
									<select name="vonaco-menu-item[customwidth]" class="vonaco-input-switcher vonaco-input-switcher-true" data-target=".submenu-subwidth-setting">
                                        <option value="1" <%= data.customwidth == 1? 'selected':'' %>> <?php esc_html_e( 'Yes', 'vonaco' ) ?></opttion>
                                        <option value="0" <%= data.customwidth == 0? 'selected':'' %>><?php esc_html_e( 'Full Width', 'vonaco' ) ?></opttion>
                                        <option value="2" <%= data.customwidth == 2? 'selected':'' %>><?php esc_html_e( 'Stretch Width', 'vonaco' ) ?></opttion>
                                        <option value="3" <%= data.customwidth == 3? 'selected':'' %>><?php esc_html_e( 'Container Width', 'vonaco' ) ?></opttion>
									</select>
								</div>

								<div class="form-group submenu-width-setting submenu-subwidth-setting toggle-select-setting elementor-hidden">
									<label for="menu_subwidth"><?php esc_html_e( 'Sub Mega Menu Max Width', 'vonaco' ) ?></label>
									<input type="text" name="vonaco-menu-item[subwidth]" value="<%= data.subwidth?data.subwidth:'600' %>" class="input" id="menu_subwidth" />
									<span class="unit">px</span>
								</div>

                                <div class="form-group submenu-width-setting submenu-subwidth-setting toggle-select-setting elementor-hidden">
                                    <label><?php esc_html_e( 'Sub Mega Menu Position Left', 'vonaco' ) ?></label>
                                    <select name="vonaco-menu-item[menuposition]">
                                        <option value="0" <%= data.menuposition == 0? 'selected':'' %>><?php esc_html_e( 'No', 'vonaco' ) ?></opttion>
                                        <option value="1" <%= data.menuposition == 1? 'selected':'' %>> <?php esc_html_e( 'Yes', 'vonaco' ) ?></opttion>
                                    </select>
                                </div>

							<% } %>
						</div>
						<% if ( data.is_loading !== true && data.edit_submenu !== true ) { %>
							<div class="vonaco-modal-footer">
								<a href="#" class="close button"><%= vonaco_memgamnu_params.i18n.close %></a>
								<?php wp_nonce_field( 'vonaco-update-menu-item', 'nonce' ) ?>
								<input name="menu_id" value="<%= data.menu_id %>" type="hidden" />
								<button type="submit" class="button button-primary button-large menu-save pull-right"><%= vonaco_memgamnu_params.i18n.submit %></button>
							</div>
						<% } %>
					<% if ( data.edit_submenu !== true && data.is_loading !== true ) { %>
						</form>
					<% } %>
				</div>
			</div>
		</script>
	<?php }
}
