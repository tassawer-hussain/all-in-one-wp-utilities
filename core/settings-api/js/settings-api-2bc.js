/**
 * The admin-specific javascript functionality of the plugin.
 *
 * @link       https://2bytecode.com/
 * @since      1.0.0
 *
 * @package    2ByteCode/Settings_Api
 * @subpackage 2ByteCode/Settings_Api/js
 * @author     2ByteCode <support@2bytecode.com>
 */

var app = {
	init: function() {
		app.settingTabsActive();
		app.triggerColorField();
	},

	/**
	 * settings tabs active/deactive
	 *
	 * @since  1.0.0
	 * @access (public)
	 */
	settingTabsActive: function() {
		/** Add active class to first tab */
		jQuery('.b2c-tab').click(function () {
			jQuery(".b2c-tab").removeClass('nav-tab-active');
			jQuery(".b2c-admin-settings").removeClass('tab-active');

			let id = jQuery(this).attr('data-id');
			window.location.hash = id;
			jQuery(".b2c-tab[data-id='" + id + "']").addClass("nav-tab-active");
			jQuery(".b2c-admin-settings[data-id='" + id + "']").addClass("tab-active");
			return false;
		});

		/* Display Settings Tabs Previous State on Form Submit */
		if (window.location.hash.length > 0) {
			var loc = window.location.hash.substring(1);
			
			jQuery(".b2c-tab").removeClass('nav-tab-active');
			jQuery(".b2c-admin-settings").removeClass('tab-active');

			jQuery(".b2c-tab[data-id='" + loc + "']").addClass("nav-tab-active");
			jQuery(".b2c-admin-settings[data-id='" + loc + "']").addClass("tab-active");
		}
	},
	
	/**
	 * Function for adding the WordPress Builtin Media with the image setting filed
	 *
	 * @since  1.0.0
	 * @access (public)
	 */
	imageSettingsfields: function ( prop ) {
		
		var input_id      = prop[0],
			input_wrapper = prop[1], 
			add_btn       = prop[2], 
			remove_btn    = prop[3]; 
		app.triggerMediaUpload( add_btn, input_id, input_wrapper );
		
		jQuery('body').on('click', remove_btn, function () {
			jQuery('#'+input_id).val('');
			jQuery('#'+input_wrapper).html('<img class="custom_media_image" src="" style="width:100%;margin:0;padding:0;float:none;" />');
		});
		
		jQuery(document).ajaxComplete(function (event, xhr, settings) {
			var queryStringArr = settings.data.split('&');
			if (jQuery.inArray('action=add-tag', queryStringArr) !== -1) {
				var xml = xhr.responseXML;
				$response = jQuery(xml).find('term_id').text();
				if ($response != "") {
					// Clear the thumb image
					jQuery('#'+input_wrapper).html('');
				}
			}
		});
    },

	/**
	 * Trigger the built-in media upload functionality
	 *
	 * @since  1.0.0
	 * @access (public)
	 */
	 triggerMediaUpload: function ( button_class, input_id, input_wrapper ) {
		
		var _custom_media         = true,
			_orig_send_attachment = wp.media.editor.send.attachment;
		
		jQuery('body').on('click', button_class, function (e) {
			var button_id = '#' + jQuery(this).attr('id');
			var send_attachment_bkp = wp.media.editor.send.attachment;
			var button = jQuery(button_id);
			_custom_media = true;
			wp.media.editor.send.attachment = function (props, attachment) {
				if (_custom_media) {
					console.log(input_id);
					jQuery('#'+input_id).val(attachment.id);
					jQuery('#'+input_wrapper).html('<img class="custom_media_image" src="" style="width:100%;margin:0;padding:0;float:none;" />');
					jQuery('#' + input_wrapper + ' .custom_media_image').attr('src', attachment.url).css('display', 'block');
				} else {
					return _orig_send_attachment.apply(button_id, [props, attachment]);
				}
			}
			wp.media.editor.open(button);
			return false;
		});
	},

	/**
	 * Function for adding the WordPress Builtin Media with the video setting filed
	 *
	 * @since  1.0.0
	 * @access (public)
	 */
	 videoSettingsfields: function ( prop ) {
		
		var input_id      = prop[0],
			input_wrapper = prop[1], 
			add_btn       = prop[2], 
			remove_btn    = prop[3]; 
		app.triggerVideoUpload( add_btn, input_id, input_wrapper );
		
		jQuery('body').on('click', remove_btn, function () {
			jQuery('#'+input_id).val('');
			jQuery('#'+input_wrapper).html('<video controls autoplay loop muted class="custom_media_video" playsinline><source src=""></video>');
		});
		
		jQuery(document).ajaxComplete(function (event, xhr, settings) {
			var queryStringArr = settings.data.split('&');
			if (jQuery.inArray('action=add-tag', queryStringArr) !== -1) {
				var xml = xhr.responseXML;
				$response = jQuery(xml).find('term_id').text();
				if ($response != "") {
					// Clear the thumb image
					jQuery('#'+input_wrapper).html('');
				}
			}
		});
    },

	/**
	 * Trigger the built-in media upload functionality
	 *
	 * @since  1.0.0
	 * @access (public)
	 */
	triggerVideoUpload: function ( button_class, input_id, input_wrapper ) {
		
		var _custom_media         = true,
			_orig_send_attachment = wp.media.editor.send.attachment;
		
		jQuery('body').on('click', button_class, function (e) {
			var button_id = '#' + jQuery(this).attr('id');
			var send_attachment_bkp = wp.media.editor.send.attachment;
			var button = jQuery(button_id);
			_custom_media = true;
			wp.media.editor.send.attachment = function (props, attachment) {
				if (_custom_media) {
					console.log(input_id);
					jQuery('#'+input_id).val(attachment.id);
					jQuery('#'+input_wrapper).html('<video controls autoplay loop muted class="custom_media_video" playsinline><source src=""></video>');
					jQuery('#' + input_wrapper + ' .custom_media_video source').attr('src', attachment.url).css('display', 'block');
				} else {
					return _orig_send_attachment.apply(button_id, [props, attachment]);
				}
			}
			wp.media.editor.open(button);
			return false;
		});
	},

	/**
	 * Trigger the color field on input type text
	 *
	 * @since  1.0.0
	 * @access (public)
	 */
	triggerColorField: function () {
		jQuery('.b2c_color_field').each(function () {
			jQuery(this).wpColorPicker();
		});
	},

};

(function() {
	jQuery( window ).load(function() {
		app.init();
	});
})( jQuery );
