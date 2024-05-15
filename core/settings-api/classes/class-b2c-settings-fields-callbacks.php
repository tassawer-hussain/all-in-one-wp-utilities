<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://2bytecode.com/
 * @since      1.0.0
 *
 * @package    2ByteCode
 * @subpackage 2ByteCode/Settings_Api
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'B2C_Settings_Fields_Callbacks' ) ) {
	/**
	 * WordPress Settings API Callbacks Class.
	 *
	 * Defines the callbacks functions for the settings api to use in child classes.
	 *
	 * @package    2ByteCode
	 * @subpackage 2ByteCode/Settings_Api
	 * @author     2ByteCode <support@2bytecode.com>
	 */
	abstract class B2C_Settings_Fields_Callbacks {

		/**
		 * Option group name of the settings.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $option_group    Option group name of the settings.
		 */
		protected $option_group;

		/**
		 * Option name of the settings against which the configuration saved in the database.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $option_name    Option name in the database in wp_options table.
		 */
		protected $option_name;

		/**
		 * Admin page slug where settings need to be displayed.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $admin_page_slug    Admin page slug.
		 */
		protected $admin_page_slug;

		/**
		 * Options array of the settings fields fetch from the database.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $options    Array of all the options in the seting section.
		 */
		protected $options;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
		}

		/**
		 * Abstract method for default values.
		 *
		 * @return void
		 */
		abstract public function b2c_add_settings_defualt_options(): array;

		/**
		 * Abstract method for adding setting tab.
		 *
		 * @since 1.0.0
		 * @param  array $tabs Array of settings tabs.
		 * @return array
		 */
		abstract public function b2c_add_settings_tab( $tabs ): array;

		/**
		 * Abstract method for adding setting section.
		 *
		 * @return void
		 */
		abstract public function b2c_add_settings_section();

		/**
		 * Abstract method for register setting fields.
		 *
		 * @return void
		 */
		abstract public function b2c_register_setting_fields();

		/**
		 * Abstract method for validate setting fields.
		 *
		 * @param  array $input Raw values received on form submission.
		 * @return void
		 */
		abstract public function b2c_validate_setting_fields( $input );

		/**
		 * Display the description of the setting section
		 *
		 * @since  1.0.0
		 * @param  string $desc Description of the setting section.
		 * @return void
		 */
		public function b2c_display_setting_section_description( $desc ) {
			printf(
				'<p>%1$s</p>',
				esc_html( $desc )
			);
		}

		/**
		 * Display the input field type for setting field callback function
		 *
		 * @param array $args Arguments received from the add settings field function.
		 * @return void
		 */
		public function b2c_callback_to_display_field_text( $args ) {

			$id          = isset( $args['id'] ) ? $args['id'] : '';
			$desc        = isset( $args['desc'] ) ? $args['desc'] : '';
			$type        = isset( $args['type'] ) ? $args['type'] : 'text';
			$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';

			$value = isset( $this->options[ $id ] ) ? sanitize_text_field( $this->options[ $id ] ) : '';

			printf(
				'<input id="%1$s_%2$s" name="%1$s[%2$s]" type="%5$s" size="40" value="%4$s" placeholder="%6$s"><br /><p class="description">%3$s</p>',
				esc_attr( $this->option_name ),
				esc_attr( $id ),
				wp_kses( $desc, 'post' ),
				esc_attr( $value ),
				esc_attr( $type ),
				esc_attr( $placeholder )
			);

		}

		/**
		 * Display the input field type with code prefix for setting field callback function
		 *
		 * @param array $args Arguments received from the add settings field function.
		 * @return void
		 */
		public function b2c_callback_to_display_field_code_text( $args ) {

			$id   = isset( $args['id'] ) ? $args['id'] : '';
			$desc = isset( $args['desc'] ) ? $args['desc'] : '';
			$type = isset( $args['type'] ) ? $args['type'] : 'text';
			$url  = isset( $args['url'] ) ? $args['url'] : trailingslashit( home_url() );

			$value = isset( $this->options[ $id ] ) ? sanitize_text_field( $this->options[ $id ] ) : '';

			printf(
				'<code>%6$s</code><input id="%1$s_%2$s" name="%1$s[%2$s]" type="%5$s" size="40" value="%4$s"><code>/</code><br /><p class="description">%3$s</p>',
				esc_attr( $this->option_name ),
				esc_attr( $id ),
				wp_kses( $desc, 'post' ),
				esc_attr( $value ),
				esc_attr( $type ),
				esc_attr( $url )
			);

		}

		/**
		 * Display the code for setting field callback function
		 *
		 * @param array $args Arguments received from the add settings field function.
		 * @return void
		 */
		public function b2c_callback_to_display_field_code( $args ) {

			$id   = isset( $args['id'] ) ? $args['id'] : '';
			$desc = isset( $args['desc'] ) ? $args['desc'] : '';
			$url  = isset( $args['url'] ) ? $args['url'] : trailingslashit( home_url() );

			printf(
				'<code for="%1$s_%2$s"><a href="%4$s" target="_blank">%4$s</a></code><br /><p class="description">%3$s</p>',
				esc_attr( $this->option_name ),
				esc_attr( $id ),
				wp_kses( $desc, 'post' ),
				esc_attr( $url )
			);

		}

		/**
		 * Display the radio field type for setting field callback function
		 *
		 * @since 1.0.0
		 * @param array $args Arguments received from the add settings field function.
		 * @return void
		 */
		public function b2c_callback_to_display_field_radio( $args ) {

			$id            = isset( $args['id'] ) ? $args['id'] : '';
			$desc          = isset( $args['desc'] ) ? $args['desc'] : '';
			$radio_options = isset( $args['options'] ) ? $args['options'] : array();

			$selected_option = isset( $this->options[ $id ] ) ? sanitize_text_field( $this->options[ $id ] ) : '';

			foreach ( $radio_options as $value => $label ) {
				$checked = checked( $selected_option === $value, true, false );

				printf(
					'<label><input name="%1$s[%2$s]" type="radio" value="%4$s" %5$s><span>%3$s</span></label><br />',
					esc_attr( $this->option_name ),
					esc_attr( $id ),
					esc_html( $label ),
					esc_attr( $value ),
					esc_attr( $checked )
				);
			}

			printf(
				'<p class="description">%1$s</p>',
				wp_kses( $desc, 'post' ),
			);

		}

		/**
		 * Display the textarea field type for setting field callback function
		 *
		 * @since 1.0.0
		 * @param array $args Arguments received from the add settings field function.
		 * @return void
		 */
		public function b2c_callback_to_display_field_textarea( $args ) {

			$id    = isset( $args['id'] ) ? $args['id'] : '';
			$desc  = isset( $args['desc'] ) ? $args['desc'] : '';
			$value = isset( $this->options[ $id ] ) ? $this->options[ $id ] : '';

			$allowed_tags = wp_kses_allowed_html( 'post' );

			printf(
				'<textarea id="%1$s_%2$s" name="%1$s[%2$s]" rows="5" cols="80">%4$s</textarea><br />
				<p class="description">%3$s</p>',
				esc_attr( $this->option_name ),
				esc_attr( $id ),
				wp_kses( $desc, 'post' ),
				wp_kses( stripslashes_deep( $value ), $allowed_tags )
			);
		}

		/**
		 * Display the checkbox field type for setting field callback function
		 *
		 * @since 1.0.0
		 * @param array $args Arguments received from the add settings field function.
		 * @return void
		 */
		public function b2c_callback_to_display_field_checkbox( $args ) {

			$id    = isset( $args['id'] ) ? $args['id'] : '';
			$label = isset( $args['label'] ) ? $args['label'] : '';
			$desc  = isset( $args['desc'] ) ? $args['desc'] : '';

			$checked = isset( $this->options[ $id ] ) ? checked( $this->options[ $id ], 1, false ) : '';

			printf(
				'<input id="%1$s_%2$s" name="%1$s[%2$s]" type="checkbox" value="1" %4$s>
				<label for="%1$s_%2$s">%3$s</label><br>
				<p class="description">%5$s</p>',
				esc_attr( $this->option_name ),
				esc_attr( $id ),
				esc_html( $label ),
				esc_attr( $checked ),
				esc_attr( $desc )
			);
		}

		/**
		 * Display the single select field type for setting field callback function
		 *
		 * @since 1.0.0
		 * @param array $args Arguments received from the add settings field function.
		 * @return void
		 */
		public function b2c_callback_to_display_field_select( $args ) {

			$id   = isset( $args['id'] ) ? $args['id'] : '';
			$desc = isset( $args['desc'] ) ? $args['desc'] : '';

			$select_options = isset( $args['options'] ) ? $args['options'] : array();

			$selected_option = isset( $this->options[ $id ] ) ? sanitize_text_field( $this->options[ $id ] ) : '';

			printf(
				'<select id="%1$s_%2$s" name="%1$s[%2$s]">',
				esc_attr( $this->option_name ),
				esc_attr( $id )
			);

			foreach ( $select_options as $value => $option ) {
				$selected = selected( $selected_option === $value, true, false );
				printf(
					'<option value="%1$s" %2$s>%3$s</option>',
					esc_attr( $value ),
					esc_attr( $selected ),
					esc_html( $option )
				);
			}

			printf(
				'</select> <p class="description">%1$s</p>',
				wp_kses( $desc, 'post' )
			);
		}

		/**
		 * Display the image upload field type for setting field callback function
		 *
		 * @since 1.0.0
		 * @param array $args Arguments received from the add settings field function.
		 * @return void
		 */
		public function b2c_callback_to_display_field_image( $args ) {

			$id   = isset( $args['id'] ) ? $args['id'] : '';
			$desc = isset( $args['desc'] ) ? $args['desc'] : '';

			$img_id = isset( $this->options[ $id ] ) ? esc_attr( $this->options[ $id ] ) : '';

			printf(
				'<input type="hidden" id="%1$s_%2$s" name="%1$s[%2$s]" value="%3$s">',
				esc_attr( $this->option_name ),
				esc_attr( $id ),
				esc_attr( $img_id )
			);

			printf(
				'<div id="%1$s_%2$s_wrapper" class="b2c_image_in_settings" style="width: 300px;">%3$s</div>',
				esc_attr( $this->option_name ),
				esc_attr( $id ),
				wp_get_attachment_image( $img_id, 'full' ),
			);

			printf(
				'<p>
					<input type="button" class="button button-secondary b2c_add_img_%2$s" id="b2c_add_img_%2$s" name="b2c_add_img_%2$s" value="%4$s" />
					<input type="button" class="button button-secondary b2c_remove_img_%2$s" id="b2c_remove_img_%2$s" name="b2c_remove_img_%2$s" value="%5$s" />
				</p>
				<p class="description">%3$s</p>',
				esc_attr( $this->option_name ),
				esc_attr( $id ),
				wp_kses( $desc, 'post' ),
				esc_attr( __( 'Add', 'all-in-one-utilities' ) ),
				esc_attr( __( 'Remove', 'all-in-one-utilities' ) ),
			);

			$args = wp_json_encode(
				array(
					$this->option_name . '_' . $id,
					$this->option_name . '_' . $id . '_wrapper',
					'.b2c_add_img_' . $id,
					'.b2c_remove_img_' . $id,
				)
			);

			$js_code = <<<IMG_JS
				<script type="text/javascript">
					jQuery( window ).load(function() {
						app.imageSettingsfields( $args );
					});
				</script>
				IMG_JS;
			echo $js_code; // phpcs:ignore WordPress.Security.EscapeOutput

		}

		/**
		 * Display the video upload field type for setting field callback function
		 *
		 * @since 1.0.0
		 * @param array $args Arguments received from the add settings field function.
		 * @return void
		 */
		public function b2c_callback_to_display_field_video( $args ) {

			$id   = isset( $args['id'] ) ? $args['id'] : '';
			$desc = isset( $args['desc'] ) ? $args['desc'] : '';

			$img_id = isset( $this->options[ $id ] ) ? esc_attr( $this->options[ $id ] ) : '';

			printf(
				'<input type="hidden" id="%1$s_%2$s" name="%1$s[%2$s]" value="%3$s">',
				esc_attr( $this->option_name ),
				esc_attr( $id ),
				esc_attr( $img_id )
			);

			printf(
				'<div id="%1$s_%2$s_wrapper" class="b2c_image_in_settings" style="width: 300px;">%3$s</div>',
				esc_attr( $this->option_name ),
				esc_attr( $id ),
				wp_get_attachment_image( $img_id, 'full' ),
			);

			printf(
				'<p>
					<input type="button" class="button button-secondary b2c_add_img_%2$s" id="b2c_add_img_%2$s" name="b2c_add_img_%2$s" value="%4$s" />
					<input type="button" class="button button-secondary b2c_remove_img_%2$s" id="b2c_remove_img_%2$s" name="b2c_remove_img_%2$s" value="%5$s" />
				</p>
				<p class="description">%3$s</p>',
				esc_attr( $this->option_name ),
				esc_attr( $id ),
				wp_kses( $desc, 'post' ),
				esc_attr( __( 'Add', 'all-in-one-utilities' ) ),
				esc_attr( __( 'Remove', 'all-in-one-utilities' ) ),
			);

			$args = wp_json_encode(
				array(
					$this->option_name . '_' . $id,
					$this->option_name . '_' . $id . '_wrapper',
					'.b2c_add_img_' . $id,
					'.b2c_remove_img_' . $id,
				)
			);

			$js_code = <<<IMG_JS
				<script type="text/javascript">
					jQuery( window ).load(function() {
						app.videoSettingsfields( $args );
					});
				</script>
				IMG_JS;
			echo $js_code; // phpcs:ignore WordPress.Security.EscapeOutput

		}

		/**
		 * Display the color field type for setting field callback function
		 *
		 * @since 1.0.0
		 * @param array $args Arguments received from the add settings field function.
		 * @return void
		 */
		public function b2c_callback_to_display_field_color( $args ) {

			$id   = isset( $args['id'] ) ? $args['id'] : '';
			$desc = isset( $args['desc'] ) ? $args['desc'] : '';

			$color_code = isset( $this->options[ $id ] ) ? esc_attr( $this->options[ $id ] ) : '';

			printf(
				'<input type="text" class="b2c_color_field" data-alpha-enabled="true" id="%1$s_%2$s" name="%1$s[%2$s]" value="%4$s" >
				<p class="description">%3$s</p>',
				esc_attr( $this->option_name ),
				esc_attr( $id ),
				wp_kses( $desc, 'post' ),
				esc_attr( $color_code )
			);

		}
	}
}
