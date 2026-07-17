<?php
/**
 * Main plugin functions.
 */
class Bunyad_Instgram_Plugin 
{
	const RENEWALS_TRANSIENT = 'bunyad_instagram_token_renewals';
	const WIDGETS_OPTION     = 'widget_null-instagram-feed';

	public $path;

	public function __construct($path)
	{
		$this->path = $path;
		
		// Included required files.
		require_once $this->path . 'inc/api.php';

		// Cron handler for token updates.
		// @deprecated 1.3.0
		// add_action('bunyad_instagram_cron', [$this, 'cron_token_refresh']);
		// if (!wp_next_scheduled('bunyad_instagram_cron')) {
		// 	wp_schedule_event(time(), 'daily', 'bunyad_instagram_cron');
		// }

		// Register our widget.
		add_action('widgets_init', [$this, 'register_widget']);

		// Load language files.
		add_action('init', function() {
			load_plugin_textdomain('bunyad-instagram-widget', false, $this->path . 'languages/');
		});
		

	}

	public function register_widget()
	{
		require_once $this->path . 'inc/widget.php';
		register_widget('Bunyad_Instagram_Widget');
	}

	/**
	 * Deactivation hook.
	 */
	public function deactivate()
	{
		wp_clear_scheduled_hook('bunyad_instagram_cron');
	}

	/**
	 * Handle cron run to renew tokens.
	 * 
	 * @deprecated 1.3.0
	 */
	public function cron_token_refresh()
	{
		$token_renewals = (array) get_transient(self::RENEWALS_TRANSIENT);
		$widgets        = get_option(self::WIDGETS_OPTION);
		$changed        = false;

		// Next renewal time of 30 days.
		$next_renewal   = time() + (DAY_IN_SECONDS * 30);

		foreach ($widgets as $key => $widget) {

			// Act if we have renewal time and access token.
			if (empty($widget['access_token'])) {
				continue;
			}

			// First time token probably. Set renewal time.
			if (!isset($token_renewals[$key])) {
				$token_renewals[$key] = $next_renewal;
				$changed = true;
				continue;
			}

			$renew_time = &$token_renewals[$key];
			if ($renew_time <= time()) {

				/**
				 * Refresh the token from API and save for the widget.
				 */
				$api = new Bunyad_Instagram_Api($widget['access_token']);
				$new_token = $api->get_token_refresh();

				if (!empty($new_token['token'])) {
					$widgets[$key]['access_token'] = $new_token['token'];

					// Set next renewal time.
					$renew_time = $next_renewal;
					$changed    = true;
				}
			}
		}

		if ($changed) {
			set_transient(self::RENEWALS_TRANSIENT, $token_renewals);
			update_option(self::WIDGETS_OPTION, $widgets);
		}
	}
}