<?php
/**
 * New Instagram API for 2024.
 */
class Bunyad_Instagram_Api
{
	protected $token;
	protected $user_id;

	public function __construct($token, $user_id = null)
	{
		$this->token   = $token;
		$this->user_id = $user_id;

		if (!$user_id) {
			// $this->user_id = $this->get_user_id();
		}
	}

	/**
	 * Make a request and return response or an error.
	 */
	public function request($url, $fields = [], $method = 'get', $args = [])
	{
		$args = array_replace([
			'timeout' => 15,
		], $args);

		if (strtolower($method) === 'post') {
			$args['body'] = $fields;
			$response     = wp_remote_post($url, $args);
		}
		else {

			// Add query args to get.
			if ($fields) {
				$url .= '?' . http_build_query($fields);
			}

			$response = wp_remote_get($url, $args);
		}

		return $response;
	}

	/**
	 * Get user ID using the access token for current user.
	 */
	public function get_user_id()
	{
		$request = $this->request('https://graph.instagram.com/me', [
			'fields' => 'id',
			'access_token' => $this->token
		]);

		$data = $this->get_data($request);
		if (is_wp_error($data) || !$data['id']) {
			return new WP_Error('no_user_id', 'Could not retrieve user ID from Instagram API. ' . $data->get_error_message());
		}

		return $data['id'];
	}

	/**
	 * Refresh a token.
	 */
	public function get_token_refresh()
	{
		$request = $this->request('https://graph.instagram.com/refresh_access_token', [
			'grant_type'   => 'ig_refresh_token',
			'access_token' => $this->token
		]);

		if (is_wp_error($request)) {
			return false;
		}

		$data = json_decode($request['body'], true);
		if (empty($data['access_token'])) {
			return false;
		}

		return [
			'token'      => $data['access_token'],
			'expires_in' => $data['expires_in'],
		];
	}

	/**
	 * Get latest media of the user.
	 * 
	 * @param integer $limit
	 * @return array|WP_Error
	 */
	public function get_media($limit = 6)
	{
		$request = $this->request('https://graph.instagram.com/me/media', [
			'fields'       => 'media_type,media_url,permalink,thumbnail_url,caption',
			'access_token' => $this->token,
			'limit'        => min($limit, 150)
		]);

		$data = $this->get_data($request);
		if (is_wp_error($data)) {
			return $data;
		}

		if (!isset($data['data'])) {
			return new WP_Error('bad_json', esc_html__('Instagram API did not return an object.', 'bunyad-instagram-widget'));
		}

		return $data['data'];
	}

	/**
	 * Get data or error from the request.
	 */
	public function get_data($request)
	{
		if (is_wp_error($request)) {
			return $request;
		}

		$response_code = wp_remote_retrieve_response_code($request);
		$data = json_decode($request['body'], true);

		// Handle API errors.
		if (200 !== $response_code) {

			// Invalid token error would be returned as 400.
			if (400 === $response_code && isset($data['error']['type']) && $data['error']['type'] === 'OAuthException') {
				return new WP_Error( 
					'invalid_token', 
					sprintf(esc_html__('The provided Access Token is invalid or expired. Please regenerate it from the Widget.', 'bunyad-instagram-widget'))
				);
			}

			return new WP_Error( 
				'invalid_response', 
				sprintf( 
					esc_html__('Instagram Error: %s', 'bunyad-instagram-widget' ), 
					esc_html(
						isset($data['error']['message']) ? $data['error']['message'] : 'Unknown.'
					)
				)
			);
		}

		return $data;
	}
	
}