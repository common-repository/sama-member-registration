<?php

class Sama_Dashboards {


	/**
	 * @return void
	 */
	public static function init() {
		global $wpdb;
		register_setting( 'sama-settings', 'sama_currency_symbol' );
		register_setting( 'sama-settings', 'sama_from_email_address' );
		register_setting( 'sama-settings', 'sama_from_name' );
		register_setting( 'sama-settings', 'sama_member_accept_email_subject' );
		register_setting( 'sama-settings', 'sama_member_accept_email_body' );

		register_setting( 'sama-settings', 'sama_member_suspend_email_subject' );
		register_setting( 'sama-settings', 'sama_member_suspend_email_body' );

		register_setting( 'sama-settings', 'sama_member_subscription_email_subject' );
		register_setting( 'sama-settings', 'sama_member_subscription_email_body' );

		register_setting( 'sama-settings', 'sama_paypal_email' );
		register_setting( 'sama-settings', 'sama_paypal_is_live' );
		register_setting( 'sama-settings', 'sama_yearly_subscription_amount' );
		register_setting( 'sama-settings', 'sama_monthly_subscription_amount' );
		register_setting( 'sama-settings', 'sama_new_member_registration_cc_email_address' );
		register_setting( 'sama-settings', 'sama_new_member_welcome_email_subject' );
		register_setting( 'sama-settings', 'sama_new_member_welcome_email_body' );

		register_setting( 'sama-settings', 'sama_new_member_welcome_message_web' );
		register_setting( 'sama-settings', 'sama_new_member_exist_message_web' );
		register_setting( 'sama-settings', 'sama_no_of_rows' );

		register_setting( 'sama-settings', 'sama_notify_url' );
		register_setting( 'sama-settings', 'sama_cancel_return_url' );
		register_setting( 'sama-settings', 'sama_return_url' );

		register_setting( 'sama-settings', 'sama_member_reject_email_subject' );
		register_setting( 'sama-settings', 'sama_member_reject_email_body' );

		register_setting( 'sama-settings', 'sama_exist_member_welcome_email_subject' );
		register_setting( 'sama-settings', 'sama_exist_member_welcome_email_body' );

		register_setting( 'sama-settings', 'sama_new_member_admin_welcome_email_subject' );
		register_setting( 'sama-settings', 'sama_new_member_admin_welcome_email_body' );

		register_setting( 'sama-settings', 'sama_paypal_paid_email_subject' );
		register_setting( 'sama-settings', 'sama_paypal_paid_email_body' );

		register_setting( 'sama-settings', 'sama_early_reminder_1' );
		register_setting( 'sama-settings', 'sama_early_reminder_2' );
		register_setting( 'sama-settings', 'sama_early_reminder_3' );
		register_setting( 'sama-settings', 'sama_early_reminder_4' );
		register_setting( 'sama-settings', 'sama_early_reminder_5' );

		register_setting( 'sama-settings', 'sama_early_reminder_1_email_subject' );
		register_setting( 'sama-settings', 'sama_early_reminder_2_email_subject' );
		register_setting( 'sama-settings', 'sama_early_reminder_3_email_subject' );
		register_setting( 'sama-settings', 'sama_early_reminder_4_email_subject' );
		register_setting( 'sama-settings', 'sama_early_reminder_5_email_subject' );

		register_setting( 'sama-settings', 'sama_early_reminder_1_email_body' );
		register_setting( 'sama-settings', 'sama_early_reminder_2_email_body' );
		register_setting( 'sama-settings', 'sama_early_reminder_3_email_body' );
		register_setting( 'sama-settings', 'sama_early_reminder_4_email_body' );
		register_setting( 'sama-settings', 'sama_early_reminder_5_email_body' );

		register_setting( 'sama-settings', 'sama_member_expiration_days' );
		register_setting( 'sama-settings', 'sama_paypal_payment_page' );
	}


	/**
	 * @return void
	 */
	public static function options() {
		include SAMA_PATH . '/views/setting.php';
	}


	/**
	 * @return void
	 */
	public static function menu() {
		$sama_admin_caps   = current_user_can( 'member_setting' ) ? 'member_setting' : 'member_setting';
		$sama_memmber_caps = current_user_can( 'member_summary' ) ? 'member_summary' : 'member_summary';

		if ( current_user_can( 'member_summary' ) ) :
			add_menu_page(
				'SAMA',
				'My account',
				$sama_memmber_caps,
				'SAMA_Member',
				array( __CLASS__, 'sama_dashboard_view_member' ),
				SAMA_URL_PATH . 'assets/icon.png'
			);
		endif;
		if ( current_user_can( 'member_setting' ) ) :

			add_menu_page(
				'SAMA',
				'Members',
				$sama_admin_caps,
				'sama_options',
				array( __CLASS__, 'sama_dashboard_view_admin' ),
				SAMA_URL_PATH . 'assets/icon.png'
			);

			add_submenu_page(
				'sama_options',
				'Dashboard',
				'Dashboard',
				$sama_admin_caps,
				'sama_options',
				array( __CLASS__, 'sama_dashboard_view_admin' )
			);

			add_submenu_page(
				'sama_options',
				'Setting',
				'Setting',
				$sama_admin_caps,
				'sama_settings',
				array( __CLASS__, 'options' )
			);

			add_submenu_page(
				'sama_options',
				'Members list',
				'Members list',
				$sama_admin_caps,
				'sama_members',
				array(
					'SAMA_Members',
					'sama_manage',
				)
			);

		endif;
	}

	/**
	 * @return void
	 */
	public static function sama_dashboard_view_member() {
		$_member       = new SAMA_Member();
		$_subscription = new SAMA_SAMA_Subscription();
		$user_ID       = get_current_user_id();
		$member        = $_member->sama_get_from_user_id( $user_ID );
		if ( ! empty( $member ) ) {
			$subscriptions = $_subscription->find( $member->id );
			include SAMA_PATH . '/views/member/member-dashboard.php';
		} else {
			echo 'You are not in our member list. Please contact admin';
		}
	}

	/**
	 * @return void
	 */
	public static function sama_dashboard_view_admin() {
		$_member         = new SAMA_Member();
		$subscriptions   = $_member->sama_dashboard_stats();
		$summary         = $_member->sama_dashboard_summary_stats();
		$new_members     = $_member->sama_dashboard_summary_new_member();
		$accept_members  = $_member->sama_dashboard_summary_accept_member();
		$reject_members  = $_member->sama_dashboard_summary_reject_member();
		$pending_members = $_member->sama_dashboard_summary_payment_pending_member();

		$suspend_members = $_member->sama_dashboard_summary_suspend_member();

		include SAMA_PATH . '/views/dashboard.php';
	}
}
