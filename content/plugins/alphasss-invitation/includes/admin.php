<?php
/**
 * @package WordPress
 * @subpackage Alphasss Invitation
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Alphasss_Invitation_Admin' ) ):

class Alphasss_Invitation_Admin
{
	/**
	 * Plugin options
	 *
	 * @var array
	 */
	public $options = array();

	private function __construct(){}

	public static function instance()
	{
		static $instance = null;

		if ( null === $instance ) {
			$instance = new Alphasss_Invitation_Admin;
			$instance->setup();
		}

		return $instance;
	}

	/**
	 * Get option
	 *
	 * @param  string $key Option key
	 *
	 * @return mixed Option value
	 */
	public function option( $key )
	{
		return alphasss_invitation()->option( $key );
	}

	/**
	 * Setup admin class
	 */
	public function setup()
	{
		if ( ( ! is_admin() && ! is_network_admin() ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$actions = array('admin_init', 'admin_menu', 'network_admin_menu');

		foreach( $actions as $action ) {
			add_action( $action, array( $this, $action ) );
		}
	}

	/**
	 * Register admin settings
	 */
	public function admin_init()
	{
		register_setting( 'alphasss_invitation_plugin_options', 'alphasss_invitation_plugin_options', array( $this, 'plugin_options_validate' ) );
		add_settings_section( 'general_section', __( 'General Settings', 'alphasss-invitation' ), array( $this, 'section_general' ), __FILE__ );

		add_settings_field( 'time-to-expire', __( 'Time to expire', 'alphasss-invitation' ), array( $this, 'setting_time_to_expire' ), __FILE__, 'general_section' );
		add_settings_field( 'guessing-attempts-limit', __( 'Guessing attempts limit', 'alphasss-invitation' ), array( $this, 'setting_guessing_attempts' ), __FILE__, 'general_section' );
	}

	public function setting_guessing_attempts()
	{
		$value = $this->option( 'guessing-attempts-limit' );

		if ( ! $value = (int) $this->option( 'guessing-attempts-limit' ) ) {
			$value = 5;
		}

		printf( '<input id="guessing-attempts-limit" name="alphasss_invitation_plugin_options[guessing-attempts-limit]" value="%d" /> ', $value);

		_e('Setup limit guessing attempts to submit valid invitation code', 'alphasss-invitation');
	}

	public function setting_time_to_expire()
	{
		$value = $this->option( 'time-to-expire' );

		if ( ! $value = (int) $this->option( 'time-to-expire' ) ) {
			$value = 86400;
		}

		printf( '<input id="time-to-expire" name="alphasss_invitation_plugin_options[time-to-expire]" value="%d" /> ', $value);

		_e('Setup time to live of invitation code', 'alphasss-invitation');
	}

	/**
	 * Add plugin settings page
	 */
	public function admin_menu()
	{
		add_options_page( __('Alphasss Invitation', 'alphasss-invitation'), __('Alphasss Invitation', 'alphasss-invitation'), 'manage_options', __FILE__, array( $this, 'options_page' ) );
	}

	/**
	 * Add plugin settings page
	 */
	public function network_admin_menu()
	{
		return $this->admin_menu();
	}

	/**
	 * Render settings page
	 */
	public function options_page()
	{
	?>
		<div class="wrap">
			<div class="icon32" id="icon-options-general"><br></div>
			<h2><?php _e('Alphasss Invitation', 'alphasss-invitation');?></h2>
			<form action="options.php" method="post">
			<?php settings_fields('alphasss_invitation_plugin_options'); ?>
			<?php do_settings_sections(__FILE__); ?>

			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'alphasss-invitation'); ?>" />
			</p>
			</form>
		</div>

	<?php
	}

	/**
	 * General settings section
	 */
	public function section_general()
	{
		_e( 'Some dummy text here', 'alphasss-invitation' );
	}

	/**
	 * Validate plugin option
	 *
	 * @return array
	 */
	public function plugin_options_validate( $input )
	{
		$input['time-to-expire']          = (int) $input['time-to-expire'];
		$input['guessing-attempts-limit'] = (int) $input['guessing-attempts-limit'];

		return $input;
	}
}
// End class Alphasss_Invitation_Admin

endif;

?>