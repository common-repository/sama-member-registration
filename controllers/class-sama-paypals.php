<?php

new Sama_Paypals();

/**
 *
 */
class Sama_Paypals {


	/**
	 *
	 */
	public function __construct() {

		$this->sama_public_hooks();

	}

	/**
	 * @return void
	 */
	public function sama_public_hooks() {

		add_action( 'rest_api_init', array( $this, 'sama_paypal_v1' ) );
	}

	/**
	 * @return void
	 */
	public function sama_paypal_v1() {
		register_rest_route(
			'sama/v1',
			'/notify',
			array(
				'methods'             => array( 'POST' ),
				'permission_callback' => '__return_true',
				'callback'            => array( $this, 'sama_paypal_notify' ),
			)
		);
	}

	/**
	 * @param $data
	 *
	 * @return int
	 */
	public function sama_paypal_notify( $data ) {
		$subscription_id = $data['subscription_id'];
		$invoice         = $data['invoice'];
		$_member         = new SAMA_Member();

		$member_data = $_member->sama_get_from_invoice_id( $invoice );

		$user_id    = (int) $member_data->user_id;
		$m_id       = (int) $member_data->m_id;
		$u          = new WP_User( $user_id );
		$user_login = $u->data->user_login;
		// Remove role
		$u->remove_role( 'subscriber' );
		// Add role
		$u->add_role( 'member' );

		$u->add_cap( 'member_summary' );
		$memberData = $_member->sama_update_paypal_subscription( $invoice, $m_id );
		$_email     = new Sama_Email();
		$password   = wp_generate_password( $length = 12, $include_standard_special_chars = false );
		wp_set_password( $password, $u->ID );
		$options = array(
			'emailSubject'    => 'sama_paypal_paid_email_subject',
			'emailBody'       => 'sama_paypal_paid_email_body',
			'subscription_id' => 0,
			'user_id'         => $user_id,
			'id'              => $m_id,
			'password'        => $password,
			'user_name'       => $user_login,
		);
		$_email->sama_send_payapal_paid_email( $options );

		return 1;
	}


}
