<?php

/**
 *
 */
class SAMA_Subscriptions {

	public static $shortcode_ids;

	/**
	 *
	 */
	public function __construct() {

	}

	/**
	 * @return int|void
	 */
	public static function get_id() {
		if ( empty( self::$shortcode_ids ) ) {
			self::$shortcode_ids = array();
		}
		$current_id = count( self::$shortcode_ids );
		$current_id ++;
		self::$shortcode_ids[] = $current_id;

		return $current_id;
	}

	/**
	 * @param $atts
	 * @param $content
	 *
	 * @return false|string
	 */
	public static function sama_paypal_form( $atts, $content = null ) {
		global $wpdb, $post;

		$atts            = shortcode_atts(
			array(
				'vendor'          => '',
				'id'              => isset( $_GET['id'] ) ? sanitize_key( $_GET['id'] ) : '',
				'subscription_id' => isset( $_GET['subscription_id'] ) ? sanitize_key( $_GET['subscription_id'] ) : '',
			),
			$atts,
			'sama-paypal'
		);
		$id              = $atts['id'];
		$subscription_id = $atts['subscription_id'];

		ob_start();
		$_member       = new SAMA_Member();
		$member        = $_member->sama_get( $id );
		$_subscription = new SAMA_Subscription();
		$subscription  = $_subscription->sama_get( $subscription_id );
		include SAMA_PATH . '/views/subscription-form.html.php';

		$content = ob_get_clean();

		return $content;

	}

	/**
	 * @param $subscription_id
	 *
	 * @return void
	 */
	public function sama_update_paypal_subscription( $subscription_id ) {
		global $wpdb;
		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';

		$result = $wpdb->query(
			$wpdb->prepare(
				'UPDATE ' . $tablename_subscription . ' SET
			is_payment_received =%s WHERE user_id=%d',
				1,
				$subscription_id
			)
		);
	}

}

new SAMA_Subscriptions();
