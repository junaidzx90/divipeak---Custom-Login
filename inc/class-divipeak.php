<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that admin attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Divipeak
 * @subpackage Divipeak/admin
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Divipeak
 * @subpackage Divipeak/admin
 * @author     Developer Junayed <admin@easeare.com>
 */
class Divipeak {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'DIVIPEAK_VERSION' ) ) {
			$this->version = DIVIPEAK_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'divipeak';

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		add_action("admin_enqueue_scripts", [$this, "admin_scripts"]);
		add_action("admin_menu", [$this, "divipeak_admin_menu"]);
		add_action("login_enqueue_scripts", [$this, "login_enqueue_scripts_callback"]);
		add_action("login_head", [$this, "login_head_scripts_callback"]);
		add_action("login_headerurl", [$this, "login_headerurl_callback"]);
	}

	function admin_scripts(){
		if(isset($_GET['page']) && $_GET['page'] === 'divipeak'){
            wp_enqueue_media();
			wp_enqueue_style( 'wp-color-picker');
			wp_enqueue_script( 'wp-color-picker');
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . "admin/css/divipeak-admin.css", array(), $this->version, 'all' );

			wp_enqueue_script( 'color-picker-alpha', plugin_dir_url( __FILE__ ) . 'admin/js/wp-color-picker-alpha.js', array( 'wp-color-picker' ), $this->version, true);
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'admin/js/divipeak-admin.js', array( 'jquery', 'color-picker-alpha' ), $this->version, true);
        }
	}

	function divipeak_admin_menu(){
		add_options_page( "divipeak - Custom Login", "divipeak - Custom Login", "manage_options", "divipeak", [$this, "divipeak_menupage"], null );

		add_settings_section( 'dpcl_general_opt_section', '', '', 'dpcl_general_opt_page' );
		
		// Logo URL
		add_settings_field( 'dpcl_custom_logo', 'Custom Logo', [$this, 'dpcl_custom_logo_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_custom_logo' );
		// Logo redirection URL
		add_settings_field( 'dpcl_logo_redirection', 'Logo redirection URL', [$this, 'dpcl_logo_redirection_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_logo_redirection' );

		// Logo margin
		add_settings_field( 'dpcl_logo_margin', 'Logo margin', [$this, 'dpcl_logo_margin_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_logo_margin_top' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_logo_margin_bottom' );

		// Logo scale
		add_settings_field( 'dpcl_logo_scale', 'Logo scale', [$this, 'dpcl_logo_scale_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_logo_scale' );

		// Body background color
		add_settings_field( 'dpcl_body_bg_color', 'Body background color', [$this, 'dpcl_body_bg_color_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_body_bg_color' );

		// Body color
		add_settings_field( 'dpcl_body_color', 'Body text color', [$this, 'dpcl_body_color_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_body_color' );

		// Form background color
		add_settings_field( 'dpcl_form_background_color', 'Form background color', [$this, 'dpcl_form_background_color_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_form_background_color' );
		// Form border
		add_settings_field( 'dpcl_form_border', 'Form border', [$this, 'dpcl_form_border_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_form_border' );
		// Form border radius
		add_settings_field( 'dpcl_form_border_radius', 'Form border radius', [$this, 'dpcl_form_border_radius_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_form_border_radius' );
		// Form box-shadow
		add_settings_field( 'dpcl_form_box_shadow_color', 'Form shadow color', [$this, 'dpcl_form_box_shadow_color_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_form_box_shadow_color' );

		// Login button background color
		add_settings_field( 'dpcl_login_btn_background_color', 'Login button background color', [$this, 'dpcl_login_btn_background_color_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_login_btn_background_color' );
		// Login button border
		add_settings_field( 'dpcl_login_btn_border', 'Login button border', [$this, 'dpcl_login_btn_border_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_login_btn_border' );
		// Login button border radius
		add_settings_field( 'dpcl_login_btn_border_radius', 'Login button border radius', [$this, 'dpcl_login_btn_border_radius_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_login_btn_border_radius' );
		// Login button hover background color
		add_settings_field( 'dpcl_login_btn_hover_bg_color', 'Login button hover background color', [$this, 'dpcl_login_btn_hover_bg_color_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_login_btn_hover_bg_color' );

		// Link color
		add_settings_field( 'dpcl_link_color', 'Link color', [$this, 'dpcl_link_color_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_link_color' );
		// Link hover color
		add_settings_field( 'dpcl_link_hover_color', 'Link hover color', [$this, 'dpcl_link_hover_color_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_link_hover_color' );
		// Link hover text-decoration
		add_settings_field( 'dpcl_link_hover_text_decoration', 'Link hover text-decoration', [$this, 'dpcl_link_hover_text_decoration_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_link_hover_text_decoration' );

		// Input focus border color
		add_settings_field( 'dpcl_input_focus_border_color', 'Input focus border color', [$this, 'dpcl_input_focus_border_color_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_input_focus_border_color' );
		// Input focus shadow color
		add_settings_field( 'dpcl_input_focus_shadow_color', 'Input focus shadow color', [$this, 'dpcl_input_focus_shadow_color_cb'], 'dpcl_general_opt_page','dpcl_general_opt_section' );
		register_setting( 'dpcl_general_opt_section', 'dpcl_input_focus_shadow_color' );
	}

	function dpcl_custom_logo_cb(){
        echo '<div class="_logo__preview">';
        if(get_option('dpcl_custom_logo')){
            echo '<img class="logo_preview_img" src="'.get_option('dpcl_custom_logo').'">';
        }
        echo '</div>';
        echo '<button id="_upload__logo" class="button-secondary">Upload a logo</button>';
        if(get_option('dpcl_custom_logo')){
            echo '&nbsp;&nbsp;<button id="_remove__logo" class="button-secondary">Remove</button>'; 
        }
        echo '<input type="hidden" name="dpcl_custom_logo" id="dpcl_custom_logo" class="widefat" value="'.get_option('dpcl_custom_logo').'">';
	}
	function dpcl_logo_redirection_cb(){
		echo '<input type="url" class="widefat" name="dpcl_logo_redirection" placeholder="URL" value="'.get_option('dpcl_logo_redirection').'">';
	}
	function dpcl_logo_margin_cb(){
		echo '<div class="logo-margin">';
		echo '<label>Top<input type="number" placeholder="0" name="dpcl_logo_margin_top" value="'.get_option('dpcl_logo_margin_top').'"></label>';
		echo '<label>Bottom<input type="number" placeholder="25px" name="dpcl_logo_margin_bottom" value="'.get_option('dpcl_logo_margin_bottom').'"></label>';
		echo '</div>';
	}
	function dpcl_logo_scale_cb(){
		echo '<div class="range-wrap">';
		echo '<div class="range-value"></div>';
		echo '<input class="range_input" value="'.get_option('dpcl_logo_scale', 1).'" type="range" name="dpcl_logo_scale" min="1" max="10">';
		echo '</div>';
	}
	function dpcl_body_bg_color_cb(){
		echo '<input type="text" name="dpcl_body_bg_color" id="dpcl_body_bg_color" data-alpha-enabled="true" class="color-picker" data-default-color="#f0f0f1" value="'.((get_option('dpcl_body_bg_color')) ? get_option('dpcl_body_bg_color') : '#f0f0f1').'">';
	}
	function dpcl_body_color_cb(){
		echo '<input type="text" name="dpcl_body_color" id="dpcl_body_color" data-alpha-enabled="true" class="color-picker" data-default-color="#3c434a" value="'.((get_option('dpcl_body_color')) ? get_option('dpcl_body_color') : '#3c434a').'">';
	}
	function dpcl_form_background_color_cb(){
		echo '<input type="text" name="dpcl_form_background_color" id="dpcl_form_background_color" data-alpha-enabled="true" class="color-picker" data-default-color="#ffffff" value="'.((get_option('dpcl_form_background_color')) ? get_option('dpcl_form_background_color') : '#ffffff').'">';
	}
	function dpcl_form_border_cb(){
		echo '<input type="text" name="dpcl_form_border" placeholder="1px solid #c3c4c7" id="dpcl_form_border" value="'.get_option('dpcl_form_border').'">';
	}
	function dpcl_form_border_radius_cb(){
		echo '<div class="range-wrap">';
		echo '<div class="range-value"></div>';
		echo '<input class="range_input" value="'.get_option('dpcl_form_border_radius', 0).'" type="range" name="dpcl_form_border_radius" min="0" max="25">';
		echo '</div>';
	}
	function dpcl_form_box_shadow_color_cb(){
		echo '<input type="text" name="dpcl_form_box_shadow_color" id="dpcl_form_box_shadow_color" data-alpha-enabled="true" class="color-picker" data-default-color="#d9d9d9" value="'.((get_option('dpcl_form_box_shadow_color')) ? get_option('dpcl_form_box_shadow_color') : '#d9d9d9').'">';
	}

	function dpcl_login_btn_background_color_cb(){
		echo '<input type="text" name="dpcl_login_btn_background_color" id="dpcl_login_btn_background_color" data-alpha-enabled="true" class="color-picker" data-default-color="#2271b1" value="'.((get_option('dpcl_login_btn_background_color')) ? get_option('dpcl_login_btn_background_color') : '#2271b1').'">';
	}
	function dpcl_login_btn_border_cb(){
		echo '<input type="text" name="dpcl_login_btn_border" placeholder="1px solid" id="dpcl_login_btn_border" value="'.get_option('dpcl_login_btn_border').'">';
	}
	function dpcl_login_btn_border_radius_cb(){
		echo '<div class="range-wrap">';
		echo '<div class="range-value"></div>';
		echo '<input class="range_input" value="'.get_option('dpcl_login_btn_border_radius', 3).'" type="range" name="dpcl_login_btn_border_radius" min="0" max="25">';
		echo '</div>';
	}
	function dpcl_login_btn_hover_bg_color_cb(){
		echo '<input type="text" name="dpcl_login_btn_hover_bg_color" id="dpcl_login_btn_hover_bg_color" data-alpha-enabled="true" class="color-picker" data-default-color="#2271b1" value="'.((get_option('dpcl_login_btn_hover_bg_color')) ? get_option('dpcl_login_btn_hover_bg_color') : '#2271b1').'">';
	}
	function dpcl_link_color_cb(){
		echo '<input type="text" name="dpcl_link_color" id="dpcl_link_color" data-alpha-enabled="true" class="color-picker" data-default-color="#50575e" value="'.((get_option('dpcl_link_color')) ? get_option('dpcl_link_color') : '#50575e').'">';
	}
	function dpcl_link_hover_color_cb(){
		echo '<input type="text" name="dpcl_link_hover_color" id="dpcl_link_hover_color" data-alpha-enabled="true" class="color-picker" data-default-color="#2271b1" value="'.((get_option('dpcl_link_hover_color')) ? get_option('dpcl_link_hover_color') : '#2271b1').'">';
	}
	function dpcl_link_hover_text_decoration_cb(){
		echo '<input type="text" name="dpcl_link_hover_text_decoration" id="dpcl_link_hover_text_decoration" placeholder="underline" value="'.get_option('dpcl_link_hover_text_decoration').'">';
	}
	function dpcl_input_focus_border_color_cb(){
		echo '<input type="text" name="dpcl_input_focus_border_color" id="dpcl_input_focus_border_color" data-alpha-enabled="true" class="color-picker" data-default-color="#2271b1" value="'.((get_option('dpcl_input_focus_border_color')) ? get_option('dpcl_input_focus_border_color') : '#2271b1').'">';
	}
	function dpcl_input_focus_shadow_color_cb(){
		echo '<input type="text" name="dpcl_input_focus_shadow_color" id="dpcl_input_focus_shadow_color" data-alpha-enabled="true" class="color-picker" data-default-color="#d9d9d9" value="'.((get_option('dpcl_input_focus_shadow_color')) ? get_option('dpcl_input_focus_shadow_color') : '#d9d9d9').'">';
	}

	function divipeak_menupage(){
		?>
		<h3>divipeak - Custom Login</h3>
		<hr>

		<form method="post" style="width: 50%" action="options.php">
			<?php
			settings_fields( 'dpcl_general_opt_section' );
			do_settings_sections( 'dpcl_general_opt_page' );
			
			submit_button(  );
			?>
		</form>	
		<?php
	}

	function login_head_scripts_callback(){
		?>
		<style>
			:root{
				--loginlogo: <?php echo ((get_option('dpcl_custom_logo')) ? 'url('.get_option('dpcl_custom_logo').')': 'url('.home_url( '/wp-admin/images/w-logo-blue.png' ).')') ?>;
				--logoScale: <?php echo ((get_option('dpcl_logo_scale')) ? 'scale('.get_option('dpcl_logo_scale').')' : 'scale(1)') ?>;
				--margin_top: <?php echo ((get_option('dpcl_logo_margin_top')) ? get_option('dpcl_logo_margin_top').'px' : '0px') ?>;
				--margin_bottom: <?php echo ((get_option('dpcl_logo_margin_bottom')) ? get_option('dpcl_logo_margin_bottom').'px' : '25px') ?>;
				--body_bg: <?php echo ((get_option('dpcl_body_bg_color')) ? get_option('dpcl_body_bg_color') : '#f0f0f1') ?>;
				--body_color: <?php echo ((get_option('dpcl_body_color')) ? get_option('dpcl_body_color') : '#3c434a') ?>;
				--form_background: <?php echo ((get_option('dpcl_form_background_color')) ? get_option('dpcl_form_background_color') : '#ffffff') ?>;
				--form_border: <?php echo ((get_option('dpcl_form_border')) ? get_option('dpcl_form_border').'px' : '1px solid #c3c4c7') ?>;
				--border_radius: <?php echo ((get_option('dpcl_form_border_radius')) ? get_option('dpcl_form_border_radius').'px' : '0px') ?>;
				--shadow_color: <?php echo ((get_option('dpcl_form_box_shadow_color')) ? get_option('dpcl_form_box_shadow_color') : '#d9d9d9') ?>;
				--button_bg: <?php echo ((get_option('dpcl_login_btn_background_color')) ? get_option('dpcl_login_btn_background_color') : '#2271b1') ?>;
				--btn_border_radius: <?php echo ((get_option('dpcl_login_btn_border_radius')) ? get_option('dpcl_login_btn_border_radius').'px' : '3px') ?>;
				--btn_border: <?php echo ((get_option('dpcl_login_btn_border')) ? get_option('dpcl_login_btn_border').'' : '1px solid') ?>;
				--btn_hover_bg_color: <?php echo ((get_option('dpcl_login_btn_hover_bg_color')) ? get_option('dpcl_login_btn_hover_bg_color') : '') ?>;
				--link_color: <?php echo ((get_option('dpcl_link_color')) ? get_option('dpcl_link_color') : '#50575e') ?>;
				--link_hover_color: <?php echo ((get_option('dpcl_link_hover_color')) ? get_option('dpcl_link_hover_color') : '#2271b1') ?>;
				--link_hover_text_decoration: <?php echo ((get_option('dpcl_link_hover_text_decoration')) ? get_option('dpcl_link_hover_text_decoration') : 'underline') ?>;
				--input_focus_border_color: <?php echo ((get_option('dpcl_input_focus_border_color')) ? get_option('dpcl_input_focus_border_color') : '#2271b1') ?>;
				--input_focus_shadow_color: <?php echo ((get_option('dpcl_input_focus_shadow_color')) ? get_option('dpcl_input_focus_shadow_color') : '#d9d9d9') ?>;
			}
		</style>
		<?php
	}

	function login_headerurl_callback(){
		return ((get_option('dpcl_logo_redirection')) ? get_option('dpcl_logo_redirection') : home_url());
	}

	function login_enqueue_scripts_callback(){
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . "public/login.css", array(), $this->version, 'all' );
	}
}
